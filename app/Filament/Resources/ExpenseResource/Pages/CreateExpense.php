<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Auto-fill recorded_by if not set
        if (empty($data['recorded_by'])) {
            $data['recorded_by'] = Auth::user()?->name ?? 'System';
        }

        return $data;
    }
}

