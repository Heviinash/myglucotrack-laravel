<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    private function tenantId(): int
    {
        return Auth::user()->tenant_id;
    }

    private function guardUser(User $user): void
    {
        abort_if(
            $user->tenant_id !== $this->tenantId() || $user->role !== 'User',
            403
        );
    }

    // ── List users ───────────────────────────────────────────────
    public function index()
    {
        $users         = User::where('tenant_id', $this->tenantId())->where('role', 'User')->latest()->get();
        $totalUsers    = $users->count();
        $activeUsers   = $users->where('status', 'Active')->count();
        $disabledUsers = $users->where('status', 'Disabled')->count();

        return view('admin.users', compact('users', 'totalUsers', 'activeUsers', 'disabledUsers'));
    }

    // ── Create user ──────────────────────────────────────────────
    public function create()
    {
        return view('admin.create-user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'fullname'  => $request->fullname,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'User',
            'status'    => 'Active',
            'tenant_id' => $this->tenantId(),
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => $this->tenantId(),
            'action'     => 'created',
            'model_type' => 'User',
            'model_id'   => $user->id,
            'description'=> 'Created user: ' . $user->fullname,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.users')
            ->with('success', "{$request->fullname} added successfully.");
    }

    // ── Edit user name/email ─────────────────────────────────────
    public function edit(User $user)
    {
        $this->guardUser($user);
        return view('admin.edit-user', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->guardUser($user);

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'fullname' => $request->fullname,
            'email'    => $request->email,
        ]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => $this->tenantId(),
            'action'     => 'updated',
            'model_type' => 'User',
            'model_id'   => $user->id,
            'description'=> 'Updated user: ' . $user->fullname,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.users')
            ->with('success', "User {$user->fullname} updated successfully.");
    }

    // ── Toggle status ────────────────────────────────────────────
    public function toggle(User $user)
    {   

        $this->guardUser($user);
        $oldStatus = $user->status;
        $user->status = $user->status === 'Active' ? 'Disabled' : 'Active';
        $user->save();
        $action = $user->status === 'Active' ? 'enabled' : 'disabled';

        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => $this->tenantId(),
            'action'     => 'status_changed',
            'model_type' => 'User',
            'model_id'   => $user->id,
            'description'=> 'Changed user status from ' . $oldStatus . ' to ' . $user->status,
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', "User {$user->fullname} has been {$action}.");
    }

    // ── Delete user ──────────────────────────────────────────────
    public function destroy(User $user)
    {
        $this->guardUser($user);
        $name = $user->fullname;
        $user->delete();
        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => $this->tenantId(),
            'action'     => 'deleted',
            'model_type' => 'User',
            'model_id'   => $user->id,
            'description'=> 'Deleted user: ' . $name,
            'ip_address' => request()->ip(),
        ]);
        return redirect()->route('admin.users')->with('success', "User {$name} removed.");
    }

    // ── Reset password ───────────────────────────────────────────
    public function resetPasswordForm(User $user)
    {
        $this->guardUser($user);
        return view('admin.reset-password', compact('user'));
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->guardUser($user);
        $request->validate(['password' => 'required|string|min:8|confirmed']);
        $user->update(['password' => Hash::make($request->password)]);

        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => $this->tenantId(),
            'action'     => 'password_changed',
            'model_type' => 'User',
            'model_id'   => $user->id,
            'description'=> 'Reset password for user: ' . $user->fullname,
            'ip_address' => request()->ip(),
        ]);


        return redirect()->route('admin.users')
            ->with('success', "Password for {$user->fullname} reset successfully.");
    }
}
