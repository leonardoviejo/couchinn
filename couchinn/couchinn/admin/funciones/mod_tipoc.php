<?php include('../../funciones/config.php');
	//Variables
	if ((empty($_POST['nombretipo']))||(empty($_POST['tipocouch']))){
		header("Location: ../../index.php");
	}
	else{
	$nombre_actual = $_POST['nombretipo'];
	$nombre_nuevo = $_POST['tipocouch'];
	$nombre_nuevo=ucwords(strtolower($nombre_nuevo));
	$nombre_actual=utf8_encode($nombre_actual);
	$nombre_nuevo=utf8_encode($nombre_nuevo);
	
	//Validar datos
	$consulta = "SELECT * FROM tipodecouch WHERE Nombre= '$nombre_actual'";
	$consulta_execute = $conexion->query($consulta);
	
	if($consulta_execute->num_rows){
		$query_result = $consulta_execute->fetch_array();
		$visible = $query_result['Visible'];
		if($visible == 1){ //Verificar si el couch a modificar está visible (concurrencia)
			$consulta = "SELECT * FROM tipodecouch WHERE Nombre= '$nombre_nuevo'";
			$consulta_execute = $conexion->query($consulta);
			if($consulta_execute->num_rows) { //Verificar si el nuevo nombre es igual a otro nombre de tipo de couch ya existente.
				echo 
					'<script> alert("Ya existe un tipo de Couch llamado '.$nombre_nuevo.', no se realizaron cambios.");
					location.href="../tiposdecouch.php";
					</script>';
			} else {
				$sql = "UPDATE `tipodecouch` SET `Nombre` = '$nombre_nuevo' WHERE `tipodecouch`.`nombre` = '$nombre_actual'";
				if (mysqli_query($conexion, $sql)) {
					echo 
						'<script> alert("El tipo de Couch '.$nombre_actual.' ahora se llama '.$nombre_nuevo.'.");
						location.href="../tiposdecouch.php";
						</script>';
				} else {
					echo "ERROR. " . mysqli_error($conexion);
				}
			}
		} else {
			echo 
				'<script> alert("El tipo de Couch '.$nombre_actual.' no se pudo modificar ya que fue borrado.");
				location.href="../tiposdecouch.php";
				</script>';			
		
		}
	}
	}
?>