<?php include('config.php');
	//Variables
	if ((empty($_POST['idreserva']))||(empty($_POST['mensaje']))||(empty($_POST['puntaje']))||(empty($_POST['idusuario']))||(empty($_POST['idcouch']))){
		header("Location: ../reservashuesped.php");
	}
	else{	
	$idreserva = $_POST['idreserva'];
	$puntaje = $_POST['puntaje'];
	$mensaje = $_POST['mensaje'];
	$idcouch = $_POST['idcouch'];
	$idusuario = $_POST['idusuario'];
	//Obtengo la fecha actual
	$hoy = date('Y-m-d');
	//Validar datos de reserva
	$consulta = "SELECT * FROM reserva WHERE Id_Reserva= '$idreserva' and Visible=1";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){
		$query_result = $consulta_execute->fetch_array();
		if (($query_result['Calif_Huesped']==0)&&($query_result['Estado']=='confirmada')&&($query_result['FechaFin']<$hoy)){
			//Actualizacion de Reserva
			$sql = "UPDATE `reserva` SET `Calif_Huesped` = '1' WHERE `reserva`.`Id_Reserva` = '$idreserva'";
			if (!mysqli_query($conexion, $sql)) {
				echo "ERROR. " . mysqli_error($conexion);
			}
			//Actualizar puntaje en couch
			$sql = "UPDATE `couch` SET `Cant_Calif` =+1, `Total_Calif` =+'$puntaje' WHERE `couch`.`Id_Couch` = '$idcouch'";
			if (!mysqli_query($conexion, $sql)) {
				echo "ERROR. " . mysqli_error($conexion);
			}
			//Insercion de Puntaje en tabla puntajes
			$insertar = "INSERT INTO punt_couch (`Id_Usuario`, `Id_Couch`, `Mensaje`, `Puntaje`) VALUES ('$idusuario', '$idcouch', '$mensaje', '$puntaje')";
			if (mysqli_query($conexion, $insertar)) {
				?>	<script> alert("Puntaje procesado correctamente, Muchas Gracias.");
					location.href='../reservashuesped.php';
					</script>
				<?php
			} else {
				echo "ERROR. " . mysqli_error($conexion);
			}
		}else{
			echo 
				'<script> alert("No existe reserva valida para votacion.");
				location.href="../reservashuesped.php";
				</script>';			
		
		}
	}else{
			echo 
				'<script> alert("No existe reserva.");
				location.href="../reservashuesped.php";
				</script>';			
		
	}
	}
?>