<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formEditLancamento">
    @csrf
    <input type="hidden" name="idLancamento" id="idLancamento" value="" />

    <div class="form-row">
        <div class="col-12 mb-4">
            <label for="convenants">Convênio <b class="error">*</b></label>
            <select class="form-control" required id="convenants" name="convenants" style="z-index: 99999!important;">
                <option value="">-Selecione-</option>
                @foreach ($agreementList as $item)
                    <option value="{{ $item->id }}">{{ $item->con_nome }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="col-12 mb-4">
            <label for="convenants">Contrato do convênio</label>
            <input type="text" class="form-control" id="contract" name="contract" maxlength="30" />
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Cancelar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
