<?php include('config.php');
	//Comprobacion de variables
	if ((empty($_POST['idusuario']))||(empty($_POST['titulo']))||(empty($_POST['idprovincia']))||(empty($_POST['idlocalidad']))||(empty($_POST['tcouch']))||(empty($_POST['capacidad']))||(empty($_POST['descripcion']))){
		$idcouch=$_POST['idcouch'];
		echo '	<script> alert("Por favor complete los campos faltantes y vuelva a intentarlo.");
					location.href="../modificarcouch.php?id='.$idcouch.'";
				</script>';
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
				echo '	<script> alert("Ha ocurrido un error en la carga de imagenes, intentelo nuevamente.");
					location.href="../modificarcouch.php?id='.$idcouch.'";
				</script>';
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
				echo '	<script> alert("Ha ocurrido un error en la carga de imagenes, intentelo nuevamente.");
					location.href="../modificarcouch.php?id='.$idcouch.'";
				</script>';
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
				echo '	<script> alert("Ha ocurrido un error en la carga de imagenes, intentelo nuevamente.");
					location.href="../modificarcouch.php?id='.$idcouch.'";
				</script>';
				break;
			}else{
				//Si los codigos estan bien comprueba formato y tamaño.
				if (in_array($_FILES['imagenes']['type'][2], $permitidos) && $_FILES['imagenes']['size'][2] <= $limite_kb * 1024){
					$imagen3ok=true;
				}
			}
		}
		//Saco datos de POST
		$idcouch=$_POST['idcouch'];
		$idusuario=$_POST['idusuario'];
		$titulo = $_POST['titulo'];
		$titulo=ucwords(strtolower($titulo));
		$idprovincia = $_POST['idprovincia'];
		$idlocalidad = $_POST['idlocalidad'];
		$tcouch = $_POST['tcouch'];
		$capacidad = $_POST['capacidad'];
		$descripcion = $_POST['descripcion'];
		
		//Comprobacion de couch existente para ese usuario con el mismo titulo
		$consulta= "SELECT * FROM couch WHERE Id_Usuario='$idusuario' and Titulo ='$titulo' and Id_Couch <> '$idcouch'";
		$consulta_execute = $conexion->query($consulta);
		if($consulta_execute->num_rows){
			echo '	<script> alert("Ya existe un couch de tu propiedad con ese titulo, elije un nuevo nombre.");
					location.href="../modificarcouch.php?id='.$idcouch.'";
				</script>';
			break;
		}else{
			//Saco rutas de fotos actuales para saber si estan cargadas.
			$consulta= "SELECT * FROM couch WHERE Id_Couch = '$idcouch'";
			$consulta_execute = $conexion->query($consulta);
			$consultafotos = $consulta_execute->fetch_assoc();
			$rutafoto2=$consultafotos["Foto2"];
			//Actualizo el Couch.
			$insertar = "UPDATE `couch` SET `Titulo` = '$titulo', `Id_Provincia` = '$idprovincia', `Id_Localidad` = '$idlocalidad', `Id_TipoDeCouch` = '$tcouch', `Capacidad` = '$capacidad', `Descripcion` = '$descripcion' WHERE `couch`.`Id_Couch` = '$idcouch';";
			if (!mysqli_query($conexion, $insertar)) {
				echo "ERROR en la base de datos vuelva a intentarlo más tarde. " . mysqli_error($conexion);
			}
			//Pregunto una por una si la imagen enviada es correcta, en ese caso muevo imagen a su carpeta y agrego la entrada a la base de datos
			//Para el caso de la imagen 2 y 3 tambien se pregunta si las anteriores existian por que de ser no ser asi
			//Esa imagen debe reemplazar el lugar de la anterior que no esta.
			if($imagen1ok){
					$ruta = 'imagenes/couchs/'.$idcouch.'/1.jpg';
					$resultado = @move_uploaded_file($_FILES["imagenes"]["tmp_name"][0], '../'.$ruta);
					if (!$resultado){
						echo'	<script> alert("Error al cargar el archivo.");
								location.href="../modificarcouch.php?id='.$idcouch.'";
							</script>';
						break;
					}
					$actualizar = "UPDATE `couch` SET `Foto1`='$ruta' WHERE `couch`.`Id_Couch` = '$idcouch'";
					if (!mysqli_query($conexion, $actualizar)) {
						echo '	<script> alert("Error en la base de datos vuelva a intentarlo más tarde.");
							location.href="../modificarcouch.php?id='.$idcouch.'";
						</script>';
						break;
					}	
			}
			if($imagen2ok){
				$ruta = 'imagenes/couchs/'.$idcouch.'/2.jpg';
				$resultado = @move_uploaded_file($_FILES["imagenes"]["tmp_name"][1], '../'.$ruta);
				if (!$resultado){
					echo'	<script> alert("Error al cargar el archivo.");
								location.href="../modificarcouch.php?id='.$idcouch.'";
							</script>';
					break;
				}
				$actualizar = "UPDATE `couch` SET `Foto2`='$ruta' WHERE `couch`.`Id_Couch` = '$idcouch'";
				if (!mysqli_query($conexion, $actualizar)) {
					echo '	<script> alert("Error en la base de datos vuelva a intentarlo más tarde.");
							location.href="../modificarcouch.php?id='.$idcouch.'";
						</script>';
					break;
				}
			}
			if($imagen3ok){
				if ($rutafoto2!=''){
					$ruta = 'imagenes/couchs/'.$idcouch.'/3.jpg';
					$resultado = @move_uploaded_file($_FILES["imagenes"]["tmp_name"][2], '../'.$ruta);
					if (!$resultado){
						echo'	<script> alert("Error al cargar el archivo.");
								location.href="../modificarcouch.php?id='.$idcouch.'";
							</script>';
						break;
					}
					$actualizar = "UPDATE `couch` SET `Foto3`='$ruta' WHERE `couch`.`Id_Couch` = '$idcouch'";
					if (!mysqli_query($conexion, $actualizar)) {
						echo '	<script> alert("Error en la base de datos vuelva a intentarlo más tarde.");
							location.href="../modificarcouch.php?id='.$idcouch.'";
						</script>';
						break;
					}
				}else{
					$ruta = 'imagenes/couchs/'.$idcouch.'/2.jpg';
					$resultado = @move_uploaded_file($_FILES["imagenes"]["tmp_name"][2], '../'.$ruta);
					if (!$resultado){
						echo'	<script> alert("Error al cargar el archivo.");
								location.href="../modificarcouch.php?id='.$idcouch.'";
							</script>';
						break;
					}
					$actualizar = "UPDATE `couch` SET `Foto2`='$ruta' WHERE `couch`.`Id_Couch` = '$idcouch'";
					if (!mysqli_query($conexion, $actualizar)) {
						echo '	<script> alert("Error en la base de datos vuelva a intentarlo más tarde.");
							location.href="../modificarcouch.php?id='.$idcouch.'";
						</script>';
						break;
					}
				}
			}
			?><script> alert("Su couch ha sido actualizado.");
					location.href='../miscouchs.php';
			</script>
			<?php
			break;
		}
	}
?>