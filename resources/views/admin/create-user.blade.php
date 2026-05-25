@extends('layouts.app')
@section('page-title', 'Add User')
@section('content')
<div class="max-w-lg mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.users') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">← Back to Users</a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Add New User</h2>
        <p class="text-gray-500 text-sm mt-1">This user will be added under <strong>{{ auth()->user()->tenant->tenant_name }}</strong>.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="fullname" value="{{ old('fullname') }}"
                       placeholder="e.g. Nurse Farah"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="staff@clinic.com"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <p class="text-xs text-gray-400 mt-1">Min 8 characters. Share this with the user to log in.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-sm text-blue-700">
                ℹ️ Role is automatically <strong>User</strong>. Clinic is automatically set to <strong>{{ auth()->user()->tenant->tenant_name }}</strong>.
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                    Create User
                </button>
                <a href="{{ route('admin.users') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
