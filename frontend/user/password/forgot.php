<?php 

	include_once '../session.php';

	if ( !file_exists( '/usr/local/pathfx/web/data/config.json' )) {
		$_SESSION[ 'is_auth' ] = false;
		$_SESSION[ 'error' ]   = 'Reset is currently disabled.';

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
					<h2>Reset Your PathFX Password</h2>

					<?php
						if ( isset( $_SESSION[ 'message' ]) && isset( $_SESSION[ 'reset' ]) && $_SESSION[ 'reset' ]) {
							$_SESSION[ 'reset' ] = false;
							$message = $_SESSION[ 'message' ];
							echo '<script>alertify.success( "' . $message . '" );</script>';

						} else if ( isset( $_SESSION[ 'error' ])) {
							$error = $_SESSION[ 'error' ];
							echo '<script>alertify.error( "' . $error . '");</script>';

							unset( $_SESSION[ 'error' ]);
						}

						unset( $_SESSION[ 'message' ]);
						unset( $_SESSION[ 'error' ]);
						unset( $_SESSION[ 'reset' ]);
					?>

					<form action="sendresetlink.php" method="post">
						<div class="form-group">
							<label for="email">Email Address</label>
							<input type="email" class="form-control" id="email" name="email" placeholder="Email Address" required>
						</div>
						<button type="submit" name="login-submit" class="btn btn-primary">Reset Password</button> <a href="/">Back to PathFX</a>
					</form>
				</div>
			</div>
		</div>

	</body>
</html>
