// facebook / youtube_embed player


/*
<!-- Sample Markup -->
<div class="youtube_embed ga_video" data-src="https://www.youtube.com/embed/lncVHzsc_QA?rel=0&amp;showinfo=0" data-ga-video-src="Sample - Video">
	<div class="youtube_load"></div>
	<iframe width="1280" height="720" src="" frameborder="0" allowfullscreen></iframe>
</div>
*/


$(document).ready( function(){

	window.setTimeout(function() {
		$( '.youtube_embed' ).each( function() {
			var yt_src = $( this ).data( 'src' );
			if ( yt_src )
				$( this ).find( 'iframe' ).attr( 'src', yt_src );
		} );
		$( '.facebook_embed' ).each( function() {
			var fb_src = $( this ).data( 'src' );
			if ( fb_src )
				$( this ).find( 'iframe' ).attr( 'src', fb_src );
		} );
	}, 3000);

	// If there isn't a background YouTube embed, move on
	if ( $( ".js_embed_bg_yt" ).length == 0 )
		return;

	// If there is a background YouTube embed, then
	// 1. Load the YouTube API library (asynchronously)
	var scriptElement = document.createElement( "script" );
	scriptElement.src = "https://www.youtube.com/iframe_api";
	$( "body script" ).first().before( scriptElement );

	// 2. Setup the YouTube video, its playback options and hooks event handling
	function onYouTubeIframeAPIReady () {
		$( document ).trigger( "youtube-api/ready" );
		new YT.Player( "youtube_video_embed", {
			events: {
				onReady: onPlayerReady,
				onStateChange: onPlayerStateChange
			}
		} );
	}
	// This function needs to exposed as a global
	window.onYouTubeIframeAPIReady = onYouTubeIframeAPIReady;

	// When the YouTube video player is ready, this function is run
	function onPlayerReady ( event ) {
		// We wait for a moment and then play the video.
		// This is so that it autoplays on mobile devices
		setTimeout( function () {
		  	event.target.playVideo();
		}, 1500 )
	}

	// Whenever the YouTube video player's state changes, this function is run
	function onPlayerStateChange ( event ) {
		if ( event.data == YT.PlayerState.PLAYING )
			window.hideFallbackImage();
		if ( event.data == YT.PlayerState.ENDED )
			event.target.seekTo( 0 );
	}

});