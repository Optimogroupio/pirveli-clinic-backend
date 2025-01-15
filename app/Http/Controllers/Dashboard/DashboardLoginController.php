<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardLoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('dashboard')->check()) {
            return redirect()->route('dashboard.home');
        }
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('login', 'password');
        if (Auth::guard('dashboard')->attempt($credentials, $request->boolean('remember'))) {
            return redirect()->route('dashboard.home');
        }

        return back()->withErrors(['login' => 'Invalid login credentials']);
    }

    /**
     * Log the user out of the dashboard.
     */
    public function logout()
    {
        Auth::guard('dashboard_user')->logout();
        return redirect()->route('backend.login');
    }
}
