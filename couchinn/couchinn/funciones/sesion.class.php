<?php
	class sesion {
		function __construct() {
			session_start ();
		}
		
		public function set($id) {
			$_SESSION ["id"] = $id;
			}
		
		public function get($id) {
			if (isset ( $_SESSION [$id] )) {
				return $_SESSION [$id];
			} else {
				return false;
			}
		}
		
		public function elimina_variable($nombre) {
			unset ( $_SESSION [$nombre] );
		}
  
		public function termina_sesion() {
			$_SESSION = array();
			session_destroy ();
		}
	}
?>