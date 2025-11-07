<?php

namespace App\Filament\Resources\Services\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\CreateAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use App\Helpers\CurrencyHelper;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('filament.service_name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->tooltip(__('filament.service_name')),
                
                TextColumn::make('description')
                    ->label(__('filament.description'))
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }
                        return $state;
                    })
                    ->wrap(),
                
                TextColumn::make('price')
                    ->label(__('filament.price'))
                    ->formatStateUsing(fn ($state) => CurrencyHelper::format($state))
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                
                BadgeColumn::make('category')
                    ->label(__('filament.category'))
                    ->colors([
                        'primary' => 'preventive',
                        'success' => 'restorative', 
                        'warning' => 'cosmetic',
                        'danger' => 'surgical',
                        'info' => 'diagnostic',
                        'secondary' => 'anesthesia',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'preventive' => __('filament.preventive_care'),
                        'restorative' => __('filament.restorative'),
                        'cosmetic' => __('filament.cosmetic'),
                        'surgical' => __('filament.surgical'),
                        'diagnostic' => __('filament.diagnostic'),
                        'anesthesia' => __('filament.anesthesia'),
                        default => ucfirst($state),
                    })
                    ->sortable(),
                
                IconColumn::make('is_active')
                    ->label(__('filament.status'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label(__('filament.updated_at'))
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label(__('filament.category'))
                    ->options([
                        'preventive' => __('filament.preventive_care'),
                        'restorative' => __('filament.restorative'),
                        'cosmetic' => __('filament.cosmetic'),
                        'surgical' => __('filament.surgical'),
                        'diagnostic' => __('filament.diagnostic'),
                        'anesthesia' => __('filament.anesthesia'),
                    ])
                    ->multiple(),
                
                TernaryFilter::make('is_active')
                    ->label(__('filament.status'))
                    ->placeholder(__('filament.all_services'))
                    ->trueLabel(__('filament.active_only'))
                    ->falseLabel(__('filament.inactive_only')),
                
                SelectFilter::make('price_range')
                    ->label(__('filament.price_range'))
                    ->options([
                        '0-100' => '؋0 - ؋100',
                        '100-500' => '؋100 - ؋500',
                        '500-1000' => '؋500 - ؋1,000',
                        '1000+' => '؋1,000+',
                    ])
                    ->query(function ($query, array $data) {
                        if (!$data['value']) return;
                        
                        return match ($data['value']) {
                            '0-100' => $query->whereBetween('price', [0, 100]),
                            '100-500' => $query->whereBetween('price', [100, 500]),
                            '500-1000' => $query->whereBetween('price', [500, 1000]),
                            '1000+' => $query->where('price', '>', 1000),
                        };
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label(__('filament.view_details')),
                EditAction::make()
                    ->label(__('filament.edit_service'))
                    ->modalWidth('lg')
                    ->modalHeading(__('filament.edit_service'))
                    ->modalSubmitActionLabel(__('filament.update_service'))
                    ->modalCancelActionLabel(__('filament.cancel'))
                    ->form([
                        \Filament\Forms\Components\TextInput::make('name_en')
                            ->label(__('filament.service_name_english'))
                            ->required()
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_en')
                            ->label(__('filament.description_english'))
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\TextInput::make('price')
                            ->label(__('filament.price'))
                            ->numeric()
                            ->prefix('؋')
                            ->required(),
                        
                        \Filament\Forms\Components\Select::make('category')
                            ->label(__('filament.category'))
                            ->options([
                                'preventive' => __('filament.preventive_care'),
                                'restorative' => __('filament.restorative'),
                                'cosmetic' => __('filament.cosmetic'),
                                'surgical' => __('filament.surgical'),
                                'diagnostic' => __('filament.diagnostic'),
                                'anesthesia' => __('filament.anesthesia'),
                            ])
                            ->required(),
                        
                        \Filament\Forms\Components\Toggle::make('is_active')
                            ->label(__('filament.active')),
                        
                        \Filament\Forms\Components\TextInput::make('name_ps')
                            ->label(__('filament.service_name_pashto'))
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_ps')
                            ->label(__('filament.description_pashto'))
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\TextInput::make('name_fa')
                            ->label(__('filament.service_name_dari'))
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_fa')
                            ->label(__('filament.description_dari'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                DeleteAction::make()
                    ->label(__('filament.delete_service'))
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                CreateAction::make()
                    ->label(__('filament.add_new_service'))
                    ->modalWidth('lg')
                    ->modalHeading(__('filament.create_new_service'))
                    ->modalSubmitActionLabel(__('filament.create_service'))
                    ->modalCancelActionLabel(__('filament.cancel'))
                    ->form([
                        \Filament\Forms\Components\TextInput::make('name_en')
                            ->label(__('filament.service_name_english'))
                            ->required()
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_en')
                            ->label(__('filament.description_english'))
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\TextInput::make('price')
                            ->label(__('filament.price'))
                            ->numeric()
                            ->prefix('؋')
                            ->required(),
                        
                        \Filament\Forms\Components\Select::make('category')
                            ->label(__('filament.category'))
                            ->options([
                                'preventive' => __('filament.preventive_care'),
                                'restorative' => __('filament.restorative'),
                                'cosmetic' => __('filament.cosmetic'),
                                'surgical' => __('filament.surgical'),
                                'diagnostic' => __('filament.diagnostic'),
                                'anesthesia' => __('filament.anesthesia'),
                            ])
                            ->required(),
                        
                        \Filament\Forms\Components\Toggle::make('is_active')
                            ->label(__('filament.active'))
                            ->default(true),
                        
                        \Filament\Forms\Components\TextInput::make('name_ps')
                            ->label(__('filament.service_name_pashto'))
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_ps')
                            ->label(__('filament.description_pashto'))
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\TextInput::make('name_fa')
                            ->label(__('filament.service_name_dari'))
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_fa')
                            ->label(__('filament.description_dari'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label(__('filament.delete_selected'))
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('name', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
