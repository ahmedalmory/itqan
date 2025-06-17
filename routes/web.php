<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\ProgressController;
use App\Http\Controllers\Student\ReportController;
use App\Http\Controllers\Student\SubscriptionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Supervisor\CircleController as SupervisorCircleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Language Switcher
Route::get('/language/{locale}', function ($locale) {
    try {
        $activeLanguages = Cache::remember('active_languages', now()->addHour(), function () {
            return \App\Models\Language::where('is_active', true)
                ->pluck('code')
                ->toArray();
        });
            
        if (in_array($locale, $activeLanguages)) {
            session()->put('locale', $locale);
            app()->setLocale($locale);
        }
    } catch (\Exception $e) {
        \Log::error('Language switch error: ' . $e->getMessage());
    }
    
    return redirect()->back();
})->name('language.switch');

Route::get('/', [HomeController::class, 'index']);

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/select-circle', [AuthController::class, 'selectCircle'])->name('select-circle');
    Route::post('/select-circle', [AuthController::class, 'storeCircle']);
    
    // Profile routes for all roles
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Password change routes for all roles
    Route::get('/password/change', [\App\Http\Controllers\PasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::put('/password/update', [\App\Http\Controllers\PasswordController::class, 'updatePassword'])->name('password.update');
});

// Admin routes
Route::middleware(['auth', 'role:super_admin,department_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    // Circle management
    Route::resource('circles', \App\Http\Controllers\Admin\CircleController::class);
    Route::get('/circles/{circle}/add-student', [\App\Http\Controllers\Admin\CircleController::class, 'showAddStudent'])->name('circles.add-student');
    Route::post('/circles/{circle}/add-student', [\App\Http\Controllers\Admin\CircleController::class, 'addStudent'])->name('circles.store-student');
    Route::delete('/circles/{circle}/students/{student}', [\App\Http\Controllers\Admin\CircleController::class, 'removeStudent'])->name('circles.remove-student');
    
    // Department management
    Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
    
    // User management
    Route::post('/users/import', [\App\Http\Controllers\Admin\UserController::class, 'import'])->name('users.import');
    Route::get('/users/export', [\App\Http\Controllers\Admin\UserController::class, 'export'])->name('users.export');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    
    // Points Management
    Route::get('/points', [\App\Http\Controllers\Admin\PointsController::class, 'index'])->name('points.index');
    Route::post('/points', [\App\Http\Controllers\Admin\PointsController::class, 'update'])->name('points.update');
    Route::post('/points/bulk', [\App\Http\Controllers\Admin\PointsController::class, 'bulkUpdate'])->name('points.bulk-update');
    Route::get('/points/student/{student}', [\App\Http\Controllers\Admin\PointsController::class, 'history'])->name('points.history');
    Route::get('/points/leaderboard', [\App\Http\Controllers\Admin\PointsController::class, 'leaderboard'])->name('points.leaderboard');
    
    // Reports management
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
    Route::get('/reports/daily', [\App\Http\Controllers\Admin\ReportController::class, 'dailyReports'])->name('reports.daily');
    Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/export-daily', [\App\Http\Controllers\Admin\ReportController::class, 'exportDaily'])->name('reports.export-daily');
    Route::post('/reports/bulk', [\App\Http\Controllers\Admin\ReportController::class, 'bulkStore'])->name('reports.bulk-store');
    Route::get('/reports/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/edit', [\App\Http\Controllers\Admin\ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'update'])->name('reports.update');
    Route::post('/reports/import', [\App\Http\Controllers\Admin\ReportController::class, 'import'])->name('reports.import');
    
    // Subscription management
    Route::resource('subscriptions', \App\Http\Controllers\Admin\SubscriptionController::class);
    
    // Language management
    Route::resource('languages', \App\Http\Controllers\Admin\LanguageController::class);
    Route::post('/languages/{language}/toggle', [\App\Http\Controllers\Admin\LanguageController::class, 'toggleStatus'])->name('languages.toggle');
    
    // Translation management
    Route::get('/translations', [\App\Http\Controllers\Admin\TranslationController::class, 'index'])->name('translations.index');
    Route::post('/translations', [\App\Http\Controllers\Admin\TranslationController::class, 'store'])->name('translations.store');
    Route::get('/translations/create', [\App\Http\Controllers\Admin\TranslationController::class, 'create'])->name('translations.create');
    Route::get('/translations/{translation}/edit', [\App\Http\Controllers\Admin\TranslationController::class, 'edit'])->name('translations.edit');
    Route::put('/translations/{translation}', [\App\Http\Controllers\Admin\TranslationController::class, 'update'])->name('translations.update');
    Route::delete('/translations/{translation}', [\App\Http\Controllers\Admin\TranslationController::class, 'destroy'])->name('translations.destroy');
    Route::post('/translations/generate', [\App\Http\Controllers\Admin\TranslationController::class, 'generate'])->name('translations.generate');
    Route::post('/translations/copy', [\App\Http\Controllers\Admin\TranslationController::class, 'copy'])->name('translations.copy');
    Route::post('/translations/import', [\App\Http\Controllers\Admin\TranslationController::class, 'import'])->name('translations.import');
    Route::get('/translations/export', [\App\Http\Controllers\Admin\TranslationController::class, 'export'])->name('translations.export');
    
    // Rewards Management
    Route::resource('rewards', \App\Http\Controllers\Admin\RewardController::class);
    
    // Reward Redemptions Management
    Route::get('/reward-redemptions', [\App\Http\Controllers\Admin\RewardRedemptionController::class, 'index'])->name('reward-redemptions.index');
    Route::get('/reward-redemptions/{redemption}', [\App\Http\Controllers\Admin\RewardRedemptionController::class, 'show'])->name('reward-redemptions.show');
    Route::put('/reward-redemptions/{redemption}/status', [\App\Http\Controllers\Admin\RewardRedemptionController::class, 'updateStatus'])->name('reward-redemptions.update-status');
    
    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'store'])->name('settings.store');
});

