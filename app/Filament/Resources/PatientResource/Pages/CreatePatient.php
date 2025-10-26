<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Enums\DentalTreatment;
use App\Enums\ToothNumber;
use App\Filament\Resources\PatientResource;
use App\Http\Requests\StorePatientWithTreatmentRequest;
use App\Models\Patient;
use App\Models\Treatment;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;

    public function form(Schema $schema): Schema
    {
        return PatientResource::form($schema)->schema([
            ...PatientResource::form($schema)->getComponents(),
            Section::make('Treatment Information')
                ->columns(2)
                ->schema([
                    Select::make('treatment_types')
                        ->label('Treatment Types')
                        ->options(array_combine(DentalTreatment::values(), DentalTreatment::values()))
                        ->multiple()
                        ->required(),
                    Select::make('tooth_numbers')
                        ->label('Tooth Numbers')
                        ->options(array_combine(ToothNumber::values(), ToothNumber::values()))
                        ->multiple()
                        ->required(),
                    DatePicker::make('treatment_date')
                        ->label('Treatment Date')
                        ->required(),
                    Textarea::make('treatment_description')
                        ->label('Treatment Description')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Validate the data using our request rules
        $rules = (new StorePatientWithTreatmentRequest())->rules();
        Validator::make($data, $rules)->validate();

        // Extract treatment data
        $treatmentData = [
            'treatment_types' => $data['treatment_types'] ?? [],
            'tooth_numbers' => $data['tooth_numbers'] ?? [],
            'treatment_date' => $data['treatment_date'] ?? null,
            'treatment_description' => $data['treatment_description'] ?? null,
        ];

        // Remove treatment data from patient data
        unset($data['treatment_types'], $data['tooth_numbers'], $data['treatment_date'], $data['treatment_description']);

        // Create patient
        $patient = Patient::create($data);

        // Create treatment if treatment data exists
        if (!empty($treatmentData['treatment_types']) || !empty($treatmentData['tooth_numbers'])) {
            Treatment::create(array_merge($treatmentData, ['patient_id' => $patient->register_id]));
        }

        return $patient;
    }
}
