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
	$consulta="SELECT * FROM usuario WHERE Id_Usuario='$idusuario' and Visible=1";
	$consulta_execute = $conexion->query($consulta);
	if ($consulta_execute->num_rows==0){
		header("location: funciones/cerrar_sesion.php");
	}
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
	// No se discrimina por visible a las reservas porque al eliminar un couch, se pone Visible=0 a la reserva. De esta manera puedo ver tambien las estadias en couch que estan eliminados.
	// Selecciono las reservas del usuario con estado 'finalizada' para mostrar en el paginado.
	$consulta = "SELECT * FROM reserva WHERE Id_Usuario=$idusuario AND Estado='finalizada'";
	$consulta_execute = $conexion->query($consulta);
	$total_resultados=$consulta_execute->num_rows;
	$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);
	
	// No se discrimina por visible a las reservas porque al eliminar un couch, se pone Visible=0 a la reserva. De esta manera puedo ver tambien las estadias en couch que estan eliminados.
	// Selecciono las reservas del usuario con estado 'finalizada' para mostrar por pagina.
	$consulta = "SELECT r.Id_Reserva, r.Id_Usuario, r.Id_Couch, r.FechaInicio, r.FechaFin, r.Estado, r.Calif_Huesped, r.FechaAlta, c.Visible AS Couch_Visible, c.Titulo AS Titulo FROM reserva r inner JOIN couch c ON r.Id_Couch = c.Id_Couch WHERE r.Id_Usuario='$idusuario' AND r.Estado='finalizada' ORDER BY r.FechaInicio DESC LIMIT ".$inicio.",".$TAMANO_PAGINA."";
	$consulta_execute = $conexion->query($consulta);	
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Mis estadías</title>
		<!-- Importacion Iconos de Google -->
 	 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Importacion de materialize css-->
		<link type="text/css" rel="stylesheet" href="css/materialize.css"  media="screen,projection"/>
		<link type="text/css" rel="stylesheet" href="css/tooltip.css"  media="screen,projection"/>
		<!--Sitio optimizado para moviles-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	
	<body>
		<a href="altacouch.php" accesskey="c"></a>
		<a href="miscouchs.php" accesskey="m"></a>
		<a href="misreservas.php" accesskey="r"></a>
		<a href="miperfil.php" accesskey="p"></a>
		<a href="ayuda.php" accesskey="a"></a>
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
						<li><a href="ayuda.php#misestadias" class="light-green"><i class="large material-icons">help_outline</i></a></li>
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
						<li><a href="ayuda.php#misestadias" class="light-green"><i class="large material-icons">help_outline</i></a></li>
					</ul>
			  </div>		
			</nav>
		</div>
		
		<!-- Comienzo del modal de puntuacion-->
		<div id="modal_pun" class="modal">
    		<div class="modal-content">
				<br>
      			<h4>Ya has visitado este couch!!!</h4>
				<br>
      			<p>Por favor puntua y escribe un breve comentario acerca de tu experiencia en este Couch, esto servirá para que todos los Couchs mejoren su servicio y atención.</p>
				<br>
				<form name="puntuacion" method="post" action="funciones/puntuacouch.php">
					<div class="input-field">
						<select class="browser-default" required name="puntaje" id="puntaje"> 
							<option value="" disabled selected>Elige un puntaje...</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
					</div>
					<br>
					<div class="input-field">
						<textarea id="mensaje" name="mensaje" class="materialize-textarea" length="250" maxlength="250" class="validate" required="required"></textarea>
						<label for="descripcion">Mensaje</label>
					</div>
					<br>
					<div class="divider"></div>
					<?php echo '<input type="hidden" name="idreserva" id="idreserva" >';
						echo '<input type="hidden" name="idcouch" id="idcouch">';
						echo '<input type="hidden" name="idusuario" value="'.$idusuario.'">';						
					?>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Puntuar">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin del modal de puntuacion -->
			
		<!-- Contenido de pagina--> 
        <div class="parallax-container-mio  z-depth-3">
        	<div class="parallax fondo-registro"></div>
        		<br>
        	    	<div class="center grey-text text-darken-2">
                        <h1> Mis estadías </h1>
                    </div>
					<br>
					<div class="divider"></div>
					<br>
					<div>
					<?php if($consulta_execute->num_rows) { ?>
						<table class="highlight responsive-table">
							<thead>
								<tr>
									<th data-field="name" class="center">Título Couch</th>
									<th data-field="name" class="center">Fecha de inicio</th>
									<th data-field="name" class="center">Fecha de fin</th>
								</tr>
							</thead>
							<?php 
							while($query_result = $consulta_execute->fetch_array()) {
								$idcouch=$query_result['Id_Couch'];
								$idreserva=$query_result['Id_Reserva'];
								$estado=$query_result['Estado'];
								$titulo = $query_result['Titulo'];
								$fechainicio = $query_result['FechaInicio'];
								$fechainicio = date('d-m-Y', strtotime($fechainicio));
								$fechafin = $query_result['FechaFin'];
								$fechafin = date('d-m-Y', strtotime($fechafin));
								if (($query_result["Calif_Huesped"]==0)&&($query_result["Estado"]=='finalizada')){
									$puedevotar=true;
								}else{
									$puedevotar=false;
								}
								if ($query_result["Couch_Visible"]==1){
									$couch_visible=true;
								}else{
									$couch_visible=false;
								}
							echo'
							<tbody>';
								echo '<tr>
									<td class="center">'.$titulo.'</td>
									<td class="center">'.$fechainicio.'</td>
									<td class="center">'.$fechafin.'</td>';
									if ($couch_visible) {
										echo '<td class="center">
											<form action="vercouch.php" method="post">
												<input type="hidden" name="id" value="'.$idcouch.'">
												<input class="waves-effect waves-light btn light-green  z-depth-2" type="submit" value="Ver Couch">
											</form>
										</td>';
									}else{
										echo '<td class="center"><a class="waves-effect waves-light btn z-depth-2 disabled">Couch Eliminado</a></td>';
									}
									if (($puedevotar)&&($couch_visible)){
										echo '<td class="center"><a class="center waves-effect waves-light btn yellow darken-3 z-depth-2 modal-trigger" data-idcouch="'.$idcouch.'" data-idreserva="'.$idreserva.'" href="#modal_pun">Calificar</a></td>';
									}
								echo'
								</tr>
							</tbody>';
							} ?>
						</table>
					<?php
					} else{
					echo '<br>
						<div class="center grey-text text-darken-2">
							<h5>No existen estadías.</h5>
						</div>
						<br>
						<br>
						<br>';
					}
					?>
						<div class="center">
							<input class="waves-effect waves-light btn light-green z-depth-2" type="button" value="Volver" onClick="location.href='misreservas.php'">
						</div>
						<br>
						<br>
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
						echo '<li class="waves-effect"><a href="misestadias.php?pagina='.$paginaant.'"><i class="material-icons">chevron_left</i></a></li>';
					}
					if ($total_paginas > 1){
						for ($i=1;$i<=$total_paginas;$i++){ 
							if ($pagina == $i){
								//si muestro el índice de la página actual, no coloco enlace 
								echo '<li class="active light-green"><a href="#!">'.$pagina.'</a></li>';
							}else{
								echo '<li class="waves-effect"><a href="misestadias.php?pagina='.$i.'">'.$i.'</a></li>';
							}
						}
						if ($pagina==$total_paginas){
							//echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
						}else{
							$paginapos=$pagina+1;
							echo '<li class="waves-effect"><a href="misestadias.php?pagina='.$paginapos.'"><i class="material-icons">chevron_right</i></a></li>';
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
  		<script type="text/javascript" src="js/funciones.js"></script>
  		<!-- Inicializacion de JS -->
  		<script type="text/javascript">
  			$(document).ready(function(){
				$(".parallax").parallax();
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
				$('.modal-trigger').leanModal();
				$(document).on("click", ".modal-trigger", function () {
					var idcouch = $(this).data('idcouch');
					var idreserva = $(this).data('idreserva');
					var fechainicio = $(this).data('fechainicio');
					var fechafin = $(this).data('fechafin');
					$(".modal-content #idcouch").val( idcouch );
					$(".modal-content #idreserva").val( idreserva );
					$(".modal-content #fini").val (fechainicio);
					$(".modal-content #ffin").val (fechafin);
				});
				$('.datepicker').pickadate({
					min:'Today',
					max:730,
					selectYears: 2,
					selectMonths: true,
					formatSubmit: 'yyyy-mm-dd',
					hiddenName: true
				});
  			});
  		</script>
	</body>

</html>
<?php 
	}
?>