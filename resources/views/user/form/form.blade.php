<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formUser">
    @csrf
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="name">Nome</label>
            <input type="text" class="form-control" id="name" placeholder="nome" name="name" required>
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="user">Usuário</label>
            <input type="text" class="form-control" id="user" name="user" placeholder="usuário" required>
            <div class="valid-feedback">
                Looks good!
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="email">E-mail</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                </div>
                <input type="text" class="form-control" id="email" name="email" placeholder="nome@provedor.com" required>
                <div class="invalid-feedback">
                    Please choose a username.
                </div>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="validationCustom03">Senha</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="****" required>
            <div class="invalid-feedback">
                Please provide a valid city.
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="validationCustom04">Conf. Senha</label>
            <input type="password" class="form-control" id="password2" name="password2" placeholder="****" required>
            <div class="invalid-feedback">
                Please provide a valid state.
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="validationCustom05">Tipo</label>
            <select class="custom-select" required="" id="usertype" name="usertype">
                <option value="">-Selecione-</option>
                @foreach ($userType as $item)
                    <option value="{{ $item->tipusr_codigoid }}">{{ $item->tipusr_nome }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback">
                Please provide a valid zip.
            </div>
        </div>
    </div>

</form>
