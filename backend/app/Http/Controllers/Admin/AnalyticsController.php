<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $totalPageViews = AnalyticsEvent::count();

        $pageViews = AnalyticsEvent::select('pagina', DB::raw('count(*) as total'))
            ->whereNotNull('pagina')
            ->groupBy('pagina')
            ->orderByDesc('total')
            ->get();

        $eventTypes = AnalyticsEvent::select('tipo', DB::raw('count(*) as total'))
            ->groupBy('tipo')
            ->orderByDesc('total')
            ->get();

        $visitsPerDay = AnalyticsEvent::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = [];
        $counts = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;
            $visits = $visitsPerDay->firstWhere('date', $date);
            $counts[] = $visits ? $visits->total : 0;
        }

        $pageLabels = $pageViews->pluck('pagina')->toJson();
        $pageCounts = $pageViews->pluck('total')->toJson();

        $typeLabels = $eventTypes->pluck('tipo')->toJson();
        $typeCounts = $eventTypes->pluck('total')->toJson();

        return view('admin.analytics.index', compact(
            'totalPageViews', 'pageViews', 'eventTypes',
            'dates', 'counts',
            'pageLabels', 'pageCounts',
            'typeLabels', 'typeCounts'
        ));
    }
}
