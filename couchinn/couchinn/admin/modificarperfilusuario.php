<!doctype html>
<?php
	require_once("../funciones/sesion.class.php");
	
	$sesion = new sesion();
	$idusuario = $sesion->get("id");
	
	if( $idusuario == false )
	{	
		header("Location: ../login.php");		
	}
	else{
		//SQL
		include('../funciones/config.php');
		$consulta="SELECT * FROM usuario WHERE Id_Usuario='$idusuario'";
		$consulta_execute = $conexion->query($consulta);
		$resultado=$consulta_execute->fetch_assoc();
		$tipo=$resultado['Id_TipoDeUsuario'];
		if ($tipo == 2){
    //Variables
			if(empty($_POST['id'])){	
				header("Location: listarusuarios.php");
			}else{
				$id = $_POST['id'];
			}
			$consulta="SELECT * FROM usuario WHERE Id_Usuario='$id'";
			$consulta_execute = $conexion->query($consulta);
			$resultado=$consulta_execute->fetch_assoc();
			$tipousuario= $resultado["Id_TipoDeUsuario"];
			$nombre=$resultado["Nombre"];
			$apellido=$resultado["Apellido"];
			$fechanac=$resultado["FechaNac"];
			$telefono=$resultado["Telefono"];
			$email=$resultado["Email"];
	
			
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Modificar Perfil de Usuario</title>
		<!-- Importacion Iconos de Google -->
 	 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Importacion de materialize css-->
		<link type="text/css" rel="stylesheet" href="../css/materialize.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="../css/tooltip.css"  media="screen,projection"/>
		<!--Sitio optimizado para moviles-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	
	<body>
		<!-- Estructuras del menu deslizables -->
		<ul class="dropdown-content" id="desplegable_couchs">
			<li><a class="light-green-text" href="../miscouchs.php">Mis Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="../misreservas.php">Mis Reservas</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_admin">
			<li><a class="light-green-text" href="administracion.php">Administración</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="tiposdecouch.php">Tipos de Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="listarusuarios.php">Usuarios</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_cuenta">
			<li><a class="light-green-text" href="../miperfil.php">Mi Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="../modificarperfil.php">Modificar Perfil</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="../eliminarcuenta.php">Eliminar Cuenta</a></li>
		</ul>
		<!-- Encabezado fijo -->
		<div class="navbar-fixed">
		<!-- Barra de navagacion -->
		<nav>
			<div class="nav-wrapper white z-depth-3">
			<!-- Logo -->
			<a href="../index.php" class="brand-logo"><img src="../imagenes/Logo.png" alt="CouchInn" width="270" class="responsive-img" id="logo"/></a>
			<a href="#" data-activates="menulateral" class="button-collapse"><i class="material-icons light-green">menu</i></a>
			<!-- Opciones -->
			<ul class="right hide-on-med-and-down">
				<li><a href="../index_login.php"  class="light-green-text">Inicio</a></li>
				<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_couchs">Couchs y Reservas</a></li>
				<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_admin">Panel Administrador</a></li>
				<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_cuenta">Mi cuenta</a></li>
				<li><a href="../funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
			</ul>
            <!-- Opciones  de menu lateral-->
			<ul class="side-nav" id="menulateral">
				<li><a href="../index_login.php"  class="light-green-text">Inicio</a></li>
				<li><a href="#"  class="light-green-text">Couchs y Reservas</a></li>
				<li><a href="#"  class="light-green-text">Panel Administrador</a></li>
				<li><a href="#"  class="light-green-text">Mi cuenta</a></li>
				<li><a href="../funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
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
                        <h1> Modificar Perfil de Usuario </h1>
                    </div>
				</div>
				<!-- Inicio del Formulario-->
                <form name="modificarperfil" method="post" action="funciones/modificar_perfil.php">
					<div class="row">
						<div class="col s4">
							<p class="left">Nombre </p>
						</div>
						<div class="input-field col s4">
							<?php 
								echo ' <input type="hidden" name="id" value="'.$id.'">
										<input name="nombre" type="text" pattern="[A-Za-z\s]+" value="'.$nombre.'" title="Solo se admiten letras" class="validate" required="required">
										<label for="nombre" data-error="Solo se admiten letras"></label>';
							?>
						</div>
					</div>
					<div class="divider"></div>
					<div class="row">
						<div class="col s4">
							<p class="left">Apellido </p>
						</div>	
						<div class="input-field col s4">
							<?php 
								echo '<input name="apellido" type="text" pattern="[A-Za-z]+" value="'.$apellido.'" title="Solo se admiten letras" class="validate" required="required">
										<label for="apellido" data-error="Solo se admiten letras"></label>';
							?>
						</div>
					</div>
					<div class="divider"></div>
					<div class="row">
						<div class="col s4">
							<p class="left">Correo </p>
						</div>
						<div class="input-field col s4">
							<?php 
								echo '<input name="email" id="email" value="'.$email.'" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" type="email" class="validate" required="required">
          						    <label class="active" for="email" data-error="Ingrese una dirección del tipo micorreo@correo.com"></label>';
							?>
						</div>
					</div>
					<div class="divider"></div>
					<div class="row">
						<div class="col s4">
							<p class="left">Fecha de Nacimiento </p>
						</div>
						<div class="input-field col s4">
							<?php echo '<input name="f_nac" type="date" class="datepicker" value="'.$fechanac.'" required="required" id="f_nac" title="Fecha de Nacimiento">';
							?>
						</div>
					</div>
					<div class="divider"></div>
					<div class="row">
						<div class="col s4">
							<p class="left">Telefono </p>
						</div>
						<div class="input-field col s4" data-tip="Ingrese el codigo de area sequido de su numero telefonico.">
							<?php
								echo '<input name="telefono" id="telefono" value="'.$telefono.'" type="tel" pattern="^[0-9]{6,13}" class="validate" required="required">
										<label for="telefono" data-error="Se permiten solo de 6 a 13 digitos."></label>';
							?>	
					    </div>
					</div>
					<div class="divider"></div>
					<br>
					<div class="row">
	        			<div class="col s12registro l6 center">
                          	<input class="waves-effect waves-light btn light-green z-depth-2" type="button" value="Cancelar" onClick="location.href='listarusuarios.php'">
                        </div>
                        <div class="col s12registro l6 center">
        	              	<input class="waves-effect waves-light btn yellow darken-3 z-depth-2" type="submit" value="Guardar">
                        </div>
                    </div>
				</form>
				<!--Fin del Formulario-->
    	    </div>        
        </div>
        <!-- Contenido de pagina-->
        
        <!-- Pie de pagina-->
		<footer class="page-footer light-green">
          <div class="container">
            <div class="row">
              <div class="col s2 l1 right">
                <img src="../imagenes/data_fiscal.jpg" class="responsive-img" alt=""/>
                <img src="../imagenes/todo_pago.jpg" class="responsive-img" alt=""/>
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
		<script type="text/javascript" src="../js/jquery.min.js"></script>
  		<script type="text/javascript" src="../js/materialize.js"></script>
		<script type="text/javascript" src="../js/funciones.js"></script>
  		<!-- Inicializacion de JS -->
  		<script type="text/javascript">
  			$(document).ready(function(){
				$(".parallax").parallax();
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
  			});
  		</script>
	</body>

</html>
<?php 
		
		}else{
			header("Location: ../index_login.php");
		}
	}
?>