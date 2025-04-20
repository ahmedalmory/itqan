@extends('layouts.dashboard')

@section('dashboard-content')
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ t('circle_details') }}</h5>
            <a href="{{ route('student.circles.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> {{ t('back_to_circles') }}
            </a>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">{{ t('circle_information') }}</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>{{ t('name') }}</th>
                                    <td>{{ $circle->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('department') }}</th>
                                    <td>{{ $circle->department->name ?? t('not_available') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('description') }}</th>
                                    <td>{{ $circle->description ?? t('no_description') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('capacity') }}</th>
                                    <td>{{ $circle->capacity ?? t('not_specified') }}</td>
                                </tr>
                                <tr>
                                    <th>{{ t('status') }}</th>
                                    <td>
                                        @if($circle->is_active)
                                            <span class="badge bg-success">{{ t('active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ t('inactive') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>{{ t('schedule') }}</th>
                                    <td>{{ $circle->schedule ?? t('not_scheduled') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0">{{ t('teacher_information') }}</h6>
                        </div>
                        <div class="card-body">
                            @if($circle->teacher)
                                <div class="text-center mb-3">
                                    <div class="avatar avatar-lg mb-3">
                                        <span class="avatar-text rounded-circle">
                                            {{ substr($circle->teacher->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <h5>{{ $circle->teacher->name }}</h5>
                                    <p class="text-muted">{{ t('teacher') }}</p>
                                </div>
                                
                                <table class="table table-bordered">
                                    <tr>
                                        <th>{{ t('email') }}</th>
                                        <td>{{ $circle->teacher->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ t('phone') }}</th>
                                        <td>{{ $circle->teacher->phone ?? t('not_available') }}</td>
                                    </tr>
                                </table>
                            @else
                                <div class="alert alert-info">
                                    {{ t('no_teacher_assigned') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">{{ t('students_in_circle') }}</h6>
                </div>
                <div class="card-body">
                    @if(isset($students) && count($students) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ t('name') }}</th>
                                        <th>{{ t('join_date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->pivot->created_at->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            {{ t('no_students_in_circle') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 