<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formEditStatusParcelas">
    @csrf
    <input type="hidden" name="idparcelas" id="idparcelas" value="" />

    <div class="form-row">
        <div class="col-12 mb-4">
            <label for="statusParc">Status <b class="error">*</b></label>
            <select class="form-control" id="statusParc" name="statusParc" style="z-index: 99999!important;">
                <option value="" selected disabled>Selecione</option>
                <?php
                foreach($statusList as $estatus){
                    ?>
                    <option value="{{ $estatus->est_nome }}">{{ $estatus->est_nome }}</option>
                    <?php
                }
                ?>
            </select>
        </div>
        <span class="form-text text-danger ms-1">As parcelas selecionadas serão alteradas independente do status atual.</span>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
