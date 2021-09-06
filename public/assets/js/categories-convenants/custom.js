$(document).ready(function(){
    //load datables library
    var table = $('#categoriesconvenants').DataTable({
        dom:"<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>" +
            "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        processing: true,
        serverSide: true,
        ajax: "/categories-convenants",
        columns: [
            {data: 'tipconv_nome', name: 'tipconv_nome'},
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


    $("#btnCategoriesConvenantsCreate").on('click', function(){
        //replace Form Title
        $("#categoriesConvenantsModalCenterTitle").html('Formulário de Cadastramento de Categoria de convênio');
        //Remove input hidden
        $("#categoriesConvenantsId").remove();
        // Clear all data on the Form
        $("#formCategoryConvenant")[0].reset();
        //Open modal
        $("#btnCategoriesConvenantsModalCreate").modal('show');
    });

    $('#formCategoryConvenant').validate({
        rule: {
            name: "required",
        },
        messages: {
            name:"Nome é um campo obrigatório",
        },
        errorElement: "span",
        highlight: function () {
            $( "#formCategoryConvenant" ).addClass( "was-validated" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( "#formCategoryConvenant" ).addClass( "was-validated" );
        },
        submitHandler: function () {
            $.ajax({
                url:"/categories-convenants/store",
                method:"POST",
                data:$("#formCategoryConvenant").serialize(),
                success: response => {
                    $("#tipconv_codigoid").remove();

                    if(response.status === 'success'){
                        table.ajax.reload();
                        swal({
                            title: 'Bom trabalho!',
                            text: response.msg,
                            type: response.status
                        });

                        $("#formCategoryConvenant")[0].reset();
                        $('#btnCategoriesConvenantsModalCreate').modal('hide');
                    }
                }
            });
        }
    });

    /**
     * Load Associate Edit Form Modal
     */
    $('#btnCategoriesConvenantsEdit').on('click', function (e) {
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
                url:"/categories-convenants/load/"+id,
                method:"GET",
                success:response => {
                    $("#tipconv_codigoid").remove();

                    $("#name").val(response[0].tipconv_nome);


                    $("#formCategoryConvenant").append('<input type="hidden" id="categoriesConvenantsId" name="categoriesConvenantsId" value="' + response[0].tipconv_codigoid + '">');
                }
            });

            //replace Title
            $("#categoriesConvenantsModalCenterTitle").html('Formulário de Alteração de Categoria de Convênio');

            //load Modal Edit Associate
            $("#btnCategoriesConvenantsModalCreate").modal('show');

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
