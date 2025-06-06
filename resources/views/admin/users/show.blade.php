@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ t('User Details') }}</h5>
                        <div>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                                <i class="bi bi-arrow-left me-1"></i> {{ t('Back to Users') }}
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i> {{ t('Edit User') }}
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3 d-flex align-items-center justify-content-center bg-light rounded-circle">
                            <span class="display-4 text-primary">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted mb-1">{{ $user->email }}</p>
                        
                        <div class="mb-2">
                            @if($user->role === 'admin')
                                <span class="badge bg-danger">{{ t('Admin') }}</span>
                            @elseif($user->role === 'supervisor')
                                <span class="badge bg-warning">{{ t('Supervisor') }}</span>
                            @elseif($user->role === 'teacher')
                                <span class="badge bg-info">{{ t('Teacher') }}</span>
                            @elseif($user->role === 'student')
                                <span class="badge bg-success">{{ t('Student') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $user->role }}</span>
                            @endif
                            
                            @if($user->is_active)
                                <span class="badge bg-success ms-1">{{ t('Active') }}</span>
                            @else
                                <span class="badge bg-danger ms-1">{{ t('Inactive') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0">{{ t('User Information') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label text-muted">{{ t('Phone Number') }}</label>
                                                <p class="mb-0">{{ $user->phone ?? t('Not provided') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label text-muted">{{ t('Gender') }}</label>
                                                <p class="mb-0">{{ $user->gender ? t(ucfirst($user->gender)) : t('Not specified') }}</p>
                                            </div>
                                        </div>
                                        
                                        @if(in_array($user->role, ['supervisor', 'teacher', 'student']))
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label text-muted">{{ t('Department') }}</label>
                                                <p class="mb-0">{{ $user->department->name ?? t('Not assigned') }}</p>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if(in_array($user->role, ['student']))
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label text-muted">{{ t('Study Circle') }}</label>
                                                <p class="mb-0">{{ $user->studyCircle->name ?? t('Not assigned') }}</p>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label text-muted">{{ t('Email Verified') }}</label>
                                                <p class="mb-0">
                                                    @if($user->email_verified_at)
                                                        <span class="text-success">
                                                            <i class="bi bi-check-circle-fill me-1"></i>
                                                            {{ t('Verified on') }} {{ $user->email_verified_at->format('M d, Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-danger">
                                                            <i class="bi bi-x-circle-fill me-1"></i>
                                                            {{ t('Not verified') }}
                                                        </span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label text-muted">{{ t('Registered On') }}</label>
                                                <p class="mb-0">{{ $user->created_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label text-muted">{{ t('Last Updated') }}</label>
                                                <p class="mb-0">{{ $user->updated_at->format('M d, Y h:i A') }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label text-muted">{{ t('Name') }}</label>
                                                <p class="mb-0">{{ $user->name }}</p>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="form-label text-muted">{{ t('national_id') }}</label>
                                                <p class="mb-0">{{ $user->national_id ?? t('Not provided') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($user->id !== auth()->id())
                    <div class="mt-4">
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                            <i class="bi bi-trash me-1"></i> {{ t('Delete User') }}
                        </button>
                    </div>
                    
                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteUserModalLabel">{{ t('Confirm Delete') }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>{{ t('Are you sure you want to delete this user?') }}</p>
                                    <div class="alert alert-danger">
                                        {{ t('This action cannot be undone. All data associated with this user will be permanently removed.') }}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('Cancel') }}</button>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{ t('Delete User') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-lg {
    width: 80px;
    height: 80px;
}
</style>
@endsection 