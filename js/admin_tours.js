
// setTimeout( function () {
// 	window.location.reload( true )
// }, 5 * 1000 );

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
	var filenames;
	if ( ! domFileInput.files.length ) {
		fileNames = [ ];
	}
	else {
		fileNames = Array.from( domFileInput.files ).map( function ( file ) { return file.name } );
	}
	$( domFileInput ).data( "path", fileNames );
	if ( fileNames.length > 1 ) {
		fileNames = fileNames.length + " files.";
	}

	$( domFileInput ).closest( ".js_tour_gallery_image" )
		.find( ".js_file_name" )
		.text( fileNames );
	$( domFileInput ).closest( ".js_tour_gallery_image" )
		.find( ".js_file_name img" )
		.attr( "alt", fileNames );
} );



var TOURS = window.__DATA__.TOURS;

/* ~~~~~
 ----- TOURS
 ~~~~~ */
/* -----
 Expanding/Collapsing a tour
 ----- */
$( document ).on( "click", ".js_tour_toggle", function ( event ) {
	var $toggler = $( event.target ).closest( ".js_tour_toggle" );
	var $tour = $( event.target ).closest( ".js_tour" );
	$toggler.toggleClass( "flip" );
	$tour.toggleClass( "show" );
} );

/* -----
 Adding a new tour
 ----- */
$( ".js_tour_add" ).on( "click", function ( event ) {

	toggleLoadingIndicator();

	var $form = $( event.target ).closest( ".js_tour" );

	// pull data from the DOM and construct a data object
	var data = { };

	data.organisation = $form.find( "[ name = organisation ]" ).val();
	data.description = $form.find( "[ name = tour_description ]" ).val();
	data.start_date = $form.find( "[ name = start_date ]" ).val();
	data.days = Array.from( $form.find( ".js_tour_gallery" ) ).map( domGallery => {
		var $gallery = $( domGallery );
		var domImages = Array.from( $gallery.find( ".js_tour_gallery_image" ) );
		return {
			description: $gallery.find( "[ name = day_description ]" ).val(),
			gallery: domImages
				.map( domImage => {
					var $image = $( domImage );
					var imageFilePaths = $image.find( "[ name = image ]" ).data( "path" ) || [ ];
					var images = imageFilePaths.map( function ( path ) {
						return {
							location: $image.find( "[ name = location ]" ).val(),
							caption: $image.find( "[ name = caption ]" ).val(),
							image: path || "",
						};
					} );
					return images;
				} )
				// flattening the array into a single dimension
				.reduce( function ( allImages, currentImages ) {
					return allImages.concat( currentImages );
				}, [ ] )
		};
	} );

	// get file objects from the file input fields
	var fileObjects = Array.from( $form.find( "[ name = image ]" ) )
		.filter( domFiles => domFiles.files.length )
		.map( domFiles => Array.from( domFiles.files ) )
		.reduce( function ( allFiles, currentFiles ) { return allFiles.concat( currentFiles ) }, [ ] );

	// build a data object that can store file data
	var formData = new FormData();
	fileObjects.forEach( file => formData.append( "gallery[]", file ) );
	formData.append( "data", JSON.stringify( data ) );
	formData.append( "action", "create" );

	$.ajax( {
		url: "/server/dashboard/tours.php",
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
			alert( "Awesome! A new tour is being added." );
			window.location.reload( true );
		}
		else {
			alert( "Something went wrong. Try again later." );
		}
	} )
	.always( function () {
		toggleLoadingIndicator();
	} )

} );

/* -----
 Updating an existing tour
 ----- */
