@extends('layouts.app')
@section('page-title', 'Manage Admins')
@section('content')
<div class="max-w-7xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">👑 Manage Admins</h2>
            <p class="text-gray-500 text-sm mt-1">All clinic admins registered in the system</p>
        </div>
        <a href="{{ route('god.admins.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            + New Admin &amp; Clinic
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs uppercase text-gray-500 tracking-wide">
                        <th class="px-5 py-3">#</th>
                        <th class="px-5 py-3">Name</th>
                        <th class="px-5 py-3">Clinic</th>
                        <th class="px-5 py-3">Email</th>
                        <th class="px-5 py-3">Patients</th>
                        <th class="px-5 py-3">Records</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Joined</th>
                        <th class="px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($admins as $admin)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">{{ $admin->fullname }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $admin->tenant->tenant_name ?? '—' }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $admin->email }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $admin->patient_count }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $admin->record_count }}</td>
                        <td class="px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $admin->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $admin->status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ $admin->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                {{-- Edit --}}
                                <a href="{{ route('god.admins.edit', $admin->id) }}"
                                   class="text-xs font-medium text-blue-600 hover:text-blue-800">Edit</a>
                                {{-- Toggle --}}
                                <form action="{{ route('god.admins.toggle', $admin->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-xs font-medium
                                        {{ $admin->status === 'Active' ? 'text-yellow-600 hover:text-yellow-800' : 'text-green-600 hover:text-green-800' }}">
                                        {{ $admin->status === 'Active' ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                {{-- Reset PW --}}
                                <a href="{{ route('god.admins.reset-password', $admin->id) }}"
                                   class="text-xs font-medium text-gray-500 hover:text-gray-700">Reset PW</a>
                                {{-- Delete --}}
                                <form action="{{ route('god.admins.destroy', $admin->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete {{ $admin->fullname }} and ALL their clinic data? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-10 text-center text-gray-400">
                            No admins yet. <a href="{{ route('god.admins.create') }}" class="text-blue-600 hover:underline">Create one →</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
