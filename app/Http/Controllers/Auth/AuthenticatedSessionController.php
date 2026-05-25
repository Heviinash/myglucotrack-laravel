<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        AuditLog::create([
            'user_id'    => Auth::id(),
            'tenant_id'  => Auth::user()->tenant_id,
            'action'     => 'login',
            'model_type' => 'User',
            'model_id'   => Auth::id(),
            'description'=> 'User logged in: ' . Auth::user()->fullname,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user(); 

        if ($user) {
            AuditLog::create([
                'user_id'    => $user->id,
                'tenant_id'  => $user->tenant_id,
                'action'     => 'logout',
                'model_type' => 'User',
                'model_id'   => $user->id,
                'description'=> 'User logged out: ' . $user->fullname,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
