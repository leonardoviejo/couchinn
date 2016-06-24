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
	$hoy = date('Y-m-d');

	//Conteo de paginado de resultado.
	$TAMANO_PAGINA=5;
	if(!isset($_GET['pagina'])) {
		$pagina=1;
		$inicio=0;
	}else{
		$pagina = $_GET["pagina"];
		$inicio = ($pagina - 1) * $TAMANO_PAGINA;
	}
	
	//Entreda de id de couchs
	if (isset($_GET['idcouch'])){
		$idcouch=$_GET['idcouch'];
	}else{
		header("Location: miscouchs.php");
	}
	
	// Selecciono el couch del usuario para sacar datos
	$consulta_couch = "SELECT * FROM couch WHERE Id_Usuario='$idusuario' AND Id_Couch='$idcouch' AND Visible=1";
	$couch_usuario = $conexion->query($consulta_couch);
	if(!$couch_usuario->num_rows) {
		header("Location: miscouchs.php");
	}
	$couch = $couch_usuario->fetch_array();
	$titulo = $couch['Titulo'];
	$idlocalidad= $couch['Id_Localidad'];
	$idprovinvia= $couch['Id_Provincia'];
	$idtipo= $couch['Id_TipoDeCouch'];
	
	//Consulta nombre de tipo de couch_usuario
	$consulta_tipocouch = "SELECT * FROM tipodecouch WHERE Id_Tipo='$idtipo'";
	$couch_tipo = $conexion->query($consulta_tipocouch);
	$couchtipo = $couch_tipo->fetch_array();
	$nombredetipo=$couchtipo['Nombre'];
		
	// Obtengo la ubicacion del couch
	$consultaubicacion= "SELECT l.Localidad as Localidad, p.Provincia as Provincia FROM localidades l inner JOIN provincias p ON l.Id_Provincia=p.Id WHERE l.Id='$idlocalidad'";
	$resultadoubicacion = $conexion->query($consultaubicacion);
	$resultado = $resultadoubicacion->fetch_assoc();
	$ubicacion = $resultado["Localidad"].', '.$resultado["Provincia"];
	
	// Selecciono las reservas del couch del usuario para mostrar en el paginado
	$consulta = "SELECT * FROM reserva WHERE Visible=1 AND Id_Couch='$idcouch'";
	$consulta_execute = $conexion->query($consulta);
	$total_resultados=$consulta_execute->num_rows;
	$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);
	
	//Obtengo las reservas de un couch
	$consulta = "SELECT r.Id_Reserva, r.Id_Usuario, r.FechaInicio, r.FechaFin, r.Estado, r.Canc_Huesped, r.FechaAlta, u.Nombre AS Nombre, u.Apellido AS Apellido, c.Titulo AS Titulo FROM reserva r inner JOIN couch c ON r.Id_Couch = c.Id_Couch inner JOIN usuario u ON r.Id_Usuario = u.Id_Usuario WHERE r.Visible=1 AND r.Id_Couch='$idcouch' ORDER BY Estado='vencida', Estado='cancelada', Estado='rechazada', Estado='confirmada', Estado='espera' LIMIT ".$inicio.",".$TAMANO_PAGINA."";
	$consulta_execute = $conexion->query($consulta);
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Reservas de <?php echo $titulo?></title>
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

		<!-- Comienzo del modal de puntuacion-->
		<div id="modal_pun" class="modal">
    		<div class="modal-content">
				<br>
      			<h4>Este usuario ha visitado tu couch!!!</h4>
				<br>
      			<p>Por favor puntua y escribe un breve comentario acerca de tu experiencia con este usuario.</p>
				<br>
				<form name="puntuacion" method="post" action="funciones/puntuahuesped.php">
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
						echo '<input type="hidden" name="idusuario" value="'.$idusuario.'">';
					?>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Puntuar">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin del modal de puntuacion -->
		
		<!-- Comienzo del modal de Cancelar -->
		<div id="modal_cancelar" class="modal">
    		<div class="modal-content">
				<br>
      			<h4>Estás cancelando una reserva confirmada!!!</h4>
				<br>
      			<p>Este paso no se puede deshacer. Ten en cuenta que esto permite que el huesped pueda calificarte de forma negativa.</p>
				<br>
				<form name="cancelar" method="post"  action="funciones/canc_reserva_couch.php">
					<?php echo '<input type="hidden" name="idreserva" id="idreserva" >'; ?>
					<br>
					<div class="divider"></div>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Cancelar Reserva">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin del modal de Cancelar -->
		
		<!-- Comienzo del modal de puntuacion huesped por cancelacion-->
		<div id="modal_pun_cou" class="modal">
    		<div class="modal-content">
				<br>
      			<h4>El usuario que habia solicitado la reserva la ha cancelado!!!</h4>
				<br>
      			<p>Por favor puntua y escribe un breve comentario.</p>
				<br>
				<form name="puntuacion" method="post" action="funciones/puntuahuespedcancelado.php">
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
						echo '<input type="hidden" name="idusuario" value="'.$idusuario.'">';						
					?>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Puntuar">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin del modal de puntuacion huesped por cancelacion-->
		
		<!-- Contenido de pagina-->
        <div class="parallax-container-mio  z-depth-3">
        	<div class="parallax fondo-registro"></div>
        		<br>
        	    <div class="center grey-text text-darken-2">
                    <h1> Reservas de <?php echo $titulo?> </h1>
                </div>
				<br>
				<div class="divider"></div>
				<div class="row">
					<div class="col s6 offset-s3 center grey-text text-darken-2">
						<?php echo '<h5><b>Ubicación: '.$ubicacion.' </b></h5>'; ?>
					</div>
				</div>
				<div class="row">
					<div class="col s6 offset-s3 center grey-text text-darken-2">
						<?php echo '<h5><b>Tipo: '.$nombredetipo.' </b></h5>'; ?>
					</div>
				</div>
				<div class="divider"></div>
				<div>
					<?php if($consulta_execute->num_rows) {
							echo '<table class="responsive-table">
										<thead>
											<tr>
												<th data-field="name" class="center">Nombre de Huesped</th>
												<th data-field="name" class="center">Fecha de inicio</th>
												<th data-field="name" class="center">Fecha de fin</th>
												<th data-field="name" class="center">Estado</th>
												<th data-field="name" class="center">Fecha de Solicitud</th>
											</tr>
										</thead>';
							while($query_result = $consulta_execute->fetch_array()) {
								$idreserva=$query_result['Id_Reserva'];
								$idusuariosolicitud=$query_result['Id_Usuario'];
								$estado=$query_result['Estado'];
								$nombre = $query_result['Nombre'].' '.$query_result['Apellido'];
								$fechainicio = $query_result['FechaInicio'];
								$fechainicio = date('d-m-Y', strtotime($fechainicio));
								$fechafin = $query_result['FechaFin'];
								$fechafin = date('d-m-Y', strtotime($fechafin));
								$fechaalta = $query_result['FechaAlta'];
								$fechaalta = date('d-m-Y', strtotime($fechaalta));
								if (($query_result['FechaFin']<$hoy)&&($query_result['Calif_Couch']==0)&&($query_result['Estado']=='confirmada')) {
									$puedevotar=true;
								}else{
									$puedevotar=false;
								}
								echo '<tbody>';
								if ($estado=='espera'){
									echo 	'<tr>
												<td bgcolor="#ffff99" class="center">'.$nombre.'</td>
												<td bgcolor="#ffff99" class="center">'.$fechainicio.'</td>
												<td bgcolor="#ffff99" class="center">'.$fechafin.'</td>
												<td bgcolor="#ffff99" class="center">'.ucwords(strtolower($estado)).'</td>
												<td bgcolor="#ffff99" class="center">'.$fechaalta.'</td>
												<td bgcolor="#ffff99" class="center"><a class="center waves-effect waves-light btn blue z-depth-2" type="button" onClick="location.href=`verperfil.php?id='.$idusuariosolicitud.'`">Ver Perfil</a></td>
												<td bgcolor="#ffff99" class="center">
													<form action="funciones/rechazareserva.php" method="post">
														<input type="hidden" name="idreserva" value="'.$idreserva.'">
														<input class="waves-effect waves-light btn red z-depth-2" type="submit" value="Rechazar">
													</form>
												</td>
												<td bgcolor="#ffff99" class="center">
													<form action="aceptareserva.php" method="post">
														<input type="hidden" name="idreserva" value="'.$idreserva.'">
														<input class="waves-effect waves-light btn light-green  z-depth-2" type="submit" value="Aceptar">
													</form>
												</td>
											</tr>';
								}else{
									if ($estado=='confirmada'){
									echo 	'<tr>
												<td bgcolor="#b2d8b2" class="center">'.$nombre.'</td>
												<td bgcolor="#b2d8b2" class="center">'.$fechainicio.'</td>
												<td bgcolor="#b2d8b2" class="center">'.$fechafin.'</td>
												<td bgcolor="#b2d8b2" class="center">'.ucwords(strtolower($estado)).'</td>
												<td bgcolor="#b2d8b2" class="center">'.$fechaalta.'</td>
												<td bgcolor="#b2d8b2" class="center"><a class="center waves-effect waves-light btn blue z-depth-2" type="button" onClick="location.href=`verperfil.php?id='.$idusuariosolicitud.'`">Ver Perfil</a></td>';
												$calificar=false;
												$cancelar=false;
												if($query_result['FechaInicio']>$hoy) {
													echo '<td bgcolor="#b2d8b2" class="center"><a class="waves-effect waves-light btn red z-depth-2 modal-trigger" data-idreserva="'.$idreserva.'" href="#modal_cancelar">Cancelar</a></td>';
													$cancelar=true;
												}
												if ($puedevotar){
													echo '<td bgcolor="#b2d8b2" class="center"><a class="waves-effect waves-light btn yellow darken-3 z-depth-2 modal-trigger" data-idreserva="'.$idreserva.'" href="#modal_pun">Calificar</a></td>';
													$calificar=true;
												}
												if (!$cancelar) {
													echo '<td bgcolor="#b2d8b2" class="center"></td>';
												}
												if (!$calificar) {
													echo '<td bgcolor="#b2d8b2" class="center"></td>';
												}
												echo '</tr>';
									}else{
										if ($estado=='rechazada'){
										echo 	'<tr>
													<td bgcolor="#ffb2b2" class="center">'.$nombre.'</td>
													<td bgcolor="#ffb2b2" class="center">'.$fechainicio.'</td>
													<td bgcolor="#ffb2b2" class="center">'.$fechafin.'</td>
													<td bgcolor="#ffb2b2" class="center">'.ucwords(strtolower($estado)).'</td>
													<td bgcolor="#ffb2b2" class="center">'.$fechaalta.'</td>
													<td bgcolor="#ffb2b2" class="center"><a class="center waves-effect waves-light btn blue z-depth-2" type="button" onClick="location.href=`verperfil.php?id='.$idusuariosolicitud.'`">Ver Perfil</a></td>
													<td bgcolor="#ffb2b2" class="center"></td>
													<td bgcolor="#ffb2b2" class="center"></td>
											</tr>';	
										}else{
											if ($estado=='cancelada'){
											echo 	'<tr>
														<td bgcolor="#cccccc" class="center">'.$nombre.'</td>
														<td bgcolor="#cccccc" class="center">'.$fechainicio.'</td>
														<td bgcolor="#cccccc" class="center">'.$fechafin.'</td>
														<td bgcolor="#cccccc" class="center">'.ucwords(strtolower($estado)).'</td>
														<td bgcolor="#cccccc" class="center">'.$fechaalta.'</td>
														<td bgcolor="#cccccc" class="center"><a class="center waves-effect waves-light btn blue z-depth-2" type="button" onClick="location.href=`verperfil.php?id='.$idusuariosolicitud.'`">Ver Perfil</a></td>';
														if (($query_result['Canc_Huesped']==1)&&($query_result['Calif_Couch']==0)){
															echo '<td bgcolor="#cccccc" class="center"><a class="center waves-effect waves-light btn yellow darken-3 z-depth-2 modal-trigger" data-idcouch="'.$idcouch.'" data-idreserva="'.$idreserva.'" href="#modal_pun_cou">Calificar</a></td>';
														}else{
															echo '<td bgcolor="#cccccc" class="center"></td>';
														}
														echo '
															<td bgcolor="#cccccc" class="center"></td>
											</tr>';	
											}else{ //Vencida
											echo 	'<tr>
														<td bgcolor="#c7e9ed" class="center">'.$nombre.'</td>
														<td bgcolor="#c7e9ed" class="center">'.$fechainicio.'</td>
														<td bgcolor="#c7e9ed" class="center">'.$fechafin.'</td>
														<td bgcolor="#c7e9ed" class="center">'.ucwords(strtolower($estado)).'</td>
														<td bgcolor="#c7e9ed" class="center">'.$fechaalta.'</td>
														<td bgcolor="#c7e9ed" class="center"><a class="center waves-effect waves-light btn blue z-depth-2" type="button" onClick="location.href=`verperfil.php?id='.$idusuariosolicitud.'`">Ver Perfil</a></td>
														<td bgcolor="#c7e9ed" class="center"></td>
														<td bgcolor="#c7e9ed" class="center"></td>
													</tr>';	
											}
										}
									}
								}
								echo 		'</tr>
										</tbody>';
							}
							echo '</table>
				</div>';
						}else{
							echo 	'<div class="center grey-text text-darken-2">
										<h5>No tienes reservas para este couch</h5>
									</div>';
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
						echo '<li class="waves-effect"><a href="reservasdecouch.php?idcouch='.$idcouch.'&pagina='.$paginaant.'"><i class="material-icons">chevron_left</i></a></li>';
					}
					if ($total_paginas > 1){
						for ($i=1;$i<=$total_paginas;$i++){
							if ($pagina == $i){
								//si muestro el índice de la página actual, no coloco enlace
								echo '<li class="active light-green"><a href="#!">'.$pagina.'</a></li>';
							}else{
								echo '<li class="waves-effect"><a href="reservasdecouch.php?idcouch='.$idcouch.'&pagina='.$i.'">'.$i.'</a></li>';
							}
						}
						if ($pagina==$total_paginas){
							//echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
						}else{
							$paginapos=$pagina+1;
							echo '<li class="waves-effect"><a href="reservasdecouch.php?idcouch='.$idcouch.'&pagina='.$paginapos.'"><i class="material-icons">chevron_right</i></a></li>';
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
				$('.modal-trigger').leanModal();
				$(document).on("click", ".modal-trigger", function () {
					var idreserva = $(this).data('idreserva');
					$(".modal-content #idreserva").val( idreserva );
				});
  			});
  		</script>
	</body>

</html>
<?php
	}
?>
