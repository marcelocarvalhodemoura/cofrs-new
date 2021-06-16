<form class="needs-validation text-left " action="javascript:void(0);" id="formAssoc" novalidate="novalidate">
    @csrf

    @include('associate.form._associate-form-line1')
    @include('associate.form._associate-form-line2')
    @include('associate.form._associate-form-line3')
    @include('associate.form._associate-form-line4')
    @include('associate.form._associate-form-line5')
    @include('associate.form._associate-form-line6')
    @include('associate.form._associate-form-line7')
    @include('associate.form._associate-form-line8')

    <div class="modal-footer">
        <button class="btn" data-dismiss="modal">
            <i class="flaticon-cancel-12"></i>
            Limpar
        </button>
        <button class="btn btn-primary" id="btnSave" type="submit">Salvar</button>
    </div>
</form>
