<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formItemAdd">
  @csrf
  <div class="form-row">
    <div class="col-md-12 mb-4">
      <label for="titulo">Título <b class="error">*</b></label>
      <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título" value="" required>

    </div>
  </div>
  <div class="form-row">
    <div class="col-md-12 mb-4">
      <label for="texto">Texto <b class="error">*</b></label>
      <textarea id="texto" name="texto" class="form-control textarea" rows="4" placeholder="Texto" required></textarea>
    </div>
  </div>
   
  <div class="form-row">
    <div class="col-md-12 mb-4">
      <label for="autor">Autor</label>
      <input type="text" id="autor" name="autor" class="form-control" value="{{ Session::get('name') }}" disabled />
    </div>
  </div>
  <div class="form-row">
    <div class="col-md-12 mb-4">
      <label for="autor">Destinatários</label>
      <div id="usersCHK">
      @foreach( $userList as $assoc )
        <label class="new-control new-checkbox checkbox-primary d-block">
          <input type="checkbox" class="new-control-input" name="users[]" id="" value="{{ $assoc->id }}" />
          <span class="new-control-indicator"></span>{{ $assoc->usr_nome }}
        </label>
      @endforeach
      </div>
    </div>
  </div>



  <div class="modal-footer">
    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Limpar</button>
    <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
  </div>
</form>