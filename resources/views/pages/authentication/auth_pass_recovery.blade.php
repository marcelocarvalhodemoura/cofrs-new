@extends('layouts.app')

@section('content')

    <div class="form-container outer">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content" style="display:none;">
                        <img src="assets/images/logooficial.png" width="30%" />
                        <h1 class="">Sistema COFRS</h1>
                        <p class="">Sistema exclusivo de funcionários do COFRS.</p>

                        <form class="text-left" id="formRecover" class="needs-validation">
                            <div class="form">
                                @csrf
                                <div id="username-field" class="field-wrapper input">
                                    <label for="username">USUÁRIO</label>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <input id="username" name="username" type="text" class="form-control" placeholder="Usuário" tabindex="0" />
                                </div>

                                <div class="d-sm-flex justify-content-between">
                                    <div class="d-block">
                                        <button type="submit" class="btn btn-primary" tabindex="0">Recuperar</button>
                                    </div>
                                    <div class="d-block">
                                        <a href="/" class="btn btn-outline-primary">< Voltar ao login</a>
                                    </div>
                                </div>


                                <p class="signup-link">Não esta registrado? Entre em contato com administrador do sistema.</p>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
