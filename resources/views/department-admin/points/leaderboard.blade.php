@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Points Leaderboard') }}</h1>
        <a href="{{ route('department-admin.points.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> {{ t('Back to Points') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('department-admin.points.leaderboard') }}" class="row g-3">
                <div class="col-md-6">
                    <select name="circle_id" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ t('All Circles') }}</option>
                        @foreach($circles as $circle)
                            <option value="{{ $circle->id }}" {{ $selectedCircle && $selectedCircle->id == $circle->id ? 'selected' : '' }}>
                                {{ $circle->name }} ({{ $circle->students_count }} {{ t('students') }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($students->isEmpty())
                <div class="alert alert-info">
                    {{ t('No students found') }}
                </div>
            @else
                <!-- Top 3 Winners Section -->
                <div class="row mb-5">
                    <!-- Second Place -->
                    <div class="col-md-4 order-md-1">
                        @if($students->count() > 1)
                            <div class="text-center">
                                <div class="position-relative d-inline-block">
                                    <div class="trophy silver-trophy mb-3">
                                        <i class="bi bi-trophy-fill display-1 text-secondary"></i>
                                        <span class="position-absolute top-50 start-50 translate-middle badge rounded-pill bg-secondary">2</span>
                                    </div>
                                </div>
                                <h4 class="mb-2">{{ $students[1]->name }}</h4>
                                <p class="text-muted mb-1">{{ t('Second Place') }} - المركز الثاني</p>
                                <h5><span class="badge bg-secondary">{{ $students[1]->total_points }} {{ t('points') }}</span></h5>
                            </div>
                        @endif
                    </div>
                    
                    <!-- First Place -->
                    <div class="col-md-4 order-md-0">
                        @if($students->isNotEmpty())
                            <div class="text-center">
                                <div class="position-relative d-inline-block">
                                    <div class="trophy gold-trophy mb-3">
                                        <i class="bi bi-trophy-fill display-1 text-warning"></i>
                                        <span class="position-absolute top-50 start-50 translate-middle badge rounded-pill bg-warning text-dark">1</span>
                                    </div>
                                    <div class="crown position-absolute top-0 start-50 translate-middle">
                                        <i class="bi bi-crown-fill text-warning fs-2"></i>
                                    </div>
                                </div>
                                <h3 class="mb-2">{{ $students[0]->name }}</h3>
                                <p class="text-muted mb-1">{{ t('First Place') }} - المركز الأول</p>
                                <h4><span class="badge bg-warning text-dark">{{ $students[0]->total_points }} {{ t('points') }}</span></h4>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Third Place -->
                    <div class="col-md-4 order-md-2">
                        @if($students->count() > 2)
                            <div class="text-center">
                                <div class="position-relative d-inline-block">
                                    <div class="trophy bronze-trophy mb-3">
                                        <i class="bi bi-trophy-fill display-1 text-danger"></i>
                                        <span class="position-absolute top-50 start-50 translate-middle badge rounded-pill bg-danger">3</span>
                                    </div>
                                </div>
                                <h4 class="mb-2">{{ $students[2]->name }}</h4>
                                <p class="text-muted mb-1">{{ t('Third Place') }} - المركز الثالث</p>
                                <h5><span class="badge bg-danger">{{ $students[2]->total_points }} {{ t('points') }}</span></h5>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Rest of the Leaderboard -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ t('Rank') }}</th>
                                <th>{{ t('Student') }}</th>
                                <th>{{ t('Points') }}</th>
                                <th>{{ t('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                                @if($index > 2) {{-- Skip first 3 as they're shown above --}}
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $index + 1 }}</span>
                                        </td>
                                        <td>{{ $student->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $student->total_points >= 0 ? 'success' : 'danger' }}">
                                                {{ $student->total_points }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('department-admin.points.history', $student->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="bi bi-clock-history"></i> {{ t('History') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.trophy {
    position: relative;
    transform: scale(1);
    transition: transform 0.3s ease;
}

.trophy:hover {
    transform: scale(1.1);
}

.gold-trophy {
    filter: drop-shadow(0 0 10px rgba(255, 193, 7, 0.5));
}

.silver-trophy {
    filter: drop-shadow(0 0 10px rgba(108, 117, 125, 0.5));
}

.bronze-trophy {
    filter: drop-shadow(0 0 10px rgba(220, 53, 69, 0.5));
}

.crown {
    animation: float 2s ease-in-out infinite;
}

@keyframes float {
    0% {
        transform: translateY(0px) rotate(-10deg);
    }
    50% {
        transform: translateY(-10px) rotate(10deg);
    }
    100% {
        transform: translateY(0px) rotate(-10deg);
    }
}
</style>
@endsection 