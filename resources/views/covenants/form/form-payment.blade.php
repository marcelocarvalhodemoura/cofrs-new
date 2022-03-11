<form class="text-left" id="formMonthlyPayment">
    @csrf
    <div class="form-row">
        <div class="col-md-12 mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="massive" id="massive1" value="associado">
                <label class="form-check-label" for="massive1">Associado</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="massive" id="massive2" value="convenio">
                <label class="form-check-label" for="massive2">Convênios</label>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div id="fileuploader"></div>
        </div>
    </div>
</form>
