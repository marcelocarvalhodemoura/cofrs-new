<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="reportFilter">
  @csrf
  <div class="form-row">
      <div class="col-md-6 mb-4">
          <label for="periodo">Período <b class="error">*</b></label>
          <input type="text" class="form-control flatpickr flatpickr-input" id="periodo" name="periodo" placeholder="Selecione o período" required>
      </div>
      <div class="col-md-6 mb-3">
          <label for="convenio">Convênio <b class="error">*</b></label>
          <select class="custom-select" id="convenio" name="convenio" required>
              <option value="">-Selecione-</option>
              @foreach ($agreementList as $item)
                  <option value="{{ $item->id }}">{{ $item->con_nome }}</option>
              @endforeach
          </select>
      </div>
    </div>
    <div class="form-row">
        <div class="col-md-12 mb-3 text-right">
            <input type="hidden" name="typeReport" id="typeReport" value="agreement" />
            <button class="btn btn-primary mb-2" type="button" onClick="buscar()">Buscar</button>
        </div>
    </div>
</form>
