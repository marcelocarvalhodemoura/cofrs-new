<form class="needs-validation text-left " action="javascript:void(0);" id="formCategoryConvenant" novalidate="novalidate">
    @csrf
    <div class="form-row">
        <div class="col-md-12 mb-6">
            <label for="name">Nome <b class="error">*</b></label>
            <input type="text" class="form-control" id="name" name="name" value="" required>
        </div>
    </div>
    <br/>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal">
            <i class="flaticon-cancel-12"></i>
            Limpar
        </button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
