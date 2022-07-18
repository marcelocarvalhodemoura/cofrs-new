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
        dom:"<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>" +
            "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
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
                "sPrevious":    "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-arrow-left\"><line x1=\"19\" y1=\"12\" x2=\"5\" y2=\"12\"></line><polyline points=\"12 19 5 12 12 5\"></polyline></svg>",
                "sNext":     "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-arrow-right\"><line x1=\"5\" y1=\"12\" x2=\"19\" y2=\"12\"></line><polyline points=\"12 5 19 12 12 19\"></polyline></svg>"
            }
        },
    });

    /**
     * Input Mask in Associate Form
     */
    $('#cep').inputmask("99999-999");
    $('#phone').inputmask("(99) 99999-9999");
    $('#phone2').inputmask("(99) 99999-9999");
    $('#cpf').inputmask("999.999.999-99");
    $('#born').inputmask("99/99/9999");

    /**
     * Input mask in Dependent form
     */
    $("#depRegistration").inputmask("999.999.999-99");

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


    //load Agent
    $.ajax({
        url:"/agent/list",
        method:"GET",
        success: response => {
            var optionAgent = '<option value="">-Selecione-</option>';

            response.forEach(element =>{
               optionAgent += '<option value="'+element.id+'">'+element.ag_nome+'</option>';
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
               optionClassification += '<option value="'+element.id+'">'+element.cla_nome+'</option>';
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
                option += '<option value="'+element.id+'">'+element.tipassoc_nome+'</option>';
            });

            $("#typeassociate").append(option);
        }
    });


    $('#formDependents').validate({
        rule: {
            depName: "required",
            depIdentify: "required",
            depRegistration: "required",
            depPhone: "required",
        },
        messages: {
            depName:"Nome é um campo obrigatório",
            depIdentify: "RG é um campo obrigatório",
            depRegistration: "CPF é um campo Obrigatório",
            depPhone: "Fone é um campo obrigatório"
        },
        errorElement: "span",
        highlight: function () {
            $( "#formDependents" ).addClass( "was-validated" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( "#formDependents" ).addClass( "was-validated" );
        },
        submitHandler: function () {
            $.ajax({
                url:"/associates/dependents/store",
                method:"POST",
                data:$("#formDependents").serialize(),
                success: response => {

                    if(response.status === 'success'){
                        swal({
                            title: 'Bom trabalho!',
                            text: response.msg,
                            type: response.status
                        });
                        $("#formDependents")[0].reset();
                        $('#associateFormModalDependents').modal('hide');
                    }
                }
            });
        }
    });
    $('#formAssoc').validate({
        rule: {
            name: "required",
            registration: "required",
            born: "required",
            cpf: "required",
            rg: "required",
            sexo: "required",
            job: "required",
            typeassociate: "required",
            identify: "required",
            email:  {
                required: true,
                email: true
            },
            classification: "required",
            civilstatus: "required",
            phone: "required",
            typeagent: "required",
            cep: "required"
        },
        messages: {
            name: "Nome é um campo obrigatório",
            registration: "Identificação é um campo obrigatório",
            born: "Data de Nascimento é um campo obrigatório",
            cpf: "CPF é um campo obrigatório",
            rg: "RG é um campo obrigatório",
            sexo: "Sexo é um campo obrigatório",
            job: "Profissão é um campo obrigatório",
            typeassociate: "Tipo é um campo obrigatório",
            email: "E-mail é um campo obrigatório e deve ser válido",
            classification: "Classificação é um campo obrigatório",
            civilstatus: "Estado Civil é um campo obrigatório",
            phone: "Fone é um campo obrigatório",
            typeagent: "Agente é um campo obrigatório",
            cep: "Cep é um campo obrigatório"
        },
        errorElement: "span",
        highlight: function () {
            $( "#formAssoc" ).addClass( "was-validated" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( "#formAssoc" ).addClass( "was-validated" );
        },
        submitHandler: function () {
            $.ajax({
                url:"/associates/store",
                method:'POST',
                data: $('#formAssoc').serialize(),
                success: response =>{
                    //console.log(response);
                    if(response.status === 'success'){
                        //reload Table
                        table.ajax.reload();
                        $('#associateFormModal').modal('hide');

                        swal({
                            title: 'Bom trabalho!',
                            text: response.msg,
                            type: response.status,
                        });

                        $('#formAssoc')[0].reset();
                    }
                }

            });
        }
    });

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
                url:"/associates/dependents/"+id,
                method:"GET",
                success: response => {
                    console.log(response);
                    let tr = '';

                    if (response == ""){

                        tr = '<tr class="table-warning"><td colspan="5" align="center">Não há registro de Dependentes</td></tr>';

                    }else{
                        response.forEach(element =>{

                            tr += '<tr>';
                            tr += '<td class="text-primary">' + element.dep_nome + '</td>';
                            tr += '<td>' + element.dep_rg + '</td>';
                            tr += '<td>' + element.dep_cpf + '</td>';
                            tr += '<td>' + element.dep_fone + '</td>';
                            tr += '<td>' +
                                    '<span className="text-danger" onclick="remove(this)" data-id="'+element.id+'">'+
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
                    $("#tableDep tbody tr").remove();
                    //include new rows
                    $("#tableDep tbody").append(tr).fadeIn();
                    //set Id on the hidden input
                    $("#assocID").val(id);
                    //show Dependents modal
                    $('#associateFormModalDependents').modal('show');
                }
            });

        } else {

            swal({
                title: "Atenção !",
                text: "Selecione apenas 1 registro por vez",
                type:"info",
                confirmButtonClass: 'btn btn-primary',
            });
        }
    });

    /**
     * Create Mask into dinamic fields
     */
    $(document).on("focus", ".registration", function(){
        $(this).inputmask("999.999.999-99");
    });

    $(document).on("focus", ".depPhone", function(){
        $(this).inputmask("(99)99999-9999");
    });


    /**
     * Create new rows
     */
    $("#btnAddDep").on('click', function(){

        $(".table-warning").remove();

        let row = '<tr>\n' +
            '                <td><input type="text" name="depName[]" class="form-control" id="depName" required></td>\n' +
            '                <td><input type="text" name="depIdentify[]" maxlength="11" class="form-control threshold" id="depIdentify" required></td>\n' +
            '                <td><input type="text" name="depRegistration[]" class="form-control registration" id="depRegistration" required></td>\n' +
            '                <td><input type="text" name="depPhone[]" class="form-control depPhone" id="depPhone" required></td>\n' +
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

        $("#tableDep tbody").append(row);


    });
    /**
     * Remove depents item
     */
     remove = function(item){
         swal({
             title: "Tem certeza?",
             text: "O registro será removido permanentemente!",
             type: "warning",
             confirmButtonClass: 'btn btn btn-primary',
             cancelButtonClass: 'btn btn-danger mr-3',
             buttonsStyling: false,
             showCancelButton: true,
             cancelButtonText:"Cancelar",
             confirmButtonText: "Remova!",
             closeOnConfirm: false
         }).then(function(result) {
             console.log(result);
             if (result == true) {
                 var tr = $(item).closest('tr');
                 $.ajax({
                     type:"post",
                     headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                     data:{
                         "_token": $('input[name="_token"]').val(),
                         "id":$(item).attr("data-id")
                     },
                     url:'/associates/dependents/remove/'+$(item).attr("data-id"),
                     success: function(response){
                         console.log(response);

                         tr.fadeOut(400, function() {
                             tr.fadeOut('slow');
                             return false;
                         });
                     }
                 });
                 swal(
                     'Removido!',
                     'Registro removido com sucesso.',
                     'success',
                 )
             }
         });

    }

    /**
     * Remove associate
     */
    $("#btnAssocietaRemove").on('click', function(e){
        e.preventDefault();

        var id = new Array();

        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function () {
            //get value in the input
            id.push($(this).val());

        });

        //validate if exist value
        if (id > 0) {
            swal({
                title: "Confirma?",
                text: "Após a confirmação o usuário será removido.",
                type: "warning",
                confirmButtonClass: 'btn btn btn-primary',
                cancelButtonClass: 'btn btn-danger mr-3',
                buttonsStyling: false,
                showCancelButton: true,
                cancelButtonText:"Cancelar",
                confirmButtonText: "Remova!",
                closeOnConfirm: false
            }).then(function(result) {

                $.ajax({
                    method:"POST",
                    url:"/associates/delete/"+id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{ id:id },
                    success: function(response){
                        if (response == true){
                            table.ajax.reload();
                        }

                    }
                });

            });
        } else {

            swal({
                title: "Atenção !",
                text: "Selecione apenas 1 registro por vez",
                type:"info",
                confirmButtonClass: 'btn btn-primary',
            });
        }
    });

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

                    var born = response.assoc_datanascimento.split('-');

                    var bornFormated = born[2]+'/'+born[1]+'/'+born[0];

                    $("#name").val(response.assoc_nome);
                    $("#identify").val(response.assoc_matricula);
                    $("#registration").val(response.assoc_identificacao);
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
                    $("#third_party_contract").val(response.assoc_contrato_terceiros);
                    $("#description").val(response.assoc_observacao);
                    $("#bank").val(response.assoc_banco);
                    $("#bank_agency").val(response.assoc_agencia);
                    $("#count").val(response.assoc_conta);

                    $("#formAssoc").append('<input type="hidden" id="associateId" name="associateId" value="' + response.id + '">');
                }
            });

            //replace Title
            $("#associateModalCenterTitle").html('Formulário de Alteração de Associado');

            //load Modal Edit Associate
            $("#associateFormModal").modal('show');

        }else{
            swal({
                title: "Atenção !",
                text: "Selecione apenas 1 registro por vez",
                type:"info",
                confirmButtonClass: 'btn btn-primary',
            });
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

            swal({
                title: "Atenção !",
                text: "Selecione apenas 1 registro por vez",
                type:"info",
                confirmButtonClass: 'btn btn-primary',
            });
        }

        $('#associateFormModalconvenants').on('hide.bs.modal', function () {
            // remove all tr in tbody
            $("#tableAssocPortion tbody tr").fadeOut().remove();
        });
    });

});
