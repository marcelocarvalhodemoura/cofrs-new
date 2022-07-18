@extends('layouts.app2')

@section('content')

<div class="layout-px-spacing">
    <div class="row  layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 ">
            <nav class="breadcrumb-two" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active"><a href="">Relatórios</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="">Associados</a></li>
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
                            @include('reports.form.form-associate')
                        </div>

                        <div class="col-md-12">
                            <table id="reporttable" class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4"">
                                <thead>
                                    <tr>
                                        <th>Convênio</th>
                                        <th>Classificação</th>
                                        <th>Vencimento</th>
                                        <th>Parcela</th>
                                        <th>Equivalência</th>
                                        <th>Quantidade</th>
                                        <th>Contrato</th>
                                        <th>Valor</th>
                                        <th>Status de pagamento</th>
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
    <script src=" {{ URL::asset('/assets/js/reports/custom.js') }}"></script>
@endpush
