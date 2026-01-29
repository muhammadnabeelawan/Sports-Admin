@extends('layouts.admin')

@section('title', 'Create Brand')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('brands.index') }}" class="btn btn-light rounded-circle me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="fw-bold mb-0">New Brand</h2>
            </div>

            <div class="stat-card">
                <form action="{{ route('brands.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Brand Name</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Nike" required id="title">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" class="form-control" id="slug" placeholder="nike" readonly required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Logo URL (Optional)</label>
                        <input type="text" name="image" class="form-control" placeholder="https://logo.com/nike.png">
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg">Create Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('title').addEventListener('input', function() {
        const title = this.value;
        const slug = title.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('slug').value = slug;
    });
</script>
@endsection
