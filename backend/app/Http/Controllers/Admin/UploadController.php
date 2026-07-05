<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function index()
    {
        $totalImagens = Media::where('tipo', 'imagem')->count();
        $totalVideos = Media::where('tipo', 'video')->count();
        $totalAudios = Media::where('tipo', 'audio')->count();
        $totalDocumentos = Media::where('tipo', 'documento')->count();
        $espacoUsado = Media::sum('tamanho');

        return view('admin.uploads.index', compact(
            'totalImagens', 'totalVideos', 'totalAudios', 'totalDocumentos', 'espacoUsado'
        ));
    }

    public function list(Request $request)
    {
        $query = Media::query();

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('nome_original', 'like', "%{$busca}%")
                  ->orWhere('caminho', 'like', "%{$busca}%");
            });
        }

        $media = $query->latest()->paginate(24);

        return response()->json($media);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'arquivo' => 'required|file|max:204800',
        ]);

        $file = $request->file('arquivo');
        $mime = $file->getMimeType();
        $tamanho = $file->getSize();
        $nomeOriginal = $file->getClientOriginalName();

        $tipo = match (true) {
            str_starts_with($mime, 'image/') => 'imagem',
            str_starts_with($mime, 'video/') => 'video',
            str_starts_with($mime, 'audio/') => 'audio',
            default => 'documento',
        };

        $diretorio = match ($tipo) {
            'imagem' => 'uploads/imagens',
            'video' => 'uploads/videos',
            'audio' => 'uploads/musicas',
            default => 'uploads/documentos',
        };

        $extensao = $file->getClientOriginalExtension();
        $nomeLimpo = Str::slug(pathinfo($nomeOriginal, PATHINFO_FILENAME));
        $nomeArmazenado = time() . '_' . $nomeLimpo . '.' . $extensao;

        $caminho = $file->storeAs("{$diretorio}", $nomeArmazenado, 'public');

        $media = Media::create([
            'nome_original' => $nomeOriginal,
            'nome_armazenado' => $nomeArmazenado,
            'caminho' => $caminho,
            'tipo' => $tipo,
            'mime_type' => $mime,
            'tamanho' => $tamanho,
            'pasta' => $diretorio,
            'usuario_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'media' => $media,
        ]);
    }

    public function destroy(Media $media)
    {
        $caminhoCompleto = storage_path("app/public/{$media->caminho}");

        if (file_exists($caminhoCompleto)) {
            unlink($caminhoCompleto);
        }

        $media->delete();

        return response()->json(['success' => true]);
    }

    public function batchDestroy(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        $mediaItems = Media::whereIn('id', $request->ids)->get();

        foreach ($mediaItems as $media) {
            $caminhoCompleto = storage_path("app/public/{$media->caminho}");
            if (file_exists($caminhoCompleto)) {
                unlink($caminhoCompleto);
            }
            $media->delete();
        }

        return response()->json(['success' => true, 'deleted' => $mediaItems->count()]);
    }
}
