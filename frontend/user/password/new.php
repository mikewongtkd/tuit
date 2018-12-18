<?php

	include_once "../session.php";

	if ( ! isset( $_GET[ 'email' ]) || ! isset( $_GET[ 'token' ]) ) {
		header( 'Location: ../index.php' );
		return;
	}

	$email = $_GET[ 'email' ];
	$token = $_GET[ 'token' ];
	$pdo   = new PDO( 'sqlite:/usr/local/pathfx/web/data/users.sqlite' ) or die( "Cannot connect to the database." );

	$stmt = $pdo->prepare( "SELECT reset_token, reset_expiry FROM users WHERE email = :email" );
	$stmt->bindValue( ':email', $email, PDO::PARAM_STR );
	$stmt->execute();
	$row = $stmt->fetch();

	// If no account || bad token || past expiry
	if ( $row == false || $token != $row[ 'reset_token' ] || time() > $row[ 'reset_expiry' ]) {
		$_SESSION[ 'error' ] = 'Reset Link Invalid or Expired';
		header( 'Location: ../index.php' );
	}

?>

<html>
	<head>
	
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="../include/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="../include/fa/css/all.css">
		<link rel="stylesheet" href="../include/alertify/css/alertify.min.css">
		<link rel="stylesheet" href="../include/alertify/css/themes/bootstrap.min.css">

		<script src="../include/jquery/js/jquery.min.js"></script>
		<script src="../include/bootstrap/js/bootstrap.min.js"></script>
		<script src="../include/alertify/js/alertify.min.js"></script>
	</head>

	<body>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-8 col-md-offset-2">

					<h2>Set a New PathFX Password</h2>

					<?php
						if ( isset( $_SESSION[ 'error' ])) {
							$error = $_SESSION[ 'error' ];
							echo '<script>alertify.error( "' . $error . '");</script>';

							unset( $_SESSION[ 'error' ]);
						}
					?>
					<form action="updatepass.php" method="post">
						<div class="form-group">
							<label for="password">New Password</label>
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
						</div>
						<div class="form-group">
							<label for="password">Confirm Password</label>
							<input type="password" class="form-control" id="confirm" name="confirm" placeholder="Confirm Password" required>
						</div>
						<input style="display:none" name="email" value="<?php echo $email ?>">
						<input style="display:none" name="token" value="<?php echo $token ?>">
						<button type="submit" name="newpass-submit" class="btn btn-primary">Reset Password</button>
					</form>

				</div>
			</div>
		</div>

	</body>
</html>
