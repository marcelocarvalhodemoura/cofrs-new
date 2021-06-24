@extends('layouts.app2')

@section('content')

    <div class="layout-px-spacing">
        <div class="row  layout-top-spacing">

            <nav class="breadcrumb-two" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active"><a href="/covenants">Conveniado</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="">Listagem</a></li>
                </ol>
            </nav>

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
                                            <option value="{{ $assoc->assoc_codigoid }}">{{ $assoc->assoc_nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Competência</label>
                                    <select class="form-control  basic" id="selCompetition">
                                        <option value="">Selecione</option>
                                        @foreach( $competitionList as $com )
                                            <option value="{{ $com->com_codigoid }}">{{ $com->com_nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Convênio</label>
                                    <select class="form-control  basic" id="selAgreement" name="selAgreement">
                                        <option value="">Selecione</option>
                                        @foreach( $agreementList as $agree )
                                            <option value="{{ $agree->con_codigoid }}">{{ $agree->con_nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Estatus</label>
                                    <select class="form-control  basic"  id="selStatus" name="selStatus">
                                        <option value="">Selecione</option>
                                        <option value="Pago">Pago</option>
                                        <option value="Cancelado">Cancelado</option>
                                        <option value="Pendente">Pendente</option>
                                        <option value="Transferido">Transferido</option>
                                        <option value="Vencido">Vencido</option>
                                    </select>
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
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table mb-4 contextual-table">
                                        <thead>
                                        <tr class="">
                                            <th>Associado</th>
                                            <th>Parcela</th>
                                            <th>Convênio</th>
                                            <th>Referência</th>
                                            <th>Competência</th>
                                            <th>Valor</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th><input id="portionSel" value="1" type="checkbox"></th>
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

    </div>
@endsection

@push('scripts')
    <script src="{{ URL::asset('/assets/js/convenant/custom.js') }}"></script>
@endpush
