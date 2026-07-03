@extends('admin.layouts.admin')

@section('page_title', isset($siteSetting) ? 'Editar Configuração' : 'Nova Configuração')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ isset($siteSetting) ? 'Editar Configuração' : 'Nova Configuração' }}</h3>
    </div>
    <form action="{{ isset($siteSetting) ? route('admin.site-settings.update', $siteSetting) : route('admin.site-settings.store') }}" method="POST">
        <div class="card-body">
            @csrf
            @if(isset($siteSetting)) @method('PUT') @endif

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="chave">Chave</label>
                        <input type="text" name="chave" id="chave" class="form-control @error('chave') is-invalid @enderror" value="{{ old('chave', $siteSetting->chave ?? '') }}" required>
                        @error('chave') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <select name="tipo" id="tipo" class="form-control" required>
                            <option value="text" {{ old('tipo', $siteSetting->tipo ?? '') == 'text' ? 'selected' : '' }}>Texto</option>
                            <option value="textarea" {{ old('tipo', $siteSetting->tipo ?? '') == 'textarea' ? 'selected' : '' }}>Texto Longo</option>
                            <option value="image" {{ old('tipo', $siteSetting->tipo ?? '') == 'image' ? 'selected' : '' }}>Imagem</option>
                            <option value="boolean" {{ old('tipo', $siteSetting->tipo ?? '') == 'boolean' ? 'selected' : '' }}>Booleano</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="valor">Valor</label>
                @if(isset($siteSetting) && $siteSetting->tipo == 'textarea')
                    <textarea name="valor" id="valor" rows="4" class="form-control">{{ old('valor', $siteSetting->valor ?? '') }}</textarea>
                @else
                    <input type="text" name="valor" id="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor', $siteSetting->valor ?? '') }}">
                @endif
                @error('valor') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">{{ isset($siteSetting) ? 'Atualizar' : 'Salvar' }}</button>
            <a href="{{ route('admin.site-settings.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@stop
