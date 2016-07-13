<!doctype html>
<?php
	require_once("funciones/sesion.class.php");

	$sesion = new sesion();
	$idusuario = $sesion->get("id");

	include('funciones/config.php');
	//Recuperar couch concerniente
	if (empty($_POST['id'])){
		if(empty($_GET['id'])){
			header("Location: index.php");
		}else{
			$idcouch=$_GET['id'];
		}
	}
	else
	{
		$idcouch = $_POST["id"];
	}
	//Conteo de paginado de resultado.
	$TAMANO_PAGINA=10;
	if(!isset($_GET['pagina'])) {
		$pagina=1;
		$inicio=0;
	}else{
		$pagina = $_GET["pagina"];
		$inicio = ($pagina - 1) * $TAMANO_PAGINA;
	}
	
	$tipo='';
	$nombreusuario='';
	$premiumusuario='';
	if ($idusuario){
		//Consultas SQL Usuario
		$consulta="SELECT * FROM usuario WHERE Id_Usuario='$idusuario' and Visible=1";
		$consulta_execute = $conexion->query($consulta);
		if ($consulta_execute->num_rows==0){
			header("location: funciones/cerrar_sesion.php");
		}
		$resultado=$consulta_execute->fetch_assoc();
		$tipo= $resultado["Id_TipoDeUsuario"];
		$nombreusuario=$resultado["Nombre"].' '.$resultado["Apellido"];
		$premiumusuario=$resultado["Premium"];
	}
	//Busqueda de couch
	$consulta= "SELECT * FROM couch WHERE Id_Couch='$idcouch' and Visible=1";
	$resulta = $conexion->query($consulta);
	if ($resulta->num_rows > 0) {
		// Data de los detalles del couch
		$row = $resulta->fetch_assoc();
		$tipoId = $row["Id_TipoDeCouch"];
		$idprovincia=$row["Id_Provincia"];
		$idlocalidad=$row["Id_Localidad"];
		$idusuariocouch = $row["Id_Usuario"];
		$titulo = $row["Titulo"];
		$capacidad = $row["Capacidad"];
		$fechaAlta = $row["FechaAlta"];
		$fechaAlta=date('d-m-Y', strtotime($fechaAlta));
		$descripcion = $row["Descripcion"];
		$foto1=$row["Foto1"];
		$foto2=$row["Foto2"];
		$foto3=$row["Foto3"];
		if ($row['Cant_Calif']==0){
			$puntajeactual=0;
		}else{
			$puntajeactual= round($row['Total_Calif']/$row['Cant_Calif']);
		}
		//Busqueda de ciudad y provincia
		$consultaubicacion= "SELECT l.Localidad as Localidad, p.Provincia as Provincia FROM localidades l inner JOIN provincias p ON l.Id_Provincia=p.Id WHERE l.Id='$idlocalidad'";
		$resultadoubicacion = $conexion->query($consultaubicacion);
		$resultado = $resultadoubicacion->fetch_assoc();
		$ubicacion = $resultado["Localidad"].', '.$resultado["Provincia"];

		//Busqueda de tipo de couch
		$consulta2= "SELECT Nombre FROM tipodecouch WHERE Id_Tipo='$tipoId'";
		$resulta2 = $conexion->query($consulta2);
		$row2 = $resulta2->fetch_assoc();
		$tipoDeCouch = $row2["Nombre"];

		//Busqueda de nombre de usuario dueño de couch
		$consulta3= "SELECT Nombre, Apellido FROM usuario WHERE Id_Usuario='$idusuariocouch'";
		$resulta3 = $conexion->query($consulta3);
		$row3 = $resulta3->fetch_assoc();
		$usuarioCouch = $row3["Nombre"] . " " . $row3["Apellido"];
		
		//Busca comentarios para listar
		$consulta4 = "SELECT * FROM comentario WHERE Visible=1 and Id_Couch='$idcouch' ORDER BY FechaAlta ASC";
		$ejecucion = $conexion->query($consulta4);
		$total_resultados=$ejecucion->num_rows;
		$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);
		$consulta4 = "SELECT c.Id_Comentario, c.Id_Usuario, c.Mensaje, c.Respondido, c.Respuesta, c.FechaAlta, u.Nombre, u.Apellido FROM comentario c inner JOIN usuario u ON c.Id_Usuario = u.Id_Usuario WHERE c.Visible=1 and Id_Couch='$idcouch' ORDER BY FechaAlta ASC LIMIT ".$inicio.",".$TAMANO_PAGINA."";
		$ejecucion = $conexion->query($consulta4);
		
		//Si es mi couch verifico el estado
		if ($idusuario == $idusuariocouch) {
			$hoy = date('Y-m-d');
			$consulta5 = "SELECT * FROM reserva WHERE Id_Couch='$idcouch' AND Estado='confirmada' AND FechaInicio <= '$hoy' AND FechaFin >= '$hoy'";
			$resulta5 = $conexion->query($consulta5);
			if($resulta5->num_rows) {
				$ocupado=true;
			}else{
				$ocupado=false;
			}
		}
		
	} else {
		?>
		<script>	alert('Couch inexistente.');
					location.href="../index.php";
		</script>
		<?php
	}
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - <?php echo $titulo ?></title>
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
		<?php
			if($tipo==1||$tipo==2){
				echo '
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
					</ul>';
			}
		?>
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
						<?php
							if($tipo==1||$tipo==2){
								echo '
									<li><a href="miperfil.php"  class="grey-text text-darken-2">Bienvenido, '.$nombreusuario.'!!!</a></li>';
									if ($premiumusuario==1){
										echo'
										<li><a href="#" class="light-green">Cuenta Premium</a></li>
										<li><a href="#" class="light-green"><i class="large material-icons">star</i></a></li>';
									}
									echo '
									<li><a href="index_login.php"  class="light-green-text">Inicio</a></li>
									<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_couchs">Couchs y Reservas</a></li>';
									if($tipo==2){
										echo '<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_admin">Panel Administrador</a></li>';
									}
									echo '
									<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_cuenta">Mi cuenta</a></li>
									<li><a href="funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
									<li><a href="ayuda.php#vercouch" class="light-green"><i class="large material-icons">help_outline</i></a></li>
							</ul>
							<!-- Opciones  de menu lateral-->
								<ul class="side-nav" id="menulateral">
									<li><a href="index_login.php"  class="light-green-text">Inicio</a></li>
									<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_couchs">Couchs y Reservas</a></li>';
										if($tipo==2){
											echo '<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_lateral_admin">Panel Administrador</a></li>';
										}
									echo '
									<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_cuenta">Mi cuenta</a></li>
									<li><a href="funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
									<li><a href="ayuda.php#vercouch" class="light-green"><i class="large material-icons">help_outline</i></a></li>
								</ul>';}
							else{ echo '
								<li><a href="registro.php"  class="light-green-text">Registrarse</a></li>
								<li><a href="login.php" class="light-green-text">Iniciar Sesión</a></li>
								<li><a href="ayuda.php#vercouch" class="light-green"><i class="large material-icons">help_outline</i></a></li>
							</ul>
							<!-- Opciones  de menu al costado-->
							<ul class="side-nav" id="menulateral">
								<li><a href="registro.php"  class="light-green-text">Registrarse</a></li>
								<li><a href="login.php" class="light-green-text">Iniciar Sesión</a></li>
								<li><a href="ayuda.php#vercouch" class="light-green"><i class="large material-icons">help_outline</i></a></li>
							</ul>';

							}?>
				</div>
			</nav>
		</div>

		<!-- Comienzo de los modals para usuarios no registrados -->
		<div id="modal_noreg" class="modal">
    		<div class="modal-content">
      			<h4>Únete a CouchInn</h4>
      			<p>Para utilizar todas las funciones del sitio CouchInn debes crear una cuenta.</p> 
      			<p>Si ya tienes una cuenta, no olvides iniciar sesion</p>
    		</div>
    		<div class="divider"></div>
    		<div class="modal-footer">
    			<a class="right waves-effect waves-light btn-flat light-green-text z-depth-2" href="registro.php">Crea tu cuenta</a>
    			<a class="right waves-effect waves-light btn-flat light-green-text z-depth-2" href="login.php">Iniciar sesion</a>
    			<a class="right waves-effect waves-light btn-flat light-green-text z-depth-2 modal-action modal-close">Cancelar</a>
    		</div>
  		</div>
		<!-- Fin de los modals para usuarios no registrados -->

		<!-- Comienzo de los modals para usuarios registrados-->
		<div id="modal_reg" class="modal">
    		<div class="modal-content">
				<br>
				<br>
      			<h4>Haz tu reserva!!!</h4>
				<br>
				<br>
      			<p>Es muy sencillo solamente elige la fecha de comienzo y fin de tu reserva y presiona Solicitar Reserva, luego deberás esperar que el dueño del couch confirme o rechace tu solicitud.</p>
				<p>Puedes consultar el estado de tus reservas en el menú "Mis Reservas".</p>
				<br>
				<form name="reserva" method="post" onSubmit="return validarReserva()" action="funciones/reservar.php">
					<div class="input-field">
						<div class="grey-text"> Comienzo de reserva</div>
						<?php echo '<input type="hidden" name="idusuario" value="'.$idusuario.'">
						<input type="hidden" name="idcouch" value="'.$idcouch.'">';
						?>
						<input name="fechainicio" type="date" class="datepicker" id="fechainicio" title="Fecha de Inicio">
	                </div>
					<br>
					<div class="center">
						Hasta
					</div>
					<br>
					<div class="input-field">
						<div class="grey-text"> Fin de reserva</div>
						<input name="fechafin" type="date" class="datepicker" id="fechafin" title="Fecha de Fin">
	                </div>
					<br>
					<br>
					<div class="divider"></div>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Solicitar Reserva">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin de los modals para usuarios registrados -->
		
		<!-- Comienzo del modal para preguntar-->
		<div id="modal_pre" class="modal">
    		<div class="modal-content">
      			<h4>Haz una pregunta al dueño del Couch!!!</h4>
				<br>
				<form name="pregunta" method="post" action="funciones/preguntar.php">
					<div class="input-field">
						<textarea id="pregunta" name="pregunta" class="materialize-textarea" length="250" maxlength="250" class="validate" required="required"></textarea>
						<label for="pregunta">Pregunta</label>
					</div>
					<?php echo '<input type="hidden" name="idcouch" value="'.$idcouch.'">';
						echo '<input type="hidden" name="idusuario" value="'.$idusuario.'">';						
					?>
					<br>
					<br>
					<div class="divider"></div>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Preguntar">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin del modal para preguntar-->
		
		<!-- Comienzo del modal para responder-->
		<div id="modal_res" class="modal">
    		<div class="modal-content">
      			<h4>Responde la pregunta!!!</h4>
				<br>
				<form name="respuesta" method="post" action="funciones/responder.php">
					<div class="grey-text" > Pregunta: </div>
					<input disabled type="text" name="mensaje" id="mensaje" value="">
					<br>
					<div class="input-field">
						<textarea id="respuesta" name="respuesta" class="materialize-textarea" length="250" maxlength="250" class="validate" required="required"></textarea>
						<label for="respuesta">Respuesta</label>
					</div>
					<input type="hidden" name="idmensaje" id="idmensaje">
					<?php echo '<input type="hidden" name="idcouch" value="'.$idcouch.'">';?>
					<br>
					<br>
					<div class="divider"></div>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Responder">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin del modal para responder-->
		
		<!-- Comienzo del modal para eliminar mensaje-->
		<div id="modal_borrar" class="modal">
    		<div class="modal-content">
      			<br>
      			<h4>Estás borrando un mensaje!!!</h4>
				<br>
      			<p>Este paso no se puede deshacer.</p>
				<br>
				<form name="eliminar" method="post" action="funciones/baja_comentario.php">
					<input type="hidden" name="idcomentario" id="idmensaje">
					<?php echo '<input type="hidden" name="idcouch" value="'.$idcouch.'">';?>
					<?php echo '<input type="hidden" name="idusuario" value="'.$idusuario.'">';?>
					<br>
					<br>
					<div class="divider"></div>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Borrar">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin del modal para eliminar mensaje-->
		
		<!-- Contenido de pagina-->
        <div class="parallax-container-mio z-depth-3">
        	<div class="parallax fondo-registro"></div>
        	<div class="container">
    	    	<div class="row">
        	    	<div class="col s12">
                    	<div class="col s12 center grey-text text-darken-2">
							<h1><?php echo $titulo ?></h1>
						</div>
                    </div>
				</div>
					<!-- Inicio del los detalles-->
				<div class="row">
					<div class="slider">
						<ul class="slides">
						<?php echo'
							<li>
								<img src="'.$foto1.'" onerror="src=`imagenes/Logo_mini.jpg`">
							</li>
							<li>
								<img src="'.$foto2.'" onerror="src=`imagenes/Logo_mini.jpg`">
							</li>
							<li>
								<img src="'.$foto3.'" onerror="src=`imagenes/Logo_mini.jpg`">
							</li>'?>
						</ul>
					</div>
				</div>
				<div class="row valign-wrapper">
					<div class="col s3">
                    	<div class="left grey-text text-darken-2">
							<h4>Detalles</h4>
						</div>
                    </div>
					<?php 	if ($idusuario==$idusuariocouch){
								if ($ocupado) {
									echo'<div class="chip_ocupado col s1">
											Ocupado
										</div>';
								}else{
									echo'<div class="chip_disponible col s1">
											Disponible
										</div>';
								}
								echo'<div class="col s4">
										<form action="reservasdecouch.php" method="get">
											<input type="hidden" name="idcouch" value="'.$idcouch.'">
											<input class="right waves-effect waves-light btn light-green z-depth-2" type="submit" value="Ver Reservas">
										</form>
									</div>
									<div class="col s2">
										<form action="modificarcouch.php" method="post">
											<input type="hidden" name="id" value="'.$idcouch.'">
											<input class="right waves-effect waves-light btn yellow darken-3 z-depth-2" type="submit" value="Modificar">
										</form>
									</div>
									<div class="col s2">
										<form action="eliminarcouch.php" method="post">
											<input type="hidden" name="id" value="'.$idcouch.'">
											<input class="right waves-effect waves-light btn red z-depth-2" type="submit" value="Borrar">
										</form>
									</div>';
							}else{
								if ($idusuario) {
									echo '<div class="col s8">
										  	<a class="right waves-effect waves-light btn light-green z-depth-2 modal-trigger" href="#modal_reg">Reservar</a>
										  </div>';
								} else {
										echo '<div class="col s8">
											  	<a class="right waves-effect waves-light btn light-green z-depth-2 modal-trigger" href="#modal_noreg">Reservar</a>
										  	  </div>';
								}
							}
					?>
				</div>
				<div class="row">
					<div class="col s4">
						<p class="left"><?php echo $ubicacion ?></p>
					</div>
					<div class="col s4">
						<p class="center"><?php echo $tipoDeCouch ?></p>
					</div>
					<div class="col s4">
						<p class="right"><?php echo $usuarioCouch ?></p>
					</div>
				</div>
				<div class="row">
					<div class="col s4">
						<p class="left">Capacidad: <?php echo $capacidad; 	if($capacidad>1){
																				echo ' personas.';
																			}else{
																				echo ' persona.';
																			}
																			?></p>
					</div>
					<div class="col s4">
						<p class="center">Agregado el: <?php echo $fechaAlta ?></p>
					</div>
					<?php 
						if ($puntajeactual==0){
							echo '
							<div class="col s4">
								<p class="right">Calificación: 
									Couch aún no puntuado.
								</p>
							</div>';
						}else{ 
							echo '
							<div class="col s2">
								<p class="left">Calificación: 
									'.$puntajeactual.'
								</p>
							</div>
							<div class="col s2">
								<form action="comentarioscouch.php" method="get">
									<input type="hidden" name="idcouch" value="'.$idcouch.'">
									<input class="right waves-effect waves-light btn light-green z-depth-2" type="submit" value="Puntajes">
								</form>
							</div>';
						}
					?>
				</div>
				<div class="row">
					<div class="col s12">
						<p><b>Descripcion: </b><?php echo $descripcion ?></p>
					</div>
				</div>
				<div class="divider"></div>
				<br>
				<div class="row">
					<div class="col s4">
						<h4 class="grey-text text-darken-2">Comentarios</h4>
					</div>
					<?php if ($idusuario<>$idusuariocouch){
							if ($idusuario) {
								echo 	'<div class="col s8">
											<a class="right waves-effect waves-light btn light-green z-depth-2 modal-trigger" href="#modal_pre">Preguntar</a>
										</div>';
							}else{
								echo '<div class="col s8">
											  	<a class="right waves-effect waves-light btn light-green z-depth-2 modal-trigger" href="#modal_noreg">Preguntar</a>
										  	  </div>';
							}
						}
					?>
				</div>
				<div class="divider"></div>
				<br>
				<?php
				if($ejecucion->num_rows) {
					echo '<div class="row">
					<ul class="collapsible" data-collapsible="accordion">';
					while($query_result = $ejecucion->fetch_array()) {
						$idmensaje=$query_result['Id_Comentario'];
						$idusuariopreg = $query_result['Id_Usuario'];
						$nombre = $query_result["Nombre"].' '.$query_result["Apellido"];
						$mensaje = $query_result['Mensaje'];
						$fecha = date("d-m-Y H:i:s",strtotime($query_result['FechaAlta']));
						$respondido=$query_result['Respondido'];
						$consultausuarioexistente="SELECT Visible FROM usuario WHERE Id_Usuario='$idusuariopreg'";
						$ejecucionpreg = $conexion->query($consultausuarioexistente);
						$respuesta = $ejecucionpreg->fetch_assoc();
						$existeusuario=true;
						if ($respuesta['Visible']==0){
							$nombre='Usuario Eliminado';
							$existeusuario=false;
						}
						if ($respondido==0){
							if (($idusuario==$idusuariocouch)&&($existeusuario)){
								echo '
									<li>
										<div class="collapsible-header"><i class="material-icons">person</i>'.$nombre.' --	Enviado el '.$fecha.'</div>
										<div class="collapsible-body">
											<p>'.$mensaje.'</p>
											<a class="waves-effect waves-light btn light-green z-depth-2 modal-trigger" data-mensaje="'.$mensaje.'" data-idmensaje="'.$idmensaje.'" href="#modal_res">Responder</a>';
											if ($tipo==2) { //Si es admin muestra el boton borrar
												echo '<a class="right waves-effect waves-light btn red z-depth-2 modal-trigger" data-idmensaje="'.$idmensaje.'" href="#modal_borrar">Borrar</a>';
											}
										echo '
										</div>
									</li>';
							}else{	
								echo '
									<li>
										<div class="collapsible-header"><i class="material-icons">person</i>'.$nombre.' --	Enviado el '.$fecha.'</div>
										<div class="collapsible-body">
											<p>'.$mensaje.'</p>';
											if ($tipo==2) { //Si es admin muestra el boton borrar
												echo '<a class="waves-effect waves-light btn red z-depth-2 modal-trigger" data-idmensaje="'.$idmensaje.'" href="#modal_borrar">Borrar</a>';
											}
										echo'
										</div>
									</li>';
							}
						}else{
							$respuestapregunta=$query_result['Respuesta'];
							echo '
								<li>
									<div class="collapsible-header"><i class="material-icons">person</i>'.$nombre.' --	Enviado el '.$fecha.'</div>
									<div class="collapsible-body">
										<p>'.$mensaje.'</p>
										<p><b><u>Respuesta</u>:</b> '.$respuestapregunta.'</p>';
										if ($tipo==2) { //Si es admin muestra el boton borrar
											echo '<a class="waves-effect waves-light btn red z-depth-2 modal-trigger" data-idmensaje="'.$idmensaje.'" href="#modal_borrar">Borrar</a>';
										}
									echo'
									</div>
								</li>';
						}
					}
					echo '
					</ul>
					</div>';
				}else{
					echo 	'<div class="center grey-text text-darken-2">
								<h5>No existen comentarios para este Couch.</h5>
								<br>
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
							echo '<li class="waves-effect"><a href="vercouch.php?idcouch='.$idcouch.'&pagina='.$paginaant.'"><i class="material-icons">chevron_left</i></a></li>';
						}
						if ($total_paginas > 1){
							for ($i=1;$i<=$total_paginas;$i++){ 
								if ($pagina == $i){
									//si muestro el índice de la página actual, no coloco enlace 
									echo '<li class="active light-green"><a href="#!">'.$pagina.'</a></li>';
								}else{
									echo '<li class="waves-effect"><a href="vercouch.php?idcouch='.$idcouch.'&pagina='.$i.'">'.$i.'</a></li>';
								}
							}
							if ($pagina==$total_paginas){
								//echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
							}else{
								$paginapos=$pagina+1;
								echo '<li class="waves-effect"><a href="vercouch.php?idcouch='.$idcouch.'&pagina='.$paginapos.'"><i class="material-icons">chevron_right</i></a></li>';
							}
						}
					?>
				</ul>
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
				$('.slider').slider();
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
				$('.modal-trigger').leanModal();
				$('.datepicker').pickadate({
					min:'Today',
					max:730,
					selectYears: 2,
					selectMonths: true,
					formatSubmit: 'yyyy-mm-dd',
					hiddenName: true
				});
				$(document).on("click", ".modal-trigger", function () {
					var idmensaje = $(this).data('idmensaje');
					var mensaje = $(this).data('mensaje');
					$(".modal-content #idmensaje").val (idmensaje);
					$(".modal-content #mensaje").val (mensaje);
				});
  			});
  		</script>
	</body>

</html>
