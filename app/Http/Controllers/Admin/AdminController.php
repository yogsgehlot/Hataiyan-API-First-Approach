<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::latest()->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'role'  => 'required',
        ]);

        $admin->update($request->only('name', 'email', 'role'));

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully!');
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);

        // Prevent deleting yourself
        if (session('admin')['id'] == $admin->id) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }

        $admin->delete();

        return back()->with('success', 'Admin removed successfully.');
    }
}
