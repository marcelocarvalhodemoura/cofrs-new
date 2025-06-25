$('.form-content').fadeIn('slow');
$('#username').focus();

var togglePassword = document.getElementById("toggle-password");

if (togglePassword) {
  togglePassword.addEventListener('click', function () {
    var x = document.getElementById("password");
    if (x.type === "password") {
      x.type = "text";
    } else {
      x.type = "password";
    }
  });
}

$('#formAuth').on('submit', event => {
  event.preventDefault();

  $.ajax({
    method: "POST",
    url: "/auth",
    data: $("#formAuth").serialize(),
    success: response => {

      if (response.status == 'error') {

        const msg = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Atenção!</strong> ' + response.msg +
          '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
          '<span aria-hidden="true">&times;</span>' +
          '</button>' +
          '</div>';

        $('#formAuth').prepend(msg);

      } else {
        /*
        console.log(response.typeId);
        */
        if(response.typeId == 4){//se for operador
          window.location.href = '/associates';
        } else {
          window.location.href = '/dashboard';
        }
      }

    }
  });

  
});


$('#formRecover').on('submit', event => {
  event.preventDefault();

  $.ajax({
    method: "POST",
    url: "/recovery",
    data: $("#formRecover").serialize(),
    success: response => {

      if (response.status == 'error') {

        const msg = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Atenção!</strong> ' + response.msg +
          '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
          '<span aria-hidden="true">&times;</span>' +
          '</button>' +
          '</div>';

        $('#formRecover').prepend(msg);

      } else {
        swal({
            title: 'Sucesso!',
            text: 'Sua senha nova foi enviada para o e-mail associado a este usuário.',
            type: response.status,
            confirmButtonClass: 'btn btn-success',
        }).then(function (result) {
            window.location.href = '/';
        });
      }

    }
  });

  
});




