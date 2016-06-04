<!doctype html>
<?php
	require_once("funciones/sesion.class.php");
	
	$sesion = new sesion();
	$idusuario = $sesion->get("id");
	
	if( $idusuario == false )
	{	
		header("Location: login.php");		
	}
	else 
	{
	include('funciones/config.php');
	//SQL
	$consulta="SELECT * FROM usuario WHERE Id_Usuario='$idusuario'";
	$consulta_execute = $conexion->query($consulta);
	$resultado=$consulta_execute->fetch_assoc();
	$tipo=$resultado['Id_TipoDeUsuario'];
	$nombreusuario=$resultado["Nombre"].' '.$resultado["Apellido"];
	$premium=$resultado['Premium'];
	if ($premium==0){
		
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Volverme Premium</title>
		<!-- Importacion Iconos de Google -->
 	 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Importacion de materialize css-->
		<link type="text/css" rel="stylesheet" href="css/materialize.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="css/tooltip.css"  media="screen,projection"/>
		<!--Sitio optimizado para moviles-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	
	<body>
		<!-- Estructuras del menu deslizables -->
		<ul class="dropdown-content" id="desplegable_couchs">
			<li><a class="light-green-text" href="miscouchs.php">Mis Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="misreservas.php">Mis Reservas</a></li>
		</ul>
			<ul class="dropdown-content" id="desplegable_admin">
			<li><a class="light-green-text" href="admin/administracion.php">Administración</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="admin/tiposdecouch.php">Tipos de Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="admin/listarusuarios.php">Usuarios</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_cuenta">
			<li><a class="light-green-text" href="miperfil.php">Mi Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="modificarperfil.php">Modificar Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="eliminarcuenta.php">Eliminar Cuenta</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_lateral_couchs">
			<li><a class="light-green-text" href="miscouchs.php">Mis Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="misreservas.php">Mis Reservas</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_lateral_admin">
			<li><a class="light-green-text" href="admin/administracion.php">Administración</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="admin/tiposdecouch.php">Tipos de Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="admin/listarusuarios.php">Usuarios</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_lateral_cuenta">
			<li><a class="light-green-text" href="miperfil.php">Mi Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="modificarperfil.php">Modificar Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="eliminarcuenta.php">Eliminar Cuenta</a></li>
		</ul>
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
						<li><a href="miperfil.php"  class="grey-text text-darken-2">Bienvenido, <?php echo $nombreusuario;?>!!!</a></li>
						<li><a href="index_login.php"  class="light-green-text">Inicio</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_couchs">Couchs y Reservas</a></li>
						<?php
							if($tipo==2){
								echo '<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_admin">Panel Administrador</a></li>';
							}
						?>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_cuenta">Mi cuenta</a></li>
						<li><a href="funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
					</ul>
					<!-- Opciones  de menu lateral-->
					<ul class="side-nav" id="menulateral">
						<li><a href="index_login.php"  class="light-green-text">Inicio</a></li>
						<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_couchs">Couchs y Reservas</a></li>
						<?php	if($tipo==2){
								echo '<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_lateral_admin">Panel Administrador</a></li>';
								}
						?>
						<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_cuenta">Mi cuenta</a></li>
						<li><a href="funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
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
                        <h1> Volverme Premium </h1>
                    </div>
					<!-- Inicio del Formulario-->
                    <form class="col s12" name="inscripcion" method="post" onSubmit="return validarTarjeta()" action="funciones/alta_premium.php">
      					<div class="row">
       				 		<div class="input-field col s4 offset-s1">
          						<input name="nombre" type="text" maxlength="30" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚüÜ\s]+" title="Solo se admiten letras" class="validate" required="required">
          						<label for="nombre" data-error="Solo se admiten letras.">Nombre del titular</label>
        					</div>
							<div class="input-field col s6 right">
          						El nombre del titular debe coincidir con el que figura en la tarjeta.
        					</div>
        				</div>
						<div class="row">							
							<div class="input-field col s4 offset-s1">
								<select class="icons" id="tarjeta">
									<option value="" disabled selected>Elija su tarjeta</option>
									<option value="american" data-icon="imagenes/tarjetas/american.jpg" class="left circle">American Express</option>
									<option value="master" data-icon="imagenes/tarjetas/master.jpg" class="left circle">MasterCard</option>
									<option value="naranja" data-icon="imagenes/tarjetas/naranja.jpg" class="left circle">Naranja</option>
									<option value="nativa" data-icon="imagenes/tarjetas/nativa.jpg" class="left circle">Nativa</option>
									<option value="visa" data-icon="imagenes/tarjetas/visa.jpg" class="left circle">Visa</option>
								</select>
								<label>Seleccione su tarjeta preferida</label>
							</div>
						</div>
						<div class="row">							
							<div class="input-field col s4 offset-s1" data-tip="Ingrese el numero de su tarjeta de credito.">
					            <input name="tarjeta" id="tarjeta" type="text" maxlength="16" pattern="^[0-9]{16,16}" class="validate" required="required">
					            <label for="tarjeta" data-error="Se permiten 16 digitos.">Número de Tarjeta</label>
					        </div>
							<div class="input-field col s6 right">
          						Ingrese los 16 digitos de su tarjeta de credito.
        					</div>
						</div>
                        <div class="row">
	      					<div class="input-field col s4 offset-s1">
	                        	<div class="grey-text"> Vencimiento</div>
								<input name="vencimiento" type="date" class="datepicker" required="required" id="vencimiento" title="Fecha de vencimientomiento de su tarjeta.">
	                        </div>
						</div>
						<div class="row">
							<div class="input-field col s4 offset-s1" data-tip="Ingrese el codigo de seguridad de su tarjeta.">
					            <input name="codigo" id="codigo" type="text" maxlength="3" pattern="^[0-9]{3,3}" class="validate" required="required">
					            <label for="codigo" data-error="Se permiten 3 digitos.">Codigo de Seguridad</label>
					        </div>
							<div class="input-field col s6 right">
          						El código de seguridad se encuentra en el reverso de su tarjeta.
        					</div>
						</div>
						<div class="row">
                            <div class="input-field col s4 offset-s1" data-tip="Ingrese el codigo de area seguido de su numero telefonico.">
					            <input name="telefono" id="telefono" type="tel" maxlength="13" pattern="^[0-9]{6,13}" class="validate" required="required">
					            <label for="telefono" data-error="Se permiten solo de 6 a 13 digitos.">Teléfono</label>
					        </div>
                         </div>
						 <!-- Envio de usuario -->
						 <?php 
							echo '<input type="hidden" name="idusuario" value="'.$idusuario.'">';
						 ?>
                         <br>
                         <br>
                         <div class="row">
	        				<div class="col s12registro l4 center">
                             	<input class="waves-effect waves-light btn light-green z-depth-2" type="button" value="Cancelar" onClick="location.href='miperfil.php'">
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
		<script type="text/javascript" src="js/funciones.js"></script>
  		<!-- Inicializacion de JS -->
  		<script type="text/javascript">
  			$(document).ready(function(){
				$(".parallax").parallax();
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
				$('.datepicker').pickadate({
					min: [2016,6,4],
					max: 1825, //hace que se muestre siempre como última fecha el día de hoy pero de 18 años atras. Solo se pueden registrar personas mayores de 18 años.
					selectYears: 5,
					selectMonths: true,
					formatSubmit: 'yyyy-mm',
					hiddenName: true
				});
				$('select').material_select();
  			});
  		</script>
	</body>

</html>
<?php 
		
		}else{
			header("Location: index_login.php");
		}
	}
?>