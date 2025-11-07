<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpense extends EditRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getValidationRules(): array
    {
        // Override validation to allow any string for expense_type
        $rules = parent::getValidationRules();
        
        // Allow any string for expense_type (not just from options list)
        $rules['expense_type'] = ['required', 'string', 'max:255'];
        
        return $rules;
    }
}

