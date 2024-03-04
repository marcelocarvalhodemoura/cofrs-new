<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formEditParcelas">
    @csrf
    <input type="hidden" name="idparcelas" id="idparcelas" value="" />
    <input type="hidden" name="idLancamento" id="idLancamento" value="" />

    <div class="form-row">
        <div class="col-md-6 mb-4">
            <label for="email">Valor da parcela <b class="error">*</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">R$</span>
                </div>
                <input type="money" class="form-control" id="valor" name="valor"  required>
            </div>
        </div>
        <span class="form-text text-danger ms-1">As alterações só serão refletidas nas parcelas que estiverem pendentes, atrasadas ou transferidas.</span>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
