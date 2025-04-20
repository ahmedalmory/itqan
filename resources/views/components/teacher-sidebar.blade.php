<nav class="nav flex-column py-3">
    <a class="nav-link {{ request()->is('teacher/dashboard*') ? 'active' : '' }}" href="{{ route('teacher.dashboard') }}">
        <i class="bi bi-speedometer2"></i> {{ t('dashboard') }}
    </a>
    <a class="nav-link {{ request()->is('teacher/circles*') ? 'active' : '' }}" href="{{ route('teacher.circles.index') }}">
        <i class="bi bi-people"></i> {{ t('my_circles') }}
    </a>
    <a class="nav-link {{ request()->is('teacher/reports*') ? 'active' : '' }}" href="{{ route('teacher.reports') }}">
        <i class="bi bi-file-earmark-text"></i> {{ t('student_reports') }}
    </a>
    <a class="nav-link {{ request()->is('teacher/attendance*') ? 'active' : '' }}" href="{{ route('teacher.attendance') }}">
        <i class="bi bi-calendar-check"></i> {{ t('attendance') }}
    </a>
</nav>