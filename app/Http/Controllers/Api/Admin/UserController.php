<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // existing user model

class UserController extends Controller
{
    public function index(Request $request)
    {
        // search / filter / paginate
        $q = User::query();

        if ($request->filled('q')) {
            $term = $request->get('q');
            $q->where(function($s) use ($term){
                $s->where('name', 'like', "%{$term}%")
                  ->orWhere('email','like',"%{$term}%");
            });
        }

        if ($request->filled('role')) {
            $q->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $q->whereNull('deleted_at');
            } elseif ($request->status === 'trashed') {
                // handled by /users-trashed
            }
        }

        $perPage = $request->get('per_page', 15);
        $users = $q->paginate($perPage);

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        return response()->json($user);
    }

    // permanently delete (dangerous)
    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        // do any cleanup here: delete related media etc.
        $user->forceDelete();

        return response()->json(['message'=>'User permanently deleted.']);
    }

    public function trashed(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $users = User::onlyTrashed()->paginate($perPage);
        return response()->json($users);
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return response()->json(['message'=>'User restored', 'user'=>$user]);
    }
}
