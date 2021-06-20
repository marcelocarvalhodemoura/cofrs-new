<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formUser">
    @csrf
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="name">Nome <b class="error">*</b></label>
            <input type="text" class="form-control" id="name" name="name" value="" required>

        </div>
        <div class="col-md-4 mb-4">
            <label for="user">Usuário <b class="error">*</b></label>
            <input type="text" class="form-control" id="user" name="user" placeholder="usuário" value="" required>

        </div>
        <div class="col-md-4 mb-4">
            <label for="email">E-mail <b class="error">*</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                </div>
                <input type="email" class="form-control" id="email" name="email" placeholder="nome@provedor.com" required>

            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="validationCustom03">Senha <b class="error">*</b></label>
            <input type="password" class="form-control" id="password1" name="password1" placeholder="****" required>
        </div>
        <div class="col-md-4 mb-4">
            <label for="validationCustom04">Conf. Senha <b class="error">*</b></label>
            <input type="password" class="form-control"  id="password2" name="password2" placeholder="****" required>
        </div>
        <div class="col-md-4 mb-4">
            <label for="validationCustom05">Tipo <b class="error">*</b></label>
            <select class="custom-select" required="" id="usertype" name="usertype">
                <option value="">-Selecione-</option>
                @foreach ($userType as $item)
                    <option value="{{ $item->tipusr_codigoid }}">{{ $item->tipusr_nome }}</option>
                @endforeach
            </select>

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
