<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // attempt to login using admin guard (MVC approach)
        if (!Auth::guard('admin')->attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password'])
                ->withInput();
        }

        // auth successful
        $admin = Auth::guard('admin')->user();

        // store admin in session manually (optional)
        session(['admin' => $admin]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function showCreateForm()
    {
        return view('admin.admins.create');
    }

    public function createAdminWeb(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6',
            'role' => 'nullable|string',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role ?? 'super',
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Admin account created successfully!');
    }


}
