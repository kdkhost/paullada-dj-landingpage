@extends('admin.layouts.admin')

@section('page_title', 'Fundo do Hero (Página Inicial)')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-image"></i> Configurar Fundo do Hero</h3>
            </div>
            <form id="heroForm" action="{{ route('admin.hero.save') }}" method="POST">
                @csrf
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="form-group">
                        <label><strong>Tipo de Fundo</strong></label>
                        <div class="d-flex gap-3">
                            <div class="form-check mr-3">
                                <input class="form-check-input" type="radio" name="hero_bg_type" id="type_gradient" value="gradient" {{ ($settings['hero_bg_type'] ?? 'gradient') === 'gradient' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_gradient">
                                    <i class="fas fa-palette"></i> Gradiente Padrão
                                </label>
                            </div>
                            <div class="form-check mr-3">
                                <input class="form-check-input" type="radio" name="hero_bg_type" id="type_image" value="image" {{ ($settings['hero_bg_type'] ?? '') === 'image' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_image">
                                    <i class="fas fa-image"></i> Imagem
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="hero_bg_type" id="type_video" value="video" {{ ($settings['hero_bg_type'] ?? '') === 'video' ? 'checked' : '' }}>
                                <label class="form-check-label" for="type_video">
                                    <i class="fas fa-video"></i> Vídeo
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="image-section" class="form-group" style="{{ ($settings['hero_bg_type'] ?? 'gradient') === 'image' ? '' : 'display:none' }}">
                        <label><strong>Imagem de Fundo</strong></label>
                        <input type="hidden" name="hero_bg_image" id="hero_bg_image" value="{{ $settings['hero_bg_image'] ?? '' }}">

                        <div id="hero-dropzone" class="dropzone-area" style="border:2px dashed #ddd; border-radius:8px; padding:30px; text-align:center; cursor:pointer; transition: all .3s;">
                            <div id="hero-dz-placeholder">
                                <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-1">Arraste uma imagem ou clique para selecionar</p>
                                <small class="text-muted">Recomendado: 1920x1080px, máx 5MB</small>
                            </div>
                            <div id="hero-dz-preview" style="display:none; position:relative;">
                                <img id="hero-preview-img" src="" alt="Preview" style="max-height:250px; width:100%; object-fit:cover; border-radius:6px;">
                                <button type="button" id="hero-remove-file" class="btn btn-danger btn-sm" style="position:absolute; top:8px; right:8px;">
                                    <i class="fas fa-times"></i> Remover
                                </button>
                            </div>
                            <div id="hero-dz-progress" style="display:none; margin-top:15px;">
                                <div class="progress" style="height:6px;">
                                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width:0%"></div>
                                </div>
                            </div>
                            <input type="file" id="hero-file-input" accept="image/*" style="display:none;">
                        </div>

                        @if(!empty($settings['hero_bg_image']))
                        <div class="mt-2">
                            <small class="text-muted">Atual: {{ basename($settings['hero_bg_image']) }}</small>
                        </div>
                        @endif
                    </div>

                    <div id="video-section" class="form-group" style="{{ ($settings['hero_bg_type'] ?? '') === 'video' ? '' : 'display:none' }}">
                        <label><strong>URL do Vídeo (MP4)</strong></label>
                        <input type="url" name="hero_bg_video" class="form-control" value="{{ $settings['hero_bg_video'] ?? '' }}" placeholder="https://exemplo.com/video.mp4">
                        <small class="text-muted">Cole a URL de um vídeo MP4 hospedado externamente</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" id="hero-submit-btn">
                        <i class="fas fa-save"></i> Salvar Configurações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-eye"></i> Preview</h3>
            </div>
            <div class="card-body p-0">
                <div id="hero-preview" style="height:250px; background: linear-gradient(135deg, #0a0a0a, #1a1a1a, #0a0a0a); display:flex; align-items:center; justify-content:center; position:relative; overflow:hidden;">
                    <div style="text-align:center; z-index:1;">
                        <div style="font-family: Orbitron, sans-serif; font-size:24px; font-weight:bold; color:#BB9C55;">PAULLADA</div>
                        <div style="font-family: Orbitron, sans-serif; font-size:18px; color:#22D3EE;">DJ</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('styles')
<style>
    .dropzone-area { background: #f8f9fa; }
    .dropzone-area.dragover { border-color: #007bff !important; background: #e7f1ff; }
    .dropzone-area.has-file { border-color: #28a745; background: #f0fff4; }
</style>
@endpush

@push('scripts')
<script>
(function() {
    const form = document.getElementById('heroForm');
    const typeRadios = document.querySelectorAll('input[name="hero_bg_type"]');
    const imageSection = document.getElementById('image-section');
    const videoSection = document.getElementById('video-section');
    const dropzone = document.getElementById('hero-dropzone');
    const fileInput = document.getElementById('hero-file-input');
    const placeholder = document.getElementById('hero-dz-placeholder');
    const previewDiv = document.getElementById('hero-dz-preview');
    const previewImg = document.getElementById('hero-preview-img');
    const progress = document.getElementById('hero-dz-progress');
    const progressBar = progress.querySelector('.progress-bar');
    const removeBtn = document.getElementById('hero-remove-file');
    const pathInput = document.getElementById('hero_bg_image');
    const heroPreview = document.getElementById('hero-preview');
    const submitBtn = document.getElementById('hero-submit-btn');

    const existingPath = pathInput.value;
    if (existingPath) {
        previewImg.src = existingPath.startsWith('http') ? existingPath : '/storage/' + existingPath;
        placeholder.style.display = 'none';
        previewDiv.style.display = 'block';
        dropzone.classList.add('has-file');
        heroPreview.style.backgroundImage = 'url(' + previewImg.src + ')';
        heroPreview.style.backgroundSize = 'cover';
        heroPreview.style.backgroundPosition = 'center';
    }

    typeRadios.forEach(radio => {
        radio.addEventListener('change', () => {
            imageSection.style.display = document.getElementById('type_image').checked ? '' : 'none';
            videoSection.style.display = document.getElementById('type_video').checked ? '' : 'none';
            updatePreview();
        });
    });

    function updatePreview() {
        const type = document.querySelector('input[name="hero_bg_type"]:checked').value;
        if (type === 'gradient') {
            heroPreview.style.background = 'linear-gradient(135deg, #0a0a0a, #1a1a1a, #0a0a0a)';
            heroPreview.style.backgroundImage = '';
        } else if (type === 'image') {
            const img = pathInput.value;
            if (img) {
                const url = img.startsWith('http') ? img : '/storage/' + img;
                heroPreview.style.backgroundImage = 'url(' + url + ')';
                heroPreview.style.backgroundSize = 'cover';
                heroPreview.style.backgroundPosition = 'center';
            }
        }
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
        previewDiv.style.display = 'none';
        placeholder.style.display = 'block';
        dropzone.classList.remove('has-file');
        fileInput.value = '';
        heroPreview.style.backgroundImage = '';
        heroPreview.style.background = 'linear-gradient(135deg, #0a0a0a, #1a1a1a, #0a0a0a)';
    });

    function uploadFile(file) {
        if (!file.type.startsWith('image/')) { alert('Apenas imagens são aceitas.'); return; }
        if (file.size > 5 * 1024 * 1024) { alert('Arquivo muito grande. Máximo 5MB.'); return; }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('pasta', 'hero');

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
                progressBar.style.width = Math.round((e.loaded / e.total) * 100) + '%';
            }
        });

        xhr.onload = function() {
            submitBtn.disabled = false;
            progress.style.display = 'none';
            if (xhr.status === 200) {
                const resp = JSON.parse(xhr.responseText);
                pathInput.value = resp.caminho;
                previewImg.src = resp.url;
                previewDiv.style.display = 'block';
                dropzone.classList.add('has-file');
                heroPreview.style.backgroundImage = 'url(' + resp.url + ')';
                heroPreview.style.backgroundSize = 'cover';
                heroPreview.style.backgroundPosition = 'center';
            } else {
                alert('Erro ao enviar arquivo.');
                placeholder.style.display = 'block';
            }
        };

        xhr.onerror = function() {
            submitBtn.disabled = false;
            progress.style.display = 'none';
            alert('Erro de conexão.');
            placeholder.style.display = 'block';
        };

        xhr.send(formData);
    }
})();
</script>
@endpush
