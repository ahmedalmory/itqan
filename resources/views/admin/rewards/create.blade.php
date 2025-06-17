@extends('layouts.app')

@section('title', t('Create Reward'))

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">{{ t('Create Reward') }}</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ t('Dashboard') }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.rewards.index') }}">{{ t('Rewards') }}</a></li>
        <li class="breadcrumb-item active">{{ t('Create') }}</li>
    </ol>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <i class="bi bi-gift me-1"></i>
            {{ t('Create Reward') }}
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.rewards.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ t('Reward Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="points_cost" class="form-label">{{ t('Points Cost') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('points_cost') is-invalid @enderror" 
                                   id="points_cost" name="points_cost" value="{{ old('points_cost') }}" 
                                   min="1" required>
                            @error('points_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ t('Description') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">{{ t('Stock Quantity') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" 
                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" 
                                   min="0" required>
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">{{ t('Reward Image') }}</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">{{ t('Supported formats: JPG, PNG, GIF. Max size: 2MB.') }}</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" 
                                       id="is_active" name="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    {{ t('Is Active') }}
                                </label>
                            </div>
                            <div class="form-text">{{ t('Only active rewards can be redeemed by students.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check me-1"></i>{{ t('Create Reward') }}
                    </button>
                    <a href="{{ route('admin.rewards.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>{{ t('Back') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // You can add image preview functionality here if needed
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection 