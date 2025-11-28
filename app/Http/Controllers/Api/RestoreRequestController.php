<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RestoreRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RestoreRequestController extends Controller
{
    public function store($username)
    {
        $authUser = Auth::user();

        if ($authUser->username !== $username) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
        }

        $existing = RestoreRequest::where('user_id', $authUser->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return response()->json(['status' => false, 'message' => 'A restore request is already pending.']);
        }

        $request = RestoreRequest::create([
            'user_id' => $authUser->id,
            'request_type' => 'account_restore',
            'status' => 'pending',
        ]);

        return response()->json(['status' => true, 'message' => 'Restore request submitted successfully.', 'data' => $request]);
    }
}
