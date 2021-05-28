@extends('layouts.app2')

@section('content')

    <div class="layout-px-spacing">
        <div class="row  layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 ">
                <nav class="breadcrumb-two" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active"><a href="/associates">Associados</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="">Listagem</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content-area br-4">
                    <div class="widget-one">

                        <h5>Controle de associados</h5>

                        <p class="">Página do Sistema Cofrs destinada ao gerenciamento de associados.</p>
                        <br />
                        <div class="row">
                            <div class="col-md-12 text-right">
                                @include('associate.modal.create')
                                @include('associate.modal.edit')
                                @include('associate.modal.covenants')
                                @include('associate.modal.dependent')
                                @include('associate.modal.delete')
                            </div>

                            <div class="col-md-12">
                                <table id="associatetable" class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4 dataTable no-footer">
                                <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Matrícula</th>
                                    <th>Fone</th>
                                    <th>Cidade</th>
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
    <script src="{{ URL::asset('/assets/js/associate/custom.js') }}"></script>
@endpush
