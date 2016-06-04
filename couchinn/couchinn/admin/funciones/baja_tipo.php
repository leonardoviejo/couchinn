<?php include('../../funciones/config.php');
	//Variables
	if (empty($_POST['id'])){
		header("Location: ../../index.php");
	}
	else{
	$id_tipo = $_POST['id'];
	
	$consulta= "SELECT * FROM tipodecouch WHERE Id_Tipo= '$id_tipo'";
	$consulta_execute = $conexion->query($consulta);
	
	if($consulta_execute->num_rows){
		$query_result = $consulta_execute->fetch_array();
		$nombre = $query_result['Nombre'];
		$sql = "UPDATE `tipodecouch` SET `Visible` = '0' WHERE `tipodecouch`.`Id_Tipo` = '$id_tipo'";
		if (mysqli_query($conexion, $sql)) {
			echo "	<script> alert('El tipo de Couch ".$nombre." ha sido borrado.');
					location.href='../tiposdecouch.php';
					</script>";
		} else {
			echo "ERROR. " . mysqli_error($conexion);
		}		
	}
	}
?>