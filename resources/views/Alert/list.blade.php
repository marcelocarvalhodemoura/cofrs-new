@extends('layouts.app2')

@section('content')

<div class="layout-px-spacing">
    <div class="row  layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 ">
            <nav class="breadcrumb-two" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active"><a href="/users">Alertas</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="">Lista</a></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content-area br-4">
                <div class="widget-one">

                    <h5>Alertas de sistema</h5>

                    <p class="">Página do Sistema Cofrs destinada a administração de alertas.</p>
                    <br />
                    <div class="row">
                        <div class="col-md-12 text-right">
                            @include('alert.modal.create')
                            @include('alert.modal.edit')
                        </div>

                        <div class="col-md-12">
                            <table id="listalert" class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                                    <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Autor</th>
                                        <th>Título</th>
                                        <th>Data</th>
                                        <th>Tipo</th>
                                        <th>Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script src=" {{ URL::asset('/assets/js/alert/custom.js') }}"></script>
@endpush
