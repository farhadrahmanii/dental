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
        $query = Patient::query();

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('father_name', 'like', "%{$searchTerm}%")
                  ->orWhere('register_id', 'like', "%{$searchTerm}%")
                  ->orWhere('doctor_name', 'like', "%{$searchTerm}%")
                  ->orWhere('treatment', 'like', "%{$searchTerm}%")
                  ->orWhere('x_ray_id', 'like', "%{$searchTerm}%");
            });
        }

        // Doctor filter
        if ($request->filled('doctor')) {
            $query->where('doctor_name', $request->get('doctor'));
        }

        // Treatment filter
        if ($request->filled('treatment')) {
            $query->where('treatment', $request->get('treatment'));
        }

        $patients = $query->latest()->paginate(12)->withQueryString();
        
        return view('dental.patients', compact('patients'));
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

