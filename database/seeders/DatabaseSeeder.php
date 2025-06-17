<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            CountrySeeder::class,
            TranslationSeeder::class,
            SurahSeeder::class,
            DepartmentSeeder::class,
            UserSeeder::class,
            DepartmentAdminSeeder::class,
            StudyCircleSeeder::class,
            CircleStudentSeeder::class,
            DailyReportSeeder::class,
            StudentPointSeeder::class,
            RewardSeeder::class,
            RewardRedemptionSeeder::class,
            SubscriptionPlanSeeder::class,
            PaymentSettingsSeeder::class,
        ]);
    }
}
