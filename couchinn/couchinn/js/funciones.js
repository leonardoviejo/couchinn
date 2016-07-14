

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
	
	function validarFormularioTarjeta(){
		var tarjeta = document.getElementById("tarjeta").value;
		var mes = document.getElementById("mes").value;
		var anio = document.getElementById("anio").value;
		var mensaje="Se ha/n encontrado el/los siguente/s error/es: ";
		var error=false;
		
		if (tarjeta == "") {
			mensaje=mensaje +"Seleccione su tarjeta";
			error=true;
		}
		
		if (mes == "" ) {
			mensaje=mensaje +" ,Seleccione mes de vencimiento";
			error=true; 
		}
		
		if (anio == "" ) {
			mensaje=mensaje +" ,Seleccione año de vencimiento.";
			error=true; 
		}
		
		if (error){
			alert(mensaje);
			return false;
		}else{
			return true;
		}
	}
	
	function validarFormularioAlta(){
		var foto1 = document.getElementById("imagen1").value;
		var foto2 = document.getElementById("imagen2").value;
		var foto3 = document.getElementById("imagen3").value;
		if ((foto1 == "")&&(foto2== "")&&(foto3=="")) {
			alert("Debe seleccionar por lo menos una imagen para su Couch");
			return false;
		}
		return true;
	}
	
	function validarReserva(){
		var fechainicio = document.getElementById("fechainicio").value;
		fechainicioparse=Date.parse(fechainicio);
		var fechafin = document.getElementById("fechafin").value;
		fechafinparse=Date.parse(fechafin);
		var mensaje="Se ha/n encontrado el/los siguente/s error/es: ";
		var error=false;
		if ((fechainicio == "")||(fechafin == "")){
			mensaje=mensaje+ "Debe seleccionar las dos fechas";
			error=true;
		}
		if (fechainicioparse>fechafinparse){
			mensaje=mensaje+" ,Verifique que la fecha del comienzo de reserva es anterior a la fecha de fin de reserva.";
			error=true;
		}
		if (error){
			alert(mensaje);
			return false;
		}else{
			return true;
		}
	}
	
	function validarReservaDos(){
		var fechainicio = document.getElementById("fechainicioDos").value;
		fechainicioparse=Date.parse(fechainicio);
		var fechafin = document.getElementById("fechafinDos").value;
		fechafinparse=Date.parse(fechafin);
		var mensaje="Se ha/n encontrado el/los siguente/s error/es: ";
		var error=false;
		if ((fechainicio == "")||(fechafin == "")){
			mensaje=mensaje+ "Debe seleccionar las dos fechas";
			error=true;
		}
		if (fechainicioparse>fechafinparse){
			mensaje=mensaje+" ,Verifique que la fecha del comienzo de reserva es anterior a la fecha de fin de reserva.";
			error=true;
		}
		if (error){
			alert(mensaje);
			return false;
		}else{
			return true;
		}
	}
	
	function validarBusqueda(){
		var fechainicio = document.getElementById("fechainicio").value;
		fechainicioparse=Date.parse(fechainicio);
		var fechafin = document.getElementById("fechafin").value;
		fechafinparse=Date.parse(fechafin);
		var mensaje="Se ha/n encontrado el/los siguente/s error/es: ";
		var error=false;
		if (((fechainicio != "")&&(fechafin == ""))||((fechainicio == "")&&(fechafin != ""))){
			mensaje=mensaje+ "Debe seleccionar las dos fechas";
			error=true;
		}
		if (fechainicioparse>fechafinparse){
			mensaje=mensaje+" ,Verifique que la fecha del comienzo de reserva es anterior a la fecha de fin de reserva.";
			error=true;
		}
		if (error){
			alert(mensaje);
			return false;
		}else{
			return true;
		}
	}
	