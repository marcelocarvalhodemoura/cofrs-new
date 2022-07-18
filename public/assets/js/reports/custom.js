$(document).ready(function () {

  var f3 = flatpickr(document.getElementById('periodo'), {
    mode: "range",
    onChange: function (selectedDates, datestr) {
      $("#periodo").val(this.formatDate(selectedDates[0], "d/m/Y") + ' a ' + this.formatDate(selectedDates[1], "d/m/Y"));
    }
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