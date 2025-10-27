<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Enums\DentalTreatment;
use App\Enums\ToothNumber;
use App\Filament\Resources\PatientResource;
use App\Http\Requests\StorePatientWithTreatmentRequest;
use App\Models\Patient;
use App\Models\Treatment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
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
                        ->required()
                        ->searchable()
                        ->preload(),
                    Select::make('tooth_numbers')
                        ->label('Tooth Numbers')
                        ->options(array_combine(ToothNumber::values(), ToothNumber::values()))
                        ->multiple()
                        ->required()
                        ->searchable()
                        ->preload(),
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
        // Log the incoming data for debugging
        Log::info('Patient creation data:', $data);

        // Validate the data using our request rules
        $rules = (new StorePatientWithTreatmentRequest())->rules();
        Validator::make($data, $rules)->validate();

        // Extract treatment data
        $treatmentTypes = $this->ensureArray($data['treatment_types'] ?? []);
        $toothNumbers = $this->ensureArray($data['tooth_numbers'] ?? []);
        $treatmentDate = $data['treatment_date'] ?? null;
        $treatmentDescription = $data['treatment_description'] ?? null;

        // Log the extracted treatment data
        Log::info('Extracted treatment data:', [
            'treatment_types' => $treatmentTypes,
            'tooth_numbers' => $toothNumbers,
            'treatment_date' => $treatmentDate,
            'treatment_description' => $treatmentDescription,
        ]);

        // Remove treatment data from patient data
        unset($data['treatment_types'], $data['tooth_numbers'], $data['treatment_date'], $data['treatment_description']);

        // Create patient
        $patient = Patient::create($data);

        // Create treatment records
        if (!empty($treatmentTypes) && !empty($toothNumbers)) {
            // Create a treatment record for each combination of treatment type and tooth number
            foreach ($treatmentTypes as $treatmentType) {
                foreach ($toothNumbers as $toothNumber) {
                    $treatmentData = [
                        'patient_id' => $patient->register_id,
                        'treatment_types' => [$treatmentType],  // Array format for JSON column
                        'tooth_numbers' => [$toothNumber],       // Array format for JSON column
                        'treatment_date' => $treatmentDate,
                        'treatment_description' => $treatmentDescription,
                    ];

                    $treatment = Treatment::create($treatmentData);
                    Log::info('Created treatment:', $treatment->toArray());
                }
            }
        } else if (!empty($treatmentTypes) || !empty($toothNumbers)) {
            // Handle cases where only treatment types or only tooth numbers are provided
            $treatmentData = [
                'patient_id' => $patient->register_id,
                'treatment_types' => $treatmentTypes,    // Already in array format
                'tooth_numbers' => $toothNumbers,        // Already in array format
                'treatment_date' => $treatmentDate,
                'treatment_description' => $treatmentDescription,
            ];

            $treatment = Treatment::create($treatmentData);
            Log::info('Created treatment:', $treatment->toArray());
        }

        return $patient;
    }

    /**
     * Ensure data is an array
     */
    private function ensureArray($value): array
    {
        // If empty, return empty array
        if (empty($value) && $value !== '0') {
            return [];
        }

        // If already an array, return it
        if (is_array($value)) {
            return $value;
        }

        // If it's a string that might be JSON, try to decode it
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
            // If not JSON, return as single item array
            return [$value];
        }

        // For any other type, wrap in array
        return [$value];
    }
}
