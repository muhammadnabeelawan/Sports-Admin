@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('categories.index') }}" class="btn btn-light rounded-circle me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="fw-bold mb-0">Edit Category</h2>
            </div>

            <div class="stat-card">
                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Category Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $category->title }}" required id="title">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Slug</label>
                        <input type="text" name="slug" class="form-control" id="slug" value="{{ $category->slug }}" readonly required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Image URL (Optional)</label>
                        <input type="text" name="image" class="form-control" value="{{ $category->image }}" placeholder="https://example.com/image.jpg">
                        @if($category->image)
                        <div class="mt-2">
                             <img src="{{ $category->image }}" class="rounded-3" width="80" height="80" style="object-fit: cover;">
                        </div>
                        @endif
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg">Update Category</button>
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
