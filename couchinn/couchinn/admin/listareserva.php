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
		$premiumusuario=$resultado["Premium"];
		//Verificacion de variables de busqueda
		if((empty($_POST['fechainicio']))||(empty($_POST['fechafin']))){
			if ((empty($_GET['fechainicio']))||(empty($_GET['fechafin']))){
				$completa=true;
			}else{
				$fechaini=$_GET['fechainicio'];
				$fechainis=date('d-m-Y',strtotime($fechaini));
				$fechafinaux=$_GET['fechafin'];
				$fechafins=date('d-m-Y',strtotime($fechafinaux));
				$fechafin=date('Y-m-d-H-i-s', strtotime($fechafinaux) + 86399);
				$completa=false;
			}
		}else{
			$fechaini=$_POST['fechainicio'];
			$fechainis=date('d-m-Y',strtotime($fechaini));
			$fechafinaux=$_POST['fechafin'];
			$fechafins=date('d-m-Y',strtotime($fechafinaux));
			$fechafin=date('Y-m-d-H-i-s', strtotime($fechafinaux) + 86399);
			$completa=false;
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
		if (!$completa){
			//Consultas SQL parcial aceptadas
			$consulta = "SELECT * FROM reserva WHERE Estado='confirmada' and (('$fechaini' between FechaInicio and FechaFin) or ('$fechafin' between FechaInicio and FechaFin) or (('$fechaini'<FechaInicio)and('$fechafin'>FechaInicio)) or (('$fechafin'>FechaFin)and('$fechaini'<FechaFin))) ORDER BY Id_Couch ASC";
			$consulta_execute = $conexion->query($consulta);
			$total_resultados=$consulta_execute->num_rows;
			$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);
			$consulta = "SELECT c.Id_Couch, c.Titulo, c.Id_Provincia, c.Id_Localidad, c.Visible as CouchVisible, r.Estado, r.FechaInicio, r.FechaFin, r.FechaAlta, u.Nombre, u.Apellido, u.Id_Usuario, u.Visible as UsuarioVisible FROM reserva r inner JOIN couch c ON r.Id_Couch = c.Id_Couch inner JOIN usuario u ON r.Id_Usuario=u.Id_Usuario WHERE r.Estado='confirmada' and (('$fechaini' between FechaInicio and FechaFin) or ('$fechafin' between FechaInicio and FechaFin) or (('$fechaini'<FechaInicio)and('$fechafin'>FechaInicio)) or (('$fechafin'>FechaFin)and('$fechaini'<FechaFin))) ORDER BY Id_Couch ASC LIMIT ".$inicio.",".$TAMANO_PAGINA."";
			$consulta_execute = $conexion->query($consulta);
		}else{
			//Consultas SQL completa
			$consulta = "SELECT * FROM reserva ORDER BY Id_Couch ASC";
			$consulta_execute = $conexion->query($consulta);
			$total_resultados=$consulta_execute->num_rows;
			$total_paginas=ceil($total_resultados/$TAMANO_PAGINA);
			$consulta = "SELECT c.Id_Couch, c.Titulo, c.Id_Provincia, c.Id_Localidad, c.Visible as CouchVisible, r.Estado, r.FechaInicio, r.FechaFin, r.FechaAlta, u.Nombre, u.Apellido, u.Id_Usuario, u.Visible as UsuarioVisible FROM reserva r inner JOIN couch c ON r.Id_Couch = c.Id_Couch inner JOIN usuario u ON r.Id_Usuario=u.Id_Usuario ORDER BY Id_Couch ASC LIMIT ".$inicio.",".$TAMANO_PAGINA."";
			$consulta_execute = $conexion->query($consulta);
		}
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>CouchInn - Reservas</title>
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
						<?php if ($premiumusuario==1) echo'
						<li><a href="#" class="light-green">Cuenta Premium</a></li>
						<li><a href="#" class="light-green"><i class="large material-icons">star</i></a></li>
						'?>
						<li><a href="../index_login.php"  class="light-green-text">Inicio</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_couchs">Couchs y Reservas</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_admin">Panel Administrador</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_cuenta">Mi cuenta</a></li>
						<li><a href="../funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
						<li><a href="../ayuda.php" class="light-green"><i class="large material-icons">help_outline</i></a></li>
					</ul>
					<!-- Opciones  de menu lateral-->
					<ul class="side-nav" id="menulateral">
						<li><a href="../index_login.php"  class="light-green-text">Inicio</a></li>
						<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_couchs">Couchs y Reservas</a></li>
						<li><a class="dropdown-button light-green-text" href="#" data-activates="desplegable_lateral_admin">Panel Administrador</a></li>
						<li><a href="#"  class="dropdown-button light-green-text" data-activates="desplegable_lateral_cuenta">Mi cuenta</a></li>
						<li><a href="../funciones/cerrar_sesion.php" class="light-green-text">Cerrar Sesión</a></li>
						<li><a href="../ayuda.php" class="light-green"><i class="large material-icons">help_outline</i></a></li>
					</ul>
			  </div>		
			</nav>
		</div>
		
		<!-- Comienzo del modal para listar reservas-->
		<div id="modal_res" class="modal">
    		<div class="modal-content">
				<br>
				<br>
      			<h4>Listar Reservas Aceptadas de Período</h4>
				<br>
				<br>
      			<p>Seleccione fecha de inicio y fin y presione Visualizar.</p>
				<br>
				<br>
				<br>
				<form name="calculo" method="post" onSubmit="return validarReserva()" action="listareserva.php">
					<div class="input-field">
						<div class="grey-text">Fecha Inicio</div>
						<input name="fechainicio" type="date" class="datepicker" id="fechainicio" title="Fecha de Inicio">
	                </div>
					<br>
					<div class="center">
						Hasta
					</div>
					<br>
					<div class="input-field">
						<div class="grey-text">Fecha Fin</div>
						<input name="fechafin" type="date" class="datepicker" id="fechafin" title="Fecha de Fin">
	                </div>
					<br>
					<br>
					<br>
					<div class="divider"></div>
					<input class="waves-effect waves-light btn-flat light-green-text" type="submit" value="Visualizar">
					<a class="right waves-effect waves-light btn-flat light-green-text modal-action modal-close">Cancelar</a>
				</form>
    		</div>
  		</div>
		<!-- Fin del modal para listar reservas-->
		
		<!-- Contenido de pagina--> 
        <div class="parallax-container-mio  z-depth-3">
        	<div class="parallax fondo-registro"></div>
        	<!--<div class="container">-->
			<br>
			<div class="center grey-text text-darken-2">
				<h1>Reservas</h1>
            </div>
			<?php if (!$completa){ echo '
			<div class="divider"></div>
			<div class="center grey-text text-darken-2">
				<h5>Período: '.$fechainis.' al '.$fechafins.'</h5>
            </div>
			<div class="divider"></div>';
			}
			?>
			<br>
			<div class="row">
				<div class="col s6 center">
					<a class="waves-effect waves-light btn yellow darken-3 z-depth-2 modal-trigger" href="#modal_res">Listar Reservas Aceptadas</a>
				</div>
				<div class="col s6 center">
					<a class="waves-effect waves-light btn yellow darken-3 z-depth-2" href="listareserva.php">Listar Todas las Reservas</a>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="center grey-text text-darken-2">
				<?php
				switch ($total_resultados){
					case 0:
						echo '<h5>No se han encontrado resultados.</h5>
								<div class="divider"></div>';
						break;
					case 1:
						echo '<h5>Se ha encontrado: '.$total_resultados.' resultado.</h5>';
						break;
					default:
						echo '<h5>Se han encontrado: '.$total_resultados.' resultados.</h5>';	
				}
				?>
				</div>
			</div>
			<div class="section">
				<!-- Tabla-->
				<div class="row">
				<?php if($consulta_execute->num_rows) { 
					echo '<ul class="collapsible" data-collapsible="accordion">';
					$resultado = $consulta_execute->fetch_array();
					$fin=false;
					while(!$fin){
						$idcouch = $resultado['Id_Couch'];
						$titulo = $resultado ['Titulo'];
						$idlocalidad = $resultado['Id_Localidad'];
						// Obtengo la ubicacion del couch
						$consultaubicacion= "SELECT l.Localidad as Localidad, p.Provincia as Provincia FROM localidades l inner JOIN provincias p ON l.Id_Provincia=p.Id WHERE l.Id='$idlocalidad'";
						$resultadoubicacion = $conexion->query($consultaubicacion);
						$resultado1 = $resultadoubicacion->fetch_assoc();
						$ubicacion = $resultado1["Localidad"].', '.$resultado1["Provincia"];
						echo '<li>
							<div class="collapsible-header"><i class="material-icons">home</i>'.$titulo.' - '.$ubicacion.'</div>';
						echo '<div class="collapsible-body">
								<table class="responsive-table">
									<thead>
										<tr>
											<th data-field="name" class="center">Nombre de Huesped</th>
											<th data-field="name" class="center">Fecha de inicio</th>
											<th data-field="name" class="center">Fecha de fin</th>
											<th data-field="name" class="center">Estado</th>
											<th data-field="name" class="center">Fecha de Solicitud</th>
										</tr>
									</thead>';
						echo '<tbody>';
						$aux=$resultado;
						while ($aux['Id_Couch']==$resultado['Id_Couch']){
							$couchvisible= $resultado['CouchVisible'];
							$usuariovisible= $resultado['UsuarioVisible'];
							$idusuariosolicitud=$resultado['Id_Usuario'];
							$estado=$resultado['Estado'];
							$nombre = $resultado['Nombre'].' '.$resultado['Apellido'];
							$fechainicio = $resultado['FechaInicio'];
							$fechainicio = date('d-m-Y', strtotime($fechainicio));
							$fechafin = $resultado['FechaFin'];
							$fechafin = date('d-m-Y', strtotime($fechafin));
							$fechaalta = $resultado['FechaAlta'];
							$fechaalta = date('d-m-Y', strtotime($fechaalta));
							if ($estado=='espera'){
								echo 	'<tr>
											<td bgcolor="#ffff99" class="center">'.$nombre.'</td>
											<td bgcolor="#ffff99" class="center">'.$fechainicio.'</td>
											<td bgcolor="#ffff99" class="center">'.$fechafin.'</td>
											<td bgcolor="#ffff99" class="center">'.ucwords(strtolower($estado)).'</td>
											<td bgcolor="#ffff99" class="center">'.$fechaalta.'</td>';
											if ($usuariovisible==1){
												echo '<td bgcolor="#ffff99" class="center"><a class="center waves-effect waves-light btn blue z-depth-2" type="button" onClick="location.href=`../verperfil.php?id='.$idusuariosolicitud.'`">Ver Perfil</a></td>';
											}else{
												echo '<td bgcolor="#ffff99" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Huesped Eliminado</a></td>';
											}
											if ($couchvisible==1){
												echo '<td bgcolor="#ffff99" class="center">
														<form action="../vercouch.php" method="post">
															<input type="hidden" name="id" value="'.$idcouch.'">
															<input class="waves-effect waves-light btn light-green  z-depth-2" type="submit" value="Ver Couch">
														</form>
													</td>';
											}else{
												echo '<td bgcolor="#ffff99" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Couch Eliminado</a></td>';
											}echo '
										</tr>';
							}else{
								if ($estado=='confirmada'){
									echo 	'<tr>
												<td bgcolor="#b2d8b2" class="center">'.$nombre.'</td>
												<td bgcolor="#b2d8b2" class="center">'.$fechainicio.'</td>
												<td bgcolor="#b2d8b2" class="center">'.$fechafin.'</td>
												<td bgcolor="#b2d8b2" class="center">'.ucwords(strtolower($estado)).'</td>
												<td bgcolor="#b2d8b2" class="center">'.$fechaalta.'</td>';
												if ($usuariovisible==1){
													echo '<td bgcolor="#b2d8b2" class="center"><a class="center waves-effect waves-light btn blue z-depth-2" type="button" onClick="location.href=`../verperfil.php?id='.$idusuariosolicitud.'`">Ver Perfil</a></td>';
												}else{
													echo '<td bgcolor="#b2d8b2" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Huesped Eliminado</a></td>';
												}
												if ($couchvisible==1){
													echo '<td bgcolor="#b2d8b2" class="center">
															<form action="../vercouch.php" method="post">
																<input type="hidden" name="id" value="'.$idcouch.'">
																<input class="waves-effect waves-light btn light-green  z-depth-2" type="submit" value="Ver Couch">
															</form>
														</td>';
												}else{
													echo '<td bgcolor="#b2d8b2" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Couch Eliminado</a></td>';
												}echo '
											</tr>';
								}else{
									if ($estado=='rechazada'){
										echo 	'<tr>
													<td bgcolor="#ffb2b2" class="center">'.$nombre.'</td>
													<td bgcolor="#ffb2b2" class="center">'.$fechainicio.'</td>
													<td bgcolor="#ffb2b2" class="center">'.$fechafin.'</td>
													<td bgcolor="#ffb2b2" class="center">'.ucwords(strtolower($estado)).'</td>
													<td bgcolor="#ffb2b2" class="center">'.$fechaalta.'</td>';
													if ($usuariovisible==1){
														echo '<td bgcolor="#ffb2b2" class="center"><a class="center waves-effect waves-light btn blue z-depth-2" type="button" onClick="location.href=`../verperfil.php?id='.$idusuariosolicitud.'`">Ver Perfil</a></td>';
													}else{
														echo '<td bgcolor="#ffb2b2" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Huesped Eliminado</a></td>';
													}
													if ($couchvisible==1){
														echo '<td bgcolor="#ffb2b2" class="center">
																<form action="../vercouch.php" method="post">
																	<input type="hidden" name="id" value="'.$idcouch.'">
																	<input class="waves-effect waves-light btn light-green  z-depth-2" type="submit" value="Ver Couch">
																</form>
															</td>';
													}else{
														echo '<td bgcolor="#ffb2b2" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Couch Eliminado</a></td>';
													}echo '
												</tr>';
									}else{
										if ($estado=='cancelada'){
										echo 	'<tr>
													<td bgcolor="#cccccc" class="center">'.$nombre.'</td>
													<td bgcolor="#cccccc" class="center">'.$fechainicio.'</td>
													<td bgcolor="#cccccc" class="center">'.$fechafin.'</td>
													<td bgcolor="#cccccc" class="center">'.ucwords(strtolower($estado)).'</td>
													<td bgcolor="#cccccc" class="center">'.$fechaalta.'</td>';
													if ($usuariovisible==1){
														echo '<td bgcolor="#cccccc" class="center"><a class="center waves-effect waves-light btn blue z-depth-2" type="button" onClick="location.href=`../verperfil.php?id='.$idusuariosolicitud.'`">Ver Perfil</a></td>';
													}else{
														echo '<td bgcolor="#cccccc" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Huesped Eliminado</a></td>';
													}
													if ($couchvisible==1){
														echo '<td bgcolor="#cccccc" class="center">
																<form action="../vercouch.php" method="post">
																	<input type="hidden" name="id" value="'.$idcouch.'">
																	<input class="waves-effect waves-light btn light-green  z-depth-2" type="submit" value="Ver Couch">
																</form>
															</td>';
													}else{
														echo '<td bgcolor="#cccccc" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Couch Eliminado</a></td>';
													}echo '
												</tr>';
										}else{
											if ($estado='finalizada'){
												echo '<tr>
												<td bgcolor="#b9f6ca" class="center">'.$nombre.'</td>
												<td bgcolor="#b9f6ca" class="center">'.$fechainicio.'</td>
												<td bgcolor="#b9f6ca" class="center">'.$fechafin.'</td>
												<td bgcolor="#b9f6ca" class="center">'.ucwords(strtolower($estado)).'</td>
												<td bgcolor="#b9f6ca" class="center">'.$fechaalta.'</td>';
												if ($usuariovisible==1){
													echo '<td bgcolor="#b9f6ca" class="center"><a class="center waves-effect waves-light btn blue z-depth-2" type="button" onClick="location.href=`../verperfil.php?id='.$idusuariosolicitud.'`">Ver Perfil</a></td>';
												}else{
													echo '<td bgcolor="#b9f6ca" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Huesped Eliminado</a></td>';
												}
												if ($couchvisible==1){
													echo '<td bgcolor="#b9f6ca" class="center">
															<form action="../vercouch.php" method="post">
																<input type="hidden" name="id" value="'.$idcouch.'">
																<input class="waves-effect waves-light btn light-green  z-depth-2" type="submit" value="Ver Couch">
															</form>
														</td>';
												}else{
													echo '<td bgcolor="#b9f6ca" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Couch Eliminado</a></td>';
												}echo '
												</tr>';
											}else{	//Reservas vencidas
												echo 	'<tr>
													<td bgcolor="#c7e9ed" class="center">'.$nombre.'</td>
													<td bgcolor="#c7e9ed" class="center">'.$fechainicio.'</td>
													<td bgcolor="#c7e9ed" class="center">'.$fechafin.'</td>
													<td bgcolor="#c7e9ed" class="center">'.ucwords(strtolower($estado)).'</td>
													<td bgcolor="#c7e9ed" class="center">'.$fechaalta.'</td>';
													if ($usuariovisible==1){
														echo '<td bgcolor="#c7e9ed" class="center"><a class="center waves-effect waves-light btn blue z-depth-2" type="button" onClick="location.href=`../verperfil.php?id='.$idusuariosolicitud.'`">Ver Perfil</a></td>';
													}else{
														echo '<td bgcolor="#c7e9ed" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Huesped Eliminado</a></td>';
													}
													if ($couchvisible==1){
														echo '<td bgcolor="#c7e9ed" class="center">
																<form action="../vercouch.php" method="post">
																	<input type="hidden" name="id" value="'.$idcouch.'">
																	<input class="waves-effect waves-light btn light-green  z-depth-2" type="submit" value="Ver Couch">
																</form>
															</td>';
													}else{
														echo '<td bgcolor="#c7e9ed" class="center"><a class="disabled center waves-effect waves-light btn blue z-depth-2" type="button" >Couch Eliminado</a></td>';
													}echo '
												</tr>';		
											}
										}	
									}
								}
							}
							$resultado = $consulta_execute->fetch_array();
							if ($resultado!= null){
								$fin=false;
							}else{
								$fin=true;
							}
						}
						echo '
							</tbody>
							</table>
							</div>
							</li>';
					}
					echo '</ul>';
				}else{
					echo '<br>
					<div class="center grey-text text-darken-2">';
						if ($completa){
							echo '<h5>No existen reservas.</h5>';
						}else{
							echo '<h5>No existen reservas para ese período.</h5>';
						}
					echo '
					</div>
					<br>
					<br>
					<br>';
				}
				?>					
					<div class="center">
						<br>
						<input class="waves-effect waves-light btn light-green z-depth-2" type="button" value="Volver" onClick="location.href='administracion.php'">
					</div>
				</div>
			</div>
			<div class="section">
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
							if ($completa){
								echo '<li class="waves-effect"><a href="listareserva.php?pagina='.$paginaant.'"><i class="material-icons">chevron_left</i></a></li>';
							}else{
								echo '<li class="waves-effect"><a href="listareserva.php?pagina='.$paginaant.'&fechainicio='.$fechainicio.'&fechafin='.$fechafin.'"><i class="material-icons">chevron_left</i></a></li>';
							}
						}
						if ($total_paginas > 1){
							for ($i=1;$i<=$total_paginas;$i++){ 
								if ($pagina == $i){
									//si muestro el índice de la página actual, no coloco enlace 
									echo '<li class="active light-green"><a href="#!">'.$pagina.'</a></li>';
								}else{
									if ($completa){
										echo '<li class="waves-effect"><a href="listareserva.php?pagina='.$i.'">'.$i.'</a></li>';
									}else{
										echo '<li class="waves-effect"><a href="listareserva.php?pagina='.$i.'&fechainicio='.$fechainicio.'&fechafin='.$fechafin.'">'.$i.'</a></li>';
									}
								}
							}
							if ($pagina==$total_paginas){
								//echo '<li class="disabled"><a href="#!"><i class="material-icons">chevron_right</i></a></li>';
							}else{
								$paginapos=$pagina+1;
								if ($completa){
									echo '<li class="waves-effect"><a href="listareserva.php?pagina='.$paginapos.'"><i class="material-icons">chevron_right</i></a></li>';
								}else{
									echo '<li class="waves-effect"><a href="listareserva.php?pagina='.$paginapos.'&fechainicio='.$fechainicio.'&fechafin='.$fechafin.'"><i class="material-icons">chevron_right</i></a></li>';
								}
							}
							
						}
					?>
				</ul>
			</div>
	        <!--</div>-->
    	</div>
        <!-- Fin Contenido de pagina-->
        
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
		<script type="text/javascript" src="../js/funciones.js"></script>
  		<!-- Inicializacion de JS -->
  		<script type="text/javascript">
  			$(document).ready(function(){
				$(".parallax").parallax();
				$(".dropdown-button").dropdown();
				$(".button-collapse").sideNav();
				$('.modal-trigger').leanModal();
				$('.datepicker').pickadate({
					min: [2013,1,1],
					selectYears: 5,
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
			header("Location: ../index_login.php");
		}
	}
?>