<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="reportFilter">
  @csrf
  <div class="form-row">
      <div class="col-md-3 mb-3">
          <label for="uf">UF</label>
          <select class="custom-select" id="uf" name="uf">
              <option value="">-Selecione-</option>
              @foreach ($estados as $item)
                  <option value="{{ $item->assoc_uf }}">{{ $item->assoc_uf }}</option>
              @endforeach
          </select>
      </div>
      <div class="col-md-6 mb-3">
          <label for="cidade">Cidade (cadastros)</label>
          <select class="custom-select" id="cidade" name="cidade">
              <option value="">-Selecione-</option>
              @foreach ($cidades as $item)
                  <option value="{{ $item->assoc_cidade }}">{{ $item->assoc_cidade }}({{ $item->cont }})</option>
              @endforeach
          </select>
      </div>
    </div>
    <div class="form-row">
        <div class="col-md-2 mb-3">
            <label for="assoc_ativosn">Status do cadastro</label>
            <select name="assoc_ativosn" id="assoc_ativosn" class="custom-select">
                <option value="">-Selecione-</option>
                <option value="1">Ativo</option>
                <option value="2">Desativado</option>
            </select>
        </div>

        <div class="col-md-4 mb-3">
          <label for="classificacao">Classificação</label>
          <select class="custom-select" id="classificacao" name="classificacao">
              <option value="">-Selecione-</option>
              @foreach ($classificationList as $item)
                  <option value="{{ $item->id }}">{{ $item->cla_nome }}</option>
              @endforeach
          </select>
      </div>
      <div class="col-md-6 mb-3">
          <label for="referencia">Tipo</label>
          <select class="custom-select" id="referencia" name="referencia">
              <option value="">-Selecione-</option>
              @foreach ($tipo as $item)
                  <option value="{{ $item->id }}">{{ $item->tipassoc_nome }}</option>
              @endforeach
          </select>
      </div>
    </div>
    <div class="form-row">
        <div class="col-md-12 mb-3 text-right">
            <input type="hidden" name="typeReport" id="typeReport" value="allAssociate" />
            <button class="btn btn-primary mb-2" type="button" onClick="buscar()">Buscar</button>
        </div>
    </div>
</form>
