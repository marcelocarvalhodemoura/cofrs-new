$(document).ready(function(){

    //load select
    $(".basic").select2({
        tags: true,
    });

    $("#btnAddPortion").on('click', (event)=>{
        $("#convenantModalCreate").modal('show');
    });

    $("#btnAddUploadFile").on('click', function () {
        $("#convenantModalUploadFiles").modal('show');
    });

    $("#btnAddInstallmentPayment").on('click', function(){
        $("#convenantInstallmentPayment").modal('show');
    });

    /**
     * Install Payment
     */
    $("#btnAddInstallmentPayment").on('click', ()=>{
        const id = new Array();
        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function(){
            //get value in the input
            id.push($(this).val());
        });

        if(id > 0){
            swal({
                title: "Confirma?",
                text: "Após a confirmação a parcela será quitada.",
                type: "warning",
                confirmButtonClass: 'btn btn btn-primary',
                cancelButtonClass: 'btn btn-danger mr-3',
                buttonsStyling: false,
                showCancelButton: true,
                cancelButtonText:"Cancelar",
                confirmButtonText: "Quite-a!",
                closeOnConfirm: false
            }).then(function(result) {
                $.ajax({
                    method:"POST",
                    url:"/convenats/portion/"+id,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{ id:id },
                    success: function(response){
                        console.log(response);
                        if (response.status === 'success'){
                            //change color row
                            $("#actionCheck:checked")
                                .closest('tr')
                                .removeClass('table-warning')
                                .addClass('table-success');
                            //change bagde
                            let checked = $("#actionCheck:checked").parents('tr').children()[4];
                            checked.innerHTML='<b class="badge badge-success">Pago</b>';


                        }

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

    /**
     * Renegation
     */
    $('#btnAddRenegotiation').on('click', () =>{

        const id = new Array();
        let lanc_id;
        $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function(){
            //get value in the input
            id.push($(this).val());
            lanc_id = $(this).parent().parent().attr('data-lanc-id');
        });

        if(id > 0){

            $.ajax({
                method:'GET',
                url: '/convenants/renegotiation/'+id+'/'+lanc_id,
                success: response => {
                    console.log(response);
                    if(response.status === 'success'){
                        //Remove all covenants rows
                        $("#tableCovenants tbody tr").remove();

                        const tr2 = '<tr>' +
                            '<td colspan="6">' +
                            '<table class="table" width="100%" style="margin-bottom: -13px!important">' +
                            '<tbody>' +
                            '<tr>'+
                            '<td colspan="3" style="text-align: center">'+
                            '<b class="btn-link">Não existem dados referentes!</b>'+
                            '</td>'+
                            '</tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</td></tr>';

                        $("#tableCovenants tbody tr").append(tr2);

                        swal({
                            title: 'Bom trabalho!',
                            text: response.msg,
                            type: 'success',
                            padding: '2em',
                            confirmButtonClass: 'btn btn-success',
                        });
                    }
                }
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

    /**
     * Load Table Convenants without data
     */
    let tr = '<tr class="table-warning">' +
                '<td colspan="6" class="text-center">' +
                        '<strong>Selecione o campo!</strong>' +
                '</td>' +
            '</tr>';

    $("#tableCovenants tbody").append(tr);

    $("#formConvenants").validate({
        rules: {
            associate:"required",
            convenants:"required",
            number:{
                required: true,
            },
            portion:"required",
            total: {
                required: true,
            },
            duedate: "required",

        },
        messages: {
            associate: "Associado é um campo obrigatório",
            convenants: "Convênio é um campo obrigatório",
            number: "Número é um campo obrigatório",
            portion: "Número da parcela é um campo obrigatório",
            total: "Valor Total é campos obrigatório",
            duedate: "Vencimento final um campo obrigatório"
        },
        errorElement: "span",
        highlight: function () {
            $( "#formConvenants" ).addClass( "was-validated" );
        },
        unhighlight: function () {
            $( "#formConvenants" ).addClass( "was-validated" );
        },
        submitHandler: function () {

            $.ajax({
                method: "POST",
                url: "/convenants/store",
                data:$("#formConvenants").serialize(),
                success: (response) => {
                    if(response.status === 'success'){

                        $('#convenantModalCreate').modal('hide');

                        swal({
                            title: 'Bom trabalho!',
                            text: response.msg,
                            type: 'success',
                            padding: '2em',
                            confirmButtonClass: 'btn btn-success',
                        });

                        $('#formConvenants')[0].reset();
                    }
                }
            });
        }
    });

    $("#portion").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    $("#portion").blur(function(){

        $("#duedate").show();
        $("#total").show();

        var valorTotal;
        var data = new Date();
        var dia = data.getDate();
        var valorFormatado = $(this).val().replace(',', '.');
        var mes = data.getMonth() + 1;
        var ano = data.getFullYear();
        var dataFormatada;
        var i;

        if(valorFormatado > 0 && $("#number").val() > 0){
            // valorTotalParcelas = ($(this).val() * $("#parcela").val());
            valorTotalParcelas = (valorFormatado * $("#number").val());
            $("#duedate").val('...');
            $("#total").val('...');
            // console.log(valorTotalParcelas);
        }else{
            $("#duedate").hide();
            $("#total").hide();
            return;
        }

        for(i = 0  ; i < $("#number").val() ; i ++){
            if(mes == 12){
                mes = 1;
                ano = ano + 1;

            }else{
                mes = mes + 1;
            }
        }

        if(mes < 10){
            dataFormatada =  '10/0' + mes + '/' + ano;
        }else if(mes > 12){
            dataFormatada =  '10/' + mes - 12 + '/' + ano;
        }else{
            dataFormatada =  '10/' + mes + '/' + ano;
        }

        setTimeout(function(){
            $("#duedate").val(dataFormatada);
            $("#total").val(parseFloat(valorTotalParcelas).toFixed(2).replace('.', ','));
        },2000);
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

    $("select.basic").on('change', event=>{

        $.ajax({
            method: "POST",
            url:'/convenants/list',
            data:$('#convenantsForm').serialize(),
            success:response =>{
                $.blockUI({
                    message: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>',
                    fadeIn: 800,
                    timeout: 2000, //unblock after 2 seconds
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
                let tr = "";

                $("#tableCovenants tbody tr").remove();

                if(response == ""){
                    let tr2 = '<tr>' +
                            '<td colspan="6">' +
                                '<table class="table" width="100%" style="margin-bottom: -13px!important">' +
                                    '<tbody>' +
                                        '<tr>'+
                                            '<td colspan="3" style="text-align: center">'+
                                                '<b class="btn-link">Não existem dados referentes!</b>'+
                                            '</td>'+
                                        '</tr>' +
                                   '</tbody>' +
                                '</table>' +
                            '</td></tr>';

                    setTimeout(function(){
                       return $("#tableCovenants tbody").append(tr2);
                    }, 2000);
                }

                response.forEach(function(item){
                    //convert string to array separated to "-" and reverse vector position
                    let dateFormated = item.lanc_datavencimento.split('-').reverse().toString().replaceAll(',','/');

                    //Total variable convert to money format
                    let total = item.lanc_valortotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});

                    tr += '<tr>' +
                            '<td colspan="6">' +
                                '<a href="#tableTest-'+item.id+'" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle collapsed">\n' +
                                    '<table width="100%" class="table" style="margin-bottom: -13px!important">' +
                                        '<tbody>' +
                                            '<tr>'+
                                                '<td class="text-primary">'+item.assoc_nome +'</td>'+
                                                '<td >'+item.assoc_cpf +'</td>'+
                                                '<td width="25%"><b class="shadow-none badge outline-badge-primary">'+item.con_nome +'</b></td>'+
                                                '<td width="20%">'+dateFormated+'</td>'+
                                                '<td width="10%">'+item.lanc_numerodeparcela+'</td>'+
                                                '<td width="10%">'+total+'</td>'+
                                            '</tr>'+
                                        '</tbody>'+
                                    '</table>\n' +
                                '</a>' +
                                '<ul class="submenu list-unstyled collapse" id="tableTest-'+item.id+'" data-parent="#tableCovenants" style="">\n' +
                                    '<li class="active">\n' +
                                        '<div class=card">'+
                                            '<div class="card-body">' +
                                                '<h6>Condições de Pagamento</h6>'+
                                                    '<table class="table table-bordered table-hover table-striped mb-4">'+
                                                    '<thead>'+
                                                    '<tr>'+

                                                    '<th><span class="badge badge-primary">Referência</span></th>'+
                                                    '<th><span class="badge badge-primary">Competência</span></th>'+
                                                    '<th><span class="badge badge-primary">Parcela</span></th>'+
                                                    '<th><span class="badge badge-primary">Valor</span></th>'+
                                                    '<th><span class="badge badge-primary">Status</span></th>'+
                                                    '<th><input id="portionSel" value="1" type="checkbox"></th>'+
                                                    '</tr>'+
                                                    '</thead>'+
                                                    '<tbody>';
                                                        //create portion convenants from associate
                                                        item.portion.forEach(function(value){
                                                            var portionPrice = value.par_valor.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
                                                            var dynamicClass = "";

                                                            switch (value.par_status){
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

                                                            tr+= '<tr class="table-'+ dynamicClass +'" data-lanc-id="'+item.id+'">' +
                                                                '<td>'+item.con_referencia+'</td>' +
                                                                '<td>'+value.com_nome+'</td>' +
                                                                '<td>'+value.par_numero+'</td>' +
                                                                '<td>'+portionPrice+'</td>' +
                                                                '<td><b class="badge badge-'+ dynamicClass +'">'+ value.par_status +'</b></td>' +
                                                                '<td><input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="'+value.par_codigoid+'"/></td>' +
                                                                '</tr>';
                                                        });



                                            tr +=  '</tbody>'+
                                                '</table>'+
                                            '</div>'+
                                        '</div>'+
                                    '</li>\n' +

                                '</ul>' +
                            '</td>' +
                        '</tr>';


                });
                setTimeout(function(){
                    $('#tableCovenants tbody').append(tr);
                }, 2000);
            }
        });

    });
});
