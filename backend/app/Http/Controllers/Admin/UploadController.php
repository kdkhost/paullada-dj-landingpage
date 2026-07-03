<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function index()
    {
        return view('admin.uploads.index');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|max:204800',
            'tipo' => 'required|in:imagem,video,musica',
        ]);

        $file = $request->file('arquivo');
        $tipo = $request->input('tipo');
        $nomeOriginal = $file->getClientOriginalName();
        $extensao = $file->getClientOriginalExtension();
        $nome = pathinfo($nomeOriginal, PATHINFO_FILENAME);
        $nomeArquivo = time() . '_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $nome) . '.' . $extensao;

        $diretorio = match ($tipo) {
            'imagem' => 'uploads/imagens',
            'video' => 'uploads/videos',
            'musica' => 'uploads/musicas',
        };

        $path = $file->storeAs("public/{$diretorio}", $nomeArquivo);
        $url = Storage::url($path);

        return response()->json([
            'success' => true,
            'nome' => $nomeArquivo,
            'url' => $url,
            'tipo' => $tipo,
            'tamanho' => $file->getSize(),
        ]);
    }
}
