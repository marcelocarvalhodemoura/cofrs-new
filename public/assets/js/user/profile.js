$(document).ready(function () {
    const id = window.document.URL.split('/');
    //Load user information
    $.ajax({
        url: '/users/load/' + id[5],
        method: 'GET',
        success: function (response) {

            $('input#name.form-control').val(response[0].usr_nome);
            $('input#user.form-control').val(response[0].usr_usuario);
            $('input#email.form-control').val(response[0].usr_email);
            $('select#usertype.custom-select').val(response[0].tipusr_codigoid);

            $('#formUserEdit').append('<input type="hidden" id="userId" name="userId" value="' + response[0].id + '"/>');
        }
    });

    //send and validate form
    $('#formUserEdit').validate({
        rules: {
            name: "required",
            user: "required",
            email: {
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
            $("#formUserEdit").addClass("was-validated");
        },
        unhighlight: function () {
            $("#formUserEdit").addClass("was-validated");
        },
        submitHandler: function () {
            //send data
            $.ajax({
                url: '/users/update/'+$("#userId").val(),
                method: 'POST',
                data: $("#formUserEdit").serialize(),
                success: function (data) {

                    if (data.status === 'success') {

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
})
