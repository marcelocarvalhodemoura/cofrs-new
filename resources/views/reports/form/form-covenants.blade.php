<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="reportFilter">
    @csrf
    <div class="form-row">
        <div class="col-md-3 mb-4">
            <label for="cpf">CPF <b class="error">*</b></label>
            <input type="text" class="form-control cpf" id="cpf" name="cpf" placeholder="CPF" required>
        </div>
        <div class="col-md-3 mb-4">
            <label for="periodo">Período <b class="error">*</b></label>
            <input type="text" class="form-control flatpickr flatpickr-input" id="periodo" name="periodo" placeholder="Selecione o período" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="convenio">Convênio</label>
            <select class="custom-select" id="convenio" name="convenio">
                <option value="">-Selecione-</option>
                @foreach ($agreementList as $item)
                    <option value="{{ $item->id }}">{{ $item->con_nome }}</option>
                @endforeach
            </select>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-6 mb-3">
            <label for="classificacao">Classificação</label>
            <select class="custom-select" id="classificacao" name="classificacao">
                <option value="">-Selecione-</option>
                @foreach ($classificationList as $item)
                    <option value="{{ $item->id }}">{{ $item->cla_nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label for="referencia">Referência</label>
            <select class="custom-select" id="referencia" name="referencia">
                <option value="">-Selecione-</option>
                @foreach ($referenceList as $item)
                    <option value="{{ $item->con_referencia }}">{{ $item->con_referencia }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label for="referencia">Status</label>
            <select class="custom-select" id="status" name="status">
                <option value="">-Selecione-</option>
                <option value="Pago">Pago</option>
                <option value="Pendente">Pendente</option>
                <option value="Vencido">Vencido</option>
            </select>
        </div>
      </div>
      <div class="form-row">
          <input type="hidden" name="typeReport" id="typeReport" value="covenant" />
          <button class="btn btn-primary mb-2" type="button" onClick="buscar()">Buscar</button>
      </div>
  </form>
  