
const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#password");
const togglePassword2 = document.querySelector("#togglePassword2");
const password2 = document.querySelector("#password2");


togglePassword.addEventListener("click",function(e){
	const type = password.type === "password" ? "text" : "password";
    password.type = type;	
	this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
} );

togglePassword2.addEventListener("click",function(e){
	const type = password2.type === "password" ? "text" : "password";
    password2.type = type;	
	this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
} );

