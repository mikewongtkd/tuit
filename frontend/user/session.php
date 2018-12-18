<?php
	define( 'USER_DB', '/usr/local/tuit/web/data/users.sqlite' );
	define( 'SESSION_PATH', '/usr/local/tuit/web/data/sessions' );
	if (!file_exists( SESSION_PATH )) { echo "ERROR: Admin must create directory SESSION PATH to complete TUIT web app installation."; }

	session_name( 'tuit' );
	session_save_path( SESSION_PATH );
	session_start();

	$GLOBALS[ 'INACTIVITY_TIMEOUT' ] = 0;
	$inactivity_limit = 30 * 60; # 30 minutes
	if( isset( $_SESSION[ 'is_auth' ]) && isset( $_SESSION[ 'timeout' ]) && (time() - $_SESSION[ 'timeout' ] > $inactivity_limit)) {
		$GLOBALS[ 'INACTIVITY_TIMEOUT' ] = 1;
		session_unset();
		session_destroy();
	} else {
		$_SESSION[ 'timeout' ] = time(); # Reset the inactivity timer
	}
?>
