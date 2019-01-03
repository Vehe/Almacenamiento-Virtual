<?php

    function backToPanel() {
        header("Location: panel.php");
        exit;
    }

    include '../../seguridad/subida/session.php';
    include '../../seguridad/subida/db.php';

    Session::init();
    
	if(Session::chkValidity()) {

        /**
         *	En caso de hacer una peticion GET con 'destroy', se borra la session 
         *  actual del usuario, y se le redirige al login.
		 *
		 */
        if(isset($_POST['destroy'])) {
            session_destroy();
            unset($_SESSION);
            header("Location: login.php");
            exit;
        }

        /**
         *	En caso de que se le pase por post el parametro 'id_file_dw', se descarga el fichero
         *  correspondiente a ese ID en caso de existir.
		 *
		 */
        if(isset($_POST['id_file_dw']) && !empty($_POST['id_file_dw'])) {

            echo 'Estas Intentendo DESCARGAR el archivo con ID ' . $_POST['id_file_dw'];
            /*
            $id = strip_tags(trim($_POST['id_file_dw']));

            $db = new DB();
            $fileInfo = $db->getFile($id);

            header("Content-disposition: attachment; filename=" . $fileInfo['nombre']);
            header("Content-type:" . $fileInfo['size']);
            readfile($db->{$FOLDER} . $id);

            $db->close();
            backToPanel();
            */
            
        }

        /**
         *	En caso de que se le pase por post el parametro 'id_file_rm', se borra el fichero
         *  correspondiente a ese ID en caso de existir.
		 *
		 */
        if(isset($_POST['id_file_rm']) && !empty($_POST['id_file_rm'])) {

            $id = strip_tags(trim($_POST['id_file_rm']));

            $db = new DB();
            $db->deleteFile($id);
            $db->close();

            backToPanel();
            
        }

        /**
         *	Se le pasa por parametros los archivos que se quieren subir, y el nombre de la carpeta
         *  padre de el/los archivos.
		 *
		 */
        if (isset($_FILES['ficheros']) && is_array($_FILES['ficheros']['name']) && isset($_POST['parent']) && !empty($_POST['parent'])){
            
            $nFicheros = count($_FILES['ficheros']['name']);
            $parent = strip_tags(trim($_POST['parent']));
            $db = new DB();
            
            for($i = 0; $i < $nFicheros; $i++){

                switch ($_FILES['ficheros']['error'][$i]) {
                    case UPLOAD_ERR_OK:
                        
                        $ret = $db->uploadFile($_FILES['ficheros']['name'][$i], $_FILES['ficheros']['size'][$i], $_FILES['ficheros']['type'][$i], $_FILES['ficheros']['tmp_name'][$i], $_SESSION['nombre'], $parent);
                        echo $ret;

                        break;
                    case UPLOAD_ERR_NO_FILE:
                        break;
                    case UPLOAD_ERR_INI_SIZE:
                        break;
                    case UPLOAD_ERR_FORM_SIZE:
                        break;
                    default:
                        break;
                }

            }

            $db->close();
            backToPanel();

        }

        /**
         *	En caso de que se le pase por post el parametro 'cte_new_folder', crea en la base de datos
         *  una nueva carpeta con su parent correspondiente.
		 *
		 */
        if(isset($_POST['cte_new_folder']) && !empty($_POST['cte_new_folder']) && isset($_POST['prnt']) && !empty($_POST['prnt'])) {

            $parent = strip_tags(trim($_POST['prnt']));
            $nom = strip_tags(trim($_POST['cte_new_folder']));

            $db = new DB();
            $db->createNewFolder($nom, $_SESSION['nombre'], $parent);
            $db->close();

            backToPanel();
            
        }

        /**
         *	En caso de que se le pase por post el parametro 'cte_rm_folder', se borra el directorio
         *  correspondiente a ese ID en caso de existir.
		 *
		 */
        if(isset($_POST['cte_rm_folder']) && !empty($_POST['cte_rm_folder']) && isset($_POST['confirm']) && !empty($_POST['confirm'])) {

            $id = strip_tags(trim($_POST['cte_rm_folder']));
            $conf = strip_tags(trim($_POST['confirm']));

            $db = new DB();
            $db->deleteFolder($id, $conf);
            $db->close();

            backToPanel();
            
        }

	} else {

        header("Location: login.php");
        exit;
        
    }
    
?>