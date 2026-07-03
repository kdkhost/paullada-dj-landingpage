@extends('admin.layouts.admin')

@section('page_title', 'Links Sociais')

@section('header_buttons')
    <a href="{{ route('admin.social-links.create') }}" class="btn btn-success btn-sm">
        <i class="fas fa-plus"></i> Novo Link
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
                    <th>Nome</th>
                    <th>URL</th>
                    <th>Ícone</th>
                    <th>Ordem</th>
                    <th>Ativo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($links as $link)
                <tr>
                    <td>{{ $link->id }}</td>
                    <td>{{ $link->nome }}</td>
                    <td><a href="{{ $link->url }}" target="_blank">{{ Str::limit($link->url, 40) }}</a></td>
                    <td><i class="{{ $link->icone }}"></i></td>
                    <td>{{ $link->ordem }}</td>
                    <td>
                        @if($link->ativo)
                            <span class="badge badge-success">Sim</span>
                        @else
                            <span class="badge badge-secondary">Não</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.social-links.edit', $link) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.social-links.destroy', $link) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza?')">
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
            {{ $links->links() }}
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
