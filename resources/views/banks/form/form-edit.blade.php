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
      <label for="bank_agency">Agência <b class="error">*</b></label>
      <input type="text" class="form-control" id="bank_agency" name="bank_agency" placeholder="Agência" value="" required>
    </div>
    <div class="col-md-6 mb-4">
      <label for="bank_account">Número da conta <b class="error">*</b></label>
      <input type="text" class="form-control" id="bank_account" name="bank_account" placeholder="Número da conta" value="" required>
    </div>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
    <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
  </div>
</form>