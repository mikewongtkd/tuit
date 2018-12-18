<?php
include_once "../session.php";

// Kick user back to the home page if they aren't currently logged in
if( ! isset( $_SESSION[ 'is_auth' ])) {
	header("location: ../index.php");
	exit;
}

// Handle logout request
$goodbye = [ 'Goodbye', 'See you later,', 'Bye bye,', 'Have a good day', 'Farewell' ];
$i       = rand( 0, 4 );
$_SESSION[ 'message' ] = $goodbye[ $i ] . ' ' . $_SESSION[ 'fname' ];

unset( $_SESSION[ 'is_auth' ]);
unset( $_SESSION[ 'email' ]);
unset( $_SESSION[ 'fname' ]);
unset( $_SESSION[ 'lname' ]);
unset( $_SESSION[ 'user' ]);


header( 'location: ../index.php' );
exit;

?>
