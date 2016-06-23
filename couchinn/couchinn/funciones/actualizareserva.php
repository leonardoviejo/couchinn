<?php
	include(config.php);
	$hoy=date('Y-m-d');
	$consulta = "SELECT * FROM reserva WHERE Estado='espera' AND Visible=1 AND FechaFin < '$hoy'";
	$result = $conexion->query($consulta);
	if($result->num_rows > 0){
		while($resultado = $result->fetch_array()) {
			$idreserva=$resultado['Id_Reserva'];
			$sql="UPDATE reserva SET `Estado`='vencida' WHERE `reserva`.`Id_Reserva` = '$idreserva'";
			if (!mysqli_query($conexion, $sql)) {
					echo "ERROR. " . mysqli_error($conexion);
			}
		}
	}
?>