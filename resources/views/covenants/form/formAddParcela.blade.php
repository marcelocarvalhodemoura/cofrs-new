<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formAddParcela">
    @csrf
    <input type="hidden" name="idLancamento" id="idLancamento" value="" />

    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="validationCustom03">Nº de Parcelas <b class="error">*</b></label>
            <input type="number" class="form-control" id="number" name="number" required>
        </div>
    
        <div class="col-md-4 mb-4">
            <label for="validationCustom05">Parcela Inicial<b class="error">*</b></label>
            <input type="text" class="form-control"  id="firstPortion" name="firstPortion" placeholder="00/00/0000" required>
        </div>

        <div class="col-md-6 mb-4">
            <label for="email">Valor da parcela <b class="error">*</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">R$</span>
                </div>
                <input type="money" class="form-control" id="valor" name="valor"  required>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
