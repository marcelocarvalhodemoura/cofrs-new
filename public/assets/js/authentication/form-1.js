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
$('form').on('click', function(e){
    e.preventDefault();
    console.log('cliquei no form ');

    window.location.href = '/dashboard'
});



