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
            'patient_email' => 'nullable|email|max:255',
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

        $patientId = $request->input('patient_id');

        if (blank($patientId)) {
            $patient = Patient::firstOrCreate(
                ['phone_number' => $request->patient_phone],
                [
                    'name' => $request->patient_name,
                    'doctor_name' => Auth::user()?->name ?? 'Unassigned',
                ]
            );

            if (!$patient->wasRecentlyCreated) {
                $patient->update(['name' => $request->patient_name]);
            }

            $patientId = $patient->register_id;
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
            'patient_phone' => $request->patient_phone,
            'service_id' => $request->service_id,
            'service_name' => $request->service_name,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'message' => $request->message,
            'patient_id' => $request->patient_id,
            'patient_email' => $request->input('patient_email'),
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
            'patient_email' => 'nullable|email|max:255',
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

    public function searchPatient(Request $request)
    {
        $search = $request->input('query');
        
        if (empty($search)) {
            return response()->json([
                'success' => false,
                'message' => 'Search query is required'
            ], 400);
        }

        // Search by name, phone, email, or appointment number
        $patient = Patient::where('name', 'like', "%{$search}%")
            ->orWhere('phone_number', 'like', "%{$search}%")
            ->orWhere('register_id', $search)
            ->first();

        // Also search by appointment number
        $appointment = null;
        if (!$patient) {
            $appointment = Appointment::where('appointment_number', $search)
                ->with('patient')
                ->first();
            
            if ($appointment && $appointment->patient) {
                $patient = $appointment->patient;
            }
        }

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        // Load patient history
        $patient->load([
            'appointments' => function ($query) {
                $query->with(['service', 'payments'])
                      ->orderBy('appointment_date', 'desc')
                      ->orderBy('appointment_time', 'desc');
            },
            'payments' => function ($query) {
                $query->with(['service', 'appointment'])
                      ->orderBy('payment_date', 'desc');
            }
        ]);

        // Get aggregated data
        $totalAppointments = $patient->appointments->count();
        $completedAppointments = $patient->appointments->where('status', 'completed')->count();
        $totalSpent = $patient->payments->sum('amount');
        $lastVisit = $patient->appointments->where('status', 'completed')->first();

        return response()->json([
            'success' => true,
            'patient' => [
                'id' => $patient->register_id,
                'name' => $patient->name,
                'phone' => $patient->phone_number,
                'email' => $patient->appointments->first()->patient_email ?? null,
                'age' => $patient->age,
                'sex' => $patient->sex,
                'doctor' => $patient->doctor_name,
                'diagnosis' => $patient->diagnosis,
            ],
            'statistics' => [
                'total_appointments' => $totalAppointments,
                'completed_appointments' => $completedAppointments,
                'total_spent' => number_format($totalSpent, 2),
                'last_visit' => $lastVisit ? $lastVisit->formatted_date : 'N/A',
            ],
            'appointments' => $patient->appointments->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'appointment_number' => $appointment->appointment_number,
                    'date' => $appointment->formatted_date,
                    'time' => $appointment->formatted_time,
                    'service' => $appointment->service_name,
                    'status' => $appointment->status,
                    'notes' => $appointment->notes,
                    'payment_amount' => $appointment->payments->sum('amount'),
                ];
            }),
            'services' => $patient->payments->map(function ($payment) {
                return [
                    'service' => $payment->service?->name ?? $payment->appointment?->service_name ?? 'N/A',
                    'amount' => number_format($payment->amount, 2),
                    'date' => $payment->payment_date->format('M d, Y'),
                    'appointment_number' => $payment->appointment?->appointment_number ?? 'N/A',
                ];
            }),
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
