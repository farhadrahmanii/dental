<?php

namespace App\Enums;

enum DentalTreatment: string
{
    case DENTAL_CHECKUP = 'Dental Check-up';
    case TEETH_CLEANING = 'Teeth Cleaning';
    case TOOTH_FILLING = 'Tooth Filling';
    case TOOTH_EXTRACTION = 'Tooth Extraction';
    case ROOT_CANAL = 'Root Canal Treatment';
    case DENTAL_CROWN = 'Dental Crown';
    case DENTAL_BRIDGE = 'Dental Bridge';
    case DENTAL_IMPLANT = 'Dental Implant';
    case DENTURES = 'Dentures (False Teeth)';
    case TEETH_WHITENING = 'Teeth Whitening';
    case DENTAL_XRAY = 'Dental X-ray';
    case BRACES = 'Braces (Orthodontic Treatment)';
    case SCALING_POLISHING = 'Scaling and Polishing';
    case GUM_TREATMENT = 'Gum Treatment';
    case WISDOM_TOOTH_REMOVAL = 'Wisdom Tooth Removal';
    case CAVITY_TREATMENT = 'Cavity Treatment';
    case DENTAL_VENEER = 'Dental Veneer';
    case FLUORIDE_TREATMENT = 'Fluoride Treatment';
    case SEALANT_APPLICATION = 'Sealant Application';
    case PEDIATRIC_DENTISTRY = 'Pediatric Dentistry (Children’s Dentistry)';
    case EMERGENCY_DENTAL_CARE = 'Emergency Dental Care';
    case COSMETIC_DENTISTRY = 'Cosmetic Dentistry';
    case ORAL_SURGERY = 'Oral Surgery';
    case TOOTH_SENSITIVITY_TREATMENT = 'Tooth Sensitivity Treatment';
    case MOUTH_GUARD = 'Mouth Guard (for Grinding or Sports)';

    public function translation(): string
    {
        return match($this) {
            self::DENTAL_CHECKUP => 'معاینه دندان',
            self::TEETH_CLEANING => 'پاک‌کردن دندان‌ها',
            self::TOOTH_FILLING => 'پرکردن دندان',
            self::TOOTH_EXTRACTION => 'کشیدن دندان',
            self::ROOT_CANAL => 'تداوی عصب دندان',
            self::DENTAL_CROWN => 'تاج دندان / کَپ دندان',
            self::DENTAL_BRIDGE => 'پُل دندان',
            self::DENTAL_IMPLANT => 'ایمپلانت دندان',
            self::DENTURES => 'دندان مصنوعی',
            self::TEETH_WHITENING => 'سفیدکردن دندان‌ها',
            self::DENTAL_XRAY => 'عکس‌برداری دندان',
            self::BRACES => 'بَریس / تداوی تنظیم دندان‌ها',
            self::SCALING_POLISHING => 'صفاکاری و جلا دادن دندان‌ها',
            self::GUM_TREATMENT => 'تداوی لثه',
            self::WISDOM_TOOTH_REMOVAL => 'کشیدن دندان عقل',
            self::CAVITY_TREATMENT => 'تداوی سُوراخ دندان',
            self::DENTAL_VENEER => 'وینیر دندان',
            self::FLUORIDE_TREATMENT => 'تداوی فلوراید',
            self::SEALANT_APPLICATION => 'گذاشتن سیلانت (پوشش محافظ)',
            self::PEDIATRIC_DENTISTRY => 'دندان‌پزشکی اطفال',
            self::EMERGENCY_DENTAL_CARE => 'مراقبت عاجل دندان',
            self::COSMETIC_DENTISTRY => 'دندان‌پزشکی زیبایی',
            self::ORAL_SURGERY => 'جراحی دهان',
            self::TOOTH_SENSITIVITY_TREATMENT => 'تداوی حساسیت دندان',
            self::MOUTH_GUARD => 'محافظ دهان',
        };
    }

    public static function options(): array
    {
        return array_map(
            fn($case) => ['value' => $case->value, 'label' => $case->translation()],
            self::cases()
        );
    }
}
