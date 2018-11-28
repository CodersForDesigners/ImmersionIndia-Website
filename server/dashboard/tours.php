<?php

// prevent page from being crawled by search engines
header( 'X-Robots-Tag: none', true );
// prevent caching
header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
header( 'Cache-Control: post-check=0, pre-check=0', false );
header( 'Pragma: no-cache' );

date_default_timezone_set( 'Asia/Kolkata' );

// ini_set( "display_errors", "On" );
// ini_set( "error_reporting", E_ALL );

require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/vendor/autoload.php' );





// continue processing this script even if
// the user closes the tab, or
// hits the ESC key
ignore_user_abort( true );

// do not let this script timeout
// ( because we know it's gonna take a while what with all the image compressing )
set_time_limit( 0 );

// scaffold the response object
$response = [ ];

/*
 * Query the databases
 */
// Tours
$db_file_name = $_SERVER[ 'DOCUMENT_ROOT' ] . '/database/tour_database.json';
$db = json_decode( file_get_contents( $db_file_name ), true );
// TinyPNG API Keys
$tiny_png_key_db = $_SERVER[ 'DOCUMENT_ROOT' ] . '/database/tinyPNG_API_keys.json';
$tiny_png_api_keys = json_decode( file_get_contents( $tiny_png_key_db ), true );

/*
 * Places where static assets are stored
 */
// Images
$directory_temporary = $_SERVER[ 'DOCUMENT_ROOT' ] . '/uploads/tmp/';
$directory_processed = $_SERVER[ 'DOCUMENT_ROOT' ] . '/uploads/';
// Log file
$log_directory = $_SERVER[ 'DOCUMENT_ROOT' ] . '/logs/';


// logs data to a file
function logThis ( $file, $context, $data ) {

	$log = [
		'timestamp' => date( 'Y-m-d H:i:s' ),
		'context' => $context,
		'data' => $data
	];
	$file_path = $GLOBALS[ 'log_directory' ] . $file;
	$log_line = json_encode( $log ) . ',' . PHP_EOL;
	try {
		file_put_contents( $file_path, $log_line, FILE_APPEND );
	} catch ( Exception $e ) {}

}

// returns a string-formatted micro timestamp
function getTimeStamp () {
	return preg_replace( '/.\.(\d+)\s*(\d+)/' , '${2}.${1}', microtime() );
}

// get a better structured $_FILES array
function getRestructuredObject ( $arr ) {

	if ( empty( $arr ) ) {
		return [ ];
	}

	$keys = array_keys( $arr );
	$values = array_values( $arr );

	$intermediate_object = array_map( null, ...$values );

	$restructured_object = array_map( function ( $el ) use ( $keys ) {
		return array_combine( $keys, $el );
	}, $intermediate_object );

	return $restructured_object;

}

// return a timestamp-suffixed version of a filename
function getFormattedFilename ( $file_path ) {

	$file_path_info = pathinfo( $file_path );
	$basename = $file_path_info[ 'basename' ];
	$formatted_name =
		$file_path_info[ 'filename' ]
		. '__'
		. getTimeStamp()
		. '.'
		. $file_path_info[ 'extension' ];
	// $target_file = $directory . $formatted_name;
	return $formatted_name;

}

// takes an array of files
// returns an object with key-value pairs of the kind
// old filename => new filename
function getFormattedFilenames ( $files ) {

	if ( empty( $files ) ) {
		return [ ];
	}

	$file_names = [ ];
	foreach ( $files as $file ) {
		$current_name = $file[ 'name' ];
		$file_names[ $current_name ] = getFormattedFilename( $current_name );
	}

	return $file_names;

}

function moveAndRenameFiles ( $files, $file_name_map, $directory ) {

	if ( empty( $files ) ) {
		return;
	}

	foreach ( $files as $file ) {
		$destination_file_path = $directory . $file_name_map[ $file[ 'name' ] ];
		move_uploaded_file( $file[ 'tmp_name' ], $destination_file_path );
	}

}

