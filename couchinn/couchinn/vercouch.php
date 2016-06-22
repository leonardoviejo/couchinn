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
	//Consultas SQL Usuario
	$consulta = "SELECT * FROM usuario WHERE Id_Usuario='$idusuario'";
	$consulta_execute = $conexion->query($consulta);
	$resultado=$consulta_execute->fetch_assoc();
	$tipo= $resultado["Id_TipoDeUsuario"];
	$nombreusuario=$resultado["Nombre"].' '.$resultado["Apellido"];
	$premiumusuario=$resultado["Premium"];

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
								</ul>';}
							else{ echo '
								<li><a href="registro.php"  class="light-green-text">Registrarse</a></li>
								<li><a href="login.php" class="light-green-text">Iniciar Sesión</a></li>
							</ul>
							<!-- Opciones  de menu al costado-->
							<ul class="side-nav" id="menulateral">
								<li><a href="registro.php"  class="light-green-text">Registrarse</a></li>
								<li><a href="login.php" class="light-green-text">Iniciar Sesión</a></li>
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
				<div class="row">
					<div class="col s4">
                    	<div class="left grey-text text-darken-2">
							<h4>Detalles</h4>
						</div>
                    </div>
					<?php 	if ($idusuario==$idusuariocouch){
								echo'<div class="col s4">
										<form action="modificarcouch.php" method="post">
											<input type="hidden" name="id" value="'.$idcouch.'">
											<input class="right waves-effect waves-light btn yellow darken-3 z-depth-2" type="submit" value="Modificar">
										</form>
									</div>
									<div class="col s4">
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
				<div class="row">
					<div class="col s12">
						<h4 class="grey-text text-darken-2">Comentarios</h4>
						<div class="divider"></div>
							<div class="section">
								<h6><b>Carlos</b></h6>
								<p>Comentario Comentario Comentario Comentario Comentario Comentario Comentario Comentario
								Comentario Comentario Comentario Comentario Comentario Comentario Comentario Comentario
								</p>
							</div>

						<div class="divider"></div>
							<div class="section">
								<h6><b>Sandra</b></h6>
								<p>Comentario Comentario Comentario Comentario Comentario Comentario Comentario Comentario
								Comentario Comentario Comentario </p>
							</div>
						<div class="divider"></div>
							<div class="section">
								<h6><b>Clara</b></h6>
								<p>Comentario Comentario Comentario Comentario Comentario Comentario Comentario Comentario
								Comentario Comentario Comentario Comentario Comentario Comentario Comentario Comentario
								Comentario Comentario Comentario ComentarioComentario</p>
							</div>
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
  			});
  		</script>
	</body>

</html>
