@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ t('Users Management') }}</h5>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i> {{ t('Add New User') }}
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Search and Filters -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="{{ t('Search users...') }}" value="{{ request()->search }}">
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-md-end">
                                <div class="me-2">
                                    <select name="role" id="roleFilter" class="form-select form-select-sm">
                                        <option value="">{{ t('All Roles') }}</option>
                                        <option value="department_admin" {{ request()->role === 'department_admin' ? 'selected' : '' }}>{{ t('department_admin') }}</option>
                                        <option value="supervisor" {{ request()->role === 'supervisor' ? 'selected' : '' }}>{{ t('Supervisor') }}</option>
                                        <option value="teacher" {{ request()->role === 'teacher' ? 'selected' : '' }}>{{ t('Teacher') }}</option>
                                        <option value="student" {{ request()->role === 'student' ? 'selected' : '' }}>{{ t('Student') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <select name="status" id="statusFilter" class="form-select form-select-sm">
                                        <option value="">{{ t('All Status') }}</option>
                                        <option value="active" {{ request()->status === 'active' ? 'selected' : '' }}>{{ t('Active') }}</option>
                                        <option value="inactive" {{ request()->status === 'inactive' ? 'selected' : '' }}>{{ t('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">{{ t('Name') }}</th>
                                    <th scope="col">{{ t('Email') }}</th>
                                    <th scope="col">{{ t('Role') }}</th>
                                    <th scope="col">{{ t('Department') }}</th>
                                    <th scope="col">{{ t('Status') }}</th>
                                    <th scope="col">{{ t('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <span class="text-primary">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                {{ $user->name }}
                                                @if($user->id === auth()->id())
                                                <span class="badge bg-info ms-1">{{ t('You') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role === 'super_admin')
                                            <span class="badge bg-danger">{{ t('super_admin') }}</span>
                                        @elseif($user->role === 'department_admin')
                                            <span class="badge bg-danger">{{ t('department_admin') }}</span>
                                        @elseif($user->role === 'supervisor')
                                            <span class="badge bg-warning">{{ t('supervisor') }}</span>
                                        @elseif($user->role === 'teacher')
                                            <span class="badge bg-info">{{ t('Teacher') }}</span>
                                        @elseif($user->role === 'student')
                                            <span class="badge bg-success">{{ t('Student') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $user->role }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $user->department->name ?? '-' }}
                                    </td>
                                    <td>
                                        @if($user->is_active)
                                            <span class="badge bg-success">{{ t('Active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ t('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-primary">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endif
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">{{ t('Confirm Delete') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ t('Are you sure you want to delete user') }}: <strong>{{ $user->name }}</strong>?
                                                        <p class="text-danger mt-2 mb-0">{{ t('This action cannot be undone.') }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('Cancel') }}</button>
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">{{ t('Delete') }}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                                            {{ t('No users found') }}
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Role and Status filter handling
        const roleFilter = document.getElementById('roleFilter');
        const statusFilter = document.getElementById('statusFilter');
        
        function applyFilters() {
            const currentUrl = new URL(window.location.href);
            
            // Set role filter
            if (roleFilter.value) {
                currentUrl.searchParams.set('role', roleFilter.value);
            } else {
                currentUrl.searchParams.delete('role');
            }
            
            // Set status filter
            if (statusFilter.value) {
                currentUrl.searchParams.set('status', statusFilter.value);
            } else {
                currentUrl.searchParams.delete('status');
            }
            
            // Redirect to filtered URL
            window.location.href = currentUrl.toString();
        }
        
        roleFilter.addEventListener('change', applyFilters);
        statusFilter.addEventListener('change', applyFilters);
    });
</script>
@endpush
@endsection 