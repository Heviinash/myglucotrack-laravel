@extends('layouts.app')
@section('page-title', 'Edit Admin')
@section('content')
<div class="max-w-lg mx-auto">

    <div class="mb-6">
        <a href="{{ route('god.admins') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">← Back to Admins</a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Edit Admin</h2>
        <p class="text-gray-500 text-sm mt-1">Update name, email, or clinic name for <strong>{{ $user->fullname }}</strong></p>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ route('god.admins.update', $user->id) }}" class="space-y-5">
            @csrf @method('PATCH')

            <div class="pb-4 border-b border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">🏥 Clinic</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Clinic Name <span class="text-red-500">*</span></label>
                    <input type="text" name="tenant_name"
                           value="{{ old('tenant_name', $user->tenant->tenant_name ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">👑 Admin Account</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="fullname"
                               value="{{ old('fullname', $user->fullname) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email"
                               value="{{ old('email', $user->email) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                    Save Changes
                </button>
                <a href="{{ route('god.admins') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
