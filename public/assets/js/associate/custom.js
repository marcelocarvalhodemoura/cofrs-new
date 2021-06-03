$(document).ready(function() {

    $("#btnAssociateModalCreate").on('click', function(){
        //replace Form Title
        $("#associateModalCenterTitle").html('Formulário de Cadastramento de Associado');
        //Remove input hidden
        $("#associateId").remove();
        // Clear all data on the Form
        $("#formAssoc")[0].reset();
        //Open modal
        $("#associateFormModal").modal('show');
    });


    //load datables library
    var table = $('#associatetable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/associates",
        columns: [
            {data: 'assoc_nome', name: 'assoc_nome'},
            {data: 'assoc_cpf', name: 'assoc_cpf'},
            {data: 'assoc_matricula', name: 'assoc_matricula'},
            {data: 'assoc_fone', name: 'assoc_fone'},
            {data: 'assoc_cidade', name: 'assoc_cidade'},
            {data: 'tipassoc_nome', name: 'tipassoc_nome'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        "oLanguage": {
            "sProcessing": "Processando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "Não foram encontrados resultados",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando de 0 até 0 de 0 registros",
            "sInfoFiltered": "",
            "sInfoPostFix": "",
            "pagingType": "full_numbers",
            "sSearch": "Buscar:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Primeiro",
                "sPrevious": "Anterior",
                "sNext": "Seguinte",
                "sLast": "Último"
            }
        },
    });

    /**
     * Input Mask
     */
    $('#cep').inputmask("99999-999");
    $('#phone').inputmask("(99) 99999-9999");
    $('#phone2').inputmask("(99) 99999-9999");
    $('#cpf').inputmask("999.999.999-99");
    $('#born').inputmask("99/99/9999");

    /**
     * CEP function
     */
    $("#cep").on("blur", function (){

        var cep = $("#cep").val();
        var adress = $("#adress").val('...');
        var district = $("#district").val('...');
        var city = $("#city").val('...');
        var state = $("#state").val('...');

        $.getJSON("https://viacep.com.br/ws/"+cep+"/json/", function(data){
            setTimeout(function(){
                // troca o valor dos elementos
                adress.val(unescape(data.logradouro));
                adress.prop('disabled', false);

                district.val(unescape(data.bairro));
                district.prop('disabled', false);

                city.val(unescape(data.localidade));
                city.prop('disabled', false);

                state.val(unescape(data.uf));
                state.prop('disabled', false);
            }, 2000);
        });
    });

    $('#formAssoc').on('submit', function(e){
        e.preventDefault();
    });

    //load Agent
    $.ajax({
        url:"/agent/list",
        method:"GET",
        success: response => {
            var optionAgent = '<option value="">-Selecione-</option>';

            response.forEach(element =>{
               optionAgent += '<option value="'+element.ag_codigoid+'">'+element.ag_nome+'</option>';
            });

            $("#typeagent").append(optionAgent);
        }
    });

    //load classification
    $.ajax({
        url:"/classification/list",
        method:"GET",
        success: response => {
            var optionClassification = '<option value="">-Selecione-</option>';

            response.forEach(element => {
               optionClassification += '<option value="'+element.cla_codigoid+'">'+element.cla_nome+'</option>';
            });

            $("#classification").append(optionClassification);
        }
    });

    //Load tipoassociado
    $.ajax({
        url:"/typeassociate/list",
        method:"GET",
        success: response => {
            var option = '<option value="">-Selecione-</option>';

            response.forEach(element => {
                option += '<option value="'+element.tipassoc_codigoid+'">'+element.tipassoc_nome+'</option>';
            });

            $("#typeassociate").append(option);
        }
    });

    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }else{

                        if(
                            $("#depName").val() == ""
                            || $("#depCpf").val() == ""
                            || $("#depRg").val() == ""
                            ||$("#depDescription").val() == ""
                        ){
                            $.ajax({
                                url:"",
                                method:"POST",
                                data:$("#formDependents").serialize(),
                                success: response => {
                                    console.log(response);
                                }
                            });
                        }

                        //send data
                        $.ajax({
                            url:"/associates/store",
                            method:'POST',
                            data: $('#formAssoc').serialize(),
                            success: response =>{
                                console.log(response);
                                if(response.status === 'success'){

                                    table.ajax.reload();

                                    $('#associateFormModal').modal('hide');

                                    swal({
                                        title: 'Bom trabalho!',
                                        text: response.msg,
                                        type: response.status,
                                        padding: '2em'
                                    });

                                    $('#formAssoc')[0].reset();
                                }
                            }

                        });
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()

    /**
     * Dependents modal
     */
    $('#btnModalDependent').on('click', function (e) {
        e.preventDefault();
        var id = new Array();

        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function () {
            //get value in the input
            id.push($(this).val());

        });

        //validate if exist value
        if (id > 0) {

            $.ajax({
                url:"/associates/depents/"+id,
                method:"GET",
                success: response => {
                    console.log(response);
                    let tr = '';

                    if (response == ""){

                        tr = '<tr class="table-warning"><td colspan="4" align="center">Não há registro de Dependentes</td></tr>';

                    }else{
                        response.forEach(element =>{
                            console.log(element.lanc_valortotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                            tr += '<tr>';
                            tr += '<td class="text-primary">' + element.dep_nome + '</td>';
                            tr += '<td>' + element.dep_rg + 'º</td>';
                            tr += '<td>' + element.dep_cpf + '</td>';
                            tr += '<td>' +
                                    '<span className="text-danger">'+
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon">' +
                                            '<polyline points="3 6 5 6 21 6"></polyline>' +
                                            '<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>' +
                                            '<line x1="10" y1="11" x2="10" y2="17"></line>' +
                                            '<line x1="14" y1="11" x2="14" y2="17"></line>' +
                                        '</svg>' +
                                    '</span>'+
                                 '</td>' +
                                '</tr>';
                        });
                    }

                    //include new rows
                    $("#tableDep tbody").append(tr).fadeIn();
                    //set Id on the hidden input
                    $("#assocID").val(id);
                    //show Dependents modal
                    $('#associateFormModalDependents').modal('show');
                }
            });

        } else {

            swal("Atenção !", "Selecione apenas 1 registro por vez", "info");
        }
    });


    /**
     * Create new rows
     */
    $("#btnAddDep").on('click', function(){

        $(".table-warning").remove()

        let row = '<tr>\n' +
            '                <td><input type="text" name="name[]" class="form-control" id="name" required></td>\n' +
            '                <td><input type="text" name="name[]" class="form-control" id="name" required></td>\n' +
            '                <td><input type="text" name="name[]" class="form-control" id="name" required></td>\n' +
            '                  <td>' +
            '<span className="text-danger">'+
            '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 icon">' +
            '<polyline points="3 6 5 6 21 6"></polyline>' +
            '<path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>' +
            '<line x1="10" y1="11" x2="10" y2="17"></line>' +
            '<line x1="14" y1="11" x2="14" y2="17"></line>' +
            '</svg>' +
            '</span>'+
            '</td>\n'+
            '            </tr>';

        $("#tableDep tbody").fadeIn().append(row);
    });
    /**
     * Remove depents item
     */
    function remove(item){

        swal({
                title: "Tem certeza?",
                text: "O registro será removido permanentemente!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn btn-danger",
                confirmButtonText: "Sim, remove o item!",
                closeOnConfirm: false
            },
            function(){
                swal("Removido!", "Registro removido com sucesso.", "success");
                var tr = $(item).closest('tr');

                $.ajax({
                    type:"post",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{ id:$(item).attr("data-id") },
                    url:'/user/remove/'+$(item).attr("data-id"),
                    success: function(response){
                        console.log(response);

                        tr.fadeOut(400, function() {
                            tr.fadeOut('slow');
                            return false;
                        });
                    }
                });


            });



    }

    /**
     * Load Associate Edit Form Modal
     */
    $('#btnAssociateModalEdit').on('click', function (e) {
        e.preventDefault();
        var id = new Array();

        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function () {
            //get value in the input
            id.push($(this).val());

        });

        //validate if exist value
        if (id > 0) {

            //load Associate data
            $.ajax({
                url:"/associates/"+id,
                method:"GET",
                success:response => {
                    console.log(response);

                    var born = response.assoc_datanascimento.split('-');

                    var bornFormated = born[2]+'/'+born[1]+'/'+born[0];

                    $("#name").val(response.assoc_nome);
                    $("#identify").val(response.assoc_identificacao);
                    $("#registration").val(response.assoc_matricula);
                    $("#born").val(bornFormated);
                    $("#email").val(response.assoc_email);
                    $("#cpf").val(response.assoc_cpf);
                    $("#rg").val(response.assoc_rg);
                    $("#sexo").val(response.assoc_sexo);
                    $("#job").val(response.assoc_profissao);
                    $("#classification").val(response.cla_codigoid);
                    $("#civilstatus").val(response.assoc_estadocivil);
                    $("#phone").val(response.assoc_fone);
                    $("#phone2").val(response.assoc_fone2);
                    $("#typeassociate").val(response.tipassoc_codigoid);
                    $("#typeagent").val(response.ag_codigoid);
                    $("#cep").val(response.assoc_cep);
                    $("#adress").val(response.assoc_endereco);
                    $("#complement").val(response.assoc_complemento);
                    $("#district").val(response.assoc_bairro);
                    $("#state").val(response.assoc_uf);
                    $("#city").val(response.assoc_cidade);
                    $("#contract").val(response.assoc_contrato);
                    $("#description").val(response.assoc_observacao);
                    $("#bank").val(response.assoc_banco);
                    $("#bank_agency").val(response.assoc_agencia);
                    $("#count").val(response.assoc_conta);

                    $("#formAssoc").append('<input type="hidden" id="associateId" name="associateId" value="' + response.assoc_codigoid + '">');
                }
            });

            //replace Title
            $("#associateModalCenterTitle").html('Formulário de Alteração de Associado');

            //load Modal Edit Associate
            $("#associateFormModal").modal('show');

        }else{
            swal("Atenção !", "Selecione apenas 1 registro por vez", "info");
        }

    });


    $('#btnAssociateModalConvenants').on('click', function (e) {
        e.preventDefault();
        var id = new Array();

        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function () {
            //get value in the input
            id.push($(this).val());
            //alert(usuarioMarcados);
        });

        //validate if exist value
        if (id > 0) {
            $('#associateFormModalconvenants').modal('show');

            $.ajax({
                url:"/associates/instalment/"+id,
                method:"GET",
                success: response => {

                    let tr = '';

                    if (response == ""){

                        tr = '<tr class="table-warning"><td colspan="5" align="center">Não há registro de Pendências</td></tr>';

                    }else{
                        response.forEach(element =>{
                            console.log(element.lanc_valortotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}));
                            tr += '<tr>';
                            tr += '<td class="text-primary">' + element.con_nome + '</td>';
                            tr += '<td>' + element.par_numero + 'º</td>';
                            tr += '<td>' + element.lanc_numerodeparcela + '</td>';
                            tr += '<td>' + element.lanc_valortotal + '</td>';
                            tr += '<td><span class="badge badge-warning">' + element.par_status + '</span></td></tr>';
                        });
                    }

                    //include new rows
                    $("#tableAssocPortion tbody").append(tr).fadeIn();
                }
            });

        } else {

            swal("Atenção !", "Selecione apenas 1 registro por vez", "info");
        }

        $('#associateFormModalconvenants').on('hide.bs.modal', function () {
            // remove all tr in tbody
            $("#tableAssocPortion tbody tr").fadeOut().remove();
        });
    });




});
