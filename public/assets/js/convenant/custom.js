$(document).ready(function(){
    //load select
    $(".basic").select2({
        tags: true,
    });

    $("#btnAddPortion").on('click', (event)=>{
        $("#convenantModalCreate").modal('show');
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
                            padding: '2em'
                        });

                        $('#formConvenants')[0].reset();
                    }
                }
            });
        }
    });

    $("#portion").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});



    $("#portion").blur(function(){
        // console.log("entrei");
        // // alert("This input field has lost its focus.");
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
                // alert('mes='+mes);
                ano = ano + 1;
                // alert('ano='+ano);
            }else{
                mes = mes + 1;
                // alert('mes='+mes);
            }
        }

        // //mes = mes + parseInt($("#edtNumeroParcelas").val());
        if(mes < 10){
            dataFormatada =  '10/0' + mes + '/' + ano;
        }else if(mes > 12){
            dataFormatada =  '10/' + mes - 12 + '/' + ano;
        }else{
            dataFormatada =  '10/' + mes + '/' + ano;
        }

        // console.log("dataFormatada="+dataFormatada);
        setTimeout(function(){
            $("#duedate").val(dataFormatada);
            // $("#dataFimDatepickerIcon").fadeOut();
            $("#total").val(parseFloat(valorTotalParcelas).toFixed(2).replace('.', ','));
            // $("#valorTotalIcon").fadeOut();
        },2000);
    });


    $("select.basic").on('change', event=>{

        $.ajax({
            method: "POST",
            url:'/convenants/list',
            data:$('#convenantsForm').serialize(),
            success:response =>{

                var tr = "";

                $("tbody tr").remove();

                response.forEach(function(item){

                    var dynamicClass = "";

                    switch (item.par_status){
                        case 'Pendente':
                            dynamicClass = "table-warning";
                            break;

                        case 'Pago':
                            dynamicClass = "table-success";
                            break;

                        case 'Vencido':
                            dynamicClass = "table-danger";
                            break;

                        case 'Transferido':
                            dynamicClass = "table-info";
                            break;
                    }

                    tr += '<tr class="'+ dynamicClass +'"><td>'+item.assoc_nome+'</td>' +
                        '<td>'+item.par_numero+'</td>' +
                        '<td>'+item.con_nome+'</td>' +
                        '<td>'+item.con_referencia+'</td>' +
                        '<td>'+item.com_nome+'</td>' +
                        '<td>'+item.par_valor+'</td>' +
                        '<td>'+item.lanc_valortotal+'</td>' +
                        '<td>'+item.par_status+'</td>' +
                        '<td><input class="" name="actionCheck[]" id="actionCheck" type="checkbox" value="'+item.par_codigoid+'"/></td>' +
                        '</tr>';
                });


               $('tbody').append(tr);

            }
        });

    });
});
