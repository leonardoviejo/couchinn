<!doctype html>
<?php
	include('config.php');
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Registro</title>
		<!-- Importacion Iconos de Google -->
 	 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Importacion de materialize css-->
		<link type="text/css" rel="stylesheet" href="css/materialize.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="css/tooltip.css"  media="screen,projection"/>
		<!--Sitio optimizado para moviles-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	
	<body>
		<!-- Encabezado fijo -->
		<div class="navbar-fixed">
			<!-- Barra de navagacion -->
			<nav>
				<div class="nav-wrapper white z-depth-3">
					<!-- Logo -->
					<a href="index.php" class="brand-logo"><img src="imagenes/Logo.png" alt="CouchInn" width="270" class="responsive-img" id="logo"/></a>
                    <a href="#" data-activates="menulateral" class="button-collapse"><i class="material-icons light-green">menu</i></a>
					<!-- Opciones -->
					<ul class="right hide-on-med-and-down">
						<li><a href="registro.php"  class="light-green-text">Registrarse</a></li>
						<li><a href="index.php"  class="light-green-text">Iniciar Sesión</a></li>
				  </ul>
                  <!-- Opciones  de menu al costado-->
					<ul class="side-nav" id="menulateral">
						<li><a href="registro.php"  class="light-green-text">Registrarse</a></li>
						<li><a href="index.php" class="light-green-text">Iniciar Sesión</a></li>
					</ul>
			  </div>		
			</nav>
		</div>
		<!-- Contenido de pagina--> 
        <div class="parallax-container-mio  z-depth-3">
        	<div class="parallax fondo-registro"></div>
        	<div class="container"> 
    	    	<div class="row">
                	<br>
        	    	<div class="col s12 center grey-text text-darken-2">
                        <h1> Formulario de Registro </h1>
                    </div>
					<!-- Inicio del Formulario-->
                    <form class="col s12" name="inscripcion" method="post"  action="reg_user.php">
      					<div class="row">
       				 		<div class="input-field col s6">
          						<input name="nombre" type="text" class="validate" required="required">
          						<label for="nombre">Nombre</label>
        					</div>
        					<div class="input-field col s6">
					        	<input name="apellido" type="text" class="validate" required="required">
	        					<label for="apellido">Apellido</label>
        					</div>
						</div>
                        <div class="row">
        					<div class="input-field col s6">
          						<input name="email" type="email" class="validate" required="required">
          						<label for="email" data-error="Ingrese una dirección del tipo micorreo@correo.com">Correo</label>
        					</div>
                            <div class="input-field col s6">
          						<input name="email_re" type="email" class="validate" required="required">
          						<label for="email" data-error="Ingrese una dirección del tipo micorreo@correo.com">Confirmar Correo</label>
        					</div>
      					</div>
      					<div class="row">
        					<div class="input-field col s6" data-tip="La contraseña debe contener 8 carácteres como mínimo">
          						<input name="password" type="password" class="validate" required="required">
          						<label for="password">Contraseña</label>
        					</div>
                            <div class="input-field col s6">
          						<input name="password_re" type="password" class="validate" required="required">
          						<label for="password">Confirmar Contraseña</label>
        					</div>
      					</div>
                        <div class="row">
	      					<div class="input-field col s6">
	                        	<div class="grey-text"> Fecha de nacimiento</div>
								<input name="f_nac" type="date" class="datepicker" required="required" id="f_nac" title="Fecha de Nacimiento">
	                        	<!--input name="f_nac" type="date" required="required" id="f_nac" title="Fecha de Nacimiento"-->
	                        </div>
                            <br>
                            <div class="input-field col s6">
					            <input name="telefono" type="tel" class="validate" required="required">
					            <label for="telefono">Teléfono</label>
					        </div>
                         </div>
                         <br>
                         <br>
                         <div class="row">
	        				<div class="col s12registro l4 center">
                             	<input class="waves-effect waves-light btn light-green z-depth-2" type="button" value="Cancelar" onClick="location.href='index.php'">
                            </div>
                            <div class="col s12registro l4 center">
    	                     	<input class="waves-effect waves-light btn light-green z-depth-2" type="reset" value="Limpiar">
                            </div>
                            <div class="col s12registro l4 center">
        	                	<input class="waves-effect waves-light btn light-green z-depth-2" type="submit" value="Aceptar">
                            </div>
                    	</div>
    				</form>
					<!--Fin del Formulario-->
	            </div>
    	    </div>        
        </div>
        <!-- Contenido de pagina-->
        
        <!-- Pie de pagina-->
		<footer class="page-footer light-green">
          <div class="container">
            <div class="row">
              <div class="col s2 l1 right">
                <img src="imagenes/data_fiscal.jpg" class="responsive-img" alt=""/>
                <img src="imagenes/todo_pago.jpg" class="responsive-img" alt=""/>
              </div>
            </div>
          </div>
          <div class="footer-copyright">
            <div class="container">
            © 2016 CouchInn - Todos los derechos reservados.
            </div>
          </div>
        </footer>
        <!-- Pie de pagina-->
        
        
 		<!-- Adjuntando los archivos JQuery -->
		<script type="text/javascript" src="js/jquery.min.js"></script>
  		<script type="text/javascript" src="js/materialize.js"></script>
  		<!-- Inicializacion de JS -->
  		<script type="text/javascript">
  			$(document).ready(function(){
			$(".parallax").parallax();
			$(".button-collapse").sideNav();
			$('.datepicker').pickadate({
				min: [1900,1,1],
				max: -6575, //hace que se muestre siempre como última fecha el día de hoy pero de 18 años atras. Solo se pueden registrar personas mayores de 18 años.
				selectYears: 116,
				selectMonths: true,
				formatSubmit: 'yyyy-mm-dd',
				hiddenName: true
			});
  			});
  		</script>
	</body>

</html>