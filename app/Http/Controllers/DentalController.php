<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class DentalController extends Controller
{
    public function home()
    {
        $recentPatients = Patient::latest()->take(6)->get();
        $totalPatients = Patient::count();
        
        return view('dental.home', compact('recentPatients', 'totalPatients'));
    }

    public function about()
    {
        return view('dental.about');
    }

    public function services()
    {
        return view('dental.services');
    }

    public function contact()
    {
        return view('dental.contact');
    }

    public function patients(Request $request)
    {
        $query = Patient::withCount(['treatments', 'payments', 'xrays', 'invoices']);

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('father_name', 'like', "%{$searchTerm}%")
                  ->orWhere('register_id', 'like', "%{$searchTerm}%")
                  ->orWhere('phone_number', 'like', "%{$searchTerm}%")
                  ->orWhere('doctor_name', 'like', "%{$searchTerm}%")
                  ->orWhere('treatment', 'like', "%{$searchTerm}%")
                  ->orWhere('x_ray_id', 'like', "%{$searchTerm}%")
                  ->orWhere('occupation', 'like', "%{$searchTerm}%");
            });
        }

        // Gender filter
        if ($request->filled('gender')) {
            $query->where('sex', $request->get('gender'));
        }

        // Age range filter
        if ($request->filled('age_min')) {
            $query->where('age', '>=', $request->get('age_min'));
        }
        if ($request->filled('age_max')) {
            $query->where('age', '<=', $request->get('age_max'));
        }

        // Marital status filter
        if ($request->filled('marital_status')) {
            $query->where('marital_status', $request->get('marital_status'));
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Doctor filter
        if ($request->filled('doctor')) {
            $query->where('doctor_name', $request->get('doctor'));
        }

        // Treatment filter
        if ($request->filled('treatment')) {
            $query->where('treatment', $request->get('treatment'));
        }

        // Quick filters
        if ($request->filled('quick_filter')) {
            switch ($request->get('quick_filter')) {
                case 'with_treatments':
                    $query->has('treatments');
                    break;
                case 'with_xrays':
                    $query->has('xrays');
                    break;
                case 'with_payments':
                    $query->has('payments');
                    break;
                case 'recent':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'no_treatments':
                    $query->doesntHave('treatments');
                    break;
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['name', 'age', 'created_at', 'register_id'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->latest();
        }

        $patients = $query->paginate(12)->withQueryString();

        // Get statistics
        $stats = [
            'total' => Patient::count(),
            'male' => Patient::where('sex', 'male')->count(),
            'female' => Patient::where('sex', 'female')->count(),
            'with_treatments' => Patient::has('treatments')->count(),
            'with_xrays' => Patient::has('xrays')->count(),
            'with_payments' => Patient::has('payments')->count(),
            'recent' => Patient::where('created_at', '>=', now()->subDays(30))->count(),
            'total_treatments' => \App\Models\Treatment::count(),
            'total_payments' => \App\Models\Payment::sum('amount'),
        ];

        // Get unique doctors for filter dropdown
        $doctors = Patient::whereNotNull('doctor_name')
            ->where('doctor_name', '!=', 'Unassigned')
            ->distinct()
            ->pluck('doctor_name')
            ->filter()
            ->sort()
            ->values();

        // Get unique treatments for filter dropdown
        $treatments = Patient::whereNotNull('treatment')
            ->distinct()
            ->pluck('treatment')
            ->filter()
            ->sort()
            ->values();
        
        return view('dental.patients', compact('patients', 'stats', 'doctors', 'treatments'));
    }

    public function patientDetail($id)
    {
        $patient = Patient::with([
            'xrays',
            'treatments.service',
            'payments',
            'invoices',
        ])->findOrFail($id);

        return view('dental.patient-detail', compact('patient'));
    }
}

