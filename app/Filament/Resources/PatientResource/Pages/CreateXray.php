<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use App\Models\Patient;
use App\Models\Xray;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Str;

class CreateXray extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string $resource = PatientResource::class;

    protected string $view = 'filament.resources.patient-resource.pages.create-xray';

    public Patient $record;

    public string $treatment = '';
    public string $doctor_name = '';
    public ?string $comment = null;
    public ?string $xray_image = null;
    public array $data = [];

    public function mount(Patient $record): void
    {
        $this->record = $record;
        $this->form->fill();
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Xray::create([
            'xray_id' => 'XR-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6)),
            'patient_id' => $this->record->register_id,
            'xray_image' => $data['xray_image'] ?? null,
            'treatment' => $data['treatment'],
            'doctor_name' => $data['doctor_name'],
            'comment' => $data['comment'] ?? null,
        ]);

        $this->notification()->success(
            title: 'X-ray added',
            body: 'The X-ray has been added successfully.',
        );

        $this->redirect(PatientResource::getUrl('view', ['record' => $this->record->register_id]));
    }

    public function getHeading(): string
    {
        return 'Add X-ray for ' . ($this->record->name ?? 'Patient');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                FileUpload::make('xray_image')
                    ->label('X-ray Image')
                    ->image()
                    ->required()
                    ->directory('xrays')
                    ->disk('public')
                    ->imageEditor()
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull(),
                TextInput::make('treatment')
                    ->label('Treatment')
                    ->placeholder('e.g. Root canal')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('doctor_name')
                    ->label('Doctor Name')
                    ->placeholder('e.g. Dr. Ahmad Zai')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Textarea::make('comment')
                    ->label('Comment (optional)')
                    ->placeholder('Notes about this X-ray (optional)')
                    ->rows(4)
                    ->maxLength(1000)
                    ->columnSpanFull(),
            ])
            ->columns(2)
            ->statePath('data');
    }
}
