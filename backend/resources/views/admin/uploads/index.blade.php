@extends('adminlte::page')

@section('title', 'Gerenciador de Mídia')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-photo-video text-primary"></i> Gerenciador de Mídia</h1>
        <div class="btn-group">
            <button type="button" class="btn btn-danger btn-sm" id="btnExcluirSelecionados" disabled>
                <i class="fas fa-trash"></i> Excluir Selecionados (<span id="countSelecionados">0</span>)
            </button>
        </div>
    </div>
@stop

@section('content')

<div class="row">
    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cloud-upload-alt"></i> Enviar Arquivos</h3>
            </div>
            <div class="card-body p-0">
                <div class="upload-zone" id="uploadZone">
                    <input type="file" id="fileInput" multiple style="display:none" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.txt">
                    <div class="upload-zone-content">
                        <div class="upload-zone-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <h4 class="upload-zone-title">Arraste seus arquivos aqui</h4>
                        <p class="upload-zone-subtitle">ou clique para selecionar do computador</p>
                        <div class="upload-zone-formats">
                            <span class="format-badge format-image"><i class="fas fa-image"></i> Imagens</span>
                            <span class="format-badge format-video"><i class="fas fa-video"></i> Vídeos</span>
                            <span class="format-badge format-audio"><i class="fas fa-music"></i> Áudios</span>
                            <span class="format-badge format-doc"><i class="fas fa-file"></i> Documentos</span>
                        </div>
                        <p class="upload-zone-limit">Máximo 200MB por arquivo</p>
                    </div>
                </div>

                <div id="uploadQueue" class="p-3" style="display:none"></div>
            </div>
        </div>

        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-folder-open"></i> Biblioteca de Mídia</h3>
                <div class="card-tools d-flex align-items-center gap-2">
                    <div class="btn-group btn-group-sm mr-2">
                        <button type="button" class="btn btn-outline-secondary filter-btn active" data-tipo="">Todos</button>
                        <button type="button" class="btn btn-outline-info filter-btn" data-tipo="imagem"><i class="fas fa-image"></i></button>
                        <button type="button" class="btn btn-outline-success filter-btn" data-tipo="video"><i class="fas fa-video"></i></button>
                        <button type="button" class="btn btn-outline-warning filter-btn" data-tipo="audio"><i class="fas fa-music"></i></button>
                    </div>
                    <div class="input-group input-group-sm" style="width:200px">
                        <input type="text" class="form-control" id="buscaMedia" placeholder="Buscar arquivo...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="btnBuscar"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="mediaGrid" class="media-grid">
                    <div class="text-center p-5" id="mediaLoading">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="mt-2 text-muted">Carregando mídia...</p>
                    </div>
                </div>
                <div id="mediaPagination" class="d-flex justify-content-center p-3"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Estatísticas</h3>
            </div>
            <div class="card-body">
                <div class="stat-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="fas fa-image text-info"></i> Imagens</span>
                        <span class="badge badge-info" id="statImagens">{{ $totalImagens }}</span>
                    </div>
                    <div class="progress progress-sm mb-3" style="height:4px">
                        <div class="progress-bar bg-info" id="barImagens" style="width:0%"></div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="fas fa-video text-success"></i> Vídeos</span>
                        <span class="badge badge-success" id="statVideos">{{ $totalVideos }}</span>
                    </div>
                    <div class="progress progress-sm mb-3" style="height:4px">
                        <div class="progress-bar bg-success" id="barVideos" style="width:0%"></div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="fas fa-music text-warning"></i> Áudios</span>
                        <span class="badge badge-warning" id="statAudios">{{ $totalAudios }}</span>
                    </div>
                    <div class="progress progress-sm mb-3" style="height:4px">
                        <div class="progress-bar bg-warning" id="barAudios" style="width:0%"></div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="fas fa-file text-secondary"></i> Documentos</span>
                        <span class="badge badge-secondary" id="statDocs">{{ $totalDocumentos }}</span>
                    </div>
                    <div class="progress progress-sm mb-3" style="height:4px">
                        <div class="progress-bar bg-secondary" id="barDocs" style="width:0%"></div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="text-muted"><i class="fas fa-database"></i> Espaço usado</span>
                    <strong class="text-white" id="statEspaco">{{ number_format($espacoUsado / 1048576, 2, ',', '.') }} MB</strong>
                </div>
            </div>
        </div>

        <div class="card card-dark card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-lightbulb"></i> Dicas</h3>
            </div>
            <div class="card-body p-2">
                <ul class="list-unstyled mb-0" style="font-size:13px">
                    <li class="py-1"><i class="fas fa-check text-success"></i> Arraste vários arquivos de uma vez</li>
                    <li class="py-1"><i class="fas fa-check text-success"></i> Clique no arquivo para copiar a URL</li>
                    <li class="py-1"><i class="fas fa-check text-success"></i> Use os filtros para encontrar rápido</li>
                    <li class="py-1"><i class="fas fa-check text-success"></i> Selecione vários para excluir em lote</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="background:#0d1117;border:1px solid #30363d">
            <div class="modal-header border-bottom border-secondary">
                <h5 class="modal-title text-white" id="previewTitle">Preview</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-info mr-2" id="btnCopiarUrlModal">
                        <i class="fas fa-link"></i> Copiar URL
                    </button>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
            </div>
            <div class="modal-body text-center p-0" id="previewBody"></div>
            <div class="modal-footer border-top border-secondary justify-content-between">
                <small class="text-muted" id="previewInfo"></small>
                <div>
                    <button class="btn btn-sm btn-outline-danger" id="btnExcluirModal"><i class="fas fa-trash"></i> Excluir</button>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
