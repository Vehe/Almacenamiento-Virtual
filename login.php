<?php

	include '../../seguridad/subida/session.php';
	include '../../seguridad/subida/db.php';
	
	Session::init();

	$mensaje = "";

	if(isset($_GET['mensaje'])) {
		$mensaje = strip_tags(trim($_GET['mensaje']));
	}

	if(Session::chkValidity()) {

		/**
		 *	En caso de que el usuario entre al login con una sesión en la que ya
		 *	se le ha validado, se le redirige al menú.
		 *
		 */
		header("Location: panel.php");
		exit;

	}

	if(isset($_POST["usuario"]) && isset($_POST["password"])){

		$user = strip_tags(trim($_POST["usuario"]));
		$pw = strip_tags(trim($_POST["password"]));
		
		if(!empty($user) && !empty($pw)){

			$db = new DB();

			$usrData = $db->getUserData($user);

			/**
			 *	Se comprueba el login, en caso de que sea correcto, se valida al usuario,
			 *	y se le envia al panel.
			 *
			 */
			if(password_verify($pw, $usrData['clave'])) {

				Session::validateUsr($user, $usrData['cuota']);
				header("Location: panel.php");
				exit;

			} else {

				/** 
				 *	Se le indica al usuario con un mensaje que la cuenta a la que esta intentando entrar
				 *	no se encuentra en la base de datos.
				 *
				 */
				header("Location: login.php?mensaje=".urlencode("alert('Usuario inexistente o clave no reconocida.')"));
				exit;
				
			}

			$db->close();

		}

	}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Login Disco Duro</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">

</head>
<body onload="<?= $mensaje ?>">
	<div class="container-login100" style="background-image: url('images/bg.jpg');">
		<div class="wrap-login100 p-l-55 p-r-55 p-t-80 p-b-30">
			<form class="login100-form validate-form"  action="login.php" method="post">
				<span class="login100-form-title p-b-37">
					Login
				</span>

				<div class="wrap-input100 m-b-20">
					<input class="input100" type="text" name="usuario" placeholder="Usuario">
					<span class="focus-input100"></span>
				</div>

				<div class="wrap-input100 m-b-25">
					<input class="input100" type="password" name="password" placeholder="Contraseña">
					<span class="focus-input100"></span>
				</div>

				<div class="container-login100-form-btn">
					<button class="login100-form-btn">
						Entrar
					</button>
				</div>
			</form>
		</div>
	</div>

</body>
</html>