/*
=========================================
|                                       |
|           Scroll To Top               |
|                                       |
=========================================
*/ 
$('.scrollTop').click(function() {
    $("html, body").animate({scrollTop: 0});
});


$('.navbar .dropdown.notification-dropdown > .dropdown-menu, .navbar .dropdown.message-dropdown > .dropdown-menu ').click(function(e) {
    e.stopPropagation();
});

/*
=========================================
|                                       |
|       Multi-Check checkbox            |
|                                       |
=========================================
*/

function checkall(clickchk, relChkbox) {

    var checker = $('#' + clickchk);
    var multichk = $('.' + relChkbox);


    checker.click(function () {
        multichk.prop('checked', $(this).prop('checked'));
    });    
}


/*
=========================================
|                                       |
|           MultiCheck                  |
|                                       |
=========================================
*/

/*
    This MultiCheck Function is recommanded for datatable
*/

function multiCheck(tb_var) {
    tb_var.on("change", ".chk-parent", function() {
        var e=$(this).closest("table").find("td:first-child .child-chk"), a=$(this).is(":checked");
        $(e).each(function() {
            a?($(this).prop("checked", !0), $(this).closest("tr").addClass("active")): ($(this).prop("checked", !1), $(this).closest("tr").removeClass("active"))
        })
    }),
    tb_var.on("change", "tbody tr .new-control", function() {
        $(this).parents("tr").toggleClass("active")
    })
}

/*
=========================================
|                                       |
|           MultiCheck                  |
|                                       |
=========================================
*/

function checkall(clickchk, relChkbox) {

    var checker = $('#' + clickchk);
    var multichk = $('.' + relChkbox);


    checker.click(function () {
        multichk.prop('checked', $(this).prop('checked'));
    });    
}

/*
=========================================
|                                       |
|               Tooltips                |
|                                       |
=========================================
*/

$('.bs-tooltip').tooltip();

/*
=========================================
|                                       |
|               Popovers                |
|                                       |
=========================================
*/

$('.bs-popover').popover();


/*
================================================
|                                              |
|               Rounded Tooltip                |
|                                              |
================================================
*/

$('.t-dot').tooltip({
    template: '<div class="tooltip status rounded-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
})


/*
================================================
|            IE VERSION Dector                 |
================================================
*/

function GetIEVersion() {
  var sAgent = window.navigator.userAgent;
  var Idx = sAgent.indexOf("MSIE");

  // If IE, return version number.
  if (Idx > 0) 
    return parseInt(sAgent.substring(Idx+ 5, sAgent.indexOf(".", Idx)));

  // If IE 11 then look for Updated user agent string.
  else if (!!navigator.userAgent.match(/Trident\/7\./)) 
    return 11;

  else
    return 0; //It is not IE
}

/*
=========================================
|                                       |
|           money to float              |
|                                       |
=========================================
*/ 

function campo6_10Up(valor) {
    // transforma para numero
    valor = formataNumero(valor);
    return valor;    
}
function formataNumero(n) {
    return n.replace(/[^\d,]/g, '').replace(",", ".");
}



function marcaMenu(){
    const raiz = window.location.origin;
    const pagina = window.location.href.substring(raiz.length);
    //console.log(pagina);
    //marca o menu
    $('a[href="'+pagina+'"]').closest('li').addClass('active');
    $('a[href="'+pagina+'"]').closest('li.menu').addClass('active');
    $('a[href="'+pagina+'"]').closest('li.menu').attr('aria-expanded', 'true');
    $('a[href="'+pagina+'"]').closest('ul.submenu').addClass('show');
    //gera a rolagem no menu lateral
    $('.sidebar-wrapper').each(function(){ const ps = new PerfectScrollbar($(this)[0]); });
}


$(window).on('load', function () {
    marcaMenu();
    /*
    Busca e monta o menu de notificações
    */
    $.getJSON('/aAlerts', function(data) {
        // se não existir novos alertas, não marca em vermelho o badge
        if(data == ''){
            $("#notification-badge").css('border-color','#445ede').css('background','#445ede');
        } else {
            $("#notification-badge").css('border-color','red').css('background','red');
        }

        // popula a lista de alertas
        $.each(data, function(index, element) {
            let dateF = element.date.split(' ');
            dateF[0] = dateF[0].split('-').reverse().join('/');

            if(element.tipo == 1){
                svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgba(0, 150, 136, 0.3686274509803922)" stroke="#009688" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6" y2="6"></line><line x1="6" y1="18" x2="6" y2="18"></line></svg>';
            } else if (element.tipo == 2) {
                svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#e7f7ff" stroke="#2196f3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>';
            }

            $('#notification-list').append(
                '<div class="dropdown-item ver-alerta" data-id="'+element.id+'">'
                + ' <div class="media">'
                + '     ' + svg
                + '          <div class="media-body">'
                + '             <div class="data-info">'
                + '                 <h6 class="">'+element.titulo+'</h6>'
                + '                 <p class="">'+dateF[0]+' '+dateF[1]+'</p>'
                + '          </div>'
                + '     </div>'
                + '</div>'
            )
        });

        $('#notification-list').append(
            '<div class="dropdown-item" onclick="meusAlertas()">'
            + ' <div class="media">'
            + '     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgba(0, 150, 136, 0.3686274509803922)" stroke="#009688" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>'
            + '          <div class="media-body">'
            + '             <div class="data-info">'
            + '                 <h6 class="">Ver todos</h6>'
            + '          </div>'
            + '     </div>'
            + '</div>'
        );

        // abre o modal de leitura da notificação
        $('.ver-alerta').on('click', function() {
            verAlerta($(this).data('id'));
        });
    });

    
});//fim onload


function verAlerta(id, marcaLeitura = 0) {
            $.getJSON('/aVerAlerta', {'id':id}, function(dataAlerta) {
                dateF = dataAlerta.date.split(' ');
                dateF[0] = dateF[0].split('-').reverse().join('/');

                $('#alertaModal .modal-title').html(dataAlerta.titulo);
                $('#alertaModal .modal-body').html('');
                $('#alertaModal .modal-body').append('<p><strong>Autor: </strong>'+dataAlerta.autor+' | <strong>Data: </strong>'+dateF[0]+' '+dateF[1]+'</p>');
                $('#alertaModal .modal-body').append(dataAlerta.texto);

                $('#alertaModal').modal('show');

                if(dataAlerta.visto == null && marcaLeitura == 0){
                    $.ajax({
                        url: '/aVisualizado/?id_alerta='+dataAlerta.id,
                        method: "GET",
                        processData: false,
                        contentType: false,
                    });
                }
            });

}

function meusAlertas(){
    window.location.href = '/meus-alertas';
}

function br2nl (str, replaceMode) {   
    var replaceStr = (replaceMode) ? "\n" : '';
    // Includes <br>, <BR>, <br />, </br>
    return str.replace(/<\s*\/?br\s*[\/]?>/gi, replaceStr);
  }