@extends('admin.layouts.admin')

@section('page_title', isset($show) ? 'Editar Show' : 'Novo Show')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ isset($show) ? 'Editar Show' : 'Novo Show' }}</h3>
    </div>
    <form action="{{ isset($show) ? route('admin.shows.update', $show) : route('admin.shows.store') }}" method="POST" enctype="multipart/form-data">
        <div class="card-body">
            @csrf
            @if(isset($show)) @method('PUT') @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="titulo">Título</label>
                        <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $show->titulo ?? '') }}" required>
                        @error('titulo') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="data_evento">Data do Evento</label>
                        <input type="date" name="data_evento" id="data_evento" class="form-control @error('data_evento') is-invalid @enderror" value="{{ old('data_evento', isset($show) ? $show->data_evento->format('Y-m-d') : '') }}" required>
                        @error('data_evento') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="hora_evento">Hora do Evento</label>
                        <input type="time" name="hora_evento" id="hora_evento" class="form-control @error('hora_evento') is-invalid @enderror" value="{{ old('hora_evento', $show->hora_evento ?? '') }}" required>
                        @error('hora_evento') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao" rows="4" class="form-control @error('descricao') is-invalid @enderror" required>{{ old('descricao', $show->descricao ?? '') }}</textarea>
                @error('descricao') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="local">Local</label>
                        <input type="text" name="local" id="local" class="form-control @error('local') is-invalid @enderror" value="{{ old('local', $show->local ?? '') }}" required>
                        @error('local') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="cidade">Cidade</label>
                        <input type="text" name="cidade" id="cidade" class="form-control @error('cidade') is-invalid @enderror" value="{{ old('cidade', $show->cidade ?? '') }}" required>
                        @error('cidade') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="preco_ingresso">Preço do Ingresso (R$)</label>
                        <input type="number" step="0.01" min="0" name="preco_ingresso" id="preco_ingresso" class="form-control @error('preco_ingresso') is-invalid @enderror" value="{{ old('preco_ingresso', $show->preco_ingresso ?? '') }}">
                        @error('preco_ingresso') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ingressos_disponiveis">Ingressos Disponíveis</label>
                        <input type="number" min="0" name="ingressos_disponiveis" id="ingressos_disponiveis" class="form-control @error('ingressos_disponiveis') is-invalid @enderror" value="{{ old('ingressos_disponiveis', $show->ingressos_disponiveis ?? '') }}">
                        @error('ingressos_disponiveis') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="link_ingresso">Link do Ingresso</label>
                        <input type="url" name="link_ingresso" id="link_ingresso" class="form-control @error('link_ingresso') is-invalid @enderror" value="{{ old('link_ingresso', $show->link_ingresso ?? '') }}">
                        @error('link_ingresso') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" {{ old('status', $show->status ?? '') == 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ old('status', $show->status ?? '') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                            <option value="done" {{ old('status', $show->status ?? '') == 'done' ? 'selected' : '' }}>Realizado</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="destaque" id="destaque" class="form-check-input" value="1" {{ old('destaque', $show->destaque ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="destaque">Destaque</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="foto_cartaz">Foto do Cartaz</label>
                        <input type="file" name="foto_cartaz" id="foto_cartaz" class="form-control-file @error('foto_cartaz') is-invalid @enderror">
                        @error('foto_cartaz') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        @if(isset($show) && $show->foto_cartaz)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $show->foto_cartaz) }}" alt="Cartaz" style="max-height: 100px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ isset($show) ? 'Atualizar' : 'Salvar' }}</button>
            <a href="{{ route('admin.shows.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@stop
