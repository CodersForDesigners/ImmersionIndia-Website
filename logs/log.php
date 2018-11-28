<?php

// get the log which has been requested
$what = $_GET[ 'what' ] ?? false;

if ( ! $what ) {
	die( "Please provide a log to log." );
}

// build the path to the log file
$log_file = $_SERVER[ 'DOCUMENT_ROOT' ] . '/logs/' . $what;

$response = '[ ' . file_get_contents( $log_file ) . ' {} ]';

header( 'Content-Type: application/json' );
die( $response );
