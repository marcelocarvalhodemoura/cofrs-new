$(document).ready(function () {

  var table = $('#table').DataTable({
    dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l>" +
      "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
      "<'table-responsive'tr>" +
      "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
    processing: true,
    serverSide: true,
    ajax: "/Cashflow",
    columnDefs: [
      { type: 'date-br', targets: 4 }
    ],
    columns: [
      { data: 'banco', name: 'banco' },
      { data: 'count', name: 'count' },
      { data: 'descricao', name: 'descricao' },
      {
        data: 'data_vencimento', name: 'data_vencimento', render: function (data) {
          dt = new Date(data);
          return dt.toLocaleDateString('pt-BR', { timeZone: 'UTC' });
        }
      },
      {
        data: 'credito', name: 'credito', render: function (data) {
          if (data == 1) {
            return 'Crédito';
          } else {
            return 'Débito';
          }
        }
      },
      {
        data: 'valor', name: 'valor', className: "text-right", render(data) {
          return "R$ " + formataNumero(data);
        }
      },
      {
        data: 'est_nome', name: 'est_nome', render: function (data, type) {
          if (data == 'Pago') {
            bg = 'bg-success';
          } else if (data == 'Pendente') {
            bg = 'bg-warning text-dark';
          } else if (data == 'Transferido') {
            bg = 'bg-info text-dark';
          } else if (data == 'Cancelado') {
            bg = 'bg-secondary';
          } else if (data == 'Atrasado') {
            bg = 'bg-danger';
          }
          return '<span class="badge ' + bg + '">' + data + '</span>';
        }
      },
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

  $('.money').maskMoney({ allowNegative: true, allowZero: true, allowEmpty: true, thousands: '.', decimal: ',' });

  $(".calendar").datepicker({
    dateFormat: 'dd/mm/yy',
    dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'],
    dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez']
  });

  jQuery.extend(jQuery.fn.dataTableExt.oSort, {
    "date-br-pre": function (a) {
      if (a == null || a == "") {
        return 0;
      }
      var brDatea = a.split('/');
      return (brDatea[2] + brDatea[1] + brDatea[0]) * 1;
    },

    "date-br-asc": function (a, b) {
      return ((a < b) ? -1 : ((a > b) ? 1 : 0));
    },

    "date-br-desc": function (a, b) {
      return ((a < b) ? 1 : ((a > b) ? -1 : 0));
    }
  });
  /**
   * Form add
   */
  $("#formItem").validate({
    rules: {
      id_conta: "required",
      id_estatus: "required",
      descricao: "required",
      data_vencimento: "required",
      valor: "required",
    },
    messages: {
      id_conta: "Conta é um campo obrigatório",
      id_estatus: "Status é um campo obrigatório",
      descricao: "Descrição é um campo obrigatório",
      data_vencimento: "Vencimento é um campo obrigatório",
      valor: "Valor é um campo obrigatório",
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
        url: '/Cashflow/store',
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
      id_conta: "required",
      id_estatus: "required",
      descricao: "required",
      data_vencimento: "required",
      valor: "required",
    },
    messages: {
      id_conta: "Conta é um campo obrigatório",
      id_estatus: "Status é um campo obrigatório",
      descricao: "Descrição é um campo obrigatório",
      data_vencimento: "Vencimento é um campo obrigatório",
      valor: "Valor é um campo obrigatório",
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
        url: '/Cashflow/store',
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
        url: '/Cashflow/load/' + id,
        method: 'GET',
        success: function (response) {

          dt = new Date(response[0].data_vencimento);

          $('#formItemEdit #id_conta').val(response[0].id_conta);
          $('#formItemEdit #id_estatus').val(response[0].id_estatus);
          $('#formItemEdit #descricao').val(response[0].descricao);
          $('#formItemEdit #data_vencimento').val(dt.toLocaleDateString('pt-BR', { timeZone: 'UTC' }));
          $('#formItemEdit #credito').val(response[0].credito);
          $('#formItemEdit #valor').val(response[0].valor);

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

function formataNumero(numero) {
  var negativo = numero < 0 ? "-" : "";
  var i = parseInt(numero = Math.abs(+ numero || 0).toFixed(2), 10) + "";
  var j = (j = i.length) > 3 ? j % 3 : 0;

  return negativo + (j ? i.substr(0, j) + "." : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + ".") + (2 ? "," + Math.abs(numero - i).toFixed(2).slice(2) : "");
}
