<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::orderBy('chave')->paginate(20);
        return view('admin.site-settings.index', compact('settings'));
    }

    public function create()
    {
        return view('admin.site-settings.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'chave' => 'required|string|max:100|unique:site_settings,chave',
            'valor' => 'nullable|string',
            'tipo' => 'required|in:text,textarea,image,boolean',
        ]);

        SiteSetting::create($data);

        return redirect()->route('admin.site-settings.index')->with('success', 'Configuração criada com sucesso!');
    }

    public function edit(SiteSetting $siteSetting)
    {
        return view('admin.site-settings.form', compact('siteSetting'));
    }

    public function update(Request $request, SiteSetting $siteSetting)
    {
        $data = $request->validate([
            'chave' => 'required|string|max:100|unique:site_settings,chave,' . $siteSetting->id,
            'valor' => 'nullable|string',
            'tipo' => 'required|in:text,textarea,image,boolean',
        ]);

        $siteSetting->update($data);

        return redirect()->route('admin.site-settings.index')->with('success', 'Configuração atualizada com sucesso!');
    }

    public function destroy(SiteSetting $siteSetting)
    {
        $siteSetting->delete();

        return redirect()->route('admin.site-settings.index')->with('success', 'Configuração excluída com sucesso!');
    }

    public function hero()
    {
        $settings = SiteSetting::whereIn('chave', [
            'hero_bg_type', 'hero_bg_image', 'hero_bg_video',
        ])->pluck('valor', 'chave')->toArray();

        return view('admin.hero', compact('settings'));
    }

    public function saveHero(Request $request)
    {
        $data = $request->validate([
            'hero_bg_type' => 'required|in:gradient,image,video',
            'hero_bg_image' => 'nullable|string|max:500',
            'hero_bg_video' => 'nullable|string|max:500',
        ]);

        foreach ($data as $chave => $valor) {
            SiteSetting::updateOrCreate(
                ['chave' => $chave],
                ['valor' => $valor, 'tipo' => 'text']
            );
        }

        return redirect()->route('admin.hero')->with('success', 'Fundo do hero atualizado com sucesso!');
    }
}
