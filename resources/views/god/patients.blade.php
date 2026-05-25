@extends('layouts.app')
@section('page-title', 'All Patients')
@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">👤 All Patients (System-wide)</h2>
        <p class="text-gray-500 text-sm mt-1">Every patient across all clinics</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-left text-xs uppercase text-gray-500 tracking-wide">
                    <th class="px-5 py-3">#</th>
                    <th class="px-5 py-3">Patient Name</th>
                    <th class="px-5 py-3">Age</th>
                    <th class="px-5 py-3">Clinic</th>
                    <th class="px-5 py-3">Added</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($patients as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $p->patient_name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $p->age }} yrs</td>
                    <td class="px-5 py-3 text-gray-600">{{ $p->tenant->tenant_name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $p->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">No patients found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
