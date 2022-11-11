@extends('layouts.app2')

@section('content')

<div class="layout-px-spacing">
    <div class="row  layout-top-spacing">
        <div class="col-xl-12 col-lg-12 col-md-12 col-12 ">
            <nav class="breadcrumb-two" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active"><a href="/users">Usuários</a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="">Perfil</a></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row layout-top-spacing">

        <div class="col-xl-12 col-lg-12 col-md-12 col-12 layout-spacing">
            <div class="widget-content-area br-4">
                <div class="widget-one">

                    <h5>Perfil de usuários</h5>

                    <p class="">Página do Sistema Cofrs destinada alteração do usuários logado.</p>
                    <br />
                    <div class="row">
                        <div class="modal-body">
                        <form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formUserEdit">
                            @csrf
                            <div class="form-row">
                                <div class="col-md-3 mb-4">
                                    <label for="name">Nome <b class="error">*</b></label>
                                    <input type="text" class="form-control" id="name" placeholder="nome" name="name" required>

                                </div>
                                <div class="col-md-3 mb-4">
                                    <label for="user">Usuário <b class="error">*</b></label>
                                    <input type="text" class="form-control" id="user" name="user" placeholder="usuário" required>

                                </div>
                                <div class="col-md-3 mb-4">
                                    <label for="email">E-mail <b class="error">*</b></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupPrepend">@</span>
                                        </div>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="nome@provedor.com" required>

                                    </div>
                                </div>
                                <div class="col-md-3 mb-4">
                                    <label for="validationCustom05">Tipo <b class="error">*</b></label>
                                    <select class="custom-select" required id="usertype" name="usertype">
                                        <option value="">-Selecione-</option>
                                        @foreach ($userType as $item)
                                            <option value="{{ $item->id }}">{{ $item->tipusr_nome }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
                                <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script src=" {{ URL::asset('/assets/js/user/profile.js') }}"></script>
@endpush
