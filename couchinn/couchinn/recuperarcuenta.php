<!doctype html>
<?php
	require_once("funciones/sesion.class.php");
	
	$sesion = new sesion();
	$usuario = $sesion->get("usuario");
	
	if( $usuario == false )
	{	
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Recuperar Cuenta</title>
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
						<li><a href="login.php"  class="light-green-text">Iniciar Sesión</a></li>
					</ul>
					<!-- Opciones  de menu al costado-->
					<ul class="side-nav" id="menulateral">
						<li><a href="registro.php"  class="light-green-text">Registrarse</a></li>
						<li><a href="login.php" class="light-green-text">Iniciar Sesión</a></li>
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
                        <h1> Recuperar Cuenta </h1>
                    </div>
					<div class="col s12 center grey-text text-darken-2">
                        <h6>Ingrese su direccion de correo electrónico y le enviaremos a la brevedad la contraseña de su cuenta.</h6>
                    </div>
					<!-- Inicio del Formulario-->
                    <form class="col s12 center" name="login" method="POST" action="funciones/recuperar_cuenta.php">
      					<div class="row">
       				 		<div class="input-field col s4 offset-s4">
          						<input name="email" id="email" type="email" maxlength="50" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" title="Ej: micorreo@correo.com" class="validate" required="required">
          						<label for="email" data-error="Ingrese una dirección del tipo micorreo@correo.com">Correo</label>
        					</div>
						</div>
                        <br>
					    <div class="row">
	        				<div class="col s4 left">
                             	<input class="waves-effect waves-light btn light-green z-depth-2" type="button" value="Cancelar" onClick="location.href='index.php'">
                            </div>
                            <div class="col s4 right">
        	                	<input class="waves-effect waves-light btn light-green z-depth-2" type="submit" value="Recuperar">
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
		<script type="text/javascript" src="js/funciones.js"></script>
  		<!-- Inicializacion de JS -->
  		<script type="text/javascript">
  			$(document).ready(function(){
			$(".parallax").parallax();
			$(".button-collapse").sideNav();
  			});
  		</script>
	</body>

</html>
<?php 
	}else{	
		header("Location: index_login.php");
	}
?>