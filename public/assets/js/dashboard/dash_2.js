try {


  $('.widget-four .widget-content .browser-list').slice(0, 50).each(function() {

    var hue = Math.floor(Math.random() * 360);
    var corPastel = 'hsl(' + hue + ', 70%, 90%)';
    $(this).find('.w-icon').css('background-color', corPastel);
  });

  /*
      =============================================
          Perfect Scrollbar | Notifications
      =============================================
  */
      $('.mt-container').each(function(){ const ps = new PerfectScrollbar($(this)[0]); });
  

} catch (e) {
  // statements
  console.log(e);
}

function nAverbadosModal() {
  $.blockUI({
    fadeIn: 800,
    message: '<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>',
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

  $.ajax({
    method: 'POST',
    url: '/dashboard/aNAverbados',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    //data: data,
    processData: false,
    contentType: false,
    success: function (response) {
        //console.log(response);
        let html = '';
        for(var k in response) {
          //console.log(k, response[k]);
          html += '<tr>'+
                    '<td>'+response[k].assoc_nome+'</td>'+
                    '<td>'+response[k].lanc_contrato+'</td>'+
                  '</tr>';
       }

        $("#nAverbadosModal #tableNAverbados tbody").html(html);
        $.unblockUI();
        $("#nAverbadosModal").modal('show');
    }
});

  
}