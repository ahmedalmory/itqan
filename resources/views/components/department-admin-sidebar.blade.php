<nav class="nav flex-column py-3">
    <a class="nav-link {{ request()->is('department-admin/dashboard*') ? 'active' : '' }}" href="{{ route('department-admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i> {{ t('dashboard') }}
    </a>
    <a class="nav-link {{ request()->is('department-admin/circles*') ? 'active' : '' }}" href="{{ route('department-admin.circles.index') }}">
        <i class="bi bi-people"></i> {{ t('study_circles') }}
    </a>
    <a class="nav-link {{ request()->is('department-admin/teachers*') ? 'active' : '' }}" href="{{ route('department-admin.teachers.index') }}">
        <i class="bi bi-person-workspace"></i> {{ t('teachers') }}
    </a>
    <a class="nav-link {{ request()->is('department-admin/students*') ? 'active' : '' }}" href="{{ route('department-admin.students.index') }}">
        <i class="bi bi-mortarboard"></i> {{ t('students') }}
    </a>
    <a class="nav-link {{ request()->is('department-admin/points*') ? 'active' : '' }}" href="{{ route('department-admin.points.index') }}">
        <i class="bi bi-star"></i> {{ t('points') }}
    </a>
    <a class="nav-link {{ request()->is('department-admin/reports*') ? 'active' : '' }}" href="{{ route('department-admin.reports') }}">
        <i class="bi bi-file-earmark-text"></i> {{ t('reports') }}
    </a>
</nav> 