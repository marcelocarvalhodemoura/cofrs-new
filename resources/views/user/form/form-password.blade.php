<form class="needs-validation text-left password" novalidate action="javascript:void(0);" id="formSavePassword">
    @csrf
    <input type="hidden" name="editUserID" id="editUserID" value=""/>
    <div class="form-row mb-4">
        <div class=" form-group col-md-6 ">
            <label for="name">Senha</label>
            <input type="password" class="form-control" id="editPassword" placeholder="inform a senha" name="editPassword" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Senha</b> é um campo obrigatório!
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <label for="confsenha">Conf. de Senha</label>
            <input type="password" class="form-control" id="editPassword" name="editPassword2" data-match="#password" data-match-error="Atenção! As senhas não estão iguais." placeholder="informe conf. de senha" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Conf. de Senha</b> é um campo obrigatório e deve ser igual a senha!
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
