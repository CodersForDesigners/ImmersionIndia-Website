// the history API allows you to explicitly take control of routing in the browser
// you take control of what happens when a user clicks the back/forwards buttons
// the API consists of the following,
// pushState
// popState
// replaceState
// for brevity, when navigating to a route, you have to make sure that all the data that pertains to that route is captured/presrverd useing the pushState method
// in order to get the "back" button functionality to work, you have to implements an event handler for the `popstate` event
	// this handlers should take care of restoring the "state" of the previous route
	// the `history` variable is a global variable whose value changes implicitly when the `popstate` event is fired
		// the `history.state` variable holds the state that was explicitly preserved when the `pushState` method was called


$title = document.title;

if ( location.search == "" ) {
	var state = { page: "pageone" };
	history.pushState( state, "pageone", "?page=pageone" );
} else {
	if ( location.search == "?page=pageone" ) {
		state = { page: "pageone" };
		history.pushState( state, "", location.search );
		document.title = $title + " | pageone";

	} else if ( location.search == "?page=pagetwo" ) {
		state = { page: "pagetwo" };
		history.pushState( state, "", location.search );
		document.title = $title + " | pagetwo";

	} else {
		state = { page: "404" };
		history.pushState( state, "404", "?page=404" );
		document.title = $title + " | 404 : Page Not Found";

	}
}

$(document).ready( function(){

	// initialise active tab
	if ( location.search == "?page=pageone" ) {
		$( '.js_nav_button[data-page-id="pageone"]' ).addClass( 'active' );
	} else if ( location.search == "?page=pagetwo" ) {
		$( '.js_nav_button[data-page-id="pagetwo"]' ).addClass( 'active' );
	}


	// facebook / youtube_embed player
	window.setTimeout(function() {
		$( '.youtube_embed' ).each( function() {
			var yt_src = $( this ).data( 'src' );
			$( this ).find( 'iframe' ).attr( 'src', yt_src );
		} );
		$( '.facebook_embed' ).each( function() {
			var fb_src = $( this ).data( 'src' );
			$( this ).find( 'iframe' ).attr( 'src', fb_src );
		} );
	}, 3000);



	$( '.js_nav_button' ).on('click', function( event ) {

		event.preventDefault();


		// active tab
		$( '.js_nav_button' ).removeClass( 'active' );
		$( this ).addClass( 'active' );


		// close mobile menu
		$(".js_navigation_box").toggleClass("show");
		$(".js_menu_button").toggleClass("close");


		// var scroll_to = $("#page-content").offset().top-150;
		// $('html, body').scrollTop(scroll_to);


		// swap out content etc...
		var $page = $( event.target ).data( 'pageId' );

		$( '#page-content' ).addClass( 'loading' );
		$( '#page-content' ).load( '/pages/' + $page + '.php', function() {

			$( '#page-content' ).removeClass( 'loading' );

			// smooth scroll to content
			// $('html,body').animate({
			// 	scrollTop: $( '#page-content' ).offset().top-150
			// }, 1600);

			// youtube_embed player
			window.setTimeout(function() {
				$( '.youtube_embed' ).each( function() {
					var yt_src = $( this ).data( 'src' );
					$( this ).find( 'iframe' ).attr( 'src', yt_src );
				} );
				$( '.facebook_embed' ).each( function() {
					var fb_src = $( this ).data( 'src' );
					$( this ).find( 'iframe' ).attr( 'src', fb_src );
				} );
			}, 3000);

		} );

		var newURL = window.location.protocol + '//' + window.location.hostname + window.location.pathname;

		newURL = newURL + '?page=' + $page;

		state = { page: $page };
		history.pushState( state, $page, newURL );

		// #page-wrapper class
		$( '#page-wrapper' ).attr( "data-page", $page );

		// updating the title
		document.title = $title + " | " + $page[0].toUpperCase()+$page.slice(1);

		// Adding a GA hook
		// scroller();

	});

	// set popstate history
	$( window ).on( "popstate", function () {
		var $page = history.state.page;
		$( '#page-content' ).load( '/pages/' + $page + '.php' );
	} );


	// mobile menu button
	$(".js_menu_button").on("click", function() {
		$(".js_navigation_box").toggleClass("show");
		$(".js_menu_button").toggleClass("close");
	} );

} );
