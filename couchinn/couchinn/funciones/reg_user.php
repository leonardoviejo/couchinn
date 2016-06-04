<?php include('config.php');
	//Variables
	if ((empty($_POST['nombre']))||(empty($_POST['apellido']))||(empty($_POST['email']))||(empty($_POST['password']))||(empty($_POST['f_nac']))||(empty($_POST['telefono']))){
		?><script> alert("Por favor complete el campo fecha de nacimiento y vuelva a intentarlo.");
			location.href='../registro.php';
		</script>
		<?php
	}
	else{	
	$nombre = $_POST['nombre'];
	$nombre=ucwords(strtolower($nombre));
	$apellido = $_POST['apellido'];
	$apellido=ucwords(strtolower($apellido));
	$email = $_POST['email'];
	$password = $_POST['password'];
	$f_nac = $_POST['f_nac'];
	$telefono = $_POST['telefono'];
	
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
				location.href='../login.php';
				</script>
			<?php
		} else {
			echo "ERROR. " . mysqli_error($conexion);
		}
	}
	}
?>

