@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('browse_circles') }}</h5>
            <a href="{{ route('student.circles.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> {{ t('back_to_my_circles') }}
            </a>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            <form action="{{ route('student.circles.browse') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="department" class="form-label">{{ t('department') }}</label>
                        <select class="form-select" id="department" name="department">
                            <option value="">{{ t('all_departments') }}</option>
                            @foreach($departments ?? [] as $department)
                                <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">{{ t('search') }}</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" 
                               placeholder="{{ t('search_by_name_or_teacher') }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> {{ t('search') }}
                        </button>
                        <a href="{{ route('student.circles.browse') }}" class="btn btn-outline-secondary ms-2">
                            {{ t('reset') }}
                        </a>
                    </div>
                </div>
            </form>
            
            @if(isset($availableCircles) && $availableCircles->count() > 0)
                <div class="row">
                    @foreach($availableCircles as $circle)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">{{ $circle->name }}</h6>
                                        @if($circle->is_active)
                                            <span class="badge bg-success">{{ t('active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ t('inactive') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-3">
                                        <strong>{{ t('department') }}:</strong> {{ $circle->department->name ?? t('not_available') }}
                                    </p>
                                    
                                    <p class="text-muted mb-3">
                                        <strong>{{ t('teacher') }}:</strong> {{ $circle->teacher->name ?? t('not_assigned') }}
                                    </p>
                                    
                                    @if($circle->schedule)
                                        <p class="text-muted mb-3">
                                            <strong>{{ t('schedule') }}:</strong> {{ $circle->schedule }}
                                        </p>
                                    @endif
                                    
                                    <p class="text-muted mb-3">
                                        <strong>{{ t('capacity') }}:</strong> 
                                        {{ $circle->student_count ?? 0 }}/{{ $circle->capacity ?? t('unlimited') }}
                                    </p>
                                    
                                    @if($circle->description)
                                        <p class="mb-3">{{ Str::limit($circle->description, 100) }}</p>
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <form action="{{ route('student.circles.enroll') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="circle_id" value="{{ $circle->id }}">
                                        <button type="submit" class="btn btn-primary btn-sm w-100" 
                                                {{ (!$circle->is_active || ($circle->capacity && $circle->student_count >= $circle->capacity)) ? 'disabled' : '' }}>
                                            {{ t('enroll') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{-- {{ $afvailableCircles->links() }} --}}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ t('no_available_circles') }}
                </div>
            @endif
        </div>
    </div>
@endsection 