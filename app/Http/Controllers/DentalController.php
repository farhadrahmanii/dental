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

    public function patients()
    {
        $patients = Patient::latest()->paginate(12);
        return view('dental.patients', compact('patients'));
    }

    public function patientDetail($id)
    {
        $patient = Patient::findOrFail($id);
        return view('dental.patient-detail', compact('patient'));
    }
}

