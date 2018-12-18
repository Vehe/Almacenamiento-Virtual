<?php

	class DB {

		private $IP = "80.211.46.139";
		private $USER = "discoduro2";
		private $PW = "discoduro2";
		private $DB = "discoduro2";
		private $FOLDER = "../../seguridad/subida/ficheros/";
		private $CON;
	
		/**
		*	Cuando se llama a la clase se ejecuta el constructor, el cual crea
		*	una conexion con la base de datos con los datos correspondientes, y la devuelve.
		*
		*/
		function __construct() {
			
			$this->CON = @mysqli_connect($this->IP, $this->USER, $this->PW, $this->DB);

			mysqli_set_charset($this->CON,'utf8');

			return $this->CON;
	
		}

		/**
		*	Se pasa como parametro el nombre el usuario del que se necesitan los datos
		*	los cuales usaremos posteriormente en diversas zonas del programa.
		*
		*	@param string $u
		*/
		function getUserData($u) {

			$query = mysqli_prepare($this->CON, "select usuario, clave, cuota from usuarios where usuario=?");
			mysqli_stmt_bind_param($query,"s",$u);
			mysqli_stmt_execute($query);
			mysqli_stmt_bind_result($query, $nombre, $clave, $cuota);
			mysqli_stmt_fetch($query);
			mysqli_stmt_close($query);
			unset($query);

			return array('nombre' => $nombre, 'clave' => $clave, 'cuota' => $cuota);		

		}

		/**
		*	Se cierra la conexion actual.
		*
		*/
		function close() {

			mysqli_close($this->CON);

		}
	}

?>