<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $patients = Patient::all();
        return response()->json($patients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request): JsonResponse
    {
        $patient = Patient::create($request->validated());

        return response()->json($patient, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient): JsonResponse
    {
        return response()->json($patient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient): JsonResponse
    {
        $patient->update($request->validated());

        return response()->json($patient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();

        return response()->json(null, 204);
    }
}
