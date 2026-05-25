@extends('layouts.app')
@section('page-title', 'Patients')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Patients</h2>
            <p class="text-gray-500 text-sm mt-1">Manage your clinic's patients</p>
        </div>
        @if(auth()->user()->role !== 'User')
            <a href="{{ route('patients.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                + Add Patient
            </a>
        @endif
    </div>

    {{-- SEARCH & FILTER --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-3 sm:p-4 mb-6">
        <form method="GET" action="{{ route('patients.index') }}" class="flex flex-col sm:flex-row flex-wrap gap-2 sm:gap-3 items-start sm:items-end">
            
            <div class="w-full sm:flex-1">
                <label class="block text-xs text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, IC number, or phone..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-xs text-gray-500 mb-1">Gender</label>
                <select name="gender" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">All</option>
                    <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-xs text-gray-500 mb-1">Age From</label>
                <input type="number" name="age_from" value="{{ request('age_from') }}" placeholder="Min"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-xs text-gray-500 mb-1">Age To</label>
                <input type="number" name="age_to" value="{{ request('age_to') }}" placeholder="Max"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>

            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit"
                        class="flex-1 sm:flex-none bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                    Search
                </button>
                @if(request()->hasAny(['search', 'gender', 'age_from', 'age_to']))
                    <a href="{{ route('patients.index') }}"
                       class="flex-1 sm:flex-none text-center text-sm text-gray-500 hover:text-gray-700 px-3 py-2 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        Clear
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-left text-xs uppercase text-gray-500 tracking-wide">
                        <th class="px-4 sm:px-5 py-3">#</th>
                        <th class="px-4 sm:px-5 py-3">Name</th>
                        <th class="hidden sm:table-cell px-4 sm:px-5 py-3">IC Number</th>
                        <th class="hidden md:table-cell px-4 sm:px-5 py-3">DOB</th>
                        <th class="px-4 sm:px-5 py-3">Age</th>
                        <th class="hidden sm:table-cell px-4 sm:px-5 py-3">Gender</th>
                        <th class="hidden lg:table-cell px-4 sm:px-5 py-3">Phone</th>
                        <th class="px-4 sm:px-5 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 sm:px-5 py-3 text-gray-400 text-xs sm:text-sm">{{ $loop->iteration }}</td>
                        <td class="px-4 sm:px-5 py-3 font-medium text-gray-800">
                            <a href="{{ route('patients.show', $patient->id) }}" class="hover:text-blue-600 transition text-sm sm:text-base">
                                {{ $patient->patient_name }}
                            </a>
                        </td>
                        <td class="hidden sm:table-cell px-4 sm:px-5 py-3 text-gray-600 font-mono tracking-wide text-xs">
                            {{ $patient->ic_number ? substr($patient->ic_number,0,6).'-'.substr($patient->ic_number,6,2).'-'.substr($patient->ic_number,8,4) : '—' }}
                        </td>
                        <td class="hidden md:table-cell px-4 sm:px-5 py-3 text-gray-600 text-xs sm:text-sm">
                            {{ $patient->dob ? $patient->dob->format('d M Y') : '—' }}
                        </td>
                        <td class="px-4 sm:px-5 py-3 text-gray-600 text-xs sm:text-sm">{{ $patient->age }} yrs</td>
                        <td class="hidden sm:table-cell px-4 sm:px-5 py-3">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $patient->gender === 'Male' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                {{ $patient->gender ?? '—' }}
                            </span>
                        </td>
                        <td class="hidden lg:table-cell px-4 sm:px-5 py-3 text-gray-600 text-xs">{{ $patient->phone ?? '—' }}</td>
                        <td class="px-4 sm:px-5 py-3">
                            <div class="flex items-center gap-2 flex-wrap">
                                <a href="{{ route('patients.show', $patient->id) }}"
                                   class="text-xs font-medium text-gray-500 hover:text-gray-700">View</a>
                                @if(auth()->user()->role !== 'User')
                                    <a href="{{ route('patients.edit', $patient->id) }}"
                                       class="text-xs font-medium text-blue-600 hover:text-blue-800">Edit</a>
                                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Delete {{ $patient->patient_name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="text-xs font-medium text-red-500 hover:text-red-700">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 sm:px-5 py-10 text-center text-gray-400 text-sm">
                            No patients found.
                            @if(auth()->user()->role !== 'User')
                                <a href="{{ route('patients.create') }}" class="text-blue-600 hover:underline ml-1">Add first patient →</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
