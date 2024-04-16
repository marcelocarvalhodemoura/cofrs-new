<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formEditParcelaObs">
    @csrf
    <input type="hidden" name="idParcela" id="idParcela" value="" />

    <div class="form-row">
      <div class="col-6 mb-4">
        <label for="validationCustom03">Vencimento</label>
        <input class="form-control" type="text" readonly id="par_vencimentoparcela">
      </div>
      <div class="col-6 mb-4">
        <label for="validationCustom03">Valor</label>
        <input class="form-control" type="text" readonly id="portionPrice">
      </div>
    </div>

    <div class="form-row">
      <div class="col-6 mb-4">
        <label for="validationCustom03">Parcela</label>
        <input class="form-control" type="text" readonly id="par_numero">
      </div>
      <div class="col-6 mb-4">
        <label for="validationCustom03">Parcela equivalente</label>
        <input class="form-control" type="text" readonly id="par_equivalente">
      </div>
    </div>

    <div class="form-row">
        <div class="col-12 mb-4">
          <label for="validationCustom03">Observações</label>
          <textarea id="par_observacao" name="par_observacao" class="form-control" rows="3"></textarea>
        </div>
    </div>
    
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
