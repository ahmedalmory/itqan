<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $pageTitle ?? config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    @if(app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    
    <!-- Fonts and Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
    
    <style>
        /* Base Variables */
        :root {
            --primary-color: #1FA959;
            --primary-dark: #198A47;
            --primary-light: #F0F9F2;
            --secondary-color: #8D6E63;
            --accent-color: #FFC107;
            --header-pattern: url("data:image/svg+xml,%3Csvg width='52' height='26' viewBox='0 0 52 26' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4v2c-3.314 0-6-2.686-6-6 0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6zm25.464-1.95l8.486 8.486-1.414 1.414-8.486-8.486 1.414-1.414z' /%3E%3C/g%3E%3C/svg%3E");
        }

        /* Global Styles */
        body {
            font-family: 'Noto Kufi Arabic', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus,
        .btn-outline-primary:active {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(31, 169, 89, 0.15);
        }

        .card-header {
            background-color: var(--primary-light);
            border-bottom: none;
            border-top-left-radius: 15px !important;
            border-top-right-radius: 15px !important;
            font-weight: 600;
        }

        /* Navbar Styles */
        .navbar {
            background-color: var(--primary-color) !important;
            padding: 0.75rem 0;
            box-shadow: 0 4px 20px rgba(31, 169, 89, 0.15);
            position: relative;
            z-index: 1000;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .navbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: var(--header-pattern);
            opacity: 0.1;
            pointer-events: none;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.4rem;
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            transform: translateY(-2px);
        }

        .brand-icon {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover .brand-icon {
            background-color: rgba(255, 255, 255, 0.3);
            transform: rotate(5deg);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .nav-link:hover, .nav-link.active {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        /* Form Styles */
        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 0.75rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(31, 169, 89, 0.25);
        }

        .form-label {
            font-weight: 500;
        }

        /* Table Styles */
        .table {
            vertical-align: middle;
        }

        .table th {
            font-weight: 600;
            background-color: var(--primary-light);
            border: none;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.5rem 0.75rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .status-active {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }

        .status-inactive {
            background-color: #ffebee;
            color: #d32f2f;
        }

        .status-pending {
            background-color: #fff8e1;
            color: #ff8f00;
        }

        /* Avatar and User Menu */
        .avatar-circle {
            width: 30px;
            height: 30px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            color: white;
            font-size: 0.9rem;
        }

        .user-dropdown {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-dropdown:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Dropdown Styles */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 16px;
            padding: 0.75rem 0.5rem;
            margin-top: 0.75rem;
            background-color: #fff;
            animation: dropdownFade 0.3s ease;
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 12px;
            transition: all 0.2s ease;
            margin: 0.25rem 0;
        }

        .dropdown-item:hover, .dropdown-item:focus {
            background-color: var(--primary-light);
            color: var(--primary-color);
            transform: translateX(3px);
        }

        .dropdown-item.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .dropdown-divider {
            opacity: 0.1;
            margin: 0.5rem 0;
        }

        /* Language Switcher */
        .lang-switcher-btn {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.4rem 1rem;
            transition: all 0.3s ease;
        }

        .lang-switcher-btn:hover, 
        .lang-switcher-btn:focus {
            background-color: rgba(255, 255, 255, 0.25);
            color: white;
            transform: translateY(-2px);
        }

        .lang-switcher-btn:active {
            transform: translateY(0);
        }

        .flag-icon {
            font-size: 1.1rem;
        }

        /* Page Title */
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 2rem;
        }

        /* Main Content Area */
        main {
            flex: 1;
        }

        /* SweetAlert2 Customization */
        .swal2-popup {
            font-family: 'Noto Kufi Arabic', sans-serif;
        }
        
        .swal2-confirm {
            background-color: var(--primary-color) !important;
        }
        
        .swal2-confirm:focus {
            box-shadow: 0 0 0 3px rgba(31, 169, 89, 0.5) !important;
        }
        
        .swal2-styled.swal2-cancel {
            background-color: #dc3545 !important;
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('partials.navbar')
    
    <main>
        @if (!empty($pageHeader))
            <div class="container py-4">
                <h1 class="page-title">{{ $pageHeader }}</h1>
            </div>
        @endif
        
        @include('partials.alerts')
        
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-light py-3 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    
    <script>
        // Handle SweetAlert confirmations
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmations
            const deleteButtons = document.querySelectorAll('.delete-confirm');
            
            if (deleteButtons) {
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        const form = this.closest('form');
                        const name = this.dataset.name || '';
                        
                        Swal.fire({
                            title: 'هل أنت متأكد؟',
                            text: `هل تريد حذف "${name}"؟`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'نعم، احذف',
                            cancelButtonText: 'إلغاء'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            }
            
            // Form validation messages
            const forms = document.querySelectorAll('.needs-validation');
            
            if (forms) {
                Array.from(forms).forEach(form => {
                    form.addEventListener('submit', event => {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        
                        form.classList.add('was-validated');
                    }, false);
                });
            }
            
            // Initialize popovers
            const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
            if (popoverTriggerList) {
                const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
            }
            
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            if (tooltipTriggerList) {
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            }
        });

        // Auto-dismiss alerts
        window.setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            
            if (alerts) {
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>