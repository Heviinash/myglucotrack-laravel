@extends('layouts.app')
@section('page-title', 'Patient Detail')
@section('content')

<div class="max-w-5xl mx-auto">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('patients.index') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">← Back to Patients</a>
            <h2 class="text-2xl font-bold text-gray-800 mt-2">{{ $patient->patient_name }}</h2>
        </div>
        @if(auth()->user()->role !== 'User')
            <a href="{{ route('patients.edit', $patient->id) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                Edit Patient
            </a>
        @endif
    </div>

    {{-- Patient Info Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-6">
        <h3 class="text-base font-semibold text-gray-700 mb-4">Patient Information</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
            <div>
                <p class="text-xs text-gray-400 mb-1">Full Name</p>
                <p class="text-sm font-medium text-gray-800">{{ $patient->patient_name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">IC Number</p>
                <p class="text-sm font-mono text-gray-800">
                    {{ $patient->ic_number
                        ? substr($patient->ic_number,0,6).'-'.substr($patient->ic_number,6,2).'-'.substr($patient->ic_number,8,4)
                        : '—' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Date of Birth</p>
                <p class="text-sm text-gray-800">{{ $patient->dob ? $patient->dob->format('d M Y') : '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Age</p>
                <p class="text-sm text-gray-800">{{ $patient->age }} years old</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Gender</p>
                <span class="inline-block px-2 py-1 rounded-full text-xs font-medium
                    {{ $patient->gender === 'Male' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                    {{ $patient->gender ?? '—' }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Phone</p>
                <p class="text-sm text-gray-800">{{ $patient->phone ?? '—' }}</p>
            </div>
            @if($patient->address)
            <div class="col-span-2 md:col-span-3">
                <p class="text-xs text-gray-400 mb-1">Address</p>
                <p class="text-sm text-gray-800">{{ $patient->address }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Blood Sugar History --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-base font-semibold text-gray-700">Blood Sugar History</h3>
            <a href="{{ route('blood-sugar.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition">
                + Add Reading
            </a>
        </div>

        @if($records->isEmpty())
            <p class="text-sm text-gray-400 text-center py-6">No readings recorded yet for this patient.</p>
        @else
            {{-- Summary --}}
            <div class="grid grid-cols-3 gap-4 mb-5">
                <div class="bg-gray-50 rounded-lg p-3 text-center">
                    <p class="text-xs text-gray-500">Total Readings</p>
                    <p class="text-xl font-bold text-gray-700 mt-1">{{ $records->count() }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 text-center">
                    <p class="text-xs text-gray-500">Average</p>
                    <p class="text-xl font-bold text-blue-600 mt-1">{{ number_format($records->avg('blood_sugar_level'), 1) }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 text-center">
                    <p class="text-xs text-gray-500">High Readings</p>
                    <p class="text-xl font-bold text-red-600 mt-1">{{ $records->where('blood_sugar_level', '>=', 7)->count() }}</p>
                </div>
            </div>

            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs uppercase text-gray-500 tracking-wide">
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Time</th>
                        <th class="px-4 py-3">Level (mmol/L)</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Type</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($records as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($r->measurement_date)->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $r->measurement_time }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-900">{{ $r->blood_sugar_level }}</td>
                        <td class="px-4 py-3">
                            @if($r->blood_sugar_level < 5.6)
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">🟢 Normal</span>
                            @elseif($r->blood_sugar_level < 7)
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">🟡 Pre-Diabetic</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">🔴 High</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $r->before_after }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

@endsection
