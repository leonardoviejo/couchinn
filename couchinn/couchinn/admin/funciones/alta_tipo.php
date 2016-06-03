<?php include('../../funciones/config.php');
	//Variables
	$nombre = $_POST['tipo'];
	$nombre=ucwords(strtolower($nombre));
	//Conexion a la base de datos
	if($conexion->connect_errno > 0){
		die('Unable to connect to database [' . $conexion->connect_error . ']');
	}
	
	//Validar datos
	$consulta= "SELECT * FROM tipodecouch WHERE Nombre= '$nombre'";
	$consulta_execute = $conexion->query($consulta);
	
	if($consulta_execute->num_rows){
		$query_result = $consulta_execute->fetch_array();
		$visible = $query_result['Visible'];
		if($visible == 1){
			?>	<script> alert("El tipo de Couch ya existe.");
				location.href='../tiposdecouch.php';
				</script>
			<?php
		}else{
			$sql = "UPDATE `tipodecouch` SET `Visible` = '1' WHERE `tipodecouch`.`nombre` = '$nombre'";
			if (mysqli_query($conexion, $sql)) {
				?>	<script> alert("El tipo de Couch estaba borrado y ahora sera visible.");
				location.href='../tiposdecouch.php';
				</script>
				<?php				
			} else {
				echo "ERROR. " . mysqli_error($conexion);
			}
		}
	}else{
		$sql= "INSERT INTO `tipodecouch` (`Nombre`) VALUES ('$nombre')";
		if (mysqli_query($conexion, $sql)) {
			?>	<script> alert("Tipo de couch creado correctamente!.");
				location.href='../tiposdecouch.php';
				</script>
			<?php
		} else {
			echo "ERROR. " . mysqli_error($conexion);
		}
	}
?>