<?php

namespace App\Http\Controllers\Api;

use App\Enums\DentalTreatment;
use App\Enums\ToothNumber;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use App\Models\Treatment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $patients = Patient::with('treatments')->get();
        return response()->json($patients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Validate patient data
        $patientData = $request->validate((new StorePatientRequest())->rules());

        $patient = Patient::create($patientData);

        // Handle treatment data if provided
        if ($request->has('treatment_types') || $request->has('tooth_numbers')) {
            $treatmentData = $request->only([
                'treatment_types',
                'tooth_numbers',
                'treatment_date',
                'treatment_description'
            ]);

            // Validate treatment data
            $treatmentRules = [
                'treatment_types' => 'nullable|array',
                'treatment_types.*' => 'in:' . implode(',', DentalTreatment::values()),
                'tooth_numbers' => 'nullable|array',
                'tooth_numbers.*' => 'in:' . implode(',', ToothNumber::values()),
                'treatment_date' => 'nullable|date',
                'treatment_description' => 'nullable|string',
            ];

            Validator::make($treatmentData, $treatmentRules)->validate();

            // Create treatment if data exists
            if (!empty($treatmentData['treatment_types']) || !empty($treatmentData['tooth_numbers'])) {
                Treatment::create(array_merge($treatmentData, ['patient_id' => $patient->register_id]));
            }
        }

        return response()->json($patient->load('treatments'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient): JsonResponse
    {
        return response()->json($patient->load('treatments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient): JsonResponse
    {
        $patient->update($request->validated());

        return response()->json($patient->load('treatments'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();

        return response()->json(null, 204);
    }

    /**
     * Add a new treatment for a patient
     */
    public function addTreatment(Request $request, Patient $patient): JsonResponse
    {
        $data = $request->validate([
            'treatment_types' => 'required|array',
            'treatment_types.*' => 'in:' . implode(',', DentalTreatment::values()),
            'tooth_numbers' => 'nullable|array',
            'tooth_numbers.*' => 'in:' . implode(',', ToothNumber::values()),
            'treatment_date' => 'required|date',
            'treatment_description' => 'nullable|string',
        ]);

        $treatment = Treatment::create(array_merge($data, ['patient_id' => $patient->register_id]));

        return response()->json($treatment, 201);
    }
}
