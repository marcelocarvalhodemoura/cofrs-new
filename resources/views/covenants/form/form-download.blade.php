<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formConvenants">
    @csrf

    <fieldset class="form-group mb-4">
        <div class="row">
            <label class="col-form-label col-xl-2 col-sm-3 col-sm-2 pt-0">Selecione a  Instituição</label>
            <div class="col-xl-10 col-lg-9 col-sm-10">
                <div class="form-check mb-2">
                    <div class="custom-control custom-radio classic-radio-info">
                        <input type="radio" id="company1" name="company[]" class="custom-control-input" value="Ipe" checked>
                        <label class="custom-control-label" for="company1">Ipe</label>
                    </div>
                </div>
                <div class="form-check mb-2">
                    <div class="custom-control custom-radio classic-radio-info">
                        <input type="radio" id="company2" name="company[]" class="custom-control-input" value="Tesouro">
                        <label class="custom-control-label" for="company2">Tesouro do Estado</label>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <hr />
    <fieldset class="form-group mb-4">
        <div class="row">
            <label class="col-form-label col-xl-2 col-sm-3 col-sm-2 pt-0">Selecione o Tipo</label>
            <div class="col-md-3">
                <div class="form-group form-check pl-0">
                    <div class="custom-control custom-checkbox checkbox-info">
                        <input type="checkbox" class="custom-control-input" name="convenants[]" id="convenants1" checked>
                        <label class="custom-control-label" for="convenants1">Mensalidade</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group form-check pl-0">
                    <div class="custom-control custom-checkbox checkbox-info">
                        <input type="checkbox" class="custom-control-input" name="convenants[]" id="convenants2" checked>
                        <label class="custom-control-label" for="convenants2">Diversos</label>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group form-check pl-0">
                    <div class="custom-control custom-checkbox checkbox-info">
                        <input type="checkbox" class="custom-control-input" name="convenants[]" id="convenants3" checked>
                        <label class="custom-control-label" for="convenants3">Emprestimos</label>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
