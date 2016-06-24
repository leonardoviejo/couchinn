<?php include('config.php');
	//Variables
	if ((empty($_POST['idreserva']))){
		header("Location: ../index.php");
	}
	else{	
	$idreserva = $_POST['idreserva'];
	
	//Validar datos
	$consulta = "SELECT * FROM reserva WHERE Id_Reserva= '$idreserva' and Visible=1 and Estado='espera'";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){ //Verifico que exista la reserva actual a procesar con el estado indicado
		$resultado = $consulta_execute->fetch_array(); //Saco datos de fecha para preguntar si esta libre (por las dudas)
		$idcouch= $resultado['Id_Couch'];
		$fechainicio = $resultado['FechaInicio'];
		$fechafin = $resultado['FechaFin'];
		$consulta= "SELECT * FROM reserva WHERE Id_Couch='$idcouch' and Visible='1' and Estado='confirmada' and (('$fechainicio' between FechaInicio and FechaFin) or ('$fechafin' between FechaInicio and FechaFin) or (('$fechainicio'<FechaInicio)and('$fechafin'>FechaInicio)) or (('$fechafin'>FechaFin)and('$fechainicio'<FechaFin)))";
		$consulta_execute = $conexion->query($consulta);
		if($consulta_execute->num_rows){ //Si devuelve algo significa que esta ocupada
			echo 
				'<script> alert("El couch se encuentra ocupado en esas fechas.");
					location.href="../reservascouch.php";
				</script>';
		}else{ //Sino actualizo la reserva
			$sql = "UPDATE `reserva` SET `Estado` = 'confirmada' WHERE `reserva`.`Id_Reserva` = '$idreserva'";
			if (!mysqli_query($conexion, $sql)) {
				echo "ERROR. " . mysqli_error($conexion);
			}
			//Consulta para buscar reservas que se solapaban con la actual ya confirmada
			$consulta= "SELECT * FROM reserva WHERE Id_Couch='$idcouch' and Visible='1' and Estado='espera' and (('$fechainicio' between FechaInicio and FechaFin) or ('$fechafin' between FechaInicio and FechaFin) or (('$fechainicio'<FechaInicio)and('$fechafin'>FechaInicio)) or (('$fechafin'>FechaFin)and('$fechainicio'<FechaFin)))";
			$consulta_execute = $conexion->query($consulta);
			while($resultado = $consulta_execute->fetch_array()) { //Proceso cada una seteando en rechazada
				$id=$resultado['Id_Reserva'];
				$sql = "UPDATE `reserva` SET `Estado` = 'rechazada' WHERE `reserva`.`Id_Reserva` = '$id'";
				if (!mysqli_query($conexion, $sql)) {
					echo "ERROR. " . mysqli_error($conexion);
				}
			}	
			echo 
				'<script> alert("Se ha confirmado la reserva!!!");
					location.href="../reservascouch.php";
				</script>';
		}
	}else{
			echo 
				'<script> alert("No es posible procesar esa reserva.");
				location.href="../reservascouch.php";
				</script>';
	}
	}
?>