
$( function () {	// start of document ready handler




// var PACKAGES = window.__DATA__;
var PACKAGES = window.__DATA__.PACKAGES;

/* -----
 Show the filename after selecting a file for upload
 ----- */
$( document ).on( "change", "input[ type=file ]", function ( event ) {
	var fileName = "/ " + event.target.files[ 0 ].name;
	$( event.target ).parent().find( ".js_file_name" ).text( fileName );
} );

/* -----
 Adding a new package
 ----- */
$( ".js_package_add" ).on( "click", function ( event ) {

	toggleFormInteraction();

	var $form = $( ".js_package_form_add" );
	var data = { };
	var formData = new FormData();

	// get schedule file
	var schedule = null;
	var scheduleFile = $form.find( "[ name = schedule ]" ).get( 0 );
	if ( scheduleFile.files.length ) {
		scheduleFile = scheduleFile.files[ 0 ];
		formData.append( "schedule", scheduleFile );
		schedule = scheduleFile.name;
	}

	// populate basic data
	data = {
		title: $form.find( "[ name = title ]" ).val(),
		price: $form.find( "[ name = price ]" ).val(),
		description: $form.find( "[ name = description ]" ).val(),
		schedule: schedule
	};

	// fill in "places" data
	data.places = [ ];
	var $places = $form.find( ".js_package_place" );
	for ( let _i = 0, _len = $places.length; _i < _len; _i += 1 ) {

		let $place = $places.eq( _i );
		let image = null;
		let imageFile = $place.find( "[ name = image ]" ).get( 0 );
		if ( imageFile.files.length ) {
			imageFile = imageFile.files[ 0 ];
			formData.append( "places[]", imageFile );
			// image = getTimeAndDateStamp() + "__" + imageFile.name;
			image = imageFile.name;
		}
		data.places.push( {
			location: $place.find( "[ name = location ]" ).val(),
			days: $place.find( "[ name = days ]" ).val(),
			image: image
		} );

	}

	// plonk all the data in the FormData structure
	formData.append( "data", JSON.stringify( data ) );
	formData.append( "action", "create" );

	$.ajax( {
		url: "/server/dashboard/packages.php",
		method: "POST",
		// data: { action: "create", data: data },
		accepts: "json",
		// dataType: "json",
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
			window.location.reload( true );
		}
	} )
	.always( function () {
		toggleFormInteraction();
	} )

} );

/* -----
 Updating an existing package
 ----- */
$( ".js_package_update" ).on( "click", function ( event ) {

	toggleFormInteraction();

	var $form = $( event.target ).closest( ".js_package_form_update" );
	var index = $form.data( "index" );
	var data = { };
	var formData = new FormData();

	// pull data of the DOM
	// get schedule file
	var schedule;
	var scheduleFile = $form.find( "[ name = schedule ]" ).get( 0 );
	if ( scheduleFile.files.length ) {
		scheduleFile = scheduleFile.files[ 0 ];
		formData.append( "schedule", scheduleFile );
		schedule = scheduleFile.name;
	}
	else {
		schedule = PACKAGES[ index ].schedule || null;
	}

	// populate basic data
	data = {
		title: $form.find( "[ name = title ]" ).val(),
		price: $form.find( "[ name = price ]" ).val(),
		description: $form.find( "[ name = description ]" ).val(),
		schedule: schedule
	};

	// fill in "places" data
	data.places = [ ];
	var placesForWhichFilesAreBeingSent = [ ];
	var $places = $form.find( ".js_package_place" );
	for ( let _i = 0, _len = $places.length; _i < _len; _i += 1 ) {

		let $place = $places.eq( _i );
		let image;
		let imageFile = $place.find( "[ name = image ]" ).get( 0 );
		if ( imageFile.files.length ) {
			imageFile = imageFile.files[ 0 ];
			formData.append( "places[]", imageFile );
			image = imageFile.name;
			placesForWhichFilesAreBeingSent.push( _i );
		}
		else {
			let place = PACKAGES[ index ].places[ _i ];
			image = place ? place.image : null;
		}
		data.places.push( {
			location: $place.find( "[ name = location ]" ).val(),
			days: $place.find( "[ name = days ]" ).val(),
			image: image
		} );

	}

	var meta = {
		index: parseInt( $form.data( "index" ), 10 ),
		placesForWhichFilesAreBeingSent
	};

	// plonk all the data in the FormData structure
	formData.append( "data", JSON.stringify( data ) );
	formData.append( "meta", JSON.stringify( meta ) );
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
		console.log( response )
		if ( response.message != "success" ) {
			alert( "Package couldn't be updated. Please try again later." );
		}
		else {
			// PACKAGES[ meta.index ] = data;
			PACKAGES[ meta.index ] = response.data;
			console.log( "package updated." );
			console.log( response.data );
			alert( "Updated package." );
		}
	} )
	.always( function () {
		toggleFormInteraction();
	} )

} )

/* -----
 Removing a package
 ----- */
$( ".js_package_remove" ).on( "click", function ( event ) {

	toggleFormInteraction();

	var $form = $( event.target ).closest( ".js_package_form_update" );

	var meta = {
		index: parseInt( $form.data( "index" ), 10 )
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
			alert( "Package couldn't be removed. Please try again later." );
		}
		else {
			PACKAGES.splice( meta.index, 1 );
			$form.remove();
			console.log( "package removed." );
			console.log( response.data );
			alert( "Removed package." );
			window.location.reload( true );
		}
	} )
	.always( function () {
		toggleFormInteraction();
	} )


} );

/* -----
 Adding a place
 ----- */
