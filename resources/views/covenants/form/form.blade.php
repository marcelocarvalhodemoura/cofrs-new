<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formConvenants">
    @csrf
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="associate">Associado <b class="error">*</b></label>
            <select class="form-control" required id="associate" name="associate" onchange="carregaContrato()">
                <option value="">-Selecione-</option>
                @foreach ($associateList as $item)
                    <option value="{{ $item->id }}" data-contrato="{{ $item->assoc_contrato }}">{{ $item->assoc_nome }}</option>
                @endforeach
            </select>

        </div>
        <div class="col-md-4 mb-4">
            <label for="convenants">Convênio <b class="error">*</b></label>
            <select class="form-control" required id="convenants" name="convenants" style="z-index: 99999!important;" onchange="carregaContrato()">
                <option value="">-Selecione-</option>
                @foreach ($agreementList as $item)
                    <option value="{{ $item->id }}" data-referencia="{{ $item->con_referencia }}">{{ $item->con_nome }}</option>
                @endforeach
            </select>

        </div>

        <div class="col-md-4 mb-4">
            <label for="validationCustom03">Nº de Parcelas <b class="error">*</b></label>
            <input type="number" class="form-control" id="number" name="number" required>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="email">Valor da parcela <b class="error">*</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">R$</span>
                </div>
                <input type="money" class="form-control" id="portion" name="portion"  required>

            </div>
        </div>

        <div class="col-md-3 mb-4">
            <label for="validationCustom04">Valor Total <b class="error">*</b></label>
            <input type="text" class="form-control"  id="total" name="total" required>

        </div>
        <div class="col-md-1 mb-4">
            <div class="spinner-border text-primary align-self-center" id="loader1" style="margin-top: 35px;">Loading...</div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="validationCustom05">Parcela Inicial<b class="error">*</b></label>
            <input type="text" class="form-control"  id="firstPortion" name="firstPortion" placeholder="00/00/0000" required>
        </div>

    </div>
    <div class="form-row">
        <div class="col-md-12 mb-4">
            <label for="convenants">Contrato do convênio<b class="error">*</b></label>
            <input type="text" class="form-control" id="contract" name="contract" maxlength="200" autocomplete="off" required />
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
