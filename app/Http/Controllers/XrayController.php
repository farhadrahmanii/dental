<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Xray;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class XrayController extends Controller
{
    public function create(string $register_id)
    {
        $patient = Patient::where('register_id', $register_id)->firstOrFail();

        return view('xrays.create', [
            'patient' => $patient,
            'register_id' => $register_id,
        ]);
    }

    public function store(Request $request, string $register_id)
    {
        $patient = Patient::where('register_id', $register_id)->firstOrFail();

        $validated = $request->validate([
            'xray_image' => ['required', 'file', 'image', 'max:5120'],
            'treatment' => ['required', 'string', 'max:255'],
            'doctor_name' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        $path = null;
        if ($request->hasFile('xray_image')) {
            $path = $request->file('xray_image')->store('xrays', 'public');
        }

        $xray = Xray::create([
            'xray_id' => 'XR-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6)),
            'patient_id' => $patient->register_id,
            'xray_image' => $path,
            'treatment' => $validated['treatment'],
            'doctor_name' => $validated['doctor_name'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect('/admin/patients')
            ->with('status', 'X-ray added successfully.');
    }
}


