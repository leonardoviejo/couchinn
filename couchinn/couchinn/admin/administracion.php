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
			$nombreusuario=$resultado["Nombre"].' '.$resultado["Apellido"];
			$premium=$resultado["Premium"];
			//Busqueda de Costo de Membresía
			$consulta= "SELECT Costo FROM costospremium ORDER BY Id_Costo DESC LIMIT 1";
			$resultado = $conexion->query($consulta);
			$fila = $resultado->fetch_assoc();
			$costoactual = $fila["Costo"];
?>		
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Administración</title>
		<!-- Importacion Iconos de Google -->
 	 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Importacion de materialize css-->
		<link type="text/css" rel="stylesheet" href="../css/materialize.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="../css/tooltip.css"  media="screen,projection"/>
		<!--Sitio optimizado para moviles-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	
	<body>
		<a href="../altacouch.php" accesskey="c"></a>
		<a href="../miscouchs.php" accesskey="m"></a>
		<a href="../misreservas.php" accesskey="r"></a>
		<a href="../miperfil.php" accesskey="p"></a>
		<a href="../ayuda.php" accesskey="a"></a>
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
		<ul class="dropdown-content" id="desplegable_lateral_couchs">
			<li><a class="light-green-text" href="../miscouchs.php">Mis Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="../misreservas.php">Mis Reservas</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_lateral_admin">
			<li><a class="light-green-text" href="administracion.php">Administración</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="tiposdecouch.php">Tipos de Couchs</a></li>
			<li class="divider"></li>
			<li><a class="light-green-text" href="listarusuarios.php">Usuarios</a></li>
		</ul>
		<ul class="dropdown-content" id="desplegable_lateral_cuenta">
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
						<li><a href="../miperfil.php"  class="grey-text text-darken-2">Bienvenido, <?php echo $nombreusuario;?>!!!</a></li>
						<?php if ($premium==1) echo'
						<li><a href="#" class="light-green">Cuenta Premium</a></li>
						<li><a href="#" class="light-green"><i class="large material-icons">star</i></a></li>
						'?>
						<li><a href="../index_login.php"  class="light-green-text">Inicio</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_couchs">Couchs y Reservas</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_admin">Panel Administrador</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_cuenta">Mi cuenta</a></li>
						<li><a href="../funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
					</ul>
					<!-- Opciones  de menu lateral-->
					<ul class="side-nav" id="menulateral">
						<li><a href="../index_login.php"  class="light-green-text">Inicio</a></li>
						<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_couchs">Couchs y Reservas</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_lateral_admin">Panel Administrador</a></li>
						<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_cuenta">Mi cuenta</a></li>
						<li><a href="../funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
					</ul>
			  </div>		
			</nav>
		</div>
		
		<!-- Comienzo del modal para modificar costo de membresia premium-->
		<div id="modal_costos" class="modal">
    		<div class="modal-content">
      			<br>
      			<h4>Modificar Costo de Membresía Premium</h4>
				<br>
      			<p>Ingresa el nuevo monto y presiona Guardar.</p>
				<br>
				<form name="costos" method="post" action="funciones/actualiza_costos.php">
					<div class="row">
						<div class="grey-text col s4 offset-s4 center"> Costo Actual: $<?php echo $costoactual ?></div>
						<br>
						<div class="input-field col s2 offset-s5" data-tip="Ingrese el monto deseado.">
							<input name="monto" id="monto" type="text" maxlength="4" pattern="^[0-9]{1,4}" class="validate" required="required">
							<label for="monto" data-error="Solo se permiten digitos.">Nuevo Costo</label>
						</div>
					</div>
					<?php echo '<input type="hidden" name="idusuario" value="'.$idusuario.'">';?>
					<br>
					<br>
					<div class="divider"></div>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Guardar">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin del modal para modificar costo de membresia premium-->
		<!-- Contenido de pagina--> 
        <div class="parallax-container-mio  z-depth-3">
        	<div class="parallax fondo-registro"></div>
        	<div class="container"> 
    	    	<div class="row">
                	<br>
        	    	<div class="col s12 center grey-text text-darken-2">
                        <h1> Administración </h1>
                    </div>
				</div>
				<!-- Inicio del Formulario-->
                <div class="row">
					<table class="col s6 offset-s3 highlight responsive-table">
        				<thead>
							<tr>
								<th class="center" data-field="name"><h5>Operaciones</h5></th>
          					</tr>
        				</thead>
						<tbody>
							<tr>
								<td class="center"><input class="waves-effect waves-light btn yellow darken-3 z-depth-2" type="button" value="Listar Couch" onClick="location.href='listarcouchs.php'"></td>
							</tr>
							<tr>
								<td class="center"><input class="waves-effect waves-light btn yellow darken-3 z-depth-2" type="button" value="Tipos de Couch" onClick="location.href='tiposdecouch.php'"></td>
							</tr>
							<tr>
								<td class="center"><input class="waves-effect waves-light btn yellow darken-3 z-depth-2" type="button" value="Listar Usuarios" onClick="location.href='listarusuarios.php'"></td>
							</tr>
							<tr>
								<td class="center"><a class="waves-effect waves-light btn yellow darken-3 z-depth-2 modal-trigger" href="#modal_costos">Modificar Costo de Membresía</a></td>
							</tr>
							<tr>
								<td class="center"><input class="waves-effect waves-light btn yellow darken-3 z-depth-2" type="button" value="Listar Usuarios Premium" onClick="location.href='listarusuariospremium.php'"></td>
							</tr>
							<tr>
								<td class="center"><input class="waves-effect waves-light btn yellow darken-3 z-depth-2" type="button" value="Listar Administradores" onClick="location.href='listaradmin.php'"></td>
							</tr>
							<tr>
								<td class="center"><input class="waves-effect waves-light btn yellow darken-3 z-depth-2 disabled" type="button" value="Listar Reservas" onClick="location.href='listarusuariospremium.php'"></td>
							</tr>
							<tr>
								<td class="center"><input class="waves-effect waves-light btn yellow darken-3 z-depth-2 disabled" type="button" value="Calcular Ganancias" onClick="location.href='listarusuariospremium.php'"></td>
							</tr>
        				</tbody>
      				</table>
				</div>
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
  		<!-- Inicializacion de JS -->
  		<script type="text/javascript">
  			$(document).ready(function(){
				$(".parallax").parallax();
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
				$('.modal-trigger').leanModal();
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