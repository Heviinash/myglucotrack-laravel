@extends('layouts.app')
@section('page-title', 'Create Admin & Clinic')
@section('content')
<div class="max-w-lg mx-auto">

    <div class="mb-6">
        <a href="{{ route('god.admins') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">← Back to Admins</a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Create Admin &amp; Clinic</h2>
        <p class="text-gray-500 text-sm mt-1">This will create a new clinic (tenant) and assign an Admin to manage it.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        {{-- Clinic section --}}
        <div class="mb-5 pb-5 border-b border-gray-100">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">🏥 Clinic Details</p>
            <form method="POST" action="{{ route('god.admins.store') }}" class="space-y-4" id="adminForm">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Clinic Name <span class="text-red-500">*</span></label>
                    <input type="text" name="tenant_name" value="{{ old('tenant_name') }}"
                           placeholder="e.g. Klinik Sejahtera Petaling Jaya"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
        </div>

        {{-- Admin section --}}
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">👑 Admin Account</p>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="fullname" value="{{ old('fullname') }}"
                       placeholder="e.g. Dr. Ahmad Razif"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                       placeholder="admin@clinic.com"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <p class="text-xs text-gray-400 mt-1">Min 8 characters. Share this with the admin to log in.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 text-sm text-blue-700 mt-5">
            ℹ️ Role is automatically set to <strong>Admin</strong>. Tenant is automatically assigned to the new clinic.
        </div>

        <div class="flex gap-3 pt-5">
            <button type="submit" form="adminForm"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                Create Admin &amp; Clinic
            </button>
            <a href="{{ route('god.admins') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium transition">
                Cancel
            </a>
        </div>

            </form>
    </div>
</div>
@endsection
