<!doctype html>
<?php
	require_once("funciones/sesion.class.php");

	$sesion = new sesion();
	$idusuario = $sesion->get("id");

	include('funciones/config.php');
	
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
	
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Ayuda</title>
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
									<li><a href="ayuda.php" class="light-green"><i class="large material-icons">help_outline</i></a></li>
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
									<li><a href="ayuda.php" class="light-green"><i class="large material-icons">help_outline</i></a></li>
								</ul>';}
							else{ echo '
								<li><a href="registro.php"  class="light-green-text">Registrarse</a></li>
								<li><a href="login.php" class="light-green-text">Iniciar Sesión</a></li>
								<li><a href="ayuda.php" class="light-green"><i class="large material-icons">help_outline</i></a></li>
							</ul>
							<!-- Opciones  de menu al costado-->
							<ul class="side-nav" id="menulateral">
								<li><a href="registro.php"  class="light-green-text">Registrarse</a></li>
								<li><a href="login.php" class="light-green-text">Iniciar Sesión</a></li>
								<li><a href="ayuda.php" class="light-green"><i class="large material-icons">help_outline</i></a></li>
							</ul>';

							}?>
				</div>
			</nav>
		</div>
		
		<!-- Contenido de pagina-->
        <div class="parallax-container-mio z-depth-3">
        	<div class="parallax fondo-registro"></div>
        	<div class="container">
    	    	<div class="row">
					<div class="col s12 m9 l10">
						<div id="introduccion" class="section scrollspy">
							<div class="center grey-text text-darken-2">
								<h1>Ayuda de CouchInn</h1>
							</div>
							<br>
							<h5>Introducción</h5>
							<p>En esta sección encontrará ayuda acerca de la utilización del sitio CouchInn. Utilice el menú lateral para desplazarse por los contenidos.</p>
						</div>
						<div id="registro" class="section scrollspy">
							<h5>Registro</h5>
							<p>La intuitiva pagina de CouchInn le permitira auto guiarse en el transcurso del registro. Complete los campos solicitados y presione aceptar.</p>
						</div>
						<div id="login" class="section scrollspy">
							<h5>Inicio de Sesión</h5>
							<p>Utilice su usuario y contraseña para iniciar sesión. Si aún no posee cuenta en CouchInn puede crearla presionando <a href="registro.php">aquí</a> o en el menú Registrarse.</p>
						</div>
						<div id="recuperarcuenta" class="section scrollspy">
							<h5>Recuperación de Cuenta</h5>
							<p>Si ha perdido su contraseña puede acceder a través de la sección Iniciar Sesión a la Opción "Olvidé mi Contraseña". 
							En la cual se le pedirá el correo electronico asociado a su cuenta.	Luego de presionar "Recuperar" se enviara un correo con una nueva contraseña. 
							Puede optar por cambiarla o utilizarla como nueva contraseña.</p>
						</div>
						<div id="buscacouch" class="section scrollspy">
							<h5>Buscar un Couch</h5>
							<p>Utilice el cuadro de busqueda que se muestra en la pagina de inicio del sitio. Podrá utilizar uno o más criterios de búsqueda.
							Si se utiliza la opcion de fechas no se mostrarán aquellos Couchs que estén ocupados entre esas fechas.</p>
						</div>
						<div id="vercouch" class="section scrollspy">
							<h5>Ver informacion de un Couch</h5>
							<p>Presionando el botón "Ver Couch" usted podrá acceder a los detalles del mismo como por ejemplo: Ubicación, Tipo de Couch, Nombre del Dueño, Puntuacion, Capacidad, Comentarios y Descripción.
							Así como poder solicitar reserva, ver puntajes y realizar preguntas (Solo los <b>usuarios registrados</b> en CouchInn podran acceder a estas operaciones).</p>
						</div>
						<div id="reservar" class="section scrollspy">
							<h5>Reservar un Couch</h5>
							<p>Para crear una reserva solo tendra que presionar el botón "Reservar" en el detalle del couch, seleccionar un rango de fechas en el que desea ir y presionar Aceptar.
							Podrá ver el estado de las reservas solicitadas en el menú "Mis Reservas".</p>
						</div>
						<div id="altacouch" class="section scrollspy">
							<h5>Crear un Couch</h5>
							<p>Podrá publicar un nuevo couch presionando el botón "Crea tu Couch" dentro del menú "Mis Couch", para ello deberá llenar un formulario con los datos solicitados
							y deberá cargar como mínimo una foto del lugar. Luego de publicarlo, el Couch, ya estará visible y a la espera de futuras reservas.</p>
						</div>
						<div id="modificarcouch" class="section scrollspy">
							<h5>Modificar Couch</h5>
							<p>Podrá cambiar los datos ingresados en su couch presionando la opción "Modificar" dentro de "Mis Couchs" o bien presionando "Modificar" desde los detalles de dicho Couch.</p>
						</div>
						<div id="eliminarcouch" class="section scrollspy">
							<h5>Eliminar Couch</h5>
							<p>Podrá en cualquier momento eliminar su couch. Tenga en cuenta que esta acción no puede deshacerse y eliminara todos los datos asociados (Reservas, Comentarios, Puntajes, etc).</p>
						</div>
						<div id="miscouchs" class="section scrollspy">
							<h5>Mis Couchs</h5>
							<p>Desde este apartado usted podra controlar sus couch como asi tambien crear uno nuevo. Además podrá acceder a las opciones rápidas de Couchs como Eliminar, Modificar o Ver sus Detalles.</p>
						</div>
						<div id="reservasdecouch" class="section scrollspy">
							<h5>Reservas de mis Couch</h5>
							<p>En este menú podra ver las reservas que otros usuarios han realizado, ellas se encuentran separadas por Couch.
							Desde aquí se pueden aceptar o rechazar las reservas y también puntuar al huesped una vez finalizada la estadía.</p>
						</div>
						<div id="reservashuesped" class="section scrollspy">
							<h5>Reservas Realizadas</h5>
							<p>Aqui figuran las reservas realizadas por usted a otros couchs agrupadas por estado. Usted puede cancelar una reserva o acceder a puntuar un couch donde se alojó.</p>
						</div>
						<div id="misestadias" class="section scrollspy">
							<h5>Mis estadías</h5>
							<p>Aqui se visualizaran los datos de aquellos Couchs donde me alojé, estarán disponibles las opciones "Ver Detalles" y "Calificar".</p>
						</div>
						<div id="verperfil" class="section scrollspy">
							<h5>Mi Perfil</h5>
							<p>Desde este menú se podran visualizar todos los datos de su cuenta. También podrá acceder a la opcíon "Obtener Premium" como así también acceder al menú de actualización de datos del usuario</p>
						</div>
						<div id="altapremium" class="section scrollspy">
							<h5>Ser Premium</h5>
							<p>Desde este lugar podrá convertir a su cuenta en Premium, esto habilitará la sorprendente capacidad de visualizar la foto de su couch en los resultados de búsqueda. Para ello debera ingresar los datos solitados y presionar aceptar.</p>
						</div>
						<div id="modificarperfil" class="section scrollspy">
							<h5>Modificar Perfil</h5>
							<p>Desde aquí podra modificar los datos que desee de su cuenta, también deberá completar con su contraseña para confirmar estos cambios.</p>
						</div>
						<div id="eliminarcuenta" class="section scrollspy">
							<h5>Eliminar Cuenta</h5>
							<p>Podra eliminar su cuenta cuando lo desee, también deberá completar con su contraseña para confirmar la eliminación. Tenga en cuenta que esta acción no puede deshacerse y eliminara todos los datos asociados (Couchs, Reservas, Comentarios, Puntajes, etc).</p>
						</div>
						<?php if ($tipo==2){ echo '
						<div id="ganancias" class="section scrollspy">
							<h5>Ganancias</h5>
							<p>Accediendo a esta opción a través del menú Administración de la barra superior, podrá seleccionando dos fechas, saber cual fue la ganancia entre dichos períodos.</p>
						</div>
						<div id="listadmin" class="section scrollspy">
							<h5>Listar Adminitradores</h5>
							<p>Accediendo a esta opción a través del menú Administración de la barra superior, podrá listar a los usuarios administradores del sistema.</p>
						</div>
						<div id="listarcouchs" class="section scrollspy">
							<h5>Listar Couchs</h5>
							<p>Accediendo a esta opción a través del menú Administración de la barra superior, podrá listar todos los couchs disponibles en CouchInn.</p>
						</div>
						<div id="listarusuarios" class="section scrollspy">
							<h5>Listar Usuarios</h5>
							<p>Accediendo a esta opción a través del menú Administración de la barra superior, podrá listar los usuarios activos del sistema.</p>
						</div>
						<div id="listarusuariospremium" class="section scrollspy">
							<h5>Listar Usuarios Premium</h5>
							<p>Accediendo a esta opción a través del menú Administración de la barra superior, podrá listar aquellos usuarios que han adquirido la licencia premium.</p>
						</div>
						<div id="tiposdecouch" class="section scrollspy">
							<h5>Tipos de Couch</h5>
							<p>Accediendo a esta opción a través del menú Administración de la barra superior, podrá listar tipos actuales de Couch y también crear nuevos o modificar existentes.</p>
						</div>
						<div id="modificarperfilusuario" class="section scrollspy">
							<h5>Modificar Perfil de Usuario</h5>
							<p>Accediendo a esta opción a través de listar usuarios, podrá modificar los perfiles de los usuarios y tambíén eliminar su cuenta.</p>
						</div>';
						}
						?>
					</div>
					<div class="col hide-on-small-only m3 l2">
						<div class="toc-wrapper">
							<ul class="section table-of-contents">
								<li><a href="#introduccion">Introducción</a></li>
								<li><a href="#registro">Registro</a></li>
								<li><a href="#login">Inicio de Sesión</a></li>
								<li><a href="#recuperarcuenta">Recuperación de Cuenta</a></li>
								<li><a href="#buscacouch">Buscar un Couch</a></li>
								<li><a href="#vercouch">Ver información de un Couch</a></li>
								<li><a href="#reservar">Reservar un Couch</a></li>
								<li><a href="#altacouch">Crear un Couch</a></li>
								<li><a href="#modificarcouch">Modificar Couch</a></li>
								<li><a href="#eliminarcouch">Eliminar Couch</a></li>
								<li><a href="#miscouchs">Mis Couchs</a></li>
								<li><a href="#reservasdecouch">Reservas de mis Couchs</a></li>
								<li><a href="#reservashuesped">Reservas Realizadas</a></li>
								<li><a href="#misestadias">Mis estadías</a></li>
								<li><a href="#verperfil">Mi perfil</a></li>
								<li><a href="#altapremium">Ser Premium</a></li>
								<li><a href="#modificarperfil">Modificar Perfil</a></li>
								<li><a href="#eliminarcuenta">Eliminar Cuenta</a></li>
								<?php if ($tipo==2){ echo '
								<li><a href="#ganancias">Ganancias</a></li>
								<li><a href="#listadmin">Listar Administradores</a></li>
								<li><a href="#listarcouchs">Listar Couchs</a></li>
								<li><a href="#listarusuarios">Listar Usuarios</a></li>
								<li><a href="#listarusuariospremium">Listar Usuarios Premium</a></li>
								<li><a href="#tiposdecouch">Tipos de Couchs</a></li>
								<li><a href="#modificarperfilusuario">Modificar Perfil de Usuario</a></li>';
								}
								?>
							</ul>
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
				$('.slider').slider();
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
				$('.scrollspy').scrollSpy();
				$('.toc-wrapper').pushpin({
					offset: 80
				});
  			});
  		</script>
	</body>

</html>
