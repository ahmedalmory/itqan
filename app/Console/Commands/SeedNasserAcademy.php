<?php

namespace App\Console\Commands;

use Database\Seeders\NasserAlKhameesAcademySeeder;
use Illuminate\Console\Command;

class SeedNasserAcademy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'academy:seed-nasser
                            {--fresh : Drop all tables and migrate fresh before seeding}
                            {--countries-only : Seed only countries and languages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with Nasser Al-Khamees Academy (مسجد ناصر بن علي الخميس) specific data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('fresh')) {
            $this->info('🔄 Running fresh migration...');
            $this->call('migrate:fresh');
        }

        if ($this->option('countries-only')) {
            $this->info('🌍 Seeding countries and languages only...');
            $this->call('db:seed', ['--class' => 'Database\\Seeders\\LanguageSeeder']);
            $this->call('db:seed', ['--class' => 'Database\\Seeders\\CountrySeeder']);
            $this->info('✅ Countries and languages seeded successfully!');
            return;
        }

        $this->info('🕌 Seeding Nasser Al-Khamees Academy data...');
        
        // First seed the required dependencies
        $this->info('📚 Seeding required dependencies...');
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\LanguageSeeder']);
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\CountrySeeder']);
        
        // Seed translations
        $this->info('🌐 Seeding translations...');
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\TranslationSeeder']);
        
        // Then seed the academy specific data
        $this->info('🏫 Seeding academy specific data...');
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\NasserAlKhameesAcademySeeder']);
        
        // Seed academy-specific translations
        $this->info('📝 Seeding academy-specific translations...');
        $this->call('db:seed', ['--class' => 'Database\\Seeders\\Translations\\NasserAcademyTranslationSeeder']);

        $this->info('✅ Nasser Al-Khamees Academy data seeded successfully!');
        $this->line('');
        $this->line('📋 Summary:');
        $this->line('   - Academy: مسجد ناصر بن علي الخميس (Nasser Al-Khamees Mosque)');
        $this->line('   - Department: Z11 مجمع إ (Digital Itqan Complex)');
        $this->line('   - Teachers: 9 teachers with real contact information');
        $this->line('   - Study Circles: 10 circles with different age groups and times');
        $this->line('   - Students: Sample students from the provided CSV data');
        $this->line('   - Translations: Academy-specific Arabic/English translations');
        $this->line('   - Countries: Support for 8 countries including Estonia');
        $this->line('');
        $this->line('🔑 Default password for all users: password123');
        $this->line('🌐 App now configured for: مسجد ناصر بن علي الخميس');
        $this->line('📧 Teacher emails: name@nasser-academy.com format');
    }
} 