<?php
	include("config.php");
	require_once("sesion.class.php");
	
	$sesion = new sesion();
	
	if ((empty($_POST['email']))||(empty($_POST['password']))){
		header("Location: ../index.php");
	}
	else{
	
	$email = $_POST["email"];
	$password = $_POST["password"];
	
	$consulta = "SELECT * FROM usuario WHERE Email='$email' AND Visible=1";
	$result = $conexion->query($consulta);
		
	if($result->num_rows > 0){
		$fila = $result->fetch_assoc();
		$id=$fila['Id_Usuario'];
		if( strcmp($password,$fila['Password']) == 0 ){
			$sesion->set($id);
			require_once("actualizareserva.php");
			header("location: ../index_login.php");					
		}else{
			?>	<script> 
					alert("Verifica tu contraseña.");
					location.href="../login.php";
				</script>
			<?php
		}
	}else{
		?>	<script> alert("Usuario inexistente.");
			location.href="../login.php";
		</script>
		<?php
	}
	}
?>

