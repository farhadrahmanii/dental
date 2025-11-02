<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;

use App\Models\Patient;
use App\Models\Service;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->ensureServiceName($data);
        $data['patient_id'] = $this->resolvePatientId($data);

        return $data;
    }

    protected function ensureServiceName(array $data): array
    {
        if (blank($data['service_name'] ?? null) && filled($data['service_id'] ?? null)) {
            $service = Service::find($data['service_id']);
            $data['service_name'] = $service?->name ?? $data['service_name'] ?? '';
        }

        return $data;
    }

    protected function resolvePatientId(array $data): ?string
    {
        if (filled($data['patient_id'] ?? null)) {
            return $data['patient_id'];
        }

        if (blank($data['patient_phone'] ?? null) || blank($data['patient_name'] ?? null)) {
            return null;
        }

        $patient = Patient::firstOrCreate(
            ['phone_number' => $data['patient_phone']],
            [
                'name' => $data['patient_name'],
                'doctor_name' => Auth::user()?->name ?? 'Unassigned',
            ]
        );

        if (!$patient->wasRecentlyCreated && $patient->name !== $data['patient_name']) {
            $patient->update(['name' => $data['patient_name']]);
        }

        return $patient->register_id;
    }
}

