<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Registrar usuario - CouchInn</title>
</head>
<body>
<?php include('config.php');
	//Variables
	$nombre = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$f_nac = $_POST['f_nac'];
	$telefono = $_POST['telefono'];
	
	//echo 'Fecha: ' . $f_nac . ' ';
	
	//$new_f_nac = date('Y-m-d',strtotime($f_nac));
	
	//echo 'Nueva Fecha: ' . $new_f_nac . ' ';
	
	//Conexion a la base de datos
	if($conexion->connect_errno > 0){
		die('Unable to connect to database [' . $conexion->connect_error . ']');
	}
	
	//Insertar en la base de datos
	$insertar = "INSERT INTO usuario (`Nombre`, `Apellido`, `Email`, `Password`, `FechaNac`, `Telefono`) VALUES ('$nombre', '$apellido', '$email', '$password', '$f_nac', '$telefono')";
	if (mysqli_query($conexion, $insertar)) {
			echo "Se agrego un usuario";
	} else {
			echo "ERROR. " . mysqli_error($conexion);
	}
?>	
</body>
</html>