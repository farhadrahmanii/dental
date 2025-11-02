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

        // Ensure expense_type is trimmed and not empty
        if (isset($data['expense_type'])) {
            $data['expense_type'] = trim($data['expense_type']);
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getValidationRules(): array
    {
        // Override validation to allow any string for expense_type
        $rules = parent::getValidationRules();
        
        // Allow any string for expense_type (not just from options list)
        // This overrides Filament's default Select validation that checks against options
        $rules['expense_type'] = ['required', 'string', 'max:255'];
        
        return $rules;
    }

    protected function mutateFormDataBeforeValidate(array $data): array
    {
        // Ensure expense_type is trimmed before validation
        if (isset($data['expense_type'])) {
            $data['expense_type'] = trim($data['expense_type']);
        }
        
        return $data;
    }
}

