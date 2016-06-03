<?php include('config.php');
	//Variables
	$nombre = $_POST['nombre'];
	$apellido = $_POST['apellido'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$f_nac = $_POST['f_nac'];
	$telefono = $_POST['telefono'];
	
	//Conexion a la base de datos
	if($conexion->connect_errno > 0){
		die('Unable to connect to database [' . $conexion->connect_error . ']');
	}
	
	//Busqueda de usuario existente
	$consulta= "SELECT * FROM usuario WHERE Email='$email'";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){
		?>	<script> alert("Ya existe una cuenta con ese correco electronico.");
				location.href='../registro.php';
				</script>
		<?php
	}else{
		//Insertar en la base de datos
		$insertar = "INSERT INTO usuario (`Nombre`, `Apellido`, `Email`, `Password`, `FechaNac`, `Telefono`) VALUES ('$nombre', '$apellido', '$email', '$password', '$f_nac', '$telefono')";
		if (mysqli_query($conexion, $insertar)) {
			?>	<script> alert("Cuenta creada correctamente. Bienvenido a CouchInn.");
				location.href='../index.php';
				</script>
			<?php
		} else {
			echo "ERROR. " . mysqli_error($conexion);
		}
	}
?>

