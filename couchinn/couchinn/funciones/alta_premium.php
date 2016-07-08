<?php include('config.php');
	//Variables
	if ((empty($_POST['idusuario']))||(empty($_POST['tarjeta']))||(empty($_POST['idcosto']))){
		header("Location: ../index.php");
	}
	else{	
	$idusuario = $_POST['idusuario'];
	$tarjeta = $_POST['tarjeta'];
	$idcosto = $_POST['idcosto'];
	$invalidas = 1111111111111111;
	
	//Validar datos
	$consulta = "SELECT * FROM usuario WHERE Id_Usuario= '$idusuario' and Visible=1";
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
				$sql = "UPDATE `usuario` SET `Premium` = '1', `Id_CostoPremium`='$idcosto', `FechaAltaPremium`=curdate() WHERE `usuario`.`Id_Usuario` = '$idusuario'";
				if (mysqli_query($conexion, $sql)) {
					echo 
						'<script> alert("Felicitaciones, ahora eres PREMIUM!!!");
						location.href="../miperfil.php";
						</script>';
				} else {
					echo "ERROR. " . mysqli_error($conexion);
				}
			}
		} else {
			echo 
				'<script> alert("El usuario fue borrado del sistema.");
				location.href="cerrar_sesion.php";
				</script>';			
		
		}
		
	}else{
			echo 
				'<script> alert("El usuario no existe.");
				location.href="cerrar_sesion.php";
				</script>';			
		
	}
	}
?>