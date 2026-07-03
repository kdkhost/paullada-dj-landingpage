@extends('admin.layouts.admin')

@section('page_title', 'Galeria')

@section('header_buttons')
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-success btn-sm">
        <i class="fas fa-plus"></i> Novo Item
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
                    <th>Tipo</th>
                    <th>Título</th>
                    <th>Ordem</th>
                    <th>Ativo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>
                        @if($item->tipo == 'foto')
                            <span class="badge badge-info"><i class="fas fa-image"></i> Foto</span>
                        @else
                            <span class="badge badge-danger"><i class="fas fa-video"></i> Vídeo</span>
                        @endif
                    </td>
                    <td>{{ $item->titulo }}</td>
                    <td>{{ $item->ordem }}</td>
                    <td>
                        @if($item->ativo)
                            <span class="badge badge-success">Sim</span>
                        @else
                            <span class="badge badge-secondary">Não</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.gallery.edit', $item) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza?')">
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
            {{ $items->links() }}
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
