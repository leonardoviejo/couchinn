<?php include('config.php');
	//Variables
	$email = $_POST['email'];
	$tarjeta = $_POST['tarjeta'];
	$invalidas = 1111111111111111;
	
	//Validar datos
	$consulta = "SELECT * FROM usuario WHERE Email= '$email'";
	$consulta_execute = $conexion->query($consulta);
	
	if($consulta_execute->num_rows){
		$query_result = $consulta_execute->fetch_array();
		$visible = $query_result['Visible'];
		if($visible == 1){ //Verificar si el usuairo no fue borrado (concurrencia)
			if($tarjeta == $invalidas) { //Verificar si es valida la tarjeta.
				echo 
					'<script> alert("La tarjeta con el numero '.$tarjeta.', fue rechazada.");
					location.href="../altapremium.php";
					</script>';
			} else {
				$sql = "UPDATE `usuario` SET `Premium` = '1' WHERE `usuario`.`Email` = '$email'";
				if (mysqli_query($conexion, $sql)) {
					echo 
						'<script> alert("Felicitaciones '.$email.', ahora sos PREMIUM!!!");
						location.href="../miperfil.php";
						</script>';
				} else {
					echo "ERROR. " . mysqli_error($conexion);
				}
			}
		} else {
			echo 
				'<script> alert("El usuario '.$email.', fue borrado del sistema.");
				location.href="cerrar_sesion.php";
				</script>';			
		
		}
	}
?>