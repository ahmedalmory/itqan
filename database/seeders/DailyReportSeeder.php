<?php

namespace Database\Seeders;

use App\Models\DailyReport;
use App\Models\Surah;
use App\Models\CircleStudent;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DailyReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all surahs
        $surahs = Surah::all();
        $surahIds = $surahs->pluck('id')->toArray();
        $surahVerseCounts = $surahs->pluck('total_verses', 'id')->toArray();
        
        // Get all student circle relationships
        $circleStudents = CircleStudent::all();
        
        // Create daily reports for the past 30 days
        for ($day = 0; $day < 30; $day++) {
            $date = Carbon::now()->subDays($day)->format('Y-m-d');
            
            // For each student, create reports for their circles on random days
            foreach ($circleStudents as $circleStudent) {
                // Only create a report on random days (30-70% chance)
                if (rand(1, 100) <= 70) {
                    // Randomly select start and end surahs
                    $fromSurahId = $surahIds[array_rand($surahIds)];
                    $toSurahId = $fromSurahId;
                    
                    // If it's the same surah, get random verses
                    $fromVerse = rand(1, max(1, $surahVerseCounts[$fromSurahId] - 5));
                    $toVerse = rand($fromVerse, min($fromVerse + 10, $surahVerseCounts[$fromSurahId]));
                    
                    // Calculate memorization parts (0.25 to 1.00)
                    $memorizationParts = rand(25, 100) / 100;
                    
                    // Calculate revision parts (0.5 to 2.00)
                    $revisionParts = rand(50, 200) / 100;
                    
                    // Calculate grade (70 to 100)
                    $grade = rand(70, 100);
                    
                    // Generate notes
                    $notes = $this->generateRandomNote($grade);
                    
                    DailyReport::create([
                        'student_id' => $circleStudent->student_id,
                        'report_date' => $date,
                        'memorization_parts' => $memorizationParts,
                        'revision_parts' => $revisionParts,
                        'grade' => $grade,
                        'memorization_from_surah' => $fromSurahId,
                        'memorization_from_verse' => $fromVerse,
                        'memorization_to_surah' => $toSurahId,
                        'memorization_to_verse' => $toVerse,
                        'notes' => $notes,
                    ]);
                }
            }
        }
    }
    
    /**
     * Generate a random note based on grade.
     */
    private function generateRandomNote($grade)
    {
        $goodNotes = [
            'ممتاز، استمر على هذا المستوى',
            'أحسنت، حفظ متقن ومتميز',
            'ممتاز جدا، التجويد سليم والحفظ متقن',
            'مستوى رائع، أحسنت، استمر',
            'حفظ متقن وتجويد سليم، بارك الله فيك',
        ];
        
        $averageNotes = [
            'جيد، لكن يجب الاهتمام بالتجويد أكثر',
            'مقبول، مع ضرورة مراجعة الآيات السابقة',
            'حفظ جيد، لكن يحتاج إلى تحسين النطق',
            'مستوى متوسط، يرجى التركيز أكثر',
            'جيد، لكن يجب زيادة وقت المراجعة',
        ];
        
        $lowNotes = [
            'ضعيف، يجب زيادة وقت الحفظ والمراجعة',
            'مستوى دون المطلوب، يرجى الاهتمام أكثر',
            'يحتاج إلى تحسين كبير، الرجاء التركيز',
            'ضعيف، لم يتم استكمال الحفظ المطلوب',
            'يجب مضاعفة الجهد والتركيز أكثر',
        ];
        
        if ($grade >= 90) {
            return $goodNotes[array_rand($goodNotes)];
        } elseif ($grade >= 80) {
            return $averageNotes[array_rand($averageNotes)];
        } else {
            return $lowNotes[array_rand($lowNotes)];
        }
    }
} 