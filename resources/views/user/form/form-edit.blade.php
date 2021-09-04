<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formUserEdit">
    @csrf
    <div class="form-row">
        <div class="col-md-3 mb-4">
            <label for="name">Nome <b class="error">*</b></label>
            <input type="text" class="form-control" id="name" placeholder="nome" name="name" required>

        </div>
        <div class="col-md-3 mb-4">
            <label for="user">Usuário <b class="error">*</b></label>
            <input type="text" class="form-control" id="user" name="user" placeholder="usuário" required>

        </div>
        <div class="col-md-3 mb-4">
            <label for="email">E-mail <b class="error">*</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                </div>
                <input type="email" class="form-control" id="email" name="email" placeholder="nome@provedor.com" required>

            </div>
        </div>
        <div class="col-md-3 mb-4">
            <label for="validationCustom05">Tipo <b class="error">*</b></label>
            <select class="custom-select" required id="usertype" name="usertype">
                <option value="">-Selecione-</option>
                @foreach ($userType as $item)
                    <option value="{{ $item->id }}">{{ $item->tipusr_nome }}</option>
                @endforeach
            </select>

        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
