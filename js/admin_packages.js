
$( function () {	// start of document ready handler





/* -----
 Show the filename after selecting a file for upload
 ----- */
// NOTE ::
// If after file selection dialog box is opened
// the user backs out of selecting one and hit "Cancel" ( or what have you )
// then the browser cancels out any file that was selected prior
// effectively removing the existing file
// or setting the file-input field to its default blank state
// at least this is the case on Chrome on MacOS
// anyways, the point being that it may not have been the user's intent
// to remove the file and that s/he accidently opened the file dialog box
$( document ).on( "change", "input[ type=file ]", function ( event ) {
	var domFileInput = event.target;
	var filename;
	if ( ! domFileInput.files.length ) {
		fileName = "";
	}
	else {
		fileName = domFileInput.files[ 0 ].name;
	}
	$( domFileInput ).data( "path", fileName );
	// $( domFileInput ).closest( ".js_package_place" ).find( ".js_file_name" ).text( fileName );
	$( domFileInput ).parent().parent().find( ".js_file_name" ).text( fileName );
} );



var PACKAGES = window.__DATA__.PACKAGES;

/* ~~~~~
 ----- PACKAGES
 ~~~~~ */
/* -----
 Add a new package
 ----- */
$( ".js_package_add" ).on( "click", function ( event ) {

	toggleLoadingIndicator();

	var $form = $( event.target ).closest( ".js_package_form_add" );

	// pull data from the DOM and construct a data object
	var data = { };

	data.title = $form.find( "[ name = title ]" ).val();
	data.label = $form.find( "[ name = label ]" ).val();
	data.price = $form.find( "[ name = price ]" ).val();
	data.description = $form.find( "[ name = description ]" ).val();
	data.schedule = $form.find( "[ name = schedule ]" ).data( "path" ) || "";
	data.cover = $form.find( "[ name = cover ]" ).data( "path" ) || "";
	// We're re-appropriating the location field for the package cover image
	data.locations = [ {
		city: "Z",
		days: "1",
		image: $form.find( "[ name = cover ]" ).data( "path" ) || ""
	} ];
	// data.locations = Array.from( $form.find( ".js_package_place" ) ).map( domPlace => {
	// 	$place = $( domPlace );
	// 	return {
	// 		city: $place.find( "[ name = city ]" ).val(),
	// 		days: $place.find( "[ name = days ]" ).val(),
	// 		image: $place.find( "[ name = image ]" ).data( "path" ) || ""
	// 	};
	// } );

	// get place file objects from the file input fields
	var placeFileObjects = Array.from( $form.find( "[ name = image ]" ) )
		.filter( domFile => domFile.files.length )
		.map( domFile => domFile.files[ 0 ] )

	// build a data object that can store file data
	var formData = new FormData();
	formData.append( "schedule", $form.find( "[ name = schedule ]" ).get( 0 ).files[ 0 ] );
	// Add the cover image as a location
	formData.append( "locations[]", $form.find( "[ name = cover ]" ).get( 0 ).files[ 0 ] );
	placeFileObjects.forEach( file => formData.append( "locations[]", file ) );
	formData.append( "data", JSON.stringify( data ) );
	formData.append( "action", "create" );

	$.ajax( {
		url: "/server/dashboard/packages.php",
		method: "POST",
		accepts: "json",
		cache: false,
		data: formData,
		processData: false,
		contentType: false,
	} )
	.done( function ( responseJSON ) {
		var response;
		try {
			response = JSON.parse( responseJSON );
		}
		catch ( e ) {
			response = responseJSON;
		}
		console.log( response );
		if ( response.message == "success" ) {
			// alert( "A new package was added. Awesome!" );
			alert( "A new package is being created. Awesome!" );
			window.location.reload( true );
		}
		else {
			alert( "Something went wrong. Please try again later." );
		}
	} )
	.always( function () {
		toggleLoadingIndicator();
	} )

} );

/* -----
 Updating an existing package
 ----- */
$( ".js_package_update" ).on( "click", function ( event ) {

	toggleLoadingIndicator();

	var $form = $( event.target ).closest( ".js_package_form_update" );

	// pull data from the DOM and construct a data object
	var meta = {
		index: $form.data( "index" )
	};
	var data = { };

	data.title = $form.find( "[ name = title ]" ).val();
	data.label = $form.find( "[ name = label ]" ).val();
	data.price = $form.find( "[ name = price ]" ).val();
	data.description = $form.find( "[ name = description ]" ).val();
	data.schedule = $form.find( "[ name = schedule ]" ).data( "path" ) || "";
	data.locations = [ {
		city: "",
		days: "",
		image: $form.find( "[ name = cover ]" ).data( "path" ) || ""
	} ];
	// data.locations = Array.from( $form.find( ".js_package_place" ) ).map( domPlace => {
	// 	$place = $( domPlace );
	// 	return {
	// 		city: $place.find( "[ name = city ]" ).val(),
	// 		days: $place.find( "[ name = days ]" ).val(),
	// 		image: $place.find( "[ name = image ]" ).data( "path" ) || ""
	// 	};
	// } );

	// get the "place" file objects from the file input fields
	var placeFileObjects = Array.from( $form.find( "[ name = image ]" ) )
		.filter( domFile => domFile.files.length )
		.map( domFile => domFile.files[ 0 ] )

	// build a data object that can store file data
	var formData = new FormData();
	formData.append( "schedule", $form.find( "[ name = schedule ]" ).get( 0 ).files[ 0 ] );
	// Add the cover image as a location
	formData.append( "locations[]", $form.find( "[ name = cover ]" ).get( 0 ).files[ 0 ] );
	placeFileObjects.forEach( file => formData.append( "locations[]", file ) );
	formData.append( "meta", JSON.stringify( meta ) );
	formData.append( "data", JSON.stringify( data ) );
	formData.append( "action", "update" );

	$.ajax( {
		url: "/server/dashboard/packages.php",
		method: "POST",
		accepts: "json",
		cache: false,
		data: formData,
		processData: false,
		contentType: false,
	} )
	.done( function ( responseJSON ) {
		var response;
		try {
			response = JSON.parse( responseJSON );
		}
		catch ( e ) {
			response = responseJSON;
		}
		console.log( response );
		if ( response.message == "success" ) {
			alert( "The package is being updated." );
			// alert( "The package was updated." );
		}
		else {
			alert( "Something went wrong. Please try again later." );
		}
	} )
	.always( function () {
		toggleLoadingIndicator();
	} )

} );

/* -----
 Remove a package
 ----- */
$( ".js_package_remove" ).on( "click", function ( event ) {

	toggleLoadingIndicator();

	var $form = $( event.target ).closest( ".js_package_form_update" );

	var meta = {
		index: $form.data( "index" )
	};

	$.ajax( {
		url: "/server/dashboard/packages.php",
		method: "POST",
		data: { action: "remove", meta },
		accepts: "json",
		// dataType: "json",
	} )
	.done( function ( responseJSON ) {
		var response;
		try {
			response = JSON.parse( responseJSON );
		}
		catch ( e ) {
			response = responseJSON;
		}
		console.log( response )
		if ( response.message != "success" ) {
			alert( "Something went wrong. Please try again later." );
		}
		else {
			$form.remove();
			alert( "Removed the package." );
			window.location.reload( true );
		}
	} )
	.always( function () {
		toggleLoadingIndicator();
	} )

} );


/* -----
 Re-order packages
 ----- */
$( document ).on( "click", ".js_package_move_up", function ( event ) {

	toggleLoadingIndicator();

	var $package = $( event.target ).closest( ".js_package" );
	var index = $package.data( "index" );

	var meta = {
		index1: index,
		index2: index - 1
	};

	$.ajax( {
		url: "/server/dashboard/packages.php",
		method: "POST",
		data: { action: "transpose", meta },
		accepts: "json",
		// dataType: "json",
	} )
	.done( function ( responseJSON ) {
		var response;
		try {
			response = JSON.parse( responseJSON );
		}
		catch ( e ) {
			response = responseJSON;
		}
		console.log( response );
		if ( response.message != "success" ) {
			alert( "Something went wrong. Please try again later." );
		}
		else {
			$package.data( "index", meta.index2 );
			$package.find( ".js_package_name" ).text( meta.index2 );
			$package.prev().data( "index", index );
			$package.prev().find( ".js_package_name" ).text( index );
			moveSiblingDOM( $package, "up" );
		}
	} )
	.always( function () {
		toggleLoadingIndicator();
	} )

} );

$( document ).on( "click", ".js_package_move_down", function ( event ) {

	toggleLoadingIndicator();

	var $package = $( event.target ).closest( ".js_package" );
	var index = $package.data( "index" );

	var meta = {
		index1: index,
		index2: index + 1
	};

	$.ajax( {
		url: "/server/dashboard/packages.php",
		method: "POST",
		data: { action: "transpose", meta },
		accepts: "json",
		// dataType: "json",
	} )
	.done( function ( responseJSON ) {
		var response;
		try {
			response = JSON.parse( responseJSON );
		}
		catch ( e ) {
			response = responseJSON;
		}
		console.log( response );
		if ( response.message != "success" ) {
			alert( "Something went wrong. Please try again later." );
		}
		else {
			$package.data( "index", meta.index2 );
			$package.find( ".js_package_name" ).text( meta.index2 );
			$package.next().data( "index", index );
			$package.next().find( ".js_package_name" ).text( index );
			moveSiblingDOM( $package, "down" );
		}
	} )
	.always( function () {
		toggleLoadingIndicator();
	} )

} );




/* ~~~~~
 ----- PLACES
 ~~~~~ */
/* -----
 Add a place
 ----- */
$( document ).on( "click", ".js_package_place_add", function ( event ) {
	var $placesContainer = $( event.target )
		.closest( ".js_package" )
		.find( ".js_package_places" );
	$newPlace = $( $( ".tmpl_package_place" ).html() );
	$placesContainer.append( $newPlace );
} );

/* -----
 Remove a place
 ----- */
$( document ).on( "click", ".js_package_place_remove", function ( event ) {
	var $place = $( event.target ).closest( ".js_package_place" );
	$place.remove();
} );

/* -----
 Move a place
 ----- */
$( document ).on( "dragstart", ".js_package_place", function ( event ) {
	var $place = $( event.target ).closest( ".js_package_place" );
	$place.attr( "id", "js_current_draggee" );
} );
$( document ).on( "dragend", ".js_package_place", function ( event ) {
	$( event.target ).closest( ".js_package_places" )
		.find( ".indicate-placement" )
		.removeClass( "indicate-placement" )
	$( "#js_current_draggee" ).removeAttr( "id" );
} );
$( document ).on( "dragover dragenter", ".js_package_place", function ( event ) {
	event.preventDefault();
} );
$( document ).on( "dragenter", ".js_package_place", function ( event ) {

	var $place = $( event.target ).closest( ".js_package_place" );
	if ( $place.is( "#js_current_draggee" ) ) {
		return;
	}

	if ( ! $place.hasClass( "indicate-placement" ) ) {
		$( ".indicate-placement" ).removeClass( "indicate-placement" );
		$place.addClass( "indicate-placement" );
	}

} );
$( document ).on( "drop", ".js_package_place", function ( event ) {
	var $place = $( event.target ).closest( ".js_package_place" );
	var $placeToBeMoved = $( "#js_current_draggee" );
	var $fromPackage = $placeToBeMoved.closest( ".js_package_places" );
	var $toPackage = $place.closest( ".js_package_places" );
	$placeToBeMoved.removeAttr( "id" );
	if ( $fromPackage.find( ".js_package_place" ).index( $placeToBeMoved ) > $toPackage.find( ".js_package_place" ).index( $place ) ) {
		$placeToBeMoved.insertBefore( $place );
	}
	else {
		$placeToBeMoved.insertAfter( $place );
	}
} );
// $( document ).on( "click", ".js_package_place_move_up", function ( event ) {
// 	var $place = $( event.target ).closest( ".js_package_place" );
// 	moveSiblingDOM( $place, "up" );
// } );
// $( document ).on( "click", ".js_package_place_move_down", function ( event ) {
// 	var $place = $( event.target ).closest( ".js_package_place" );
// 	moveSiblingDOM( $place, "down" );
// } );









} );	// end of document ready handler

function toggleLoadingIndicator () {}

function moveSiblingDOM ( $el, direction ) {

	if ( direction == "up" ) {
		$prev = $el.prev();
		if ( ! $prev.length ) {
			return;
		}
		$el.detach().insertBefore( $prev );
	}
	else if ( direction == "down" ) {
		$next = $el.next();
		if ( ! $next.length ) {
			return;
		}
		$el.detach().insertAfter( $next );
	}

}
