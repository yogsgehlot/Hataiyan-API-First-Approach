<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Report; // change if your model name is different

class DashboardController extends Controller
{
    public function index()
    {
    
        // Fetch counts
        $totalUsers   = User::count();
        $totalPosts   = Post::count();
        $totalReports = Report::count(); // or whatever model you're using

        // Fetch recent items
        $recentUsers = User::latest()->take(5)->get();
        $recentPosts = Post::with('user')->latest()->take(5)->get();
        $recentReports = Report::latest()->take(5)->get();

        return view('admin.auth.dashboard', [
            'totalUsers'     => $totalUsers,
            'totalPosts'     => $totalPosts,
            'totalReports'   => $totalReports,
            'recentUsers'    => $recentUsers,
            'recentPosts'    => $recentPosts,
            'recentReports'  => $recentReports,
        ]);
    }
}
