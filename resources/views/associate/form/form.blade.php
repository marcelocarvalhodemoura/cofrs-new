<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formAssoc">
    @csrf
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="name">Nome<b>*</b></label>
            <input type="text" class="form-control" id="name" name="name" value="" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Nome</b> é um campo obrigatório!
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="user">Identificação <b>*</b></label>
            <input type="text" class="form-control" id="identify" name="identify" placeholder="Número identificação" value="" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Identificação</b> é um campo obrigatório!
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="registration">Matrícula <b>*</b></label>
            <div class="input-group">
                <input type="text" class="form-control" id="registration" name="registration" placeholder="informe a matrícula" required>
                <div class="valid-feedback">

                </div>
                <div class="invalid-feedback">
                    <b>Matrícula</b> é um campo obrigatório!
                </div>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="born">Nascimento <b>*</b></label>
            <input type="text" class="form-control" id="born" name="born" placeholder="99/99/9999" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Data Nascimento</b> é um campo obrigatório!
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="cpf">CPF <b>*</b></label>
            <input type="text" class="form-control" id="cpf" name="cpf" placeholder="999.999.999-99" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>CPF</b> é um campo obrigatório!
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="rg">RG <b>*</b></label>
            <input type="text" class="form-control" id="rg" name="rg" placeholder="" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>RG</b> é um campo obrigatório!
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="sexo" class="form-label">Sexo <b>*</b></label>
            <select name="sexo" class="form-control" id="sexo"  required>
                <option value="">-Selecione-</option>
                <option value="Feminino">Feminino</option>
                <option value="Masculino">Masculino</option>
            </select>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Sexo</b> é um campo obrigatório!
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="job">Profissão  <b>*</b></label>
            <input type="text" class="form-control" id="job" name="job" placeholder="Informe o profissão" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Profissão</b> é um campo obrigatório!
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <label for="typeassociate">Tipo<b>*</b></label>
            <select name="typeassociate" id="typeassociate" class="form-control" required>

            </select>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Tipo</b> é um campo obrigatório!
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="email">E-mail <b>*</b></label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend">@</span>
                </div>
                <input type="text" class="form-control" id="email" name="email" placeholder="nome@provedor.com" required="">
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
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="phone">Fone <b>*</b></label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="(99) 99999-9999" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Fone</b> é um campo obrigatório!
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <label for="phone2">Fone2</label>
            <input type="text" class="form-control" id="phone2" name="phone2" placeholder="(99) 99999-9999">

        </div>
        <div class="col-md-4 mb-4">
            <label for="typeagent">Agente <b>*</b></label>
            <select name="typeagent" id="typeagent" class="form-control" required>

            </select>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Agente</b> é um campo obrigatório!
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="cep">Cep <b>*</b></label>
            <input type="text" class="form-control" id="cep" name="cep" placeholder="999999-999" required>
            <div class="valid-feedback">

            </div>
            <div class="invalid-feedback">
                <b>Cep</b> é um campo obrigatório!
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <label for="adress">Logradouro</label>
            <input type="text" class="form-control" id="adress" name="adress" placeholder="Endereço" disabled>
        </div>

        <div class="col-md-4 mb-4">
            <label for="complement">Complemento</label>
            <input type="text" class="form-control" id="complement" name="complement" placeholder="..." >
        </div>

    </div>
    <div class="form-row">
        <div class="col-md-3 mb-3">
            <label for="district">Bairro </label>
            <input type="text" class="form-control" id="district" name="district" placeholder="Bairro" disabled>
        </div>

        <div class="col-md-3 mb-3">
            <label for="stade">UF</label>
            <input type="text" class="form-control" id="state" name="state" placeholder="UF" disabled>
        </div>

        <div class="col-md-3 mb-3">
            <label for="city">Cidade</label>
            <input type="text" class="form-control" id="city" name="city" placeholder="Cidade" disabled>
        </div>
        <div class="col-md-3 md-3">
            <label class="control-label">Contrato</label>
            <input type="text" class="form-control"  placeholder="Insira o contrato" id="contract" name="contract">

        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12">
            <label for="description">Observação</label>
            <textarea class="form-control" name="description" id="description">

            </textarea>
        </div>
    </div>
    <br/>
    <div class="form-row">
        <div class="col-md-12 mb-4">
            <h5>Informações Bancárias</h5>
        </div>
    </div>

    <div class="form-row">
        <div class="col-md-4 mb-4">
            <label for="bank">Banco </label>
            <input type="text" class="form-control" id="bank" name="bank" placeholder="Nome do Banco" >
        </div>

        <div class="col-md-4 mb-4">
            <label for="bank_agency">Agência </label>
            <input type="text" class="form-control" id="bank_agency" name="bank_agency" placeholder="Agência bancária">
        </div>

        <div class="col-md-4 mb-4">
            <label for="count">Conta</label>
            <input type="text" class="form-control" id="count" name="count" placeholder="Conta bancária">
        </div>
    </div>

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal">
            <i class="flaticon-cancel-12"></i>
            Limpar
        </button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
