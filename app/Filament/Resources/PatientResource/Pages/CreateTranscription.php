<?php

namespace App\Filament\Resources\PatientResource\Pages;

use App\Filament\Resources\PatientResource;
use App\Models\Patient;
use App\Models\Transcription;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;

class CreateTranscription extends Page implements HasForms
{
    use InteractsWithForms;
    
    protected static string $resource = PatientResource::class;

    protected string $view = 'filament.resources.patient-resource.pages.create-transcription';

    public Patient $record;
    public array $data = [];

    public function mount(Patient $record): void
    {
        $this->record = $record;
        $this->form->fill();
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Auto-generate transcription_id in AFG-0001 format
        $lastTranscription = Transcription::orderBy('id', 'desc')->first();
        $nextNumber = 1;
        
        if ($lastTranscription && preg_match('/AFG-(\d+)/', $lastTranscription->transcription_id, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        }
        
        $transcriptionId = 'AFG-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        Transcription::create([
            'transcription_id' => $transcriptionId,
            'patient_id' => $this->record->register_id,
            'transcription_text' => $data['transcription_text'],
            'recorded_by' => $data['recorded_by'],
            'date' => $data['date'] ?? now(),
        ]);

        Notification::make()
            ->success()
            ->title('Transcription added')
            ->body('The transcription has been added successfully.')
            ->send();

        $this->redirect(PatientResource::getUrl('index'));
    }

    public function getHeading(): string
    {
        return 'Add Transcription for ' . ($this->record->name ?? 'Patient');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Textarea::make('transcription_text')
                    ->label('Transcription Text')
                    ->placeholder('Enter the transcription text...')
                    ->required()
                    ->rows(10)
                    ->columnSpanFull(),
                TextInput::make('recorded_by')
                    ->label('Recorded By')
                    ->placeholder('e.g. Dr. Ahmad Zai')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('date')
                    ->label('Date')
                    ->default(now()),
            ])
            ->columns(2)
            ->statePath('data');
    }
}
