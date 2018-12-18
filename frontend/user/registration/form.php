<?php 
	include_once "session.php";
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="css/index.css">
		<link rel="stylesheet" type="text/css" href="include/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="include/fa/css/fontawesome.css">
		<script src="include/jquery/js/jquery.min.js"></script>
		<script src="include/bootstrap/js/bootstrap.min.js"></script>
		<title>Taekwondo University Registration</title>
	</head>

	<body>

		<?php include_once( 'header.php' ); ?>
		<?php
			$form = new stdClass();
			if( isset( $_SESSION[ 'form' ] )) {
				$form = json_decode( base64_decode( $_SESSION[ 'form' ] ));
				unset( $_SESSION[ 'form' ] );
			}
		?>
		<div class="registration-form container" style="padding:50px 0 100px 0;">
		<form  action="handle/user-registration.php" method="post">
		<div class="form-group row">
			<label for="email" class="col-lg-4">Email Address</label>
			<input type="email" class="form-control col-lg-8" id="email" name="email" placeholder="Email Address" value="<?= $form->email ?>" required>
		</div>

		<div class="form-group row">
			<label for="password" class="col-lg-4">Password</label>
			<input type="password" class="form-control col-lg-8" id="password" name="password" placeholder="Password" required>
		</div>

		<div class="form-group row">
			<label for="confirm" class="col-lg-4">Confirm Password</label>
			<input type="password" class="form-control col-lg-8" id="confirm" name="confirm" placeholder="Confirm Password" required>
		</div>

		<div class="form-group row">
			<label for="firstname" class="col-lg-4">Full Name</label>
			<input type="text" class="form-control col-lg-4" id="firstname" name="firstname" placeholder="First Name" value="<?= $form->firstname ?>" required>
			<input type="text" class="form-control col-lg-4" id="lastname"	name="lastname"	placeholder="Last Name"	value="<?= $form->lastname ?>" required>
		</div>

		<div class="form-group row">
			<label for="role" class="col-lg-4">Role</label>
			<select class="form-control col-lg-8" id="role" name="role">
			<option <?php if( $form->role == 'Student'    ): ?>selected<?php endif ?>>Student</option>
			<option <?php if( $form->role == 'Instructor' ): ?>selected<?php endif ?>>Instructor</option>
			</select>
		</div>

		<!--	If registration throws an alert back, display it. -->
		<?php 
			if ( isset($_SESSION[ 'error' ] ) ) {

			$error = $_SESSION[ 'error' ];

			echo '<div class="alert alert-danger" role="alert">';
			echo "<strong>$error</strong>";
			echo '</div>';

			$_SESSION['error'] = NULL;
			}
		?>
		
		<button type="submit" name="register-submit" class="btn btn-success btn-block float-right col-lg-4">Register</button>
		</form>
		<div style="clear: both;">
		</div>
		<?php include_once( 'footer.php' ); ?>
		</div>
		<script>
			$(() => {
				setTimeout( () => { $( '#email' ).focus(); }, 100 );
			});
		</script>
	</body>
</html>
