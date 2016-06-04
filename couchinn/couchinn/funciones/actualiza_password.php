<?php include('config.php');
	//Variables
	if ((empty($_POST['passwordact']))||(empty($_POST['password_nueva']))||(empty($_POST['id']))){
		header("Location: ../index.php");
	}
	else{
	$passwordact = $_POST['passwordact'];
	$password_nueva = $_POST['password_nueva'];
	$id=$_POST['id'];
	
	//Busqueda del usuario
	$consulta= "SELECT * FROM usuario WHERE Id_Usuario='$id' and Visible=1";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){
		$fila = $consulta_execute->fetch_assoc();
		if($passwordact==$fila['Password']){
			$sql = "UPDATE `usuario` SET `Password` = '$password_nueva' WHERE `usuario`.`Id_Usuario` = '$id';";
			if (mysqli_query($conexion, $sql)) {
				echo '<script> alert("Se ha actualizado tu contraseña!!!");
						location.href="../miperfil.php";
					</script>';
			}else{
				echo "ERROR. " . mysqli_error($conexion);
				}				
		}else{?><script> 
					alert("Verifica tu contraseña actual.");
					location.href="../modificarpassword.php";
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

