
// check support for passive event handling
var supportPassiveEventHandling = false;

try {
  document.createElement( "div" )
    .addEventListener(
        "test",
        function () {},
        Object.defineProperty( { }, "passive", {
          get: function () {
            supportPassiveEventHandling = true;
            return false;
          }
        } )
    );
}
catch ( e ) {}

// takes a function as input
// returns another function that only executes the given function
// before the next paint
function runBeforePaint ( fn ) {

  var animationFrameId = null;

  return function ( event ) {

	if ( animationFrameId ) {
		window.cancelAnimationFrame( animationFrameId );
		animationFrameId = null;
	}
	animationFrameId = window.requestAnimationFrame( fn.bind( null, event ) );

  };

};

// the scroll event handler
var scrollHandler = runBeforePaint( function () {

	// store the navigation anchor tags
	var $navigation = $( ".js_navigation_section" );
	var $linkHome = $navigation.find( "[ href = '#home' ]" );
	var $linkWhatWeDo = $navigation.find( "[ href = '#what_we_do' ]" );
	var $linkTours = $navigation.find( "[ href = '#tour_packages' ]" );
	var $linkBook = $navigation.find( "[ href = '#book_now' ]" );
	var $linkTeam = $navigation.find( "[ href = '#team' ]" );



	return function scrollHandler ( event ) {

		// the sections
		var $sectionHome = $( "#home" );
		var $sectionWhatWeDo = $( "#what_we_do" );
		var $sectionTours = $( "#tour_packages" );
		var $sectionBook = $( "#book_now" );
		var $sectionTeam = $( "#team" );

		var scrollTop = window.scrollY;

		// sticky the nav when it's past the home section
		if ( scrollTop >= $sectionWhatWeDo.get( 0 ).offsetTop ) {
			$navigation.addClass( "scrolled" );
		}
		else {
			$navigation.removeClass( "scrolled" );
		}

		// mark the currently active section on the navigation bar
		$navigation.find( ".active" ).removeClass( "active" );
		if ( scrollTop >= $sectionTeam.get( 0 ).offsetTop ) {
			$linkTeam.addClass( "active" );
		}
		else if ( scrollTop >= $sectionBook.get( 0 ).offsetTop ) {
			$linkBook.addClass( "active" );
		}
		else if ( scrollTop >= $sectionTours.get( 0 ).offsetTop ) {
			$linkTours.addClass( "active" );
		}
		else if ( scrollTop >= $sectionWhatWeDo.get( 0 ).offsetTop ) {
			$linkWhatWeDo.addClass( "active" );
		}
		else if ( scrollTop >= $sectionHome.get( 0 ).offsetTop ) {
			$linkHome.addClass( "active" );
		}

	};

}() );

// run this once anyways to determine the active section
scrollHandler();
// attach the scroll handling function to the scroll event
window.addEventListener(
	"scroll",
	scrollHandler,
	supportPassiveEventHandling ? { capture: true, passive: true } : true
);





// takes a function as input
// returns the same function whose execution is controlled in a way that –
// if invoked multiple times within a span of milli-seconds,
// it will only run once, and not for the number of times it was invoked
// function debounceAndRunBeforePaint ( fn ) {

//   var animationFrameId = null;
//   var timeoutId = null;

//   return function ( event ) {

//     if ( timeoutId ) {
//       window.clearTimeout( timeoutId );
//       timeoutId = null;
//     }
//     timeoutId = window.setTimeout( function () {

//       if ( animationFrameId ) {
//         window.cancelAnimationFrame( animationFrameId );
//         animationFrameId = null;
//       }
//       animationFrameId = window.requestAnimationFrame( fn.bind( null, event ) );

//     }, 51 );

//   };

// };

// function debounceAndRun ( fn ) {

//   var timeoutId = null;

//   return function ( event ) {

//     if ( timeoutId ) {
//       window.clearTimeout( timeoutId );
//       timeoutId = null;
//     }
//     timeoutId = window.setTimeout( fn.bind( null, event ), 51 );

//   };

// };

// function throttleAndRun ( fn ) {

// 	var timeoutId = null;
// 	var scheduleExecution = false;

// 	function runFn () {

// 		fn( event );
// 		timeoutId = null;

// 		if ( scheduleExecution ) {
// 			timeoutId = window.setTimeout( runFn, 51 );
// 		}

// 	}

// 	return function ( event ) {

// 		if ( timeoutId ) {
// 			scheduleExecution = true;
// 			return;
// 		}
// 		timeoutId = window.setTimeout( runFn, 501 );

// 	};

// };

// function throttleAndRunBeforePaint ( fn ) {

// };
