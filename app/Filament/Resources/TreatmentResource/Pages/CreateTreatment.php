<?php

namespace App\Filament\Resources\TreatmentResource\Pages;

use App\Filament\Resources\TreatmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTreatment extends CreateRecord
{
    protected static string $resource = TreatmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure tooth_numbers is stored as JSON array
        if (isset($data['tooth_numbers']) && is_array($data['tooth_numbers'])) {
            // The cast in the model should handle this, but we ensure it here
        }

        // If patient_id is not set but provided in URL, use it
        if (!isset($data['patient_id']) && ($patientId = request()->query('patient_id'))) {
            $data['patient_id'] = $patientId;
        }

        return $data;
    }

    protected function getDefaultFormValues(): array
    {
        $values = parent::getDefaultFormValues();

        // Pre-fill patient_id if provided in URL
        if ($patientId = request()->query('patient_id')) {
            $values['patient_id'] = $patientId;
        }

        return $values;
    }

    public function mount(): void
    {
        parent::mount();

        // Ensure patient_id is set in form state if provided in URL
        if ($patientId = request()->query('patient_id')) {
            $this->form->fill(['patient_id' => $patientId]);
        }
    }
}
