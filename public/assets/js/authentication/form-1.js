$('.form-content').fadeIn('slow');

var togglePassword = document.getElementById("toggle-password");

if (togglePassword) {
	togglePassword.addEventListener('click', function() {
	  var x = document.getElementById("password");
	  if (x.type === "password") {
	    x.type = "text";
	  } else {
	    x.type = "password";
	  }
	});
}
$('#formAuth').on('submit', event =>{
    event.preventDefault();

    $.ajax({
        method:"POST",
        url:"/auth",
        data:$("#formAuth").serialize(),
        success: response => {
            console.log(response.status);
            if( response.status == 'error' ){
                const msg = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>Atenção!</strong> ' + response.msg +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span>' +
                    '</button>' +
                    '</div>';

                $('#formAuth').append(msg);
            }else{
                window.location.href = '/dashboard';
            }

        }
    });


});



