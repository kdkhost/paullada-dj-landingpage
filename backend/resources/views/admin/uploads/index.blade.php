@extends('adminlte::page')

@section('title', 'Upload de Arquivos')

@section('content_header')
    <h1><i class="fas fa-cloud-upload-alt text-primary"></i> Upload de Arquivos</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Arraste e solte ou clique para enviar</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="upload-zone" id="uploadZone">
                    <div class="upload-zone-content">
                        <i class="fas fa-cloud-upload-alt fa-4x text-muted mb-3"></i>
                        <h4>Arraste arquivos aqui</h4>
                        <p class="text-muted">ou clique para selecionar</p>
                        <p class="text-muted small">
                            <i class="fas fa-image"></i> Imagens (JPG, PNG, GIF, WEBP) |
                            <i class="fas fa-video"></i> Vídeos (MP4, WEBM) |
                            <i class="fas fa-music"></i> Músicas (MP3, WAV, OGG)
                        </p>
                        <p class="text-muted small">Tamanho máximo: 200MB por arquivo</p>
                        <input type="file" id="fileInput" accept="image/*,video/*,audio/*" multiple style="display:none">
                    </div>
                </div>

                <div id="uploadProgress" style="display:none;" class="mt-3"></div>
            </div>
        </div>

        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title">Arquivos Enviados</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Tamanho</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="arquivosEnviados">
                        <tr id="semArquivos">
                            <td colspan="6" class="text-center text-muted">Nenhum arquivo enviado ainda</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Informações</h3>
            </div>
            <div class="card-body">
                <div class="info-box bg-info">
                    <span class="info-box-icon"><i class="fas fa-image"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Imagens</span>
                        <span class="info-box-number" id="countImagens">0</span>
                    </div>
                </div>
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-video"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Vídeos</span>
                        <span class="info-box-number" id="countVideos">0</span>
                    </div>
                </div>
                <div class="info-box bg-warning">
                    <span class="info-box-icon"><i class="fas fa-music"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Músicas</span>
                        <span class="info-box-number" id="countMusicas">0</span>
                    </div>
                </div>
                <hr>
                <p class="text-muted small mb-0">
                    <i class="fas fa-database"></i> Espaço utilizado: <span id="espacoUtilizado">0 MB</span>
                </p>
            </div>
        </div>

        <div class="card card-dark card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-cog"></i> Atalhos</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.gallery.create') }}" class="btn btn-app bg-success">
                    <i class="fas fa-plus-circle"></i> Add à Galeria
                </a>
                <a href="{{ route('admin.gallery.index') }}" class="btn btn-app bg-primary">
                    <i class="fas fa-images"></i> Galeria
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <h5 class="modal-title text-white" id="previewTitle"></h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center" id="previewBody">
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.upload-zone {
    border: 3px dashed #6c757d;
    border-radius: 15px;
    padding: 50px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #1a1a2e;
}
.upload-zone:hover, .upload-zone.drag-over {
    border-color: #00d2ff;
    background: #16213e;
    transform: scale(1.01);
}
.upload-zone.drag-over {
    border-color: #00d2ff;
    box-shadow: 0 0 30px rgba(0, 210, 255, 0.3);
}
.upload-progress-item {
    background: #1a1a2e;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 12px;
    border-left: 4px solid #00d2ff;
    transition: all 0.3s;
}
.upload-progress-item .progress {
    height: 6px;
    background: #2a2a4e;
    border-radius: 3px;
}
.upload-progress-item .progress-bar {
    background: linear-gradient(90deg, #00d2ff, #9b59b6);
    border-radius: 3px;
    transition: width 0.3s ease;
}
.upload-preview-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.2s;
}
.upload-preview-thumb:hover {
    transform: scale(1.1);
}
.upload-preview-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #2a2a4e;
    border-radius: 8px;
    font-size: 24px;
    cursor: pointer;
}
.upload-status {
    font-size: 12px;
    padding: 3px 8px;
    border-radius: 4px;
}
.upload-status.success { background: #28a74520; color: #28a745; }
.upload-status.error { background: #dc354520; color: #dc3545; }
.upload-status.uploading { background: #00d2ff20; color: #00d2ff; }
.time-remaining {
    font-size: 11px;
    color: #6c757d;
}
</style>
@stop

@section('js')
<script>
$(function() {
    let arquivosEnviados = [];
    let uploadsAtivos = 0;

    const uploadZone = $('#uploadZone');
    const fileInput = $('#fileInput');
    const uploadProgress = $('#uploadProgress');

    uploadZone.on('click', function() { fileInput.click(); });

    uploadZone.on('dragover dragenter', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadZone.addClass('drag-over');
    });

    uploadZone.on('dragleave dragend', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadZone.removeClass('drag-over');
    });

    uploadZone.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadZone.removeClass('drag-over');
        const files = e.originalEvent.dataTransfer.files;
        processFiles(files);
    });

    fileInput.on('change', function() {
        processFiles(this.files);
        this.value = '';
    });

    function processFiles(files) {
        for (let file of files) {
            if (file.size > 209715200) {
                toastr.error(`Arquivo muito grande: ${file.name} (máx. 200MB)`);
                continue;
            }
            startUpload(file);
        }
    }

    function getFileType(file) {
        if (file.type.startsWith('image/')) return 'imagem';
        if (file.type.startsWith('video/')) return 'video';
        if (file.type.startsWith('audio/')) return 'musica';
        return null;
    }

    function getFileIcon(tipo) {
        return {
            'imagem': '<i class="fas fa-image text-info"></i>',
            'video': '<i class="fas fa-video text-success"></i>',
            'musica': '<i class="fas fa-music text-warning"></i>',
        }[tipo] || '<i class="fas fa-file text-muted"></i>';
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function formatTime(seconds) {
        if (seconds < 60) return Math.ceil(seconds) + 's';
        const m = Math.floor(seconds / 60);
        const s = Math.ceil(seconds % 60);
        return `${m}m ${s}s`;
    }

    function createPreview(file, tipo) {
        return new Promise((resolve) => {
            if (tipo === 'imagem') {
                const reader = new FileReader();
                reader.onload = function(e) {
                    resolve(`<img src="${e.target.result}" class="upload-preview-thumb" onclick="previewFile('${e.target.result}', 'imagem')">`);
                };
                reader.readAsDataURL(file);
            } else if (tipo === 'video') {
                const url = URL.createObjectURL(file);
                resolve(`<div class="upload-preview-icon text-success" onclick="previewFile('${url}', 'video')"><i class="fas fa-play-circle fa-2x"></i></div>`);
            } else if (tipo === 'musica') {
                const url = URL.createObjectURL(file);
                resolve(`<div class="upload-preview-icon text-warning" onclick="previewFile('${url}', 'musica')"><i class="fas fa-headphones fa-2x"></i></div>`);
            }
        });
    }

    async function startUpload(file) {
        const tipo = getFileType(file);
        if (!tipo) {
            toastr.error(`Tipo de arquivo não suportado: ${file.name}`);
            return;
        }

        uploadsAtivos++;
        $('#semArquivos').hide();
        uploadProgress.show();

        const previewHtml = await createPreview(file, tipo);
        const itemId = 'upload-' + Date.now();

        const itemHtml = `
            <div class="upload-progress-item" id="${itemId}">
                <div class="d-flex align-items-center mb-2">
                    ${previewHtml}
                    <div class="ml-3 flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong class="text-white text-truncate" style="max-width:300px">${file.name}</strong>
                            <span class="upload-status uploading">Enviando...</span>
                        </div>
                        <div class="d-flex justify-content-between small text-muted mt-1">
                            <span>${formatSize(file.size)}</span>
                            <span class="time-remaining" id="${itemId}-time">calculando...</span>
                        </div>
                    </div>
                </div>
                <div class="progress">
                    <div class="progress-bar" id="${itemId}-bar" style="width:0%"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <span class="small text-muted" id="${itemId}-percent">0%</span>
                    <span class="small text-muted" id="${itemId}-speed"></span>
                </div>
            </div>
        `;

        uploadProgress.prepend(itemHtml);

        const formData = new FormData();
        formData.append('arquivo', file);
        formData.append('tipo', tipo);
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
                xhr: function() {
                    const xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percent = Math.round((e.loaded / e.total) * 100);
                            const now = Date.now();
                            const elapsed = (now - startTime) / 1000;
                            const loadDiff = e.loaded - (lastLoaded || 0);
                            const timeDiff = (now - lastTime) / 1000;
                            const speed = loadDiff / (timeDiff || 1) / 1024;
                            const remaining = ((e.total - e.loaded) / (speed * 1024 || 1));
                            const speedStr = speed > 1024 ? (speed / 1024).toFixed(1) + ' MB/s' : speed.toFixed(1) + ' KB/s';

                            $(`#${itemId}-bar`).css('width', percent + '%');
                            $(`#${itemId}-percent`).text(percent + '%');
                            $(`#${itemId}-speed`).text(speedStr);
                            $(`#${itemId}-time`).text(remaining > 0 ? formatTime(remaining) : 'finalizando...');

                            lastLoaded = e.loaded;
                            lastTime = now;
                        }
                    }, false);
                    return xhr;
                }
            });

            $(`#${itemId} .upload-status`).removeClass('uploading').addClass('success').text('Concluído');
            $(`#${itemId}-bar`).css('width', '100%');
            $(`#${itemId}-time`).text('Concluído');

            adicionarTabela(response, previewHtml, file);
            atualizarContadores();

            toastr.success(`${file.name} enviado com sucesso!`);
        } catch (error) {
            $(`#${itemId} .upload-status`).removeClass('uploading').addClass('error').text('Erro');
            $(`#${itemId}-bar`).css('width', '0%');
            $(`#${itemId}-time`).text('Falhou');
            toastr.error(`Erro ao enviar ${file.name}`);
        } finally {
            uploadsAtivos--;
            if (uploadsAtivos === 0) {
                setTimeout(function() {
                    uploadProgress.find('.upload-progress-item').fadeOut(300, function() {
                        $(this).remove();
                        if (uploadProgress.children().length === 0) uploadProgress.hide();
                    });
                }, 3000);
            }
        }
    }

    function adicionarTabela(response, previewHtml, file) {
        const tipoLabel = { 'imagem': 'Imagem', 'video': 'Vídeo', 'musica': 'Música' };
        const data = new Date().toLocaleDateString('pt-BR');
        const url = response.url;

        const row = `
            <tr data-url="${url}" data-tipo="${response.tipo}">
                <td>${previewHtml}</td>
                <td class="text-white">${response.nome}</td>
                <td><span class="badge badge-${response.tipo === 'imagem' ? 'info' : response.tipo === 'video' ? 'success' : 'warning'}">${tipoLabel[response.tipo]}</span></td>
                <td class="text-muted">${formatSize(response.tamanho)}</td>
                <td class="text-muted">${data}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="previewFile('${url}', '${response.tipo}')" title="Preview">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-secondary" onclick="copiarURL('${url}')" title="Copiar URL">
                        <i class="fas fa-link"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#arquivosEnviados').append(row);
    }

    function atualizarContadores() {
        $('.info-box-number').text('...');
    }

    window.previewFile = function(url, tipo) {
        const previewModal = $('#previewModal');
        const previewBody = $('#previewBody');
        const previewTitle = $('#previewTitle');

        previewTitle.text('Preview');

        if (tipo === 'imagem') {
            previewBody.html(`<img src="${url}" class="img-fluid" style="max-height:70vh">`);
        } else if (tipo === 'video') {
            previewBody.html(`
                <video controls class="w-100" style="max-height:70vh">
                    <source src="${url}" type="video/mp4">
                    Seu navegador não suporta vídeo.
                </video>
            `);
        } else if (tipo === 'musica') {
            previewBody.html(`
                <div class="py-5">
                    <i class="fas fa-headphones fa-5x text-warning mb-4"></i>
                    <audio controls class="w-75">
                        <source src="${url}" type="audio/mpeg">
                        Seu navegador não suporta áudio.
                    </audio>
                </div>
            `);
        }

        previewModal.modal('show');
    };

    window.copiarURL = function(url) {
        navigator.clipboard.writeText(url).then(function() {
            toastr.success('URL copiada!');
        });
    };
});
</script>
@stop
