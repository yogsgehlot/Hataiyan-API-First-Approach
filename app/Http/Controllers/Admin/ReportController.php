<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Services\NotificationService;


class ReportController extends Controller
{
    /**
     * List all reports
     */
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reportedUser', 'post']);

        if ($request->q) {
            $query->where(function ($sub) use ($request) {
                $sub->where('reason', 'like', "%{$request->q}%")
                    ->orWhereHas('reporter', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->q}%");
                    })
                    ->orWhereHas('reportedUser', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->q}%");
                    });
            });
        }

        $query->orderByRaw("
        FIELD(status, 'pending', 'reviewed', 'resolved')
    ")->orderBy('created_at', 'desc');

        $perPage = $request->per_page ?? 10;

        $reports = $query->paginate($perPage)->withQueryString();

        return view('admin.reports.index', [
            'reports' => $reports,
            'filters' => $request->only(['q', 'per_page'])
        ]);
    }

    public function resolve($id, NotificationService $notifications)
    {
        $admin = auth('admin')->user();
        $report = Report::findOrFail($id);

        $report->status = 'resolved';
        $report->resolved_at = now();
        $report->processed_by = $admin->id;
        $report->save();

        // Get users
        $reporter = $report->reporter;          // reported_by user
        $postOwner = $report->reportedUser;     // the post owner

        if ($reporter) {
            $notifications->create(
                $reporter,
                'report_actioned',
                [
                    'report_id' => $report->id,
                    'action' => 'resolved',
                    'post_id' => $report->post_id,
                    'admin_id' => $admin->id
                ],
                $admin
            );
        }

        if ($postOwner) {
            $notifications->create(
                $postOwner,
                'report_actioned',
                [
                    'report_id' => $report->id,
                    'action' => 'resolved',
                    'post_id' => $report->post_id,
                    'admin_id' => $admin->id
                ],
                $admin
            );
        }

        return redirect()->back()->with('success', 'Report marked as resolved.');
    }

}
