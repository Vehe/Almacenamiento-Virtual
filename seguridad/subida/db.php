<?php

	class DB {

		private $IP = "80.211.46.139";
		private $USER = "discoduro2";
		private $PW = "discoduro2";
		private $DB = "discoduro2";
		public $FOLDER = 'C:\xampp\seguridad\subida\ficheros\\';
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
		 *
		 *	@return array
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
		 *	Se pasa como parametro el id del fichero que se desea descargar,
		 *	seleciona datos sobre este fichero, y los devuelve.
		 *
		 *	@param string $id
		 *
		 *	@return array
		 */
		function getFile($id) {

			$query = mysqli_prepare($this->CON, "select nombre, tamanyo, tipo from ficheros where id=?");
			mysqli_stmt_bind_param($query, "s", $id);
			mysqli_stmt_bind_result($query, $nom, $size, $type);
			mysqli_stmt_execute($query);
			mysqli_stmt_store_result($query);
			$n = mysqli_stmt_num_rows($query);
			if ($n != 1) { header("Location: error.php"); exit; }
			mysqli_stmt_fetch($query);
			mysqli_stmt_close($query);
			unset($query);

			return array('nombre' => $nom, 'size' => $size, 'tipo' => $type);

		}

		/**
		 *	Se pasa como parametro el id del fichero que se desea borrar del sistema.
		 *
		 *	@param string $id
		 */
		function deleteFile($id) {

			if (unlink($this->FOLDER . $id)){
				
				$query = mysqli_prepare($this->CON, "delete from disco where id=?");
				mysqli_stmt_bind_param($query, "s", $id);
				mysqli_stmt_execute($query);
				mysqli_stmt_close($query);
				unset($query);
				
			}else { header("Location: error.php"); exit; }
			
		}

		/**
		 *	Se pasa como parametro el id de la carpeta que se desea borrar del sistema.
		 *	En caso de que no se quiera eliminar la carpeta si tiene contenido dentro, se vuelve sin realizar cambios.
		 *
		 *	@param string $id
		 *	@param string $conf
		 *
		 *	@return int
		 */
		function deleteFolder($id, $conf) {

			$dir = $this->getFoldersFromDirectory($id);

			if($conf == 'false') {
				if(!empty($dir)){
					return -1;
				}
			}

			$this->deleteFromParent($dir);

			$query = mysqli_prepare($this->CON, "delete from disco where id=?");
			mysqli_stmt_bind_param($query, "s", $id);
			mysqli_stmt_execute($query);
			mysqli_stmt_close($query);
			unset($query);

			return 0;
			
		}

		/**
		 *	Borra el directorio que se le pasa como parametro, y todos sus hijos.
		 *
		 *	@param array $dir
		 */
		function deleteFromParent($dir) {

			foreach($dir as $key => $value) {

				$key = explode('::',$key);

				if(gettype($value) == 'array') {

					if(!empty($value)) {
						$this->deleteFromParent($dir);
					} else {
						$this->deleteFolder($key[1], true);
					}
					
				} else {
					$this->deleteFile($key[1]);	
				}

			}

		}

		/**
		 *	Se pasa como parametro el usuario del cual necesitamos buscar el id del directorio raiz.
		 *
		 *	@param string $usr
		 *
		 *	@return string
		 */
		function getRootDirectory($usr) {
			
			$dir = "/";

			$query = mysqli_prepare($this->CON, "select id from disco where nombre=? and usuario=?");
			mysqli_stmt_bind_param($query, "ss", $dir, $usr);
			mysqli_stmt_bind_result($query, $id);
			mysqli_stmt_execute($query);
			mysqli_stmt_store_result($query);
			mysqli_stmt_fetch($query);
			mysqli_stmt_close($query);
			unset($query);

			return $id;
			
		}

		/**
		 *	
		 *	Devuelve las carpetas que contiene cierta carpeta, la cual es pasada por parametro.
		 *
		 *	@param string $id
		 *
		 *	@return array
		 */
		function getFoldersFromDirectory($id) {

			$archivos = $this->getFilesFromDirectory($id);

			$contenido = array();

			$query = mysqli_prepare($this->CON, "select id, nombre from disco where id_depende=? and tipoFichero='D'");
			mysqli_stmt_bind_param($query, "s", $id);
			mysqli_stmt_bind_result($query, $id, $nombre);
			mysqli_stmt_execute($query);
			mysqli_stmt_store_result($query);
			
			while(mysqli_stmt_fetch($query)) {
				$contenido[$nombre . '::' . $id] = $this->getFoldersFromDirectory($id);
			}

			mysqli_stmt_close($query);
			unset($query);

			return array_merge($contenido, $archivos);
			
		}

		/**
		 *	
		 *	Devuelve en forma de array los archivos de la carpeta que se le pasa por parametro.
		 *
		 *	@param string $id
		 *
		 *	@return array
		 */
		function getFilesFromDirectory($id) {

			$contenido = array();

			$query = mysqli_prepare($this->CON, "select id, nombre from disco where id_depende=? and tipoFichero='A'");
			mysqli_stmt_bind_param($query, "s", $id);
			mysqli_stmt_bind_result($query, $id, $nombre);
			mysqli_stmt_execute($query);
			mysqli_stmt_store_result($query);

			$n = mysqli_stmt_num_rows($query);
			if($n == 0) { return array(); }
			
			while(mysqli_stmt_fetch($query)) {
				$contenido[$nombre . '::' . $id] = "A";
			}

			mysqli_stmt_close($query);
			unset($query);

			return $contenido;
			
		}

		/**
		 *	
		 *	Sube a la BD los datos de el/los nuevo/s ficheros y devuelve true si se ha realizado correctamente.
		 *
		 *	@param string $nombre_
		 *	@param string $tamanyo_
		 *	@param string $tipo_
		 *	@param string $tmp_name
		 *	@param string $usr
		 *	@param string $parent
		 *
		 *	@return boolean
		 */
		function uploadFile($nombre_, $tamanyo_, $tipo_, $tmp_name, $usr, $parent) {

			$id_ = uniqid('',true);
			$typ = 'A';
			$ficheroSubido = $this->FOLDER . $id_;
			
            $query = mysqli_prepare($this->CON, "insert into disco values (?,?,?,?,?,?,?)");
			mysqli_stmt_bind_param($query,"ssissss", $id_, $nombre_, $tamanyo_, $tipo_, $typ, $usr ,$parent);

			if (move_uploaded_file($tmp_name, $ficheroSubido)) { mysqli_stmt_execute($query); } else { return false; }
			
            mysqli_stmt_close($query);
			unset($query);

			return true;

		}

		/**
		 *	
		 *	Crea una carpeta en la base de datos y devuelve true si se ha realizado correctamente.
		 *
		 *	@param string $nombre_
		 *	@param string $usr
		 *	@param string $parent
		 *
		 *	@return boolean
		 */
		function createNewFolder($nombre_, $usr, $parent) {

			$id_ = uniqid('',true);
			$tamanyo_ = 0;
			$tipo_ = null;
			$typ = 'D';
			$ficheroSubido = $this->FOLDER . $id_;
			
            $query = mysqli_prepare($this->CON, "insert into disco values (?,?,?,?,?,?,?)");
			mysqli_stmt_bind_param($query,"ssissss", $id_, $nombre_, $tamanyo_, $tipo_, $typ, $usr ,$parent);
			mysqli_stmt_execute($query);
            mysqli_stmt_close($query);
			unset($query);

			return true;

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