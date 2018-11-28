<?php

ini_set( "display_errors", "On" );
ini_set( "error_reporting", E_ALL );

/*
 * Imports the output / contents of a PHP script
 * in a way such that it can be assigned to variable
 */
function require_to_var ( $__file__, $ctx ) {
	extract( $ctx );
	ob_start();
	require $__file__;
	return ob_get_clean();
}

/*
 * Logs data to a file
 */
function logThis ( $file, $context, $data ) {

	$log = [
		'timestamp' => date( 'Y-m-d H:i:s' ),
		'context' => $context,
		'data' => $data
	];
	$file_path = $_SERVER[ 'DOCUMENT_ROOT' ] . '/logs/' . $file;
	$log_line = json_encode( $log ) . ',' . PHP_EOL;
	try {
		file_put_contents( $file_path, $log_line, FILE_APPEND );
	} catch ( Exception $e ) {}

}

/*
 * In the scenario that the mailing functionality stops working,
 * this function delegates the mailing to our Lazaro server
 *
 * NOTE: This is a workaround and should not be considered a permanent solution.
 */
function delegateMail ( $envelope ) {

	$request = curl_init( "http://lazaro.in/server/proxy_mailer.php" );

	curl_setopt( $request, CURLOPT_CUSTOMREQUEST, "POST" );
	curl_setopt( $request, CURLOPT_POSTFIELDS, http_build_query( $envelope ) );
	curl_setopt( $request, CURLOPT_RETURNTRANSFER, true);
	curl_setopt( $request, CURLOPT_HEADER, "Content-Type: application/x-www-form-urlencoded");

	$response = curl_exec( $request );

	$error = [
		'number' => curl_errno( $request ),
		'message' => curl_error( $request )
	];

	curl_close( $request );

	if ( $error[ 'number' ] != 0 ) {
		logThis( 'mailer', 'Tour Packages', $error );
	}

	return $response;

}







// Pulling data from the request
$form_data = [
	'name' => $_POST[ 'name' ],
	'email_address' => $_POST[ 'email_address' ],
	'phone_number' => $_POST[ 'phone_number' ],
	'institute' => $_POST[ 'institute' ] ?? null,
	'package' => $_POST[ 'package' ] ?? null,
	'tour_date' => $_POST[ 'tour_date' ] ?? null
];

/* -----
 *
 * Data Validation, Sanitization and Organisation
 *
 ----- */
$user_data = $form_data;

$name = preg_replace( '/\s+/', ' ', trim( $user_data[ 'name' ] ) );
$names = preg_split( '/\s/', $name );
$user_data[ 'last_name' ] = array_pop( $names );
$user_data[ 'first_name' ] = implode( ' ', $names );
if ( empty( $user_data[ 'first_name' ] ) ) {
	$user_data[ 'first_name' ] = $user_data[ 'last_name' ];
	$user_data[ 'last_name' ] = 'nosurname';
}





/* -----
 *
 * Log data to a file ( just in case )
 *
 ----- */
// if the data was not submitted by one of us, then log
// if ( strpos( $email, "@lazaro.in" ) === false ) {
logThis( 'bookings', 'form handler', $form_data );
// }





/*
 * -----
 * Ping
 * 	The enquirer
 * via e-mail
 * -----
 */
/* -----
  Tour Booking Form
 ----- */
$mail_data = array_merge( $user_data, [ 'HOST' => 'http://immersionindia.com' ] );

$subject = 'ImmersionIndia';
if ( ! empty( $form_data[ 'package' ] ) ) {
	$message = require_to_var( 'templates/tour_packages.php', $mail_data );
} else {
	$message = require_to_var( 'templates/tour_interest.php', $mail_data );
}

$envelope = [
	'u' => 'tours@immersionindia.com',
	'p' => 'Godisgood@2017',
	'from_email' => 'tours@immersionindia.com',
	'from_name' => 'ImmersionIndia',
	'to_email' => $user_data[ 'email_address' ],
	'subject' => $subject,
	'message' => $message
];
require 'postman.php';

/*
 * In the scenario that the mailing functionality stops working,
 * use the function.
 * NOTE: This is a workaround and should not be considered a permanent solution.
 */
// delegateMail( $envelope );






/*
 * -----
 * Ping
 * 	The Immersion folks
 * on Insightly
 * -----
 */

// Prepping the data
$insightly_data = [
	'FIRST_NAME' => $user_data[ 'first_name' ],
	'LAST_NAME' => $user_data[ 'last_name' ],
	'PHONE_NUMBER' => $user_data[ 'phone_number' ],
	'EMAIL_ADDRESS' => $user_data[ 'email_address' ]
];
if ( ! empty( $user_data[ 'institute' ] ) ) {
	$insightly_data[ 'ORGANIZATION_NAME' ] = $user_data[ 'institute' ];
}
if ( ! empty( $user_data[ 'package' ] ) ) {
	$insightly_data[ 'LEAD_DESCRIPTION' ] = 'Package: ' . $user_data[ 'package' ] . '\n\n';
}
if ( ! empty( $user_data[ 'tour_date' ] ) ) {
	$insightly_data[ 'LEAD_DESCRIPTION' ] .= 'Date: ' . $user_data[ 'tour_date' ];
}

$request = curl_init( 'https://api.insight.ly/v2.2/Leads' );
curl_setopt( $request, CURLOPT_HTTPHEADER, [
	'cache-control: no-cache',
	'content-type: application/json',
	'authorization: Basic ' . base64_encode( 'c1ad2c48-d17a-4d29-a91c-096d89c9febc' )
] );
curl_setopt( $request, CURLOPT_MAXREDIRS, 9 );
curl_setopt( $request, CURLOPT_HEADER, 0 );
curl_setopt( $request, CURLOPT_CUSTOMREQUEST, 'POST' );
curl_setopt( $request, CURLOPT_POSTFIELDS, json_encode( $insightly_data ) );
curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
$response = curl_exec( $request );
$error = [
	'number' => curl_errno( $request ),
	'message' => curl_error( $request )
];
curl_close( $request );

if ( $error[ 'number' ] != 0 ) {
	logThis( 'insightly', 'Insightly API', $error );
}





// response from sending the e-mail
return $response;
