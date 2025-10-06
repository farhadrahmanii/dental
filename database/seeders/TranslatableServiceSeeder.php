<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class TranslatableServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'General Dentistry',
                'name_en' => 'General Dentistry',
                'name_ps' => 'عمومي ډینټري',
                'name_fa' => 'دندانپزشکی عمومی',
                'description' => 'Comprehensive oral health care including cleanings, fillings, root canals, and preventive treatments.',
                'description_en' => 'Comprehensive oral health care including cleanings, fillings, root canals, and preventive treatments.',
                'description_ps' => 'د پاکولو، ډکولو، ریښې کانالونو، او د مخنیوي درملنو په ګډون بشپړه شفاهي روغتیا پاملرنه.',
                'description_fa' => 'مراقبت‌های جامع سلامت دهان و دندان شامل تمیز کردن، پر کردن، عصب‌کشی و درمان‌های پیشگیرانه.',
                'price' => 150.00,
                'category' => 'general',
                'is_active' => true,
            ],
            [
                'name' => 'Cosmetic Dentistry',
                'name_en' => 'Cosmetic Dentistry',
                'name_ps' => 'کاسمیټیک ډینټري',
                'name_fa' => 'دندانپزشکی زیبایی',
                'description' => 'Transform your smile with our cosmetic dental procedures designed to enhance your appearance.',
                'description_en' => 'Transform your smile with our cosmetic dental procedures designed to enhance your appearance.',
                'description_ps' => 'د خپل خندا د ښه کولو او د خپل اعتماد د زیاتولو لپاره د زموږ د کاسمیټیک ډینټل پروسیجرونو سره خپله خندا بدله کړئ.',
                'description_fa' => 'لبخند خود را با روش‌های زیبایی دندانپزشکی ما که برای بهبود ظاهر طراحی شده‌اند، متحول کنید.',
                'price' => 300.00,
                'category' => 'cosmetic',
                'is_active' => true,
            ],
            [
                'name' => 'Orthodontics',
                'name_en' => 'Orthodontics',
                'name_ps' => 'ارتودونټکس',
                'name_fa' => 'ارتودنسی',
                'description' => 'Straighten your teeth and improve your bite with our advanced orthodontic treatments.',
                'description_en' => 'Straighten your teeth and improve your bite with our advanced orthodontic treatments.',
                'description_ps' => 'د ټولو عمرونو د ناروغانو لپاره د زموږ د پرمختللو ارتودونټیک درملنو سره خپل غاښونه سم کړئ او خپل څښتنه ښه کړئ.',
                'description_fa' => 'دندان‌های خود را صاف کنید و گاز گرفتن خود را با درمان‌های پیشرفته ارتودنسی ما بهبود دهید.',
                'price' => 2500.00,
                'category' => 'orthodontics',
                'is_active' => true,
            ],
            [
                'name' => 'Oral Surgery',
                'name_en' => 'Oral Surgery',
                'name_ps' => 'شفاهي جراحي',
                'name_fa' => 'جراحی دهان',
                'description' => 'Advanced surgical procedures including wisdom teeth removal, dental implants, and complex extractions.',
                'description_en' => 'Advanced surgical procedures including wisdom teeth removal, dental implants, and complex extractions.',
                'description_ps' => 'د حکمت غاښونو لرې کولو، ډینټل امپلانټونو، او پیچلو استخراجونو په ګډون پرمختللي جراحي پروسیجرونه.',
                'description_fa' => 'روش‌های جراحی پیشرفته شامل کشیدن دندان عقل، ایمپلنت دندان و کشیدن‌های پیچیده.',
                'price' => 500.00,
                'category' => 'surgery',
                'is_active' => true,
            ],
            [
                'name' => 'Emergency Care',
                'name_en' => 'Emergency Care',
                'name_ps' => 'د اورګاندي پاملرنه',
                'name_fa' => 'مراقبت‌های اورژانسی',
                'description' => '24/7 emergency dental services for urgent dental problems that require immediate attention.',
                'description_en' => '24/7 emergency dental services for urgent dental problems that require immediate attention.',
                'description_ps' => 'د فوري پاملرنې ته اړتیا لرونکو د اورګاندي ډینټل ستونزو لپاره 24/7 د اورګاندي ډینټل خدمات.',
                'description_fa' => 'خدمات دندانپزشکی اورژانسی 24/7 برای مشکلات فوری دندان که نیاز به توجه فوری دارند.',
                'price' => 200.00,
                'category' => 'emergency',
                'is_active' => true,
            ],
            [
                'name' => 'Pediatric Dentistry',
                'name_en' => 'Pediatric Dentistry',
                'name_ps' => 'د ماشومانو ډینټري',
                'name_fa' => 'دندانپزشکی کودکان',
                'description' => 'Specialized dental care for children, creating positive dental experiences and promoting lifelong oral health.',
                'description_en' => 'Specialized dental care for children, creating positive dental experiences and promoting lifelong oral health.',
                'description_ps' => 'د ماشومانو لپاره ځانګړي ډینټل پاملرنه، مثبت ډینټل تجربې رامینځته کول او د ژوندانه شفاهي روغتیا ته وده ورکول.',
                'description_fa' => 'مراقبت‌های تخصصی دندانپزشکی برای کودکان، ایجاد تجربیات مثبت دندانپزشکی و ترویج سلامت دهان و دندان مادام‌العمر.',
                'price' => 120.00,
                'category' => 'pediatric',
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