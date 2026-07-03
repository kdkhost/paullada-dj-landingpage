<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class SocialLinkController extends Controller
{
    public function index()
    {
        $links = SocialLink::orderBy('ordem')->paginate(10);
        return view('admin.social-links.index', compact('links'));
    }

    public function create()
    {
        return view('admin.social-links.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'icone' => 'required|string|max:100',
            'ordem' => 'nullable|integer|min:0',
            'ativo' => 'nullable|boolean',
        ]);

        $data['ativo'] = $request->has('ativo');

        SocialLink::create($data);

        return redirect()->route('admin.social-links.index')->with('success', 'Link social criado com sucesso!');
    }

    public function edit(SocialLink $socialLink)
    {
        return view('admin.social-links.form', compact('socialLink'));
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'icone' => 'required|string|max:100',
            'ordem' => 'nullable|integer|min:0',
            'ativo' => 'nullable|boolean',
        ]);

        $data['ativo'] = $request->has('ativo');

        $socialLink->update($data);

        return redirect()->route('admin.social-links.index')->with('success', 'Link social atualizado com sucesso!');
    }

    public function destroy(SocialLink $socialLink)
    {
        $socialLink->delete();

        return redirect()->route('admin.social-links.index')->with('success', 'Link social excluído com sucesso!');
    }
}
