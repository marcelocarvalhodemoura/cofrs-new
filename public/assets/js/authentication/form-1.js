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
$('form').on('click', event =>{
    event.preventDefault();

    $.ajax({
        method:"POST",
        url:"/auth",
        data:$(this).serialize(),
        success: response => {
            console.log(response);
        }
    });

    //window.location.href = '/dashboard'
});



