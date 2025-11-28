<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->q}%")
                    ->orWhere('email', 'like', "%{$request->q}%");
            });
        }

        // Role filter (if your users table has role field)
        if ($request->role) {
            $query->where('role', $request->role);
        }

        // Status filter
        // Example: active, banned etc. (if such column exists)
        if ($request->status) {
            $query->where('account_status', $request->status);
        }

        // Pagination
        $perPage = $request->per_page ?? 10;
        $users = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'filters' => $request->only(['q', 'role', 'status', 'per_page'])
        ]);
    }
    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        return view('admin.users.show', [
            'user' => $user
        ]);
    }
    public function trashed(Request $request)
    {
        $perPage = $request->per_page ?? 10;

        $users = User::onlyTrashed()
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.users.trashed', [
            'users' => $users
        ]);
    }

    public function destroy($id, NotificationService $notifications)
    {
        $admin = auth('admin')->user(); // admin user

        $user = User::withTrashed()->findOrFail($id);

        // ----------------------------------------------------
        // 🔥 PERMANENT DELETE (force delete)
        // ----------------------------------------------------
        if ($user->trashed()) {

            // Notify user BEFORE deletion
            $notifications->create(
                $user,
                'admin_deleted',
                [
                    'target_type' => 'user',
                    'target_id' => $user->id,
                    'admin_id' => $admin->id,
                ],
                $admin
            );

            $user->forceDelete();

            return redirect()->back()->with('success', 'User permanently deleted.');
        }

        // ----------------------------------------------------
        // 🔥 SOFT DELETE (move to trash)
        // ----------------------------------------------------
        $user->delete();

        $notifications->create(
            $user,
            'admin_trashed',
            [
                'target_type' => 'user',
                'target_id' => $user->id,
                'admin_id' => $admin->id,
            ],
            $admin
        );

        return redirect()->back()->with('success', 'User moved to trash.');
    }

    public function restore($id, NotificationService $notifications)
    {
        $admin = auth('admin')->user();

        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        // 🔥 Notify user
        $notifications->create(
            $user,
            'admin_restored',
            [
                'target_type' => 'user',
                'target_id' => $user->id,
                'admin_id' => $admin->id,
            ],
            $admin
        );

        return redirect()->back()->with('success', 'User restored successfully.');
    }
}
