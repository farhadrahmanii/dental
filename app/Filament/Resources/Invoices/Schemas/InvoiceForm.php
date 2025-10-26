<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Patient;
use App\Models\Service;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('patient_id')
                    ->label('Patient')
                    ->relationship('patient', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->placeholder('Select a patient'),

                DatePicker::make('invoice_date')
                    ->label('Invoice Date')
                    ->required()
                    ->default(now())
                    ->displayFormat('M d, Y'),

                DatePicker::make('due_date')
                    ->label('Due Date')
                    ->required()
                    ->default(now()->addDays(30))
                    ->displayFormat('M d, Y'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('draft'),

                Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3)
                    ->placeholder('Additional notes about this invoice')
                    ->columnSpanFull(),
            ]);
    }
}
