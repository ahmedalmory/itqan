<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Custom Console Commands
|--------------------------------------------------------------------------
*/
Artisan::command('seed:teacher-translations', function () {
    $this->comment('Running TeacherTranslationSeeder...');
    $this->call('db:seed', ['--class' => 'Database\Seeders\TeacherTranslationSeeder']);
    $this->info('Teacher translations seeded successfully!');
})->purpose('Seed teacher dashboard translations');
