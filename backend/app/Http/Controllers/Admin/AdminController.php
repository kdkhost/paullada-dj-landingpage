<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Show;
use App\Models\GalleryItem;
use App\Models\AnalyticsEvent;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $totalShows = Show::count();
        $futureShows = Show::where('data_evento', '>=', now())->where('status', 'active')->count();
        $totalGallery = GalleryItem::count();
        $totalEvents = AnalyticsEvent::count();

        $visitsPerDay = AnalyticsEvent::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('total', 'date')
            ->toArray();

        $dates = [];
        $counts = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;
            $counts[] = $visitsPerDay[$date] ?? 0;
        }

        $recentShows = Show::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard.index', compact(
            'totalShows', 'futureShows', 'totalGallery', 'totalEvents',
            'dates', 'counts', 'recentShows'
        ));
    }
}
