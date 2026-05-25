@extends('layouts.app')

@section('page-title', 'Audit Logs')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Audit Logs</h2>
        <p class="text-gray-500 text-sm mt-1">Monitor all system activities and changes</p>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Total Logs</p>
            <p class="text-2xl font-bold text-blue-600">{{ $logs->total() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Admins</p>
            <p class="text-2xl font-bold text-green-600">{{ $admins->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Tenants</p>
            <p class="text-2xl font-bold text-purple-600">{{ $tenants->count() }}</p>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3 sm:p-4 mb-6">
        <form method="GET" action="{{ route('god.audit-logs') }}" class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 items-start sm:items-end">
            
            <div class="w-full sm:w-auto">
                <label class="block text-xs text-gray-500 mb-1">Admin User</label>
                <select name="user_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Users</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ request('user_id') == $admin->id ? 'selected' : '' }}>
                            {{ $admin->fullname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-xs text-gray-500 mb-1">Tenant</label>
                <select name="tenant_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Tenants</option>
                    @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ request('tenant_id') == $tenant->id ? 'selected' : '' }}>
                            {{ $tenant->tenant_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-xs text-gray-500 mb-1">Action</label>
                <select name="action" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Actions</option>
                    @foreach($actionTypes as $key => $label)
                        <option value="{{ $key }}" {{ request('action') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-xs text-gray-500 mb-1">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-xs text-gray-500 mb-1">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit"
                        class="flex-1 sm:flex-none bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    Filter
                </button>
                @if(request()->hasAny(['user_id', 'tenant_id', 'action', 'from_date', 'to_date']))
                    <a href="{{ route('god.audit-logs') }}"
                       class="flex-1 sm:flex-none text-center text-sm text-gray-500 hover:text-gray-700 px-3 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- LOGS TABLE --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs uppercase text-gray-500 tracking-wide">
                        <th class="px-4 sm:px-5 py-3">Date & Time</th>
                        <th class="px-4 sm:px-5 py-3">Admin</th>
                        <th class="hidden sm:table-cell px-4 sm:px-5 py-3">Tenant</th>
                        <th class="px-4 sm:px-5 py-3">Action</th>
                        <th class="hidden md:table-cell px-4 sm:px-5 py-3">Model</th>
                        <th class="hidden lg:table-cell px-4 sm:px-5 py-3">Description</th>
                        <th class="hidden lg:table-cell px-4 sm:px-5 py-3">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 sm:px-5 py-3 font-medium text-gray-700 text-xs sm:text-sm">
                                <div>{{ $log->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-4 sm:px-5 py-3 text-gray-700 font-medium text-xs sm:text-sm">
                                {{ $log->user->fullname ?? '—' }}
                            </td>
                            <td class="hidden sm:table-cell px-4 sm:px-5 py-3 text-gray-600 text-xs sm:text-sm">
                                {{ $log->tenant->tenant_name ?? '—' }}
                            </td>
                            <td class="px-4 sm:px-5 py-3">
                                @php
                                    $actionColors = [
                                        'created' => 'green',
                                        'updated' => 'blue',
                                        'deleted' => 'red',
                                        'viewed' => 'gray',
                                        'status_changed' => 'yellow',
                                        'password_changed' => 'orange',

                                        // ✅ ADD THESE
                                        'login' => 'green',
                                        'logout' => 'gray',
                                    ];

                                    $color = $actionColors[$log->action] ?? 'gray';

                                    $classes = match($color) {
                                        'green' => 'bg-green-100 text-green-700',
                                        'blue' => 'bg-blue-100 text-blue-700',
                                        'red' => 'bg-red-100 text-red-700',
                                        'yellow' => 'bg-yellow-100 text-yellow-700',
                                        'orange' => 'bg-orange-100 text-orange-700',
                                        'gray' => 'bg-gray-100 text-gray-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp

                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $classes }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>
                            <td class="hidden md:table-cell px-4 sm:px-5 py-3 text-gray-600 text-xs">
                                {{ $log->model_type }} <span class="text-gray-400">#{{ $log->model_id }}</span>
                            </td>
                            <td class="hidden lg:table-cell px-4 sm:px-5 py-3 text-gray-600 text-xs">
                                <span title="{{ $log->description }}">
                                    {{ Str::limit($log->description, 50) }}
                                </span>
                            </td>
                            <td class="hidden lg:table-cell px-4 sm:px-5 py-3 text-gray-500 text-xs font-mono">
                                {{ $log->ip_address ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 sm:px-5 py-10 text-center text-gray-400">
                                No audit logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-6">
        {{ $logs->links() }}
    </div>

</div>

@endsection
