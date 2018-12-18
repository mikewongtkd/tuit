<?php
	include "../session.php";
	define( 'FORGOT_PASS_LINK', '<a href="resetpassword/forgotpass.php">Forgot your password?</a>');

	// User shouldn't be here ... ?
	if ( ! isset($_POST[ 'login-submit' ])) { return; }

	// Did we get an email and password?
	$incomplete = !( isset( $_POST[ 'email' ]) && isset( $_POST[ 'password' ] ));
	if ( $incomplete )
		loginError( "Please enter an email and password to login." );

	$email    = $_POST[ 'email' ];
	$password = hash( 'sha256', $_POST[ 'password' ] );

	// Load User
	$pdo  = new PDO( 'sqlite:' . USER_DB );
	$stmt = $pdo->prepare( "SELECT email, firstname, lastname, password FROM users WHERE email = :email" );

	if($stmt === false)
		loginError( "Error looking up username in users database." );

	$stmt->bindValue( ':email', $email, PDO::PARAM_STR );
	$stmt->execute();
	$row = $stmt->fetch();

	// Verify Credentials
	$unknown_user = $row == false;
	$bad_password = $row[ 'password' ] !== $password;
	if ( $unknown_user || $bad_password )
		loginError( "Invalid email or password. Please try again.<br>" . FORGOT_PASS_LINK );

	$fname = $row[ 'firstname' ];

	loginSuccess( $row, 'Welcome ' . $fname );

	function loginError( $error = "Error" ) {
		$_SESSION[ 'error' ] = $error;
		if( isset( $_POST[ 'download' ])) { header( "Location: ../jobs.php?download=" . $_POST[ 'download' ]); } 
		else { header( "Location: ../index.php" ); } 
		exit;
	}

	function loginSuccess( $row = [], $message = "Success" ) {
		include "../config.php";
		$_SESSION[ 'is_auth' ] = true;
		$_SESSION[ 'email' ]   = $row[ 'email' ];
		$_SESSION[ 'fname' ]   = $row[ 'firstname' ];
		$_SESSION[ 'lname' ]   = $row[ 'lastname' ];
		$_SESSION[ 'user' ]    = substr( sha1( $row[ 'email' ] . $config->salt ), 0, 8 );
		$_SESSION[ 'message' ] = $message;
		$_SESSION[ 'timeout' ] = time();

		// If the user is using an e-mailed link to download a file, redirect them to download
		$download_request = isset( $_POST[ 'download' ]) && strlen( $_POST[ 'download' ]) > 0;
		if( $download_request ) { header( "Location: ../jobs.php?download=" . $_POST[ 'download' ]); } 

		// Otherwise direct the user to the home page
		else { header( "Location: ../index.php" ); } 

		exit;
	}
?>
