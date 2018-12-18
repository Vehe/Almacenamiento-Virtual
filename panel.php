<?php

    include '../../seguridad/subida/session.php';
    include '../../seguridad/subida/db.php';

    Session::init();
    
	if(Session::chkValidity()) {

        

	} else {

        header("Location: login.php");
        exit;
        
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Panel de Administracion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/panel.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="js/jquery.redirect.js"></script>
    <script src="js/peticion.js"></script>
    <script src="js/panel.js"></script>
</head>
<body>
    <div class="padd"></div>
    <div class="container tam">
        <div class="fila">
            <div class="columna">
                <div class="space zoom">
                    <div class="prueba">
                        <div class="roww">
                            <div class="colu1">
                                <span class="tit">Archivos Actuales</span>
                            </div>
                            <div class="colu2">
                                <div class="actarch">
                                    <form action="#" class="formbg">
                                        <span class="fname">pablodios.php</span><br /><br />
                                        <input type="submit" class="fbtn" value="Descargar" />
                                        <input type="submit" class="fbtn" value="Borrar" />
                                    </form>
                                    <form action="#" class="formbg">
                                        <span class="fname">manolofeo.pdf</span><br /><br />
                                        <input type="submit" class="fbtn" value="Descargar" />
                                        <input type="submit" class="fbtn" value="Borrar" />
                                    </form>
                                    <form action="#" class="formbg">
                                        <span class="fname">jajajaj.salu2</span><br /><br />
                                        <input type="submit" class="fbtn" value="Descargar" />
                                        <input type="submit" class="fbtn" value="Borrar" />
                                    </form>
                                    <form action="#" class="formbg">
                                        <span class="fname">agustinpanchito.lol</span><br /><br />
                                        <input type="submit" class="fbtn" value="Descargar" />
                                        <input type="submit" class="fbtn" value="Borrar" />
                                    </form>
                                    <form action="#" class="formbg">
                                        <span class="fname">agustinpanchito.lol</span><br /><br />
                                        <input type="submit" class="fbtn" value="Descargar" />
                                        <input type="submit" class="fbtn" value="Borrar" />
                                    </form>
                                    <form action="#" class="formbg">
                                        <span class="fname">agustinpanchito.lol</span><br /><br />
                                        <input type="submit" class="fbtn" value="Descargar" />
                                        <input type="submit" class="fbtn" value="Borrar" />
                                    </form>
                                </div>
                                <span class="justpadd"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="columna">
                <div class="space zoom">
                    <div class="prueba">
                        <div class="roww">
                            <div class="colu1">
                                <span class="tit">Subir Archivos</span>
                            </div>
                            <div class="colu2">
                                <form enctype="multipart/form-data" action="admin.php" method="post">
                                    <div class="input-group">
                                        <label class="input-group-btn">
                                            <span class="btn btn-primary">
                                                <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                                                Seleccionar&hellip;<input type="file" name="ficheros[]" multiple="multiple" style="display: none;">
                                            </span>
                                        </label>
                                        <input type="text" class="form-control" readonly>
                                    </div>
                                    <input type="submit" class="bntsend" value="Subir" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>