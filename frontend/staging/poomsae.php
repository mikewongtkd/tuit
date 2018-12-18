<?php
	$i = $_GET[ 'ring' ];
?>
<html>
	<head>
		<link href="../include/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<link href="../css/poomsae.css" rel="stylesheet" />
		<link href="../include/alertify/css/alertify.min.css" rel="stylesheet" />
		<link href="../include/alertify/css/themes/bootstrap.min.css" rel="stylesheet" />
		<link href="../include/page-transitions/css/animations.css" rel="stylesheet" type="text/css" />
		<link href="../include/fontawesome/css/fontawesome.min.css" rel="stylesheet" />
		<script src="../include/jquery/js/jquery.min.js"></script>
		<script src="../include/bootstrap/js/bootstrap.min.js"></script>
		<script src="../include/alertify/js/alertify.min.js"></script>
		<title>World Class Ring <?= $i ?> Operations</title>
	</head>
	<body>
		<div id="pt-main" class="pt-perspective">
			<!-- ============================================================ -->
			<!-- RING DIVISIONS -->
			<!-- ============================================================ -->
			<div class="pt-page pt-page-1">
				<div class="container">
					<div class="page-header"><span id="ring-header">Ring <?= $i ?> Operations</span></div>
				</div>
			</div>
			<!-- ============================================================ -->
			<!-- DIVISION ATHLETES -->
			<!-- ============================================================ -->
			<div class="pt-page pt-page-2">
				<div class="container">
				<div class="page-header">
					<a id="back-to-divisions" class="btn btn-warning"><span class="glyphicon glyphicon-menu-left"></span> Ring <?= $i ?></a> <span id="division-header"></span>
				</div>
			</div>
		</div>
		<script src="../include/page-transitions/js/pagetransitions.js"></script>
		<script>
			alertify.defaults.theme.ok     = "btn btn-danger";
			alertify.defaults.theme.cancel = "btn btn-warning";

/*
			var ws         = new WebSocket( 'ws://<?= $host ?>:3088/worldclass/' + tournament.db + '/' + ring.num );
			var network    = { reconnect: 0 }

			ws.onerror = network.error = function() {
				setTimeout( function() { location.reload(); }, 15000 ); // Attempt to reconnect every 15 seconds
			};

			ws.onopen = network.connect = function() {
				var request;
				request      = { data : { type : 'ring', action : 'read' }};
				request.json = JSON.stringify( request.data );
				ws.send( request.json );

			};

			ws.onmessage = network.message = function( response ) {
				var update = JSON.parse( response.data );
				console.log( update );


			// ===== TRY TO RECONNECT IF WEBSOCKET CLOSES
			ws.onclose = network.close = function() {
				if( network.reconnect < 10 ) { // Give 10 attempts to reconnect
					if( network.reconnect == 0 ) { alertify.error( 'Network error. Trying to reconnect.' ); }
					network.reconnect++;
					ws = new WebSocket( 'ws://' + host + ':3088/worldclass/' + tournament.db + '/' + ring.num ); 
					
					ws.onerror   = network.error;
					ws.onmessage = network.message;
					ws.onclose   = network.close;
				}
			};

			var sound = {
				ok    : new Howl({ urls: [ "../../sounds/upload.mp3",   "../../sounds/upload.ogg" ]}),
				error : new Howl({ urls: [ "../../sounds/quack.mp3",    "../../sounds/quack.ogg"  ]}),
				next  : new Howl({ urls: [ "../../sounds/next.mp3",     "../../sounds/next.ogg"   ]}),
				prev  : new Howl({ urls: [ "../../sounds/prev.mp3",     "../../sounds/prev.ogg"   ]}),
			};
*/

			var page = {
				num : 1,
				transition: ( ev ) => { page.num = PageTransitions.nextPage({ animation: page.animation( page.num )}); },
				animation:  ( pn ) => { return pn; } // Left-right movement is animation #1 and #2 coinciding with page numbers
			};

			var sendRequest = ( request ) => {
				request.json = JSON.stringify( request.data );
				ws.send( request.json );
			};
		</script>
	</body>
</html>
