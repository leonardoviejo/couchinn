<?php include('../../funciones/config.php');
	//Variables
	if ((empty($_POST['idadmin']))||(empty($_POST['idusuario']))){
		header("Location: ../../index.php");
	}
	else{
		$idadmin = $_POST["idadmin"];
		$idusuario = $_POST["idusuario"];
		
		//Validar datos admin
		$consulta= "SELECT * FROM usuario WHERE Id_Usuario= '$idadmin' and Visible=1 and Id_TipoDeUsuario=2";
		$consulta_execute = $conexion->query($consulta);
		if($consulta_execute->num_rows){
			$consulta= "SELECT * FROM usuario WHERE Id_Usuario= '$idusuario' and Visible=1";
			$consulta_execute = $conexion->query($consulta);
			if ($resultado = $consulta_execute->fetch_array()){
				if($resultado["Id_TipoDeUsuario"]==1){
					$sql = "UPDATE `usuario` SET `Id_TipoDeUsuario` = '2' WHERE `usuario`.`Id_Usuario` = '$idusuario'";
					if (mysqli_query($conexion, $sql)) {
						?>	<script> alert("El usuario ahora es administrador.");
								location.href="../listarusuarios.php";
							</script>
						<?php				
					}else{
						echo "ERROR. " . mysqli_error($conexion);
					}
				}else{
					$consultaadmin= "SELECT * FROM usuario WHERE Id_TipoDeUsuario=2 and Visible=1";
					$consulta_execute = $conexion->query($consultaadmin);
					if($consulta_execute->num_rows>1){
						$sql = "UPDATE usuario SET usuario.Id_TipoDeUsuario=1  WHERE usuario.Id_Usuario = '$idusuario'";
						if (mysqli_query($conexion, $sql)) {
							echo '<script> alert("El usuario ya no es administrador");
									location.href="../listarusuarios.php";
								</script>';
						}else{
							echo "ERROR. " . mysqli_error($conexion);
						}
					}else{
						echo '<script> alert("No puedes sacar privilegios a esta cuenta ya que es la última cuenta ADMINISTRADOR del sistema!!!");
								location.href="../listarusuarios.php";
							</script>';
					}
				}
			}else{
				echo 	'<script> alert("El usuario no existe.");
							location.href="../listarusuarios.php";
						</script>';
			}
		}else{
			echo '<script> alert("Usted no posee los permisos necesarios!.");
				location.href="../../index.php";
			</script>';
		}
	}
?>