//load datatables
$(document).ready(function(){
    //load datables library
    var table = $('#typecategorytable').DataTable({
        dom:"<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>" +
            "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        processing: true,
        serverSide: true,
        ajax: "/covenants-type",
        columns: [
            {data: 'con_nome', name: 'con_nome'},
            {data: 'tipconv_nome', name: 'tipconv_nome'},
            {data: 'con_referencia', name: 'con_referencia'},
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

    $('.dataTables_filter input[type="search"]').css({'width':'450px','display':'inline-block'});

    /**
     * Form add Type Category
     */
    $("#formTypeCategory").validate({
        rules: {
            name:"required",
            typeCategory:"required",
            elaborate:"required",
            reference: "required",

        },
        messages: {
            name: "Nome é um campo obrigatório",
            typeCategory: "Tipo é um campo obrigatório",
            elaborate: "Prolabore é um campo obrigatório",
            reference: "Referência é um campo obrigatório"
        },
        errorElement: "span",
        highlight: function () {
            $( "#formTypeCategory" ).addClass( "was-validated" );
        },
        unhighlight: function () {
            $( "#formTypeCategory" ).addClass( "was-validated" );
        },
        submitHandler: function () {
            $.ajax({
                url:'/convenants-type/store',
                method:'POST',
                data: $('#formTypeCategory').serialize(),
                success: function(data){

                    if(data.status === 'success'){

                        table.ajax.reload();

                        $('#typeCategoryConvenantsFormModal').modal('hide');

                        swal({
                            title: 'Bom trabalho!',
                            text: "Formulário salvo com sucesso",
                            type: 'success',
                        });

                        $('#formTypeCategory')[0].reset();
                    }
                }

            });
        }
    });

    /**
     * submit User Edit Form
     */
    $('#formUserEdit').validate({
            rules: {
                name:"required",
                user:"required",
                email:{
                    required: true,
                    email: true
                },
                usertype: "required",
            },
            messages: {
                name: "Nome é um campo obrigatório",
                user: "Usuário é um campo obrigatório",
                email: {
                    required: "E-mail é um campo obrigatório",
                    email: "E-mail inválido"
                },
                usertype: "Tipo é um campo obrigatório"
            },
            errorElement: "span",
            highlight: function () {
                $( "#formUserEdit" ).addClass( "was-validated" );
            },
            unhighlight: function () {
                $( "#formUserEdit" ).addClass( "was-validated" );
            },
            submitHandler: function () {
                //send data
                $.ajax({
                    url: '/users/store',
                    method: 'POST',
                    data: $("#formUserEdit").serialize(),
                    success: function (data) {

                        if (data.status === 'success') {

                            table.ajax.reload();

                            $('#editUserModal').modal('hide');

                            swal({
                                title: 'Bom trabalho!',
                                text: "Formulário salvo com sucesso",
                                type: 'success',
                                confirmButtonClass: 'btn btn-primary',
                            });

                        }
                    }

                });
            }
        }
    );
    /**
     * Validate checkbox in the datatables
     *
     * @type {any[]}
     */
    $('#btnUserEdit').on('click', function (e){

        var id = new Array();

        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function(){
            //get value in the input
            id.push($(this).val());
            //alert(usuarioMarcados);
        });

        //validate if exist value
        if(id > 0){

            $.ajax({
                url:'/users/load/'+id,
                method:'GET',
                success: function(response){

                    $('input#name.form-control').val(response[0].usr_nome);
                    $('input#user.form-control').val(response[0].usr_usuario);
                    $('input#email.form-control').val(response[0].usr_email);
                    $('select#usertype.custom-select').val(response[0].tipusr_codigoid);

                    $('#formUserEdit').append('<input type="hidden" id="userId" name="userId" value="'+response[0].id+'"/>');
                }
            });

            $('#editUserModal').modal()
        }else{

            swal({
                title: "Atenção !",
                text: "Selecione apenas 1 registro por vez",
                type:"info",
                confirmButtonClass: 'btn btn-primary',
            });
        }

    });


    /**
     * Modal Change Pass
     *
     */
    $('#btnPasswordModal').on('click', function(e){
        var id = new Array();

        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function(){
            //get value in the input
            id.push($(this).val());
            //alert(usuarioMarcados);
        });

        //validate if exist value
        if(id > 0){
            $('#passwordModal').modal('show');
            $("#editUserID").val(id);
        }else{

            swal({
                title: "Atenção !",
                text: "Selecione apenas 1 registro por vez",
                type:"info",
                confirmButtonClass: 'btn btn-primary',
            });
        }
    });

    /**
     * Forgot Password
     */

    $("#formSavePassword").validate({
        rules:{
            editPassword: "required",
            editPassword2: {
                required: true,
                equalTo: '#editPassword'
            }
        },
        messages: {
            editPassword: "Senha é um campo obrigatório",
            editPassword2: {
                required: "Conf. Senha é um campo obrigatório",
                equalTo: "Conf. de Senha deve ser igual ao campo Senha"
            }
        },
        errorElement: "span",
        highlight: function () {
            $( "#formSavePassword" ).addClass( "was-validated" );
        },
        unhighlight: function () {
            $( "#formSavePassword" ).addClass( "was-validated" );
        },
        submitHandler: function () {
            const id = $("#editUserID").val();

            $.ajax({
                url: "/users/pass/"+id,
                method:"POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $("#formSavePassword").serialize(),
                success: function(response){


                    if(response.status == 'error'){
                        msg = response.msg;
                        titleAlert = 'Atenção!';
                        typeAlert = 'info';
                    }else{
                        typeAlert = 'success';
                        titleAlert = 'Bom trabalho!'
                        msg = response.msg;
                        $('#formSavePassword')[0].reset();
                        $('#passwordModal').modal('hide');
                    }

                    swal({
                        title: titleAlert,
                        text: msg,
                        type:typeAlert,
                        confirmButtonClass: 'btn btn-primary',
                    });
                }

            });
        }
    });


    /**
     * Remove user data
     */

    $('#bntUserDelete').on('click', function(e){
        e.preventDefault();

        var id = new Array();

        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function(){
            //get value in the input
            id.push($(this).val());
            //alert(usuarioMarcados);
        });

        //validate if exist value
        if(id > 0){

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
                    url:"/users/delete/"+id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{ id:id },
                    success: function(response){
                        // if (response == true){
                        table.ajax.reload();
                        // }

                    }
                });

            });
        }else{
            swal({
                title: "Atenção !",
                text: "Selecione apenas 1 registro por vez",
                type:"info",
                confirmButtonClass: 'btn btn-primary',
            });
        }

    });

});


