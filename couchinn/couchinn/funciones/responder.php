<?php include('config.php');
	//Variables
	if ((empty($_POST['idmensaje']))||(empty($_POST['respuesta']))||(empty($_POST['idcouch']))){
		header("Location: ../index.php");
	}else{	
		$idmensaje = $_POST['idmensaje'];
		$respuesta = $_POST['respuesta'];
		$idcouch = $_POST['idcouch'];
		
		//Validar datos
		$consulta = "SELECT * FROM comentario WHERE Id_Comentario= '$idmensaje' and Visible=1 and Respondido=0";
		$consulta_execute = $conexion->query($consulta);
		if($consulta_execute->num_rows){
			$sql = "UPDATE `comentario` SET `Respondido` = '1', `Respuesta`='$respuesta' WHERE `comentario`.`Id_Comentario` = '$idmensaje'";
				if (mysqli_query($conexion, $sql)) {
					echo 
						'<script> alert("Se ha procesado su respuesta.");
						location.href="../vercouch.php?id='.$idcouch.'";
						</script>';
				} else {
					echo "ERROR. " . mysqli_error($conexion);
				}
		}else{
				echo 
					'<script> alert("El couch al que intenta responder no existe o ya fue respondido.");
					location.href="cerrar_sesion.php";
					</script>';			
			
		}
	}
?>