function validateTinyPNG_APIKey () {
	try {
		\Tinify\validate();
	} catch ( Exception $e ) {
		$message = $e->getMessage();
		logThis( 'tours', 'Attempting to validate TinyPNG\'s API key', [ 'type' => 'error', 'message' => $message ] );
		return false;
	}
	return true;
}

function resizeAndCompressImage ( $file_name, $source_dir, $target_dir ) {

	$inputFile = $source_dir . $file_name;
	$inputFileBasename = pathinfo( $inputFile )[ 'filename' ];

	/*
	 * JPEG / PNG
	 */
	try {
		$processor = \Tinify\fromFile( $source_dir . $file_name );
		$resizer = $processor->resize( [
			'method' => 'scale',
			'width' => 1400,
		] );
		$resizer->toFile( $target_dir . $file_name );
		$resizer = $processor->resize( [
			'method' => 'scale',
			'width' => 480,
		] );
		$resizer->toFile( $target_dir . 'thumbnails/' . $file_name );
	} catch ( Exception $e ) {
		$message = $e->getMessage();
		logThis( 'tours', 'Attempting to process the file using TinyPNG', [ 'type' => 'error', 'file' => $file_name, 'message' => $message ] );
		return false;
	}

	/*
	 * WebP
	 */
	$pre_cmd = 'PATH=$PATH:/usr/local/bin ';
	$core_cmd = 'cwebp -q 75 -m 5 -resize ';
	$cmd_suffix = ' && printf "\nY"';

	// Large ( 1400w )
	$outputFile = $target_dir . $inputFileBasename . '.webp';
	$cmd = $pre_cmd
		. $core_cmd . '1400 0 "' . $inputFile . '" -o "' . $outputFile . '"'
		. $cmd_suffix;
	$r = exec( $cmd );
	if ( $r != 'Y' ) {
		logThis( 'packages', 'Attempting to process the file using cwebp', [ 'type' => 'error', 'file' => $inputFileBasename ] );
	}

	// Thumbnail ( 480w )
	$outputFile = $target_dir . 'thumbnails/' . $inputFileBasename . '.webp';
	$cmd = $pre_cmd
		. $core_cmd . '480 0 "' . $inputFile . '" -o "' . $outputFile . '"'
		. $cmd_suffix;
	$r = exec( $cmd );
	if ( $r != 'Y' ) {
		logThis( 'packages', 'Attempting to process the file using cwebp', [ 'type' => 'error', 'file' => $inputFileBasename ] );
	}

	return true;

}

// processes images
// 	resizes and compresses them using tinyPNG
// returns an array of processed image file names,
// 	responds to the client with an error
function processImages ( $source_directory, $file_names, $target_directory ) {

	if ( empty( $file_names ) ) {
		return;
	}

	$tiny_png_api_keys = $GLOBALS[ 'tiny_png_api_keys' ];
	$current_api_key_index = 0;
	\Tinify\setKey( $tiny_png_api_keys[ $current_api_key_index ][ 'key' ] );

	foreach ( $file_names as $name ) {
		$image_processed = resizeAndCompressImage( $name, $source_directory, $target_directory );
		while ( ! $image_processed ) {
			$current_api_key_index += 1;
			if ( $current_api_key_index >= count( $tiny_png_api_keys ) ) {
				break;
			}
			\Tinify\setKey( $tiny_png_api_keys[ $current_api_key_index ][ 'key' ] );
			$image_processed = resizeAndCompressImage( $name, $source_directory, $target_directory );
		}
	}

}





/* -----
 Adding a new tour
 ----- */
