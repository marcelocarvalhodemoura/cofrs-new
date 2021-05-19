$(document).ready(function() {
    //load datables library
    var table = $('#associatetable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "/associates",
        columns: [
            {data: 'assoc_nome', name: 'assoc_nome'},
            {data: 'assoc_cpf', name: 'assoc_cpf'},
            {data: 'assoc_matricula', name: 'assoc_matricula'},
            {data: 'assoc_fone', name: 'assoc_fone'},
            {data: 'assoc_cidade', name: 'assoc_cidade'},
            {data: 'tipassoc_codigoid', name: 'tipassoc_codigoid'},
            {data: 'assoc_ativosn', name: 'assoc_ativosn'},
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
                "sFirst": "Primeiro",
                "sPrevious": "Anterior",
                "sNext": "Seguinte",
                "sLast": "Último"
            }
        },
    });
});
