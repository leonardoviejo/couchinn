<?php include('../../funciones/config.php');
	//Variables
	$nombre = $_POST['nombre'];
	$nombre=ucwords(strtolower($nombre));
	$apellido = $_POST['apellido'];
	$apellido=ucwords(strtolower($apellido));
	$email = $_POST['email'];
	$f_nac = $_POST['f_nac'];
	$telefono = $_POST['telefono'];
	$id=$_POST['id'];
	
	//Busqueda del usuario
	$consulta= "SELECT * FROM usuario WHERE Id_Usuario='$id' and Visible=1";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){
		$consultacorreo="SELECT Email,Id_Usuario FROM usuario WHERE Email='$email'";
		$consulta_execute = $conexion->query($consultacorreo);
		if($consulta_execute->num_rows){
			$filacorreos = $consulta_execute->fetch_assoc();
			if($id==$filacorreos['Id_Usuario']){
				$sql = "UPDATE `usuario` SET `Nombre` = '$nombre', `Apellido` = '$apellido', `FechaNac` = '$f_nac', `Telefono` = '$telefono' WHERE `usuario`.`Id_Usuario` = '$id';";
				if (mysqli_query($conexion, $sql)) {
					echo '<script> alert("Se han actualizado los datos del usuario!!!");
							location.href="../listarusuarios.php";
						</script>';
				} else {
					echo "ERROR. " . mysqli_error($conexion);
				}
			}else{?><script> 
						alert("El correo esta siendo utilizado por otra persona.");
						location.href="../listarusuarios.php";
					</script>
				<?php
			}
		}else{
			$sql = "UPDATE `usuario` SET `Nombre` = '$nombre', `Apellido` = '$apellido', `Email` = '$email', `FechaNac` = '$f_nac', `Telefono` = '$telefono' WHERE `usuario`.`Id_Usuario` = '$id';";
			if (mysqli_query($conexion, $sql)) {
				echo '<script> alert("Se han actualizado sus datos!!!");
							location.href="../listarusuarios.php";
						</script>';
				} else {
					echo "ERROR. " . mysqli_error($conexion);
				}
		}
	}else{
		?>	<script> alert("La cuenta que desea eliminar no existe.");
				location.href='../listarusuarios.php';
				</script>
		<?php
	}
?>

