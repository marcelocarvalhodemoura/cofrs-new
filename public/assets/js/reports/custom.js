$(document).ready(function () {

  const fp = flatpickr("#periodo", {  
      mode: "range", 
      locale: {
        weekdays: {
          shorthand: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
          longhand: [
            "Domingo",
            "Segunda-feira",
            "Terça-feira",
            "Quarta-feira",
            "Quinta-feira",
            "Sexta-feira",
            "Sábado",
          ],
        },
      
        months: {
          shorthand: [
            "Jan",
            "Fev",
            "Mar",
            "Abr",
            "Mai",
            "Jun",
            "Jul",
            "Ago",
            "Set",
            "Out",
            "Nov",
            "Dez",
          ],
          longhand: [
            "Janeiro",
            "Fevereiro",
            "Março",
            "Abril",
            "Maio",
            "Junho",
            "Julho",
            "Agosto",
            "Setembro",
            "Outubro",
            "Novembro",
            "Dezembro",
          ],
        },
        rangeSeparator: " a "
      },
      onChange: function (selectedDates, dateStr, instance) { 
        setTimeout(() => {
          $("#periodo").val(this.formatDate(selectedDates[0], "d/m/Y") + ' a ' + this.formatDate(selectedDates[1], "d/m/Y"));
        },100);
      }, 
  });

  $('#cpf').inputmask("999.999.999-99");

  $.extend($.fn.dataTable.defaults, {
    "lengthMenu": [[10, 50, 100, 500, -1], [10, 50, 100, 500, "All"]],
    "pageLength": 50,
    "language": {
      "url": "/plugins/table/datatable/datatables.pt_br.json"
    },
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
            montaTabela(response.tabela, $("#typeReport").val());
            $("#reportModal h4").html(response.cabecalho);
            $("#reportModal").modal('show');
          }
      }
    });
  } else {
    //alert('Verifique os campos - trocar mensagem');
  }
}

function montaTabela(dataSet,typeReport){

  if ($.fn.DataTable.isDataTable("#reporttable")) {
    $('#reporttable').DataTable().clear().destroy();
  }
  $("#reporttable tbody tr").remove();
  var tr2;

  if(typeReport == 'associate'){
    dataSet.map((value,index) => {
      let vencimento = new Date(value.vencimento);

      tr2 = `<tr>
        <td>${value.convenio}</td>
        <td>${value.classificacao}</td>
        <td align="center" data-order="${value.vencimento}">${vencimento.toLocaleDateString("pt-BR")}</td>
        <td align="center">${value.parcela}</td>
        <td align="center">${value.equivalencia}</td>
        <td align="center">${value.quantidade}</td>
        <td>${value.contrato}</td>
        <td align="right">${value.valor}</td>
        <td align="center">${value.status}</td>
      </tr>`;
      $("#reporttable tbody").append(tr2);
    });
  }

  if(typeReport == 'agreement'){
  }

  if(typeReport == 'covenant'){
  }

  if(typeReport == 'cashflow'){
  }

  console.log($("#reportModal .modal-body h4").text());

  $("#reporttable").DataTable({
    dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'B>" +
    "<'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'f>>>" +
    "<'table-responsive'tr>" +
    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count  mb-sm-0 mb-3'i><'dt--pagination'p>>",
    destroy: true,
    deferRender: true,
    buttons: [
      { extend: 'csv', className: 'btn btn-sm' },
      { extend: 'excel', className: 'btn btn-sm' },
      { extend: 'pdfHtml5', 
        className: 'btn btn-sm',
        orientation: 'landscape',
        pageSize: 'A4',
        messageTop: function() {
          return $("#reportModal .modal-body h4").text();
        },
        customize: function(doc){
          doc.content.splice(0,1);
          doc.pageMargins = [20,50,0,40];
          var now = new Date();
					var jsDate = now.getDate()+'/'+(now.getMonth()+1)+'/'+now.getFullYear();
          doc['header'] = (function(){
            return {
              columns: [
                {
                  alignment: 'left',
                  fontSize: 18,
                  margin: [20,20,0,0],
                  text: 'Sistema COFRS'
                }
              ]
            }
          });
          doc['footer'] = function(page, pages) { 
            return {
              columns: [
                {
                  alignment: 'left',
                  text: ['Criado em: ', { text: jsDate.toString() }],
                  margin: [20,0,0,20],
                },
                {
                  alignment: 'right',
                  text: ['Página ', { text: page.toString() },	'/',	{ text: pages.toString() }],
                  margin: [0,0,40,20],
                }
              ],
            } 
          }
        }
      }
    ],
  });
}