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
		$consulta="SELECT * FROM usuario WHERE Id_Usuario='$idusuario' and Visible=1";
		$consulta_execute = $conexion->query($consulta);
		if ($consulta_execute->num_rows==0){
			header("location: ../funciones/cerrar_sesion.php");
		}
		$resultado=$consulta_execute->fetch_assoc();
		$tipo=$resultado['Id_TipoDeUsuario'];
		if ($tipo == 2){
		$nombreusuario=$resultado["Nombre"].' '.$resultado["Apellido"];
		$premium=$resultado["Premium"];
    //Variables
			if(empty($_POST['nombretipo'])){	
				header("Location: tiposdecouch.php");
			}else{
				$id = $_POST['nombretipo'];
			}
			$consulta="SELECT * FROM tipodecouch WHERE Id_Tipo='$id'";
			$consulta_execute = $conexion->query($consulta);
			$resultado=$consulta_execute->fetch_assoc();
			$nombretipo=$resultado['Nombre'];
?>

<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Modificar tipo de couch</title>
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
					<li><a href="../ayuda.php#tiposdecouch" class="light-green"><i class="large material-icons">help_outline</i></a></li>
				</ul>
				<!-- Opciones  de menu lateral-->
				<ul class="side-nav" id="menulateral">
					<li><a href="../index_login.php"  class="light-green-text">Inicio</a></li>
					<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_couchs">Couchs y Reservas</a></li>
					<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_lateral_admin">Panel Administrador</a></li>
					<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_cuenta">Mi cuenta</a></li>
					<li><a href="../funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
					<li><a href="../ayuda.php#tiposdecouch" class="light-green"><i class="large material-icons">help_outline</i></a></li>
				</ul>
			</div>    
		</nav>
    </div>
		<!-- Contenido de pagina--> 
        <div class="parallax-container-mio  z-depth-3">
        	<div class="parallax fondo-registro"></div>
        	<div class="container"> 
    	    	<div class="row">
                	<br>
        	    	<div class="col s12 center grey-text text-darken-2">
                        <h1> Modificar tipo de couch </h1>
                    </div>
					<!-- Inicio del Formulario-->
                    <form class="col s12 center" name="login" method="POST" action="funciones/mod_tipoc.php">
      					<div class="row">
       				 		<div class="input-field col s4 offset-s4">
                      <?php 
          						  echo 
                          '<input name="tipocouch" id="tipocouch" maxlength="30" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚüÜ\s]+" value="'.$nombretipo.'" type="text" class="validate" required="required">
          						    <label class="active" data-error="Solo se admiten letras" for="tipocouch">Ingrese el nuevo nombre para el tipo de couch '.$nombretipo.'</label>
                          <input type="hidden" name="nombretipo" value="'.$nombretipo.'">';
                      ?>
        					</div>
						</div>
                         <br>
                         <br>
                         <div class="row">
	        				<div class="col s12registro l4 center">
                             	<input class="waves-effect waves-light btn light-green z-depth-2" type="button" value="Cancelar" onClick="location.href='tiposdecouch.php'">
                            </div>
                            <div class="col s12registro l4 center">
    	                     	<input class="waves-effect waves-light btn light-green z-depth-2" type="reset" value="Limpiar">
                            </div>
                            <div class="col s12registro l4 center">
        	                	<input class="waves-effect waves-light btn light-green z-depth-2" type="submit" value="Aceptar">
                            </div>
                    	</div>
    				</form>
					<!--Fin del Formulario-->
	            </div>
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