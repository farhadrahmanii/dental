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
                Section::make(__('filament.basic_information'))
                    ->description(__('filament.enter_basic_service_information'))
                    ->schema([
                        TextInput::make('name_en')
                            ->label(__('filament.service_name_english'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.service_name_english_placeholder')),

                        TextInput::make('price')
                            ->label(__('filament.price'))
                            ->numeric()
                            ->prefix(CurrencyHelper::prefix())
                            ->required()
                            ->step(0.01)
                            ->placeholder('0.00'),

                        Textarea::make('description_en')
                            ->label(__('filament.description_english'))
                            ->rows(3)
                            ->placeholder(__('filament.enter_detailed_description')),

                        Select::make('category')
                            ->label(__('filament.category'))
                            ->options([
                                'preventive' => __('filament.preventive_care'),
                                'restorative' => __('filament.restorative'),
                                'cosmetic' => __('filament.cosmetic'),
                                'surgical' => __('filament.surgical'),
                                'diagnostic' => __('filament.diagnostic'),
                                'anesthesia' => __('filament.anesthesia'),
                            ])
                            ->required()
                            ->searchable()
                            ->placeholder(__('filament.select_category')),

                        Toggle::make('is_active')
                            ->label(__('filament.service_active'))
                            ->default(true),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make(__('filament.multilingual_support'))
                    ->description(__('filament.add_translations'))
                    ->schema([
                        TextInput::make('name_ps')
                            ->label(__('filament.service_name_pashto'))
                            ->maxLength(255)
                            ->placeholder('د غاښونو پاکول'),

                        TextInput::make('name_fa')
                            ->label(__('filament.service_name_dari'))
                            ->maxLength(255)
                            ->placeholder('تمیز کردن دندان'),

                        Textarea::make('description_ps')
                            ->label(__('filament.description_pashto'))
                            ->rows(3)
                            ->placeholder('د دغه خدمت تفصیلات ولیکئ'),

                        Textarea::make('description_fa')
                            ->label(__('filament.description_dari'))
                            ->rows(3)
                            ->placeholder('توضیحات این خدمت را بنویسید'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