$( document ).on( "click", ".js_package_place_add", function ( event ) {

	toggleFormInteraction();

	// stamp out a new clone of the "place" form
	$placeForm = $( ".js_package_place:first" ).clone();
	// since we're not cloning from a template, but rather an existing
	// "place" form on the page, we're clearing out the values if any
	$placeForm.find( "input" ).val( null );
	$placeForm.find( ".js_file_name" ).text( "" );

	$places = $( event.target )
				.closest( ".js_package" )
				.find( ".js_package_places" );
	$places.append( $placeForm );

	toggleFormInteraction();

} );

/* -----
 Removing a place
 ----- */
$( document ).on( "click", ".js_package_place_remove", function ( event ) {

	if ( ! confirm( "Are you sure?" ) ) {
		return;
	}

	toggleFormInteraction();

	var $form = $( event.target ).closest( ".js_package" );
	var $place = $( event.target ).closest( ".js_package_place" );

	if ( $form.hasClass( "js_package_form_update" ) ) {
		var index = $form.data( "index" );
		var placeIndex = $place.parent().find( ".js_package_place" ).index( $place );
		PACKAGES[ index ].places.splice( placeIndex, 1 );
	}
	$place.remove();

	toggleFormInteraction();

} );

/* -----
 Re-ordering packages
 ----- */
$( document ).on( "click", ".js_package_move_up", function ( event ) {

	toggleFormInteraction();

	var $package = $( event.target ).closest( ".js_package" );
	var index = $package.data( "index" );
	if ( index == 0 ) {
		toggleFormInteraction();
		return;
	}

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
			alert( "Package couldn't be re-ordered. Please try again later." );
		}
		else {
			$package.data( "index", index - 1 );
			$package.find( ".js_package_name" ).text( index - 1 );
			$package.prev().data( "index", index );
			$package.prev().find( ".js_package_name" ).text( index );
			moveSiblingDOM( $package, "up" );

			[ PACKAGES[ index ], PACKAGES[ index - 1 ] ] =
				[ PACKAGES[ index - 1 ], PACKAGES[ index ] ];
		}
	} )
	.always( function () {
		toggleFormInteraction();
	} )

} );

$( document ).on( "click", ".js_package_move_down", function ( event ) {

	toggleFormInteraction();

	var $package = $( event.target ).closest( ".js_package" );
	var index = $package.data( "index" );
	if ( index == PACKAGES.length - 1 ) {
		toggleFormInteraction();
		return;
	}

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
		console.log( response )
		if ( response.message != "success" ) {
			alert( "Package couldn't be re-ordered. Please try again later." );
		}
		else {
			$package.data( "index", index + 1 );
			$package.find( ".js_package_name" ).text( index + 1 );
			$package.next().data( "index", index );
			$package.next().find( ".js_package_name" ).text( index );
			moveSiblingDOM( $package, "down" );

			[ PACKAGES[ index ], PACKAGES[ index + 1 ] ] =
				[ PACKAGES[ index + 1 ], PACKAGES[ index ] ];
		}
	} )
	.always( function () {
		toggleFormInteraction();
	} )

} );

/* -----
 Re-ordering places
 ----- */
$( document ).on( "click", ".js_package_place_move_up", function ( event ) {

	toggleFormInteraction();

	var packageIndex = $( event.target ).closest( ".js_package" ).data( "index" );
	var $place = $( event.target ).closest( ".js_package_place" );
	var $places = $place.closest( ".js_package_places" ).find( '.js_package_place' );
	var placeIndex = $places.index( $place );
	var places = packageIndex && PACKAGES[ packageIndex ].places;
	if ( places && placeIndex == 0 ) {
		toggleFormInteraction();
		return;
	}

	moveSiblingDOM( $place, "up" );
	// if the package has been committed prior, then do the swap for the in-memory data structure
	if ( packageIndex ) {
		[ places[ placeIndex ], places[ placeIndex - 1 ] ] =
			[ places[ placeIndex - 1 ], places[ placeIndex ] ];
	}

	toggleFormInteraction();

} );

$( document ).on( "click", ".js_package_place_move_down", function ( event ) {

	toggleFormInteraction();

	var packageIndex = $( event.target ).closest( ".js_package" ).data( "index" );
	var $place = $( event.target ).closest( ".js_package_place" );
	var $places = $place.closest( ".js_package_places" ).find( '.js_package_place' );
	var placeIndex = $places.index( $place );
	var places = packageIndex && PACKAGES[ packageIndex ].places;
	if ( places && placeIndex == places.length - 1 ) {
		toggleFormInteraction();
		return;
	}

	moveSiblingDOM( $place, "down" );
	// if the package has been committed prior, then do the swap for the in-memory data structure
	if ( packageIndex ) {
		[ places[ placeIndex ], places[ placeIndex + 1 ] ] =
			[ places[ placeIndex + 1 ], places[ placeIndex ] ];
	}

	toggleFormInteraction();

} );





} );	// end of document ready handler

// not used anymore
function getTimeAndDateStamp () {
	var date = new Date();
	var stamp = (
		date.getFullYear() + "-" +
		( date.getMonth() + 1 ) + "-" +
		date.getDate() + "_" +
		date.getHours() + "-" +
		date.getMinutes() + "-" +
		date.getSeconds()
	);
	return stamp;
}

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

function toggleFormInteraction() {

	if ( ! $( ".js_package_add" ).attr( "disabled" ) ) {
		$( ".js_package_add, .js_package_update, .js_package_remove, .js_package_place_add, .js_package_place_remove" ).attr( "disabled", true );
	}
	else {
		$( ".js_package_add, .js_package_update, .js_package_remove, .js_package_place_add, .js_package_place_remove" ).attr( "disabled", false );
	}

}
