<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formItemEdit">
  @csrf
  <div class="form-row">
    <div class="col-md-12 mb-4">
      <label for="counttype_nome">Tipo <b class="error">*</b></label>
      <input type="text" class="form-control" id="counttype_nome" name="counttype_nome" placeholder="Nome do tipo" value="" required>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
    <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
  </div>
</form>