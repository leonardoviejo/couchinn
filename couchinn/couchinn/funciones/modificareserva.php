<?php include('config.php');
	//Variables
	if ((empty($_POST['idreserva']))||(empty($_POST['fechainicio']))||(empty($_POST['fechafin']))){
		header("Location: ../index.php");
	}
	else{	
	$idreserva = $_POST['idreserva'];
	$fechainicio = $_POST['fechainicio'];
	$fechafin = $_POST['fechafin'];
	
	//Busqueda del usuario
	$consulta= "SELECT * FROM reserva WHERE Id_Reserva='$idreserva' and Visible=1";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){
		$resultado = $consulta_execute->fetch_assoc();
		$idcouch=$resultado['Id_Couch'];
		$consulta= "SELECT * FROM reserva WHERE Id_Couch='$idcouch' and Visible='1' and Estado='confirmada' and (('$fechainicio' between FechaInicio and FechaFin) or ('$fechafin' between FechaInicio and FechaFin) or (('$fechainicio'<FechaInicio)and('$fechafin'>FechaInicio)) or (('$fechafin'>FechaFin)and('$fechainicio'<FechaFin)))";
		$consulta_execute = $conexion->query($consulta);
		if($consulta_execute->num_rows){
			?><script> 
					alert("La fecha solicitada se encuentra ocupada.");
					location.href="../reservashuesped.php";
				</script>
			<?php
		}else{	
			$sql = "UPDATE `reserva` SET `FechaInicio` = '$fechainicio', `FechaFin` = '$fechafin' WHERE `reserva`.`Id_Reserva` = '$idreserva'";
			if (mysqli_query($conexion, $sql)) {
						echo '<script> alert("Se ha actualizado su reserva!!!");
								location.href="../reservashuesped.php";
							</script>';
			} else {
				echo "ERROR. " . mysqli_error($conexion);
			}
		}
	}else{
		?>	<script> alert("Esa reserva no existe.");
				location.href='../reservashuesped.php';
				</script>
		<?php
	}
	}
?>

