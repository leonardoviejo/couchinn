

	function validarFormulario(){
		var p1 = document.getElementById("password").value;
		var p2 = document.getElementById("password_re").value;
		var e1 = document.getElementById("email").value;
		var e2 = document.getElementById("email_re").value;
		var mensaje="Se ha/n encontrado el/los siguente/s error/es: ";
		var error=false;
		if (p1 !== p2) {
			mensaje=mensaje +"Contraseña no coincide, ";
			error=true;
		}
		if (e1 !== e2) {
			mensaje=mensaje +"Correo no coincide, ";
			error=true; 
		}
		if (error){
			alert(mensaje);
			return false;
		}else{
			return true;
		}
	}
	
	function validarFormularioPerfil(){
		var e1 = document.getElementById("email").value;
		var e2 = document.getElementById("email_re").value;
		var error=false;
		if (e1 !== e2) {
			error=true; 
		}
		if (error){
			alert("Los correos no coinciden");
			return false;
		}else{
			return true;
		}
	}
	
	function validarFormularioPassword(){
		var p1 = document.getElementById("password_nueva").value;
		var p2 = document.getElementById("password_re_nueva").value;
		var error=false;
		if (p1 !== p2) {
			error=true; 
		}
		if (error){
			alert("Las contraseñas nuevas no coinciden");
			return false;
		}else{
			return true;
		}
	}