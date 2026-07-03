@extends('adminlte::page')

@section('title', config('app.name', 'Paullada DJ'))

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>@yield('page_title', 'Painel Administrativo')</h1>
        @yield('header_buttons')
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/paullada-admin.css">
    @stack('styles')
@stop

@section('js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>
    @stack('scripts')
@stop
