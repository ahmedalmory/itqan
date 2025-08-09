<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudyCircle;
use App\Models\DailyReport;
use App\Models\Department;
use App\Models\StudentPoint;
use App\Models\RewardRedemption;
use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\StudentSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display the reports overview page.
     */
    public function index()
    {
        $pageTitle = t('reports_overview');
        
        // Get basic statistics
        $stats = $this->getOverviewStatistics();
        
        // Get chart data
        $chartData = $this->getChartData();
        
        return view('admin.reports.index', compact('pageTitle', 'stats', 'chartData'));
    }

    /**
     * Display detailed reports page with advanced statistics.
     */
    public function detailed()
    {
        $pageTitle = t('detailed_reports');
        
        // Get comprehensive statistics
        $stats = $this->getDetailedStatistics();
        
        // Get advanced chart data
        $chartData = $this->getAdvancedChartData();
        
        return view('admin.reports.detailed', compact('pageTitle', 'stats', 'chartData'));
    }

    /**
     * Get overview statistics for the main reports page.
     */
    private function getOverviewStatistics()
    {
        return [
            // User Statistics
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_teachers' => User::where('role', 'teacher')->count(),
            'total_supervisors' => User::where('role', 'supervisor')->count(),
            'total_department_admins' => User::where('role', 'department_admin')->count(),
            'active_students' => User::where('role', 'student')->where('is_active', true)->count(),
            'inactive_students' => User::where('role', 'student')->where('is_active', false)->count(),
            
            // Gender Statistics
            'male_students' => User::where('role', 'student')->where('gender', 'male')->count(),
            'female_students' => User::where('role', 'student')->where('gender', 'female')->count(),
            'male_teachers' => User::where('role', 'teacher')->where('gender', 'male')->count(),
            'female_teachers' => User::where('role', 'teacher')->where('gender', 'female')->count(),
            
            // Circle Statistics
            'total_circles' => StudyCircle::count(),
            'active_circles' => StudyCircle::where('is_active', true)->count(),
            'circles_with_students' => StudyCircle::whereHas('students')->count(),
            'average_students_per_circle' => $this->calculateAverageStudentsPerCircle(),
            
            // Memorization Statistics
            'total_memorized_pages' => DailyReport::sum('memorization_parts') ?? 0,
            'total_revised_pages' => DailyReport::sum('revision_parts') ?? 0,
            'total_reports' => DailyReport::count(),
            'average_grade' => round(DailyReport::avg('grade') ?? 0, 2),
            
            // Points Statistics
            'total_points_awarded' => StudentPoint::sum('total_points') ?? 0,
            'total_points_spent' => RewardRedemption::sum('points_spent') ?? 0,
            'average_points_per_student' => round(StudentPoint::avg('total_points') ?? 0, 1),
            
            // Department Statistics
            'total_departments' => Department::count(),
            'departments_with_circles' => Department::whereHas('studyCircles')->count(),
            
            // Recent Activity Statistics
            'new_users_this_month' => User::whereMonth('created_at', Carbon::now()->month)->count(),
            'reports_this_month' => DailyReport::whereMonth('created_at', Carbon::now()->month)->count(),
            'subscriptions_this_month' => StudentSubscription::whereMonth('created_at', Carbon::now()->month)->count(),
        ];
    }

    /**
     * Get detailed statistics for the detailed reports page.
     */
    private function getDetailedStatistics()
    {
        $stats = $this->getOverviewStatistics();
        
        // Add more detailed statistics
        $additionalStats = [
            // Age Distribution
            'age_distribution' => User::where('role', 'student')
                ->whereNotNull('age')
                ->select('age', DB::raw('count(*) as count'))
                ->groupBy('age')
                ->orderBy('age')
                ->get(),
            
            // Country Distribution
            'country_distribution' => User::with('country')
                ->whereHas('country')
                ->select('country_id', DB::raw('count(*) as count'))
                ->groupBy('country_id')
                ->get(),
            
            // Circle Performance
            'circle_performance' => StudyCircle::withCount('students')
                ->with(['teacher:id,name', 'supervisor:id,name'])
                ->orderByDesc('students_count')
                ->take(10)
                ->get(),
            
            // Top Performing Students
            'top_students' => User::where('role', 'student')
                ->withSum('studentPoints', 'total_points')
                ->orderByDesc('student_points_sum_total_points')
                ->take(10)
                ->get(),
            
            // Monthly Progress
            'monthly_progress' => DailyReport::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(memorization_parts) as total_memorized'),
                DB::raw('COUNT(*) as total_reports')
            )
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get(),
            
            // Task Statistics
            'task_stats' => [
                'total_tasks' => Task::count(),
                'active_tasks' => Task::where('status', 'active')->count(),
                'completed_tasks' => TaskCompletion::count(),
                'completion_rate' => $this->calculateTaskCompletionRate(),
            ],
        ];
        
        return array_merge($stats, $additionalStats);
    }

    /**
     * Get chart data for overview page.
     */
    private function getChartData()
    {
        return [
            // User Registration Trend (Last 12 months)
            'user_registration_trend' => $this->getUserRegistrationTrend(),
            
            // Gender Distribution
            'gender_distribution' => [
                'male' => User::where('gender', 'male')->count(),
                'female' => User::where('gender', 'female')->count(),
            ],
            
            // Role Distribution
            'role_distribution' => User::select('role', DB::raw('count(*) as count'))
                ->groupBy('role')
                ->pluck('count', 'role'),
            
            // Memorization Progress (Last 6 months)
            'memorization_progress' => $this->getMemorizationProgress(),
        ];
    }

    /**
     * Get advanced chart data for detailed reports page.
     */
    private function getAdvancedChartData()
    {
        $basicChartData = $this->getChartData();
        
        $advancedChartData = [
            // Points Distribution
            'points_distribution' => $this->getPointsDistribution(),
            
            // Circle Activity
            'circle_activity' => $this->getCircleActivity(),
            
            // Department Performance
            'department_performance' => $this->getDepartmentPerformance(),
            
            // Weekly Activity Pattern
            'weekly_activity' => $this->getWeeklyActivity(),
        ];
        
        return array_merge($basicChartData, $advancedChartData);
    }

    /**
     * Get user registration trend for the last 12 months.
     */
    private function getUserRegistrationTrend()
    {
        return User::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'value' => $item->count,
                ];
            });
    }

    /**
     * Get memorization progress for the last 6 months.
     */
    private function getMemorizationProgress()
    {
        return DailyReport::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(memorization_parts) as total_memorized'),
            DB::raw('SUM(revision_parts) as total_revised')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'memorized' => (float) $item->total_memorized,
                    'revised' => (float) $item->total_revised,
                ];
            });
    }

    /**
     * Get points distribution data.
     */
    private function getPointsDistribution()
    {
        return StudentPoint::select(
            DB::raw('CASE 
                WHEN total_points BETWEEN 0 AND 100 THEN "0-100"
                WHEN total_points BETWEEN 101 AND 500 THEN "101-500"
                WHEN total_points BETWEEN 501 AND 1000 THEN "501-1000"
                WHEN total_points BETWEEN 1001 AND 2000 THEN "1001-2000"
                ELSE "2000+" 
            END as points_range'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('points_range')
            ->get()
            ->pluck('count', 'points_range');
    }

    /**
     * Get circle activity data.
     */
    private function getCircleActivity()
    {
        return StudyCircle::withCount('students')
            ->with('teacher:id,name')
            ->orderByDesc('students_count')
            ->take(10)
            ->get()
            ->map(function ($circle) {
                return [
                    'name' => $circle->name,
                    'teacher' => $circle->teacher->name ?? 'N/A',
                    'students' => $circle->students_count,
                ];
            });
    }

    /**
     * Get department performance data.
     */
    private function getDepartmentPerformance()
    {
        return Department::withCount(['studyCircles'])
            ->get()
            ->map(function ($department) {
                return [
                    'name' => $department->name,
                    'circles' => $department->study_circles_count,
                ];
            });
    }

    /**
     * Get weekly activity pattern.
     */
    private function getWeeklyActivity()
    {
        return DailyReport::select(
            DB::raw('DAYOFWEEK(created_at) as day_of_week'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', Carbon::now()->subMonths(3))
            ->groupBy('day_of_week')
            ->orderBy('day_of_week')
            ->get()
            ->map(function ($item) {
                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                return [
                    'day' => $days[$item->day_of_week - 1],
                    'count' => $item->count,
                ];
            });
    }

    /**
     * Calculate task completion rate.
     */
    private function calculateTaskCompletionRate()
    {
        $totalTasks = Task::count();
        
        // Count unique tasks that have been completed
        $completedTasks = TaskCompletion::join('task_assignments', 'task_completions.task_assignment_id', '=', 'task_assignments.id')
            ->distinct('task_assignments.task_id')
            ->count('task_assignments.task_id');
        
        return $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;
    }

    /**
     * Calculate average students per circle.
     */
    private function calculateAverageStudentsPerCircle()
    {
        $circles = StudyCircle::withCount('students')->get();
        
        if ($circles->isEmpty()) {
            return 0;
        }
        
        $totalStudents = $circles->sum('students_count');
        $totalCircles = $circles->count();
        
        return round($totalStudents / $totalCircles, 1);
    }

    /**
     * Export reports data to Excel/CSV.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $stats = $this->getDetailedStatistics();
        
        if ($format === 'csv') {
            return $this->exportToCsv($stats);
        }
        
        // For Excel format, we'd need Laravel Excel package
        return response()->json([
            'message' => 'Excel export requires Laravel Excel package to be installed',
            'format' => $format,
            'available_formats' => ['csv'],
        ]);
    }

    /**
     * Export statistics to CSV format.
     */
    private function exportToCsv($stats)
    {
        $filename = 'reports_export_' . date('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($stats) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Arabic display in Excel
            fwrite($file, "\xEF\xBB\xBF");
            
            // Write header
            fputcsv($file, ['Itqan Academy - Comprehensive Reports Export']);
            fputcsv($file, ['Generated on: ' . date('Y-m-d H:i:s')]);
            fputcsv($file, []); // Empty row
            
            // User Statistics Section
            fputcsv($file, ['=== USER STATISTICS ===']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, [t('total_users'), number_format($stats['total_users'])]);
            fputcsv($file, [t('total_students'), number_format($stats['total_students'])]);
            fputcsv($file, [t('total_teachers'), number_format($stats['total_teachers'])]);
            fputcsv($file, [t('total_supervisors'), number_format($stats['total_supervisors'])]);
            fputcsv($file, [t('active_students'), number_format($stats['active_students'])]);
            fputcsv($file, [t('inactive_students'), number_format($stats['inactive_students'])]);
            fputcsv($file, [t('male_students'), number_format($stats['male_students'])]);
            fputcsv($file, [t('female_students'), number_format($stats['female_students'])]);
            fputcsv($file, [t('new_users_this_month'), number_format($stats['new_users_this_month'])]);
            fputcsv($file, []); // Empty row
            
            // Circle Statistics Section
            fputcsv($file, ['=== CIRCLE STATISTICS ===']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, [t('total_circles'), number_format($stats['total_circles'])]);
            fputcsv($file, [t('active_circles'), number_format($stats['active_circles'])]);
            fputcsv($file, [t('circles_with_students'), number_format($stats['circles_with_students'])]);
            fputcsv($file, [t('avg_students_per_circle'), number_format($stats['average_students_per_circle'], 1)]);
            fputcsv($file, []); // Empty row
            
            // Memorization Statistics Section
            fputcsv($file, ['=== MEMORIZATION STATISTICS ===']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, [t('total_memorized_pages'), number_format($stats['total_memorized_pages'], 1)]);
            fputcsv($file, [t('total_revised_pages'), number_format($stats['total_revised_pages'], 1)]);
            fputcsv($file, [t('total_reports'), number_format($stats['total_reports'])]);
            fputcsv($file, [t('average_grade'), number_format($stats['average_grade'], 2) . '%']);
            fputcsv($file, [t('reports_this_month'), number_format($stats['reports_this_month'])]);
            fputcsv($file, []); // Empty row
            
            // Points Statistics Section
            fputcsv($file, ['=== POINTS STATISTICS ===']);
            fputcsv($file, ['Metric', 'Value']);
            fputcsv($file, [t('total_points_awarded'), number_format($stats['total_points_awarded'])]);
            fputcsv($file, [t('total_points_spent'), number_format($stats['total_points_spent'])]);
            fputcsv($file, [t('average_points_per_student'), number_format($stats['average_points_per_student'], 1)]);
            fputcsv($file, []); // Empty row
            
            // Task Statistics Section
            if (isset($stats['task_stats'])) {
                fputcsv($file, ['=== TASK STATISTICS ===']);
                fputcsv($file, ['Metric', 'Value']);
                fputcsv($file, [t('total_tasks'), number_format($stats['task_stats']['total_tasks'])]);
                fputcsv($file, [t('active_tasks'), number_format($stats['task_stats']['active_tasks'])]);
                fputcsv($file, [t('completed_tasks'), number_format($stats['task_stats']['completed_tasks'])]);
                fputcsv($file, [t('completion_rate'), $stats['task_stats']['completion_rate'] . '%']);
                fputcsv($file, []); // Empty row
            }
            
            // Top Students Section
            if (isset($stats['top_students']) && $stats['top_students']->isNotEmpty()) {
                fputcsv($file, ['=== TOP PERFORMING STUDENTS ===']);
                fputcsv($file, [t('rank'), t('student_name'), t('total_points')]);
                foreach ($stats['top_students'] as $index => $student) {
                    fputcsv($file, [
                        $index + 1,
                        $student->name,
                        number_format($student->student_points_sum_total_points ?? 0)
                    ]);
                }
                fputcsv($file, []); // Empty row
            }
            
            // Circle Performance Section
            if (isset($stats['circle_performance']) && $stats['circle_performance']->isNotEmpty()) {
                fputcsv($file, ['=== CIRCLE PERFORMANCE ===']);
                fputcsv($file, [t('circle_name'), t('teacher'), t('supervisor'), t('students_count'), t('status')]);
                foreach ($stats['circle_performance'] as $circle) {
                    fputcsv($file, [
                        $circle->name,
                        $circle->teacher->name ?? 'N/A',
                        $circle->supervisor->name ?? 'N/A',
                        $circle->students_count,
                        $circle->is_active ? t('active') : t('inactive')
                    ]);
                }
                fputcsv($file, []); // Empty row
            }
            
            // Age Distribution Section
            if (isset($stats['age_distribution']) && $stats['age_distribution']->isNotEmpty()) {
                fputcsv($file, ['=== AGE DISTRIBUTION ===']);
                fputcsv($file, [t('age'), t('students_count')]);
                foreach ($stats['age_distribution'] as $age) {
                    fputcsv($file, [$age->age, $age->count]);
                }
                fputcsv($file, []); // Empty row
            }
            
            // Country Distribution Section
            if (isset($stats['country_distribution']) && $stats['country_distribution']->isNotEmpty()) {
                fputcsv($file, ['=== COUNTRY DISTRIBUTION ===']);
                fputcsv($file, [t('country'), t('students_count')]);
                foreach ($stats['country_distribution'] as $country) {
                    fputcsv($file, [
                        $country->country->name ?? 'Unknown',
                        $country->count
                    ]);
                }
                fputcsv($file, []); // Empty row
            }
            
            // Monthly Progress Section
            if (isset($stats['monthly_progress']) && $stats['monthly_progress']->isNotEmpty()) {
                fputcsv($file, ['=== MONTHLY PROGRESS ===']);
                fputcsv($file, [t('month'), t('memorized_pages'), t('total_reports')]);
                foreach ($stats['monthly_progress'] as $progress) {
                    fputcsv($file, [
                        \Carbon\Carbon::create($progress->year, $progress->month)->format('M Y'),
                        number_format($progress->total_memorized, 1),
                        number_format($progress->total_reports)
                    ]);
                }
                fputcsv($file, []); // Empty row
            }
            
            // Footer
            fputcsv($file, ['=== END OF REPORT ===']);
            fputcsv($file, ['Generated by Itqan Academy Reports System']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 