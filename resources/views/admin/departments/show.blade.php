@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ $department->name }}</h1>
        <div>
            <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil"></i> {{ t('Edit') }}
            </a>
            <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> {{ t('Back to Departments') }}
            </a>
        </div>
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

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    {{ t('Department Information') }}
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Description') }}</h6>
                        <p>{{ $department->description ?? t('No description available') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Student Gender') }}</h6>
                        <span class="badge bg-{{ $department->student_gender == 'male' ? 'primary' : ($department->student_gender == 'female' ? 'info' : 'secondary') }}">
                            {{ ucfirst($department->student_gender) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Registration Status') }}</h6>
                        @if($department->registration_open)
                            <span class="badge bg-success">{{ t('Open') }}</span>
                        @else
                            <span class="badge bg-danger">{{ t('Closed') }}</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Working Days') }}</h6>
                        <ul class="list-inline mb-0">
                            @if($department->work_monday)
                                <li class="list-inline-item badge bg-light text-dark">{{ t('Monday') }}</li>
                            @endif
                            @if($department->work_tuesday)
                                <li class="list-inline-item badge bg-light text-dark">{{ t('Tuesday') }}</li>
                            @endif
                            @if($department->work_wednesday)
                                <li class="list-inline-item badge bg-light text-dark">{{ t('Wednesday') }}</li>
                            @endif
                            @if($department->work_thursday)
                                <li class="list-inline-item badge bg-light text-dark">{{ t('Thursday') }}</li>
                            @endif
                            @if($department->work_friday)
                                <li class="list-inline-item badge bg-light text-dark">{{ t('Friday') }}</li>
                            @endif
                            @if($department->work_saturday)
                                <li class="list-inline-item badge bg-light text-dark">{{ t('Saturday') }}</li>
                            @endif
                            @if($department->work_sunday)
                                <li class="list-inline-item badge bg-light text-dark">{{ t('Sunday') }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    {{ t('Subscription Fees') }}
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th width="40%">{{ t('Monthly') }}</th>
                                <td>
                                    @if($department->monthly_fees)
                                        {{ number_format($department->monthly_fees, 2) }} {{ t('EGP') }}
                                    @else
                                        <span class="text-muted">{{ t('Not available') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ t('Quarterly') }}</th>
                                <td>
                                    @if($department->quarterly_fees)
                                        {{ number_format($department->quarterly_fees, 2) }} {{ t('EGP') }}
                                    @else
                                        <span class="text-muted">{{ t('Not available') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ t('Biannual') }}</th>
                                <td>
                                    @if($department->biannual_fees)
                                        {{ number_format($department->biannual_fees, 2) }} {{ t('EGP') }}
                                    @else
                                        <span class="text-muted">{{ t('Not available') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ t('Annual') }}</th>
                                <td>
                                    @if($department->annual_fees)
                                        {{ number_format($department->annual_fees, 2) }} {{ t('EGP') }}
                                    @else
                                        <span class="text-muted">{{ t('Not available') }}</span>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ t('Study Circles') }} ({{ $department->circles->count() }})</span>
                    <a href="{{ route('admin.circles.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> {{ t('Create Circle') }}
                    </a>
                </div>
                <div class="card-body">
                    @if($department->circles->isEmpty())
                        <div class="alert alert-info">
                            {{ t('no_available_circles') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ t('Name') }}</th>
                                        <th>{{ t('Teacher') }}</th>
                                        <th>{{ t('Students') }}</th>
                                        <th>{{ t('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($department->circles as $circle)
                                        <tr>
                                            <td>{{ $circle->name }}</td>
                                            <td>
                                                @if($circle->teacher)
                                                    {{ $circle->teacher->name }}
                                                @else
                                                    <span class="text-danger">{{ t('No teacher assigned') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $circleStudentCounts[$circle->id] ?? 0 }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.circles.show', $circle) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.circles.edit', $circle) }}" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ route('admin.circles.add-student', $circle) }}" class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-person-plus"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Add more sections like statistics, recent activities, etc. as needed -->
        </div>
    </div>
</div>
@endsection 