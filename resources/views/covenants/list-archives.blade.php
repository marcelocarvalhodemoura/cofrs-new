@extends('layouts.app2')

@section('content')

    <div class="layout-px-spacing">
        <div class="row  layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 ">
                <nav class="breadcrumb-two" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active"><a href="/processArchive">Processamento de arquivos</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="">Listagem</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content-area br-4">
                    <div class="widget-one">

                        <h5>Arquivos de baixa enviados</h5>

                        <p class="">Página do Sistema Cofrs destinada ao gerenciamento do processamento dos arquivos de retorno.</p>
                        <br/>
                        <div class="row">
                            <div class="col-md-12 text-right">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-hover" id="tableArchives">
                                    <thead>
                                        <th width="20%">Competência</th>
                                        <th width="5%">Convênio</th>
                                        <th width="5%">Tipo</th>
                                        <th width="15%">Responsável</th>
                                        <th width="15%">Enviado em</th>
                                        <th width="15%">Atualizado em</th>
                                        <th width="20%">Status</th>
                                        <th width="10%">Ações</th>
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
    <script src="{{ URL::asset('/assets/js/convenant/custom.js?v=1234223') }}"></script>
    <!-- Modal de detalhes -->
    <div class="modal fade" id="archiveDetailsModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do processamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="archiveDetailsContent">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Status</th>
                                    <th>Contrato</th>
                                    <th>Matrícula</th>
                                    <th>Referência</th>
                                    <th>Mensagem</th>
                                </tr>
                            </thead>
                            <tbody id="archiveDetailsTbody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endpush