<style>
.upload-zone {
    border: 3px dashed #30363d;
    border-radius: 0;
    padding: 60px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #0d1117;
    margin: 0;
}
.upload-zone:hover { border-color: #BB9C55; background: #161b22; }
.upload-zone.drag-over {
    border-color: #22D3EE;
    background: #0d2137;
    box-shadow: inset 0 0 60px rgba(34,211,238,0.1);
}
.upload-zone-icon {
    font-size: 64px;
    color: #30363d;
    margin-bottom: 16px;
    transition: all 0.3s;
}
.upload-zone:hover .upload-zone-icon { color: #BB9C55; }
.upload-zone.drag-over .upload-zone-icon { color: #22D3EE; transform: scale(1.1); }
.upload-zone-title { color: #e6edf3; font-weight: 600; margin-bottom: 8px; }
.upload-zone-subtitle { color: #8b949e; margin-bottom: 16px; }
.upload-zone-formats { display: flex; gap: 8px; justify-content: center; flex-wrap: wrap; margin-bottom: 12px; }
.format-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
    border: 1px solid #30363d;
    color: #8b949e;
}
.format-image:hover { border-color: #22D3EE; color: #22D3EE; }
.format-video:hover { border-color: #3fb950; color: #3fb950; }
.format-audio:hover { border-color: #d29922; color: #d29922; }
.format-doc:hover { border-color: #8b949e; color: #e6edf3; }
.upload-zone-limit { color: #484f58; font-size: 12px; margin: 0; }

.upload-queue-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: #161b22;
    border: 1px solid #21262d;
    border-radius: 8px;
    margin-bottom: 8px;
    animation: slideIn 0.3s ease;
}
@keyframes slideIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.upload-queue-thumb {
    width: 48px;
    height: 48px;
    border-radius: 6px;
    object-fit: cover;
    flex-shrink: 0;
    background: #21262d;
}
.upload-queue-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    flex-shrink: 0;
    font-size: 20px;
}
.upload-queue-info { flex: 1; min-width: 0; }
.upload-queue-name {
    color: #e6edf3;
    font-weight: 500;
    font-size: 13px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.upload-queue-meta { color: #8b949e; font-size: 11px; margin-top: 2px; }
.upload-queue-progress {
    height: 4px;
    background: #21262d;
    border-radius: 2px;
    margin-top: 6px;
    overflow: hidden;
}
.upload-queue-bar {
    height: 100%;
    border-radius: 2px;
    transition: width 0.3s ease;
    background: linear-gradient(90deg, #BB9C55, #22D3EE);
}
.upload-queue-status {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 4px;
    white-space: nowrap;
    flex-shrink: 0;
}
.status-uploading { background: rgba(34,211,238,0.15); color: #22D3EE; }
.status-success { background: rgba(63,185,80,0.15); color: #3fb950; }
.status-error { background: rgba(248,81,73,0.15); color: #f85149; }

.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 12px;
    padding: 16px;
}
.media-card {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    background: #161b22;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.2s;
    aspect-ratio: 1;
}
.media-card:hover { border-color: #30363d; transform: translateY(-2px); }
.media-card.selected { border-color: #BB9C55; }
.media-card input[type="checkbox"] {
    position: absolute;
    top: 8px;
    left: 8px;
    z-index: 2;
    width: 18px;
    height: 18px;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s;
}
.media-card:hover input[type="checkbox"],
.media-card.selected input[type="checkbox"] { opacity: 1; }
.media-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.media-card-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 8px;
    background: linear-gradient(transparent, rgba(0,0,0,0.8));
    opacity: 0;
    transition: opacity 0.2s;
}
.media-card:hover .media-card-overlay { opacity: 1; }
.media-card-name {
    color: #e6edf3;
    font-size: 11px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.media-card-size { color: #8b949e; font-size: 10px; }
.media-card-type {
    position: absolute;
    top: 8px;
    right: 8px;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 10px;
    font-weight: 600;
    z-index: 1;
}
.type-imagem { background: rgba(34,211,238,0.9); color: #0d1117; }
.type-video { background: rgba(63,185,80,0.9); color: #0d1117; }
.type-audio { background: rgba(210,153,34,0.9); color: #0d1117; }
.type-documento { background: rgba(139,148,158,0.9); color: #0d1117; }
.media-card-icon {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    background: #21262d;
}
.media-card-actions {
    position: absolute;
    bottom: 8px;
    right: 8px;
    display: flex;
    gap: 4px;
    z-index: 2;
}
.media-card-actions .btn {
    width: 28px;
    height: 28px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    font-size: 11px;
    opacity: 0;
    transition: all 0.2s;
}
.media-card:hover .media-card-actions .btn { opacity: 1; }
.media-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 60px 20px;
    color: #484f58;
}
.pagination .page-link { background: #161b22; border-color: #30363d; color: #8b949e; }
.pagination .page-item.active .page-link { background: #BB9C55; border-color: #BB9C55; color: #fff; }
.pagination .page-link:hover { background: #21262d; color: #e6edf3; }
</style>
@stop

@section('js')
<script>
$(function() {
    let currentFilter = '';
    let currentPage = 1;
    let currentSearch = '';
    let currentMediaId = null;

    const uploadZone = $('#uploadZone');
    const fileInput = $('#fileInput');
    const uploadQueue = $('#uploadQueue');

    uploadZone.on('click', () => fileInput.click());
    uploadZone.on('dragover dragenter', e => { e.preventDefault(); uploadZone.addClass('drag-over'); });
    uploadZone.on('dragleave dragend drop', e => { e.preventDefault(); uploadZone.removeClass('drag-over'); });
    uploadZone.on('drop', e => { processFiles(e.originalEvent.dataTransfer.files); });
    fileInput.on('change', function() { processFiles(this.files); this.value = ''; });

    function processFiles(files) {
        const arr = Array.from(files);
        arr.forEach(file => {
            if (file.size > 209715200) {
                showToast('error', `${file.name} excede 200MB`);
                return;
            }
            startUpload(file);
        });
    }

    function getFileType(file) {
        if (file.type.startsWith('image/')) return 'imagem';
        if (file.type.startsWith('video/')) return 'video';
        if (file.type.startsWith('audio/')) return 'audio';
        return 'documento';
    }

    function getTypeIcon(tipo) {
        return { imagem: 'fa-image', video: 'fa-video', audio: 'fa-music', documento: 'fa-file' }[tipo] || 'fa-file';
    }

    function getTypeColor(tipo) {
        return { imagem: '#22D3EE', video: '#3fb950', audio: '#d29922', documento: '#8b949e' }[tipo] || '#8b949e';
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes/1024).toFixed(1) + ' KB';
        if (bytes < 1073741824) return (bytes/1048576).toFixed(1) + ' MB';
        return (bytes/1073741824).toFixed(2) + ' GB';
    }

    function formatSpeed(bytesPerSec) {
        if (bytesPerSec > 1048576) return (bytesPerSec/1048576).toFixed(1) + ' MB/s';
        return (bytesPerSec/1024).toFixed(0) + ' KB/s';
    }

    function formatTime(seconds) {
        if (seconds < 60) return Math.ceil(seconds) + 's';
        return Math.floor(seconds/60) + 'm ' + Math.ceil(seconds%60) + 's';
    }

    function createThumbPreview(file, tipo) {
        return new Promise(resolve => {
            if (tipo === 'imagem') {
                const reader = new FileReader();
                reader.onload = e => resolve(`<img src="${e.target.result}" class="upload-queue-thumb">`);
                reader.readAsDataURL(file);
            } else {
                const bg = getTypeColor(tipo);
                resolve(`<div class="upload-queue-icon" style="background:${bg}20;color:${bg}"><i class="fas ${getTypeIcon(tipo)}"></i></div>`);
            }
        });
    }

    async function startUpload(file) {
        const tipo = getFileType(file);
        const itemId = 'uq-' + Date.now() + '-' + Math.random().toString(36).substr(2,5);
        const thumb = await createThumbPreview(file, tipo);

        uploadQueue.show();
        uploadQueue.prepend(`
            <div class="upload-queue-item" id="${itemId}">
                ${thumb}
                <div class="upload-queue-info">
                    <div class="upload-queue-name">${file.name}</div>
                    <div class="upload-queue-meta">${formatSize(file.size)} &bull; ${tipo}</div>
                    <div class="upload-queue-progress"><div class="upload-queue-bar" id="${itemId}-bar" style="width:0%"></div></div>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted" id="${itemId}-pct">0%</small>
                        <small class="text-muted" id="${itemId}-spd"></small>
                    </div>
                </div>
                <span class="upload-queue-status status-uploading" id="${itemId}-status"><i class="fas fa-spinner fa-spin"></i></span>
            </div>
        `);

        const formData = new FormData();
        formData.append('arquivo', file);
        formData.append('_token', '{{ csrf_token() }}');

        let startTime = Date.now();
        let lastLoaded = 0;
        let lastTime = startTime;

        try {
            const response = await $.ajax({
                url: '{{ route("admin.uploads.upload") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr() {
                    const xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', e => {
                        if (e.lengthComputable) {
                            const pct = Math.round((e.loaded/e.total)*100);
                            const now = Date.now();
                            const speed = (e.loaded - lastLoaded) / ((now - lastTime)/1000 || 1);
                            const remaining = (e.total - e.loaded) / (speed || 1);

                            $(`#${itemId}-bar`).css('width', pct + '%');
                            $(`#${itemId}-pct`).text(pct + '%');
                            $(`#${itemId}-spd`).text(formatSpeed(speed) + ' • ' + formatTime(remaining));

                            lastLoaded = e.loaded;
                            lastTime = now;
                        }
                    });
                    return xhr;
                }
            });

            $(`#${itemId}-status`).removeClass('status-uploading').addClass('status-success').html('<i class="fas fa-check"></i>');
            $(`#${itemId}-bar`).css('width', '100%');
            $(`#${itemId}-spd`).text('Concluído');

            setTimeout(() => $(`#${itemId}`).fadeOut(300, function() { $(this).remove(); if (uploadQueue.children().length === 0) uploadQueue.hide(); }), 2000);

            showToast('success', `${file.name} enviado!`);
            loadMedia();
        } catch(err) {
            $(`#${itemId}-status`).removeClass('status-uploading').addClass('status-error').html('<i class="fas fa-times"></i>');
            $(`#${itemId}-bar`).css('width', '0%');
            $(`#${itemId}-spd`).text('Falhou');
            showToast('error', `Erro ao enviar ${file.name}`);
        }
    }

    function loadMedia(page) {
        page = page || 1;
        currentPage = page;
        $.get('{{ route("admin.uploads.list") }}', { page, tipo: currentFilter, busca: currentSearch }, function(data) {
            renderMedia(data.data);
            renderPagination(data);
            updateStats();
        });
    }

    function renderMedia(items) {
        const grid = $('#mediaGrid');
        grid.empty();

        if (!items || items.length === 0) {
            grid.html('<div class="media-empty"><i class="fas fa-folder-open fa-3x mb-3"></i><p>Nenhum arquivo encontrado</p></div>');
            return;
        }

        items.forEach(m => {
            const isImage = m.tipo === 'imagem';
            const isVideo = m.tipo === 'video';
            const isAudio = m.tipo === 'audio';

            let content = '';
            if (isImage) {
                content = `<img src="${m.url}" class="media-card-img" loading="lazy" alt="${m.nome_original}">`;
            } else {
                const icon = isVideo ? 'fa-play-circle' : isAudio ? 'fa-headphones' : 'fa-file';
                const color = getTypeColor(m.tipo);
                content = `<div class="media-card-icon" style="color:${color}"><i class="fas ${icon}"></i></div>`;
            }

            grid.append(`
                <div class="media-card" data-id="${m.id}" data-url="${m.url}" data-tipo="${m.tipo}" data-nome="${m.nome_original}" data-tamanho="${m.tamanho_formatado}">
                    <input type="checkbox" class="media-select" data-id="${m.id}">
                    <span class="media-card-type type-${m.tipo}">${m.tipo}</span>
                    ${content}
                    <div class="media-card-overlay">
                        <div class="media-card-name">${m.nome_original}</div>
                        <div class="media-card-size">${m.tamanho_formatado}</div>
                    </div>
                    <div class="media-card-actions">
                        <button class="btn btn-sm btn-outline-light btn-preview" title="Preview"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-info btn-copy" title="Copiar URL"><i class="fas fa-link"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-delete" title="Excluir"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `);
        });
    }

    function renderPagination(data) {
        const pag = $('#mediaPagination');
        pag.empty();
        if (data.last_page <= 1) return;

        let html = '<nav><ul class="pagination pagination-sm mb-0">';
        if (data.current_page > 1)
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page-1}">&laquo;</a></li>`;

        for (let i = 1; i <= data.last_page; i++) {
            if (i === 1 || i === data.last_page || Math.abs(i - data.current_page) <= 2) {
                html += `<li class="page-item ${i === data.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            } else if (Math.abs(i - data.current_page) === 3) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        if (data.current_page < data.last_page)
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page+1}">&raquo;</a></li>`;

        html += '</ul></nav>';
        pag.html(html);
    }

    function updateStats() {
        $.get('{{ route("admin.uploads.list") }}', { per_page: 1 }, function(data) {
            const total = data.total;
            $.get('{{ route("admin.uploads.list") }}', { tipo: 'imagem', per_page: 1 }, d => { $('#statImagens').text(d.total); });
            $.get('{{ route("admin.uploads.list") }}', { tipo: 'video', per_page: 1 }, d => { $('#statVideos').text(d.total); });
            $.get('{{ route("admin.uploads.list") }}', { tipo: 'audio', per_page: 1 }, d => { $('#statAudios').text(d.total); });
            $.get('{{ route("admin.uploads.list") }}', { tipo: 'documento', per_page: 1 }, d => { $('#statDocs').text(d.total); });
        });
    }

    function showToast(type, msg) {
        if (typeof toastr !== 'undefined') {
            toastr[type](msg);
        } else {
            alert(msg);
        }
    }

    function openPreview(id) {
        currentMediaId = id;
        const card = $(`.media-card[data-id="${id}"]`);
        const url = card.data('url');
        const tipo = card.data('tipo');
        const nome = card.data('nome');
        const tamanho = card.data('tamanho');

        $('#previewTitle').text(nome);
        $('#previewInfo').text(`${tipo.toUpperCase()} • ${tamanho}`);

        let html = '';
        if (tipo === 'imagem') {
            html = `<img src="${url}" style="max-height:70vh;max-width:100%;margin:auto;display:block">`;
        } else if (tipo === 'video') {
            html = `<video controls autoplay style="max-height:70vh;max-width:100%;margin:auto;display:block"><source src="${url}"></video>`;
        } else if (tipo === 'audio') {
            html = `<div class="p-5"><i class="fas fa-headphones fa-5x mb-4" style="color:#d29922"></i><audio controls autoplay style="width:80%"><source src="${url}"></audio></div>`;
        } else {
            html = `<div class="p-5"><i class="fas fa-file fa-5x mb-4" style="color:#8b949e"></i><p class="text-white">Pré-visualização não disponível</p><a href="${url}" class="btn btn-outline-info mt-2" target="_blank"><i class="fas fa-download"></i> Baixar</a></div>`;
        }

        $('#previewBody').html(html);
        $('#previewModal').modal('show');
    }

    function deleteMedia(id) {
        if (!confirm('Excluir este arquivo permanentemente?')) return;
        $.ajax({
            url: `/admin/uploads/${id}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success() {
                showToast('success', 'Arquivo excluído');
                $(`.media-card[data-id="${id}"]`).fadeOut(300, function() { $(this).remove(); });
                $('#previewModal').modal('hide');
                loadMedia(currentPage);
            },
            error() { showToast('error', 'Erro ao excluir'); }
        });
    }

    function deleteSelected() {
        const ids = [];
        $('.media-select:checked').each(function() { ids.push($(this).data('id')); });
        if (ids.length === 0) return;
        if (!confirm(`Excluir ${ids.length} arquivo(s) permanentemente?`)) return;

        $.ajax({
            url: '{{ route("admin.uploads.batch-destroy") }}',
            type: 'POST',
            data: { ids, _token: '{{ csrf_token() }}' },
            success() {
                showToast('success', `${ids.length} arquivo(s) excluído(s)`);
                loadMedia(currentPage);
                $('.media-select').prop('checked', false);
                updateSelectionCount();
            },
            error() { showToast('error', 'Erro ao excluir'); }
        });
    }

    function updateSelectionCount() {
        const count = $('.media-select:checked').length;
        $('#countSelecionados').text(count);
        $('#btnExcluirSelecionados').prop('disabled', count === 0);
    }

    $(document).on('click', '.filter-btn', function(e) {
        e.preventDefault();
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('tipo');
        loadMedia(1);
    });

    $(document).on('click', '.page-link', function(e) {
        e.preventDefault();
        loadMedia($(this).data('page'));
    });

    $('#btnBuscar').on('click', function() { currentSearch = $('#buscaMedia').val(); loadMedia(1); });
    $('#buscaMedia').on('keypress', function(e) { if (e.which === 13) { currentSearch = $(this).val(); loadMedia(1); } });

    $(document).on('click', '.media-card', function(e) {
        if ($(e.target).is('input') || $(e.target).closest('.btn').length) return;
        openPreview($(this).data('id'));
    });

    $(document).on('click', '.btn-preview', function(e) {
        e.stopPropagation();
        openPreview($(this).closest('.media-card').data('id'));
    });

    $(document).on('click', '.btn-copy', function(e) {
        e.stopPropagation();
        const url = $(this).closest('.media-card').data('url');
        navigator.clipboard.writeText(url).then(() => showToast('success', 'URL copiada!'));
    });

    $(document).on('click', '.btn-delete', function(e) {
        e.stopPropagation();
        deleteMedia($(this).closest('.media-card').data('id'));
    });

    $(document).on('change', '.media-select', function() { updateSelectionCount(); });

    $('#btnExcluirSelecionados').on('click', deleteSelected);

    $('#btnCopiarUrlModal').on('click', function() {
        const url = $(`.media-card[data-id="${currentMediaId}"]`).data('url');
        navigator.clipboard.writeText(url).then(() => showToast('success', 'URL copiada!'));
    });

    $('#btnExcluirModal').on('click', function() { deleteMedia(currentMediaId); });

    loadMedia(1);
});
</script>
@stop
