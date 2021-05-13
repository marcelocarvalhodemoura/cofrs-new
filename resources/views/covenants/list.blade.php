@extends('layouts.app2')

@section('content')

    <div class="layout-px-spacing">
        <div class="row  layout-top-spacing">

            <nav class="breadcrumb-two" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active"><a href="/covenants">Convênios</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="">Listagem</a></li>
                </ol>
            </nav>

        </div>

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content-area br-4">
                    <div class="widget-one">

                        <h5>Controle de convênios</h5>

                        <p class="">Página do Sistema Cofrs destinada ao gerenciamento de convênios do associados.</p>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
