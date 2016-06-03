<?php
	$conexion = mysqli_connect('localhost','root','','couchinn');
	if (mysqli_connect_errno()) {
		echo "Falló al conectar con la base de datos: " . mysqli_connect_error();
	}
	if (!mysqli_set_charset($conexion, "utf8")) {
		printf("Error cargando el conjunto de caracteres utf8: %s\n", mysqli_error($enlace));
		exit();
	}
?>