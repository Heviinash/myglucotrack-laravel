<?php

namespace App\Http\Controllers;

use App\Models\Patient;

use App\Models\AuditLog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::where('tenant_id', Auth::user()->tenant_id);

        // Search by patient name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('patient_name', 'like', "%{$search}%")
                  ->orWhere('ic_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by age range
        if ($request->filled('age_from')) {
            $query->where('age', '>=', $request->age_from);
        }

        if ($request->filled('age_to')) {
            $query->where('age', '<=', $request->age_to);
        }

        $patients = $query->latest()->get();

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'ic_number'    => 'required|digits:12|unique:patients,ic_number',
            'gender'       => 'required|in:Male,Female',
            'phone'        => 'required|string|max:20',
            'address'      => 'nullable|string|max:500',
        ]);

        // Auto-calculate DOB and age from IC
        $dob = Patient::dobFromIc($request->ic_number);
        $age = Patient::ageFromIc($request->ic_number);

        // Prevent duplicate IC in same tenant
        $exists = Patient::where('tenant_id', Auth::user()->tenant_id)
            ->where('ic_number', $request->ic_number)
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->with('error', 'A patient with this IC number already exists.');
        }

        $patient = Patient::create([
            'patient_name' => $request->patient_name,
            'ic_number'    => $request->ic_number,
            'dob'          => $dob,
            'age'          => $age,
            'gender'       => $request->gender,
            'phone'        => $request->phone,
            'address'      => $request->address,
            'tenant_id'    => Auth::user()->tenant_id,
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => Auth::user()->tenant_id,
            'action'     => 'created',
            'model_type' => 'Patient',
            'model_id'   => $patient->id,
            'description'=> 'Created patient: ' . $patient->patient_name,
            'ip_address' => request()->ip(),
        ]);
                

        return redirect()->route('patients.index')
            ->with('success', 'Patient created successfully.');
    }

    public function show(Patient $patient)
    {
        if ($patient->tenant_id !== Auth::user()->tenant_id) abort(403);

        $records = $patient->bloodSugarLevels()->latest()->get();

        return view('patients.show', compact('patient', 'records'));
    }

    public function edit(Patient $patient)
    {
        if ($patient->tenant_id !== Auth::user()->tenant_id) abort(403);

        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        if ($patient->tenant_id !== Auth::user()->tenant_id) abort(403);

        $request->validate([
            'patient_name' => 'required|string|max:255',
            'ic_number'    => 'required|digits:12|unique:patients,ic_number,' . $patient->id,
            'gender'       => 'required|in:Male,Female',
            'phone'        => 'required|string|max:20',
            'address'      => 'nullable|string|max:500',
        ]);

        $dob = Patient::dobFromIc($request->ic_number);
        $age = Patient::ageFromIc($request->ic_number);

        $patient->update([
            'patient_name' => $request->patient_name,
            'ic_number'    => $request->ic_number,
            'dob'          => $dob,
            'age'          => $age,
            'gender'       => $request->gender,
            'phone'        => $request->phone,
            'address'      => $request->address,
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => Auth::user()->tenant_id,
            'action'     => 'updated',
            'model_type' => 'Patient',
            'model_id'   => $patient->id,
            'description'=> 'Updated patient: ' . $patient->patient_name,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        if ($patient->tenant_id !== Auth::user()->tenant_id) abort(403);

        $patient->delete();

        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => Auth::user()->tenant_id,
            'action'     => 'deleted',
            'model_type' => 'Patient',
            'model_id'   => $patient->id,
            'description'=> 'Deleted patient: ' . $patient->patient_name,
            'ip_address' => request()->ip(),
        ]);


        return redirect()->route('patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}
