@extends('layouts.app2')

@section('content')

    <div class="layout-px-spacing">
        <div class="row  layout-top-spacing">

            <nav class="breadcrumb-two" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active"><a href="/users">Usuários</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="">Listagem</a></li>
                </ol>
            </nav>

        </div>

        <div class="row layout-top-spacing">

            <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
                <div class="widget-content-area br-4">
                    <div class="widget-one">

                        <h5>Controle de usuários</h5>

                        <p class="">Página do Sistema Cofrs destinada ao gerenciamento de usuários.</p>
                        <br/>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                @include('user.modal.create')
                                @include('user.modal.edit')
                                @include('user.modal.password')
                                @include('user.modal.delete')
                            </div>

                            <div class="col-md-12">
                                <table id="usertable" class="table table-bordered table-hover table-striped table-checkable table-highlight-head mb-4"">
                                    <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Usuário</th>
                                        <th>E-mail</th>
                                        <th>Tipo</th>
                                        <th>Ação</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($userList as $item)

                                        <tr>
                                            <td>{{ $item->usr_nome }}</td>
                                            <td>{{ $item->usr_usuario }}</td>
                                            <td>{{ $item->usr_email }}</td>
                                            <td>
                                            @switch($item->tipusr_nome)
                                                @case('Administrador')
                                                    <span class="badge badge-danger">{{ $item->tipusr_nome }}</span>
                                                @break

                                                @case('Gestor')
                                                    <span class="badge badge-warning">{{ $item->tipusr_nome }}</span>
                                                @break

                                                @case('Operador')
                                                    <span class="badge badge-info">{{ $item->tipusr_nome }}</span>
                                                @break
                                            @endswitch
                                            </td>
                                            <td>sadsadsadsa</td>
                                        </tr>
                                    @endforeach
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
    <script src="{{ URL::asset('/assets/js/user/custom.js') }}"></script>
@endpush