// Department Admin routes
Route::middleware(['auth', 'role:department_admin'])->prefix('department-admin')->name('department-admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('department-admin.dashboard');
    })->name('dashboard');
    
    // Points Management
    Route::get('/points', [\App\Http\Controllers\DepartmentAdmin\PointsController::class, 'index'])->name('points.index');
    Route::post('/points', [\App\Http\Controllers\DepartmentAdmin\PointsController::class, 'update'])->name('points.update');
    Route::post('/points/bulk', [\App\Http\Controllers\DepartmentAdmin\PointsController::class, 'bulkUpdate'])->name('points.bulk-update');
    Route::get('/points/student/{student}', [\App\Http\Controllers\DepartmentAdmin\PointsController::class, 'history'])->name('points.history');
    Route::get('/points/leaderboard', [\App\Http\Controllers\DepartmentAdmin\PointsController::class, 'leaderboard'])->name('points.leaderboard');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    // Dashboard
    Route::get('/', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
    
    // Circles management
    Route::get('/circles', [App\Http\Controllers\Teacher\CircleController::class, 'index'])->name('circles.index');
    Route::get('/circles/{circle}', [App\Http\Controllers\Teacher\CircleController::class, 'show'])->name('circles.show');
    Route::get('/circles/{circle}/edit', [App\Http\Controllers\Teacher\CircleController::class, 'edit'])->name('circles.edit');
    Route::put('/circles/{circle}', [App\Http\Controllers\Teacher\CircleController::class, 'update'])->name('circles.update');
    Route::get('/circles/{circle}/students', [App\Http\Controllers\Teacher\CircleController::class, 'students'])->name('circles.students');
    
    // Daily Reports
    Route::get('/daily-reports', [App\Http\Controllers\Teacher\DailyReportController::class, 'index'])->name('daily-reports.index');
    Route::post('/daily-reports', [App\Http\Controllers\Teacher\DailyReportController::class, 'store'])->name('daily-reports.store');
    Route::post('/daily-reports/bulk', [App\Http\Controllers\Teacher\DailyReportController::class, 'bulkStore'])->name('daily-reports.bulk-store');
    Route::delete('/daily-reports/{report}', [App\Http\Controllers\Teacher\DailyReportController::class, 'destroy'])->name('daily-reports.destroy');
    Route::get('/daily-reports/history', [App\Http\Controllers\Teacher\DailyReportController::class, 'history'])->name('daily-reports.history');
    
    // Points Management
    Route::get('/points', [App\Http\Controllers\Teacher\PointsController::class, 'index'])->name('points.index');
    Route::post('/points', [App\Http\Controllers\Teacher\PointsController::class, 'update'])->name('points.update');
    Route::post('/points/bulk', [App\Http\Controllers\Teacher\PointsController::class, 'bulkUpdate'])->name('points.bulk-update');
    Route::get('/points/student/{student}', [App\Http\Controllers\Teacher\PointsController::class, 'history'])->name('points.history');
    Route::get('/points/leaderboard', [App\Http\Controllers\Teacher\PointsController::class, 'leaderboard'])->name('points.leaderboard');
    
    // Rewards viewing (read-only)
    Route::prefix('rewards')->name('rewards.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teacher\RewardController::class, 'index'])->name('index');
        Route::get('/{reward}', [\App\Http\Controllers\Teacher\RewardController::class, 'show'])->name('show');
    });
});

