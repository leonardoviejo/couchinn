<?php include('config.php');
	//Variables
	if ((empty($_POST['password']))||(empty($_POST['id']))){
		header("Location: ../index.php");
	}
	else{
	$password = $_POST['password'];
	$id=$_POST['id'];
	
	//Busqueda del usuario
	$consulta= "SELECT * FROM usuario WHERE Id_Usuario='$id' and Visible=1";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){
		$fila = $consulta_execute->fetch_assoc();
		if($password==$fila['Password']){
			$sql = "UPDATE usuario u left JOIN couch c ON u.Id_Usuario=c.Id_Usuario left JOIN reserva r ON u.Id_Usuario=r.Id_Usuario  SET u.Visible = 0, c.Visible=0, r.Visible=0  WHERE u.Id_Usuario = '$id';";
			if (mysqli_query($conexion, $sql)) {
				echo '<script> alert("Se ha eliminado su cuenta!!!");
						location.href="cerrar_sesion.php";
					</script>';
			}else{
				echo "ERROR. " . mysqli_error($conexion);
				}				
		}else{?><script> 
					alert("Verifica tu contraseña actual.");
					location.href="../eliminarcuenta.php";
				</script>
			<?php
		}
	}else{
		?>	<script> alert("Su cuenta ya se encontraba eliminada.");
				location.href='cerrar_sesion.php';
				</script>
		<?php
	}
	}
?>

