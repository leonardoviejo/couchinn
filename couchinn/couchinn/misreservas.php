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

	// Obtengo los datos del usuario
	$consulta="SELECT * FROM usuario WHERE Id_Usuario='$idusuario'";
	$consulta_execute = $conexion->query($consulta);
	$resultado=$consulta_execute->fetch_assoc();
	$tipo=$resultado['Id_TipoDeUsuario'];
	$nombreusuario=$resultado["Nombre"].' '.$resultado["Apellido"];
	$premium=$resultado["Premium"];

	//Obtengo la fecha actual
	$hoy = date('d-m-Y');

	//Conteo de paginado de resultado.
	$TAMANO_PAGINA=5;
	if(!isset($_GET['pagina'])) {
		$pagina=1;
		$inicio=0;
	}else{
		$pagina = $_GET["pagina"];
		$inicio = ($pagina - 1) * $TAMANO_PAGINA;
	}
	// Selecciono las reservas del usuario para mostrar en el paginado
	$consulta = "SELECT * FROM reserva WHERE Visible=1 AND Id_Usuario=$idusuario";
	$consulta_execute = $conexion->query($consulta);
	$total_resultados=$consulta_execute->num_rows;
	$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);

	// Selecciono las reservas del usuario para mostrar por pagina
	$consulta = "SELECT r.Id_Reserva, r.Id_Usuario, r.Id_Couch, r.FechaInicio, r.FechaFin, r.Estado, r.FechaAlta, c.Titulo AS Titulo FROM reserva r inner JOIN couch c ON r.Id_Couch = c.Id_Couch WHERE r.Visible=1 AND r.Id_Usuario='$idusuario' ORDER BY Estado='vencida', Estado='cancelada', Estado='rechazada', Estado='confirmada', Estado='espera' LIMIT ".$inicio.",".$TAMANO_PAGINA."";
	$consulta_execute = $conexion->query($consulta);	
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Mis Reservas</title>
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
        		<br>
        	    	<div class="center grey-text text-darken-2">
                        <h1> Mis Reservas </h1>
                    </div>
					<br>
					<div class="divider"></div>
					<br>
					<div>
					<?php if($consulta_execute->num_rows) { ?>
						<table class="responsive-table">
							<thead>
								<tr>
									<th data-field="name" class="center">Titulo Couch</th>
									<th data-field="name" class="center">Fecha de inicio</th>
									<th data-field="name" class="center">Fecha de fin</th>
									<th data-field="name" class="center">Estado</th>
									<th data-field="name" class="center">Fecha de Solicitud</th>
								</tr>
							</thead>
							<?php 
							while($query_result = $consulta_execute->fetch_array()) {
								$idcouch=$query_result['Id_Couch'];
								$estado=$query_result['Estado'];
								$titulo = $query_result['Titulo'];
								$fechainicio = $query_result['FechaInicio'];
								$fechainicio = date('d-m-Y', strtotime($fechainicio));
								$fechafin = $query_result['FechaFin'];
								$fechafin = date('d-m-Y', strtotime($fechafin));
								$fechaalta = $query_result['FechaAlta'];
								$fechaalta = date('d-m-Y', strtotime($fechaalta));						
							echo'
							<tbody>';
								if($estado=='espera') {
									echo '<tr>
										<td bgcolor="#ffff99" class="center">'.$titulo.'</td>
										<td bgcolor="#ffff99" class="center">'.$fechainicio.'</td>
										<td bgcolor="#ffff99" class="center">'.$fechafin.'</td>
										<td bgcolor="#ffff99" class="center">'.ucwords(strtolower($estado)).'</td>
										<td bgcolor="#ffff99" class="center">'.$fechaalta.'</td>
										<td bgcolor="#ffff99" class="center">
											<form action="vercouch.php" method="post">
												<input type="hidden" name="id" value="'.$idcouch.'">
												<input class="waves-effect waves-light btn light-green  z-depth-2" type="submit" value="Ver Couch">
											</form>
										</td>
									</tr>';
								} else {
									if($estado=='confirmada') {
										echo '<tr>
											<td bgcolor="#b2d8b2" class="center">'.$titulo.'</td>
											<td bgcolor="#b2d8b2" class="center">'.$fechainicio.'</td>
											<td bgcolor="#b2d8b2" class="center">'.$fechafin.'</td>
											<td bgcolor="#b2d8b2" class="center">'.ucwords(strtolower($estado)).'</td>
											<td bgcolor="#b2d8b2" class="center">'.$fechaalta.'</td>
											<td bgcolor="#b2d8b2" class="center">
												<form action="vercouch.php" method="post">
													<input type="hidden" name="id" value="'.$idcouch.'">
													<input class="waves-effect waves-light btn light-green  z-depth-2" type="submit" value="Ver Couch">
												</form>
											</td>
										</tr>';
									} else {
										if($estado=='rechazada') {
											echo '<tr>
												<td bgcolor="#ffb2b2" class="center">'.$titulo.'</td>
												<td bgcolor="#ffb2b2" class="center">'.$fechainicio.'</td>
												<td bgcolor="#ffb2b2" class="center">'.$fechafin.'</td>
												<td bgcolor="#ffb2b2" class="center">'.ucwords(strtolower($estado)).'</td>
												<td bgcolor="#ffb2b2" class="center">'.$fechaalta.'</td>
												<td bgcolor="#ffb2b2" class="center">
													<form action="vercouch.php" method="post">
														<input type="hidden" name="id" value="'.$idcouch.'">
														<input class="waves-effect waves-light btn light-green  z-depth-2" type="submit" value="Ver Couch">
													</form>
												</td>
											</tr>';
										}
									}
								}
							echo'
							</tbody>';
							} ?>
						</table>
					<?php
					} else{
					echo '<br>
						<div class="center grey-text text-darken-2">
							<h5>No existen reservas.</h5>
						</div>
						<br>
						<br>
						<br>';
					}
					?>
					</div>
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
						echo '<li class="waves-effect"><a href="misreservas.php?pagina='.$paginaant.'"><i class="material-icons">chevron_left</i></a></li>';
					}
					if ($total_paginas > 1){
						for ($i=1;$i<=$total_paginas;$i++){ 
							if ($pagina == $i){
								//si muestro el índice de la página actual, no coloco enlace 
								echo '<li class="active light-green"><a href="#!">'.$pagina.'</a></li>';
							}else{
								echo '<li class="waves-effect"><a href="misreservas.php?pagina='.$i.'">'.$i.'</a></li>';
							}
						}
						if ($pagina==$total_paginas){
							//echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
						}else{
							$paginapos=$pagina+1;
							echo '<li class="waves-effect"><a href="misreservas.php?pagina='.$paginapos.'"><i class="material-icons">chevron_right</i></a></li>';
						}
					}
				?>
				</ul>
                  
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