<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use App\Models\Patient;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;

    public function form(Schema $schema): Schema
    {
        return PatientResource::form($schema);
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Log the incoming data for debugging
        Log::info('Patient creation data:', $data);

        // Create patient
        $patient = Patient::create($data);

        return $patient;
    }
}
