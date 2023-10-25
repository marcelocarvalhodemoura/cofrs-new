$(document).ready(function () {

    //load select
    $(".basic").select2({
        tags: true,
    });

    $("#btnAddPortion").on('click', (event) => {
        $("#convenantModalCreate").modal('show');
    });

    $("#btnAddUploadFile").on('click', function () {
        $("#convenantModalUploadFiles").modal('show');
    });

    $("#btnAddDownloadFile").on('click', function () {
        $("#convenantModalDownloadFiles").modal('show');
    });

    $("#btnAddInstallmentPayment").on('click', function () {
        $("#convenantInstallmentPayment").modal('show');
    });

    hideModels();

    $('#btnAddMonthlyPayment').on('click', (event) => {
        event.preventDefault();
        $("#monthlyPayment").modal('show');


        // $.ajax({
        //     method:'GET',
        //     url: '/convenants/monthly',
        //     success: response => {
        //         console.log(response);
        //         swal({
        //             title: "Confirma?",
        //             text: `Existem ${response.data} usuários sem mensalidades. Deseja incluí-las?`,
        //             type: "info",
        //             confirmButtonClass: 'btn btn btn-primary',
        //             cancelButtonClass: 'btn btn-danger mr-3',
        //             buttonsStyling: false,
        //             showCancelButton: true,
        //             cancelButtonText:"Cancelar",
        //             confirmButtonText: "Quite-a!",
        //             closeOnConfirm: false
        //         }).then(function(result) {
        //             if(result === true){
        //                 $.ajax({
        //                     method: "POST",
        //                     url: "/convenants/monthly/add",
        //                     headers: {
        //                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                     },
        //                     success: response => {
        //                         console.log(response);
        //                     }
        //                 });
        //             }
        //
        //         });
        //     }
        // });
    });

    $('#btnSavePayment').on('click', function (event) {
        event.preventDefault();

        var data = new FormData();

        alert($('#fileuploader input[name="file"]')[0].files[0].name);

        data.append('file', document.getElementById('.fileuploader input[name="file"]').files[0]);

        //data.append('file', document.getElementById('file').files[0]);
        //data.append('file', document.getElementById('file').files[0]);
        //data.append('massive', $('input[name="massive[]"]:checked').val());

        $.ajax({
            method: 'POST',
            url: '/convenants/monthly/add',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                //console.log(response);
                let title;
                if (response[0].status === 'success') {
                    title = 'Bom trabalho!';
                } else {
                    title = 'Atenção!';
                }
                /* Retorno do PHP */
                $('#monthlyPayment').modal('hide');
                swal({
                    title: title,
                    text: response[0].msg,
                    type: response[0].status,
                    confirmButtonClass: 'btn btn-success',
                });
            }
        });
    });

    /**
     * Generation
     */
    $("#btnCreateFile").on('click', (event)=>{
       event.preventDefault();

       $.ajax({
           method:'POST',
           url: '/convenants/file/create',
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           data: $('#formDowloadFile').serialize(),
           success: file => {
               var a = document.createElement('a'), blob, url;
               if (typeof a.download === 'undefined') {
                   alert('download não suportado pelo navegador');
               } else {
                   // criar "arquivo", conteúdo como array e tipo como objeto
                   blob = new Blob([file], {type: 'text/plain'});
                   // criar URL para arquivo criado
                   url = URL.createObjectURL(blob);
                   a.href = url;
                   // atribuir nome de download do arquivo
                   a.download = 'arGerado.txt';
                   // fazer download
                   a.click();
                   // revogar URL criada
                   URL.revokeObjectURL(url);
               }
           }
       })
    });


    /**
     * Install Payment
     */
    $("#btnAddInstallmentPayment").on('click', () => {
        const id = new Array();
        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function () {
            //get value in the input
            id.push($(this).val());
        });

        if (id.length > 0) {
            swal({
                title: "Confirma?",
                text: "Após a confirmação a parcela será quitada.",
                type: "warning",
                confirmButtonClass: 'btn btn btn-primary',
                cancelButtonClass: 'btn btn-danger mr-3',
                buttonsStyling: false,
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonText: "Quite-a!",
                closeOnConfirm: false
            }).then(function (result) {
                $.ajax({
                    method: "POST",
                    url: "/convenats/portion",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { id: id },
                    success: function (response) {

                        if (response.status === 'success') {
                            //remove current line
                            $("#tableCovenants tbody tr").remove();

                            const tr2 = '<tr>' +
                                '<td colspan="6">' +
                                '<table class="table" width="100%" style="margin-bottom: -13px!important">' +
                                '<tbody>' +
                                '<tr>' +
                                '<td colspan="3" style="text-align: center">' +
                                '<b class="btn-link">Não existem dados referentes!</b>' +
                                '</td>' +
                                '</tr>' +
                                '</tbody>' +
                                '</table>' +
                                '</td></tr>';
                            // add line - not found
                            $("#tableCovenants tbody tr").append(tr2);


                        }

                    }
                });
            });
        } else {
            swal({
                title: "Atenção !",
                text: "Selecione apenas 1 registro por vez",
                type: "info",
                confirmButtonClass: 'btn btn-primary',
            });
        }


    });

    /**
     * Renegation
     */
    $('#btnAddRenegotiation').on('click', () => {

        const id = new Array();
        let lanc_id = 0;
        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function () {
            //get value in the input
            id.push($(this).val());
            lanc_id = $(this).parent().parent().attr('data-lanc-id');

        });

        if (id.length > 0 && id.length < 2) {

            $.ajax({
                method: 'GET',
                url: '/convenants/renegotiation/' + id[0] + '/' + lanc_id,
                success: response => {

                    if (response.status === 'success') {
                        //Remove all covenants rows
                        $("#tableCovenants tbody tr").remove();

                        const tr2 = '<tr>' +
                            '<td colspan="6">' +
                            '<table class="table" width="100%" style="margin-bottom: -13px!important">' +
                            '<tbody>' +
                            '<tr>' +
                            '<td colspan="3" style="text-align: center">' +
                            '<b class="btn-link">Não existem dados referentes!</b>' +
                            '</td>' +
                            '</tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</td></tr>';

                        $("#tableCovenants tbody tr").append(tr2);

                        swal({
                            title: 'Bom trabalho!',
                            text: response.msg,
                            type: 'success',
                            confirmButtonClass: 'btn btn-success',
                        });
                    }
                }
            });
        } else {
            swal({
                title: "Atenção !",
                text: "Selecione apenas 1 registro por vez",
                type: "info",
                confirmButtonClass: 'btn btn-primary',
            });
        }

    });

    /**
     * Load Table Convenants without data
     */
    let tr = '<tr class="table-warning">' +
        '<td colspan="7" class="text-center">' +
        '<strong>Selecione o campo!</strong>' +
        '</td>' +
        '</tr>';

    $("#tableCovenants tbody").append(tr);

    $("#portion").prop('disabled', true);
    $("#total").prop('disabled', true);
    $("#firstPortion").prop('disabled', true);
    $("#loader1").hide();
    $("#loader2").hide();

    $('#number').on('click', () => {
        $("#portion").prop('disabled', false);
        $("#firstPortion").prop('disabled', true);
        $("#total").prop('disabled', true);

        $("#firstPortion").val('');
        $("#total").val('');

    });



    $("#formConvenants").validate({
        rules: {
            associate: "required",
            convenants: "required",
            number: {
                required: true,
            },
            portion: "required",
            total: {
                required: true,
            },
            firstPortion: "required",
            contract: {
                required: true,
            }

        },
        messages: {
            associate: "Associado é um campo obrigatório",
            convenants: "Convênio é um campo obrigatório",
            number: "Número é um campo obrigatório",
            portion: "Número da parcela é um campo obrigatório",
            total: "Valor Total é campos obrigatório",
            firstPortion: "Parcela inicial é um campo obrigatório",
            contract: "Contrato é um campo obrigatório"
        },
        errorElement: "span",
        highlight: function () {
            $("#formConvenants").addClass("was-validated");
        },
        unhighlight: function () {
            $("#formConvenants").addClass("was-validated");
        },
        submitHandler: function () {

            $.ajax({
                method: "POST",
                url: "/convenants/store",
                data: $("#formConvenants").serialize(),
                success: (response) => {
                    if (response.status === 'success') {

                        $('#convenantModalCreate').modal('hide');

                        swal({
                            title: 'Bom trabalho!',
                            text: response.msg,
                            type: 'success',
                            confirmButtonClass: 'btn btn-success',
                        });

                        $('#formConvenants')[0].reset();
                    }
                }
            });
        }
    });

    $("#portion").maskMoney({ prefix: 'R$ ', allowNegative: true, thousands: '.', decimal: ',', affixesStay: false });

    $("#portion").blur(function () {

        $("#loader1").fadeIn();

        setTimeout(() => {

            $("#loader1").fadeOut();

            $("#firstPortion").prop('disabled', false);
            $("#total").prop('disabled', false);

            $("#firstPortion").fadeIn();
            $("#total").fadeIn();
        }, 2000);


        // var valorTotal;
        var data = new Date();
        // var dia = data.getDate();
        var valorFormatado = $(this).val().replace(',', '.');
        // var mes = data.getMonth() + 1;
        // var ano = data.getFullYear();
        // var dataFormatada;
        // var i;

        if (valorFormatado > 0 && $("#number").val() > 0) {
            // valorTotalParcelas = ($(this).val() * $("#parcela").val());
            valorTotalParcelas = (valorFormatado * $("#number").val());

            $("#total").val('...');

        } else {

            $("#total").hide();
            return;
        }

        setTimeout(function () {

            $("#total").val(parseFloat(valorTotalParcelas).toFixed(2).replace('.', ','));
        }, 2000);
    });

    //creat chart on the form
    var donutChart = {
        chart: {
            height: 350,
            type: 'donut',
            toolbar: {
                show: false,
            }
        },
        // colors: ['#4361ee', '#888ea8', '#e3e4eb', '#d3d3d3'],
        series: [44, 55, 41, 17],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    }


    var donut = new ApexCharts(
        document.querySelector("#donut-chart"),
        donutChart
    );

    donut.render();

    donut.render();

    //Second upload
    //var secondUpload = new FileUploadWithPreview('mySecondImage');

}); //ready

