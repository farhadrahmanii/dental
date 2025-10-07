<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Schemas\Schema;
use App\Helpers\CurrencyHelper;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name_en')
                    ->label('Service Name (English)')
                    ->required()
                    ->maxLength(255),
                
                Textarea::make('description_en')
                    ->label('Description (English)')
                    ->rows(3)
                    ->columnSpanFull(),
                
                TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->prefix(CurrencyHelper::prefix())
                    ->required(),
                
                Select::make('category')
                    ->label('Category')
                    ->options([
                        'preventive' => 'Preventive Care',
                        'restorative' => 'Restorative',
                        'cosmetic' => 'Cosmetic',
                        'surgical' => 'Surgical',
                        'diagnostic' => 'Diagnostic',
                        'anesthesia' => 'Anesthesia',
                    ])
                    ->required(),
                
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                
                TextInput::make('name_ps')
                    ->label('Service Name (Pashto)')
                    ->maxLength(255),
                
                Textarea::make('description_ps')
                    ->label('Description (Pashto)')
                    ->rows(3)
                    ->columnSpanFull(),
                
                TextInput::make('name_fa')
                    ->label('Service Name (Farsi)')
                    ->maxLength(255),
                
                Textarea::make('description_fa')
                    ->label('Description (Farsi)')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}