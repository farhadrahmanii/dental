<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Helpers\CurrencyHelper;
use App\Models\Expense;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-receipt-percent';

    protected static ?string $navigationLabel = null;
    
    protected static ?string $modelLabel = null;
    
    protected static ?string $pluralModelLabel = null;
    
    public static function getNavigationLabel(): string
    {
        return __('filament.expenses');
    }
    
    public static function getModelLabel(): string
    {
        return __('filament.expense');
    }
    
    public static function getPluralModelLabel(): string
    {
        return __('filament.expenses');
    }

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'expense_id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Expense Information')
                    ->description('Record clinic expenses and transactions')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Select::make('expense_type')
                            ->label('Expense Type')
                            ->options(function () {
                                // Get predefined expense types
                                $predefined = [
                                    'Utilities' => 'Utilities',
                                    'Supplies' => 'Supplies',
                                    'Equipment' => 'Equipment',
                                    'Rent' => 'Rent',
                                    'Salaries' => 'Salaries',
                                    'Maintenance' => 'Maintenance',
                                    'Marketing' => 'Marketing',
                                    'Insurance' => 'Insurance',
                                    'Professional Services' => 'Professional Services',
                                    'Other' => 'Other',
                                ];
                                
                                // Get existing expense types from database
                                try {
                                    $existing = Expense::distinct()
                                        ->pluck('expense_type')
                                        ->filter()
                                        ->mapWithKeys(fn($type) => [$type => $type])
                                        ->toArray();
                                    
                                    // Merge predefined with existing (existing takes precedence)
                                    return array_merge($predefined, $existing);
                                } catch (\Throwable $e) {
                                    // If table doesn't exist yet, return only predefined
                                    return $predefined;
                                }
                            })
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Expense Type Name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Enter expense type name')
                                    ->rules(['required', 'string', 'max:255']),
                            ])
                            ->createOptionUsing(function (array $data): string {
                                // Return the trimmed name - this will be the selected value
                                // The value is automatically set in the form state
                                return trim($data['name']);
                            })
                            ->getOptionLabelUsing(function ($value): ?string {
                                // Always return the value as label (handles both existing and newly created)
                                // This ensures custom values not in options list are still displayed
                                return $value ?: null;
                            })
                            ->required()
                            ->native(false)
                            ->rules(['required', 'string', 'max:255'])
                            ->prefixIcon('heroicon-o-tag')
                            ->helperText('Select an existing type or create a new one using the + button'),

                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->maxLength(1000)
                            ->placeholder('Enter expense description')
                            ->columnSpanFull(),

                        TextInput::make('amount')
                            ->label('Amount')
                            ->numeric()
                            ->required()
                            ->prefix(CurrencyHelper::symbol())
                            ->prefixIcon('heroicon-o-currency-dollar')
                            ->minValue(0)
                            ->step(0.01),

                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'Cash' => 'Cash',
                                'Bank' => 'Bank',
                                'Credit' => 'Credit',
                                'Mobile Payment' => 'Mobile Payment',
                            ])
                            ->required()
                            ->default('Cash')
                            ->native(false)
                            ->prefixIcon('heroicon-o-credit-card'),

                        DatePicker::make('expense_date')
                            ->label('Expense Date')
                            ->required()
                            ->default(now())
                            ->native(false)
                            ->displayFormat('M d, Y')
                            ->prefixIcon('heroicon-o-calendar'),

                        TextInput::make('paid_to')
                            ->label('Paid To')
                            ->maxLength(255)
                            ->placeholder('Person or company name (optional)')
                            ->prefixIcon('heroicon-o-user'),

                        TextInput::make('recorded_by')
                            ->label('Recorded By')
                            ->default(fn() => Auth::user()?->name ?? 'System')
                            ->disabled()
                            ->dehydrated()
                            ->prefixIcon('heroicon-o-user-circle'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Receipt')
                    ->description('Upload receipt image (optional)')
                    ->icon('heroicon-o-document')
                    ->schema([
                        FileUpload::make('receipt_image')
                            ->label('Receipt Image')
                            ->disk('public')
                            ->directory('receipts')
                            ->image()
                            ->imageEditor()
                            ->downloadable()
                            ->openable()
                            ->maxSize(5120)
                            ->helperText('Upload receipt image (max 5MB)')
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expense_id')
                    ->label(__('filament.expense_id'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                TextColumn::make('expense_type')
                    ->label(__('filament.type'))
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('description')
                    ->label(__('filament.description'))
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn($record) => $record->description)
                    ->wrap(),

                TextColumn::make('amount')
                    ->label(__('filament.amount'))
                    ->money('AFN')
                    ->sortable()
                    ->weight('bold')
                    ->color('danger'),

                TextColumn::make('payment_method')
                    ->label(__('filament.payment_method'))
                    ->badge()
                    ->formatStateUsing(fn($state) => match($state) {
                        'Cash' => __('filament.cash'),
                        'Bank' => __('filament.bank_transfer'),
                        'Credit' => __('filament.card'),
                        'Mobile Payment' => __('filament.other'),
                        default => $state,
                    })
                    ->color(fn($state) => match($state) {
                        'Cash' => 'success',
                        'Bank' => 'info',
                        'Credit' => 'warning',
                        'Mobile Payment' => 'primary',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('expense_date')
                    ->label(__('filament.date'))
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('paid_to')
                    ->label(__('filament.paid_to'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('recorded_by')
                    ->label(__('filament.recorded_by'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label(__('filament.created_at'))
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('expense_type')
                    ->label(__('filament.expense_type'))
                    ->options([
                        'Utilities' => 'Utilities',
                        'Supplies' => 'Supplies',
                        'Equipment' => 'Equipment',
                        'Rent' => 'Rent',
                        'Salaries' => 'Salaries',
                        'Maintenance' => 'Maintenance',
                        'Marketing' => 'Marketing',
                        'Insurance' => 'Insurance',
                        'Professional Services' => 'Professional Services',
                        'Other' => __('filament.other'),
                    ])
                    ->multiple(),

                SelectFilter::make('payment_method')
                    ->label(__('filament.payment_method'))
                    ->options([
                        'Cash' => __('filament.cash'),
                        'Bank' => __('filament.bank_transfer'),
                        'Credit' => __('filament.card'),
                        'Mobile Payment' => __('filament.other'),
                    ])
                    ->multiple(),

                Filter::make('expense_date')
                    ->form([
                        DatePicker::make('from')
                            ->label(__('filament.from_date')),
                        DatePicker::make('until')
                            ->label(__('filament.until_date')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('expense_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('expense_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('expense_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'view' => Pages\ViewExpense::route('/{record}'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}

