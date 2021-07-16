function showSubItens(item){


    // var itemClicked = $(item);
    //
    // itemClicked.on('click', function(a){
        console.log('cliquei no trem !');
    //     // $(this).children('ul').slideToggle(400, function() {
    //     //     $(this).parent("li").toggleClass("open")
    //     // }), a.stopPropagation()
    // });
}
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

                $(".dinamicAccordion table.table").remove();

                if(response == ""){
                    let tr2 ='<table class="table" width="100%">' +
                            '<tbody>' +
                                '<tr>'+
                                    '<td colspan="3" style="text-align: center">'+
                                        '<b class="btn-link">Não existem dados referentes!</b>'+
                                    '</td>'+
                                '</tr>' +
                           '</tbody>' +
                        '</table>';

                    setTimeout(function(){
                       return $(".dinamicAccordion").append(tr2);
                    }, 2000);
                }

                response.forEach(function(item){
                    var total = item.lanc_valortotal.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});

                    tr += '<div class="">'+
                                '<table class="table" id="heading'+item.id+'">' +
                                    '<tbody>' +
                                        '<tr>'+
                                            '<td class="collapsed" data-toggle="collapse" data-target="#collapse'+item.id+'" aria-expanded="false" aria-controls="collapse'+item.id+'">'+
                                                '<b class=" btn-link" style="cursor: pointer;">'+item.assoc_nome +'</b>'+
                                            '</td>'+
                                            '<td width="25%" class="collapsed" data-toggle="collapse" data-target="#collapse'+item.id+'" aria-expanded="false" aria-controls="collapse'+item.id+'">'+
                                                '<b class="badge badge-primary btn-link">'+item.con_nome +'</b>'+
                                            '</td>'+
                                            '<td width="20%"><b class="badge badge-warning">'+item.lanc_datavencimento+'</b></td>' +
                                            '<td width="10%"><b class="badge badge-warning">'+item.lanc_numerodeparcela+' parcelas</b></td>' +
                                            '<td width="10%"><b class="badge badge-warning">R$'+total+'</b></td>' +
                                        '</tr>' +
                                    '</tbody>' +
                                '</table>'+
                            '</div>'+
                            ' <div id="collapse'+item.id+'" class="collapse" aria-labelledby="heading'+item.id+'" data-parent="#accordion">'+
                                '<div className="card-body">'+
                                    // '<ul>' +
                                    //     '<li>' +
                                            '<fieldset>' +
                                                '<legend><span class="label label-primary">Parcelamento</span></legend>' +
                                                '<div> '+
                                                   '<table class="table table-bordered table-hover table-striped mb-4">'+
                                                         '<thead>'+
                                                             '<tr>'+

                                                                 '<th>Referência</th>'+
                                                                 '<th>Competência</th>'+
                                                                 '<th>Parcela</th>'+
                                                                 '<th>Valor</th>'+
                                                                 '<th>Status</th>'+
                                                                 '<th><input id="portionSel" value="1" type="checkbox"></th>'+
                                                             '</tr>'+
                                                         '</thead>'+
                                                         '<tbody>';
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

                                                                tr+= '<tr class="table-'+ dynamicClass +'">' +
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
                                        '</fieldset>'+
                                    //     '</li>' +
                                    // '</ul>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
                });
                setTimeout(function(){
                    $(".dinamicAccordion").append(tr);
                }, 2000);
            }
        });

    });
});
