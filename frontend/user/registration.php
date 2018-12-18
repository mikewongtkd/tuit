<?php
include_once "../session.php";

// Error checking
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

function success( $msg ) {
	$_SESSION[ 'message' ] = $msg;
	header("Location: ../index.php");
	exit;
}

function fail( $msg ) {
	$_SESSION[ 'error' ] = $msg;
	unset( $_POST[ 'password' ]);
	unset( $_POST[ 'confirm' ]);
	$_SESSION[ 'form' ] = base64_encode( json_encode( $_POST ));
	// Go back in history
	header("Location: ../register.php");
	exit;
}

// Make sure user didn't reach this accidentally - or maliciously?
if ( ! isset($_POST['register-submit']) ) { return; }

// Some basic validation - we got a valid email and a password?
$incomplete    = !( isset( $_POST[ 'email' ] ) && isset( $_POST[ 'password' ] ) && isset( $_POST[ 'confirm' ]));
if ( $incomplete  )
	fail( 'Missing email or password. Try again.' );

$inconsistent  = $_POST[ 'password' ] != $_POST[ 'confirm' ];
if ( $inconsistent  )
	fail( 'Passwords must match. Try again.' );

$invalid_email = !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL );
if ( $invalid_email  )
	fail( 'You must provide a valid e-mail address. Try again.' );

// Make sure this email address isn't already registered
$email = $_POST['email'];

try{
	$pdo  = new PDO( 'sqlite:' . USER_DB ) or die( "Cannot connect to the database." );
	$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$stmt = $pdo->prepare( "SELECT email FROM users WHERE email = :email" );
	$stmt->bindValue( ':email', $email, PDO::PARAM_STR );
	$stmt->execute();
	$row = $stmt->fetch();

	// Ensure email address isn't already in use/registered
	$available_username = $row == false;
	if ( ! $available_username	)
		fail( "The provided email address is already registered." );

	// Create Account
	$stmt = $pdo->prepare("INSERT INTO users (email, password, firstname, lastname, organization, title, role, usage) VALUES (:email, :password, :firstname, :lastname, :organization, :title, :role, :usage)");
	$hash = hash( "sha256", $_POST[ 'password' ]);

	$stmt->bindValue(':email',        $_POST[ 'email' ],        PDO::PARAM_STR);
	$stmt->bindValue(':password',     $hash,                    PDO::PARAM_STR);
	$stmt->bindValue(':firstname',    $_POST[ 'firstname' ],    PDO::PARAM_STR);
	$stmt->bindValue(':lastname',     $_POST[ 'lastname' ],     PDO::PARAM_STR);
	$stmt->bindValue(':organization', $_POST[ 'organization' ], PDO::PARAM_STR);
	$stmt->bindValue(':title',        $_POST[ 'title' ],        PDO::PARAM_STR);
	$stmt->bindValue(':role',         $_POST[ 'role' ],         PDO::PARAM_STR);
	$stmt->bindValue(':usage',        $_POST[ 'usage' ],        PDO::PARAM_STR);

	$result = $stmt->execute();
	if( $result){
		$_SESSION[ 'is_auth' ] = true;
		$_SESSION[ 'email' ]   = $_POST[ 'email' ];
		$_SESSION[ 'fname' ]   = $_POST[ 'firstname' ];
		$_SESSION[ 'lname' ]   = $_POST[ 'lastname' ];
		success( "Registration successful. Welcome " . $_SESSION[ 'fname' ] . "!" );

	}
	else
	{
		fail( 'Registration unsuccessful. Unable to save information to database.' );
	}

} catch (Exception $e) {
	$message = $e->getMessage();
	if( preg_match( '/unique constraint failed: users\.email/i', $message )) {
		fail( 'The provided email address is already registered.' );
	} else {
		fail( 'Registration unsuccessful. ' . $e->getMessage());
	}
}

?>
