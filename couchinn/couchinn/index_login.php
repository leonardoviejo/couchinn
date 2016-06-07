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
	$premium=$resultado["Premium"];
	//Conteo de paginado de resultado.
		$TAMANO_PAGINA=5;
		if(!isset($_GET['pagina'])) {
			$pagina=1;
			$inicio=0;
		}else{
			$pagina = $_GET["pagina"];
			$inicio = ($pagina - 1) * $TAMANO_PAGINA;
		}
		//Consultas SQL
		$consulta = "SELECT * FROM couch WHERE Visible=1 ORDER BY Titulo ASC";
		$consulta_execute = $conexion->query($consulta);
		$total_resultados=$consulta_execute->num_rows;
		$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);
		$consulta = "SELECT c.Id_Couch, c.Id_TipoDeCouch, c.Id_Usuario, c.Titulo, c.Ciudad, c.Capacidad, c.Foto1, u.Premium, t.Nombre AS NombreTipo FROM couch c inner JOIN usuario u ON c.Id_Usuario = u.Id_Usuario inner JOIN tipodecouch t ON c.Id_TipoDeCouch = t.Id_Tipo WHERE c.Visible=1 ORDER BY Titulo ASC LIMIT ".$inicio.",".$TAMANO_PAGINA."";
		$consulta_execute = $conexion->query($consulta);
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Inicio</title>
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
						<li><a href="miperfil.php"  class="grey-text  text-darken-2">Bienvenido, <?php echo $nombreusuario;?>!!!</a></li>
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
        <div class="parallax-container-mio-home z-depth-3">
        	<div class="parallax transparencia"><img src="imagenes/fondo.jpg" alt=""></div>
        	<div class="container"> 
    	    	<div class="row">
        	    	<div class="col s12">
                    	<br>
						<br>
						<br>
                        <div class="input-field white z-depth-3">
							<input id="search" type="search" required>
							<label for="search"><i class="material-icons">search</i></label>
							<i class="material-icons">close</i>
						</div>
						<br>
                    </div>
	            </div>
				<div class="section">
					<!-- Tabla-->
					<?php if($consulta_execute->num_rows) { ?>
					<div class="row card-panel">
						<table class="col s12 highlight responsive-table">
							<thead>
								<tr>
									<th class="center" data-field="name"></th>
									<th class="center" data-field="name">Titulo</th>
									<th class="center" data-field="name">Ciudad</th>
									<th class="center" data-field="name">Capacidad</th>
									<th class="center" data-field="name">Tipo</th>
								</tr>
							</thead>
							<?php 
							while($query_result = $consulta_execute->fetch_array()) {
								$id=$query_result['Id_Couch'];
								$titulo = $query_result['Titulo'];
								$ciudad = $query_result['Ciudad'];
								$capacidad = $query_result['Capacidad'];
								$tipocouch = $query_result['NombreTipo'];
								$premium= $query_result['Premium'];
								$foto1= $query_result['Foto1'];
												
							echo'
							<tbody>
								<tr>';
									if($premium==1){
										echo '<td class="center" ><img width="70" height="70" src="'.$foto1.'"></td>';
									}else{
										echo '<td class="center" ><img width="70" height="70" src="imagenes/mini.png"></td>';
									}
									echo 
									'<td class="center" >'.$titulo.'</td>
									<td class="center" >'.$ciudad.'</td>
									<td class="center" >'.$capacidad.'</td>
									<td class="center" >'.$tipocouch.'</td>
									<td class="right">
										<form action="vercouch.php" method="post">
											<input type="hidden" name="id" value="'.$id.'">
											<input class="waves-effect waves-light btn light-green z-depth-2" type="submit" value="Ver Couch">
										</form>
									</td>
								</tr>
							</tbody>';
							} ?>
						</table>
                        <?php
						} else{
						echo '<tr>
            					<td class="center">No existen Couchs</td>
          					</tr>';
						}
						?>
						<ul class="pagination center">
						<?php
							if ($pagina==1){
								if ($total_paginas==1){
									echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
									echo '<li class="disabled"><a href="#">1</a></li>';
									echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
								}else{
									echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
								}
							}else{
								$paginaant=$pagina-1;
								echo '<li class="waves-effect"><a href="index_login.php?pagina='.$paginaant.'"><i class="material-icons">chevron_left</i></a></li>';
							}
							if ($total_paginas > 1){
								for ($i=1;$i<=$total_paginas;$i++){ 
									if ($pagina == $i){
										//si muestro el índice de la página actual, no coloco enlace 
										echo '<li class="active light-green"><a href="#!">'.$pagina.'</a></li>';
									}else{
										echo '<li class="waves-effect"><a href="index_login.php?pagina='.$i.'">'.$i.'</a></li>';
									}
								}
								if ($pagina==$total_paginas){
									echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
								}else{
									$paginapos=$pagina+1;
									echo '<li class="waves-effect"><a href="index_login.php?pagina='.$paginapos.'"><i class="material-icons">chevron_right</i></a></li>';
								}
								
							}
						?>
						</ul>
					</div>
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
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
  			});
  		</script>
	</body>

</html>
<?php 
	}	
?>