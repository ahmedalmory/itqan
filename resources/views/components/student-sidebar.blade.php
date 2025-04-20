<nav class="nav flex-column py-3">
    <a class="nav-link {{ request()->is('student/dashboard*') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
        <i class="bi bi-speedometer2"></i> {{ t('dashboard') }}
    </a>
    <a class="nav-link {{ request()->is('student/daily-report*') ? 'active' : '' }}" href="{{ route('student.daily-report') }}">
        <i class="bi bi-journal-text"></i> {{ t('daily_report') }}
    </a>
    <a class="nav-link {{ request()->is('student/progress*') ? 'active' : '' }}" href="{{ route('student.progress') }}">
        <i class="bi bi-graph-up"></i> {{ t('my_progress') }}
    </a>
    <a class="nav-link {{ request()->is('student/circles*') ? 'active' : '' }}" href="{{ route('student.circles.index') }}">
        <i class="bi bi-info-circle"></i> {{ t('circle_information') }}
    </a>
    <a class="nav-link {{ request()->is('student/subscriptions*') ? 'active' : '' }}" href="{{ route('student.subscriptions') }}">
        <i class="bi bi-credit-card"></i> {{ t('my_subscriptions') }}
    </a>    
</nav> 