//load datatables
$(document).ready(function () {
    //load datables library
    table = $('#alerttable').DataTable({
        dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>" +
            "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
            "<'table-responsive'tr>" +
            "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
        processing: true,
        serverSide: true,
        ajax: "/meus-alertas",
        columns: [
            { data: 'titulo', name: 'titulo' },
            { data: 'autor', name: 'autor' },
            { data: 'titulo', name: 'titulo' },
            { data: 'date', name: 'date' },
            { data: 'tipo', name: 'tipo' },
            { data: 'visto', name: 'visto' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
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
                "sPrevious": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-arrow-left\"><line x1=\"19\" y1=\"12\" x2=\"5\" y2=\"12\"></line><polyline points=\"12 19 5 12 12 5\"></polyline></svg>",
                "sNext": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-arrow-right\"><line x1=\"5\" y1=\"12\" x2=\"19\" y2=\"12\"></line><polyline points=\"12 5 19 12 12 19\"></polyline></svg>"
            }
        },
    });

    table2 = $('#listalert').DataTable({
      dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>" +
          "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
          "<'table-responsive'tr>" +
          "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
      processing: true,
      serverSide: true,
      ajax: "/alertas",
      columns: [
          { data: 'titulo', name: 'titulo' },
          { data: 'autor', name: 'autor' },
          { data: 'titulo', name: 'titulo' },
          { data: 'date', name: 'date' },
          { data: 'tipo', name: 'tipo' },
          { data: 'action', name: 'action', orderable: false, searchable: false },
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
              "sPrevious": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-arrow-left\"><line x1=\"19\" y1=\"12\" x2=\"5\" y2=\"12\"></line><polyline points=\"12 19 5 12 12 5\"></polyline></svg>",
              "sNext": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-arrow-right\"><line x1=\"5\" y1=\"12\" x2=\"19\" y2=\"12\"></line><polyline points=\"12 5 19 12 12 19\"></polyline></svg>"
          }
      },
  });

    $('.dataTables_filter input[type="search"]').css({'width':'450px','display':'inline-block'});

    table.on( 'draw', function () {
      $('.bs-popover').popover({
        placement:'top',
        container: 'body',
        trigger: 'hover',
      });
  });

    table2.on( 'draw', function () {
      $('.bs-popover').popover({
          placement:'top',
          container: 'body',
          trigger: 'hover',
        });
    });
          

}); // fim do document ready

$("#btnAddAlert").on('click', (event) => {
    $("#createModal").modal('show');
});

$("#formItemAdd").validate({
    errorElement: "span",
    highlight: function () {
      $("#formItemAdd").addClass("was-validated");
    },
    unhighlight: function () {
      $("#formItemAdd").addClass("was-validated");
    },
    submitHandler: function () {
      $.ajax({
        url: '/alertas/store',
        method: 'POST',
        data: $('#formItemAdd').serialize(),
        success: function (data) {

          if (data.status === 'success') {

            table2.ajax.reload();

            $('#createModal').modal('hide');

            swal({
              title: 'Bom trabalho!',
              text: "Formulário salvo com sucesso",
              type: 'success',
            });

            $('#formItemAdd')[0].reset();
          }
        }

      });
    }
  });



function editAlerta(id){
    $('#formItemEdit')[0].reset();

    $.getJSON('/aVerAlerta', {'id':id}, function(dataAlerta) {
          $('#formItemEdit #titulo').val(dataAlerta.titulo);
          $('#formItemEdit #texto').val(br2nl(dataAlerta.texto));
          $('#formItemEdit #autor').val(dataAlerta.autor);
          $('#formItemEdit').append('<input type="hidden" id="id" name="id" value="' + dataAlerta.id + '"/>');
    })

    $.getJSON('/aUsersAlert', {'id_alert':id}, function(dataAlerta) {
        $.each(dataAlerta, function(i, item) {
            $('#formItemEdit input[name="users[]"][value="' + item.id_user + '"]').prop('checked', true);
        });
    });

    $('#editModal').modal();
}

$("#formItemEdit").validate({
    errorElement: "span",
    highlight: function () {
      $("#formItemEdit").addClass("was-validated");
    },
    unhighlight: function () {
      $("#formItemEdit").addClass("was-validated");
    },
    submitHandler: function () {
      $.ajax({
        url: '/alertas/store',
        method: 'POST',
        data: $('#formItemEdit').serialize(),
        success: function (data) {

          if (data.status === 'success') {

            table2.ajax.reload();

            $('#editModal').modal('hide');

            swal({
              title: 'Bom trabalho!',
              text: "Formulário salvo com sucesso",
              type: 'success',
            });

            $('#formItemEdit')[0].reset();
          }
        }

      });
    }
  });

  function quemViu(id_alert){
    $.getJSON('/aQuemViu', {'id_alert':id_alert}, function(dataAlerta) {
        $('#alertaModal .modal-title').html("Quem viu o alerta");
        $('#alertaModal .modal-body').html('');

        html = '<div class="table-responsive">'
                +'<table class="table table-bordered mb-4">'
                +'   <thead>'
                +'      <tr>'
                +'         <th>Name</th>'
                +'         <th>Visto em</th>'
                +'      </tr>'
                +'   </thead>'
                +'   <tbody>';

        $.each(dataAlerta, function(i, item) {
            if(item.lido){
                dateF = item.lido.split(' ');
                dateF[0] = dateF[0].split('-').reverse().join('/');
                dateFinal = dateF[0]+' '+dateF[1];
            } else {
                dateFinal = 'Não lido';
            }
            html += '<tr>'
                    +'  <td>'+item.nome+'</td>'
                    +'  <td>'+dateFinal+'</td>'
                    +'</tr>';
        });

        html += '</tbody></table></div>';

        $('#alertaModal .modal-body').append(html);

        $('#alertaModal').modal('show');
    });
}