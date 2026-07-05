@extends('admin.layouts.admin')

@section('page_title', isset($gallery) ? 'Editar Item da Galeria' : 'Novo Item da Galeria')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ isset($gallery) ? 'Editar Item da Galeria' : 'Novo Item da Galeria' }}</h3>
    </div>
    <form id="galleryForm" action="{{ isset($gallery) ? route('admin.gallery.update', $gallery) : route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
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
                <label>Arquivo (Imagem)</label>
                <input type="hidden" name="arquivo" id="arquivo_path" value="{{ old('arquivo', $gallery->arquivo ?? '') }}">

                <div id="dropzone" class="dropzone-area" style="border:2px dashed #ddd; border-radius:8px; padding:40px; text-align:center; cursor:pointer; transition: all .3s; position:relative;">
                    <div id="dropzone-placeholder">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                        <p class="text-muted mb-1">Arraste e solte uma imagem aqui</p>
                        <p class="text-muted small">ou clique para selecionar (máx. 5MB)</p>
                    </div>
                    <div id="dropzone-preview" style="display:none; position:relative;">
                        <img id="preview-img" src="" alt="Preview" style="max-height:200px; border-radius:6px;">
                        <button type="button" id="remove-file" class="btn btn-danger btn-sm" style="position:absolute; top:5px; right:5px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="dropzone-progress" style="display:none; margin-top:15px;">
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width:0%"></div>
                        </div>
                        <small class="text-muted mt-1 d-block">Enviando...</small>
                    </div>
                    <input type="file" id="file-input" accept="image/*" style="display:none;">
                </div>
                @error('arquivo') <span class="text-danger">{{ $message }}</span> @enderror
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
            <button type="submit" class="btn btn-primary" id="submit-btn">
                <i class="fas fa-save"></i> {{ isset($gallery) ? 'Atualizar' : 'Salvar' }}
            </button>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@stop

@push('styles')
<style>
    .dropzone-area { background: #f8f9fa; }
    .dropzone-area.dragover { border-color: #007bff; background: #e7f1ff; }
    .dropzone-area.has-file { border-color: #28a745; background: #f0fff4; }
</style>
@endpush

@push('scripts')
<script>
(function() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('file-input');
    const placeholder = document.getElementById('dropzone-placeholder');
    const preview = document.getElementById('dropzone-preview');
    const previewImg = document.getElementById('preview-img');
    const progress = document.getElementById('dropzone-progress');
    const progressBar = progress.querySelector('.progress-bar');
    const removeBtn = document.getElementById('remove-file');
    const pathInput = document.getElementById('arquivo_path');
    const form = document.getElementById('galleryForm');
    const submitBtn = document.getElementById('submit-btn');

    const existingPath = pathInput.value;
    if (existingPath) {
        previewImg.src = '/storage/' + existingPath;
        placeholder.style.display = 'none';
        preview.style.display = 'block';
        dropzone.classList.add('has-file');
    }

    dropzone.addEventListener('click', () => fileInput.click());

    dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        if (e.dataTransfer.files.length) uploadFile(e.dataTransfer.files[0]);
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) uploadFile(fileInput.files[0]);
    });

    removeBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        pathInput.value = '';
        previewImg.src = '';
        preview.style.display = 'none';
        placeholder.style.display = 'block';
        dropzone.classList.remove('has-file');
        fileInput.value = '';
    });

    function uploadFile(file) {
        if (!file.type.startsWith('image/')) { alert('Apenas imagens são aceitas.'); return; }
        if (file.size > 5 * 1024 * 1024) { alert('Arquivo muito grande. Máximo 5MB.'); return; }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('pasta', 'gallery');

        placeholder.style.display = 'none';
        progress.style.display = 'block';
        progressBar.style.width = '0%';
        submitBtn.disabled = true;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route("api.upload") }}');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const pct = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = pct + '%';
            }
        });

        xhr.onload = function() {
            submitBtn.disabled = false;
            progress.style.display = 'none';
            if (xhr.status === 200) {
                const resp = JSON.parse(xhr.responseText);
                pathInput.value = resp.caminho;
                previewImg.src = resp.url;
                preview.style.display = 'block';
                dropzone.classList.add('has-file');
            } else {
                alert('Erro ao enviar arquivo. Tente novamente.');
                placeholder.style.display = 'block';
            }
        };

        xhr.onerror = function() {
            submitBtn.disabled = false;
            progress.style.display = 'none';
            alert('Erro de conexão. Tente novamente.');
            placeholder.style.display = 'block';
        };

        xhr.send(formData);
    }

    $('#tipo').on('change', function() {
        if ($(this).val() === 'foto') {
            $('#foto-group').show();
            $('#video-group').hide();
        } else {
            $('#foto-group').hide();
            $('#video-group').show();
        }
    });
})();
</script>
@endpush