// Student routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    
    // Circle management for students
    Route::get('/circles', [\App\Http\Controllers\Student\CircleController::class, 'index'])->name('circles.index');
    Route::get('/circles/{circle}', [\App\Http\Controllers\Student\CircleController::class, 'show'])->name('circles.show');
    Route::get('/browse-circles', [\App\Http\Controllers\Student\CircleController::class, 'browseCircles'])->name('circles.browse');
    Route::post('/enroll', [\App\Http\Controllers\Student\CircleController::class, 'enroll'])->name('circles.enroll');
    
    // Daily Report shortcut (for backward compatibility)
    Route::get('/daily-report', [App\Http\Controllers\Student\ReportController::class, 'create'])->name('daily-report');
    
    // Progress routes
    Route::get('/progress', [App\Http\Controllers\Student\ProgressController::class, 'index'])->name('progress');
    Route::get('/progress/index', [App\Http\Controllers\Student\ProgressController::class, 'index'])->name('progress.index');
    Route::get('/progress/points', [App\Http\Controllers\Student\ProgressController::class, 'points'])->name('progress.points');
    Route::get('/progress/attendance', [App\Http\Controllers\Student\ProgressController::class, 'attendance'])->name('progress.attendance');
    
    // Subscriptions shortcut (for backward compatibility)
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    
    // Daily Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    Route::delete('/reports/date/{date}', [ReportController::class, 'destroyByDate'])->name('reports.destroyByDate');
    
    // Student Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::put('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    // Subscription routes
    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index'])->name('index');
        Route::get('/create', [SubscriptionController::class, 'create'])->name('create');
        Route::get('/plans', [SubscriptionController::class, 'getPlans'])->name('plans');
        Route::post('/', [SubscriptionController::class, 'store'])->name('store');
        Route::get('/{subscription}', [SubscriptionController::class, 'show'])->name('show');
        Route::get('/{subscription}/payment', [SubscriptionController::class, 'showPayment'])->name('payment');
        Route::post('/{subscription}/process-payment', [PaymentController::class, 'processPayment'])->name('process-payment');
    });
    
    // Rewards routes
    Route::prefix('rewards')->name('rewards.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\RewardController::class, 'index'])->name('index');
        Route::get('/{reward}', [\App\Http\Controllers\Student\RewardController::class, 'show'])->name('show');
        Route::post('/{reward}/redeem', [\App\Http\Controllers\Student\RewardController::class, 'redeem'])->name('redeem');
        Route::get('/my/redemptions', [\App\Http\Controllers\Student\RewardController::class, 'redemptions'])->name('redemptions');
    });
});

// Supervisor routes
Route::middleware(['auth', 'role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/dashboard', function () {
        return view('supervisor.dashboard');
    })->name('dashboard');
    
    // Circle management
    Route::get('/circles', [SupervisorCircleController::class, 'index'])->name('circles.index');
    Route::get('/circles/{circle}', [SupervisorCircleController::class, 'show'])->name('circles.show');
    Route::get('/circles/{circle}/edit', [SupervisorCircleController::class, 'edit'])->name('circles.edit');
    Route::put('/circles/{circle}', [SupervisorCircleController::class, 'update'])->name('circles.update');
    
    // Student management within circles
    Route::get('/circles/{circle}/manage-students', [SupervisorCircleController::class, 'manageStudents'])->name('circles.manage-students');
    Route::post('/circles/{circle}/students', [SupervisorCircleController::class, 'addStudent'])->name('circles.students.add');
    Route::delete('/circles/{circle}/students/{student}', [SupervisorCircleController::class, 'removeStudent'])->name('circles.students.remove');
    Route::get('/circles/{circle}/students/{student}', [SupervisorCircleController::class, 'viewStudent'])->name('circles.students.view');
    
    // Rewards viewing (read-only)
    Route::prefix('rewards')->name('rewards.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Supervisor\RewardController::class, 'index'])->name('index');
        Route::get('/{reward}', [\App\Http\Controllers\Supervisor\RewardController::class, 'show'])->name('show');
    });
});

// Payment Routes
Route::prefix('payment')->group(function () {
    Route::post('process/{subscription}', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
});

// Webhook Routes
Route::post('webhooks/tap', [PaymentController::class, 'handleWebhook'])->name('webhooks.tap');