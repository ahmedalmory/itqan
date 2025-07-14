@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">
            @if($date->isToday())
                {{ t('Today\'s Tasks') }}
            @else
                {{ t('Tasks for') }} {{ $date->format('l, F j, Y') }}
            @endif
        </h1>
        <div>
            <button type="button" class="btn btn-success me-2" onclick="shareReport()">
                <i class="bi bi-share me-1"></i> {{ t('Share Report') }}
            </button>
            <button type="button" class="btn btn-info" onclick="bulkComplete()">
                <i class="bi bi-check-all me-1"></i> {{ t('Mark Selected as Done') }}
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fs-2 fw-bold">{{ $assignments->count() }}</div>
                            <div>{{ t('Total Tasks') }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-list-task fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fs-2 fw-bold">{{ $assignments->filter(fn($a) => $a->isCompleted())->count() }}</div>
                            <div>{{ t('Completed') }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fs-2 fw-bold">{{ $assignments->filter(fn($a) => !$a->isCompleted())->count() }}</div>
                            <div>{{ t('Pending') }}</div>
                        </div>
                        <div class="align-self-center">
                            <i class="bi bi-clock-history fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($assignments->count() > 0)
        <!-- Progress Bar -->
        @php
            $totalTasks = $assignments->count();
            $completedTasks = $assignments->filter(fn($a) => $a->isCompleted())->count();
            $completionPercentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 1) : 0;
        @endphp
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ t('Progress') }}</h5>
                <div class="progress mb-2" style="height: 25px;">
                    <div class="progress-bar progress-bar-striped" role="progressbar" 
                         style="width: {{ $completionPercentage }}%;" 
                         aria-valuenow="{{ $completionPercentage }}" 
                         aria-valuemin="0" aria-valuemax="100">
                        {{ $completionPercentage }}%
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">{{ t('Completed: :count/:total', ['count' => $completedTasks, 'total' => $totalTasks]) }}</span>
                    <span class="text-muted">{{ t('Remaining: :count', ['count' => $totalTasks - $completedTasks]) }}</span>
                </div>
            </div>
        </div>

        <!-- Tasks List -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ t('Tasks for Today') }}</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">
                            {{ t('Select All') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @foreach($assignments as $assignment)
                    <div class="task-item card mb-3 {{ $assignment->isCompleted() ? 'border-success' : 'border-warning' }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-1">
                                    @if(!$assignment->isCompleted())
                                        <div class="form-check">
                                            <input class="form-check-input task-checkbox" type="checkbox" 
                                                   name="task_ids[]" value="{{ $assignment->task_id }}">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-7">
                                    <h6 class="mb-1">
                                        {{ $assignment->task->title }}
                                        <span class="badge bg-{{ $assignment->task->getPriorityColor() }} ms-2">
                                            {{ ucfirst($assignment->task->priority) }}
                                        </span>
                                    </h6>
                                    @if($assignment->task->description)
                                        <p class="text-muted mb-1">{{ $assignment->task->description }}</p>
                                    @endif
                                    @if($assignment->task->department)
                                        <small class="text-muted">
                                            <i class="bi bi-building me-1"></i>{{ $assignment->task->department->name }}
                                        </small>
                                    @endif
                                </div>
                                <div class="col-md-2">
                                    <span class="badge bg-{{ $assignment->getStatusColor() }} fs-6">
                                        {{ ucfirst($assignment->status) }}
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <div class="btn-group" role="group">
                                        @if($assignment->isCompleted())
                                            <button type="button" class="btn btn-sm btn-success" disabled>
                                                <i class="bi bi-check-lg me-1"></i> {{ t('Done') }}
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                                    onclick="uncompleteTask({{ $assignment->task_id }}, '{{ $date->format('Y-m-d') }}')">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="completeTask({{ $assignment->task_id }}, '{{ $date->format('Y-m-d') }}')">
                                                <i class="bi bi-check-lg me-1"></i> {{ t('Mark Done') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            @if($assignment->completion)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            {{ t('Completed on :date', ['date' => $assignment->completion->completed_at->format('Y-m-d H:i')]) }}
                                        </span>
                                        <span class="text-muted">{{ t('By: :name', ['name' => $assignment->completion->completedBy->name]) }}</span>
                                    </div>
                                    @if($assignment->completion->notes)
                                        <div class="mt-2">
                                            <strong>{{ t('Notes:') }}</strong> {{ $assignment->completion->notes }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                <!-- Bulk Actions -->
                <div class="bulk-actions-section mt-3" style="display: none;">
                    <div class="card">
                        <div class="card-body">
                            <h6>{{ t('Bulk Action') }}</h6>
                            <form id="bulkCompletionForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <textarea class="form-control" name="notes" 
                                                  placeholder="{{ t('Add notes for selected tasks (optional)') }}" 
                                                  rows="3"></textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-success w-100" onclick="submitBulkCompletion()">
                                            <i class="bi bi-check-all me-1"></i> {{ t('Mark as Done') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-calendar-check fs-1 text-success"></i>
            <h3 class="mt-3">{{ t('No tasks for today!') }}</h3>
            <p class="text-muted">{{ t('You have no tasks assigned for today. Great job!') }}</p>
        </div>
    @endif
</div>

<!-- Complete Modal -->
<div class="modal fade" id="completeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ t('Mark Task as Done') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="completeForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="taskNotes" class="form-label">{{ t('Notes') }}</label>
                        <textarea class="form-control" id="taskNotes" name="notes" rows="3" 
                                  placeholder="{{ t('Add any comments or notes...') }}"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('Cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ t('Mark as Done') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ t('Share Report') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <textarea class="form-control" id="shareText" rows="15" readonly></textarea>
                </div>
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    {{ t('Copy this text and share it on WhatsApp or any messaging app.') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ t('Close') }}</button>
                <button type="button" class="btn btn-success" onclick="copyToClipboard()">
                    <i class="bi bi-clipboard me-1"></i> {{ t('Copy') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentTaskId = null;
    let currentDate = null;

    // Select all
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.task-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkActions();
    });

    // Toggle bulk actions
    document.querySelectorAll('.task-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkActions);
    });

    function toggleBulkActions() {
        const checkedBoxes = document.querySelectorAll('.task-checkbox:checked');
        const bulkSection = document.querySelector('.bulk-actions-section');
        
        if (checkedBoxes.length > 0) {
            bulkSection.style.display = 'block';
        } else {
            bulkSection.style.display = 'none';
        }
    }

    // Complete task
    function completeTask(taskId, date) {
        currentTaskId = taskId;
        currentDate = date;
        const modal = new bootstrap.Modal(document.getElementById('completeModal'));
        modal.show();
    }

    // Complete form submission
    document.getElementById('completeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('task_id', currentTaskId);
        formData.append('date', currentDate);
        
        fetch('/teacher/tasks/complete-task', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error completing task');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error completing task');
        });
        
        bootstrap.Modal.getInstance(document.getElementById('completeModal')).hide();
    });

    // Uncomplete task
    function uncompleteTask(taskId, date) {
        if (confirm('{{ t('Mark this task as not completed?') }}')) {
            const formData = new FormData();
            formData.append('task_id', taskId);
            formData.append('date', date);
            
            fetch('/teacher/tasks/uncomplete-task', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error updating task');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating task');
            });
        }
    }

    // Bulk completion
    function submitBulkCompletion() {
        const form = document.getElementById('bulkCompletionForm');
        const formData = new FormData(form);
        
        // Add selected task IDs
        const checkedBoxes = document.querySelectorAll('.task-checkbox:checked');
        checkedBoxes.forEach(checkbox => {
            formData.append('task_ids[]', checkbox.value);
        });
        
        // Add current date
        formData.append('date', '{{ $date->format('Y-m-d') }}');
        
        fetch('/teacher/tasks/bulk-complete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error completing tasks');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error completing tasks');
        });
    }

    // Share report
    function shareReport() {
        fetch('{{ route('teacher.tasks.share-text') }}?date={{ $date->format('Y-m-d') }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('shareText').value = data.text;
            const modal = new bootstrap.Modal(document.getElementById('shareModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error generating report');
        });
    }

    // Copy to clipboard
    function copyToClipboard() {
        const textArea = document.getElementById('shareText');
        textArea.select();
        document.execCommand('copy');
        
        // Show success message
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check me-1"></i> {{ t('Copied!') }}';
        setTimeout(() => {
            btn.innerHTML = originalText;
        }, 2000);
    }

    // Bulk complete button
    function bulkComplete() {
        const checkedBoxes = document.querySelectorAll('.task-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('{{ t('Please select at least one task to complete.') }}');
            return;
        }
        
        document.querySelector('.bulk-actions-section').scrollIntoView({ behavior: 'smooth' });
    }
</script>
@endpush 