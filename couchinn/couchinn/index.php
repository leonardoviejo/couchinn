<!doctype html>
<?php
	require_once("funciones/sesion.class.php");
	
	$sesion = new sesion();
	$idusuario = $sesion->get("id");
	
	if( $idusuario == false )
	{
	//Conteo de paginado de resultado.
	$TAMANO_PAGINA=5;
	if(!isset($_GET['pagina'])) {
		$pagina=1;
		$inicio=0;
	}else{
		$pagina = $_GET["pagina"];
		$inicio = ($pagina - 1) * $TAMANO_PAGINA;
	}
	include('funciones/config.php');
	//Consultas SQL
	//Busca provincias para selector
	$consultaprov = "SELECT * from provincias";
	$resultadoprov = $conexion->query($consultaprov);
	
	//Busca couchs para listar
	$consulta = "SELECT * FROM couch WHERE Visible=1 ORDER BY Titulo ASC";
	$consulta_execute = $conexion->query($consulta);
	$total_resultados=$consulta_execute->num_rows;
	$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);
	$consulta = "SELECT c.Id_Couch, c.Id_TipoDeCouch, c.Id_Usuario, c.Titulo, c.Id_Localidad, c.Capacidad, c.Foto1, u.Premium, t.Nombre AS NombreTipo, l.localidad AS Ciudad FROM couch c inner JOIN usuario u ON c.Id_Usuario = u.Id_Usuario inner JOIN tipodecouch t ON c.Id_TipoDeCouch = t.Id_Tipo inner JOIN localidades l ON c.Id_Localidad=l.Id WHERE c.Visible=1 ORDER BY Titulo ASC LIMIT ".$inicio.",".$TAMANO_PAGINA."";
	$consulta_execute = $conexion->query($consulta);
	//Selecciono los tipos de couch para las opciones de búsqueda
	$consulta_tipo = "SELECT * FROM tipodecouch";
	$resultado_tipo = $conexion->query($consulta_tipo);
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Inicio</title>
		<!-- Importacion Iconos de Google -->
 	 	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Importacion de materialize css-->
		<link type="text/css" rel="stylesheet" href="css/materialize.css"  media="screen,projection"/>
		<!--Sitio optimizado para moviles-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	</head>
	
	<body>
		<a href="ayuda.php" accesskey="a"></a>
    	<!-- Encabezado fijo -->
		<div class="navbar-fixed">
			<!-- Barra de navagacion -->
			<nav>
				<div class="nav-wrapper white z-depth-3">
					<!-- Logo -->
					<a href="#" class="brand-logo"><img src="imagenes/Logo.png" alt="CouchInn" width="270" class="responsive-img" id="logo"/></a>
                    <a href="#" data-activates="menulateral" class="button-collapse"><i class="material-icons light-green">menu</i></a>
					<!-- Opciones -->
					<ul class="right hide-on-med-and-down">
						<li><a href="registro.php"  class="light-green-text">Registrarse</a></li>
						<li><a href="login.php" class="light-green-text">Iniciar Sesión</a></li>
					</ul>
					<!-- Opciones  de menu al costado-->
					<ul class="side-nav" id="menulateral">
						<li><a href="registro.php"  class="light-green-text">Registrarse</a></li>
						<li><a href="login.php" class="light-green-text">Iniciar Sesión</a></li>
					</ul>
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
					<h5><p class="center">¿Donde puedo Alojarme?</p></h5>
					<form class="col s12" name="busqueda" method="get" onSubmit="return validarBusqueda()" action="resultado_busqueda.php">
						<div class="input-field col s3">
							<select class="browser-default" name="idprovincia" id="idprovincia">
								<option value="" disabled selected>Elige una provincia...</option>
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
							<option value="" disabled selected>Elige una ciudad...</option>
							</select>
						</div>
						<div class="input-field col s3">
							<select class="browser-default" name="tcouch" id="tcouch"> 
								<option value="" disabled selected>Elige un tipo de couch...</option>
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
							<input id="cant_visitantes" name="cant_visitantes" maxlength="2" value="" pattern="^[0-9]{1,2}" type="text" class="validate">
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
										<input name="titulo" type="text" length="30" maxlength="30" pattern="[A-Za-zñÑáéíóúÁÉÍÓÚüÜ\s]+" title="Solo se admiten letras" class="validate">
										<label for="titulo" data-error="Solo se admiten letras">Título</label>
									</div>
									<div class="input-field col s3">
										<div class="grey-text"> Fecha Inicio</div>
											<input name="fechainicio" type="date" class="datepicker" id="fechainicio" title="Fecha de Inicio">
									</div>
									<div class="input-field col s3">
										<div class="grey-text"> Fecha Fin</div>
										<input name="fechafin" type="date" class="datepicker" id="fechafin" title="Fecha de Fin">
									</div>
									<div class="input-field col s12">
										<textarea id="descripcion" name="descripcion" class="materialize-textarea" length="250" maxlength="250" class="validate" ></textarea>
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
					<?php if($consulta_execute->num_rows) { ?>
					<div class="row card-panel z-depth-3">
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
            					<td class="center">No existen Couchs</td>
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
								}
							}else{
								$paginaant=$pagina-1;
								echo '<li class="waves-effect"><a href="index.php?pagina='.$paginaant.'"><i class="material-icons">chevron_left</i></a></li>';
							}
							if ($total_paginas > 1){
								for ($i=1;$i<=$total_paginas;$i++){ 
									if ($pagina == $i){
										//si muestro el índice de la página actual, no coloco enlace 
										echo '<li class="active light-green"><a href="#!">'.$pagina.'</a></li>';
									}else{
										echo '<li class="waves-effect"><a href="index.php?pagina='.$i.'">'.$i.'</a></li>';
									}
								}
								if ($pagina==$total_paginas){
									//echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
								}else{
									$paginapos=$pagina+1;
									echo '<li class="waves-effect"><a href="index.php?pagina='.$paginapos.'"><i class="material-icons">chevron_right</i></a></li>';
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
<?php 
	}else{	
		header("Location: index_login.php");
	}
?>