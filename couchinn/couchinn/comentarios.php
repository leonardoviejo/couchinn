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
	if (empty($_GET['idcouch'])){
		header("Location: index.php");
	}else{
		$idcouch = $_GET["idcouch"];
	}
	// Obtengo los datos del usuario
	$consulta="SELECT * FROM usuario WHERE Id_Usuario='$idusuario'";
	$consulta_execute = $conexion->query($consulta);
	$resultado=$consulta_execute->fetch_assoc();
	$tipo=$resultado['Id_TipoDeUsuario'];
	$nombreusuario=$resultado["Nombre"].' '.$resultado["Apellido"];
	$premium=$resultado["Premium"];

	//Conteo de paginado de resultado.
	$TAMANO_PAGINA=10;
	if(!isset($_GET['pagina'])) {
		$pagina=1;
		$inicio=0;
	}else{
		$pagina = $_GET["pagina"];
		$inicio = ($pagina - 1) * $TAMANO_PAGINA;
	}
	// Selecciono los datos del couch
	$consulta = "SELECT * FROM couch WHERE Visible=1 AND Id_Couch='$idcouch'";
	$consulta_execute = $conexion->query($consulta);
	$resultado = $consulta_execute->fetch_array();
	$titulo=$resultado['Titulo'];
	$idlocalidad=$resultado['Id_Localidad'];
	$idtipo=$resultado['Id_TipoDeCouch'];
	
	//Busqueda de tipo de couch
	$consulta2= "SELECT Nombre FROM tipodecouch WHERE Id_Tipo='$idtipo'";
	$resulta2 = $conexion->query($consulta2);
	$row2 = $resulta2->fetch_assoc();
	$tipodecouch = $row2["Nombre"];
	
	//Busqueda de ciudad y provincia
	$consultaubicacion= "SELECT l.Localidad as Localidad, p.Provincia as Provincia FROM localidades l inner JOIN provincias p ON l.Id_Provincia=p.Id WHERE l.Id='$idlocalidad'";
	$resultadoubicacion = $conexion->query($consultaubicacion);
	$resultado = $resultadoubicacion->fetch_assoc();
	$ubicacion = $resultado["Localidad"].', '.$resultado["Provincia"];
	
	// Selecciono los mensajes para mostrar para el paginado
	$consulta = "SELECT * FROM punt_couch WHERE Visible=1 AND Id_Couch='$idcouch'";
	$consulta_execute = $conexion->query($consulta);
	$total_resultados=$consulta_execute->num_rows;
	$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);

	// Selecciono los couch del usuario para mostrar por pagina
	$consulta = "SELECT u.Nombre as Nombre, u.Apellido as Apellido, p.puntaje as Puntaje, p.Mensaje as Mensaje FROM usuario u inner JOIN punt_couch p ON u.Id_Usuario = p.Id_Usuario WHERE p.Id_Couch='$idcouch' and p.Visible=1 LIMIT ".$inicio.",".$TAMANO_PAGINA."";
	$consulta_execute = $conexion->query($consulta);	
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Comentarios - <?php echo $titulo?></title>
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
						<?php if ($premium==1) echo'
						<li><a href="#" class="light-green">Cuenta Premium</a></li>
						<li><a href="#" class="light-green"><i class="large material-icons">star</i></a></li>
						'?>
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
				<br>
				<div class="center grey-text text-darken-2">
					<h1> Comentarios </h1>
				</div>
				<div class="divider"></div>
				<div class="row">
						<div class="col s6 offset-s3 center grey-text text-darken-2">
							<?php echo '<h5><b>Título: '.$titulo.' </b></h5>'; ?>
						</div>
					</div>
					<div class="row">
						<div class="col s6 offset-s3 center grey-text text-darken-2">
							<?php echo '<h5><b>Ubicación: '.$ubicacion.' </b></h5>'; ?>
						</div>
					</div>
					<div class="row">
						<div class="col s6 offset-s3 center grey-text text-darken-2">
							<?php echo '<h5><b>Tipo: '.$tipodecouch.' </b></h5>'; ?>
						</div>
					</div>
					<div class="divider"></div>
				<br>
				<?php if($consulta_execute->num_rows) { ?>
					<ul class="collapsible" data-collapsible="expandable">
						<?php 
						while($query_result = $consulta_execute->fetch_array()) {
							$nombre = $query_result["Nombre"].' '.$query_result["Apellido"];
							$mensaje = $query_result['Mensaje'];
							$puntaje = $query_result['Puntaje'];
							echo '
							<li>
								<div class="collapsible-header"><i class="material-icons">person</i>'.$nombre.' - Puntaje: '.$puntaje.'</div>
								<div class="collapsible-body"><p>'.$mensaje.'</p></div>
							</li>';
						}
						echo '</ul>';
					}
					?>
					<ul class="pagination center">
					<?php
						if ($pagina==1){
							if ($total_paginas==1){
								echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
								echo '<li class="disabled"><a href="#">1</a></li>';
								echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
							}
						}else{
							$paginaant=$pagina-1;
							echo '<li class="waves-effect"><a href="comentarios.php?idcouch='.$idcouch.'&pagina='.$paginaant.'"><i class="material-icons">chevron_left</i></a></li>';
						}
						if ($total_paginas > 1){
							for ($i=1;$i<=$total_paginas;$i++){ 
								if ($pagina == $i){
									//si muestro el índice de la página actual, no coloco enlace 
									echo '<li class="active light-green"><a href="#!">'.$pagina.'</a></li>';
								}else{
									echo '<li class="waves-effect"><a href="comentarios.php?idcouch='.$idcouch.'&pagina='.$i.'">'.$i.'</a></li>';
								}
							}
							if ($pagina==$total_paginas){
								//echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
							}else{
								$paginapos=$pagina+1;
								echo '<li class="waves-effect"><a href="comentarios.php?idcouch='.$idcouch.'&pagina='.$paginapos.'"><i class="material-icons">chevron_right</i></a></li>';
							}
						}
					?>
					</ul>
					<div class="row">
						<div class="col s12registro l12 center">
							<input class="waves-effect waves-light btn light-green z-depth-2" type="button" value="Volver" onClick="location.href='vercouch.php?id=<?php echo $idcouch ?>'">
						</div>
					</div>
			</div>	
        </div>
        <!-- Fin Contenido de pagina-->
        
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
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
  			});
  		</script>
	</body>

</html>
<?php 
	}	
?>