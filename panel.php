<?php

    /**
     *  Devuelve en formato de string el formato en el que se muestra un fichero en el cuerpo de la pagina 
     *  con los datos correspondientes que se le pasan por parametro. 
     *
     *  @param string $nameFile
     *  @param string $idFile
     * 
     *  @return string
     */
    function createFileElement($nameFile, $idFile) {

        $result = '<div> <div class="rowin"> <div class="columnin"> <div class="div-folders cont"> <i class="fas fa-file inside"></i> <span class="name-file">' . $nameFile . '</span> </div> </div>';
        $result .= '<div class="columnin2 div-folders"> <i class="fas fa-file-download" id="' . $idFile . '"></i> <i class="fas fa-trash-alt" id="' . $idFile . '"></i> </div> </div> </div>';
        return $result;

    }

    /**
     *  Devuelve en formato de string el formato en el que se muestra un directorio en el cuerpo de la pagina 
     *  con los datos correspondientes que se le pasan por parametro, se hace uso de recursividad para sacar todo el arbol
     *  de directorios correspondiente. 
     *
     *  @param string $nameFile
     *  @param string $idFile
     * 
     *  @return string
     */
    function createFolderElement($nameFolder, $idFolder, $childArray) {

        $idF = str_replace(".", "-", $idFolder);

        $result = '<div> <div class="rowin"> <div class="columnin"> <div class="div-folders display-content cont" data-toggle="collapse" data-target="#' . $idF . '"> <i class="fas fa-folder inside"></i>';
        $result .= '<span class="name-folder">' . $nameFolder . '</span>';
        $result .= '</div> </div> <div class="columnin2">';
        $result .= '<div class="hide"><form enctype="multipart/form-data" action="admin.php" method="post"><input type="hidden" name="MAX_FILE_SIZE" value="2500000" /> <input type="file" name="ficheros[]" id="FF' . $idF . '" class="inpUser inpUser-1 hide" data-multiple-caption="{count} archivos seleccionados" multiple="multiple" /><label class="trans" for="FF' . $idF . '"> <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Seleccionar Archivos &hellip;</span> </label><input type="hidden" name="parent" value="' . $idFolder . '" /><input type="submit" class="send" value="Enviar" /><input type="reset" class="send reset" value="Cancelar" /></form></div>';
        $result .= '<div class="div-folders show"><i class="fas fa-file-medical"></i> <i class="fas fa-folder-plus" id="' . $idFolder . '"></i> <i class="fas fa-folder-minus" id="' . $idFolder . '"></i></div>';
        $result .= '</div> </div><div id="' . $idF . '" class="collapse">';

        foreach($childArray as $key => $value) {

            $key = explode('::',$key);

            if(gettype($value) == 'array') {

                $result .= createFolderElement($key[0], $key[1], $value);

            } else {

                $result .= createFileElement($key[0], $key[1]);

            }

        }

        $result .= '</div></div>';
        return $result;

    }

    
    include '../../seguridad/subida/session.php';
    include '../../seguridad/subida/db.php';

    Session::init();
  
	if(Session::chkValidity()) {

        /**
         *  Una vez que comprobamos que el usuario tiene una sesion valida,
         *  descargamos de la base de datos todo el arbol de directorios correspondiente a este usuario.
         *
         */
        $db = new DB();

        $usr = strip_tags(trim($_SESSION['nombre']));
        $id_root = $db->getRootDirectory($usr);
        $tree = $db->getFoldersFromDirectory($id_root);


	} else {

        header("Location: login.php");
        exit;
        
    }

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Panel de Administracion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="js/jquery.redirect.js"></script>
    <link rel="stylesheet" type="text/css" href="css/panel.css" />
    <script src="js/panel.js"></script>
</head>
<body>
    <header class="sky">
        <h1>Almacenamiento Virtual</h1>
    </header>

    <div class="christmasLights"></div>

    <div class="container">

        <div>
            <div class="rowfirts">
                <div class="columnin usr">
                    <span>Conectado como usuario: <strong><?= (isset($_SESSION['nombre'])) ? strip_tags(trim($_SESSION['nombre'])) : "Desconocido" ?></strong></span>
                </div>

                <div class="columnin2">
                    <button id="destroy">Desconectarse</button>
                </div>

            </div>
        </div>

        <?php 

            /**
             *  Crea el cuerpo de la pagina, crea el arbol de directorios correspondiente al id del usuario, 
             *  que ha iniciado sesion. 
             *
             */
            foreach($tree as $key => $value) {

                $key = explode('::',$key);

                if(gettype($value) == 'array') {
    
                    echo createFolderElement($key[0], $key[1], $value);

                } else {

                    echo createFileElement($key[0], $key[1]);

                }
               
            }

        ?>

    </div>
</body>
</html>