@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Points Leaderboard') }} 🏆</h1>
        <a href="{{ route('teacher.points.index') }}" class="btn btn-outline-secondary">
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

    <div class="card shadow-sm">
        <div class="card-header bg-light py-3">
            <form method="GET" action="{{ route('teacher.points.leaderboard') }}" class="row g-3 align-items-center">
                <div class="col-md-4">
                    <select name="circle_id" class="form-select" onchange="this.form.submit()">
                        <option value="">{{ t('All Circles') }}</option>
                        @foreach($circles as $circle)
                            <option value="{{ $circle->id }}" {{ request('circle_id') == $circle->id ? 'selected' : '' }}>
                                {{ $circle->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-body">
            @if($leaderboard->isEmpty())
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ t('No students found') }}
                </div>
            @else
                <!-- Top 3 Podium -->
                <div class="row mb-5 position-relative">
                    <!-- Decorative Background -->
                    <div class="position-absolute top-0 start-0 end-0 bottom-0" style="background: linear-gradient(180deg, rgba(255,215,0,0.1) 0%, rgba(255,255,255,0) 100%); border-radius: 1rem; z-index: 0;"></div>

                    <div class="col-md-4 order-md-2">
                        <!-- First Place -->
                        @if($leaderboard->isNotEmpty())
                            <div class="text-center position-relative first-place">
                                <div class="crown position-absolute top-0 start-50 translate-middle-x">
                                    <i class="bi bi-crown-fill text-warning display-4 animated-crown"></i>
                                </div>
                                <div class="avatar-xl mx-auto mb-2 mt-4 position-relative">
                                    <div class="trophy position-absolute top-0 start-50 translate-middle-x">
                                        <i class="bi bi-trophy-fill text-warning display-4 glow-gold"></i>
                                    </div>
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                        <span class="text-white h4 mb-0">{{ t('First') }}</span>
                                    </div>
                                </div>
                                <h5 class="mb-1">{{ $leaderboard[0]->student->name }}</h5>
                                <p class="mb-0 text-primary fw-bold">{{ $leaderboard[0]->total_points }} {{ t('points') }}</p>
                                @if(!$selectedCircleId)
                                    <small class="text-muted">{{ $leaderboard[0]->circle->name }}</small>
                                @endif
                                <div class="mt-2">
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-star-fill me-1"></i>
                                        {{ t('Champion') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4 order-md-1 d-flex align-items-end">
                        <!-- Second Place -->
                        @if($leaderboard->count() > 1)
                            <div class="text-center w-100">
                                <div class="avatar-lg mx-auto mb-2 position-relative">
                                    <div class="trophy position-absolute top-0 start-50 translate-middle-x">
                                        <i class="bi bi-trophy-fill text-secondary display-4 glow-silver"></i>
                                    </div>
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                        <span class="text-white h5 mb-0">{{ t('Second') }}</span>
                                    </div>
                                </div>
                                <h5 class="mb-1">{{ $leaderboard[1]->student->name }}</h5>
                                <p class="mb-0 text-secondary fw-bold">{{ $leaderboard[1]->total_points }} {{ t('points') }}</p>
                                @if(!$selectedCircleId)
                                    <small class="text-muted">{{ $leaderboard[1]->circle->name }}</small>
                                @endif
                                <div class="mt-2">
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-star-fill me-1"></i>
                                        {{ t('Runner Up') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-4 order-md-3 d-flex align-items-end">
                        <!-- Third Place -->
                        @if($leaderboard->count() > 2)
                            <div class="text-center w-100">
                                <div class="avatar-lg mx-auto mb-2 position-relative">
                                    <div class="trophy position-absolute top-0 start-50 translate-middle-x">
                                        <i class="bi bi-trophy-fill text-orange display-4 glow-bronze"></i>
                                    </div>
                                    <div class="rounded-circle bg-orange d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                        <span class="text-white h5 mb-0">{{ t('Third') }}</span>
                                    </div>
                                </div>
                                <h5 class="mb-1">{{ $leaderboard[2]->student->name }}</h5>
                                <p class="mb-0 text-orange fw-bold">{{ $leaderboard[2]->total_points }} {{ t('points') }}</p>
                                @if(!$selectedCircleId)
                                    <small class="text-muted">{{ $leaderboard[2]->circle->name }}</small>
                                @endif
                                <div class="mt-2">
                                    <span class="badge bg-orange">
                                        <i class="bi bi-star-fill me-1"></i>
                                        {{ t('Bronze Winner') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Rest of the Leaderboard -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" width="80">{{ t('Rank') }}</th>
                                <th>{{ t('Student') }}</th>
                                @if(!$selectedCircleId)
                                    <th>{{ t('Circle') }}</th>
                                @endif
                                <th class="text-end">{{ t('Points') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaderboard->slice(3) as $index => $points)
                                <tr>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-light text-dark">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="ms-2">
                                                <h6 class="mb-0">{{ $points->student->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    @if(!$selectedCircleId)
                                        <td>{{ $points->circle->name }}</td>
                                    @endif
                                    <td class="text-end">
                                        <span class="badge bg-{{ $points->total_points >= 100 ? 'success' : ($points->total_points >= 50 ? 'info' : 'secondary') }}">
                                            {{ $points->total_points }} {{ t('points') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $leaderboard->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.text-orange {
    color: #cd7f32;
}
.bg-orange {
    background-color: #cd7f32;
}
.animated-crown {
    animation: float 2s ease-in-out infinite;
    filter: drop-shadow(0 0 10px rgba(255, 193, 7, 0.5));
}
.glow-gold {
    filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.7));
    animation: pulse 2s infinite;
}
.glow-silver {
    filter: drop-shadow(0 0 8px rgba(192, 192, 192, 0.7));
    animation: pulse 2s infinite 0.3s;
}
.glow-bronze {
    filter: drop-shadow(0 0 8px rgba(205, 127, 50, 0.7));
    animation: pulse 2s infinite 0.6s;
}
@keyframes float {
    0%, 100% { transform: translateY(0) rotate(-10deg); }
    50% { transform: translateY(-15px) rotate(10deg); }
}
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}
.trophy {
    transition: transform 0.3s ease;
}
.trophy:hover {
    transform: scale(1.2);
}
.badge {
    font-weight: 500;
    padding: 0.5em 1em;
}
.table > :not(caption) > * > * {
    padding: 1rem 0.75rem;
}
.pagination {
    margin-bottom: 0;
}
.page-link {
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    margin: 0 0.25rem;
}
</style>
@endsection 