<?php
	include("config.php");
	require_once("sesion.class.php");
	
	$sesion = new sesion();
	
	$email = $_POST["email"];
	$password = $_POST["password"];
	
	if(validarUsuario($email,$password,$conexion) == true)
	{			
		$sesion->set("usuario",$email);	
		header("location: ../index_login.php");
	}
	else 
	{
		?>	<script>
			location.href="../login.php";
			</script>
		<?php
	}
		
	function validarUsuario($email, $password, $conexion){
		$consulta = "SELECT Password FROM usuario WHERE Email='$email'";
		$result = $conexion->query($consulta);
		
		if($result->num_rows > 0){
			$fila = $result->fetch_assoc();
			if( strcmp($password,$fila['Password']) == 0 )
				return true;						
			else
				?>	<script> alert("Verifica tu contraseña.");
				</script>
				<?php
				return false;
		}
		else
				?>	<script> alert("Usuario inexistente.");
				</script>
				<?php
				return false;
		}

?>

