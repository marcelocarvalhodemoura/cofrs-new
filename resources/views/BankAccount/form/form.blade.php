<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formItem">
    @csrf
    <div class="form-row">
        <div class="col-md-12 mb-4">
            <label for="id_banco">Banco <b class="error">*</b></label>
            <select class="custom-select" required="" id="id_banco" name="id_banco" required>
                <option value="" selected disabled>-Selecione-</option>
                @foreach ($Banks as $item)
                <option value="{{ $item->id }}">{{ $item->name_bank }}/{{ $item->febraban_code }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12 mb-4">
            <label for="id_tipoconta">Tipo de conta <b class="error">*</b></label>
            <select class="custom-select" required="" id="id_tipoconta" name="id_tipoconta" required>
                <option value="" selected disabled>-Selecione-</option>
                @foreach ($AccountType as $item)
                <option value="{{ $item->id }}">{{ $item->counttype_nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12 mb-4">
            <label for="agencia">Agência <b class="error">*</b></label>
            <input type="text" class="form-control" id="agencia" name="agencia" placeholder="Agência" value="" required>

        </div>
        <div class="col-md-12 mb-4">
            <label for="conta">Conta <b class="error">*</b></label>
            <input type="text" class="form-control" id="conta" name="conta" placeholder="Conta" value="" required>

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>