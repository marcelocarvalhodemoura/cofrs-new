$(document).ready(function () {

  var table = $('#table').DataTable({
    dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>" +
      "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
      "<'table-responsive'tr>" +
      "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
    processing: true,
    serverSide: true,
    ajax: "/banks",
    columns: [
      { data: 'name_bank', name: 'name_bank' },
      { data: 'febraban_code', name: 'febraban_code' },
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

  /**
   * Form add 
   */
  $("#formItem").validate({
    rules: {
      name_bank: "required",
    },
    messages: {
      name_bank: "Banco é um campo obrigatório",
    },
    errorElement: "span",
    highlight: function () {
      $("#formItem").addClass("was-validated");
    },
    unhighlight: function () {
      $("#formItem").addClass("was-validated");
    },
    submitHandler: function () {
      $.ajax({
        url: '/banks/store',
        method: 'POST',
        data: $('#formItem').serialize(),
        success: function (data) {

          if (data.status === 'success') {

            table.ajax.reload();

            $('#userFormModal').modal('hide');

            swal({
              title: 'Bom trabalho!',
              text: "Formulário salvo com sucesso",
              type: 'success',
            });

            $('#formItem')[0].reset();
          }
        }

      });
    }
  });

  /**
   * Form edit 
   */
  $("#formItemEdit").validate({
    rules: {
      name_bank: "required",
    },
    messages: {
      name_bank: "Banco é um campo obrigatório",
    },
    errorElement: "span",
    highlight: function () {
      $("#formItemEdit").addClass("was-validated");
    },
    unhighlight: function () {
      $("#formItemEdit").addClass("was-validated");
    },
    submitHandler: function () {
      $.ajax({
        url: '/banks/store',
        method: 'POST',
        data: $('#formItemEdit').serialize(),
        success: function (data) {

          if (data.status === 'success') {

            table.ajax.reload();

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

  /**
   * Validate checkbox in the datatables
   *
   * @type {any[]}
   */
  $('#btnEdit').on('click', function (e) {

    var id = new Array();

    $("input[type=checkbox][name=\'actionCheck[]\']:checked").each(function () {
      //get value in the input
      id.push($(this).val());
      //alert(usuarioMarcados);
    });

    //validate if exist value
    if (id > 0) {
      $.ajax({
        url: '/banks/load/' + id,
        method: 'GET',
        success: function (response) {

          $('input#name_bank.form-control').val(response[0].name_bank);
          $('input#febraban_code.form-control').val(response[0].febraban_code);

          $('#formItemEdit').append('<input type="hidden" id="id" name="id" value="' + response[0].id + '"/>');
        }
      });
      $('#editModal').modal()
    } else {
      swal({
        title: "Atenção !",
        text: "Selecione apenas 1 registro por vez",
        type: "info",
        confirmButtonClass: 'btn btn-primary',
      });
    }

  });





});