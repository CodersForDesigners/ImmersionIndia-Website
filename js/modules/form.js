
$( function () {

	/* -----
		SUBMISSION
	 ----- */
	$( "#js_form_book_tour" ).on( "submit", function ( event ) {

		event.preventDefault();

		// pull values from the form
		var $form = $( event.target );
		var $submitButton = $form.find( ".js_form_submit" );

		/* Pull in the form field DOM elements */
		var $name = $form.find( ".js_name" );
		var $emailAddress = $form.find( ".js_email_address" );
		var $phoneNumber = $form.find( ".js_phone_number" );
		var $institute = $form.find( ".js_institute" );
		var $package = $form.find( ".js_form_booking_package" );
		var $tourDate = $form.find( ".js_tour_date" );



		/* Validate the data entered in the form */
		// first, remove any `form-error` classes on the input fields ( if present from a prior submission attempt )
		$form.find( ".form-error" ).removeClass( "form-error" );

		var name = $name.val().trim().replace( /\s+/, ' ' );
		if ( ! name ) {
			$name.addClass( "form-error" );
			alert( "Please enter your name." );
		}
		else if ( name.split( /\s/ ).length < 2 ) {
			$name.addClass( "form-error" );
			alert( "Please enter your full name." );
		}
		if ( ! $emailAddress.val().trim() ) {
			$emailAddress.addClass( "form-error" );
			alert( "Please enter your email." );
		}
		// $phoneNumber.val( $phoneNumber.val().replace( /[^-–+\d\s]/g, "" ) );
		// if ( $phoneNumber.val().replace( /\D/g, "" ).length < 8 ) {
		// 	$phoneNumber.addClass( "form-error" );
		// 	alert( "Please enter a valid phone number." );
		// }
		/*if ( $phoneNumber.val().replace( /\D/g, "" ).length < 9 ) {
			$phoneNumber.addClass( "form-error" );
			alert( "Please enter a valid phone number." );
		}*/

		// If any of the required fields had no value or an incorrect one, then
		// do not proceed.
		if ( $form.find( ".form-error" ).length ) {
			return;
		}

		var data = {
			name: $name.val(),
			email_address: $emailAddress.val(),
			phone_number: $phoneNumber.val(),
			institute: $institute.val() || null,
			package: $package.val() || null,
			tour_date: $tourDate.val() || null
		};
		data.async = true;

		// Show processing indicator
		$submitButton
			.prop( "disabled", true )
			.attr( "value", "Sending..." )
			.addClass( "loading" );

		// For Google Tag Manager
		$( document ).trigger( "::enquiry:tour-package" );

		// Mail to builder about general
		$.ajax( {
			url: "/server/mailer/handle_form_data.php",
			method: "POST",
			data: data
		} )
		.done( function ( responseRaw, status, xhr ) {

			var form_submit_message = "Thank You! We will get in touch.";

			$submitButton
				// .prop( "disabled", false )
				.attr( "value", form_submit_message )
				.removeClass( "loading" )
				.addClass( "done" );

		} );

		// Triggering event for ga tracking of form submit
		$form.trigger( "ga_form_validated" );

	} );

} );
