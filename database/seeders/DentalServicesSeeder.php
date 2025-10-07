<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class DentalServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Teeth Filling',
                'name_ps' => 'د غاښونو ډکول',
                'name_fa' => 'پر کردن دندان',
                'description' => 'Dental filling procedure to restore damaged teeth',
                'description_ps' => 'د غاښونو د زیانمنولو د بیا رغولو لپاره د غاښونو ډکولو پروسه',
                'description_fa' => 'روش پر کردن دندان برای ترمیم دندان های آسیب دیده',
                'price' => 150.00,
                'category' => 'restorative',
                'is_active' => true,
            ],
            [
                'name' => 'Cleaning Teeth',
                'name_ps' => 'د غاښونو پاکول',
                'name_fa' => 'تمیز کردن دندان',
                'description' => 'Professional dental cleaning and scaling',
                'description_ps' => 'د غاښونو د مسلکي پاکولو او سکیلینګ پروسه',
                'description_fa' => 'تمیز کردن حرفه ای دندان و جرم گیری',
                'price' => 80.00,
                'category' => 'preventive',
                'is_active' => true,
            ],
            [
                'name' => 'X-Ray of Teeth',
                'name_ps' => 'د غاښونو ایکس رې',
                'name_fa' => 'عکس برداری از دندان',
                'description' => 'Dental X-ray imaging for diagnosis',
                'description_ps' => 'د تشخیص لپاره د غاښونو ایکس رې انځور اخیستل',
                'description_fa' => 'تصویربرداری با اشعه ایکس از دندان برای تشخیص',
                'price' => 50.00,
                'category' => 'diagnostic',
                'is_active' => true,
            ],
            [
                'name' => 'Small Teeth Surgery',
                'name_ps' => 'د غاښونو کوچنۍ جراحي',
                'name_fa' => 'جراحی کوچک دندان',
                'description' => 'Minor dental surgical procedures',
                'description_ps' => 'د غاښونو کوچنۍ جراحي پروسې',
                'description_fa' => 'روش های جراحی جزئی دندان',
                'price' => 300.00,
                'category' => 'surgical',
                'is_active' => true,
            ],
            [
                'name' => 'Big Teeth Surgery',
                'name_ps' => 'د غاښونو لویه جراحي',
                'name_fa' => 'جراحی بزرگ دندان',
                'description' => 'Major dental surgical procedures',
                'description_ps' => 'د غاښونو لویه جراحي پروسې',
                'description_fa' => 'روش های جراحی بزرگ دندان',
                'price' => 800.00,
                'category' => 'surgical',
                'is_active' => true,
            ],
            [
                'name' => 'Dental Implant',
                'name_ps' => 'د غاښونو امپلانټ',
                'name_fa' => 'کاشت دندان',
                'description' => 'Dental implant procedure to replace missing teeth',
                'description_ps' => 'د ورک شویو غاښونو د بدلولو لپاره د غاښونو امپلانټ پروسه',
                'description_fa' => 'روش کاشت دندان برای جایگزینی دندان های از دست رفته',
                'price' => 1500.00,
                'category' => 'surgical',
                'is_active' => true,
            ],
            [
                'name' => 'RCT of Teeth',
                'name_ps' => 'د غاښونو آر سی ټي',
                'name_fa' => 'عصب کشی دندان',
                'description' => 'Root Canal Treatment for infected teeth',
                'description_ps' => 'د عفونت لرونکو غاښونو لپاره د ریښې کانال درملنه',
                'description_fa' => 'درمان کانال ریشه برای دندان های عفونی',
                'price' => 400.00,
                'category' => 'restorative',
                'is_active' => true,
            ],
            [
                'name' => 'Teeth Styling',
                'name_ps' => 'د غاښونو سټایلینګ',
                'name_fa' => 'استایل دندان',
                'description' => 'Cosmetic teeth styling and shaping',
                'description_ps' => 'د غاښونو کاسمیټیک سټایلینګ او شکل ورکول',
                'description_fa' => 'استایل و شکل دهی زیبایی دندان',
                'price' => 250.00,
                'category' => 'cosmetic',
                'is_active' => true,
            ],
            [
                'name' => 'Teeth Veneer',
                'name_ps' => 'د غاښونو وینیر',
                'name_fa' => 'ونیر دندان',
                'description' => 'Dental veneer for cosmetic enhancement',
                'description_ps' => 'د کاسمیټیک ښه کولو لپاره د غاښونو وینیر',
                'description_fa' => 'ونیر دندان برای بهبود زیبایی',
                'price' => 600.00,
                'category' => 'cosmetic',
                'is_active' => true,
            ],
            [
                'name' => 'Electric Anesthesia',
                'name_ps' => 'بریښنایي بی حسي',
                'name_fa' => 'بی حسی الکتریکی',
                'description' => 'Electric anesthesia for pain management during procedures',
                'description_ps' => 'د پروسو پر مهال د درد د اداره کولو لپاره بریښنایي بی حسي',
                'description_fa' => 'بی حسی الکتریکی برای مدیریت درد در طول درمان',
                'price' => 100.00,
                'category' => 'anesthesia',
                'is_active' => true,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::updateOrCreate(
                ['name' => $serviceData['name']],
                $serviceData
            );
        }
    }
}
