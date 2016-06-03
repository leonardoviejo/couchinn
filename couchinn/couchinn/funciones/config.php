<?php
	$conexion = mysqli_connect('localhost','root','','couchinn');
	if (mysqli_connect_errno()) {
		echo "Falló al conectar con la base de datos: " . mysqli_connect_error();
	}
?>