if ( $_POST[ 'action' ] == 'create' ) {

	// pull out the data object from the POST request
		// into a "regular" ( not `stdClass` ) object
			// hence the `true` argument
	$data = json_decode( $_POST[ 'data' ], true );

	// get the index to which the new entry will be added to / updated on
	// #forlater
	$index = count( $db );

	// get a restructured $_FILES array
	$files = getRestructuredObject( $_FILES[ 'gallery' ] ?? [ ] );

	// get an object with key-value pairs like so,
	// input filename => actual formatted filename
	// for ex.
	// mysore_palace.png => mysore_palace__1507025893.62233800.png
	$file_name_map = getFormattedFilenames( $files );

	// reflect the actual file-names in the database
	foreach ( $data[ 'days' ] as &$day ) {
		foreach ( $day[ 'gallery' ] as &$image ) {
			$source = $image[ 'image' ];
			$image[ 'image' ] = $file_name_map[ $source ] ?? $source;
		}
		unset( $image );
	}
	unset( $day );

	// set this entry's visibility to `false` ( as a precaution )
	// $data[ 'visible' ] = false;
	// mark this entry's state as `processing`
	$data[ 'processing' ] = true;

	// append the newly entered data
	$db[ ] = $data;

	// persist the data back to the database
	file_put_contents( $db_file_name, json_encode( $db ) );

	// add the newly entered data to the response
	$response[ 'data' ] = end( $db );
	$response[ 'message' ] = 'success';

	// actually make the response now
	ob_start();
	echo json_encode( $response );
	header( 'Content-Encoding: none' );
	header( 'Connection: close' );
	header( 'Content-Length: ' . ob_get_length() );

	// close off the connection to the client
	// fastcgi_finish_request();
	ob_end_flush();
	ob_flush();
	flush();


	/* Now on with the tedious work! */
	// but first, we'll take a little power nap
	// sleep( 9 );


	// pull in the image files ( if any were sent along )
	moveAndRenameFiles( $files, $file_name_map, $directory_temporary );

	// compress the images
	processImages( $directory_temporary, $file_name_map, $directory_processed );

	// delete the originally uploaded files
	foreach ( $file_name_map as $name ) {
		@unlink( $directory_temporary . $name );
		@unlink( $directory_temporary . pathinfo( $name )[ 'filename' ] . '.webp' );
	}


	// remove this entry from its `processing` state
	$data[ 'processing' ] = false;

	// persist the data back to the database
	// again read the database ( in case it has been updated in the meanwhile )
	$db = json_decode( file_get_contents( $db_file_name ), true );
	// modify the newly entered entry
	$db[ $index ] = $data;
	file_put_contents( $db_file_name, json_encode( $db ) );


/* -----
 Updating an existing tour
 ----- */
} else if ( $_POST[ 'action' ] == 'update' ) {

	// pull out the meta and data objects from the POST request
		// into "regular" ( not `stdClass` ) objects
			// hence the `true` argument
	$meta = json_decode( $_POST[ 'meta' ], true );
	$data = json_decode( $_POST[ 'data' ], true );

	$index = $meta[ 'index' ];

	// get a restructured $_FILES array
	$files = getRestructuredObject( $_FILES[ 'gallery' ] ?? [ ] );

	// get an object with key-value pairs like so,
	// input filename => actual formatted filename
	// for ex.
	// mysore_palace.png => mysore_palace__1507025893.62233800.png
	$file_name_map = getFormattedFilenames( $files );

	// reflect the actual file-names in the database
	foreach ( $data[ 'days' ] as &$day ) {
		foreach ( $day[ 'gallery' ] as &$image ) {
			$source = $image[ 'image' ];
			$image[ 'image' ] = $file_name_map[ $source ] ?? $source;
		}
		unset( $image );
	}
	unset( $day );

	// get a diff between the files
	// that were associated with the previous version of this entry,
	// that of the one being ingested
	$images_from_db = array_column(
		call_user_func_array( 'array_merge', array_column( $db[ $index ][ 'days' ], 'gallery' ) ),
		'image'
	);
	$images_from_request = array_column(
		call_user_func_array( 'array_merge', array_column( $data[ 'days' ], 'gallery' ) ),
		'image'
	);
	$diff = array_diff( $images_from_db, $images_from_request );


	// set this entry's visibility to `false` ( as a precaution )
	// $data[ 'visible' ] = false;
	// mark this entry's state as `processing`
	$data[ 'processing' ] = true;

	// update the existing entry
	$db[ $index ] = $data;

	// persist the data back to the database
	file_put_contents( $db_file_name, json_encode( $db ) );

	// add the updated entry to the response
	$response[ 'data' ] = $db[ $index ];
	$response[ 'message' ] = 'success';

	// actually make the response now
	ob_start();
	echo json_encode( $response );
	header( 'Content-Encoding: none' );
	header( 'Connection: close' );
	header( 'Content-Length: ' . ob_get_length() );

	// close off the connection to the client
	// fastcgi_finish_request();
	ob_end_flush();
	ob_flush();
	flush();


	/* Now on with the tedious work! */
	// but first, we'll take a little power nap
	// sleep( 9 );

	// pull in the image files ( if any were sent along )
	moveAndRenameFiles( $files, $file_name_map, $directory_temporary );

	// compress the images
	processImages( $directory_temporary, $file_name_map, $directory_processed );

	// delete the originally uploaded files
	foreach ( $file_name_map as $name ) {
		@unlink( $directory_temporary . $name );
		@unlink( $directory_temporary . pathinfo( $name )[ 'filename' ] . '.webp' );
	}

	// delete the files that belong to the diff that we computed earlier
	foreach ( $diff as $name ) {
		@unlink( $directory_processed . $name );
		@unlink( $directory_processed . 'thumbnails/' . $name );
		@unlink( $directory_processed . pathinfo( $name )[ 'filename' ] . '.webp' );
		@unlink( $directory_processed . 'thumbnails/' . pathinfo( $name )[ 'filename' ] . '.webp' );
	}


	// remove this entry from its `processing` state
	$data[ 'processing' ] = false;

	$db[ $index ] = $data;

	// persist the data back to the database
	// again read the database ( in case it has been updated in the meanwhile )
	$db = json_decode( file_get_contents( $db_file_name ), true );
	// update the existing entry
	$db[ $index ] = $data;
	file_put_contents( $db_file_name, json_encode( $db ) );


/* -----
 Removing an existing tour
 ----- */
} else if ( $_POST[ 'action' ] == 'remove' ) {

	// get the index of the entry to be removed
	$index = $_POST[ 'meta' ][ 'index' ];

	// delete the files associated with the tour
	$files = array_column( call_user_func_array( 'array_merge', array_column( $db[ $index ][ 'days' ], 'gallery' ) ), 'image' );

	foreach ( $files as $name ) {
		@unlink( $directory_processed . $name );
		@unlink( $directory_processed . 'thumbnails/' . $name );
		@unlink( $directory_processed . pathinfo( $name )[ 'filename' ] . '.webp' );
		@unlink( $directory_processed . 'thumbnails/' . pathinfo( $name )[ 'filename' ] . '.webp' );
	}

	// add the removed entry to the response
	$response[ 'data' ] = $db[ $index ];

	// remove the existing entry
	array_splice( $db, $index, 1 );

	// persist the data back to the database
	file_put_contents( $db_file_name, json_encode( $db ) );

	// prepare the response
	$response[ 'message' ] = 'success';
	die( json_encode( $response ) );


/* -----
 Transpose two tours
 ----- */
} else if ( $_POST[ 'action' ] == 'transpose' ) {

	// get the index of the entries to be swapped
	$index1 = $_POST[ 'meta' ][ 'index1' ];
	$index2 = $_POST[ 'meta' ][ 'index2' ];

	// swap the entries
	$tmp = $db[ $index1 ];
	$db[ $index1 ] = $db[ $index2 ];
	$db[ $index2 ] = $tmp;

	// persist the data back to the database
	file_put_contents( $db_file_name, json_encode( $db ) );

	// prepare the response
	$response[ 'message' ] = 'success';
	die( json_encode( $response ) );

}
