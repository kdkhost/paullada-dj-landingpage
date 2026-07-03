@extends('admin.layouts.admin')

@section('page_title', isset($socialLink) ? 'Editar Link Social' : 'Novo Link Social')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ isset($socialLink) ? 'Editar Link Social' : 'Novo Link Social' }}</h3>
    </div>
    <form action="{{ isset($socialLink) ? route('admin.social-links.update', $socialLink) : route('admin.social-links.store') }}" method="POST">
        <div class="card-body">
            @csrf
            @if(isset($socialLink)) @method('PUT') @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" id="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome', $socialLink->nome ?? '') }}" required>
                        @error('nome') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="icone">Ícone (classe FontAwesome)</label>
                        <input type="text" name="icone" id="icone" class="form-control @error('icone') is-invalid @enderror" value="{{ old('icone', $socialLink->icone ?? '') }}" placeholder="fab fa-instagram" required>
                        @error('icone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="url">URL</label>
                <input type="url" name="url" id="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $socialLink->url ?? '') }}" required>
                @error('url') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ordem">Ordem</label>
                        <input type="number" min="0" name="ordem" id="ordem" class="form-control" value="{{ old('ordem', $socialLink->ordem ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="ativo" id="ativo" class="form-check-input" value="1" {{ old('ativo', $socialLink->ativo ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="ativo">Ativo</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ isset($socialLink) ? 'Atualizar' : 'Salvar' }}</button>
            <a href="{{ route('admin.social-links.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@stop
