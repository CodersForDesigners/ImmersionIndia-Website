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
			$( this ).find( 'iframe' ).attr( 'src', yt_src );
		} );
		$( '.facebook_embed' ).each( function() {
			var fb_src = $( this ).data( 'src' );
			$( this ).find( 'iframe' ).attr( 'src', fb_src );
		} );
	}, 3000);

});