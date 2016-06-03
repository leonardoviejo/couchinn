<?php include('config.php');
	//Variables
	$email = $_POST['email'];
	
	//Busqueda de usuario existente
	$consulta= "SELECT * FROM usuario WHERE Email='$email'";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){
		$password_nuevo= substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8);
		$consulta = "UPDATE `usuario` SET `Password` = '$password_nuevo' WHERE `usuario`.`Email` = '$email'";
		$conexion->query($consulta);
		enviarEmail($email,$password_nuevo);
		?>	<script> alert("La nueva contraseña fue enviada a su direccion de correo electronico.");
				location.href='../login.php';
				</script>
		<?php
	}else{
		?>	<script> alert("No existe un usuario con esa cuenta de correo electronico.");
				location.href='../recuperarcuenta.php';
				</script>
		<?php
	}
	
	
	function enviarEmail( $email, $password_nuevo ){
		$mensaje = '	<html>
						<head>
							<title>Restablece tu contraseña</title>
						</head>
						<body>
							<p>Hemos recibido una petición para restablecer la contraseña de tu cuenta.</p>
							<p>Esta es tu nueva contraseña: '.$password_nuevo.'</p>
						</body>
						</html>';
	
		$cabeceras = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$cabeceras .= 'From: CouchInn <administracion@couchinn.com>' . "\r\n";
		// Envio de correo
		mail($email, "Recuperar contraseña", $mensaje, $cabeceras);
	}
?>

