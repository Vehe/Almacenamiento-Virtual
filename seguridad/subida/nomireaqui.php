<?php

    define("IP","80.211.46.139");
    define ("USER", "discoduro");
    define ("PW", "discoduro");
    define ("DB", "discoduro");
    define("__FOLDER__","../../seguridad/subida/ficheros/");


    /*
	*	Se ejecuta cuando se logea correctamente.
	*	@param string $nombre
	*	@param string $cuota
	*/
    function validarUsuario($nombre, $cuota) {

    	session_cache_limiter('nocache');
		session_start();

		$_SESSION['validado'] = true;
		$_SESSION['nombre'] = $nombre;
		$_SESSION['cuota'] = $cuota;

    }

?>