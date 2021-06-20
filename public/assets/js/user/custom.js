//load datatables
$(document).ready(function(){
    //load datables library
    var table = $('#usertable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/users",
        columns: [
            {data: 'usr_nome', name: 'usr_nome'},
            {data: 'usr_usuario', name: 'usr_usuario'},
            {data: 'usr_email', name: 'usr_email'},
            {data: 'tipusr_nome', name: 'tipusr_nome'},
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
                "sFirst":    "Primeiro",
                "sPrevious": "Anterior",
                "sNext":     "Seguinte",
                "sLast":     "Último"
            }
        },
    });


    /**
     *  Validation Form
     */

    // window.addEventListener('load', function() {
    //
    //     // Fetch all the forms we want to apply custom Bootstrap validation styles to
    //     var forms = $("#formUser");
    //
    //     // Loop over them and prevent submission
    //     var validation = Array.prototype.filter.call( forms, function(form) {
    //
    //         form.addEventListener('submit', function(event) {
    //
    //             if ( form.checkValidity() === false ) {
    //
    //                 event.preventDefault();
    //                 event.stopPropagation();
    //
    //             }else{
    //
    //                 //send data
    //
    //
    //             }
    //
    //             form.classList.add('was-validated');
    //
    //         }, false);
    //
    //     });
    //
    // }, false);

    /**
     * Form add User
     */
    $("#formUser").validate({
        rules: {
            name:"required",
            user:"required",
            email:{
                required: true,
                email: true
            },
            password1:"required",
            password2: {
                required: true,
                equalTo: '#password1'
            },
            usertype: "required",

        },
        messages: {
            name: "Nome é um campo obrigatório",
            user: "Usuário é um campo obrigatório",
            password1: "Senha é um campo obrigatório",
            password2: {
                required: "Conf. de Senha é um campo obrigatório",
                equalTo: "Insira o mesmo valor do campo senha"
            },
            email: {
                required: "E-mail é um campo obrigatório",
                email: "E-mail inválido"
            },
            usertype: "Tipo é um campo obrigatório"
        },
        errorElement: "span",
        highlight: function () {
            $( "#formUser" ).addClass( "was-validated" );
        },
        unhighlight: function () {
            $( "#formUser" ).addClass( "was-validated" );
        },
        submitHandler: function () {
            $.ajax({
                url:'/users/store',
                method:'POST',
                data: $('#formUser').serialize(),
                success: function(data){

                    if(data.status === 'success'){

                        table.ajax.reload();

                        $('#userFormModal').modal('hide');

                        swal({
                            title: 'Bom trabalho!',
                            text: "Formulário salvo com sucesso",
                            type: 'success',
                            padding: '2em'
                        });

                        $('#formUser')[0].reset();
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
                                padding: '2em'
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

                    $('input#name.form-control').val(response.usr_nome);
                    $('input#user.form-control').val(response.usr_usuario);
                    $('input#email.form-control').val(response.usr_email);
                    $('select#usertype.custom-select').val(response.tipusr_codigoid);

                    $('#formUserEdit').append('<input type="hidden" id="userId" name="userId" value="'+response.usr_codigoid+'"/>');
                }
            });

            $('#editUserModal').modal()
        }else{

            swal("Atenção !", "Selecione apenas 1 registro por vez", "info");
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

            swal("Atenção !", "Selecione apenas 1 registro por vez", "info");
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

                    swal(titleAlert, msg, typeAlert);
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
                showCancelButton: true,
                cancelButtonText:"Cancelar",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Sim, remova!",
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
                        if (response == true){
                            table.ajax.reload();
                        }

                    }
                });

            });
        }else{
            swal("Atenção !", "Selecione apenas 1 registro por vez", "info");
        }

    });

});


