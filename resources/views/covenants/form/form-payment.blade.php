<form class="text-left" id="formMonthlyPayment">
    <div class="form-row">
        <div class="col-md-12">
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
