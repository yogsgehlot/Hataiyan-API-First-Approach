<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report; // assume you have a Report model for user posts/comments reports
use App\Models\AccountRestoreRequest; // optional model

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $q = Report::query();

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where('title', 'like', "%{$term}%")->orWhere('message', 'like', "%{$term}%");
        }

        $perPage = $request->get('per_page', 20);
        return response()->json($q->paginate($perPage));
    }

    public function resolve(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $report->status = $request->get('status', 'resolved'); // resolved, ignored
        $report->resolved_by = $request->user()->id;
        $report->save();

        return response()->json(['message' => 'Report updated', 'report' => $report]);
    }

    public function restoreRequests(Request $request)
    {
        $q = AccountRestoreRequest::query(); // customize
        $perPage = $request->get('per_page', 20);
        return response()->json($q->paginate($perPage));
    }

    public function approveRestore($id)
    {
        $req = AccountRestoreRequest::findOrFail($id);
        $user = $req->user; // relation
        if ($user && $user->trashed()) {
            $user->restore();
            $req->status = 'approved';
            $req->processed_by = request()->user()->id;
            $req->save();
            return response()->json(['message' => 'Account restored', 'user' => $user]);
        }

        return response()->json(['message' => 'Unable to restore'], 400);
    }

    public function rejectRestore($id)
    {
        $req = AccountRestoreRequest::findOrFail($id);
        $req->status = 'rejected';
        $req->processed_by = request()->user()->id;
        $req->save();

        return response()->json(['message' => 'Restore request rejected']);
    }
}
