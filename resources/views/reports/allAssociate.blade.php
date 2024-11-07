@extends('layouts.app2')

@section('content')

<div class="layout-px-spacing">
    <div class="row  layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 ">
            <nav class="breadcrumb-two" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active"><a href="">Relatórios</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="">Todos os associados</a></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content-area br-4">
                <div class="widget-one">
                    <h5>Relatório de Associados</h5>
                    <p class="">Página do Sistema Cofrs destinada a emissão do relatório de associados.<br />Selecione os filtro e pressione buscar para pré-visualizar o relatório.</p>
                    <br />
                    <div class="row">
                        <div class="col-md-12 text-right">
                            @include('reports.form.form-AllAssociate')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Resultado da busca</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <h4 class="h5"></h4>
                <table id="reporttable" class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                    <thead>
                        <tr>
                            <th>Associado</th>
                            <th>CPF</th>
                            <th>Data de nascimento</th>
                            <th>UF</th>
                            <th>Cidade</th>
                            <th>CEP</th>
                            <th>Endereço</th>
                            <th>Bairro</th>
                            <th>Status</th>
                            <th>Tipo</th>
                            <th>Classificacao</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection

@push('scripts')
    <script src=" {{ URL::asset('/assets/js/reports/custom.js') }}"></script>
@endpush
