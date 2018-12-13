<?php

    function backToPanel() {
        header("Location: panel.php");
        exit;
    }

    session_cache_limiter('nocache');
	session_start();
    
	if(isset($_SESSION['validado']) && $_SESSION['validado'] == true) {

        if (isset($_FILES['ficheros']) && is_array($_FILES['ficheros']['name'])){

            include '../../seguridad/subida/nomireaqui.php';

			$conexion = @mysqli_connect(IP,USER,PW,DB);
			if(!$conexion){ echo '<h1 style="color:red;text-align:center;">Ha ocurrido un error al conectarse a la DB</h1>'; exit; }
            mysqli_set_charset($conexion,'utf8');

            $sumSize = 0;
            $nFiles = count($_FILES['ficheros']['name']);
            
            for($i = 0; $i < $nFiles; $i++){
                $sumSize += $_FILES['ficheros']['size'][$i];
            }

            if($sumSize <= $_SESSION['cuota']) {

                $usr = $_SESSION['nombre'];
                $query = mysqli_prepare($conexion,"insert into ficheros (id,nombre,tamanyo,tipo,usuario) values (?,?,?,?,?)");
                mysqli_stmt_bind_param($query,"ssiss", $id_ , $nombre_ , $tamanyo_ , $tipo_ , $usr);

                for($i = 0; $i < $nFiles; $i++){
                    switch ($_FILES['ficheros']['error'][$i]) {
                        case UPLOAD_ERR_OK:

                            $id_ = uniqid('', true);
                            $uplFile = __FOLDER__.$id_;

                            if (move_uploaded_file($_FILES['ficheros']['tmp_name'][$i], $uplFile)) {
                                $nombre_ = basename($_FILES['ficheros']['name'][$i]);
                                $tamanyo_ = $_FILES['ficheros']['size'][$i];
                                $tipo_ = $_FILES['ficheros']['type'][$i];
                                mysqli_stmt_execute($query);
                            }

                            echo "TODO OK";
                            break;
                        case UPLOAD_ERR_NO_FILE:

                            echo "UPLOAD_ERR_NO_FILE";
                            break;

                        case UPLOAD_ERR_INI_SIZE:

                            echo "UPLOAD_ERR_INI_SIZE";
                            break;

                        case UPLOAD_ERR_FORM_SIZE:

                            echo "UPLOAD_ERR_FORM_SIZE";
                            break;

                        default:

                            echo "DEFAULT";
                            break;
                    }
                }

                mysqli_stmt_close($query);
                mysqli_close($conexion);

            } else {
                echo "Excede el size permitido";
            }


        } else {
            backToPanel();
        }

	} else {
        backToPanel();
    }

?>