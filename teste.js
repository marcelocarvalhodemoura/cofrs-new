var domain = window.location.origin;
if(domain === "http://localhost:8888"){
    domain = domain+"/novo-cofrs";
}else{
    domain = "https://novo-cofrs.herokuapp.com";
}
$("#btnDependent").on('click', function(){
    $("#dependentForm").append('<div class="row"><div class="col-md-3"><div class="form-group">' +
        '<label  class="control-label">Nome</label>' +
        '<input type="text" class="form-control"  placeholder="Insira nome do dependente" name="dependente[]" ></div></div>' +
        '<div class="col-md-3"><div class="form-group">'+
        '<label  class="control-label">Fone</label>'+
        '<input type="text" class="form-control fonemask"  placeholder="Insira nome do dependente" name="foneDependent[]" ></div></div>'+
        '<div class="col-md-3"><div class="form-group">' +
        '<label  class="control-label">Rg do Dependente</label>' +
        '<input type="text" class="form-control"  placeholder="Insira o Rg do dependente" name="associadoRg[]"></div></div>' +
        '<div class="col-md-3"><div class="form-group"><label  class="control-label">CPF Dependente</label> ' +
        '<input type="text" class="form-control cpfmask"   id="associacaoCPF" name="associacaoCPF[]"></div></div></div>'
    );
});

setTimeout(function(){
    $("#closeDependent").click(function(){
        // console.log('cliquei no X');
    });
},1000);

$('#identificacao').blur( function(){
    $(this).val((("000000000000" + $(this).val()).slice(-12)));
});

$('#identificacaoEdit').blur(function(){
    $(this).val((("000000000000" + $(this).val()).slice(-12)));
});

var valorTotalParcelas = 0;

$("#mensalidadeConvenio").blur(function(){
    alert("This input field has lost its focus.");
    $("#dataFimDatepickerIcon").show();
    $("#valorTotalIcon").show();
    // console.log(parseFloat($(this).val()));
    // console.log(valorTotalParcelas);
    var valorTotal;
    var data = new Date();
    var dia = data.getDate();
    var mes = data.getMonth() + 1;
    var ano = data.getFullYear();
    var dataFormatada;
    var i;

    if($(this).val() > 0 && $("#parcela").val() > 0){
        valorTotalParcelas = ($(this).val() * $("#parcela").val());
        valorTotalParcelas = ($(this).val().replace(",", ".") * $("#parcela").val());
        $("#dataFimDatepicker").val('...');
        $("#valorTotal").val('...');
        console.log(valorTotalParcelas);
    }else{
        $("#dataFimDatepickerIcon").hide();
        $("#valorTotalIcon").hide();        return;
    }
    if(dia < 11){
            mes = mes - 1;
    }

    console.log($("#parcela").val());

    for(i = 0  ; i < $("#parcela").val() ; i ++){
        if(mes == 12){
            mes = 1;
            alert('mes='+mes);
            ano = ano + 1;
            alert('ano='+ano);
        }else{
            mes = mes + 1;
            alert('mes='+mes);
        }
    }

    mes = mes + parseInt($("#edtNumeroParcelas").val());

    if(mes < 10){
        dataFormatada =  '10/0' + mes + '/' + ano;
    }else if(mes > 12){
        dataFormatada =  '10/' + mes - 12 + '/' + ano;
    }else{
        dataFormatada =  '10/' + mes + '/' + ano;
    }

    console.log("dataFormatada="+dataFormatada);
    setTimeout(function(){
        $("#dataFimDatepicker").val(dataFormatada);
        $("#dataFimDatepickerIcon").fadeOut();
        $("#valorTotal").val(parseFloat(valorTotalParcelas).toFixed(2));
        $("#valorTotalIcon").fadeOut();
        },2000);
});

