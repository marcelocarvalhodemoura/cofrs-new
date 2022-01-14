<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formItem">
    @csrf
    <div class="form-row">
        <div class="col-md-12 mb-4">
            <label for="id_conta">Conta bancária <b class="error">*</b></label>
            <select class="custom-select" required="" id="id_conta" name="id_conta" required>
                <option value="" selected disabled>-Selecione-</option>
                @foreach ($BankAccount as $item)
                <option value="{{ $item->id }}">{{ $item->name_bank }}/{{ $item->febraban_code }} - {{ $item->count }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-12 mb-4">
            <label for="id_estatus">Status <b class="error">*</b></label>
            <select class="custom-select" required="" id="id_estatus" name="id_estatus" required>
                <option value="" selected disabled>-Selecione-</option>
                @foreach ($Status as $item)
                <option value="{{ $item->id }}">{{ $item->est_nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-12 mb-4">
            <label for="descricao">Descrição <b class="error">*</b></label>
            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Descrição" value="" required>

        </div>
        <div class="col-md-4 mb-4">
            <label for="data_vencimento">Data de Vencimento <b class="error">*</b></label>
            <input type="text" class="form-control calendar" id="data_vencimento" name="data_vencimento" placeholder="Data de Vencimento" value="" required>

        </div>
        <div class="col-md-12 mb-4">
            <label for="valor">Valor <b class="error">*</b></label>
            <div class="input-group">
                <span class="input-group-text" id="basic-addon3">R$</span>
                <input type="text" class="form-control money" id="valor" name="valor" placeholder="Valor" value="" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>