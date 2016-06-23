<!doctype html>
<?php
	require_once("funciones/sesion.class.php");
	
	$sesion = new sesion();
	$idusuario = $sesion->get("id");
	
	include('funciones/config.php');

	//Consultas SQL Usuario
	$consulta = "SELECT * FROM usuario WHERE Id_Usuario='$idusuario'";
	$consulta_execute = $conexion->query($consulta);
	$resultado=$consulta_execute->fetch_assoc();
	$tipo= $resultado["Id_TipoDeUsuario"];
	$nombreusuario=$resultado["Nombre"].' '.$resultado["Apellido"];
	
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
		$consulta= "SELECT * FROM couch c WHERE Visible=1";
		$consultafinal="";
		if(isset($_GET['tcouch'])){
			$consulta.= " AND c.Id_TipoDeCouch = ".$_GET['tcouch'];
			$consultafinal.=" AND c.Id_TipoDeCouch = ".$_GET['tcouch'];
		}
		if(isset($_GET['idprovincia'])){
			$consulta.= " AND c.Id_Provincia = ".$_GET['idprovincia'];
			$consultafinal.=" AND c.Id_Provincia = ".$_GET['idprovincia'];
		}
		if((isset($_GET['idlocalidad']))&&(!empty($_GET['idlocalidad']))){
			$consulta.= " AND c.Id_Localidad = ".$_GET['idlocalidad'];
			$consultafinal.=" AND c.Id_Localidad = ".$_GET['idlocalidad'];
		}
		if((isset($_GET['cant_visitantes']))&& (!empty($_GET['cant_visitantes']))){
			$consulta.= " AND c.Capacidad = ".$_GET['cant_visitantes'];
			$consultafinal.=" AND c.Capacidad = ".$_GET['cant_visitantes'];
		}
		$consulta.=" ORDER BY Titulo ASC";
		$consultafinal.=" ORDER BY Titulo ASC LIMIT ".$inicio.",".$TAMANO_PAGINA."";
		$consulta_execute = $conexion->query($consulta);
		$total_resultados=$consulta_execute->num_rows;
		$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);
		$consulta = "SELECT c.Id_Couch, c.Id_TipoDeCouch, c.Id_Usuario, c.Titulo, c.Id_Localidad, c.Capacidad, c.Foto1, u.Premium, t.Nombre AS NombreTipo, l.localidad AS Ciudad FROM couch c inner JOIN usuario u ON c.Id_Usuario = u.Id_Usuario inner JOIN tipodecouch t ON c.Id_TipoDeCouch = t.Id_Tipo inner JOIN localidades l ON c.Id_Localidad=l.Id WHERE c.Visible=1";
		$consulta .= $consultafinal;
		$consulta_execute = $conexion->query($consulta);
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Resultados de la búsqueda</title>
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
									<li><a href="miperfil.php"  class="grey-text text-darken-2">Bienvenido, '.$nombreusuario.'!!!</a></li>
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
		<!-- Contenido de pagina--> 
        <div class="parallax-container-mio-home z-depth-3">
        	<div class="parallax transparencia"><img src="imagenes/fondo.jpg" alt=""></div>
        	<div class="container"> 
    	    	<br>
        		<br>
        		<br>
				<div class="row card-panel">
					<h5><p class="center">Resultados de la búsqueda</p></h5>
				</div>
				<br>
				<br>
				<div class="section">
					<!-- Tabla-->
					<div class="row card-panel">
					<?php if($consulta_execute->num_rows) { ?>
					
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
            					<td class="center"><h6><p class="center">No existen couchs con ese criterio de busqueda</p></h6></td>
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
									//echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_left</i></a></li>';
								}
							}else{
								$paginaant=$pagina-1;
								echo '<li class="waves-effect"><a href="resultado_busqueda.php?pagina='.$paginaant.'';
									if(isset($_GET['tcouch'])){
										echo '&tcouch='.$_GET['tcouch'].'';
									}
									if(isset($_GET['idprovincia'])){
										echo '&idprovincia='.$_GET['idprovincia'].'';
									}
									if(isset($_GET['idlocalidad'])){
										echo '&idlocalidad='.$_GET['idlocalidad'].'';
									}
									if(isset($_GET['cant_visitantes'])){
										echo '&cant_visitantes='.$_GET['cant_visitantes'].'';
									}
									echo '"><i class="material-icons">chevron_left</i></a></li>';
							}
							if ($total_paginas > 1){
								for ($i=1;$i<=$total_paginas;$i++){ 
									if ($pagina == $i){
										//si muestro el índice de la página actual, no coloco enlace 
										echo '<li class="active light-green"><a href="#!">'.$pagina.'</a></li>';
									}else{
										echo '<li class="waves-effect"><a href="resultado_busqueda.php?pagina='.$i.'';
										if(isset($_GET['tcouch'])){
											echo '&tcouch='.$_GET['tcouch'].'';
										}
										if(isset($_GET['idprovincia'])){
											echo '&idprovincia='.$_GET['idprovincia'].'';
										}
										if(isset($_GET['idlocalidad'])){
											echo '&idlocalidad='.$_GET['idlocalidad'].'';
										}
										if(isset($_GET['cant_visitantes'])){
											echo '&cant_visitantes='.$_GET['cant_visitantes'].'';
										}
										echo '">'.$i.'</a></li>';
									}
								}
								if ($pagina==$total_paginas){
									//echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
								}else{
									$paginapos=$pagina+1;
									echo '<li class="waves-effect"><a href="resultado_busqueda.php?pagina='.$paginapos.'';
									if(isset($_GET['tcouch'])){
										echo '&tcouch='.$_GET['tcouch'].'';
									}
									if(isset($_GET['idprovincia'])){
										echo '&idprovincia='.$_GET['idprovincia'].'';
									}
									if(isset($_GET['idlocalidad'])){
										echo '&idlocalidad='.$_GET['idlocalidad'].'';
									}
									if(isset($_GET['cant_visitantes'])){
										echo '&cant_visitantes='.$_GET['cant_visitantes'].'';
									}
									echo '"><i class="material-icons">chevron_right</i></a></li>';
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
				$('.slider').slider();
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
  			});
  		</script>
	</body>

</html>