$( ".js_tour_update" ).on( "click", function ( event ) {

	toggleLoadingIndicator();

	var $form = $( event.target ).closest( ".js_tour" );

	// pull data from the DOM and construct a data object
	var meta = {
		index: $form.data( "index" )
	};
	var data = { };

	data.organisation = $form.find( "[ name = organisation ]" ).val();
	data.description = $form.find( "[ name = tour_description ]" ).val();
	data.start_date = $form.find( "[ name = start_date ]" ).val();
	data.days = Array.from( $form.find( ".js_tour_gallery" ) ).map( domGallery => {
		var $gallery = $( domGallery );
		var domImages = Array.from( $gallery.find( ".js_tour_gallery_image" ) );
		return {
			description: $gallery.find( "[ name = day_description ]" ).val(),
			gallery: domImages
				.map( domImage => {
					var $image = $( domImage );
					var imageFilePaths = $image.find( "[ name = image ]" ).data( "path" ) || [ ];
					var images = imageFilePaths.map( function ( path ) {
						return {
							location: $image.find( "[ name = location ]" ).val(),
							caption: $image.find( "[ name = caption ]" ).val(),
							image: path || "",
						};
					} );
					return images;
				} )
				// flattening the array into a single dimension
				.reduce( function ( allImages, currentImages ) {
					return allImages.concat( currentImages );
				}, [ ] )
		};
	} );

	// get file objects from the file input fields
	var fileObjects = Array.from( $form.find( "[ name = image ]" ) )
		.filter( domFiles => domFiles.files.length )
		.map( domFiles => Array.from( domFiles.files ) )
		.reduce( function ( allFiles, currentFiles ) { return allFiles.concat( currentFiles ) }, [ ] );

	// build a data object that can store file data
	var formData = new FormData();
	fileObjects.forEach( file => formData.append( "gallery[]", file ) );
	formData.append( "meta", JSON.stringify( meta ) );
	formData.append( "data", JSON.stringify( data ) );
	formData.append( "action", "update" );

	/* Prevent the tour from being modified */
	// $form.find( ".js_tour_toggle, .js_tour_move_up, .js_tour_move_down, .js_tour_remove" ).prop( "disabled", true );

	$.ajax( {
		url: "/server/dashboard/tours.php",
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
			alert( "The tour is being updated." );
			// $form.find( ".js_tour_toggle, .js_tour_move_up, .js_tour_move_down, .js_tour_remove" ).prop( "disabled", false );
			// $form.find( ".js_tour_toggle" ).trigger( "click" );
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
 Removing a tour
 ----- */
$( ".js_tour_remove" ).on( "click", function ( event ) {

	toggleLoadingIndicator();

	var $form = $( event.target ).closest( ".js_tour" );

	var meta = {
		index: $form.data( "index" )
	};

	$.ajax( {
		url: "/server/dashboard/tours.php",
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
			alert( "Removed the tour." );
			window.location.reload( true );
		}
	} )
	.always( function () {
		toggleLoadingIndicator();
	} )

} );


/* -----
 Re-ordering tours
 ----- */
$( document ).on( "click", ".js_tour_move_up", function ( event ) {

	toggleLoadingIndicator();

	var $tour = $( event.target ).closest( ".js_tour" );
	var index = $tour.data( "index" );

	var meta = {
		index1: index,
		index2: index - 1
	};

	$.ajax( {
		url: "/server/dashboard/tours.php",
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
			$tour.data( "index", meta.index2 );
			$tour.find( ".js_tour_name" ).text( meta.index2 );
			$tour.prev().data( "index", index );
			$tour.prev().find( ".js_tour_name" ).text( index );
			moveSiblingDOM( $tour, "up" );
		}
	} )
	.always( function () {
		toggleLoadingIndicator();
	} )

} );

$( document ).on( "click", ".js_tour_move_down", function ( event ) {

	toggleLoadingIndicator();

	var $tour = $( event.target ).closest( ".js_tour" );
	var index = $tour.data( "index" );

	var meta = {
		index1: index,
		index2: index + 1
	};

	$.ajax( {
		url: "/server/dashboard/tours.php",
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
			$tour.data( "index", meta.index2 );
			$tour.find( ".js_tour_name" ).text( meta.index2 );
			$tour.next().data( "index", index );
			$tour.next().find( ".js_tour_name" ).text( index );
			moveSiblingDOM( $tour, "down" );
		}
	} )
	.always( function () {
		toggleLoadingIndicator();
	} )

} );




/* ~~~~~
 ----- DAYS
 ~~~~~ */

/* -----
  Expanding/Collapsing a day
 ----- */
$( document ).on( "click", ".js_day_toggle", function ( event ) {
	var $toggler = $( event.target ).closest( ".js_day_toggle" );
	var $day = $( event.target ).closest( ".js_tour_gallery" );
	$toggler.toggleClass( "flip" );
	$day.toggleClass( "expand" );
} );

/* -----
 Adding a day
 ----- */
$( document ).on( "click", ".js_tour_gallery_add", function ( event ) {
	var $galleryContainer = $( event.target )
		.closest( ".js_tour" )
		.find( ".js_tour_galleries" );
	$newGallery = $( $( ".tmpl_tour_gallery" ).html() );
	$galleryContainer.append( $newGallery );
} );

/* -----
 Removing a day
 ----- */
$( document ).on( "click", ".js_tour_gallery_remove", function ( event ) {
	var $gallery = $( event.target ).closest( ".js_tour_gallery" );
	$gallery.remove();
} );

/* -----
 Moving a day
 ----- */
$( document ).on( "click", ".js_tour_gallery_move_up", function ( event ) {
	var $gallery = $( event.target ).closest( ".js_tour_gallery" );
	moveSiblingDOM( $gallery, "up" );
} );
$( document ).on( "click", ".js_tour_gallery_move_down", function ( event ) {
	var $gallery = $( event.target ).closest( ".js_tour_gallery" );
	moveSiblingDOM( $gallery, "down" );
} );





/* ~~~~~
 ----- IMAGES IN A DAY'S GALLERY
 ~~~~~ */
/* -----
 Adding an image in a day's gallery
 ----- */
$( document ).on( "click", ".js_tour_gallery_image_add", function ( event ) {
	var $imagesContainer = $( event.target )
		.closest( ".js_tour_gallery" )
		.find( ".js_tour_gallery_images" );
	$newImage = $( $( ".tmpl_tour_gallery_image" ).html() );
	$imagesContainer.append( $newImage );
} );

/* -----
 Removing an image in a day's gallery
 ----- */
$( document ).on( "click", ".js_tour_gallery_image_remove", function ( event ) {
	var $image = $( event.target ).closest( ".js_tour_gallery_image" );
	$image.remove();
} );

/* -----
 Moving an image in a day's gallery
 ----- */
$( document ).on( "dragstart", ".js_tour_gallery_image", function ( event ) {
	var $image = $( event.target ).closest( ".js_tour_gallery_image" );
	$image.attr( "id", "js_current_draggee" );
} );
$( document ).on( "dragend", ".js_tour_gallery_image", function ( event ) {
	$( event.target ).closest( ".js_tour_gallery_images" )
		.find( ".indicate-placement" )
		.removeClass( "indicate-placement" )
	$( "#js_current_draggee" ).removeAttr( "id" );
} );
$( document ).on( "dragover dragenter", ".js_tour_gallery_image", function ( event ) {
	event.preventDefault();
} );
$( document ).on( "dragenter", ".js_tour_gallery_image", function ( event ) {

	var $image = $( event.target ).closest( ".js_tour_gallery_image" );
	if ( $image.is( "#js_current_draggee" ) ) {
		return;
	}

	if ( ! $image.hasClass( "indicate-placement" ) ) {
		$( ".indicate-placement" ).removeClass( "indicate-placement" );
		$image.addClass( "indicate-placement" );
	}

} );
$( document ).on( "drop", ".js_tour_gallery_image", function ( event ) {
	var $image = $( event.target ).closest( ".js_tour_gallery_image" );
	var $imageToBeMoved = $( "#js_current_draggee" );
	var $fromGallery = $imageToBeMoved.closest( ".js_tour_gallery_images" );
	var $toGallery = $image.closest( ".js_tour_gallery_images" );
	$imageToBeMoved.removeAttr( "id" );
	if ( $fromGallery.find( ".js_tour_gallery_image" ).index( $imageToBeMoved ) > $toGallery.find( ".js_tour_gallery_image" ).index( $image ) ) {
		$imageToBeMoved.insertBefore( $image );
	}
	else {
		$imageToBeMoved.insertAfter( $image );
	}
} );
// $( document ).on( "click", ".js_tour_gallery_image_move_up", function ( event ) {
// 	var $image = $( event.target ).closest( ".js_tour_gallery_image" );
// 	moveSiblingDOM( $image, "up" );
// } );
// $( document ).on( "click", ".js_tour_gallery_image_move_down", function ( event ) {
// 	var $image = $( event.target ).closest( ".js_tour_gallery_image" );
// 	moveSiblingDOM( $image, "down" );
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
