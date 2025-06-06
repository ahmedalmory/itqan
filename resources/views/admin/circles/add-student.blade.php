@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ t('Add Student to') }}: {{ $circle->name }}</h1>
        <a href="{{ route('admin.circles.show', $circle) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> {{ t('Back to Circle') }}
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

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    {{ t('Circle Information') }}
                </div>
                <div class="card-body">
                    <p><strong>{{ t('Department') }}:</strong> {{ $circle->department->name }}</p>
                    <p><strong>{{ t('Age Range') }}:</strong> {{ $circle->age_from }} - {{ $circle->age_to }} {{ t('years') }}</p>
                    <p><strong>{{ t('Capacity') }}:</strong> {{ $circle->students->count() }}/{{ $circle->max_students }} {{ t('students') }}</p>
                    @if($circle->teacher)
                        <p><strong>{{ t('Teacher') }}:</strong> {{ $circle->teacher->name }}</p>
                    @endif
                    @if($circle->location)
                        <p><strong>{{ t('Location') }}:</strong> {{ $circle->location }}</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ t('Add Student') }}
                </div>
                <div class="card-body">
                    @if($circle->students->count() >= $circle->max_students)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i> {{ t('This circle has reached its maximum capacity.') }}
                        </div>
                    @endif
                    
                    @if($students->isEmpty())
                        <div class="alert alert-info">
                            {{ t('No eligible students found matching the circle criteria.') }}
                        </div>
                    @else
                        <form action="{{ route('admin.circles.store-student', $circle) }}" method="POST">
                            @csrf
                            
                            @if($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label for="student_id" class="form-label">{{ t('Select Student') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
                                    <option value="">{{ t('Select a student to add') }}</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} ({{ $student->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary" {{ $circle->students->count() >= $circle->max_students ? 'disabled' : '' }}>
                                    <i class="bi bi-person-plus"></i> {{ t('Add Student') }}
                                </button>
                            </div>
                        </form>
                        
                        <hr class="my-4">
                        
                        <div class="mb-3">
                            <h5>{{ t('Quick Search') }}</h5>
                            <div class="input-group">
                                <input type="text" class="form-control" id="studentSearch" placeholder="{{ t('Search by name or email...') }}">
                                <button class="btn btn-outline-secondary" type="button">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="studentsTable">
                                <thead>
                                    <tr>
                                        <th>{{ t('Name') }}</th>
                                        <th>{{ t('Email') }}</th>
                                        <th>{{ t('Phone') }}</th>
                                        <th>{{ t('Age') }}</th>
                                        <th>{{ t('preferred_time') }}</th>
                                        <th>{{ t('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $student)
                                        <tr>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->phone }}</td>
                                            <td>{{ $student->age }}</td>
                                            <td>{{ t($student->preferred_time) }}</td>
                                            <td>
                                                <form action="{{ route('admin.circles.store-student', $circle) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-success" {{ $circle->students->count() >= $circle->max_students ? 'disabled' : '' }}>
                                                        <i class="bi bi-plus-circle"></i> {{ t('Add') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('studentSearch');
        const table = document.getElementById('studentsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        searchInput.addEventListener('keyup', function() {
            const query = searchInput.value.toLowerCase();
            
            for (let i = 0; i < rows.length; i++) {
                const nameCell = rows[i].getElementsByTagName('td')[0];
                const emailCell = rows[i].getElementsByTagName('td')[1];
                
                if (nameCell && emailCell) {
                    const name = nameCell.textContent || nameCell.innerText;
                    const email = emailCell.textContent || emailCell.innerText;
                    
                    if (name.toLowerCase().indexOf(query) > -1 || email.toLowerCase().indexOf(query) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection