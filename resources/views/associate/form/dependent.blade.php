<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formDependents">
    @csrf
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="Name">Nome <b>*</b></label>
            <input type="text" class="form-control" id="depName" name="depName" placeholder="" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Nome</b> é um campo obrigatório!
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="cpf">CPF <b>*</b></label>
            <input type="text" class="form-control" id="depCpf" name="depCpf" placeholder="999.999.999-99" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>CPF</b> é um campo obrigatório!
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="rg">Rg <b>*</b></label>
            <input type="text" class="form-control" id="depRg" name="depRg" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Rg</b> é um campo obrigatório!
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12 mb-4">
            <label for="description">Observação</label>
            <textarea name="depDescription" id="depDescription" class="form-control">

            </textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal">
            <i class="flaticon-cancel-12"></i>
            Limpar
        </button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
