<?php
//	ini_set('display_errors', 1);
//	ini_set('display_startup_errors', 1);
//	error_reporting(E_ALL);

/*
 * NOTE: If you are using GMail's SMTP servers, you will need to enable the
 * GMail option for "Allow Less Secure Apps"
 *
 * See the PHPMailer Troubleshooting Guide below for more details:
 *
 * https://github.com/PHPMailer/PHPMailer/wiki/Troubleshooting#user-content-read-the-smtp-transcript
 */

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require '../include/PHPMailer/Exception.php';
	require '../include/PHPMailer/PHPMailer.php';
	require '../include/PHPMailer/SMTP.php';

	include_once "../session.php";
	include_once "../config.php";

	// Some basic validation - we got a valid email?
	$incomplete    = !( isset( $_POST[ 'email' ] ) );
	$invalid_email = !filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL );

	if ( $incomplete ) {
		$_SESSION[ 'error' ] = "Please enter an email address."; 
		header( 'Location: forgotpass.php' );
		exit;
	}

	if( $invalid_email )	{
		$_SESSION[ 'error' ] = "Invalid Email Address."; 
		header( 'Location: forgotpass.php' );
		exit;
	}

try{
	$pdo = new PDO( 'sqlite:/usr/local/pathfx/web/data/users.sqlite' ) or die( 'Cannot connect to the database.' );

	$email = $_POST['email'];
	
	// First check if the user exists
	$stmt = $pdo->prepare( "SELECT email FROM users WHERE email = :email" );
	$stmt->bindValue( ':email', $email, PDO::PARAM_STR );
	$stmt->execute();
	$row = $stmt->fetch();

	// User doesn't exist
	if ( $row == false ) {
		$_SESSION['reset'] = true;
		header("Location: forgotpass.php");
	}

	$token  = hash( "sha256", $email . strval(time()) . $config->salt );
	$expiry = time() + (60 * 30);
	
	$stmt = $pdo->prepare( "UPDATE users SET reset_token = :token, reset_expiry = :expiry WHERE email = :email" );
	$stmt->bindValue( ':token',  $token,  PDO::PARAM_STR );
	$stmt->bindValue( ':expiry', $expiry, PDO::PARAM_INT );
	$stmt->bindValue( ':email',  $email,  PDO::PARAM_STR );
	$stmt->execute();

	// Build message
	$resetlink = "<a href=\"" . $config->base_url . "/resetpassword/setnewpassword.php?email=$email&token=$token\">" . $config->base_url . "/resetpassword/setnewpassword.php?email=$email&token=$token</a>";
	$message = "<p>Click the link below to reset your PathFX password</p><p>$resetlink</p><p>This link expires in 30 minutes.</p><p>&nbsp;</p><p>- PathFX Team</p>";

	$mail             = new PHPMailer();
	$mail->IsSMTP();
	$mail->SMTPDebug  = 0;
	$mail->CharSet    = "UTF-8";
	$mail->SMTPAuth   = true;
	$mail->SMTPSecure = "tls";
	$mail->Host       = "smtp.gmail.com";
	$mail->Port       = 587;
	$mail->Username   = $config->smtp->username; 
	$mail->Password   = $config->smtp->password;

	$mail->SetFrom( $config->smtp->username, 'PathFX' );
	$mail->Subject    = "PathFX Reset Password";
	$mail->MsgHTML( $message );

	$mail->AddAddress( $email );

	if( ! $mail->Send()) {
		$_SESSION[ 'reset' ] = false;
		$_SESSION[ 'error' ] = "Username: " . $config->smtp->username . "<br>Password: " . $config->smtp->password . "<br>Mailer error: " . $mail->ErrorInfo;
		header( 'Location: forgotpass.php' );
		exit();
	}

	$_SESSION[ 'reset' ]   = true;
	$_SESSION[ 'message' ] = "A e-mail has been sent to <b>$email</b> with a link to reset your password.";

} catch( phpmailerException $e ) {
	$_SESSION[ 'reset' ]   = false;
	$_SESSION[ 'error' ]   = "Error resetting password: ". $e->errorMessage();

} catch( Exception $e ) {
	$_SESSION[ 'reset' ]   = false;
	$_SESSION[ 'error' ]   = "Error resetting password: ". $e->getMessage();
}
header( 'Location: ../index.php' );

?>
