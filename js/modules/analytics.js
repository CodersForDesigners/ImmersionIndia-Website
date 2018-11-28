
// If a detailed itinerary was clicked on
$( document ).on( "::pre-enquiry:get-detailed-itinerary", function ( event ) {
	if ( ! $( document ).data( "detailedItineraryClickedOnBefore" ) ) {
		dataLayer.push( {
			event: "Request",
			thing: "Detailed Itinerary"
		} );
		$( document ).data( "detailedItineraryClickedOnBefore", true );
	}
} );

// If the Tour Package form was submitted
$( document ).on( "::enquiry:tour-package", function ( event, data ) {
	if ( ! $( document ).data( "tourPackageFormSubmittedBefore" ) ) {
		dataLayer.push( {
			event: "Enquiry",
			via: "Tour Package Form"
		} );
		$( document ).data( "tourPackageFormSubmittedBefore", true );
	}
} );

// If the user enquired by calling the number
$( document ).on( "::enquiry:phone", function ( event, data ) {
	if ( ! $( document ).data( "attemptedToPhoneBefore" ) ) {
		dataLayer.push( {
			event: "Enquiry",
			via: "Phone"
		} );
		$( document ).data( "attemptedToPhoneBefore", true );
	}
} );

// If the user enquired by initiating the sending of an e-mail
$( document ).on( "::enquiry:send-email", function ( event, data ) {
	if ( ! $( document ).data( "attemptedToSendEmailBefore" ) ) {
		dataLayer.push( {
			event: "Enquiry",
			via: "E-mail"
		} );
		$( document ).data( "attemptedToSendEmailBefore", true );
	}
} );
