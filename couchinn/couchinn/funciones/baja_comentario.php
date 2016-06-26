<?php include('config.php');
	//Variables
	if ((empty($_POST['idusuario']))||(empty($_POST['idcomentario']))||(empty($_POST['idcouch']))){
		header("Location: ../admin/listarcouchs.php");
	}
	else{
		$idcomentario=$_POST['idcomentario'];
		$idcouch=$_POST['idcouch'];
		$idusuario=$_POST['idusuario'];
		
		//Busqueda del usuario
		$consulta="SELECT * FROM usuario WHERE Id_Usuario='$idusuario' AND Id_TipoDeUsuario=2";
		$consulta_execute = $conexion->query($consulta); //El usuario que elimina comentario es administrador?
		if($consulta_execute->num_rows){
			//Busqueda del comentario
			$consulta= "SELECT * FROM comentario WHERE Id_Comentario='$idcomentario' and Visible=1";
			$consulta_execute = $conexion->query($consulta);
			if($consulta_execute->num_rows){
				$actualizacion = "UPDATE comentario SET Visible = 0 WHERE Id_Comentario = '$idcomentario';";
				if (mysqli_query($conexion, $actualizacion)) {
					echo '<script> alert("Se ha eliminado el comentario!!!");
							location.href="../vercouch.php?id='.$idcouch.'";
						</script>';
				}else{
					echo "ERROR. " . mysqli_error($conexion);
				}
			}else{
				?>	<script> alert("El comentario que desea eliminar no existe.");
						location.href='../admin/listarcouchs.php';
					</script>
				<?php
			}
		}else{
			?>	<script> alert("No tienes permiso para eliminar comentarios.");
						location.href='../index.php';
				</script>
			<?php
		}
	}
?>

