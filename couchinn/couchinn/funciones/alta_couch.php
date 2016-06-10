<?php include('config.php');
	//Comprobacion de variables
	if ((empty($_POST['idusuario']))||(empty($_POST['titulo']))||(empty($_POST['idprovincia']))||(empty($_POST['idlocalidad']))||(empty($_POST['tcouch']))||(empty($_POST['capacidad']))||(empty($_POST['descripcion']))){
		?><script> alert("Por favor complete los campos faltantes y vuelva a intentarlo.");
			location.href='../altacouch.php';
		</script>
		<?php
		break;
	}
	else{
		//Comprobacion de imagenes enviadas
		$permitidos = array("image/jpeg","image/jpg");//Array de tipos permitidos para comprobar
		$limite_kb = 3000; //Limite de tamaño de imagen 3Mb
		//Variables para comprobar si cada foto cumple con tipo y tamaño
		$imagen1ok=false;
		$imagen2ok=false;
		$imagen3ok=false;
		//Pregunto si enviaron algo para cada imagen
		if(!empty($_FILES['imagenes']['name'][0])){
			//Si enviaron algo pregunto por el codigo de error (0=OK y 4=No enviaron nada), si es diferente a esos avisa y redirecciona.
			if (($_FILES["imagenes"]["error"][0] > 0)&&($_FILES["imagenes"]["error"][0] != 4)){
				?>	<script> alert("Ha ocurrido un error en la carga de imagenes, intentelo nuevamente.");
					location.href='../altacouch.php';
				</script>
				<?php
				break;
			}else{
				//Si los codigos estan bien comprueba formato y tamaño.
				if (in_array($_FILES['imagenes']['type'][0], $permitidos) && $_FILES['imagenes']['size'][0] <= $limite_kb * 1024){
					$imagen1ok=true;
				}
			}
		}
		if(!empty($_FILES['imagenes']['name'][1])) {
			//Si enviaron algo pregunto por el codigo de error (0=OK y 4=No enviaron nada), si es diferente a esos avisa y redirecciona.
			if (($_FILES["imagenes"]["error"][1] > 0)&&($_FILES["imagenes"]["error"][1] != 4)){
				?>	<script> alert("Ha ocurrido un error en la carga de imagenes, intentelo nuevamente.");
					location.href='../altacouch.php';
				</script>
				<?php
				break;
			}else{
				//Si los codigos estan bien comprueba formato y tamaño.
				if (in_array($_FILES['imagenes']['type'][1], $permitidos) && $_FILES['imagenes']['size'][1] <= $limite_kb * 1024){
					$imagen2ok=true;
				}
			}
		}
		if(!empty($_FILES['imagenes']['name'][2])) {
			//Si enviaron algo pregunto por el codigo de error (0=OK y 4=No enviaron nada), si es diferente a esos avisa y redirecciona.
			if (($_FILES["imagenes"]["error"][2] > 0)&&($_FILES["imagenes"]["error"][2] != 4)){
				?>	<script> alert("Ha ocurrido un error en la carga de imagenes, intentelo nuevamente.");
					location.href='../altacouch.php';
				</script>
				<?php
				break;
			}else{
				//Si los codigos estan bien comprueba formato y tamaño.
				if (in_array($_FILES['imagenes']['type'][2], $permitidos) && $_FILES['imagenes']['size'][2] <= $limite_kb * 1024){
					$imagen3ok=true;
				}
			}
		}
		//Compruebo si alguna de las 3 imagenes esta correcta
		if(($imagen1ok)||($imagen2ok)||($imagen3ok)){
			$idusuario=$_POST['idusuario'];
			$titulo = $_POST['titulo'];
			$titulo=ucwords(strtolower($titulo));
			$idprovincia = $_POST['idprovincia'];
			$idlocalidad = $_POST['idlocalidad'];
			$tcouch = $_POST['tcouch'];
			$capacidad = $_POST['capacidad'];
			$descripcion = $_POST['descripcion'];
			
			//Busqueda de usuario existente
			$consulta= "SELECT * FROM usuario WHERE Id_Usuario='$idusuario'";
			$consulta_execute = $conexion->query($consulta);
			if($consulta_execute->num_rows){
				//Comprobacion de couch existente para ese usuario con el mismo titulo
				$consulta= "SELECT * FROM couch WHERE Id_Usuario='$idusuario' and Titulo='$titulo'";
				$consulta_execute = $conexion->query($consulta);
				if($consulta_execute->num_rows){
					?>	<script> alert("Ya existe un couch de tu propiedad con ese titulo, elije un nuevo nombre.");
							location.href='../altacouch.php';
						</script>
					<?php
					break;
				}else{
					//Inserto a la base de datos un couch no visible sin fotos.
					$insertar = "INSERT INTO couch (`Id_TipoDeCouch`, `Id_Usuario`, `Titulo`, `Id_Provincia`, `Id_Localidad`, `Descripcion`, `Capacidad`,`Visible`) VALUES ('$tcouch', '$idusuario', '$titulo', '$idprovincia', '$idlocalidad', '$descripcion','$capacidad',0)";
					if (mysqli_query($conexion, $insertar)) {
						//Si la insersion fue satisfactoria entonces busco el couch que cree para sacar el ID
						$consulta= "SELECT Id_Couch FROM couch WHERE Id_Usuario='$idusuario' and Titulo='$titulo'";
						$consulta_execute = $conexion->query($consulta);
						$resultadoconsulta=$consulta_execute->fetch_assoc();
						$idcouch=$resultadoconsulta['Id_Couch'];
					}else{
						echo "ERROR en la base de datos vuelva a intentarlo más tarde. " . mysqli_error($conexion);
					}
					//Pregunto una por una si la imagen enviada es correcta, en ese caso muevo imagen a su carpeta y agrego la entrada a la base de datos
					//Para el caso de la imagen 2 y 3 tambien se pregunta si las anteriores existian por que de ser no ser asi
					//Esa imagen debe reemplazar el lugar de la anterior que no esta.
					if($imagen1ok){
							$ruta = 'imagenes/couchs/'.$idcouch.'/1.jpg';
							@mkdir('../imagenes/couchs/'.$idcouch);
							$resultado = @move_uploaded_file($_FILES["imagenes"]["tmp_name"][0], '../'.$ruta);
							if (!$resultado){
								?>	<script> alert("Error al cargar el archivo.");
										location.href='../altacouch.php';
									</script>
								<?php
								break;
							}
							$actualizar = "UPDATE `couch` SET `Visible` = '1', `Foto1`='$ruta', `Foto2`='', `Foto3`='' WHERE `couch`.`Id_Couch` = '$idcouch'";
							if (!mysqli_query($conexion, $actualizar)) {
								?>	<script> alert("Error en la base de datos vuelva a intentarlo más tarde.");
									location.href='../altacouch.php';
								</script>
								<?php
								break;
							}	
					}
					if($imagen2ok){
						if ($imagen1ok){
							$ruta = 'imagenes/couchs/'.$idcouch.'/2.jpg';
							@mkdir('../imagenes/couchs/'.$idcouch);
							$resultado = @move_uploaded_file($_FILES["imagenes"]["tmp_name"][1], '../'.$ruta);
							if (!$resultado){
								?>	<script> alert("Error al cargar el archivo.");
										location.href='../altacouch.php';
									</script>
								<?php
								break;
							}
							$actualizar = "UPDATE `couch` SET `Visible` = '1', `Foto2`='$ruta', `Foto3`='' WHERE `couch`.`Id_Couch` = '$idcouch'";
							if (!mysqli_query($conexion, $actualizar)) {
								?>	<script> alert("Error en la base de datos vuelva a intentarlo más tarde.");
									location.href='../altacouch.php';
								</script>
								<?php
								break;
							}
						}else{
							$ruta = 'imagenes/couchs/'.$idcouch.'/1.jpg';
							@mkdir('../imagenes/couchs/'.$idcouch);
							$resultado = @move_uploaded_file($_FILES["imagenes"]["tmp_name"][1], '../'.$ruta);
							if (!$resultado){
								?>	<script> alert("Error al cargar el archivo.");
									location.href='../altacouch.php';
								</script>
								<?php
								break;
							}
							$actualizar = "UPDATE `couch` SET `Visible` = '1', `Foto1`='$ruta', `Foto2`='', `Foto3`='' WHERE `couch`.`Id_Couch` = '$idcouch'";
							if (!mysqli_query($conexion, $actualizar)) {
								?>	<script> alert("Error en la base de datos vuelva a intentarlo más tarde.");
									location.href='../altacouch.php';
								</script>
								<?php
								break;
							}
						}
					}
					if($imagen3ok){
						if (($imagen1ok)&&($imagen2ok)){
							$ruta = 'imagenes/couchs/'.$idcouch.'/3.jpg';
							@mkdir('../imagenes/couchs/'.$idcouch);
							$resultado = @move_uploaded_file($_FILES["imagenes"]["tmp_name"][2], '../'.$ruta);
							if (!$resultado){
								?>	<script> alert("Error al cargar el archivo.");
										location.href='../altacouch.php';
									</script>
								<?php
								break;
							}
							$actualizar = "UPDATE `couch` SET `Visible` = '1', `Foto3`='$ruta' WHERE `couch`.`Id_Couch` = '$idcouch'";
							if (!mysqli_query($conexion, $actualizar)) {
								?>	<script> alert("Error en la base de datos vuelva a intentarlo más tarde.");
									location.href='../altacouch.php';
								</script>
								<?php
								break;
							}
						}else{
							if(($imagen1ok)&&(!$imagen2ok)){
								$ruta = 'imagenes/couchs/'.$idcouch.'/2.jpg';
								@mkdir('../imagenes/couchs/'.$idcouch);
								$resultado = @move_uploaded_file($_FILES["imagenes"]["tmp_name"][2], '../'.$ruta);
								if (!$resultado){
									?>	<script> alert("Error al cargar el archivo.");
										location.href='../altacouch.php';
									</script>
									<?php
									break;
								}
								$actualizar = "UPDATE `couch` SET `Visible` = '1', `Foto2`='$ruta', `Foto3`='' WHERE `couch`.`Id_Couch` = '$idcouch'";
								if (!mysqli_query($conexion, $actualizar)) {
									?>	<script> alert("Error en la base de datos vuelva a intentarlo más tarde.");
										location.href='../altacouch.php';
									</script>
									<?php
									break;
								}
							}else{
								if((!$imagen1ok)&&($imagen2ok)){
									$ruta = 'imagenes/couchs/'.$idcouch.'/2.jpg';
									@mkdir('../imagenes/couchs/'.$idcouch);
									$resultado = @move_uploaded_file($_FILES["imagenes"]["tmp_name"][2], '../'.$ruta);
									if (!$resultado){
										?>	<script> alert("Error al cargar el archivo.");
												location.href='../altacouch.php';
											</script>
										<?php
										break;
									}
									$actualizar = "UPDATE `couch` SET `Visible` = '1', `Foto2`='$ruta', `Foto3`='' WHERE `couch`.`Id_Couch` = '$idcouch'";
									if (!mysqli_query($conexion, $actualizar)) {
										?>	<script> alert("Error en la base de datos vuelva a intentarlo más tarde.");
											location.href='../altacouch.php';
										</script>
										<?php
										break;
									}
								}else{
									if((!$imagen1ok)&&(!$imagen2ok)){
										$ruta = 'imagenes/couchs/'.$idcouch.'/1.jpg';
										@mkdir('../imagenes/couchs/'.$idcouch);
										$resultado = @move_uploaded_file($_FILES["imagenes"]["tmp_name"][2], '../'.$ruta);
										if (!$resultado){
											?>	<script> alert("Error al cargar el archivo.");
												location.href='../altacouch.php';
											</script>
											<?php
											break;
										}
										$actualizar = "UPDATE `couch` SET `Visible` = '1', `Foto1`='$ruta', `Foto2`='', `Foto3`='' WHERE `couch`.`Id_Couch` = '$idcouch'";
										if (!mysqli_query($conexion, $actualizar)) {
											?>	<script> alert("Error en la base de datos vuelva a intentarlo más tarde.");
												location.href='../altacouch.php';
											</script>
											<?php
											break;
										}
									}
								}
							}
						}
					}
					?><script> alert("Su couch ha sido creado y publicado.");
							location.href='../miscouchs.php';
					</script>
					<?php
					break;
				}
			}else{
				//El usuario no existe
				?>	<script> alert("Su cuenta ha sido borrada del sistema.");
						location.href='cerrar_sesion.php';
						</script>
				<?php
				break;
			}
		}else{
			?>	<script> alert("Debe seleccionar al menos una imagen para subir a su Couch.");
					location.href='../altacouch.php';
				</script>
			<?php
			break;
		}
	}
?>