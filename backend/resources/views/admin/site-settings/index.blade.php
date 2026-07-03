@extends('admin.layouts.admin')

@section('page_title', 'Configurações do Site')

@section('header_buttons')
    <a href="{{ route('admin.site-settings.create') }}" class="btn btn-success btn-sm">
        <i class="fas fa-plus"></i> Nova Configuração
    </a>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-bordered table-striped datatable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Chave</th>
                    <th>Valor</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($settings as $setting)
                <tr>
                    <td>{{ $setting->id }}</td>
                    <td><code>{{ $setting->chave }}</code></td>
                    <td>{{ Str::limit($setting->valor, 60) }}</td>
                    <td>
                        @if($setting->tipo == 'text')
                            <span class="badge badge-info">Texto</span>
                        @elseif($setting->tipo == 'textarea')
                            <span class="badge badge-warning">Texto Longo</span>
                        @elseif($setting->tipo == 'image')
                            <span class="badge badge-success">Imagem</span>
                        @else
                            <span class="badge badge-secondary">Booleano</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.site-settings.edit', $setting) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.site-settings.destroy', $setting) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $settings->links() }}
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
    $(function () {
        $('.datatable').DataTable({
            paging: false,
            info: false,
            searching: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/Portuguese-Brasil.json'
            }
        });
    });
</script>
@endpush
