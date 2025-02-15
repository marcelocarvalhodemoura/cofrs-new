<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formTypeCategory">
    @csrf
    <div class="form-row">
        <div class="col-md-12 mb-6">
            <label for="name">Nome <b class="error">*</b></label>
            <input type="text" class="form-control" id="name" name="name" value="" required>

        </div>
    </div>
    <br/>
    <div class="form-row">
        <div class="col-md-6 mb-6">
            <label for="typeCategory">Tipo <b class="error">*</b></label>
            <select class="custom-select" required="" id="typeCategory" name="typeCategory">
                <option value="">-Selecione-</option>
                @foreach ($categoryConvenants as $item)
                    <option value="{{ $item->id }}">{{ $item->tipconv_nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-6">
            <label for="reference">Referência <b class="error">*</b></label>
            <select class="custom-select" required="" id="reference" name="reference">
                <option value="">-Selecione-</option>
                <option value="DIVERSOS">Diversos</option>
                <option value="EMPRESTIMO">Empréstimos</option>
                <option value="MENSALIDADE">Mensalidade</option>
                <option value="SEG MARITIMA">Seg Marítima</option>
            </select>
        </div>
      </div>
    <br/>
    <div class="form-row">
        <div class="col-md-6 mb-6">
            <label for="con_despesa_canal">Despesa Canal <b class="error">*</b></label>
            <input type="text" class="form-control" id="con_despesa_canal" name="con_despesa_canal" required>
        </div>
        <div class="col-md-6 mb-6">
            <label for="con_comissao_cofrs">Comissão COFRS <b class="error">*</b></label>
            <input type="text" class="form-control" id="con_comissao_cofrs" name="con_comissao_cofrs" required>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
