<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Overview Stats
        $stats = [
            'total_visits' => \App\Models\ActivityLog::count(),
            'unique_visitors' => \App\Models\ActivityLog::distinct('ip_address')->count(),
            'total_emails' => \App\Models\EmailLog::count(),
            'today_visits' => \App\Models\ActivityLog::whereDate('created_at', today())->count(),
        ];

        // Top pages
        $topPages = \App\Models\ActivityLog::select('url')
            ->selectRaw('count(*) as count')
            ->groupBy('url')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Recent Activity
        $recentActivity = \App\Models\ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact('stats', 'topPages', 'recentActivity'));
    }

    public function activity(Request $request)
    {
        $query = \App\Models\ActivityLog::with('user');

        if ($request->filled('search')) {
            $query->where('url', 'like', '%' . $request->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $request->search . '%');
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->latest()->paginate(20);
        return view('admin.analytics.activity', compact('logs'));
    }

    public function emailLogs(Request $request)
    {
        $query = \App\Models\EmailLog::with('user');
        
        if ($request->filled('search')) {
            $query->where('recipient_email', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%');
        }
        
        $logs = $query->latest()->paginate(20);
        return view('admin.analytics.emails', compact('logs'));
    }
    
    public function showEmailLog(\App\Models\EmailLog $emailLog)
    {
        return view('admin.analytics.email-show', compact('emailLog'));
    }
}
