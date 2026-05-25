@extends('layouts.app')
@section('page-title', 'All Blood Sugar Records')
@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">🩸 All Records (System-wide)</h2>
        <p class="text-gray-500 text-sm mt-1">Every blood sugar reading across all clinics</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-left text-xs uppercase text-gray-500 tracking-wide">
                    <th class="px-5 py-3">#</th>
                    <th class="px-5 py-3">Patient</th>
                    <th class="px-5 py-3">Clinic</th>
                    <th class="px-5 py-3">Level (mmol/L)</th>
                    <th class="px-5 py-3">Status</th>
                    <th class="px-5 py-3">Type</th>
                    <th class="px-5 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($records as $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $r->patient->patient_name ?? '—' }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $r->tenant->tenant_name ?? '—' }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-900">{{ $r->blood_sugar_level }}</td>
                    <td class="px-5 py-3">
                        @if($r->blood_sugar_level < 5.6)
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">🟢 Normal</span>
                        @elseif($r->blood_sugar_level < 7)
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">🟡 Pre-Diabetic</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">🔴 High</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $r->before_after }}</td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ \Carbon\Carbon::parse($r->measurement_date)->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
