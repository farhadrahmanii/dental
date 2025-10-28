<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use App\Filament\Resources\TreatmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPatients extends ListRecords
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Actions\Action::make('add_treatment')
                ->label('Add Treatment')
                ->icon('heroicon-o-beaker')
                ->url(fn ($record) => TreatmentResource::getUrl('create', ['patient_id' => $record->register_id])),
        ];
    }
}
