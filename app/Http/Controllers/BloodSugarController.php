<?php

namespace App\Http\Controllers;

use App\Models\BloodSugarLevel;
use App\Models\Patient;

use App\Models\AuditLog;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BloodSugarController extends Controller
{   
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BloodSugarLevel::with('patient')
            ->where('tenant_id', Auth::user()->tenant_id);

        // Filter by patient
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        // Filter by date (from)
        if ($request->filled('from_date')) {
            $query->whereDate('measurement_date', '>=', $request->from_date);
        }

        // Filter by date (to)
        if ($request->filled('to_date')) {
            $query->whereDate('measurement_date', '<=', $request->to_date);
        }

        $records = $query->latest()->get();

        // KPIs based on filtered data
        $totalRecords = $records->count();
        $averageSugar = $records->avg('blood_sugar_level');
        $highCount = $records->where('blood_sugar_level', '>=', 7)->count();
        $todayCount = $records->where('measurement_date', now()->toDateString())->count();

        $patients = Patient::where('tenant_id', Auth::user()->tenant_id)->get();

        return view('blood.index', compact(
            'records',
            'totalRecords',
            'averageSugar',
            'highCount',
            'todayCount',
            'patients'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::where('tenant_id', Auth::user()->tenant_id)
            ->get();

        return view('blood.create', compact('patients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  
        $patient = Patient::find($request->patient_id);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'blood_sugar_level' => 'required|numeric',
            'before_after' => 'required',
            'measurement_date' => 'required|date',
            'measurement_time' => 'required',
        ]);

        $record=BloodSugarLevel::create([
            'patient_id' => $request->patient_id,
            'blood_sugar_level' => $request->blood_sugar_level,
            'before_after' => $request->before_after,
            'measurement_date' => $request->measurement_date,
            'measurement_time' => $request->measurement_time,
            'measurement_by' => Auth::user()->fullname,
            'notes' => $request->notes,
            'tenant_id' => Auth::user()->tenant_id,
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => Auth::user()->tenant_id,
            'action'     => 'created',
            'model_type' => 'BloodSugarLevel',
            'model_id'   => $record->id,
            'description'=> 'Created blood sugar record for patient: ' . $patient->patient_name,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('blood-sugar.index')
            ->with('success', 'Blood sugar record added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = BloodSugarLevel::with('patient')
            ->where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        return view('blood.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $record = BloodSugarLevel::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        $patients = Patient::where('tenant_id', Auth::user()->tenant_id)->get();

        return view('blood.edit', compact('record', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   
        $patient = Patient::find($request->patient_id);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'blood_sugar_level' => 'required|numeric',
            'before_after' => 'required',
            'measurement_date' => 'required|date',
            'measurement_time' => 'required',
        ]);

        $record = BloodSugarLevel::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        $record->update([
            'patient_id' => $request->patient_id,
            'blood_sugar_level' => $request->blood_sugar_level,
            'before_after' => $request->before_after,
            'measurement_date' => $request->measurement_date,
            'measurement_time' => $request->measurement_time,
            'notes' => $request->notes,
        ]);


        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => Auth::user()->tenant_id,
            'action'     => 'updated',
            'model_type' => 'BloodSugarLevel',
            'model_id'   => $record->id,
            'description'=> 'Updated blood sugar record patient: ' . $patient->patient_name,
             'ip_address' => request()->ip(),
        ]);

        return redirect()->route('blood-sugar.index')
            ->with('success', 'Record updated successfully');
    }

    public function destroy(string $id)
    {

        $record = BloodSugarLevel::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        $recordId = $record->id;
        
        $patient = Patient::find($record->patient_id);

        $record->delete();

        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => Auth::user()->tenant_id,
            'action'     => 'deleted',
            'model_type' => 'BloodSugarLevel',
            'model_id'   => $recordId,
            'description'=> 'Deleted blood sugar record for patient: ' . $patient->patient_name,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('blood-sugar.index')
            ->with('success', 'Record deleted successfully');
    }
}
