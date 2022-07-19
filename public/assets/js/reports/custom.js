$(document).ready(function () {

  var f3 = flatpickr(document.getElementById('periodo'), {
    mode: "range",
    onChange: function (selectedDates, datestr) {
      $("#periodo").val(this.formatDate(selectedDates[0], "d/m/Y") + ' a ' + this.formatDate(selectedDates[1], "d/m/Y"));
    }
  });

  $("#reporttable").DataTable( {
      dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'B>" +
          "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
          "<'table-responsive'tr>" +
          "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
      buttons: [
          { extend: 'csv', className: 'btn btn-sm' },
          { extend: 'excel', className: 'btn btn-sm' },
          { extend: 'pdf', className: 'btn btn-sm' }
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

});

function buscar(){
  var data = new FormData(reportFilter);
  
  $.ajax({
    method: 'POST',
    url: '/aReport',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: data,
    processData: false,
    contentType: false,
    success: function (response) {
        console.log(response);
        
    }
});

}