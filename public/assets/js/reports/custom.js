$(document).ready(function () {

  var f3 = flatpickr(document.getElementById('periodo'), {
    mode: "range",
    onChange: function (selectedDates, datestr) {
      $("#periodo").val(this.formatDate(selectedDates[0], "d/m/Y") + ' a ' + this.formatDate(selectedDates[1], "d/m/Y"));
    }
  });

	$.validator.setDefaults({
		debug: false,//impede de enviar caso for true
		errorElement: "em",
		errorPlacement: function (error, element) {
		},
		highlight: function (element, errorClass, validClass) {
			$(element).addClass("is-invalid");//.removeClass( "is-valid" );
			$(element).parents('.form-group').addClass("text-danger");
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element).removeClass("is-invalid");//.addClass( "is-valid" )
			$(element).parents('.form-group').removeClass("text-danger");
		},
		ignore: ':disabled, [type="search"]',
	});

  jQuery.validator.addMethod("cpf", function (value, element) {
		value = jQuery.trim(value);

		value = value.replace('.', '');
		value = value.replace('.', '');
		cpf = value.replace('-', '');
		while (cpf.length < 11) cpf = "0" + cpf;
		var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
		var a = [];
		var b = new Number;
		var c = 11;
		for (i = 0; i < 11; i++) {
			a[i] = cpf.charAt(i);
			if (i < 9) b += (a[i] * --c);
		}
		if ((x = b % 11) < 2) { a[9] = 0 } else { a[9] = 11 - x }
		b = 0;
		c = 11;
		for (y = 0; y < 10; y++) b += (a[y] * c--);
		if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11 - x; }

		var retorno = true;
		if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]) || cpf.match(expReg)) retorno = false;

		return this.optional(element) || retorno;

	}, "Informe um CPF válido");

  $('#reporttable').DataTable().destroy();
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
  if($("#reportFilter").valid()){
    $.blockUI({
      message: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-loader spin"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>',
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
      dataType: 'json',
      success: function (response) {
          console.log(response);
          $.unblockUI();

          if(response.erro){
            swal({
              title: "Nenhum resultado encontrado",
              text: "Verifique os filtros utilizados.",
              type: "warning",
              confirmButtonClass: 'btn btn btn-primary',
              cancelButtonClass: 'btn btn-alert mr-3',
              buttonsStyling: false,
              showCancelButton: false,
              confirmButtonText: "Ok",
              closeOnConfirm: false
            });
          } else {
            console.log('ok');
            $("#reportModal").modal('show');

          }
      }
    });
  } else {
    //alert('Verifique os campos - trocar mensagem');
  }

  
  

}