//Remove Portion
    $("#btnRemove").click(()=> {
        const id = new Array();
        let lanc_id = 0;

        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function () {
            //get value in the input
            id.push($(this).val());
            lanc_id = $(this).parent().parent();

        });

        if (id.length === 0) {
            return swal({
                title: "Atenção !",
                text: "Selecione apenas 1 registro por vez",
                type: "info",
                confirmButtonClass: 'btn btn-primary',
            });
        }

        $.ajax({
            method: "POST",
            url: '/convenants/remove',
            data: {
                'id': id,
                '_token': $('input[name="_token"]').val()
            },
            success: response => {
                if (response.status === 'success') {
                    swal({
                        title: "Sucesso !",
                        text: "Registro removido com sucesso",
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                    });
                    $("#tableCovenants tbody tr").remove();
                }
            }
        });
    });

    function filtroConvenant() {

        $.blockUI({
            message: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>',
            overlayCSS: {
                backgroundColor: '#1b2024',
                opacity: 0.8,
                zIndex: 1200,
                cursor: 'wait'
            },
            css: {
                border: 0,
                color: '#fff',
                zIndex: 1201,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });


        $.ajax({
            method: "POST",
            url: '/convenants/list',
            data: $('#convenantsForm').serialize(),
            success: response => {

                let tr = "";

                $("#tableCovenants tbody tr").remove();

                if (response.length === 0 || response.status === 'error') {
                    let tr2 = '<tr>' +
                        '<td colspan="7">' +
                        '<table class="table" width="100%" style="margin-bottom: -13px!important">' +
                        '<tbody>' +
                        '<tr>' +
                        '<td colspan="3" style="text-align: center">' +
                        '<b class="btn-link">Não existem dados referentes!</b>' +
                        '</td>' +
                        '</tr>' +
                        '</tbody>' +
                        '</table>' +
                        '</td></tr>';

                        $.unblockUI();

                        return $("#tableCovenants tbody").append(tr2);
                } else {
                    // console.log(response);
                    response.forEach(function (item) {
                        if(item.portion.length == 0){
                            return;
                        }

                        //convert string to array separated to "-" and reverse vector position
                        let dateFormated = item.lanc_datavencimento.split('-').reverse().toString().replaceAll(',', '/');

                        //Total variable convert to money format
                        let total = item.lanc_valortotal.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
                        //console.log(item.con_referencia);
                        tr += '<tr>' +
                            '<td colspan="7">' +
                            '<a href="#tableTest-' + item.lanc_codigoid + '" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">\n' +
                            '<table width="100%" class="table" style="margin-bottom: -13px!important">' +
                            '<tbody>' +
                            '<tr>' +
                            '<td class="text-primary" width="20%">' + item.assoc_nome + '</td>' +
                            '<td width="10%">' + item.assoc_cpf + '</td>' +
                            '<td width="15%"><b class="shadow-none badge outline-badge-primary">' + item.con_nome + '</b></td>';
                            if(item.con_referencia == 'MENSALIDADE'){
                                //console.log('é mensalidade');
                                tr += '<td width="15%">' + item.assoc_contrato + '</td>';
                            }else{
                                //console.log('não é ');
                                tr += '<td width="15%">' + item.lanc_contrato + '</td>';
                            }
                            tr +='<td width="10%">' + dateFormated + '</td>' +
                            '<td width="10%" align="center">' + item.lanc_numerodeparcela + '</td>' +
                            '<td width="10%">' + total + '</td>' +
                            '</tr>' +
                            '</tbody>' +
                            '</table>\n' +
                            '</a>' +
                            '<ul class="submenu list-unstyled collapse" id="tableTest-' + item.lanc_codigoid + '" data-parent="#tableCovenants" style="">\n' +
                            '<li class="active">\n' +
                            '<div class=card">' +
                            '<div class="card-body">' +
                            '<h6>Condições de Pagamento</h6>' +
                            '<table class="table table-bordered table-hover table-striped mb-4">' +
                            '<thead>' +
                            '<tr>' +

                            '<th><span class="badge badge-primary">Referência</span></th>' +
                            '<th><span class="badge badge-primary">Competência</span></th>' +
                            '<th><span class="badge badge-primary">Parcela</span></th>' +
                            '<th><span class="badge badge-primary">Valor</span></th>' +
                            '<th><span class="badge badge-primary">Status</span></th>' +
                            '<th><input id="portionSel" value="1" type="checkbox" onclick="selAll(' + item.lanc_codigoid + ')"></th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody>';
                        //create portion convenants from associate
                        item.portion.forEach(function (value) {

                            var portionPrice = value.par_valor.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
                            var dynamicClass = "";

                            switch (value.par_status) {
                                case 'Pendente':
                                    dynamicClass = "warning";
                                    break;

                                case 'Pago':
                                    dynamicClass = "success";
                                    break;

                                case 'Vencido':
                                    dynamicClass = "danger";
                                    break;

                                case 'Transferido':
                                    dynamicClass = "info";
                                    break;
                            }

                            tr += '<tr class="table-' + dynamicClass + '" data-lanc-id="' + item.lanc_codigoid + '">' +
                                '<td>' + item.con_referencia + '</td>' +
                                '<td>' + value.com_nome + '</td>' +
                                '<td>' + value.par_numero + '</td>' +
                                '<td>' + portionPrice + '</td>' +
                                '<td><b class="badge badge-' + dynamicClass + '">' + value.par_status + '</b></td>' +
                                '<td><input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="' + value.par_codigoid + '"/></td>' +
                                '</tr>';
                        });



                        tr += '</tbody>' +
                            '</table>' +
                            '</div>' +
                            '</div>' +
                            '</li>\n' +

                            '</ul>' +
                            '</td>' +
                            '</tr>';


                    });

                    $('#tableCovenants tbody').append(tr);
                }//else

                $.unblockUI();
            }//success
        });//ajax

    };

function selAll(ulID) {
    if($('#tableTest-'+ulID+' #portionSel').is(':checked')){
        $('#tableTest-'+ulID+' input[type="checkbox"]').prop('checked', true);
    } else {
        $('#tableTest-'+ulID+' input[type="checkbox"]').prop('checked', false);
    }
}

function loadUpload() {
    var uploadObj = $("#fileuploader").uploadFile({
        url: '/convenants/monthly/add',
        fileName: "file",
        formData: { '_token': $('input[name="_token"]').val(), 'massive': $("input[name=massive]:checked").val() },
        autoSubmit: true,
        /*
        onSelect: function (files) {
            if (!$("input[name=massive]:checked").val()) {
                alert($("input[name=massive]:checked").val());
                uploadObj.cancelAll();
                uploadObj.reset();
            } else {
                uploadObj.startUpload();
            }
        },
        */
        onSuccess: function (files, data, xhr, pd) {
            console.log(data);
            /* Retorno do PHP */
            uploadObj.reset();
            if (data[0].status == 'success') {
                $('#monthlyPayment').modal('hide');
                $('#retorno').html('');
                swal({
                    title: 'Bom trabalho!',
                    text: data[0].msg,
                    type: data[0].status,
                    confirmButtonClass: 'btn btn-success',
                })
                .then((modalReturn) => {
                    location.reload();
                });
            } else {
                $('#retorno').html(data[0].msg);
            }
        },
        multiple: false,
        dragDrop: true,
        showDelete: false,
        showCancel: false,
        maxFileCount: 1,
        acceptFiles: ".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        showFileCounter: false,
        uploadStr: 'Clique ou arraste o arquivo aqui',
        dragDropStr: 'Clique ou arraste o arquivo aqui',
        extErrorStr: 'Não é permitido esse tipo de arquivo. As extensões permitidas são: ',
        maxFileCountErrorStr: 'Somente um arquivo pode ser enviado de cada vez.',
        cancelStr: 'Cancelar',
    });
}

function freeCompetence(){
    $('#selCompetitionDropBill').prop('disabled', false).focus();

    hideModels();
    if($("input[name=typeArchive]:checked").val() == "ipe"){
        $("#model_ipe").show();
    }
    if($("input[name=typeArchive]:checked").val() == "tesouro"){
        $("#model_tesouro").show();
    }
}
function hideModels(){
    $(".models").hide();
}

function loadUploadDropBill() {
    var uploadObj = $("#fileuploaderDropBill").uploadFile({
        url: '/convenants/dropBill',
        fileName: "file",
        formData: {
                '_token': $('input[name="_token"]').val(),
                'typeArchive': $('input[name="typeArchive"]').val(),
                'selCompetitionDropBill': $('#selCompetitionDropBill').val()
            },
        autoSubmit: true,
        onSuccess: function (files, data, xhr, pd) {
            //console.log(data);
            /* Retorno do PHP */
            uploadObj.reset();
            if (data[0].status === 'success') {
                $('#convenantModalUploadFiles').modal('hide');
                $('#retornoDropBill').html('');
                swal({
                    title: 'Bom trabalho!',
                    html: data[0].msg,
                    type: data[0].status,
                    confirmButtonClass: 'btn btn-success',
                });
            } else {
                $('#retornoDropBill').html(data[0].msg);
            }
        },
        multiple: false,
        dragDrop: true,
        showDelete: false,
        showCancel: false,
        maxFileCount: 1,
        acceptFiles: "text/plain",
        showFileCounter: false,
        uploadStr: 'Clique ou arraste o arquivo aqui',
        dragDropStr: 'Clique ou arraste o arquivo aqui',
        extErrorStr: 'Não é permitido esse tipo de arquivo. As extensões permitidas são: ',
        maxFileCountErrorStr: 'Somente um arquivo pode ser enviado de cada vez.',
        cancelStr: 'Cancelar',
    });
}
