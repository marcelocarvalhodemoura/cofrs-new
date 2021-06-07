<form class="needs-validation text-left" novalidate action="javascript:void(0);" id="formDependents">
    @csrf
    <input type="hidden" name="assocID" id="assocID" value="">
    <table class="table table-striped" id="tableDep">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Rg</th>
                <th>CPF</th>
                <th>Fone</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <button class="btn btn-primary" id="btnAddDep" type="button">
        +
    </button>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal">
            <i class="flaticon-cancel-12"></i>
            Limpar
        </button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
