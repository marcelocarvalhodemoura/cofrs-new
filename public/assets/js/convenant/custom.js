$(document).ready(function(){
    //load select
    $(".basic").select2({
        tags: true,
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
