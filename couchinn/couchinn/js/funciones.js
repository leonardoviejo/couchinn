

	function validarFormulario(){
		var p1 = document.getElementById("password").value;
		var p2 = document.getElementById("password_re").value;
		var e1 = document.getElementById("email").value;
		var e2 = document.getElementById("email_re").value;
		var t= document.getElementById("telefono").value;
		var mensaje="Se ha/n encontrado el/los siguente/s error/es: ";
		var error=false;
		if (p1 !== p2) {
			mensaje=mensaje +"Contraseña no coincide, ";
			error=true;
		}
		/*if (!error){
			if (p1.length<8) {
				mensaje=mensaje+ "Contraseña muy corta, ";
				error=true;
			}
		}*/
		if (e1 !== e2) {
			mensaje=mensaje +"Correo no coincide, ";
			error=true; 
		}
		 /*if (!/^([0-9])*$/.test(t)){
			 mensaje=mensaje +"El numero telefonico solo puede contener numeros.";
			 error=true;
		}*/
		if (error){
			alert(mensaje);
			return false;
		}else{
			return true;
		}
	}