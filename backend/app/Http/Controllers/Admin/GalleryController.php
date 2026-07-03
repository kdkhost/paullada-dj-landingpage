<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $items = GalleryItem::orderBy('ordem')->paginate(10);
        return view('admin.gallery.index', compact('items'));
    }

    public function create()
    {
        return view('admin.gallery.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|in:foto,video',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|image|max:5120',
            'url_youtube' => 'nullable|url|max:500',
            'ordem' => 'nullable|integer|min:0',
            'ativo' => 'nullable|boolean',
        ]);

        if ($request->hasFile('arquivo')) {
            $data['arquivo'] = $request->file('arquivo')->store('gallery', 'public');
        }

        $data['ativo'] = $request->has('ativo');

        GalleryItem::create($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Item da galeria criado com sucesso!');
    }

    public function edit(GalleryItem $gallery)
    {
        return view('admin.gallery.form', compact('gallery'));
    }

    public function update(Request $request, GalleryItem $gallery)
    {
        $data = $request->validate([
            'tipo' => 'required|in:foto,video',
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'arquivo' => 'nullable|image|max:5120',
            'url_youtube' => 'nullable|url|max:500',
            'ordem' => 'nullable|integer|min:0',
            'ativo' => 'nullable|boolean',
        ]);

        if ($request->hasFile('arquivo')) {
            if ($gallery->arquivo) {
                Storage::disk('public')->delete($gallery->arquivo);
            }
            $data['arquivo'] = $request->file('arquivo')->store('gallery', 'public');
        }

        $data['ativo'] = $request->has('ativo');

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Item da galeria atualizado com sucesso!');
    }

    public function destroy(GalleryItem $gallery)
    {
        if ($gallery->arquivo) {
            Storage::disk('public')->delete($gallery->arquivo);
        }
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Item da galeria excluído com sucesso!');
    }
}
