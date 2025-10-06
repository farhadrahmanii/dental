<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // General Dentistry
            ['name' => 'Dental Consultation', 'description' => 'Initial dental examination and consultation', 'price' => 50.00, 'category' => 'General'],
            ['name' => 'Teeth Cleaning', 'description' => 'Professional dental cleaning and scaling', 'price' => 80.00, 'category' => 'General'],
            ['name' => 'Dental X-Ray', 'description' => 'Single tooth X-ray', 'price' => 25.00, 'category' => 'General'],
            ['name' => 'Full Mouth X-Ray', 'description' => 'Complete mouth X-ray examination', 'price' => 120.00, 'category' => 'General'],
            
            // Restorative Dentistry
            ['name' => 'Dental Filling (Amalgam)', 'description' => 'Silver amalgam filling', 'price' => 100.00, 'category' => 'Restorative'],
            ['name' => 'Dental Filling (Composite)', 'description' => 'White composite filling', 'price' => 150.00, 'category' => 'Restorative'],
            ['name' => 'Root Canal Treatment', 'description' => 'Complete root canal therapy', 'price' => 400.00, 'category' => 'Restorative'],
            ['name' => 'Crown (Porcelain)', 'description' => 'Porcelain dental crown', 'price' => 800.00, 'category' => 'Restorative'],
            ['name' => 'Crown (Metal)', 'description' => 'Metal dental crown', 'price' => 600.00, 'category' => 'Restorative'],
            
            // Oral Surgery
            ['name' => 'Tooth Extraction (Simple)', 'description' => 'Simple tooth extraction', 'price' => 120.00, 'category' => 'Surgery'],
            ['name' => 'Tooth Extraction (Surgical)', 'description' => 'Surgical tooth extraction', 'price' => 200.00, 'category' => 'Surgery'],
            ['name' => 'Wisdom Tooth Removal', 'description' => 'Wisdom tooth extraction', 'price' => 300.00, 'category' => 'Surgery'],
            
            // Cosmetic Dentistry
            ['name' => 'Teeth Whitening', 'description' => 'Professional teeth whitening treatment', 'price' => 300.00, 'category' => 'Cosmetic'],
            ['name' => 'Dental Veneer', 'description' => 'Porcelain veneer per tooth', 'price' => 1000.00, 'category' => 'Cosmetic'],
            ['name' => 'Dental Bonding', 'description' => 'Composite bonding per tooth', 'price' => 200.00, 'category' => 'Cosmetic'],
            
            // Orthodontics
            ['name' => 'Orthodontic Consultation', 'description' => 'Initial orthodontic examination', 'price' => 100.00, 'category' => 'Orthodontics'],
            ['name' => 'Braces Installation', 'description' => 'Complete braces installation', 'price' => 3000.00, 'category' => 'Orthodontics'],
            ['name' => 'Braces Adjustment', 'description' => 'Monthly braces adjustment', 'price' => 100.00, 'category' => 'Orthodontics'],
            
            // Preventive Care
            ['name' => 'Fluoride Treatment', 'description' => 'Professional fluoride application', 'price' => 40.00, 'category' => 'Preventive'],
            ['name' => 'Sealant Application', 'description' => 'Dental sealant per tooth', 'price' => 60.00, 'category' => 'Preventive'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}