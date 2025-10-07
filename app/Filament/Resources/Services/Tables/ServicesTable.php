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
                    ->label('Service Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable()
                    ->tooltip('Click to copy service name'),
                
                TextColumn::make('description')
                    ->label('Description')
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
                    ->label('Price')
                    ->formatStateUsing(fn ($state) => CurrencyHelper::format($state))
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),
                
                BadgeColumn::make('category')
                    ->label('Category')
                    ->colors([
                        'primary' => 'preventive',
                        'success' => 'restorative', 
                        'warning' => 'cosmetic',
                        'danger' => 'surgical',
                        'info' => 'diagnostic',
                        'secondary' => 'anesthesia',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'preventive' => 'Preventive Care',
                        'restorative' => 'Restorative',
                        'cosmetic' => 'Cosmetic',
                        'surgical' => 'Surgical',
                        'diagnostic' => 'Diagnostic',
                        'anesthesia' => 'Anesthesia',
                        default => ucfirst($state),
                    })
                    ->sortable(),
                
                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Category')
                    ->options([
                        'preventive' => 'Preventive Care',
                        'restorative' => 'Restorative',
                        'cosmetic' => 'Cosmetic',
                        'surgical' => 'Surgical',
                        'diagnostic' => 'Diagnostic',
                        'anesthesia' => 'Anesthesia',
                    ])
                    ->multiple(),
                
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All services')
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only'),
                
                SelectFilter::make('price_range')
                    ->label('Price Range')
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
                    ->label('View Details'),
                EditAction::make()
                    ->label('Edit Service')
                    ->modalWidth('lg')
                    ->modalHeading('Edit Service')
                    ->modalSubmitActionLabel('Update Service')
                    ->modalCancelActionLabel('Cancel')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('name_en')
                            ->label('Service Name (English)')
                            ->required()
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_en')
                            ->label('Description (English)')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->prefix('؋')
                            ->required(),
                        
                        \Filament\Forms\Components\Select::make('category')
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
                        
                        \Filament\Forms\Components\Toggle::make('is_active')
                            ->label('Active'),
                        
                        \Filament\Forms\Components\TextInput::make('name_ps')
                            ->label('Service Name (Pashto)')
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_ps')
                            ->label('Description (Pashto)')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\TextInput::make('name_fa')
                            ->label('Service Name (Farsi)')
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_fa')
                            ->label('Description (Farsi)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                DeleteAction::make()
                    ->label('Delete Service')
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                CreateAction::make()
                    ->label('Add New Service')
                    ->modalWidth('lg')
                    ->modalHeading('Create New Service')
                    ->modalSubmitActionLabel('Create Service')
                    ->modalCancelActionLabel('Cancel')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('name_en')
                            ->label('Service Name (English)')
                            ->required()
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_en')
                            ->label('Description (English)')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->numeric()
                            ->prefix('؋')
                            ->required(),
                        
                        \Filament\Forms\Components\Select::make('category')
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
                        
                        \Filament\Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                        
                        \Filament\Forms\Components\TextInput::make('name_ps')
                            ->label('Service Name (Pashto)')
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_ps')
                            ->label('Description (Pashto)')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\TextInput::make('name_fa')
                            ->label('Service Name (Farsi)')
                            ->maxLength(255),
                        
                        \Filament\Forms\Components\Textarea::make('description_fa')
                            ->label('Description (Farsi)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Delete Selected')
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('name', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}
