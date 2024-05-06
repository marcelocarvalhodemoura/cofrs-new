@extends('layouts.app2')

@section('content')

    <div class="layout-px-spacing">
        <div class="row  layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 ">
                <nav class="breadcrumb-two" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active"><a href="/covenants">Conveniado</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="">Listagem</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content-area br-4">
                    <div class="widget-one">

                        <h5>Controle de Mensalista e Conveniados</h5>

                        <p class="">Página do Sistema Cofrs destinada ao gerenciamento de convênios do associados.</p>
                        <br/>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                @include('covenants.modal.create')
                                @include('covenants.modal.monthly-payment')
                                @include('covenants.modal.renegotiation')
                                @include('covenants.modal.installment-payment')
                                @include('covenants.modal.upload-file')
                                @include('covenants.modal.download-file')
                                @include('covenants.modal.delete')
                                @include('covenants.modal.editParcela')
                                @include('covenants.modal.editStatusParcela')
                            </div>
                        </div>
                        <form id="convenantsForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Associado</label>
                                    <select class="form-control  basic" id="selAssociate" name="selAssociate" >
                                        <option value="">Selecione</option>
                                        @foreach( $associateList as $assoc )
                                            <option value="{{ $assoc->id }}">{{ $assoc->assoc_nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Competência</label>
                                    <select class="form-control  basic" name="selCompetence" id="selCompetence">
                                        <option value="">Selecione</option>
                                        @foreach( $competitionList as $com )
                                            <option value="{{ $com->id }}">{{ $com->com_nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Convênio</label>
                                    <select class="form-control  basic" id="selAgreement" name="selAgreement">
                                        <option value="">Selecione</option>
                                        @foreach( $agreementList as $agree )
                                            <option value="{{ $agree->id }}">{{ $agree->con_nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Estatus</label>
                                    <select class="form-control  basic"  id="selStatus" name="selStatus">
                                        <option value="">Selecione</option>
                                        <?php
                                        foreach($statusList as $estatus){
                                            ?>
                                            <option value="{{ $estatus->est_nome }}">{{ $estatus->est_nome }}</option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label class="w-100">Pesquisa</label>
                                    <button type="button" class="btn btn-primary btn-lg" onclick="filtroConvenant()">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
    {{--                                @include('user.modal.create')--}}
    {{--                                @include('user.modal.edit')--}}
    {{--                                @include('user.modal.password')--}}
    {{--                                @include('user.modal.delete')--}}
                                </div>
                            </div>
                        </form>
                        <hr/>
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-hover" id="tableCovenants">
                                    <thead>
                                        <th width="20%">Associado</th>
                                        <th width="10%">CPF</th>
                                        <th width="15%">Convênios</th>
                                        <th width="15%">Contrato</th>
                                        <th width="10%">Vencimento</th>
                                        <th width="10%">Parcelas</th>
                                        <th width="5%">Total</th>
                                        <th width="5%">Editar</th>
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
    @include('covenants.modal.editLancamento')
    @include('covenants.modal.addParcela')
    @include('covenants.modal.editParcelaObs')
@endsection

@push('scripts')
    <script src="{{ URL::asset('/assets/js/convenant/custom.js?v=1234223') }}"></script>
    <script src="{{ URL::asset('/assets/js/jquery-maskmoney.js') }}"></script>
    <script src="{{ URL::asset('plugins/apex/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/apex/custom-apexcharts.js') }}"></script>
@endpush