// Get Cep from Correios
function getEndereco() {
    if($("#cep").val() !== ""){
        var cepIcon = $("#cepIcon");
        var ufIcon = $("#ufIcon");
        var enderecoIcon = $("#enderecoIcon");
        var bairroIcon = $("#bairroIcon");
        var cidadeIcon = $("#cidadeIcon");
        cepIcon.show();
        ufIcon.show();
        enderecoIcon.show();
        bairroIcon.show();
        cidadeIcon.show();

        var cep = $("#cep").val();
        var endereco = $("#endereco").val('...');
        var bairro = $("#bairro").val('...');
        var cidade = $("#cidade").val('...');
        var uf = $("#uf").val('...');
    }else{

        var cepIcon = $("#cepEditIcon");
        var ufIcon = $("#ufEditIcon");
        var enderecoIcon = $("#enderecoEditIcon");
        var bairroIcon = $("#bairroEditIcon");
        var cidadeIcon = $("#cidadeEditIcon");
        cepIcon.show();
        ufIcon.show();
        enderecoIcon.show();
        bairroIcon.show();
        cidadeIcon.show();

        var cep = $("#cepEdit").val();
        var endereco = $("#enderecoEdit").val('...');
        var bairro = $("#bairroEdit").val('...');
        var cidade = $("#cidadeEdit").val('...');
        var uf = $("#ufEdit").val('...');
    }

    $.getJSON("https://viacep.com.br/ws/"+cep+"/json/", function(data){
        setTimeout(function(){
            // troca o valor dos elementos
            endereco.val(unescape(data.logradouro));
            enderecoIcon.fadeOut();
            bairro.val(unescape(data.bairro));
            bairroIcon.fadeOut();
            cidade.val(unescape(data.localidade));
            cidadeIcon.fadeOut();
            uf.val(unescape(data.uf));
            ufIcon.fadeOut();
            cepIcon.fadeOut();
            },
        2000);
    });
}

// Mascaras para os campos
$(document).on("focus", ".cpfmask", function(){
    $(this).mask('000.000.000-00');
});

$(document).on("focus", ".cepmask", function(){
    $(this).mask('99999-999');
});

$(document).on("focus", ".datemask", function(){
    $(this).mask('99/99/9999');
});

$(document).on("focus", ".fonemask", function(){
    $(this).mask('(99)99999-9999');
});



table = $('#datatableAssoc').DataTable({
    "processing": true,
    "serverSide": true,
    "order":[],
    "ajax":{
        url: domain + '/Associated/ListAssociated',        method:"POST",
    },
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
            "sFirst":
                "Primeiro",
            "sPrevious": "Anterior",
            "sNext":     "Seguinte",
            "sLast":     "Último"
        }
        },
    "columnsDefs":[
        {
            "targets":[ -1 ],
            "orderable":false,        },
    ],
});

/******* Refresh Datatables *********/
function DataTablesRefresh(){
    table.ajax.reload(null,false);
    //reload datatable ajax
}

function removeDependents(){
    console.log('cliquei no botao!!!!!');
    var idDependent = new Array();
    $("input[type=checkbox][name=\'checkDepId[]\']:checked").each(
        function(){
            idDependent.push($(this).val());
            // console.log(idDependent[0]);
    });
    if(idDependent[0] > 0){
        swal({
            title: "Confirma?",
            text: "Após a confirmação do Dependente será removido.",
            type: "warning",
            showCancelButton: true,
            cancelButtonText:"Cancelar",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Sim, Remova!",
            closeOnConfirm: false
        },function(){
            $.ajax({
                type: "POST",
                url:domain + "/Associated/RemoveDependets/"+idDependent[0],
                success: function(data){
                    swal("Remoção!", "Dependente removido com sucesso.", "success");
                    $("#assoc-list-depend").modal('hide');
                }
            });
        });
    }
}

