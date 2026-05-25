@extends('layouts.app')

@section('page-title', 'Edit Blood Sugar Record')

@section('content')

<div class="max-w-2xl mx-auto px-4 sm:px-6">

    <div class="mb-6">
        <a href="{{ route('blood-sugar.index') }}" class="text-sm text-gray-500 hover:text-blue-600 transition">
            ← Back to Records
        </a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Edit Blood Sugar Record</h2>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg">
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">

        <form method="POST" action="{{ route('blood-sugar.update', $record->id) }}">
            @csrf
            @method('PUT')

            {{-- Patient --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                <select name="patient_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="">— Select Patient —</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id', $record->patient_id) == $patient->id ? 'selected' : '' }}>
                            {{ $patient->patient_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Blood Sugar Level --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Blood Sugar Level (mmol/L) <span class="text-red-500">*</span></label>
                <input type="number" step="0.1" name="blood_sugar_level"
                       value="{{ old('blood_sugar_level', $record->blood_sugar_level) }}"
                       placeholder="e.g. 5.4"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <p class="text-xs text-gray-400 mt-1">Normal: &lt;5.6 | Pre-diabetic: 5.6–6.9 | High: ≥7.0</p>
            </div>

            {{-- Before / After --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Measurement Type <span class="text-red-500">*</span></label>
                <select name="before_after" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="Before Meal" {{ old('before_after', $record->before_after) == 'Before Meal' ? 'selected' : '' }}>Before Meal</option>
                    <option value="After Meal" {{ old('before_after', $record->before_after) == 'After Meal' ? 'selected' : '' }}>After Meal</option>
                </select>
            </div>

            {{-- Date & Time --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="measurement_date"
                           value="{{ old('measurement_date', $record->measurement_date) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Time <span class="text-red-500">*</span></label>
                    <input type="time" name="measurement_time"
                           value="{{ old('measurement_time', $record->measurement_time) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea name="notes" rows="3" placeholder="Any relevant observations..."
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $record->notes) }}</textarea>
            </div>

            {{-- Record Info --}}
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm text-gray-600">
                <p><strong>Recorded by:</strong> {{ $record->measurement_by }}</p>
                <p><strong>Created:</strong> {{ $record->created_at->format('M d, Y H:i') }}</p>
            </div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                    Update Record
                </button>
                <a href="{{ route('blood-sugar.index') }}"
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium transition">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

@endsection
