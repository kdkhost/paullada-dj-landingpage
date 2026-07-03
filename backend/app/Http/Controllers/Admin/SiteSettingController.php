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
}
