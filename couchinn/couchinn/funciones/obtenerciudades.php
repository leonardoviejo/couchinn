<?php
	if(isset($_POST["idprovincia"]))
	{
		include 'config.php';
		$opciones = '<option value="">Elige una ciudad...</option>';

		$consulta = "SELECT Id, Localidad from localidades where Id_Provincia = ".$_POST["idprovincia"];
		$result = $conexion->query($consulta);
		

		while( $fila = $result->fetch_array() )
		{
			$opciones.='<option value="'.$fila["Id"].'">'.$fila["Localidad"].'</option>';
		}

		echo $opciones;
	}
?>

