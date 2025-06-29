
const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#password");



togglePassword.addEventListener("click",function(e){
	const type = password.type === "password" ? "text" : "password";
    password.type = type;	
	this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
} );

