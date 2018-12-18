<?php

	class Session {
		
		/**
		*	Inicia la sesión.
		*
		*/
		public static function init() {

			session_cache_limiter('nocache');
	    	session_start();

		}

		/**
		*	Comprueba si el usuario ha sido validado por completo, o esta
		*	semi validado.
		*
		*/
		public static function chkValidity() {

			return isset($_SESSION['validado']);

		}

		/**
		*	Se ejecuta cuando se logea correctamente, se valida la sesión, y se
		*	asignan algunas variables de datos de usuario.
		*
		*	@param string $nombre
		*	@param string $cuota
		*/
		public static function validateUsr($nombre, $cuota) {

			$_SESSION['validado'] = true;
			$_SESSION['nombre'] = $nombre;
			$_SESSION['cuota'] = $cuota;

		}

	}

?>