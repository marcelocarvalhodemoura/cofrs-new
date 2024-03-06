<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formEditStatusParcelas">
    @csrf
    <input type="hidden" name="idparcelas" id="idparcelas" value="" />

    <div class="form-row">
        <div class="col-12 mb-4">
            <label for="statusParc">Status <b class="error">*</b></label>
            <select class="form-control" id="statusParc" name="statusParc" style="z-index: 99999!important;">
                <option value="" selected disabled>Selecione</option>
                <option value="Pago">Pago</option>
                <option value="Cancelado">Cancelado</option>
                <option value="Pendente">Pendente</option>
                <option value="Transferido">Transferido</option>
                <option value="Vencido">Vencido</option>
            </select>
        </div>
        <span class="form-text text-danger ms-1">As parcelas selecionadas serão alteradas independente do status atual.</span>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