$('#alterarDepend').on('click', function (e) {
    // Remove to child from formAssocDependent for not repeat the same datas
    $("#formAssocDependent").empty();
    e.preventDefault();
    var id = new Array();
    $("input[type=checkbox][name=\'ckbSelecao[]\']:checked").each(function(){
        //toda vez que o checkbox for selecionado , paga-se o valor do mesmo
        id.push($(this).val());
        $("#tableDependent").hide();
        $("#alertDependent").hide();
        $("#deleteDependent").hide();
        $("#editModalPedent").hide();
        $('#pedentList').children().remove();

        if(id[0] > 0){
            $('#tableDependent').show();
            $("#hidDepAssocId").val(id[0]);
            $.ajax({
                url: domain + '/Associated/LoadDependents/'+ id[0],
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    // console.log(data);
                    var cols = "";
                    if(data != null){
                        $("#deleteDependent").show();
                        $("#editModalPedent").show();
                        $("#tableDependent").show();

                        cols = '<input type="hidden" name="hidDepAssocId" id="hidDepAssocId" value="'+data[0].assoc_codigoid+'">';

                        for (var i = 0; i < data.length; i++){
                            cols += '<tr class="warning">';
                            cols += '<td>'+ data[i].dep_nome+'</td>';
                            cols += '<td>'+ data[i].dep_fone+'</td>';
                            cols += '<td>'+ data[i].dep_rg+'</td>';
                            cols += '<td>'+ data[i].dep_cpf+'</td>';
                            cols += '<td><input type="checkbox"  value="'+data[i].dep_codigoid+'" name="checkDepId[]" class="btn btn-danger"></td>';
                            cols += '</tr>';
                        }

                        $('#pedentList').append(cols);

                        $('#modalDependent').append('');
                        cols = "";
                        data = "";
                    }else{
                        $("#alertDependent").show();
                        $("#tableDependent").hide();
                    }
                }
            });
        }else{
            swal("Atenção !", "Selecione apenas 1 registro por vez", "info");
        }
    });
});

var contDynamic = 1;

function createDependentsNew(){
    $("#loadNewDependets").append(
        '<div class="row newDependent" id="line' + contDynamic + '">' +
        '<div class="col-md-3"><div class="form-group">' +
        '<label  class="control-label">Nome</label>' +
        '<input type="text" class="form-control"  placeholder="Insira nome do dependente" name="dependente[]" ></div></div>' +
        '<div class="col-md-2"><div class="form-group">' +
        '<label class="control-label">Fone</label>' +
        '<input type="text" name="fone[]" class="form-control fonemask" placeholder="(00)00000-0000"></div></div>'+
        '<div class="col-md-2"><div class="form-group"><label  class="control-label">Rg</label>' +
        '<input type="text" class="form-control"  placeholder="Insira o Rg do dependente" name="associadoRg[]"></div></div>' +
        '<div class="col-md-3"><div class="form-group"><label  class="control-label">CPF</label> ' +
        '<input type="text" class="form-control cpfmask"   id="associacaoCPF" name="associacaoCPF[]" ></div></div>' +
        '<div class="col-md-2"><div class="form-group"><button type="button" class="btn btn-danger" style="margin-top: 25px;" onclick="removeDependentNew()"><i class="fa fa-close"></i></button>' +
        '</div></div></div>'
    );
    contDynamic++;
}
function removeDependentNew(){
    contDynamic--;    // console.log(contDynamic);
    $('#line'+contDynamic+'').remove();
}

$("#createNewDependent").on('click', function(){
    // console.log($('#hidDepAssocId').val());
    $.ajax({
        type: "POST",
        url: domain + "/Associated/CreateDependent/" + $('#hidDepAssocId').val(),
        data: $('#formCreateNew').serialize(),
        success: function(msg){
            console.log('msg ='+msg);
            if (msg === "success") {
                $('#createNewDependent').text('Salvar');
                $('#createNewDependent').attr('disabled',false);

                swal("Bom trabalho!", "Formulário cadastrado com sucesso!", "success");
                //remove children
                $("#loadNewDependets").children().remove();
                // reseta os dados do formulário
                $('#formCreateNew')[0].reset();
            }else{
                //Envia mensagem dos campos requiridos
                $('#createNewDependent').text('Salvar');
                //change button text
                $('#createNewDependent').attr('disabled',false);

                swal("Atenção !", msg, "info");
            }
        }
    });
});

$("#newPedent").on("click", function(e){
    // console.log('cliquei');
    $("#assoc-new-depend").modal('show');
    $("#assoc-list-depend").modal('hide');
});

