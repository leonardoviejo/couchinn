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
	
	//Consultas SQL
		//Busca provincias para selector
		$consultaprov = "SELECT * from provincias";
		$resultadoprov = $conexion->query($consultaprov);
		
	//Selecciono los tipos de couch para las opciones de búsqueda
		$consulta_tipo = "SELECT * FROM tipodecouch";
		$resultado_tipo = $conexion->query($consulta_tipo);
	
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
		
		if((isset($_GET['tcouch']))&&(!empty($_GET['tcouch']))){
			$consulta.= " AND c.Id_TipoDeCouch = ".$_GET['tcouch'];
			$consultafinal.=" AND c.Id_TipoDeCouch = ".$_GET['tcouch'];
			//Selecciono el nombre de tipo de couch para mostrar en el formulario de busqueda solo si el usuario utilizo ese campo
			$consulta_nombretipo = "SELECT * FROM tipodecouch WHERE Id_Tipo = ".$_GET['tcouch']."";
			$resultado_nombretipo = $conexion->query($consulta_nombretipo);
			$resultadoconsulta = $resultado_nombretipo->fetch_array();
			$nombretipocouch = $resultadoconsulta['Nombre'];
		}
		if((isset($_GET['idprovincia']))&&(!empty($_GET['idprovincia']))){
			$consulta.= " AND c.Id_Provincia = ".$_GET['idprovincia'];
			$consultafinal.=" AND c.Id_Provincia = ".$_GET['idprovincia'];
			//Selecciono el nombre de provincia para mostrar en el formulario de busqueda solo si el usuario utilizo ese campo
			$consulta_nombreprov = "SELECT * FROM provincias WHERE Id = ".$_GET['idprovincia']."";
			$resultado_nombreprov = $conexion->query($consulta_nombreprov);
			$resultadoconsulta = $resultado_nombreprov->fetch_array();
			$nombreprov = $resultadoconsulta['Provincia'];
		}
		if((isset($_GET['idlocalidad']))&&(!empty($_GET['idlocalidad']))){
			$consulta.= " AND c.Id_Localidad = ".$_GET['idlocalidad'];
			$consultafinal.=" AND c.Id_Localidad = ".$_GET['idlocalidad'];
			//Selecciono el nombre de tipo de localidad para mostrar en el formulario de busqueda solo si el usuario utilizo ese campo
			$consulta_nombreloc = "SELECT * FROM localidades WHERE Id = ".$_GET['idlocalidad']."";
			$resultado_nombreloc = $conexion->query($consulta_nombreloc);
			$resultadoconsulta = $resultado_nombreloc->fetch_array();
			$nombreloc = $resultadoconsulta['Localidad'];
		}
		if((isset($_GET['cant_visitantes']))&& (!empty($_GET['cant_visitantes']))){
			$consulta.= " AND c.Capacidad = ".$_GET['cant_visitantes'];
			$consultafinal.=" AND c.Capacidad = ".$_GET['cant_visitantes'];
		}
		if((isset($_GET['titulo']))&& (!empty($_GET['titulo']))){
			$titulo=trim($_GET['titulo']);
			$arreglotitulo=explode(' ',$titulo);
			$consulta.= ' AND (c.Titulo like "%'.$arreglotitulo[0].'%"'; 
			$consultafinal.= ' AND (c.Titulo like "%'.$arreglotitulo[0].'%"';
			$total=count($arreglotitulo);
			for($i=1 ; $i<$total ; $i++){
				if (strlen($arreglotitulo[$i])>3){
					$consulta.= 'or c.Titulo like "%'.$arreglotitulo[$i].'%"';
					$consultafinal.='or c.Titulo like "%'.$arreglotitulo[$i].'%"';
				}
			}
			$consulta.= ')';
			$consultafinal.= ')';
		}
		if((isset($_GET['descripcion']))&& (!empty($_GET['descripcion']))){
			$descripcion=trim($_GET['descripcion']);
			$arreglodescripcion=explode(' ',$descripcion);
			$consulta.= ' AND (c.Descripcion like "%'.$arreglodescripcion[0].'%"'; 
			$consultafinal.= ' AND (c.Descripcion like "%'.$arreglodescripcion[0].'%"';
			$total=count($arreglodescripcion);
			for($i=1 ; $i<$total ; $i++){
				if (strlen($arreglodescripcion[$i])>3){
					$consulta.= 'or c.Descripcion like "%'.$arreglodescripcion[$i].'%"';
					$consultafinal.='or c.Descripcion like "%'.$arreglodescripcion[$i].'%"';
				}
			}
			$consulta.= ')';
			$consultafinal.= ')';
		}
		if(((isset($_GET['fechainicio']))&& (!empty($_GET['fechainicio'])))&&((isset($_GET['fechafin']))&&(!empty($_GET['fechafin'])))){
			if ($_GET['fechainicio']<$_GET['fechafin']){
				$f_inicio=$_GET['fechainicio'];
				$f_fin=$_GET['fechafin'];
				$consulta.= " AND c.Id_Couch not IN ( SELECT Id_Couch FROM reserva WHERE (('".$_GET['fechainicio']."' between FechaInicio and FechaFin) or ('".$_GET['fechafin']."' between FechaInicio and FechaFin) or (('".$_GET['fechainicio']."' < FechaInicio)and('".$_GET['fechafin']."' > FechaInicio)) or (('".$_GET['fechafin']."' > FechaFin)and ('".$_GET['fechainicio']."' < FechaFin))) and Estado='confirmada' and Visible=1)";
				$consultafinal.= " AND c.Id_Couch not IN ( SELECT Id_Couch FROM reserva WHERE (('".$_GET['fechainicio']."' between FechaInicio and FechaFin) or ('".$_GET['fechafin']."' between FechaInicio and FechaFin) or (('".$_GET['fechainicio']."' < FechaInicio)and('".$_GET['fechafin']."' > FechaInicio)) or (('".$_GET['fechafin']."' > FechaFin)and ('".$_GET['fechainicio']."' < FechaFin))) and Estado='confirmada' and Visible=1)";
			}
		}
		$consulta.=" ORDER BY Titulo ASC";
		$consultafinal.=" ORDER BY Titulo ASC LIMIT ".$inicio.",".$TAMANO_PAGINA."";
		$consulta_execute = $conexion->query($consulta);
		$total_resultados=$consulta_execute->num_rows;
		$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);
		$consulta = "SELECT c.Id_Couch, c.Id_TipoDeCouch, c.Id_Usuario, c.Titulo as Titulo, c.Id_Localidad, c.Capacidad, c.Foto1, u.Premium, t.Nombre AS NombreTipo, l.localidad AS Ciudad FROM couch c inner JOIN usuario u ON c.Id_Usuario = u.Id_Usuario inner JOIN tipodecouch t ON c.Id_TipoDeCouch = t.Id_Tipo inner JOIN localidades l ON c.Id_Localidad=l.Id WHERE c.Visible=1";
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
									<li><a href="miperfil.php"  class="grey-text text-darken-2">Bienvenido, '.$nombreusuario.'!!!</a></li>';
									if ($premium==1) echo'
									<li><a href="#" class="light-green">Cuenta Premium</a></li>
									<li><a href="#" class="light-green"><i class="large material-icons">star</i></a></li>';
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
        <div class="parallax-container-mio-home z-depth-3">
        	<div class="parallax transparencia"><img src="imagenes/fondo.jpg" alt=""></div>
        	<div class="container"> 
    	    	<br>
        		<br>
        		<br>
				<div class="row card-panel z-depth-3">
					<h5><p class="center">Resultados de Búsqueda</p></h5>
					<form class="col s12" name="busqueda" method="get" onSubmit="return validarBusqueda()" action="resultado_busqueda.php">
						<div class="input-field col s3">
							<select class="browser-default" name="idprovincia" id="idprovincia">
							<?php if((isset($_GET['idprovincia']))&&(!empty($_GET['idprovincia']))){
									echo '<option value="'.$_GET["idprovincia"].'" selected>'.$nombreprov.'</option>';
								}
							?>
								<option value="">Elige una provincia...</option>
								<?php
									while($query_result = $resultadoprov->fetch_array()) {
										$idprov=$query_result['Id'];
										$nombreprov=$query_result['Provincia'];
										echo '<option value="'.$idprov.'">'.$nombreprov.'</option>';
									}
								?>
							</select>
						</div>
						<div class="input-field col s3">
							<select class="browser-default" name="idlocalidad" id="idlocalidad">
							<?php if((isset($_GET['idlocalidad']))&&(!empty($_GET['idlocalidad']))){
									echo '<option value="'.$_GET["idlocalidad"].'" selected>'.$nombreloc.'</option>';
								}
							?>
							<option value="">Elige una ciudad...</option>
							</select>
						</div>
						<div class="input-field col s3">
							<select class="browser-default" name="tcouch" id="tcouch">
							<?php if((isset($_GET['tcouch']))&&(!empty($_GET['tcouch']))){
									echo '<option value="'.$_GET["tcouch"].'" selected>'.$nombretipocouch.'</option>';
								}
							?>
								<option value="">Elige un tipo de couch...</option>
								<?php
									while($query_result = $resultado_tipo->fetch_array()) {
										$nombretipo=$query_result['Nombre'];
										$idTipoDeCouch=$query_result['Id_Tipo'];
										echo '<option value="'.$idTipoDeCouch.'">'.$nombretipo.'</option>';
									}
								?>
							</select>
						</div>
						<div class="input-field col s2">
							<input id="cant_visitantes" name="cant_visitantes" maxlength="2" value="<?php echo $_GET["cant_visitantes"] ?>" pattern="^[0-9]{1,2}" type="text" class="validate">
							<label for="cant_visitantes">Capacidad</label>
						</div>
						<div class="input-field col s1 right">					
							<button class="btn-floating btn-large waves-effect waves-light light-green z-depth-2" type="submit" name="action">
								<i class="material-icons right">search</i>
							</button>
			            
						</div>
						<ul class="collapsible col s12" data-collapsible="accordion">
							<li>
								<div class="collapsible-header"><i class="material-icons">settings</i>Más opciones</div>
								<div class="collapsible-body">
									<div class="input-field col s6">
										<input name="titulo" value="<?php echo $_GET['titulo'] ?>" type="text" length="30" maxlength="30" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚüÜ\s]+" title="Solo se admiten letras" class="validate">
										<label for="titulo" data-error="Solo se admiten letras">Título</label>
									</div>
									<div class="input-field col s3">
										<div class="grey-text"> Fecha Inicio</div>
										<?php
											if((isset($_GET['fechainicio']))&& (!empty($_GET['fechainicio']))) {
												echo '<input name="fechainicio" value="'.date("d-m-Y", strtotime($f_inicio)).'" type="date" class="datepicker" id="fechainicio" title="Fecha de Inicio">';
											}else{
												echo '<input name="fechainicio" type="date" class="datepicker" id="fechainicio" title="Fecha de Inicio">';
											}
										?>
									</div>
									<div class="input-field col s3">
										<div class="grey-text"> Fecha Fin</div>
										<?php
											if((isset($_GET['fechafin']))&& (!empty($_GET['fechafin']))) {
												echo '<input name="fechafin" value="'.date("d-m-Y", strtotime($f_fin)).'" type="date" class="datepicker" id="fechafin" title="Fecha de Fin">';
											}else{
												echo '<input name="fechafin" type="date" class="datepicker" id="fechafin" title="Fecha de Fin">';
											}
										?>
									</div>
									<div class="input-field col s12">
										<textarea id="descripcion" name="descripcion" class="materialize-textarea" length="250" maxlength="250" class="validate" ><?php echo $_GET['descripcion'] ?></textarea>
										<label for="descripcion">Descripción</label>
									<br>
									<br>
									</div>
								</div>
							</li>
						</ul>
					</form>
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
            					<td class="center"><div class="center grey-text text-darken-2">
													<h5>No existen couchs para ese criterio de busqueda.</h5>
												</div>
								</td>
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
						<div class="left">
							<?php if ($total_resultados>1){
								echo '<small>Se han encontrado '.$total_resultados.' resultados </small>';
							}else{
								if ($total_resultados>0){
									echo '<small>Se ha encontrado '.$total_resultados.' resultado </small>';
								}
							}
							?>
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
				$("#idprovincia").change(function(){
					$.ajax({
						url:"funciones/obtenerciudades.php",
						type: "POST",
						data:"idprovincia="+$("#idprovincia").val(),
						success: function(opciones){
						$("#idlocalidad").html(opciones);
						}
					})
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