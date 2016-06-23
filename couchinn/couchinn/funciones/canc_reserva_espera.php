<?php include('config.php');
	//Variables
	if ((empty($_POST['idreserva']))) {
		echo' <script>
			location.href="../index.php";
			</script>';
	}
	else{	
		$idreserva = $_POST['idreserva'];

		//Busqueda de la reserva a cancelar
		$consulta= "SELECT * FROM reserva WHERE Id_Reserva='$idreserva' and Visible=1 and Estado='espera' ";
		$consulta_execute = $conexion->query($consulta);
		if($consulta_execute->num_rows){
			$sql= "UPDATE `reserva` SET `Estado`='cancelada' WHERE `reserva`.`Id_Reserva` = '$idreserva'";
			if (mysqli_query($conexion, $sql)) {
					echo 
						'<script> alert("La reserva ha sido cancelada!!!");
						location.href="../reservashuesped.php";
						</script>';
				} else {
					echo "ERROR. " . mysqli_error($conexion);
				}
		}else{
			echo' <script> alert("Ya no existe esa reserva.");
				location.href="../index.php";
				</script>';
		}
	}
?>

