<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Patient;
use App\Models\BloodSugarLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GodController extends Controller
{
    // ── Dashboard ────────────────────────────────────────────────
    public function dashboard()
    {
        $totalTenants  = Tenant::count();
        $totalAdmins   = User::where('role', 'Admin')->count();
        $totalUsers    = User::where('role', 'User')->count();
        $totalPatients = Patient::withoutGlobalScopes()->count();
        $totalRecords  = BloodSugarLevel::withoutGlobalScopes()->count();

        $recentAdmins = User::where('role', 'Admin')
            ->with('tenant')->latest()->take(5)->get();

        return view('god.dashboard', compact(
            'totalTenants','totalAdmins','totalUsers',
            'totalPatients','totalRecords','recentAdmins'
        ));
    }

    // ── All Admins ───────────────────────────────────────────────
    public function admins()
    {
        $admins = User::where('role', 'Admin')
            ->with('tenant')
            ->latest()
            ->get()
            ->map(function ($admin) {
                $admin->patient_count = Patient::withoutGlobalScopes()
                    ->where('tenant_id', $admin->tenant_id)->count();
                $admin->record_count = BloodSugarLevel::withoutGlobalScopes()
                    ->where('tenant_id', $admin->tenant_id)->count();
                return $admin;
            });

        return view('god.admins', compact('admins'));
    }

    // ── Create Admin + Tenant ────────────────────────────────────
    public function createAdmin()
    {
        return view('god.create-admin');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'tenant_name' => 'required|string|max:255',
            'fullname'    => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
        ]);

        $tenant = Tenant::create(['tenant_name' => $request->tenant_name]);

        User::create([
            'fullname'  => $request->fullname,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'Admin',
            'status'    => 'Active',
            'tenant_id' => $tenant->id,
        ]);

        return redirect()->route('god.admins')
            ->with('success', "Admin {$request->fullname} and clinic {$request->tenant_name} created.");
    }

    // ── Edit Admin name/email ────────────────────────────────────
    public function editAdmin(User $user)
    {
        abort_if($user->role !== 'Admin', 403);
        return view('god.edit-admin', compact('user'));
    }

    public function updateAdmin(Request $request, User $user)
    {
        abort_if($user->role !== 'Admin', 403);

        $request->validate([
            'fullname'    => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'tenant_name' => 'required|string|max:255',
        ]);

        $user->update([
            'fullname' => $request->fullname,
            'email'    => $request->email,
        ]);

        $user->tenant()->update(['tenant_name' => $request->tenant_name]);

        return redirect()->route('god.admins')
            ->with('success', "Admin {$user->fullname} updated successfully.");
    }

    // ── Toggle Admin ─────────────────────────────────────────────
    public function toggleAdmin(User $user)
    {
        abort_if($user->role !== 'Admin', 403);
        $user->status = $user->status === 'Active' ? 'Disabled' : 'Active';
        $user->save();
        $action = $user->status === 'Active' ? 'enabled' : 'disabled';
        return back()->with('success', "Admin {$user->fullname} has been {$action}.");
    }

    // ── Delete Admin + Tenant ────────────────────────────────────
    public function destroyAdmin(User $user)
    {
        abort_if($user->role !== 'Admin', 403);
        $name = $user->fullname;
        $user->tenant()->delete();
        return redirect()->route('god.admins')
            ->with('success', "Admin {$name} and their clinic removed.");
    }

    // ── Reset Admin Password ─────────────────────────────────────
    public function resetPasswordForm(User $user)
    {
        abort_if($user->role !== 'Admin', 403);
        return view('god.reset-password', compact('user'));
    }

    public function resetPassword(Request $request, User $user)
    {
        abort_if($user->role !== 'Admin', 403);
        $request->validate(['password' => 'required|string|min:8|confirmed']);
        $user->update(['password' => Hash::make($request->password)]);
        return redirect()->route('god.admins')
            ->with('success', "Password for {$user->fullname} reset.");
    }

    // ── Audit Logs ───────────────────────────────────────────────
    public function auditLogs(Request $request)
    {
        $query = \App\Models\AuditLog::with(['user', 'tenant'])
            ->orderBy('created_at', 'desc');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by tenant
        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->paginate(50);
        $admins = User::where('role', 'Admin')->get();
        $tenants = \App\Models\Tenant::all();
        $actionTypes = \App\Models\AuditLog::getActionTypes();

        return view('god.audit-logs', compact('logs', 'admins', 'tenants', 'actionTypes'));
    }
}
