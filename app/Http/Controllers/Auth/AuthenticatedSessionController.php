<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        $user = Auth::user();

        switch ($user->role) {
            case 'admin':
                $redirect = route('admin.dashboard', absolute: false);
                break;
            case 'maskapai':
                $redirect = route('maskapai.dashboard', absolute: false);
                break;
            case 'user':
                $redirect = route('user.dashboard', absolute: false);
                break;
            default:
                abort(403, 'Unathorized');
                break;
        }

        flash()->use('theme.neon')->success('Login berhasil');

        return redirect()->intended($redirect);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
