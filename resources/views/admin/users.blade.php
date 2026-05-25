@extends('layouts.app')
@section('page-title', 'Manage Users')
@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">👥 Manage Users</h2>
            <p class="text-gray-500 text-sm mt-1">Staff accounts under your clinic</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            + Add User
        </a>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500">Total Users</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500">Active</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $activeUsers }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-500">Disabled</p>
            <p class="text-3xl font-bold text-red-600 mt-1">{{ $disabledUsers }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-left text-xs uppercase text-gray-500 tracking-wide">
                    <th class="px-5 py-3">#</th>
                    <th class="px-5 py-3">Name</th>
                    <th class="px-5 py-3">Email</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Joined</th>
                    <th class="px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $user->fullname }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $user->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            {{-- Edit --}}
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="text-xs font-medium text-blue-600 hover:text-blue-800">Edit</a>
                            {{-- Toggle --}}
                            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="inline">
                                @csrf
                                <button class="text-xs font-medium
                                    {{ $user->status === 'Active' ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }}">
                                    {{ $user->status === 'Active' ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                            {{-- Reset PW --}}
                            <a href="{{ route('admin.users.reset-password', $user->id) }}"
                               class="text-xs font-medium text-gray-500 hover:text-gray-700">Reset PW</a>
                            {{-- Remove --}}
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Remove {{ $user->fullname }}?')">
                                @csrf @method('DELETE')
                                <button class="text-xs font-medium text-red-500 hover:text-red-700">Remove</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                        No users yet. <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:underline">Add first user →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
