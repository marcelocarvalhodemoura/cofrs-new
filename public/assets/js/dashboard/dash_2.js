try {


   /*
      ==============================
          Statistics | Options
      ==============================
  */

  // Followers

  var d_1options3 = {
    chart: {
      id: 'sparkline1',
      type: 'area',
      height: 160,
      sparkline: {
        enabled: true
      },
    },
    stroke: {
      curve: 'smooth',
      width: 2,
    },
    series: [{
      name: 'Sales',
      data: [38, 60, 38, 52, 36, 40, 28]
    }],
    labels: ['1', '2', '3', '4', '5', '6', '7'],
    yaxis: {
      min: 0
    },
    colors: ['#1b55e2'],
    tooltip: {
      x: {
        show: false,
      }
    },
    fill: {
      type: "gradient",
      gradient: {
        type: "vertical",
        shadeIntensity: 1,
        inverseColors: !1,
        opacityFrom: .40,
        opacityTo: .05,
        stops: [45, 100]
      }
    },
  }

  // Referral

  var d_1options4 = {
    chart: {
      id: 'sparkline1',
      type: 'area',
      height: 160,
      sparkline: {
        enabled: true
      },
    },
    stroke: {
      curve: 'smooth',
      width: 2,
    },
    series: [{
      name: 'Sales',
      data: [60, 28, 52, 38, 40, 36, 38]
    }],
    labels: ['1', '2', '3', '4', '5', '6', '7'],
    yaxis: {
      min: 0
    },
    colors: ['#e7515a'],
    tooltip: {
      x: {
        show: false,
      }
    },
    fill: {
      type: "gradient",
      gradient: {
        type: "vertical",
        shadeIntensity: 1,
        inverseColors: !1,
        opacityFrom: .40,
        opacityTo: .05,
        stops: [45, 100]
      }
    },
  }

  // Engagement Rate

  var d_1options5 = {
    chart: {
      id: 'sparkline1',
      type: 'area',
      height: 160,
      sparkline: {
        enabled: true
      },
    },
    stroke: {
      curve: 'smooth',
      width: 2,
    },
    fill: {
      opacity: 1,
    },
    series: [{
      name: 'Sales',
      data: [28, 50, 36, 60, 38, 52, 38]
    }],
    labels: ['1', '2', '3', '4', '5', '6', '7'],
    yaxis: {
      min: 0
    },
    colors: ['#8dbf42'],
    tooltip: {
      x: {
        show: false,
      }
    },
    fill: {
      type: "gradient",
      gradient: {
        type: "vertical",
        shadeIntensity: 1,
        inverseColors: !1,
        opacityFrom: .40,
        opacityTo: .05,
        stops: [45, 100]
      }
    },
  }




  /*
      ==============================
      |    @Render Charts Script    |
      ==============================
  */


  /*
      ===================================
          Unique Visitors | Script
      ===================================
  */

  var d_1C_3 = new ApexCharts(
    document.querySelector("#uniqueVisits"),
    d_1options1
  );
  d_1C_3.render();

  /*
      ==============================
          Statistics | Script
      ==============================
  */





  /*
      =============================================
          Perfect Scrollbar | Notifications
      =============================================
  */
  const ps = new PerfectScrollbar(document.querySelector('.mt-container'));


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