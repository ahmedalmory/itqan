<nav class="nav flex-column py-3">
    <a class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i> {{ t('dashboard') }}
    </a>
    <a class="nav-link {{ request()->is('admin/circles*') ? 'active' : '' }}" href="{{ route('admin.circles.index') }}">
        <i class="bi bi-people"></i> {{ t('study_circles') }}
    </a>
    <a class="nav-link {{ request()->is('admin/departments*') ? 'active' : '' }}" href="{{ route('admin.departments.index') }}">
        <i class="bi bi-diagram-3"></i> {{ t('departments') }}
    </a>
    <a class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
        <i class="bi bi-person"></i> {{ t('users') }}
    </a>
    <a class="nav-link {{ request()->is('admin/points*') ? 'active' : '' }}" href="{{ route('admin.points.index') }}">
        <i class="bi bi-star"></i> {{ t('points') }}
    </a>
    <a class="nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
        <i class="bi bi-file-earmark-text"></i> {{ t('reports') }}
    </a>
    <a class="nav-link {{ request()->is('admin/subscriptions*') ? 'active' : '' }}" href="{{ route('admin.subscriptions.index') }}">
        <i class="bi bi-credit-card"></i> {{ t('subscriptions') }}
    </a>
    <a class="nav-link {{ request()->is('admin/translations*') ? 'active' : '' }}" href="{{ route('admin.translations.index') }}">
        <i class="bi bi-translate"></i> {{ t('translations') }}
    </a>
    <a class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings') }}">
        <i class="bi bi-gear"></i> {{ t('settings') }}
    </a>
</nav> 