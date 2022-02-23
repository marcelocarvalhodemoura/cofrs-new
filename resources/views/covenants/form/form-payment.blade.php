<form class="text-left" id="formMonthlyPayment">
    <div class="form-row">
        <fieldset class="form-group mb-4">
            <div class="row">
                <label class="col-form-label col-xl-6 col-sm-6 col-sm-6 pt-0">Selecione o tipo de inclusão</label>
                <div class="col-xl-12 col-lg-12 col-sm-12">
                    <div class="form-check mb-2">
                        <div class="custom-control custom-radio classic-radio-info">
                            <input type="radio" id="massive1" name="massive[]" class="custom-control-input" value="associado">
                            <label class="custom-control-label" for="massive1"> Associado</label>
                        </div>
                    </div>
                    <div class="form-check mb-2">
                        <div class="custom-control custom-radio classic-radio-info">
                            <input type="radio" id="massive2" name="massive[]" class="custom-control-input" value="convenio">
                            <label class="custom-control-label" for="massive2">Convênios</label>
                        </div>
                    </div>

                </div>
            </div>
        </fieldset>

        <div class="col-md-6">
            <div class="custom-file-container" data-upload-id="mySecondImage">
                <label class="left">Upload de leitura de arquivo CSV <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                <label class="custom-file-container__custom-file" >

                    <input type="file" name="file" id="file" class="custom-file-container__custom-file__custom-file-input">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                    <span class="custom-file-container__custom-file__custom-file-control"></span>

                </label>
                <div class="custom-file-container__image-preview"></div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
        <button class="btn btn-primary" id="btnSavePayment" type="submit">Salvar</button>
    </div>
</form>