$('#alterarAssoc').click(function(){
    $("#assocEditLoader").show();
    var id = new Array();
    $("input[type=checkbox][name=\'ckbSelecao[]\']:checked").each(function(){
        id.push($(this).val());
        if(id > 0){
            $.ajax({
                url : domain + '/Associated/LoadAssociated/'+id[0],
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    var dataFormatada = data[0].assoc_datanascimento.split('-');

                    dataFormatada = dataFormatada[2] +'/'+ dataFormatada[1] +'/'+ dataFormatada[0];

                    var dataFormatadaAtivacao = data[0].assoc_dataativacao.split('-');

                    dataFormatadaAtivacao = dataFormatadaAtivacao[2] +'/'+ dataFormatadaAtivacao[1] +'/'+ dataFormatadaAtivacao[0];
                    $("#hidAssocId").val(data[0].assoc_codigoid);
                    $("#nomeEdit").val(data[0].assoc_nome);
                    $("#identificacaoEdit").val(("0000000000" + data[0].assoc_identificacao).slice(-10));
                    $("#matriculaEdit").val(data[0].assoc_matricula);
                    $("#datanascimentoEdit").val(dataFormatada);
                    $("#cpfEdit").val(data[0].assoc_cpf);
                    $("#rgEdit").val(data[0].assoc_rg);
                    $("#sexoEdit").val(data[0].assoc_sexo);
                    $("#profissaoEdit").val(data[0].assoc_profissao);
                    $("#tipoAssociadoEdit").val(data[0].tipassoc_codigoid);
                    $("#emailEdit").val(data[0].assoc_email);
                    $("#classificacaoEdit").val(data[0].cla_codigoid);
                    $("#estadocivelEdit").val(data[0].assoc_estadocivil);
                    $("#foneEdit").val(data[0].assoc_fone);
                    $("#fone2Edit").val(data[0].assoc_fone2);
                    $("#fone3Edit").val(data[0].assoc_fone3);
                    $("#cepEdit").val(data[0].assoc_cep);
                    $("#enderecoEdit").val(data[0].assoc_endereco);
                    $("#complementoEdit").val(data[0].assoc_complemento);
                    $("#bairroEdit").val(data[0].assoc_bairro);
                    $("#ufEdit").val(data[0].assoc_uf);
                    $("#cidadeEdit").val(data[0].assoc_cidade);
                    $("#bancoEdit").val(data[0].assoc_banco);
                    $("#agenciaEdit").val(data[0].assoc_agencia);
                    $("#contaEdit").val(data[0].assoc_conta);
                    $("#dependenteEdit").val(data[0].assoc_conta);
                    $("#tipoagenteEdit").val(data[0].ag_codigoid);
                    $("#ativacaoEdit").val(dataFormatadaAtivacao);
                    setTimeout(function(){
                        $("#assocEditLoader").fadeOut()
                    },2000);},
                error: function (jqXHR, textStatus, errorThrown) {
                    swal("Atenção !", "Não foi possivel carregar o registro", "info");
                }
            });


            $("#assoc-edit-modal").modal();
        }else{
            swal("Atenção !", "Selecione apenas 1 registro por vez", "info");
        }
    });

    $("#pendenciaAssoc").on('click', function(e){
        e.preventDefault();
        $("#pendenciaAssocLoader").show();

        var id = new Array();
        $("input[type=checkbox][name=\'ckbSelecao[]\']:checked").each(function(){        //toda vez que o checkbox for selecionado , paga-se o valor do mesmo
            id.push($(this).val());
        });    //caso haja valor, então...

        if(id > 0){
            $('#convenioAssoc').children().remove();
            $.ajax({
                url:domain + "/Associated/ListParc/"+id,
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    var cols = "";
                    var classTr = "";
                    var newRow = $("<tr>");

                    for (var i = 0; i < data.length; i++) {
                        if(data[i]['par_status'] == "Pendente"){
                            classTr = "warning";
                        }else{
                            classTr ="danger";
                        }

                        var dateFormat = data[i].lanc_datavencimento.split("-");
                        cols = '<tr class="'+classTr+'">';
                        cols += '<td>'+data[i].con_nome +'</td>';
                        cols += '<td>'+data[i].par_numero +'</td>';
                        cols += '<td> R$ '+data[i].par_valor +',00</td>';
                        cols += '<td>'+dateFormat[2]+'/'+dateFormat[1]+'/'+dateFormat[0]+'</td>';
                        cols += '<td>'+data[i].par_status +'</td>';
                        cols += '</tr>';

                        // newRow.append(cols);
                        $("#convenioAssoc").append(cols);
                        cols = "";
                        classTr = "";
                    }

                    data="";
                    setTimeout(function(){ $("#pendenciaAssocLoader").fadeOut()},2000);
                    DataTablesRefresh();
                }
            });
        }
    });
    /*** Realiza UPDATE # no formulário de Alteração do Associado***/

    $("#editAssoc").on('click', function(e){
        e.preventDefault();
        var id = $('#hidAssocId').val();

        $.ajax({
            type: "POST",
            url: domain +'/Associated/UpdateAssoc/'+id,
            data: $("#formAssocEdit").serialize(),
            success: function(msg){
                if(msg === "sucess"){
                    //Envia a mensagem sweet alert
                    DataTablesRefresh();

                    swal("Bom trabalho!", "Formulário editado com sucesso!", "success");
                }else{
                    //Envia mensagem dos campos requiridos
                    swal("Atenção !", msg, "info");
                }
            }
        });
    });

    $('#removeAssoc').on('click', function(){
        // var host = window.location.host;
        var id = new Array();
        $("input[type=checkbox][name=\'ckbSelecao[]\']:checked").each(function(){        //toda vez que o checkbox for selecionado , paga-se o valor do mesmo
            id.push($(this).val());
        });    //caso haja valor, então...

        if(id > 0){
            // console.log('peguei codigo');
            swal({
                title: "Confirma?",
                text: "Após a confirmação o associado será removido.",
                type: "warning",
                showCancelButton: true,
                cancelButtonText:"Cancelar",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Sim, Remova!",
                closeOnConfirm: false
            },
                function(){            // console.log('cliquei no sim!');
                $.ajax({
                    type: "POST",
                    url:domain + "/Associated/RemoveAssoc/"+id,
                    success: function(data){                    // console.log('ok');
                        DataTablesRefresh();
                        swal("Remoção!", "Associado removido com sucesso.", "success");
                    }
                });
            });
        }else{
            swal("Atenção !", "Selecione apenas 1 registro", "info");    }
    });

