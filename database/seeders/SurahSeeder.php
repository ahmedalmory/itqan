<?php

namespace Database\Seeders;

use App\Models\Surah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if surahs already exist
        if (Surah::count() > 0) {
            $this->command->info('Surahs already exist in the database.');
            return;
        }
        
        // List of all 114 Surahs with their verse counts
        $surahs = [
            ['name' => 'الفاتحة', 'total_verses' => 7],
            ['name' => 'البقرة', 'total_verses' => 286],
            ['name' => 'آل عمران', 'total_verses' => 200],
            ['name' => 'النساء', 'total_verses' => 176],
            ['name' => 'المائدة', 'total_verses' => 120],
            ['name' => 'الأنعام', 'total_verses' => 165],
            ['name' => 'الأعراف', 'total_verses' => 206],
            ['name' => 'الأنفال', 'total_verses' => 75],
            ['name' => 'التوبة', 'total_verses' => 129],
            ['name' => 'يونس', 'total_verses' => 109],
            ['name' => 'هود', 'total_verses' => 123],
            ['name' => 'يوسف', 'total_verses' => 111],
            ['name' => 'الرعد', 'total_verses' => 43],
            ['name' => 'إبراهيم', 'total_verses' => 52],
            ['name' => 'الحجر', 'total_verses' => 99],
            ['name' => 'النحل', 'total_verses' => 128],
            ['name' => 'الإسراء', 'total_verses' => 111],
            ['name' => 'الكهف', 'total_verses' => 110],
            ['name' => 'مريم', 'total_verses' => 98],
            ['name' => 'طه', 'total_verses' => 135],
            ['name' => 'الأنبياء', 'total_verses' => 112],
            ['name' => 'الحج', 'total_verses' => 78],
            ['name' => 'المؤمنون', 'total_verses' => 118],
            ['name' => 'النور', 'total_verses' => 64],
            ['name' => 'الفرقان', 'total_verses' => 77],
            ['name' => 'الشعراء', 'total_verses' => 227],
            ['name' => 'النمل', 'total_verses' => 93],
            ['name' => 'القصص', 'total_verses' => 88],
            ['name' => 'العنكبوت', 'total_verses' => 69],
            ['name' => 'الروم', 'total_verses' => 60],
            ['name' => 'لقمان', 'total_verses' => 34],
            ['name' => 'السجدة', 'total_verses' => 30],
            ['name' => 'الأحزاب', 'total_verses' => 73],
            ['name' => 'سبأ', 'total_verses' => 54],
            ['name' => 'فاطر', 'total_verses' => 45],
            ['name' => 'يس', 'total_verses' => 83],
            ['name' => 'الصافات', 'total_verses' => 182],
            ['name' => 'ص', 'total_verses' => 88],
            ['name' => 'الزمر', 'total_verses' => 75],
            ['name' => 'غافر', 'total_verses' => 85],
            ['name' => 'فصلت', 'total_verses' => 54],
            ['name' => 'الشورى', 'total_verses' => 53],
            ['name' => 'الزخرف', 'total_verses' => 89],
            ['name' => 'الدخان', 'total_verses' => 59],
            ['name' => 'الجاثية', 'total_verses' => 37],
            ['name' => 'الأحقاف', 'total_verses' => 35],
            ['name' => 'محمد', 'total_verses' => 38],
            ['name' => 'الفتح', 'total_verses' => 29],
            ['name' => 'الحجرات', 'total_verses' => 18],
            ['name' => 'ق', 'total_verses' => 45],
            ['name' => 'الذاريات', 'total_verses' => 60],
            ['name' => 'الطور', 'total_verses' => 49],
            ['name' => 'النجم', 'total_verses' => 62],
            ['name' => 'القمر', 'total_verses' => 55],
            ['name' => 'الرحمن', 'total_verses' => 78],
            ['name' => 'الواقعة', 'total_verses' => 96],
            ['name' => 'الحديد', 'total_verses' => 29],
            ['name' => 'المجادلة', 'total_verses' => 22],
            ['name' => 'الحشر', 'total_verses' => 24],
            ['name' => 'الممتحنة', 'total_verses' => 13],
            ['name' => 'الصف', 'total_verses' => 14],
            ['name' => 'الجمعة', 'total_verses' => 11],
            ['name' => 'المنافقون', 'total_verses' => 11],
            ['name' => 'التغابن', 'total_verses' => 18],
            ['name' => 'الطلاق', 'total_verses' => 12],
            ['name' => 'التحريم', 'total_verses' => 12],
            ['name' => 'الملك', 'total_verses' => 30],
            ['name' => 'القلم', 'total_verses' => 52],
            ['name' => 'الحاقة', 'total_verses' => 52],
            ['name' => 'المعارج', 'total_verses' => 44],
            ['name' => 'نوح', 'total_verses' => 28],
            ['name' => 'الجن', 'total_verses' => 28],
            ['name' => 'المزمل', 'total_verses' => 20],
            ['name' => 'المدثر', 'total_verses' => 56],
            ['name' => 'القيامة', 'total_verses' => 40],
            ['name' => 'الإنسان', 'total_verses' => 31],
            ['name' => 'المرسلات', 'total_verses' => 50],
            ['name' => 'النبأ', 'total_verses' => 40],
            ['name' => 'النازعات', 'total_verses' => 46],
            ['name' => 'عبس', 'total_verses' => 42],
            ['name' => 'التكوير', 'total_verses' => 29],
            ['name' => 'الانفطار', 'total_verses' => 19],
            ['name' => 'المطففين', 'total_verses' => 36],
            ['name' => 'الانشقاق', 'total_verses' => 25],
            ['name' => 'البروج', 'total_verses' => 22],
            ['name' => 'الطارق', 'total_verses' => 17],
            ['name' => 'الأعلى', 'total_verses' => 19],
            ['name' => 'الغاشية', 'total_verses' => 26],
            ['name' => 'الفجر', 'total_verses' => 30],
            ['name' => 'البلد', 'total_verses' => 20],
            ['name' => 'الشمس', 'total_verses' => 15],
            ['name' => 'الليل', 'total_verses' => 21],
            ['name' => 'الضحى', 'total_verses' => 11],
            ['name' => 'الشرح', 'total_verses' => 8],
            ['name' => 'التين', 'total_verses' => 8],
            ['name' => 'العلق', 'total_verses' => 19],
            ['name' => 'القدر', 'total_verses' => 5],
            ['name' => 'البينة', 'total_verses' => 8],
            ['name' => 'الزلزلة', 'total_verses' => 8],
            ['name' => 'العاديات', 'total_verses' => 11],
            ['name' => 'القارعة', 'total_verses' => 11],
            ['name' => 'التكاثر', 'total_verses' => 8],
            ['name' => 'العصر', 'total_verses' => 3],
            ['name' => 'الهمزة', 'total_verses' => 9],
            ['name' => 'الفيل', 'total_verses' => 5],
            ['name' => 'قريش', 'total_verses' => 4],
            ['name' => 'الماعون', 'total_verses' => 7],
            ['name' => 'الكوثر', 'total_verses' => 3],
            ['name' => 'الكافرون', 'total_verses' => 6],
            ['name' => 'النصر', 'total_verses' => 3],
            ['name' => 'المسد', 'total_verses' => 5],
            ['name' => 'الإخلاص', 'total_verses' => 4],
            ['name' => 'الفلق', 'total_verses' => 5],
            ['name' => 'الناس', 'total_verses' => 6],
        ];
        
        // Insert all surahs
        foreach ($surahs as $surah) {
            Surah::create($surah);
        }
        
        $this->command->info('114 Surahs have been added to the database.');
    }
}
