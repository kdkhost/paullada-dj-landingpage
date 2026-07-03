<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Show;
use App\Models\GalleryItem;
use App\Models\SocialLink;
use App\Models\SiteSetting;
use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function shows()
    {
        $shows = Show::where('status', 'active')
            ->where('data_evento', '>=', now())
            ->orderBy('data_evento')
            ->get();

        return response()->json($shows);
    }

    public function gallery()
    {
        $items = GalleryItem::where('ativo', true)
            ->orderBy('ordem')
            ->get();

        return response()->json($items);
    }

    public function socialLinks()
    {
        $links = SocialLink::where('ativo', true)
            ->orderBy('ordem')
            ->get();

        return response()->json($links);
    }

    public function settings()
    {
        $settings = SiteSetting::all()->pluck('valor', 'chave');

        return response()->json($settings);
    }

    public function trackEvent(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|string|max:100',
            'pagina' => 'nullable|string|max:255',
        ]);

        AnalyticsEvent::create([
            'tipo' => $data['tipo'],
            'pagina' => $data['pagina'] ?? null,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json(['success' => true]);
    }
}
