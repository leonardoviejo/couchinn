<?php include('config.php');
	//Variables
	if ((empty($_POST['pregunta']))||(empty($_POST['idcouch']))||(empty($_POST['idusuario']))){
		header("Location: ../index.php");
	}else{	
		$idusuario = $_POST['idusuario'];
		$pregunta = $_POST['pregunta'];
		$idcouch = $_POST['idcouch'];
		
		//Validar datos
		$consulta = "SELECT * FROM couch WHERE Id_Couch= '$idcouch' and Visible=1";
		$consulta_execute = $conexion->query($consulta);
		if($consulta_execute->num_rows){
			$sql = "INSERT INTO comentario (`Id_Usuario`, `Id_Couch`, `Mensaje`) VALUES ('$idusuario', '$idcouch', '$pregunta')";
				if (mysqli_query($conexion, $sql)) {
					echo 
						'<script> alert("Se ha procesado su pregunta.");
						location.href="../vercouch.php?id='.$idcouch.'";
						</script>';
				} else {
					echo "ERROR. " . mysqli_error($conexion);
				}
		}else{
				echo 
					'<script> alert("El couch al que intenta preguntar no existe.");
					location.href="cerrar_sesion.php";
					</script>';			
			
		}
	}
?>