@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ $circle->name }}</h1>
        <div>
            <a href="{{ route('admin.circles.edit', $circle) }}" class="btn btn-primary me-2">
                <i class="bi bi-pencil"></i> {{ t('Edit') }}
            </a>
            <a href="{{ route('admin.circles.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> {{ t('Back to Circles') }}
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
                    {{ t('Circle Information') }}
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Department') }}</h6>
                        @if($circle->department)
                            <p>
                                <a href="{{ route('admin.departments.show', $circle->department) }}">
                                    {{ $circle->department->name }}
                                </a>
                            </p>
                        @else
                            <p class="text-muted">{{ t('Not assigned') }}</p>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Description') }}</h6>
                        <p>{{ $circle->description ?? t('No description available') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Status') }}</h6>
                        @if($circle->is_active)
                            <span class="badge bg-success">{{ t('Active') }}</span>
                        @else
                            <span class="badge bg-danger">{{ t('Inactive') }}</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Age Range') }}</h6>
                        <p>{{ $circle->age_from }} - {{ $circle->age_to }} {{ t('years') }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Capacity') }}</h6>
                        <p>{{ $circle->max_students }} {{ t('students') }}</p>
                    </div>
                    
                    @if($circle->location)
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Location') }}</h6>
                        <p>{{ $circle->location }}</p>
                    </div>
                    @endif
                    
                    @if($circle->meeting_time)
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Meeting Time') }}</h6>
                        <p>{{ $circle->meeting_time }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    {{ t('Staff') }}
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Teacher') }}</h6>
                        @if($circle->teacher)
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <i class="bi bi-person-circle fs-4"></i>
                                </div>
                                <div>
                                    <div>{{ $circle->teacher->name }}</div>
                                    <small class="text-muted">{{ $circle->teacher->email }}</small>
                                </div>
                            </div>
                        @else
                            <p class="text-danger">{{ t('No teacher assigned') }}</p>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ t('Supervisor') }}</h6>
                        @if($circle->supervisor)
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <i class="bi bi-person-circle fs-4"></i>
                                </div>
                                <div>
                                    <div>{{ $circle->supervisor->name }}</div>
                                    <small class="text-muted">{{ $circle->supervisor->email }}</small>
                                </div>
                            </div>
                        @else
                            <p class="text-muted">{{ t('No supervisor assigned') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ t('Students') }} ({{ $students->count() }}/{{ $circle->max_students }})</span>
                    <a href="{{ route('admin.circles.add-student', $circle) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-person-plus"></i> {{ t('Add Student') }}
                    </a>
                </div>
                <div class="card-body">
                    @if($students->isEmpty())
                        <div class="alert alert-info">
                            {{ t('No students enrolled in this circle') }}
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ t('Name') }}</th>
                                        <th>{{ t('Email') }}</th>
                                        <th>{{ t('Phone') }}</th>
                                        <th>{{ t('Enrollment Date') }}</th>
                                        <th>{{ t('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->phone }}</td>
                                            <td>
                                                @if($student->pivot && $student->pivot->created_at)
                                                    {{ $student->pivot->created_at->format('Y-m-d') }}
                                                @else
                                                    <span class="text-muted">{{ t('Unknown') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <form action="{{ route('admin.circles.remove-student', ['circle' => $circle->id, 'student' => $student->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ t('Are you sure you want to remove this student from the circle?') }}')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-person-dash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $students->links() }}
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Activity logs or additional information can be added here -->
        </div>
    </div>
</div>
@endsection 