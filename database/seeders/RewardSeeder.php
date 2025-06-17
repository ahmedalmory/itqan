<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reward;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            [
                'name' => 'مصحف شريف مزخرف',
                'description' => 'مصحف شريف بتصميم جميل ومزخرف بالذهب، مثالي للهدايا والاقتناء الشخصي.',
                'points_cost' => 500,
                'stock_quantity' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'كتاب تفسير القرآن الكريم',
                'description' => 'مجموعة كتب تفسير القرآن الكريم للعلماء المعتمدين، تساعد في فهم معاني الآيات.',
                'points_cost' => 750,
                'stock_quantity' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'سبحة إلكترونية ذكية',
                'description' => 'سبحة إلكترونية مع عداد رقمي ومنبه للأذكار، تساعد في تنظيم الذكر والتسبيح.',
                'points_cost' => 300,
                'stock_quantity' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'أسطوانة تلاوات قرآنية',
                'description' => 'مجموعة من أجمل التلاوات القرآنية لمشاهير القراء، بجودة صوت عالية.',
                'points_cost' => 200,
                'stock_quantity' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'دفتر ملاحظات إسلامي',
                'description' => 'دفتر ملاحظات أنيق بتصميم إسلامي، مثالي لكتابة الملاحظات والتأملات الدينية.',
                'points_cost' => 150,
                'stock_quantity' => 40,
                'is_active' => true,
            ],
            [
                'name' => 'ساعة حائط بآيات قرآنية',
                'description' => 'ساعة حائط جميلة مزينة بآيات قرآنية مختارة، تضفي جواً روحانياً على المكان.',
                'points_cost' => 400,
                'stock_quantity' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'طقم أدوات كتابة فاخر',
                'description' => 'طقم أدوات كتابة فاخر يتضمن قلم وقلم رصاص ومسطرة، مثالي للطلاب المتفوقين.',
                'points_cost' => 250,
                'stock_quantity' => 35,
                'is_active' => true,
            ],
            [
                'name' => 'شهادة تقدير مؤطرة',
                'description' => 'شهادة تقدير مؤطرة وجاهزة للعرض، تكريماً للإنجازات المتميزة في الحفظ والتلاوة.',
                'points_cost' => 100,
                'stock_quantity' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'حقيبة كتب إسلامية',
                'description' => 'حقيبة أنيقة خاصة بحمل الكتب والمصاحف، مصنوعة من مواد عالية الجودة.',
                'points_cost' => 350,
                'stock_quantity' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'مجموعة كتب الأدعية والأذكار',
                'description' => 'مجموعة شاملة من كتب الأدعية والأذكار المأثورة، مرجع مهم للمسلم في حياته اليومية.',
                'points_cost' => 600,
                'stock_quantity' => 18,
                'is_active' => true,
            ],
            [
                'name' => 'جهاز تسجيل للتلاوة',
                'description' => 'جهاز تسجيل صوتي محمول لتسجيل التلاوات الشخصية ومراجعة الحفظ.',
                'points_cost' => 800,
                'stock_quantity' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'لوحة فنية بالخط العربي',
                'description' => 'لوحة فنية جميلة مكتوبة بالخط العربي الأصيل، تحمل آيات قرآنية أو أحاديث شريفة.',
                'points_cost' => 450,
                'stock_quantity' => 22,
                'is_active' => true,
            ],
            [
                'name' => 'مكتبة إلكترونية إسلامية',
                'description' => 'قرص مدمج يحتوي على مكتبة إلكترونية شاملة من الكتب الإسلامية والتفاسير.',
                'points_cost' => 350,
                'stock_quantity' => 0, // Out of stock example
                'is_active' => true,
            ],
            [
                'name' => 'طقم هدايا رمضانية',
                'description' => 'طقم هدايا جميل يتضمن فانوس رمضان ومصحف صغير وسبحة، مثالي لشهر رمضان المبارك.',
                'points_cost' => 550,
                'stock_quantity' => 8,
                'is_active' => false, // Seasonal item - inactive
            ],
            [
                'name' => 'حامل مصحف خشبي',
                'description' => 'حامل مصحف مصنوع من الخشب الطبيعي بتصميم أنيق، يساعد في القراءة المريحة.',
                'points_cost' => 280,
                'stock_quantity' => 28,
                'is_active' => true,
            ],
        ];

        foreach ($rewards as $rewardData) {
            Reward::create($rewardData);
        }

        $this->command->info('✅ Created ' . count($rewards) . ' rewards');
        
        // Display summary
        $active = collect($rewards)->where('is_active', true)->count();
        $inactive = collect($rewards)->where('is_active', false)->count();
        $outOfStock = collect($rewards)->where('stock_quantity', 0)->count();
        
        $this->command->info("   - Active: {$active}");
        $this->command->info("   - Inactive: {$inactive}");
        $this->command->info("   - Out of Stock: {$outOfStock}");
    }
} 