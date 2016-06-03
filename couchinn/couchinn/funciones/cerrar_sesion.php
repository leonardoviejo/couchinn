<?php
	require_once("sesion.class.php");
	
	$sesion = new sesion();
	$idusuario = $sesion->get("id");	
	if( $idusuario == false )
	{	
		header("Location: ../login.php");
	}
	else 
	{
		$idusuario = $sesion->get("id");	
		$sesion->termina_sesion();	
		header("location: ../index.php");
	}
?>