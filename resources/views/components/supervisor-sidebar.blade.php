<nav class="nav flex-column py-3">
    <a class="nav-link {{ request()->is('supervisor/dashboard*') ? 'active' : '' }}" href="{{ route('supervisor.dashboard') }}">
        <i class="bi bi-speedometer2"></i> {{ t('dashboard') }}
    </a>
    <a class="nav-link {{ request()->is('supervisor/circles*') ? 'active' : '' }}" href="{{ route('supervisor.circles.index') }}">
        <i class="bi bi-people"></i> {{ t('supervised_circles') }}
    </a>
    <a class="nav-link {{ request()->is('supervisor/reports*') ? 'active' : '' }}" href="{{ route('supervisor.reports') }}">
        <i class="bi bi-file-earmark-text"></i> {{ t('review_reports') }}
    </a>
</nav> 