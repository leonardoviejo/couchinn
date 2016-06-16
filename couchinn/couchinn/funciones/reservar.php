<?php include('config.php');
	//Variables
	if ((empty($_POST['idcouch']))||(empty($_POST['idusuario']))||(empty($_POST['fechainicio']))||(empty($_POST['fechafin']))){
		echo' <script> alert("Debe ingresar todos los datos pedidos.");
			location.href="../vercouch.php?id='.$idcouch.'";
			</script>';
	}
	else{	
		$idusuario = $_POST['idusuario'];
		$idcouch = $_POST['idcouch'];
		$fechainicio = $_POST['fechainicio'];
		$fechafin = $_POST['fechafin'];
		$hoy = date('Y-m-d');
		
		//Busqueda de couch existente
		$consulta= "SELECT * FROM couch WHERE Id_Couch='$idcouch' and Visible=1";
		$consulta_execute = $conexion->query($consulta);
		if($consulta_execute->num_rows){
			//Comprobacion de fechas
			if ($fechainicio<$fechafin){
				if ($fechainicio>$hoy){
					//Insertar en la base de datos
					$insertar = "INSERT INTO reserva (`Id_Usuario`, `Id_Couch`, `FechaInicio`, `FechaFin`) VALUES ('$idusuario', '$idcouch', '$fechainicio', '$fechafin')";
					if (mysqli_query($conexion, $insertar)) {
						?>	<script> alert("Reserva procesada correctamente.");
							location.href='../misreservas.php';
							</script>
						<?php
					} else {
						echo "ERROR. " . mysqli_error($conexion);
					}
				}else{
					echo' <script> alert("La fecha ingresada es incorrecta.");
					location.href="../vercouch.php?id='.$idcouch.'";
					</script>';
				}
			}else{
				echo' <script> alert("Verifique que la fecha del comienzo de reserva es anterior a la fecha de fin de reserva.");
				location.href="../vercouch.php?id='.$idcouch.'";
				</script>';
				}	
		}else{
			echo' <script> alert("Ya no existe ese couch.");
				location.href="../index.php";
				</script>';
		}
	}
?>

