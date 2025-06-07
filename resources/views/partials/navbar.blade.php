<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <!-- Logo and Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <span class="brand-icon me-2">
                <i class="bi bi-book"></i>
            </span>
            <span class="brand-text fw-bold">{{ t('app_name') }}</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarMain" aria-controls="navbarMain" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collapsible Content -->
        <div class="collapse navbar-collapse" id="navbarMain">
            <!-- Main Navigation -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                @guest
                    <li class="nav-item mx-1">
                        <a class="nav-link rounded-pill px-3 {{ Route::currentRouteName() == 'login' ? 'active' : '' }}" 
                           href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> {{ t('login') }}
                        </a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link rounded-pill px-3 {{ Route::currentRouteName() == 'register' ? 'active' : '' }}" 
                           href="{{ route('register') }}">
                            <i class="bi bi-person-plus me-1"></i> {{ t('register') }}
                        </a>
                    </li>
                @else
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isDepartmentAdmin())
                        <li class="nav-item mx-1">
                            <a class="nav-link rounded-pill px-3 {{ request()->is('admin/dashboard') ? 'active' : '' }}" 
                               href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i> {{ t('dashboard') }}
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link rounded-pill px-3 {{ request()->is('admin/users*') ? 'active' : '' }}" 
                               href="{{ route('admin.users.index') }}">
                                <i class="bi bi-person me-1"></i> {{ t('users') }}
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link rounded-pill px-3 {{ request()->is('admin/departments*') ? 'active' : '' }}" 
                               href="{{ route('admin.departments.index') }}">
                                <i class="bi bi-building me-1"></i> {{ t('departments') }}
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link rounded-pill px-3 {{ request()->is('admin/circles*') ? 'active' : '' }}" 
                               href="{{ route('admin.circles.index') }}">
                                <i class="bi bi-circle me-1"></i> {{ t('study_circles') }}
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link rounded-pill px-3 {{ request()->is('admin/points*') ? 'active' : '' }}" 
                               href="{{ route('admin.points.index') }}">
                                <i class="bi bi-star me-1"></i> {{ t('points') }}
                            </a>
                        </li>
                        
                        <!-- Language Management -->
                        <li class="nav-item dropdown mx-1">
                            <a class="nav-link dropdown-toggle rounded-pill px-3 {{ request()->is('admin/languages*') || request()->is('admin/translations*') ? 'active' : '' }}" 
                               href="#" id="languageDropdown" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-translate me-1"></i> {{ t('language_management') }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/languages*') ? 'active' : '' }}" 
                                       href="{{ route('admin.languages.index') }}">
                                        <i class="bi bi-globe me-2"></i> {{ t('languages') }}
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->is('admin/translations*') ? 'active' : '' }}" 
                                       href="{{ route('admin.translations.index') }}">
                                        <i class="bi bi-chat-text me-2"></i> {{ t('translations') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @elseif(Auth::user()->isTeacher())
                        <li class="nav-item mx-1">
                            <a class="nav-link rounded-pill px-3 {{ request()->is('teacher/dashboard') ? 'active' : '' }}" 
                               href="{{ route('teacher.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i> {{ t('dashboard') }}
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link rounded-pill px-3 {{ request()->is('teacher/circles*') ? 'active' : '' }}" 
                               href="{{ route('teacher.circles.index') }}">
                                <i class="bi bi-people me-1"></i> {{ t('my_circles') }}
                            </a>
                        </li>
                    @elseif(Auth::user()->isSupervisor())
                        <li class="nav-item mx-1">
                            <a class="nav-link rounded-pill px-3 {{ request()->is('supervisor/dashboard') ? 'active' : '' }}" 
                               href="{{ route('supervisor.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i> {{ t('dashboard') }}
                            </a>
                        </li>
                    @elseif(Auth::user()->isStudent())
                        <li class="nav-item mx-1">
                            <a class="nav-link rounded-pill px-3 {{ request()->is('student/dashboard') ? 'active' : '' }}" 
                               href="{{ route('student.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i> {{ t('dashboard') }}
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link rounded-pill px-3 {{ request()->is('student/circles*') ? 'active' : '' }}" 
                               href="{{ route('student.circles.index') }}">
                                <i class="bi bi-people me-1"></i> {{ t('my_circles') }}
                            </a>
                        </li>
                        <li class="nav-item mx-1">
                            <a class="nav-link rounded-pill px-3 {{ request()->is('student/browse-circles') ? 'active' : '' }}" 
                               href="{{ route('student.circles.browse') }}">
                                <i class="bi bi-search me-1"></i> {{ t('browse_circles') }}
                            </a>
                        </li>
                    @endif
                @endguest
            </ul>

            <!-- Right Side Navigation -->
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center user-dropdown px-3 rounded-pill" 
                           href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="avatar-circle me-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <span class="fw-bold">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 py-2" 
                            aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item px-3 py-2 rounded-3 mx-1" href="{{ url('/profile') }}">
                                    <i class="bi bi-person me-2"></i> {{ t('profile') }}
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item px-3 py-2 rounded-3 mx-1" href="{{ route('password.change') }}">
                                    <i class="bi bi-key me-2"></i> {{ t('change_password') }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider mx-2"></li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <a class="dropdown-item px-3 py-2 rounded-3 mx-1 text-danger" href="#" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> {{ t('logout') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                @endauth
                
                <!-- Language Switcher -->
                <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                    <x-language-switcher />
                </li>
            </ul>
        </div>
    </div>
</nav>