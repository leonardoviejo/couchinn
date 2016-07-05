<?php include('../../funciones/config.php');
	//Variables
	if ((empty($_POST['idusuario']))||(empty($_POST['monto']))){
		header("Location: ../../index.php");
	}
	else{
		
		$idusuario = $_POST["idusuario"];
		$costo = $_POST["monto"];
		
		//Validar datos admin
		$consulta= "SELECT * FROM usuario WHERE Id_Usuario= '$idusuario' and Id_TipoDeUsuario=2 and Visible=1";
		$consulta_execute = $conexion->query($consulta);
		
		if($consulta_execute->num_rows){
			$sql= "INSERT INTO `costospremium` (`Costo`) VALUES ('$costo')";
			if (mysqli_query($conexion, $sql)) {
				?>	<script> alert("Costo actualizado correctamente!.");
					location.href='../administracion.php';
					</script>
				<?php
			} else {
				echo "ERROR. " . mysqli_error($conexion);
			}
		}else{
			?>	<script> alert("Usted no posee los permisos necesarios.");
					location.href='../../index.php';
				</script>
			<?php
		}
	}
?>