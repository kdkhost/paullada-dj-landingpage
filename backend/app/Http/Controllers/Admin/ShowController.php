<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreShowRequest;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShowController extends Controller
{
    public function index()
    {
        $shows = Show::orderBy('data_evento', 'desc')->paginate(10);
        return view('admin.shows.index', compact('shows'));
    }

    public function create()
    {
        return view('admin.shows.form');
    }

    public function store(StoreShowRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('foto_cartaz')) {
            $data['foto_cartaz'] = $request->file('foto_cartaz')->store('shows', 'public');
        }

        $data['status'] = $data['status'] ?? 'active';
        $data['destaque'] = $request->has('destaque');

        Show::create($data);

        return redirect()->route('admin.shows.index')->with('success', 'Show criado com sucesso!');
    }

    public function edit(Show $show)
    {
        return view('admin.shows.form', compact('show'));
    }

    public function update(StoreShowRequest $request, Show $show)
    {
        $data = $request->validated();

        if ($request->hasFile('foto_cartaz')) {
            if ($show->foto_cartaz) {
                Storage::disk('public')->delete($show->foto_cartaz);
            }
            $data['foto_cartaz'] = $request->file('foto_cartaz')->store('shows', 'public');
        }

        $data['destaque'] = $request->has('destaque');

        $show->update($data);

        return redirect()->route('admin.shows.index')->with('success', 'Show atualizado com sucesso!');
    }

    public function destroy(Show $show)
    {
        if ($show->foto_cartaz) {
            Storage::disk('public')->delete($show->foto_cartaz);
        }
        $show->delete();

        return redirect()->route('admin.shows.index')->with('success', 'Show excluído com sucesso!');
    }
}
