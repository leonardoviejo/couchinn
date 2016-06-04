<?php include('config.php');
	//Variables
	if ((empty($_POST['nombre']))||(empty($_POST['apellido']))||(empty($_POST['id']))||(empty($_POST['email']))||(empty($_POST['password']))||(empty($_POST['f_nac']))||(empty($_POST['telefono']))){
		header("Location: ../index.php");
	}
	else{	
	$nombre = $_POST['nombre'];
	$nombre=ucwords(strtolower($nombre));
	$apellido = $_POST['apellido'];
	$apellido=ucwords(strtolower($apellido));
	$email = $_POST['email'];
	$password = $_POST['password'];
	$f_nac = $_POST['f_nac'];
	$telefono = $_POST['telefono'];
	$id=$_POST['id'];
	
	//Busqueda del usuario
	$consulta= "SELECT * FROM usuario WHERE Id_Usuario='$id' and Visible=1";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){
		$fila = $consulta_execute->fetch_assoc();
		if($password==$fila['Password']){
			$consultacorreo="SELECT Email,Id_Usuario FROM usuario WHERE Email='$email'";
			$consulta_execute = $conexion->query($consultacorreo);
			if($consulta_execute->num_rows){
				$filacorreos = $consulta_execute->fetch_assoc();
				if($id==$filacorreos['Id_Usuario']){
					$sql = "UPDATE `usuario` SET `Nombre` = '$nombre', `Apellido` = '$apellido', `FechaNac` = '$f_nac', `Telefono` = '$telefono' WHERE `usuario`.`Id_Usuario` = '$id';";
					if (mysqli_query($conexion, $sql)) {
						echo '<script> alert("Se han actualizado sus datos!!!");
								location.href="../miperfil.php";
							</script>';
					} else {
						echo "ERROR. " . mysqli_error($conexion);
					}
				}else{?><script> 
							alert("El correo esta siendo utilizado por otra persona.");
							location.href="../modificarperfil.php";
						</script>
					<?php
				}
			}else{
				$sql = "UPDATE `usuario` SET `Nombre` = '$nombre', `Apellido` = '$apellido', `Email` = '$email', `FechaNac` = '$f_nac', `Telefono` = '$telefono' WHERE `usuario`.`Id_Usuario` = '$id';";
				if (mysqli_query($conexion, $sql)) {
					echo '<script> alert("Se han actualizado sus datos!!!");
								location.href="../miperfil.php";
							</script>';
					} else {
						echo "ERROR. " . mysqli_error($conexion);
					}
			}
		}else{?><script> 
					alert("Verifica tu contraseña.");
					location.href="../modificarperfil.php";
				</script>
			<?php
		}
	}else{
		?>	<script> alert("Su cuenta se encuentra eliminada.");
				location.href='cerrar_sesion.php';
				</script>
		<?php
	}
	}
?>

