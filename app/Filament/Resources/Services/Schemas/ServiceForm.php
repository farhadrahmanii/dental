<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Helpers\CurrencyHelper;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Basic Information')
                    ->description('Enter the basic service information')
                    ->schema([
                        TextInput::make('name_en')
                            ->label('Service Name (English)')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Dental Cleaning')
                            ->helperText('Enter the service name in English'),
                        
                        TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->prefix(CurrencyHelper::prefix())
                            ->required()
                            ->step(0.01)
                            ->placeholder('0.00')
                            ->helperText('Enter the service price in Afghani'),
                        
                        Textarea::make('description_en')
                            ->label('Description (English)')
                            ->rows(3)
                            ->placeholder('Enter a detailed description of the service')
                            ->helperText('Provide a clear description of what this service includes'),
                        
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
                            ->required()
                            ->searchable()
                            ->placeholder('Select a category')
                            ->helperText('Choose the appropriate service category'),
                        
                        Toggle::make('is_active')
                            ->label('Service Active')
                            ->default(true)
                            ->helperText('Toggle to enable/disable this service'),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Section::make('Multilingual Support')
                    ->description('Add translations for Pashto and Dari languages')
                    ->schema([
                        TextInput::make('name_ps')
                            ->label('Service Name (Pashto)')
                            ->maxLength(255)
                            ->placeholder('د غاښونو پاکول')
                            ->helperText('Enter the service name in Pashto'),
                        
                        TextInput::make('name_fa')
                            ->label('Service Name (Dari)')
                            ->maxLength(255)
                            ->placeholder('تمیز کردن دندان')
                            ->helperText('Enter the service name in Dari'),
                        
                        Textarea::make('description_ps')
                            ->label('Description (Pashto)')
                            ->rows(3)
                            ->placeholder('د دغه خدمت تفصیلات ولیکئ')
                            ->helperText('Provide description in Pashto'),
                        
                        Textarea::make('description_fa')
                            ->label('Description (Dari)')
                            ->rows(3)
                            ->placeholder('توضیحات این خدمت را بنویسید')
                            ->helperText('Provide description in Dari'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}