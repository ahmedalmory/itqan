@extends('layouts.dashboard')

@section('dashboard-content')
<style>
.islamic-pattern {
    background-color: #f8f9fa;
    background-image: url('data:image/svg+xml,<svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><path d="M20 0L0 20h40L20 0zm0 40L40 20H0l20 20z" fill="%23e9ecef" fill-opacity="0.4"/></svg>');
    padding: 0rem;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.stats-container {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
}

.stats-card {
    flex: 1;
    background: linear-gradient(135deg, #004d40 0%, #00695c 100%);
    color: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
    pointer-events: none;
}

.stats-card .value {
    font-size: 2.5rem;
    font-weight: bold;
    margin: 0.5rem 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

.stats-card .label {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
}

.stats-card .icon {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.circle-list {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-top: 1rem;
}

.circle-item {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.circle-item .rank {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
    font-size: 0.8rem;
}

.report-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    border: 1px solid #e9ecef;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.report-card .card-header {
    background-color: #004d40;
    color: white;
    padding: 1rem;
    text-align: center;
    font-family: "Traditional Arabic", serif;
}

.report-card.holiday .card-header {
    background-color: #7b1fa2;
}

.report-card.no-report .card-header {
    background-color: #616161;
}

.report-card.excellent .card-header {
    background-color: #2e7d32;
}

.report-card.good .card-header {
    background-color: #1565c0;
}

.report-card.pass .card-header {
    background-color: #ef6c00;
}

.report-card.poor .card-header {
    background-color: #c62828;
}

.report-card .card-body {
    padding: 1.5rem;
    text-align: center;
    flex-grow: 1;
}

.report-card .card-footer {
    background-color: #f8f9fa;
    padding: 1rem;
    text-align: center;
    margin-top: auto;
}

.report-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
}

.status-excellent {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.status-good {
    background-color: #e3f2fd;
    color: #1565c0;
}

.status-pass {
    background-color: #fff3e0;
    color: #ef6c00;
}

.status-poor {
    background-color: #ffebee;
    color: #c62828;
}

.status-holiday {
    background-color: #f3e5f5;
    color: #7b1fa2;
}

.status-no-report {
    background-color: #fafafa;
    color: #616161;
}

.report-stats {
    display: flex;
    justify-content: space-around;
    margin: 1rem 0;
    font-family: "Traditional Arabic", serif;
}

.stat-item {
    text-align: center;
    padding: 0.5rem;
}

.stat-value {
    font-size: 1.2rem;
    font-weight: bold;
    color: #004d40;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
}

.surah-range {
    font-size: 0.9rem;
    color: #666;
    margin-top: 1rem;
    padding: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 5px;
}

.action-btn {
    background-color: #004d40;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    transition: all 0.3s;
}

.action-btn:hover {
    background-color: #00695c;
    color: white;
    transform: translateY(-2px);
}

.action-btn.delete-btn {
    background-color: #dc3545;
    margin-left: 0.5rem;
}

.action-btn.delete-btn:hover {
    background-color: #c82333;
}

.welcome-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 2rem;
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.welcome-header i {
    font-size: 2rem;
    color: #004d40;
    margin-bottom: 1rem;
}

.welcome-header h3 {
    color: #004d40;
    margin-bottom: 0;
}

.points-bubble {
    position: fixed;
    top: 20px;
    right: 20px;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #004d40 0%, #00695c 100%);
    border-radius: 50%;
    color: white;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 1000;
}

.points-bubble:hover {
    transform: scale(1.1);
}

.points-bubble .value {
    font-size: 1.5rem;
    font-weight: bold;
    line-height: 1;
    margin-bottom: 2px;
}

.points-bubble .label {
    font-size: 0.7rem;
    opacity: 0.9;
}

.points-details {
    position: fixed;
    top: 110px;
    right: 20px;
    background: white;
    border-radius: 10px;
    padding: 1rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 200px;
    display: none;
    z-index: 999;
}

.points-details.show {
    display: block;
}

.points-details .circle-item {
    background: #f8f9fa;
    color: #004d40;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    border-radius: 5px;
    font-size: 0.8rem;
}

.points-details .circle-item:last-child {
    margin-bottom: 0;
}

.points-details .circle-name {
    font-weight: bold;
    margin-bottom: 0.2rem;
}

.points-details .circle-stats {
    display: flex;
    justify-content: space-between;
    color: #666;
}
</style>

<!-- Add SweetAlert2 -->
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">
@endpush

<div class="welcome-header">
    <i class="bi bi-book"></i>
    <h3>{{ t('welcome_message') }} {{ Auth::user()->name }}</h3>
</div>

<!-- Points Bubble -->
<div class="points-bubble" onclick="togglePointsDetails()">
    <div class="value">{{ number_format($total_points) }}</div>
    <div class="label">{{ t('points') }}</div>
</div>

<!-- Points Details -->
<div class="points-details" id="pointsDetails">
    @foreach ($points_data as $data)
    <div class="circle-item">
        <div class="circle-name">{{ $data->circle_name }}</div>
        <div class="circle-stats">
            <span>{{ number_format($data->points) }} {{ t('points') }}</span>
            <span>{{ number_format($data->student_rank) }}/{{ number_format($data->total_students) }}</span>
        </div>
    </div>
    @endforeach
</div>

<div class="islamic-pattern">
    <!-- Student Info Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">{{ t('circle_info') }}</h5>
        </div>
        <div class="card-body">
            @if($circle)
            <div class="row">
                <div class="col-md-6">
                    <p><strong>{{ t('department') }}:</strong> {{ $circle->department->name ?? t('not_assigned') }}</p>
                    <p><strong>{{ t('circle') }}:</strong> {{ $circle->name ?? t('not_assigned') }}</p>
                    <p><strong>{{ t('teacher') }}:</strong> {{ $circle->teacher->name ?? t('not_assigned') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>{{ t('supervisor') }}:</strong> {{ $circle->supervisor->name ?? t('not_assigned') }}</p>
                    <p><strong>{{ t('circle_time') }}:</strong> {{ $circle->circle_time ? t($circle->circle_time) : t('not_assigned') }}</p>
                    <p><strong>{{ t('gender') }}:</strong> {{ $circle->department->student_gender ? t($circle->department->student_gender) : t('not_specified') }}</p>
                </div>
            </div>
            <div class="mt-3">
                @if (!empty($circle->whatsapp_group))
                    <a href="{{ $circle->whatsapp_group }}" class="btn btn-success btn-sm" target="_blank">
                        <i class="bi bi-whatsapp"></i> {{ t('join_whatsapp_group') }}
                    </a>
                @endif
                
                @if (!empty($circle->telegram_group))
                    <a href="{{ $circle->telegram_group }}" class="btn btn-info btn-sm" target="_blank">
                        <i class="bi bi-telegram"></i> {{ t('join_telegram_group') }}
                    </a>
                @endif
            </div>
            @else
            <div class="alert alert-warning">
                {{ t('not_enrolled_in_circle') }}
                <a href="{{ route('student.circles.browse') }}" class="alert-link">{{ t('browse_circles') }}</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Subscription Info Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">{{ t('subscription_info') }}</h5>
        </div>
        <div class="card-body">
            @if($active_subscription)
                @php
                $end_date = new DateTime($active_subscription->end_date);
                $today = new DateTime();
                $days_remaining = $today->diff($end_date)->days;
                $is_expiring_soon = $days_remaining <= 7;
                @endphp
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ t('plan') }}:</strong> {{ $active_subscription->plan->lessons_per_month }} {{ t('lessons') }}</p>
                        <p><strong>{{ t('duration') }}:</strong> {{ $active_subscription->duration_months }} {{ t('months') }}</p>
                        <p><strong>{{ t('payment_status') }}:</strong> 
                            @if($active_subscription->payment_status === 'paid')
                                <span class="badge bg-success">{{ t('paid') }}</span>
                            @elseif($active_subscription->payment_status === 'pending')
                                <span class="badge bg-warning">{{ t('pending') }}</span>
                            @else
                                <span class="badge bg-danger">{{ t($active_subscription->payment_status) }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ t('start_date') }}:</strong> {{ date('Y-m-d', strtotime($active_subscription->start_date)) }}</p>
                        <p><strong>{{ t('end_date') }}:</strong> {{ date('Y-m-d', strtotime($active_subscription->end_date)) }}</p>
                        <p><strong>{{ t('days_remaining') }}:</strong> 
                            <span class="{{ $is_expiring_soon ? 'text-danger fw-bold' : '' }}">
                                {{ $days_remaining }} {{ t('days') }}
                            </span>
                            @if($is_expiring_soon)
                                <span class="badge bg-danger">{{ t('expiring_soon') }}</span>
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="mt-3">
                    <a href="{{ route('student.subscriptions.index') }}" class="btn btn-primary">
                        <i class="bi bi-card-list"></i> {{ t('view_subscription_details') }}
                    </a>
                    @if($is_expiring_soon)
                        <a href="{{ route('student.subscriptions.renew') }}" class="btn btn-warning">
                            <i class="bi bi-arrow-repeat"></i> {{ t('renew_subscription') }}
                        </a>
                    @endif
                </div>
            @else
                <div class="alert alert-warning">
                    <p>{{ t('no_active_subscription') }}</p>
                    <p>{{ t('please_subscribe_to_continue') }}</p>
                </div>
                
                @if(count($subscription_plans) > 0)
                    <h5 class="mt-3 mb-3">{{ t('available_plans') }}</h5>
                    <div class="row">
                        @foreach($subscription_plans as $plan)
                            <div class="col-md-6 col-lg-3 mb-3">
                                <div class="card h-100 border-primary">
                                    <div class="card-header bg-primary text-white text-center">
                                        <h5 class="mb-0">{{ $plan->lessons_per_month }} {{ t('lessons') }}</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <h3 class="card-title pricing-card-title">
                                            {{ $plan->price }} {{ t('currency') }}
                                            <small class="text-muted">/ {{ t('month') }}</small>
                                        </h3>
                                        <a href="{{ route('student.subscriptions.subscribe', ['plan' => $plan->id]) }}" class="btn btn-outline-primary mt-3">
                                            {{ t('subscribe_now') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>{{ t('no_plans_available') }}</p>
                @endif
                
                <div class="mt-3">
                    <a href="{{ route('student.subscriptions.index') }}" class="btn btn-primary">
                        <i class="bi bi-card-list"></i> {{ t('view_all_subscription_options') }}
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        @foreach($days as $day)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="report-card {{ 
                    !$day['is_working_day'] && !$day['report'] ? 'holiday' : 
                    (!$day['report'] ? 'no-report' : 
                    ($day['report']['grade'] >= 90 ? 'excellent' : 
                    ($day['report']['grade'] >= 75 ? 'good' : 
                    ($day['report']['grade'] >= 60 ? 'pass' : 'poor')))) 
                }}">
                    <div class="card-header">
                        <h5 class="mb-0">
                            {{ t($day['day_name']) }}
                            <br>
                            <small>{{ date('Y/m/d', strtotime($day['date'])) }}</small>
                            @if(!$day['is_working_day'])
                                <br>
                                <small class="text-warning"><i class="bi bi-moon-stars"></i> {{ t('holiday') }}</small>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(!$day['is_working_day'] && !$day['report'])
                            <div class="report-icon status-holiday">
                                <i class="bi bi-moon-stars"></i>
                            </div>
                            <div class="stat-value">{{ t('holiday') }}</div>
                        @elseif($day['report'])
                            <div class="report-icon {{ 
                                $day['report']['grade'] >= 90 ? 'status-excellent' : 
                                ($day['report']['grade'] >= 75 ? 'status-good' : 
                                ($day['report']['grade'] >= 60 ? 'status-pass' : 'status-poor')) 
                            }}">
                                <i class="bi bi-mortarboard"></i>
                            </div>
                            <div class="report-stats">
                                <div class="stat-item">
                                    <div class="stat-value">{{ number_format($day['report']['memorization_parts'], 1) }}</div>
                                    <div class="stat-label">{{ t('memorization') }}</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ number_format($day['report']['revision_parts'], 1) }}</div>
                                    <div class="stat-label">{{ t('revision') }}</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-value">{{ (int)$day['report']['grade'] }}%</div>
                                    <div class="stat-label">{{ t('grade') }}</div>
                                </div>
                            </div>
                            <div class="surah-range">
                                <i class="bi bi-bookmark"></i>
                                {{ $day['report']['from_surah_name'] }} 
                                ({{ $day['report']['memorization_from_verse'] }}) 
                                - 
                                {{ $day['report']['to_surah_name'] }}
                                ({{ $day['report']['memorization_to_verse'] }})
                            </div>
                        @else
                            <div class="report-icon status-no-report">
                                <i class="bi bi-journal-plus"></i>
                            </div>
                            <div class="stat-value">{{ t('no_report') }}</div>
                        @endif
                    </div>
                    <div class="card-footer">
                        @if($day['report'])
                            <a href="{{ route('student.reports.edit', ['report' => $day['report']['id']]) }}" class="action-btn">
                                <i class="bi bi-pencil"></i> 
                            </a>
                            <button onclick="confirmDelete('{{ $day['date'] }}')" class="action-btn delete-btn">
                                <i class="bi bi-trash"></i> 
                            </button>
                        @else
                            <a href="{{ route('student.reports.create', ['date' => $day['date']]) }}" class="action-btn">
                                <i class="bi bi-plus-circle"></i> 
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">{{ t('reports_summary') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ t('date') }}</th>
                            <th>{{ t('memorization') }}</th>
                            <th>{{ t('revision') }}</th>
                            <th>{{ t('grade') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($days as $day)
                        <tr>
                            <td>{{ date('Y/m/d', strtotime($day['date'])) }}</td>
                            <td>{{ $day['report'] ? number_format($day['report']['memorization_parts'], 1) : 0 }}</td>
                            <td>{{ $day['report'] ? number_format($day['report']['revision_parts'], 1) : 0 }}</td>
                            <td>{{ $day['report'] ? (int)$day['report']['grade'] : 0 }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function togglePointsDetails() {
    const details = document.getElementById('pointsDetails');
    details.classList.toggle('show');
}

// Close details when clicking outside
document.addEventListener('click', function(event) {
    const bubble = document.querySelector('.points-bubble');
    const details = document.getElementById('pointsDetails');
    
    if (!bubble.contains(event.target) && !details.contains(event.target)) {
        details.classList.remove('show');
    }
});

function confirmDelete(date) {
    Swal.fire({
        title: '{{ t("confirm_delete") }}',
        text: '{{ t("delete_report_confirmation") }}',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ t("yes_delete") }}',
        cancelButtonText: '{{ t("cancel") }}',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Send delete request
            axios.delete(`/student/reports/date/${date}`)
                .then(response => {
                    Swal.fire({
                        title: '{{ t("deleted") }}',
                        text: '{{ t("report_deleted_successfully") }}',
                        icon: 'success'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire({
                        title: '{{ t("error") }}',
                        text: '{{ t("error_deleting_report") }}',
                        icon: 'error'
                    });
                });
        }
    });
}
</script>
@endsection 