$("#editModalPedent").on("click", function(){
    var id = new Array();

    $("input[type=checkbox][name=\'checkDepId[]\']:checked").each(function(){
        id.push($(this).val());
        $("#assoc-list-depend").modal("hide");
    });

    if(id[0] > 1 && id.length == 1){
        $("#hidEditPend").val(id[0]);
        $("#assoc-edit-depend").modal();

        $.ajax({
            url : domain + '/Associated/LoadDependent/'+id[0],
            type: "GET",
            dataType: "JSON",
            success: function(data){
                $("#dependenteEditNameEdit").val(data[0].dep_nome);
                $("#dependenteEditRGEdit").val(data[0].dep_rg);
                $("#dependenteFoneEdit").val(data[0].dep_fone);
                $("#associacaoCPFEdit").val(data[0].dep_cpf);
                $("#hidAssocCodigoID").val(data[0].assoc_codigoid);
            }
        });
    }else{
        swal("Atenção !", "Selecione apenas 1 registro", "info");
    }

    // console.log("teste do click no Edit!")
});

 $("#buttonEditPendentNew").on("click", function(e){
    e.preventDefault();

    $.ajax({
        type: "POST",
        url: domain + "/Associated/UpdateDependent",
        data: $('#formEditDependent').serialize(),
        success: function (msg) {
            if(msg === 'success'){
                swal("Atenção!", "Dependentes alterados com sucesso.", "success");
            }else{
                swal("Atenção !", msg, "info");
            }
        }
    });
});

    //Remove Dependent from assoc
$("#deleteDependent").on("click", function(e){
    e.preventDefault();
    var id = new Array();

    $("input[type=checkbox][name=\'checkDepId[]\']:checked").each(function(){

        id.push($(this).val());
        $("#assoc-list-depend").modal("hide");
    });

    if(id[0] > 1 && id.length == 1) {
        swal({
            title: "Confirma?",
            text: "Após a confirmação os Dependentes serão removidos.",
            type: "warning",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Sim, Remova!",
            closeOnConfirm: false
        },
        function () {
            $.ajax({
                type: "POST",
                url: domain + "/Associated/RemoveDependent/" + id[0],
                success: function (data) {
                    swal("Remoção!", "Associado removido com sucesso.", "success");
                }
            });
        });
    }else{
        swal("Atenção !", "Selecione apenas 1 registro", "info");
    }
});

