@extends('admin.layouts.admin')

@section('page_title', isset($gallery) ? 'Editar Item da Galeria' : 'Novo Item da Galeria')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ isset($gallery) ? 'Editar Item da Galeria' : 'Novo Item da Galeria' }}</h3>
    </div>
    <form action="{{ isset($gallery) ? route('admin.gallery.update', $gallery) : route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
        <div class="card-body">
            @csrf
            @if(isset($gallery)) @method('PUT') @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <select name="tipo" id="tipo" class="form-control" required>
                            <option value="foto" {{ old('tipo', $gallery->tipo ?? '') == 'foto' ? 'selected' : '' }}>Foto</option>
                            <option value="video" {{ old('tipo', $gallery->tipo ?? '') == 'video' ? 'selected' : '' }}>Vídeo</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ordem">Ordem</label>
                        <input type="number" min="0" name="ordem" id="ordem" class="form-control" value="{{ old('ordem', $gallery->ordem ?? 0) }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $gallery->titulo ?? '') }}" required>
                @error('titulo') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" rows="3" class="form-control">{{ old('descricao', $gallery->descricao ?? '') }}</textarea>
            </div>

            <div id="foto-group" class="form-group">
                <label for="arquivo">Arquivo (Imagem)</label>
                <input type="file" name="arquivo" id="arquivo" class="form-control-file @error('arquivo') is-invalid @enderror">
                @error('arquivo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                @if(isset($gallery) && $gallery->arquivo && $gallery->tipo == 'foto')
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $gallery->arquivo) }}" alt="Preview" style="max-height: 100px;">
                    </div>
                @endif
            </div>

            <div id="video-group" class="form-group" style="{{ old('tipo', $gallery->tipo ?? '') == 'video' ? '' : 'display:none;' }}">
                <label for="url_youtube">URL do YouTube</label>
                <input type="url" name="url_youtube" id="url_youtube" class="form-control" value="{{ old('url_youtube', $gallery->url_youtube ?? '') }}">
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="ativo" id="ativo" class="form-check-input" value="1" {{ old('ativo', $gallery->ativo ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="ativo">Ativo</label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ isset($gallery) ? 'Atualizar' : 'Salvar' }}</button>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@stop

@push('scripts')
<script>
    $('#tipo').on('change', function() {
        if ($(this).val() === 'foto') {
            $('#foto-group').show();
            $('#video-group').hide();
        } else {
            $('#foto-group').hide();
            $('#video-group').show();
        }
    });
</script>
@endpush
