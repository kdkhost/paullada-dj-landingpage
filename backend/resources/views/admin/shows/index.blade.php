@extends('admin.layouts.admin')

@section('page_title', 'Shows')

@section('header_buttons')
    <a href="{{ route('admin.shows.create') }}" class="btn btn-success btn-sm">
        <i class="fas fa-plus"></i> Novo Show
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
                    <th>Título</th>
                    <th>Data</th>
                    <th>Local</th>
                    <th>Cidade</th>
                    <th>Status</th>
                    <th>Destaque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($shows as $show)
                <tr>
                    <td>{{ $show->id }}</td>
                    <td>{{ $show->titulo }}</td>
                    <td>{{ $show->data_evento->format('d/m/Y') }}</td>
                    <td>{{ $show->local }}</td>
                    <td>{{ $show->cidade }}</td>
                    <td>
                        @if($show->status == 'active')
                            <span class="badge badge-success">Ativo</span>
                        @elseif($show->status == 'inactive')
                            <span class="badge badge-secondary">Inativo</span>
                        @else
                            <span class="badge badge-dark">Realizado</span>
                        @endif
                    </td>
                    <td>
                        @if($show->destaque)
                            <span class="badge badge-warning"><i class="fas fa-star"></i></span>
                        @else
                            <span class="badge badge-light">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.shows.edit', $show) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.shows.destroy', $show) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza?')">
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
            {{ $shows->links() }}
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
