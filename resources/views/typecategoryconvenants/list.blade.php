@extends('layouts.app2')

@section('content')
    <div class="layout-px-spacing">
        <div class="row  layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 ">
                <nav class="breadcrumb-two" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active"><a href="/convenants-type">Tipos de Convênio</a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="">Listagem</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content-area br-4">
                    <div class="widget-one">

                        <h5>Controle de Categoria do Tipo de Convênio</h5>

                        <p class="">Página do Sistema Cofrs destinada ao gerenciamento de Categoria do Tipo de Convênio.</p>
                        <br/>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                @include('typecategoryconvenants.modal.create')
{{--                                @include('user.modal.edit')--}}
{{--                                @include('user.modal.password')--}}
{{--                                @include('user.modal.delete')--}}
                            </div>

                            <div class="col-md-12">
                                <table id="typecategorytable" class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4">
                                <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Categoria</th>
                                    <th>Referência</th>
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
    <script src="{{ URL::asset('/assets/js/categories-type-convenants/custom.js') }}"></script>
@endpush

