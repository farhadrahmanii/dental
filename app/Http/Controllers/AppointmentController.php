<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['service', 'patient', 'createdBy'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(20);

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $services = Service::active()->get();
        $patients = Patient::orderBy('name')->get();
        
        return view('appointments.create', compact('services', 'patients'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'required|email|max:255',
            'patient_phone' => 'required|string|max:20',
            'service_id' => 'nullable|exists:services,id',
            'service_name' => 'required|string|max:255',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'message' => 'nullable|string|max:1000',
            'patient_id' => 'nullable|exists:patients,register_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if the time slot is available
        $isAvailable = $this->isTimeSlotAvailable($request->appointment_date, $request->appointment_time);
        
        if (!$isAvailable) {
            return response()->json([
                'success' => false,
                'message' => 'This time slot is not available. Please choose another time.'
            ], 409);
        }

        $appointment = Appointment::create([
            'patient_name' => $request->patient_name,
            'patient_email' => $request->patient_email,
            'patient_phone' => $request->patient_phone,
            'service_id' => $request->service_id,
            'service_name' => $request->service_name,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'message' => $request->message,
            'patient_id' => $request->patient_id,
            'created_by' => Auth::id(),
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment booked successfully!',
            'appointment' => $appointment,
            'appointment_number' => $appointment->appointment_number
        ]);
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['service', 'patient', 'createdBy']);
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $services = Service::active()->get();
        $patients = Patient::orderBy('name')->get();
        
        return view('appointments.edit', compact('appointment', 'services', 'patients'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'required|email|max:255',
            'patient_phone' => 'required|string|max:20',
            'service_id' => 'nullable|exists:services,id',
            'service_name' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'status' => 'required|in:pending,confirmed,completed,cancelled,no_show',
            'message' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'patient_id' => 'nullable|exists:patients,register_id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check availability if date/time changed
        if ($appointment->appointment_date != $request->appointment_date || 
            $appointment->appointment_time != $request->appointment_time) {
            
            $isAvailable = $this->isTimeSlotAvailable($request->appointment_date, $request->appointment_time, $appointment->id);
            
            if (!$isAvailable) {
                return back()->with('error', 'This time slot is not available. Please choose another time.');
            }
        }

        $appointment->update($request->all());

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Appointment updated successfully!');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        
        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully!');
    }

    public function getAvailableTimeSlots(Request $request)
    {
        $date = $request->input('date');
        
        if (!$date) {
            return response()->json([
                'success' => false,
                'message' => 'Date is required'
            ], 400);
        }

        $availableSlots = $this->generateAvailableTimeSlots($date);
        
        return response()->json([
            'success' => true,
            'available_slots' => $availableSlots
        ]);
    }

    public function getServices()
    {
        $services = Service::active()->get(['id', 'name', 'description', 'price']);
        
        return response()->json([
            'success' => true,
            'services' => $services
        ]);
    }

    private function isTimeSlotAvailable($date, $time, $excludeAppointmentId = null)
    {
        $query = Appointment::where('appointment_date', $date)
            ->where('appointment_time', $time)
            ->where('status', '!=', 'cancelled');

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->count() === 0;
    }

    private function generateAvailableTimeSlots($date)
    {
        $dateObj = Carbon::parse($date);
        
        // Skip past dates
        if ($dateObj->isPast() && !$dateObj->isToday()) {
            return [];
        }

        // Define working hours (9 AM to 5 PM)
        $workingHours = [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30',
            '15:00', '15:30', '16:00', '16:30', '17:00'
        ];

        // Get booked slots for the date
        $bookedSlots = Appointment::where('appointment_date', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('appointment_time')
            ->toArray();

        // Filter out booked slots
        $availableSlots = array_filter($workingHours, function($slot) use ($bookedSlots, $dateObj) {
            // Skip past times for today
            if ($dateObj->isToday()) {
                $slotTime = Carbon::parse($date . ' ' . $slot);
                if ($slotTime->isPast()) {
                    return false;
                }
            }
            
            return !in_array($slot, $bookedSlots);
        });

        // Format slots for frontend
        return array_map(function($slot) {
            return [
                'value' => $slot,
                'label' => Carbon::parse($slot)->format('g:i A')
            ];
        }, array_values($availableSlots));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,completed,cancelled,no_show'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status',
                'errors' => $validator->errors()
            ], 422);
        }

        $appointment->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment status updated successfully',
            'appointment' => $appointment
        ]);
    }

    public function calendar(Request $request)
    {
        $startDate = $request->input('start', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end', now()->endOfMonth()->toDateString());

        $appointments = Appointment::with(['service', 'patient'])
            ->byDateRange($startDate, $endDate)
            ->get();

        return response()->json([
            'success' => true,
            'appointments' => $appointments
        ]);
    }
}
