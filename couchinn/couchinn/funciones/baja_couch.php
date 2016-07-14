<?php include('config.php');
	//Variables
	if ((empty($_POST['password']))||(empty($_POST['idcouch']))||(empty($_POST['idusuario']))){
		header("Location: ../miscouchs.php");
	}
	else{
	$password = $_POST['password'];
	$idcouch=$_POST['idcouch'];
	$idusuario=$_POST['idusuario'];
	
	//Busqueda del usuario
	$consulta= "SELECT * FROM couch WHERE Id_Couch='$idcouch' and Visible=1";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){
		$resultado = $consulta_execute->fetch_assoc();
		if($resultado['Id_Usuario']==$idusuario){
			$consultausuario= "SELECT * FROM usuario WHERE Id_Usuario='$idusuario' and Visible=1";
			$consulta_execute = $conexion->query($consultausuario);
			$resultado = $consulta_execute->fetch_assoc();
			if($password==$resultado['Password']){
				$actualizacion = "UPDATE couch c left JOIN reserva r ON c.Id_Couch=r.Id_Couch left JOIN comentario o ON c.Id_Couch=o.Id_Couch left JOIN punt_couch p ON c.Id_Couch=p.Id_Couch SET c.Visible = 0, r.Visible=0, o.Visible=0, p.Visible=0  WHERE c.Id_Couch = '$idcouch';";
				if (mysqli_query($conexion, $actualizacion)) {
					echo '<script> alert("Se ha eliminado su couch!!!");
							location.href="../miscouchs.php";
						</script>';
				}else{
					echo "ERROR. " . mysqli_error($conexion);
				}	
			}else{
				echo '<script> 
						alert("Verifica tu contraseña actual.");
						location.href="../eliminarcouch.php?id='.$idcouch.'";
					</script>';
			}
		}else{
			?>	<script> alert("El couch que intenta borrar no es suyo, metase en sus asuntos.");
				location.href='../miscouchs.php';
				</script>
		<?php
		}
	}else{
		?>	<script> alert("Su couch no existe o ya ha sido eliminado.");
				location.href='../miscouchs.php';
				</script>
		<?php
	}
	}
?>

