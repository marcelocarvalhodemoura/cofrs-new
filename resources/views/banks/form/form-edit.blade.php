<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formItemEdit">
  @csrf
  <div class="form-row">
    <div class="col-md-12 mb-4">
      <label for="name_bank">Banco <b class="error">*</b></label>
      <input type="text" class="form-control" id="name_bank" name="name_bank" placeholder="Nome do Banco" value="" required>

    </div>
  </div>
  <div class="form-row">
    <div class="col-md-6 mb-4">
      <label for="febraban_code">Cod. Febraban <b class="error">*</b></label>
      <input type="text" class="form-control" id="febraban_code" name="febraban_code" placeholder="Cod. Febraban" value="">
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
    <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
  </div>
</form>