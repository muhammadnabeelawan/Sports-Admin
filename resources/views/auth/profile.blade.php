@extends('layouts.admin')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <h2 class="fw-bold mb-4">Account Settings</h2>
            
            @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                {{ session('success') }}
            </div>
            @endif

            <div class="stat-card">
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    <div class="mb-4 text-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=eff6ff&color=3b82f6&size=128" class="rounded-circle mb-3 shadow-sm" width="100">
                        <h5 class="fw-bold">{{ $user->name }}</h5>
                        <p class="text-muted small">Super Admin</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <hr class="my-4 opacity-10">
                    <h6 class="fw-bold mb-3">Change Password</h6>
                    <p class="text-muted small mb-4">Leave blank if you don't want to change it.</p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
