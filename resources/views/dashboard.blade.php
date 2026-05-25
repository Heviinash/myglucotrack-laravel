@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- WELCOME HEADER --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Welcome back, {{ auth()->user()->fullname }}! 👋</h2>
        <p class="text-gray-500 text-sm mt-1">Here's your overview for today.</p>
    </div>

    {{-- STATS CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-500">Total Patients</h3>
                <span class="text-2xl">👤</span>
            </div>
            <p class="text-3xl font-bold text-blue-600">{{ $totalPatients }}</p>
            <p class="text-xs text-gray-400 mt-1">Registered under you</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-500">Total Records</h3>
                <span class="text-2xl">📋</span>
            </div>
            <p class="text-3xl font-bold text-green-600">{{ $totalRecords }}</p>
            <p class="text-xs text-gray-400 mt-1">Blood sugar readings</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-500">High Readings</h3>
                <span class="text-2xl">🔴</span>
            </div>
            <p class="text-3xl font-bold text-red-600">{{ $highReadings }}</p>
            <p class="text-xs text-gray-400 mt-1">Levels ≥ 7.0 mmol/L</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-500">Today's Readings</h3>
                <span class="text-2xl">📅</span>
            </div>
            <p class="text-3xl font-bold text-yellow-600">{{ $todayReadings }}</p>
            <p class="text-xs text-gray-400 mt-1">Recorded today</p>
        </div>

    </div>

    {{-- RECENT RECORDS TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-semibold text-gray-800">Recent Blood Sugar Records</h3>
            <a href="{{ route('blood-sugar.index') }}"
               class="text-sm text-blue-600 hover:underline font-medium">
                View All →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 text-left text-xs uppercase text-gray-500 tracking-wide">
                        <th class="pb-3 pr-4">Patient</th>
                        <th class="pb-3 pr-4">Level (mmol/L)</th>
                        <th class="pb-3 pr-4">Status</th>
                        <th class="pb-3 pr-4">Type</th>
                        <th class="pb-3">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentRecords as $record)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 pr-4 font-medium text-gray-700">
                                {{ $record->patient->patient_name ?? '—' }}
                            </td>
                            <td class="py-3 pr-4 font-semibold">
                                {{ $record->blood_sugar_level }}
                            </td>
                            <td class="py-3 pr-4">
                                @if($record->blood_sugar_level < 5.6)
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        🟢 Normal
                                    </span>
                                @elseif($record->blood_sugar_level < 7)
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                        🟡 Pre-Diabetic
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        🔴 High
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 pr-4 text-gray-500">
                                {{ $record->before_after }}
                            </td>
                            <td class="py-3 text-gray-500">
                                {{ \Carbon\Carbon::parse($record->measurement_date)->format('d M Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">
                                No records yet. <a href="{{ route('blood-sugar.create') }}" class="text-blue-600 hover:underline">Add the first one →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection
