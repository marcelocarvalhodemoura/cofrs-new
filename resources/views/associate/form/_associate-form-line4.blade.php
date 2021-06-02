<div class="form-row">
    <div class="col-md-4 mb-4">
        <label for="email">E-mail <b>*</b></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupPrepend">@</span>
            </div>
            <input type="email" class="form-control" id="email" name="email" placeholder="nome@provedor.com" required="">
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>E-mail</b> é um campo obrigatório!
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <label for="classification">Classificação <b>*</b></label>
        <select aria-invalid="classification" name="classification" class="form-control" id="classification" required>

        </select>
        <div class="valid-feedback">

        </div>
        <div class="invalid-feedback">
            <b>Classificação</b> é um campo obrigatório!
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <label for="civilstatus">Estado Cívil <b>*</b></label>
        <select name="civilstatus" id="civilstatus" class="form-control" required>
            <option value="">-Selecione-</option>
            <option value="Casado">Casado(a)</option>
            <option value="Solteiro">Solteiro(a)</option>
            <option value="Viuvo">Viuvo(a)</option>
        </select>
        <div class="valid-feedback">

        </div>
        <div class="invalid-feedback">
            <b>Estado Cívil</b> é um campo obrigatório!
        </div>
    </div>
</div>
