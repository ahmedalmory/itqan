@extends('layouts.app')

@section('content')
<div class="select-circle-page">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <h1 class="page-title text-center mb-4">{{ t('select_circle') }}</h1>
                        <p class="text-center mb-4">{{ t('select_circle_welcome_message') }}</p>

                        <div class="row">
                            @forelse($departments as $department)
                                <div class="col-md-6 mb-4">
                                    <div class="department-card">
                                        <h3 class="department-title">{{ $department->name }}</h3>
                                        <p class="department-description">{{ $department->description }}</p>
                                        
                                        @if($department->circles->count() > 0)
                                            <div class="circles-list">
                                                @foreach($department->circles as $circle)
                                                    <div class="circle-item">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h4 class="circle-name">{{ $circle->name }}</h4>
                                                            <span class="circle-capacity">
                                                                {{ $circle->students->count() }} / {{ $circle->max_students ?: '∞' }}
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="circle-details">
                                                            <p>{{ t('age_range') }}: {{ $circle->age_from }} - {{ $circle->age_to }}</p>
                                                            @if($circle->circle_time)
                                                                <p>{{ t('circle_time') }}: {{ t($circle->circle_time) }}</p>
                                                            @endif
                                                            @if($circle->teacher)
                                                                <p>{{ t('teacher') }}: {{ $circle->teacher->name }}</p>
                                                            @endif
                                                        </div>
                                                        
                                                        <form method="POST" action="{{ route('select-circle') }}">
                                                            @csrf
                                                            <input type="hidden" name="circle_id" value="{{ $circle->id }}">
                                                            <button type="submit" class="btn btn-primary" 
                                                                {{ ($circle->max_students && $circle->students->count() >= $circle->max_students) ? 'disabled' : '' }}>
                                                                {{ t('join_circle') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="alert alert-info">
                                                {{ t('no_circles_available') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        {{ t('no_departments_available') }}
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.select-circle-page {
    background-color: #f8f9fa;
    min-height: 100vh;
}

.department-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    height: 100%;
}

.department-title {
    color: var(--primary-dark);
    font-size: 1.4rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
    border-bottom: 2px solid var(--primary-light);
    padding-bottom: 0.5rem;
}

.department-description {
    color: #666;
    margin-bottom: 1.5rem;
}

.circles-list {
    max-height: 400px;
    overflow-y: auto;
}

.circle-item {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.circle-name {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: var(--primary-color);
}

.circle-capacity {
    background-color: var(--primary-light);
    color: var(--primary-dark);
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
}

.circle-details {
    margin: 0.75rem 0;
    font-size: 0.9rem;
}

.circle-details p {
    margin-bottom: 0.25rem;
}
</style>
@endpush 