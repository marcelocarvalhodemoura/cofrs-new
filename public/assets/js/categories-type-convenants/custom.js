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
    $("#formtypecategoryconvenants").validate({
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
            $( "#formtypecategoryconvenants" ).addClass( "was-validated" );
        },
        unhighlight: function () {
            $( "#formtypecategoryconvenants" ).addClass( "was-validated" );
        },
        submitHandler: function () {
            $.ajax({
                url:'/convenants-type/store',
                method:'POST',
                data: $('#formtypecategoryconvenants').serialize(),
                success: function(data){

                    if(data.status === 'success'){

                        table.ajax.reload();

                        $('#editTypeCategoryModal').modal('hide');

                        swal({
                            title: 'Bom trabalho!',
                            text: "Formulário salvo com sucesso",
                            type: 'success',
                        });

                        $('#formtypecategoryconvenants')[0].reset();
                    }
                }

            });
        }
    });

    /**
     * Validate checkbox in the datatables
     *
     * @type {any[]}
     */
    $('#btntypecategoryconvenantsEdit').on('click', function (e){

        var id = new Array();

        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function(){
            //get value in the input
            id.push($(this).val());
            //alert(usuarioMarcados);
        });

        //validate if exist value
        if(id > 0){

            $.ajax({
                url:'/convenants-type/getAgreement/'+id,
                method:'GET',
                success: function(response){

                    $('#formtypecategoryconvenants #name').val(response.con_nome);

                    $('#formtypecategoryconvenants #reference').val(response.con_referencia);
                    $('#formtypecategoryconvenants #typeCategory').val(response.tipconv_codigoid);

                    $('#formtypecategoryconvenants #con_comissao_cofrs').val(response.con_comissao_cofrs.toString().replace('.',','));
                    $('#formtypecategoryconvenants #con_despesa_canal').val(response.con_despesa_canal.toString().replace('.',','));

                    $('#formtypecategoryconvenants').append('<input type="hidden" id="typeCategoryId" name="typeCategoryId" value="'+response.id+'"/>');
                }
            });

            $('#editTypeCategoryModal').modal()
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


