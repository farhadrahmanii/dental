<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Treatment;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure we have some patients and services
        $patients = Patient::limit(5)->get();
        $services = Service::limit(5)->get();

        if ($patients->count() > 0 && $services->count() > 0) {
            foreach ($patients as $patient) {
                Treatment::create([
                    'patient_id' => $patient->register_id,
                    'service_id' => $services->random()->id,
                    'treatment_description' => 'Regular checkup and cleaning',
                    'treatment_date' => now(),
                    'tooth_numbers' => ['1', '2', '3'],
                ]);
            }
        }
    }
}
