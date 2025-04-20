@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ t('Manage Students') }} - {{ $circle->name }}</h5>
                    <a href="{{ route('supervisor.circles.show', $circle) }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> {{ t('Back to Circle') }}
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ t('Current Students') }} ({{ $currentStudents->count() }} / {{ $circle->max_students ?: t('Unlimited') }})</h6>
                                </div>
                                <div class="card-body p-0">
                                    @if($currentStudents->isEmpty())
                                        <div class="alert alert-info m-3">
                                            {{ t('No students enrolled in this circle yet.') }}
                                        </div>
                                    @else
                                        <div class="list-group list-group-flush">
                                            @foreach($currentStudents as $student)
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">{{ $student->name }}</h6>
                                                        <small>{{ $student->email }} | {{ $student->phone }}</small>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('supervisor.circles.students.view', ['circle' => $circle, 'student' => $student]) }}" class="btn btn-sm btn-info me-1">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <form method="POST" action="{{ route('supervisor.circles.students.remove', ['circle' => $circle, 'student' => $student]) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ t('Are you sure you want to remove this student from the circle?') }}')">
                                                                <i class="bi bi-person-dash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">{{ t('Add Student to Circle') }}</h6>
                                </div>
                                <div class="card-body">
                                    @if($circle->max_students && $currentStudents->count() >= $circle->max_students)
                                        <div class="alert alert-warning">
                                            {{ t('This circle has reached its maximum capacity.') }}
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('supervisor.circles.students.add', $circle) }}">
                                            @csrf
                                            
                                            <div class="mb-3">
                                                <label for="student_id" class="form-label">{{ t('Select Student') }}</label>
                                                <select class="form-select @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
                                                    <option value="">{{ t('Choose a student') }}</option>
                                                    @foreach($potentialStudents as $student)
                                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                            {{ $student->name }} ({{ $student->email }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('student_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-person-plus"></i> {{ t('Add Student') }}
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            
                            @if($potentialStudents->isEmpty())
                                <div class="alert alert-info mt-3">
                                    {{ t('No more students available to add.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 