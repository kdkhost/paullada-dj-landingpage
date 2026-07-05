<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadApiController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:20480',
            'pasta' => 'nullable|string|max:100',
        ]);

        $file = $request->file('file');
        $pasta = $request->input('pasta', 'uploads');
        $mime = $file->getMimeType();
        $tamanho = $file->getSize();
        $nomeOriginal = $file->getClientOriginalName();

        $tipo = match (true) {
            str_starts_with($mime, 'image/') => 'imagem',
            str_starts_with($mime, 'video/') => 'video',
            str_starts_with($mime, 'audio/') => 'audio',
            default => 'documento',
        };

        $extensao = $file->getClientOriginalExtension();
        $nomeLimpo = Str::slug(pathinfo($nomeOriginal, PATHINFO_FILENAME));
        $nomeArmazenado = time() . '_' . $nomeLimpo . '.' . $extensao;

        $caminho = $file->storeAs("{$pasta}", $nomeArmazenado, 'public');

        return response()->json([
            'success' => true,
            'url' => Storage::disk('public')->url($caminho),
            'caminho' => $caminho,
            'nome_original' => $nomeOriginal,
            'tamanho' => $tamanho,
            'mime_type' => $mime,
            'tipo' => $tipo,
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'caminho' => 'required|string',
        ]);

        $caminho = $request->input('caminho');

        if (Storage::disk('public')->exists($caminho)) {
            Storage::disk('public')->delete($caminho);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Arquivo não encontrado'], 404);
    }
}
