//load datatables
$(document).ready(function(){

    var table = $('#usertable').DataTable({
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

    $('#btnSave').on('click', (event)=>{
        event.preventDefault();
        $.ajax({
            url:'/users/store',
            method:'POST',
            data: $('#formUser').serialize(),
            success: function(data){

                if(data.status === 'success'){

                    $('#userFormModal').modal('hide');
                    swal({
                        title: 'Bom trabalho!',
                        text: "Formulário salvo com sucesso",
                        type: 'success',
                        padding: '2em'
                    });
                }
            }

        });

    });
});


