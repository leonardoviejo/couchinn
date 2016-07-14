<?php include('../../funciones/config.php');
	
	//Variables
	if ((empty($_POST['idadmin']))||(empty($_POST['idusuario']))){
		header("Location: ../index.php");
	}
	else{
	if ((empty($_POST['fechainicio']))||(empty($_POST['fechafin']))){
		$completa=true;
	}else{
		$fechainicio=$_POST['fechainicio'];
		$fechafin=$_POST['fechafin'];
		$completa=false;
	}
	$idadmin = $_POST['idadmin'];
	$idusuario=$_POST['idusuario'];
	
	//Busqueda del administrador
	$consulta= "SELECT * FROM usuario WHERE Id_Usuario='$idadmin' and Visible=1 and Id_TipoDeUsuario=2";
	$consulta_execute = $conexion->query($consulta);
	if($consulta_execute->num_rows){
		//Busqueda de usuario
		$consulta= "SELECT * FROM usuario WHERE Id_Usuario='$idusuario' and Visible=1";
		$consulta_execute = $conexion->query($consulta);
		if($consulta_execute->num_rows){
			$fila = $consulta_execute->fetch_assoc();
			if ($fila['Id_TipoDeUsuario']==2){
				$consultaadmin= "SELECT * FROM usuario WHERE Id_TipoDeUsuario=2 and Visible=1";
				$consulta_execute = $conexion->query($consultaadmin);
				if($consulta_execute->num_rows>1){
					$sql = "UPDATE usuario u left JOIN couch c ON u.Id_Usuario=c.Id_Usuario left JOIN reserva r ON u.Id_Usuario=r.Id_Usuario left JOIN reserva e ON c.Id_Couch=e.Id_Couch left JOIN comentario o ON o.Id_Couch=c.Id_Couch left JOIN punt_couch p ON p.Id_Couch=c.Id_Couch left JOIN punt_usuario n ON n.Id_Usuario=u.Id_Usuario SET u.Visible = 0, c.Visible=0, r.Visible=0, o.Visible=0, p.Visible=0, n.Visible=0, e.Visible=0  WHERE u.Id_Usuario = '$idusuario'";
					if (mysqli_query($conexion, $sql)) {
						if($completa){
							echo '<script> alert("Se ha eliminado la cuenta ADMINISTRADOR!!!");
									location.href="../listarusuarios.php";
								</script>';
						}else{
							echo '<script> alert("Se ha eliminado la cuenta ADMINISTRADOR!!!");
									location.href="../listarusuariosperiodo.php?fechainicio='.$fechainicio.'&fechafin='.$fechafin.'";
								</script>';
						}
					}else{
						echo "ERROR. " . mysqli_error($conexion);
					}
				}else{
					if($completa){
						echo '<script> alert("No puedes eliminar esta cuenta ya que es la última cuenta ADMINISTRADOR del sistema!!!");
								location.href="../listarusuarios.php";
							</script>';
					}else{
						echo '<script> alert("No puedes eliminar esta cuenta ya que es la última cuenta ADMINISTRADOR del sistema!!!");
								location.href="../listarusuariosperiodo.php?fechainicio='.$fechainicio.'&fechafin='.$fechafin.'";
							</script>';
					}
				}
			}else{
				$sql = "UPDATE usuario u left JOIN couch c ON u.Id_Usuario=c.Id_Usuario left JOIN reserva r ON u.Id_Usuario=r.Id_Usuario left JOIN reserva e ON c.Id_Couch=e.Id_Couch left JOIN comentario o ON o.Id_Couch=c.Id_Couch left JOIN punt_couch p ON p.Id_Couch=c.Id_Couch left JOIN punt_usuario n ON n.Id_Usuario=u.Id_Usuario SET u.Visible = 0, c.Visible=0, r.Visible=0, o.Visible=0, p.Visible=0, n.Visible=0, e.Visible=0  WHERE u.Id_Usuario = '$idusuario'";
				if (mysqli_query($conexion, $sql)) {
					if($completa){
							echo '<script> alert("Se ha eliminado la cuenta!!!");
									location.href="../listarusuarios.php";
								</script>';
						}else{
							echo '<script> alert("Se ha eliminado la cuenta!!!");
									location.href="../listarusuariosperiodo.php?fechainicio='.$fechainicio.'&fechafin='.$fechafin.'";
								</script>';
						}
				}else{
					echo "ERROR. " . mysqli_error($conexion);
				}
			}
		}else{
			if($completa){
				echo '<script> alert("El usuario ya se encuentra eliminado.");
						location.href="../listarusuarios.php";
					</script>';
			}else{
				echo '<script> alert("El usuario ya se encuentra eliminado.");
						location.href="../listarusuariosperiodo.php?fechainicio='.$fechainicio.'&fechafin='.$fechafin.'";
					</script>';
			}
		}
	}else{
		if($completa){
			echo '<script> alert("No tiene permisos para borrar un usuario.");
					location.href="../listarusuarios.php";
				</script>';
		}else{
			echo '<script> alert("No tiene permisos para borrar un usuario.");
					location.href="../listarusuariosperiodo.php?fechainicio='.$fechainicio.'&fechafin='.$fechafin.'";
				</script>';
		}
	}
	}
?>

