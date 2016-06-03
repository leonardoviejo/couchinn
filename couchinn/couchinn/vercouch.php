<!doctype html>
<?php
	require_once("funciones/sesion.class.php");
	
	$sesion = new sesion();
	$idusuario = $sesion->get("id");
	
	include('funciones/config.php');
	//Recuperar couch concerniente
	/*if (empty($_POST['id'])){
		header("Location: ../index.php");
	}
	else
	{
		$couchId = $_POST["id"];
	}
	*/
	$couchId=2;
	//Consultas SQL Usuario
	$consulta = "SELECT * FROM usuario WHERE Id_Usuario='$idusuario'";
	$consulta_execute = $conexion->query($consulta);
	$resultado=$consulta_execute->fetch_assoc();
	$tipo= $resultado["Id_TipoDeUsuario"];
	
	//Busqueda de couch
	$consulta= "SELECT * FROM couch WHERE Id_Couch='$couchId'";
	$resulta = $conexion->query($consulta);
	if ($resulta->num_rows > 0) {
		// Data de los detalles del couch
		$row = $resulta->fetch_assoc();
		$tipoId = $row["Id_TipoDeCouch"];
		$usuarioId = $row["Id_Usuario"];
		$titulo = $row["Titulo"];
		$ciudad = $row["Ciudad"];
		$capacidad = $row["Capacidad"];
		$fechaAlta = $row["FechaAlta"];
		$descripcion = $row["Descripcion"];
		$foto1=$row["Foto1"];
		$foto2=$row["Foto2"];
		$foto3=$row["Foto3"];
		
		//Busqueda de tipo de couch
		$consulta2= "SELECT Nombre FROM tipodecouch WHERE Id_Tipo='$tipoId'";
		$resulta2 = $conexion->query($consulta2);
		$row2 = $resulta2->fetch_assoc();
		$tipoDeCouch = $row2["Nombre"];
		
		//Busqueda de nombre de usuario
		$consulta3= "SELECT Nombre, Apellido, Premium FROM usuario WHERE Id_Usuario='$usuarioId'";
		$resulta3 = $conexion->query($consulta3);
		$row3 = $resulta3->fetch_assoc();
		$usuarioCouch = $row3["Nombre"] . " " . $row3["Apellido"];
		$premium =$row3['Premium'];
	} else {
		echo"<script> alert('Couch inexistente.');</script>";
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
									<li><a href="#"  class="light-green-text">Couchs y Reservas</a></li>
									<li><a href="#"  class="light-green-text">Mi cuenta</a></li>
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
								<img src="'.$foto1.'" onerror="src=`imagenes/Logo_mini.png`">
							</li>
							<li>
								<img src="'.$foto2.'" onerror="src=`imagenes/Logo_mini.png`">
							</li>
							<li>
								<img src="'.$foto3.'" onerror="src=`imagenes/Logo_mini.png`">
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
					<div class="col s4">
                    	<input class="right waves-effect waves-light btn light-green z-depth-2" type="button" value="Reservar" onClick="#">
                    </div>
					<div class="col s4">
                    	<input class="right waves-effect waves-light btn light-green z-depth-2" type="button" value="Calificar" onClick="#">
                    </div>
				</div>
				<div class="row">
					<div class="col s4">
						<p class="left"><?php echo $ciudad ?></p>
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
																				echo 'persona.';
																			}
																			?></p>
					</div>
					<div class="col s4">
						<p class="center">Agregado el: <?php echo $fechaAlta ?></p>
					</div>
					<div class="col s4">
						<p class="right">Calificación: 4</p>
					</div>
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
  		<!-- Inicializacion de JS -->
  		<script type="text/javascript">
  			$(document).ready(function(){
				$(".parallax").parallax();
				$('.slider').slider();
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
  			});
  		</script>
	</body>

</html>