<?php

	$root_path = $_SERVER[ 'DOCUMENT_ROOT' ];

	require ('inc/lazaro.php');

	$database_file = "{$root_path}/database/package_database.json";
	$packages = json_decode( file_get_contents( $database_file ) );

	// get the total days in the package with the highest number of days
	$places_in_packages = array_column( $packages, 'locations' );
	$days_in_packages = array_map( function ( $places_in_package ) {
		return array_sum( array_column( $places_in_package, 'days' ) );
	}, $places_in_packages );
	$max_days_in_a_package = max( $days_in_packages );

	$database_file = "{$root_path}/database/tour_database.json";
	$tours = json_decode( file_get_contents( $database_file ), true );
	// create ids for the tours
	foreach ( $tours as &$tour ) {
		$tour[ 'id' ] = strtolower( preg_replace( '/\s+/', '-', $tour[ 'organisation' ] ) . '-' . date( 'F-Y', strtotime( $tour[ 'start_date' ] ) ) );
	}
	unset( $tour );

	// the modal to show right when the page loaded
	$view = $_GET[ 'view' ] ?? false;

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
	prefix="og: http://ogp.me/ns# fb: http://www.facebook.com/2008/fbml">

<head>


	<!-- Page Meta -->
	<meta charset="utf-8">
	<title>Immersion India | We make travelling to India on an immersion tour easy.</title>
	<meta name="description"
		content="We are a single window solution provider specializing in study tours. We can adapt the itinerary of a tour to suit your curriculum. Come experience the language and culture of urban and rural India.">
	<!-- <meta name="keywords" content="Keyword_A, Keyword_B, Keyword_C, Keyword_D, Keyword_E"> -->
	<meta name="author" content="Lazaro Advertising">
	<link rel="canonical" href="http://immersionindia.com/">

	<!-- Viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<script type="text/javascript">var SETTINGS = { };</script>

	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
	<link rel="manifest" href="favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#444444">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#444444">

	<!-- Open Graph Name Space -->
	<meta property="og:title" content="Immersion India | We make travelling to India on an immersion tour easy.">
	<meta property="og:type" content="website">
	<meta property="og:url" content="http://immersionindia.com/">
	<meta property="og:site_name" content="http://immersionindia.com/">
	<meta property="og:image" content="http://immersionindia.com/social/og-thumbnail-image.png">
	<meta property="og:image:width" content="310">
	<meta property="og:image:height" content="310">
	<meta property="og:image" content="http://immersionindia.com/social/og-cover-image.png">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:description"
		content="We are a single window solution provider specialising in study tours. We can adapt the itinerary of a tour to suit your curriculum. Come experience the language and culture of urban and rural India.">

	<!-- Facebook APP ID -->
	<meta property="fb:app_id" content="Your FB_APP_ID">

	<!-- Google Tag Manager -->
	<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-PK46W6N');</script>
	<!-- End Google Tag Manager -->

	<!-- Fonts -->
	<script src="https://use.typekit.net/dtk5iub.js"></script>
	<script>try{Typekit.load({ async: true });}catch(e){}</script>

	<!-- Global CSS Vars -->
	<style type="text/css">
		:root {
			--max-days: <?php echo $max_days_in_a_package ?>;
			--max-package-height: 580px;
		}
	</style>

	<!-- Icons -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!-- Stylesheet -->
	<link rel="stylesheet" type="text/css" href="style.css?v=191023">

	<!-- jQuery 3 -->
	<script type="text/javascript" src="/js/jquery-3.0.0.min.js?v=191023"></script>

	<!-- Codyhouse Animating Headline -->
	<link rel="stylesheet" type="text/css" href="plugins/cd-headline/css/style.css?v=191023"/>

	<!-- Slick Carousel -->
	<link rel="stylesheet" type="text/css" href="plugins/slick/slick.css?v=191023"/>
	<link rel="stylesheet" type="text/css" href="plugins/slick/slick-theme.css?v=191023"/>

</head>









<body id="body" class="body <?php if ( $view ) : ?> modal-open <?php endif; ?>">

<!--
	Checking support for :: WebP
 -->
<script type="text/javascript">
	/*!
	 * modernizr v3.5.0
	 * Build https://modernizr.com/download?-webp-setclasses-dontmin
	 */
	!function(A,e,n){function o(A,e){return typeof A===e}function t(){var A,e,n,t,a,i,r;for(var u in l)if(l.hasOwnProperty(u)){if(A=[],e=l[u],e.name&&(A.push(e.name.toLowerCase()),e.options&&e.options.aliases&&e.options.aliases.length))for(n=0;n<e.options.aliases.length;n++)A.push(e.options.aliases[n].toLowerCase());for(t=o(e.fn,"function")?e.fn():e.fn,a=0;a<A.length;a++)i=A[a],r=i.split("."),1===r.length?f[r[0]]=t:(!f[r[0]]||f[r[0]]instanceof Boolean||(f[r[0]]=new Boolean(f[r[0]])),f[r[0]][r[1]]=t),s.push((t?"":"no-")+r.join("-"))}}function a(A){var e=c.className,n=f._config.classPrefix||"";if(p&&(e=e.baseVal),f._config.enableJSClass){var o=RegExp("(^|\\s)"+n+"no-js(\\s|$)");e=e.replace(o,"$1"+n+"js$2")}f._config.enableClasses&&(e+=" "+n+A.join(" "+n),p?c.className.baseVal=e:c.className=e)}function i(A,e){if("object"==typeof A){for(var o in A)u(A,o)&&i(o,A[o])}else{A=A.toLowerCase();var t=A.split("."),s=f[t[0]];if(2==t.length&&(s=s[t[1]]),n!==s)return f;e="function"==typeof e?e():e,1==t.length?f[t[0]]=e:(!f[t[0]]||f[t[0]]instanceof Boolean||(f[t[0]]=new Boolean(f[t[0]])),f[t[0]][t[1]]=e),a([(e&&0!=e?"":"no-")+t.join("-")]),f._trigger(A,e)}return f}var s=[],l=[],r={_version:"3.5.0",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(A,e){var n=this;setTimeout(function(){e(n[A])},0)},addTest:function(A,e,n){l.push({name:A,fn:e,options:n})},addAsyncTest:function(A){l.push({name:null,fn:A})}},f=function(){};f.prototype=r,f=new f;var u,c=e.documentElement,p="svg"===c.nodeName.toLowerCase();!function(){var A={}.hasOwnProperty;u=o(A,"undefined")||o(A.call,"undefined")?function(A,e){return e in A&&o(A.constructor.prototype[e],"undefined")}:function(e,n){return A.call(e,n)}}(),r._l={},r.on=function(A,e){this._l[A]||(this._l[A]=[]),this._l[A].push(e),f.hasOwnProperty(A)&&setTimeout(function(){f._trigger(A,f[A])},0)},r._trigger=function(A,e){if(this._l[A]){var n=this._l[A];setTimeout(function(){var A,o;for(A=0;A<n.length;A++)(o=n[A])(e)},0),delete this._l[A]}},f._q.push(function(){r.addTest=i}),f.addAsyncTest(function(){function A(A,e,n){function o(e){var o=e&&"load"===e.type?1==t.width:!1,a="webp"===A;i(A,a&&o?new Boolean(o):o),n&&n(e)}var t=new Image;t.onerror=o,t.onload=o,t.src=e}var e=[{uri:"data:image/webp;base64,UklGRiQAAABXRUJQVlA4IBgAAAAwAQCdASoBAAEAAwA0JaQAA3AA/vuUAAA=",name:"webp"},{uri:"data:image/webp;base64,UklGRkoAAABXRUJQVlA4WAoAAAAQAAAAAAAAAAAAQUxQSAwAAAABBxAR/Q9ERP8DAABWUDggGAAAADABAJ0BKgEAAQADADQlpAADcAD++/1QAA==",name:"webp.alpha"},{uri:"data:image/webp;base64,UklGRlIAAABXRUJQVlA4WAoAAAASAAAAAAAAAAAAQU5JTQYAAAD/////AABBTk1GJgAAAAAAAAAAAAAAAAAAAGQAAABWUDhMDQAAAC8AAAAQBxAREYiI/gcA",name:"webp.animation"},{uri:"data:image/webp;base64,UklGRh4AAABXRUJQVlA4TBEAAAAvAAAAAAfQ//73v/+BiOh/AAA=",name:"webp.lossless"}],n=e.shift();A(n.name,n.uri,function(n){if(n&&"load"===n.type)for(var o=0;o<e.length;o++)A(e[o].name,e[o].uri)})}),t(),a(s),delete r.addTest,delete r.addAsyncTest;for(var d=0;d<f._q.length;d++)f._q[d]();A.Modernizr=f}(window,document)
</script>

<script type="text/javascript">
	/* Get Viewport Size */
	function getViewportCategory () {
		var viewportSize;
		var w = window.innerWidth;

		if ( w >= 1380 ){ viewportSize = "xlarge"; }
		else if ( w >= 1040 ){ viewportSize = "large"; }
		else if ( w >= 640 ){ viewportSize = "medium"; }
		else { viewportSize = "small"; }
		return viewportSize;
	}
	SETTINGS.VIEWPORT_SIZE = getViewportCategory();
</script>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PK46W6N"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!--  ★  MARKUP GOES HERE  ★  -->

<div id="page-wrapper"><!-- Page Wrapper -->

	<h1 class="visuallyhidden">We make travelling to India on an immersion tour easy.</h1>
	<h2 class="visuallyhidden">We are a single window solution provider specialising in study tours. We can adapt the itinerary of a tour to suit your curriculum. Come experience the language and culture of urban and rural India.</h2>

	<!-- Page Content -->
	<div id="page-content">



		<!-- Navigation Section -->
		<section class="navigation-section js_navigation_section">
			<div class="container">
				<div class="row">
					<div class="mini-menu-button js_modal_trigger" data-mod-id="mini-menu" tabindex="-1">
						<span class="line"></span>
						<span class="line"></span>
						<span class="line"></span>
					</div>
				</div>
				<div class="row">
					<div class="navigation columns small-12 text-center">
						<a class="button js_nav_button js_smooth" href="#home">Home</a>
						<a class="button js_nav_button js_smooth" href="#what_we_do">Why us</a>
						<a class="button js_nav_button js_smooth" href="#tour_packages">Study Programs</a>
						<a class="button js_modal_trigger" data-mod-id="modal-faqs">FAQs</a>
						<a class="button js_nav_button js_smooth" href="#team">Team</a>
						<a class="button button-highlight js_nav_button js_smooth" href="#book_now">Enquire Now</a>
					</div>
				</div>
			</div>
		</section> <!-- END : Navigation Section -->



		<!-- Landing Section -->
		<section class="landing-section fill-dark" id="home">
			<div class="landing-video youtube-embed-bg js_embed_bg_yt">

				<div class="video-fallback js_video_fallback"></div>
				<iframe id="youtube_video_embed" width="1280" height="1600" src="https://www.youtube.com/embed/HbF9dprVnkk?enablejsapi=1&html5=1&mute=1&color=white&controls=0&disablekb=1&fs=0&autoplay=0&loop=0&modestbranding=1&playsinline=1&rel=0&showinfo=0&end=83" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

				<!-- <div class="youtube_embed" data-src="https://www.youtube.com/embed/Scxs7L0vhZ4?rel=0&controls=0&showinfo=0&rel=0&mute=1&autoplay=1&loop=1" style="padding-top: 56.25%; padding-bottom: 0.14%;">
					<div class="youtube_load"></div>
					<iframe width="1280" height="720" src="" frameborder="0" allowfullscreen></iframe>
				</div> -->

				<div class="overlay-pattern"></div>

			</div>
			<div class="landing-playbutton row js_landing_play_button hidden">
				<div class="container">
					<div class="columns small-12 text-center">
						<button class="button fill-teal js_modal_trigger" data-mod-id="tour-film">
							<i class="icon material-icons">play_arrow</i>
						</button>
						<div class="h3 text-uppercase">Watch Me First</div>
					</div>
				</div>
			</div>
			<div class="landing row">
				<div class="logo-strip">
					<div class="logo">
						<img src="img/logo-light.svg?v=191023">
					</div>
				</div>
				<div class="container">
					<div class="columns small-12 text-center">
						<div class="headline h3 cd-headline slide">
							<span>&nbsp; Come experience our</span>
							<span class="cd-words-wrapper">
								<b class="is-visible">people.</b>
								<b>language.</b>
								<b>industry.</b>
								<b>science.</b>
								<b>economy.</b>
								<b>business.</b>
								<b>history.</b>
								<b>villages.</b>
								<b>cities.</b>
								<b>food.</b>
								<b>transport.</b>
								<b>culture.</b>
								<b>politics.</b>
								<b>education.</b>
								<b>nature.</b>
							</span>
						</div>
					</div>
				</div>
			</div>
		</section> <!-- END : Landing Section -->



		<!-- Intro Section -->
		<section class="intro-section js_intro_section" id="what_we_do">
			<div class="container">
				<div class="intro row">

					<div class="headline columns small-10 small-offset-1 medium-9 large-5">
						<div class="h2">We guide students, corporate executives, faculty & professionals on <strong>study-centric, experiential learning programs in urban and rural India</strong>.</div>
						<div class="underline columns small-6 medium-4 large-5"><span class="fill-teal"></span></div>
						<div class="h3 subheading"><strong>Travel. Experience. Learn. Repeat.</strong></div>
						<div class="p">What you can learn from first-hand experiences in a culturally-diverse developing nation like India, is so much more than what textbooks can teach you. We’ve got learning experiences for everyone – students, corporates, faculty & professionals!</div>
					</div>

					<div class="points columns small-10 small-offset-1 large-5 large-offset-0">
						<div class="point row">
							<div class="h3 title columns small-10 small-offset-1">Customised High-Impact Experiences</div>
							<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-teal"></span></div>
							<div class="p description columns small-10 small-offset-1 medium-7 medium-offset-0 large-8">
								What you want is what you get! You could begin your journey at a start-up company in an Urban Metropolis and maybe wind up in a Wildlife Sanctuary – with us, anything’s possible! Our programs are designed to pack a wide variety of experiences into a defined schedule, without compromising quality time to immerse yourself in the experience and learn from it.
							</div>
						</div>

						<div class="point">
							<div class="h3 title columns small-10 small-offset-1">Researched & Handpicked Programs</div>
							<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-teal"></span></div>
							<div class="p description columns small-10 small-offset-1 medium-7 medium-offset-0 large-8">
								What’s trending in urban and rural India? We have dedicated academic and cultural experts skilled in researching trends and current issues that can provide high-impact learning experiences. We work closely with faculty and program leaders, to get more clarity on how best to adapt study experiences to complement specific academic requirements.
							</div>
						</div>

						<div class="point">
							<div class="h3 title columns small-10 small-offset-1">Opportunities to Explore & Co-Learn</div>
							<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-teal"></span></div>
							<div class="p description columns small-10 small-offset-1 medium-7 medium-offset-0 large-8">
								What better way to learn than to co-learn with the best minds! Our experiences give you the freedom to explore a wide range of academic disciplines through our extensive connects in leading corporations and top-ranked academic institutions. With unrestricted access to industry experts and academicians, we offer great opportunities to deepen your understanding of any subject and increase your cultural awareness.
							</div>
						</div>

						<div class="point">
							<div class="h3 title columns small-10 small-offset-1">We’ve Got It Covered</div>
							<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-teal"></span></div>
							<div class="p description columns small-10 small-offset-1 medium-7 medium-offset-0 large-8">
								Planning the A to Z of your study experience in a foreign country is quite challenging; that requires the ‘We’ve Got It Covered’ superpower – and that’s exactly what we have! More than any travel agent, we would know your schedule best – be it airport transfers, local transport, hotel accommodation, breakfast, lunch, dinner, ferry tickets or travel insurance, we’ve got you covered. All you have to do is make the decision to entrust your learning experience to us and immerse yourself in what India can teach you!
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="intro-bg watercolor-taj">
				<picture class="container-img">
					<source src="img/watercolor-taj.webp?v=191023" type="image/webp">
					<img src="img/watercolor-taj.png?v=191023">
				</picture>
			</div>
			<div class="intro-bg watercolor-splash-1">
				<picture class="container-img">
					<source src="img/watercolor-splash-1.webp?v=191023" type="image/webp">
					<img src="img/watercolor-splash-1.png?v=191023">
				</picture>
			</div>
			<!-- <div class="intro-bg watercolor-splash-2">
				<img src="img/watercolor-splash-2.png?v=191023">
			</div> -->
		</section> <!-- END : Intro Section -->



		<!-- Gallery Section -->
		<section class="gallery-section fill-dark">
			<div class="gallery row">

				<!-- slick — Facts Gallery -->
				<div class="facts-gallery">

					<div class="fact">
						<div class="image slide_1"></div>
						<span class="caption-box">
							<div class="container">
								<div class="columns small-10 small-offset-1 medium-8 medium-offset-2 large-6 large-offset-3 xlarge-6 xlarge-offset-3 text-center">
									<span class="p caption">
										ORACLE has a 120,000 global workforce, 31,000 work in India; making it the second largest, after Oracle's 54,000 employee strength in the US. The facility above is in Bangalore, a destination on your tour.
									</span>
								</div>
							</div>
						</span>
					</div>

					<div class="fact">
						<div class="image slide_2"></div>
						<span class="caption-box">
							<div class="container">
								<div class="columns small-10 small-offset-1 medium-8 medium-offset-2 large-6 large-offset-3 xlarge-6 xlarge-offset-3 text-center">
									<span class="p caption">
										India will become the world's youngest country by 2020. By when, the median age in India will be 29. The population in the 15-34 age group is expected to rise from 430 million in 2011 to 464 million in 2021.
									</span>
								</div>
							</div>
						</span>
					</div>

					<div class="fact">
						<div class="image slide_3"></div>
						<span class="caption-box">
							<div class="container">
								<div class="columns small-10 small-offset-1 medium-8 medium-offset-2 large-6 large-offset-3 xlarge-6 xlarge-offset-3 text-center">
									<span class="p caption">
										India's medical tourism industry is set to equal $2 billion. Becoming a travel destination for various procedures, with savings of 60-90%. Harvard affiliated Wockhardt Hospital has performed over 20,000 heart procedures, with 98%+ success rates, surpassing US & EU standards.
									</span>
								</div>
							</div>
						</span>
					</div>

					<div class="fact">
						<div class="image slide_4"></div>
						<span class="caption-box">
							<div class="container">
								<div class="columns small-10 small-offset-1 medium-8 medium-offset-2 large-6 large-offset-3 xlarge-6 xlarge-offset-3 text-center">
									<span class="p caption">
										India is the world's second-largest English speaking country. India is second only to the USA when it comes to speaking English with around 125 million people speaking the language, which is only 10% of our population.
									</span>
								</div>
							</div>
						</span>
					</div>

					<div class="fact">
						<div class="image slide_5"></div>
						<span class="caption-box">
							<div class="container">
								<div class="columns small-10 small-offset-1 medium-8 medium-offset-2 large-6 large-offset-3 xlarge-6 xlarge-offset-3 text-center">
									<span class="p caption">
										The United States is the world’s largest exporter of Corn. You will be visiting a few places in South India, which make it the largest exporter of Rice, pushing 10.25 million tonnes annually.
									</span>
								</div>
							</div>
						</span>
					</div>

				</div> <!-- END : slick -->

			</div>
		</section> <!-- END : Gallery Section -->



		<!-- Quote Section -->
		<section class="quote-section fill-teal">
			<div class="container">
				<div class="row">
					<div class="columns small-10 small-offset-1">
						<div class="quote h2 text-center">
							<strong>“</strong>When overseas you learn more about your own country, than you do the place you’re visiting.<strong>”</strong>
							<span class="person no-wrap">– Clint Borgen.</span>
						</div>
					</div>
				</div>
			</div>
		</section> <!-- END : Quote Section -->



		<!-- Video Section -->
		<section class="video-section fill-dark hidden js_video_section">
			<div class="video-container">
				<video id="tour_summary_video" class="" src="video/landing-video.mp4?v=191023" muted webkit-playsinline playsinline loop></video>
				<div class="video-poster js_video_poster" data-for="tour_summary_video"></div>
				<div class="overlay-pattern"></div>
				<button class="video-controls js_video_controls" data-for="tour_summary_video">
					<i class="icon material-icons">play_circle_outline</i>
				</button>
			</div>
		</section> <!-- END : Video Section -->



		<!-- Packages Section -->
		<section class="packages-section" id="tour_packages">
			<div class="container">
				<div class="packages row">
					<div class="h1 headline text-uppercase columns small-10 small-offset-1">Study Programs</div>
					<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-pink"></span></div>
					<div class="h3 title text-pink columns small-10 small-offset-1 medium-7 medium-offset-0 large-5">
						Trending Innovations and <br>Business Experience Programs
					</div>
					<div class="p description text-dark columns small-10 small-offset-1 medium-7 medium-offset-4 medium-offset-0 large-5 large-offset-3">
						<br>We believe that an exposure to India's business, Culture and History serves as an eye-opener to understanding this magnificent country . It also gives you a heightened sense of cultural awareness that helps put the prevalent global environment in perspective. Give us two weeks and we guarantee you’ll go back with a wealth of academic, experiential and cultural experiences that will change the way you view the world. <br><br>
						From seeing to learning to applying to innovating… your journey begins with us! <br>
					</div>
				</div>
			</div>
			<!-- Packages List -->
			<div class="container container-package natural-scroll">
				<div class="packages row">
					<div class="packages-list natural-list js_packages_list columns small-12 large-10 large-offset-1">

						<?php foreach ( $packages as $package ) : ?>
							<?php
								$description = preg_replace( '/\R/', '<br>', $package->description );
								$image = $package->locations[ 0 ]->image ?: '';
								if ( ! empty( $image ) )
									$image = '/uploads/' . $image;
							?>
							<div class="package js_package" tabindex="0">
								<div class="days-title fill-dark h4 text-uppercase"><?php echo $package->label ?></div>
								<div class="alt-thumbnail block" style="background-image: url( '<?php echo $image ?>');"></div>
								<!-- <div class="thumbnail">
									<?php /* foreach ( $package->locations as $location ) : ?>
										<?php
											if ( empty( $location->image ) ) {
												$imageURL_A = '/img/placeholder.png?v=191023';
												$imageURL_B = '/img/placeholder.webp';
											} else {
												$imageURL_A = '/uploads/thumbnails/' . $location->image;
												$imageURL_B = '/uploads/thumbnails/' . preg_replace( '/\.(jpe?g|png)$/', '.webp', $location->image );
											}
										?>
										<div class="day" data-day="<?php echo $location->days ?>" data-bg-img="<?php echo $imageURL_A ?>" style="--number-of-days: <?php echo $location->days ?>;">
											<div class="location h4">
												<span class="days-label text-uppercase fill-pink inline"><?php echo $location->days ?> Days</span>
												<span class="location-label text-cursive h3 inline">in <?php echo $location->city ?></span>
											</div>
										</div>
									<?php endforeach; */ ?>
								</div> -->
								<div class="h3 title"><?php echo $package->title ?></div>
								<div class="hidden h4 price text-pink"><span class="h3"><?php echo $package->price ?></span> per person</div>
								<div class="p description"><?php echo $description ?></div>
								<button class="button block fill-pink js_get_schedule" href="#" data-title="<?php echo $package->title ?>">
									<span>Customize Your Program</span>
									<!-- <i class="icon material-icons">attach_file</i> -->
								</button>
							</div>
						<?php endforeach; ?>

					</div>
				</div>
			</div>

			<div class="packages-bg watercolor-train">
				<picture class="container-img">
					<source srcset="img/watercolor-train.webp?v=191023" type="image/webp">
					<img src="img/watercolor-train.png?v=191023">
				</picture>
			</div>

			<div class="packages-bg watercolor-splash-3">
				<picture class="container-img">
					<source srcset="img/watercolor-splash-3.webp?v=191023" type="image/webp">
					<img src="img/watercolor-splash-3.png?v=191023">
				</picture>
			</div>
		</section> <!-- END : Packages Section -->



		<!-- Tours Section -->
		<section class="tours-section fill-off-neutral" id="tours">
			<div class="container">
				<div class="tours row">
					<div class="h1 headline text-uppercase columns small-10 small-offset-1">Journals</div>
					<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-teal"></span></div>
				</div>
			</div>
			<!-- Tours List -->
			<div class="container container-tours natural-scroll">
				<div class="tours row">
					<!-- DO NOT ACCIDENTALLY DELETE THIS RAWR~! -->
					<div class="tours-list natural-list js_tours_list columns small-12 large-10 large-offset-1">

						<?php foreach ( $tours as $index => $tour ) : ?>
							<?php
								$description = preg_replace( '/\R/', '<br>', $tour[ 'description' ] );
								$number_of_days = count( $tour[ 'days' ] );
								// $duration_animation = ( $duration_visible + $duration_crossfade ) * $number_of_days;
								$tourHighlightImages = [ ];
								foreach ( $tour[ 'days' ] as $day ) {
									$dayHighlightImage = $day[ 'gallery' ][ 0 ][ 'image' ];
									if ( $dayHighlightImage )
										$tourHighlightImages[ ] = $dayHighlightImage;
								}
								if ( count( $tourHighlightImages ) == 1 ) {
									$tourHighlightImages = array_slice( array_column( $tour[ 'days' ][ 0 ][ 'gallery' ], 'image' ), 0, 5 );
								}
								if ( ! count( $tourHighlightImages ) )
									$tourHighlightImages[ ] = '/img/placeholder.png';
								else {
									$tourHighlightImages = array_map( function ( $image ) {
										return '/uploads/thumbnails/' . $image;
									}, $tourHighlightImages );
								}
							?>
							<div class="tour js_tour js_modal_trigger cursor-pointer" data-mod-id="<?php echo $tour[ 'id' ] ?>" tabindex="0">

								<div class="thumbnail">
									<div class="slides js_slides">
										<?php foreach ( $tourHighlightImages as $image ) : ?>
											<div class="slide" style="background-image: url( '<?php echo $image ?>' )"></div>
										<?php endforeach; ?>
									</div>
									<div class="time hidden">
										<span class="text-uppercase fill-teal"><?php echo $number_of_days ?> Days</span>
										<div class="h3 text-cursive"><?php echo date( 'F Y', strtotime( $tour[ 'start_date' ] ) ) ?></div>
									</div>
								</div>
								<div class="hidden h3 title"><?php echo $tour[ 'organisation' ] ?></div>
								<div class="p excerpt"><?php echo $description ?></div>
							</div>
						<?php endforeach; ?>

					</div>

				</div>
			</div>
		</section> <!-- END : Tours Section -->



		<!-- Quote Section -->
		<section class="quote-section fill-pink">
			<div class="container">
				<div class="row">
					<div class="columns small-10 small-offset-1">
						<div class="quote h2 text-center">
							<strong>“</strong>One’s destination is never a place, but rather a new way of looking at things.<strong>”</strong>
							<span class="person no-wrap">– Henry Miller.</span>
						</div>
					</div>
				</div>
			</div>
		</section> <!-- END : Quote Section -->



		<!-- Booking Section -->
		<section class="booking-section" id="book_now">
			<div class="container">
				<div class="booking row">

					<div class="h1 headline text-uppercase columns small-10 small-offset-1">Customize Your Program</div>
					<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-pink"></span></div>
					<div class="booking-form columns small-10 small-offset-1 large-8 large-offset-0">
						<div class="row">
							<form id="js_form_book_tour" class="form">
								<div class="form-item columns small-12 medium-6">
									<label class="block required" for="booking_name">Full Name</label>
									<input class="block js_name" name="booking_name" id="booking_name" type="text">
								</div>
								<div class="form-item columns small-12 medium-6">
									<label class="block required" for="booking_email">Email ID</label>
									<input class="block js_email_address" name="booking_email" id="booking_email" type="email">
								</div>
								<div class="form-item columns small-12 medium-6">
									<label class="block" for="booking_phone">Phone</label>
									<input class="block js_phone_number" name="booking_phone" id="booking_phone" type="text">
								</div>
								<div class="form-item columns small-12 medium-6">
									<label class="block" for="booking_institute">College / University</label>
									<input class="block js_institute" name="booking_institute" id="booking_institute" type="text">
								</div>
								<div class="form-item columns small-12 medium-6">
									<label class="block" for="form_booking_package">Pick a Theme</label>
									<select class="block js_form_booking_package" name="booking_package" id="form_booking_package">
										<option value="" disabled="disabled" selected="true">Select</option>
										<?php /* foreach ( $packages as $package ) : ?>
											<option value="<?php echo $package->title ?>"><?php echo $package->label . ' [ ' . $package->price . ' ]' ?></option>
										<?php endforeach; */ ?>
										<?php foreach ( $packages as $package ) : ?>
											<option value="<?php echo $package->title ?>"><?php echo $package->label ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="form-item columns small-12 medium-6">
									<label class="block" for="tour_date">Suggest a tour Date</label>
									<input class="block js_tour_date" name="tour_date" id="tour_date" type="date">
								</div>
								<div class="form-item columns small-12 medium-6 medium-offset-6">
									<label for="booking_submit" class="invisible">Submit</label>
									<input class="fill-pink block js_form_submit" name="booking_submit" id="booking_submit" type="submit" value="Submit">
								</div>
							</form>
						</div>
					</div>

					<!-- email action -->
					<a href="mailto:vineeth@immersionindia.com" class="email-action columns small-10 small-offset-1 large-8 large-offset-3 js_enquire_email">
						<div class="icon inline-bottom">
							<img class="block" src="img/icon-color-email.svg?v=191023">
						</div>
						<div class="inline-bottom">
							<span class="block">or, drop us an email at&hellip;</span>
							<span class="block email-id"><span class="text-underline">vineeth</span>@immersionindia.com</span>
						</div>
					</a>

				</div>
			</div>
		</section> <!-- END : Booking Section -->



		<!-- Team Section -->
		<section class="team-section fill-dark" id="team">
			<div class="container">
				<div class="team row">

					<div class="h1 headline columns small-10 small-offset-1 text-uppercase">Meet The Team</div>
					<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-orange"></span></div>
					<div class="p description columns small-10 small-offset-1 medium-10 large-6 large-offset-0">
						A group of experienced, fun to work with, customer-focused individuals – we’ve got heaps of great ideas that take the shape of great learning experiences. We’re passionate about what we do and determined to deliver the best experiential study programs that showcase India’s brilliant urban and rural potential.
						<br><br>
						The leadership team has significant experience in the education sector, complemented by long-standing associations with top-ranked educational institutions. We have also worked with foreign universities, and played a decisive role in curating partnerships and experiences with their Indian counterparts.
						<br><br>
						The long-term working partnerships we’ve forged with many of our clients stand testament to the seamless study programs we’ve curated. Our experiences are completely flexible and we are happy to tackle any aspect of your visit, right from managing the whole trip to simply giving you an experienced set of hands on site.
						<br><br>
						Team up with us and ‘Let India Happen To You’!
					</div>

					<div class="profile clearfix">
						<div class="picture columns small-10 small-offset-1 medium-4 large-3 large-offset-3 float-left">
							<picture>
								<img class="block" src="img/portrait_vineeth.jpg?v=2.6.3">
							</picture>
						</div>
						<div class="columns small-10 small-offset-1 medium-6 medium-offset-0 large-5">
							<div class="h2 name">Vineeth Thomas</div>
							<div class="p text-uppercase designation">Founder &amp; Managing Partner</div>
						</div>
						<!-- <div class="p description columns small-10 small-offset-1 medium-6 medium-offset-0 large-5 large-offset-0">
							I am a business graduate with experience in International Education and Exchange. I have developed strategic global opportunities with foreign universities, keeping in mind a student's needs and interests. Before joining the education space, I worked with the Tata Group’s IT Division, on their Business Development team. When I am not working, I prefer studying photography, history, cultural intelligence or just traveling.
						</div> -->
					</div>

					<div class="profile clearfix">
						<div class="picture columns small-10 small-offset-1 medium-4 large-3 large-offset-3 float-left">
							<picture>
								<img class="block" src="img/portrait_anand.jpg?v=2.6.3">
							</picture>
						</div>
						<div class="columns small-10 small-offset-1 medium-6 medium-offset-0 large-5">
							<div class="h2 name">Anand Joseph</div>
							<div class="p text-uppercase designation">Founder &amp; Partner</div>
						</div>
						<!-- <div class="p description columns small-10 small-offset-1 medium-6 medium-offset-0 large-5 large-offset-0">
							I am a business graduate. When I was a student intern, I had traveled extensively through the United States to spend time at various universities there. I currently spend time building rapport with students to understand their needs and aspirations, before counseling them on their career path. I stay with my family in Bangalore, India. Things that keep me busy otherwise are a keen interest in music, travel and keeping up with the capital markets.
						</div> -->
					</div>

				</div>
			</div>
		</section> <!-- END : Team Section -->



		<!-- Footer Section -->
		<section class="footer-section fill-dark">
			<div class="container">
				<div class="footer row">
					<div class="logo">
						<div class="columns small-6 small-offset-1 medium-3 large-offset-3"><img class="block" src="img/logo-light-full.svg?v=191023"></div>
					</div>
					<div class="columns small-10 small-offset-1 medium-4 large-3 large-offset-3">
						<a class="block js_enquire_phone" href="tel:+919591658632">
							<img class="inline-bottom" src="img/icon-phone.svg?v=191023">
							<span class="inline-bottom" style="position: relative;">+91 95916 58632
								<small class="block" style="position: absolute; color: #7C7577; font-size: 1rem; white-space: nowrap; line-height: 0; left: 2px; bottom: -10px;">Also on WhatsApp &amp; FaceTime.</small>
							</span>
						</a>
						<a class="block js_enquire_email" href="mailto:vineeth@immersionindia.com"><img class="inline-bottom" src="img/icon-email.svg?v=191023"><span class="inline-bottom">vineeth@immersionindia.com</span></a>
					</div>
					<div class="address columns small-10 small-offset-1 medium-6 medium-offset-0 large-4">
						<a class="block" href="https://in.linkedin.com/company/immersion-india" target="_blank"><img class="inline-bottom" src="img/icon-linkedin.svg?v=191023"><span class="inline-bottom">Connect on LinkedIn</span></a>
						<a class="block" href="https://goo.gl/maps/F8LD2dLLjZ22" target="_blank"><img class="inline-bottom" src="img/icon-gmaps.svg?v=191023"><span class="inline-bottom">Immersion India.</span></a>
						<div class="block address">
							303, Milwaukee, 40 Promenade Road, Frazer Town.
							Bangalore—560005. Karnataka. India.
						</div>
					</div>
				</div>
			</div>

			<!-- Version -->
			<div class="text-center">
				<span class="inline" style="font-size: 1.1rem; padding: 15px 0;">&lt; Ver. 3.0 &gt; &nbsp; Updated on 24<sup>th</sup> October 2019</span>
				<a class="lazaro-disclaimer js_modal_trigger" data-mod-id="disclaimer" href="">
			</div>
		</section> <!-- END : Footer Section -->



	</div> <!-- END : Page Content -->


	<!-- Lazaro Signature -->
	<?php lazaro(); ?>
	<!-- END : Lazaro Signature -->

</div><!-- END : Page Wrapper -->









<!-- ⬇ All Modals below this point ⬇ -->
<div id="modal-wrapper"><!-- Modal Wrapper -->

	<!-- Modal Close Button -->
	<div class="modal-close js_modal_close" tabindex="0">&times;</div>

	<!-- Modal Box -->
	<div class="modal-box js_modal_box" <?php if ( $view ) : ?> style="display: block;" <?php endif; ?> >

		<!-- Modal Content : Sample Video -->
		<div class="modal-box-content js_modal_box_content" data-mod-id="sample-video">
			<div class="container">
				<div class="row">
					<div class="columns small-12">
						<!-- video embed -->
						<div class="youtube_embed" data-src="https://www.youtube.com/embed/lncVHzsc_QA?rel=0&showinfo=0">
							<div class="youtube_load"></div>
							<iframe width="1280" height="720" src="" frameborder="0" allowfullscreen></iframe>
						</div>
					</div>
				</div>
			</div>
		</div><!-- END : Sample Video -->


		<!-- Modal Content : Mini Menu -->
		<div class="modal-box-content js_modal_box_content" data-mod-id="mini-menu">
			<div class="container">
				<div class="row">
					<div class="navigation">
						<a class="button columns small-12 text-center js_nav_button js_smooth active" href="#home">Home</a>
						<a class="button columns small-12 text-center js_nav_button js_smooth" href="#what_we_do">Why Us</a>
						<a class="button columns small-12 text-center js_nav_button js_smooth" href="#tour_packages">Study Programs</a>
						<a class="button columns small-12 text-center js_nav_button js_smooth" href="#team">Team</a>
						<a class="button button-highlight columns small-12 text-center js_nav_button js_smooth" href="#book_now">Enquire Now</a>
					</div>
				</div>
			</div>
		</div><!-- END : Sample Form -->


		<!-- Modal Content : Disclaimer -->
		<div class="modal-box-content js_modal_box_content" data-mod-id="disclaimer">
			<div class="container">
				<?php lazaro_disclaimer(); ?>
			</div>
		</div><!-- END : Disclaimer -->


		<!-- Modal Content : Tour Film -->
		<div class="modal-box-content js_modal_box_content" data-mod-id="tour-film">
			<div class="container">
				<div class="row">
					<div class="columns small-12">
						<!-- video embed -->
						<div class="youtube_embed">
							<div class="youtube_load"></div>
							<iframe class="js_tour_film_embed" width="1280" height="720" src="https://www.youtube.com/embed/AwgsK30aFl8?enablejsapi=1&html5=1&color=white&disablekb=1&modestbranding=1&playsinline=0&rel=0&showinfo=0" frameborder="0" allowfullscreen></iframe>
						</div>
					</div>
				</div>
			</div>
		</div><!-- END : Tour Film -->


		<!-- Modal Content : Frequently Asked Questions (FAQs) -->
		<div class="modal-box-content js_modal_box_content <?php if ( $view == 'faqs' ) : ?> active <?php endif; ?>" data-mod-id="modal-faqs">
			<div class="container">
				<div class="faqs-modal row">

					<div class="headline h1 columns small-10 small-offset-1">Frequently Asked Questions.</div>

					<div class="title h2 columns small-10 small-offset-1">General Info.</div>
					<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-orange"></span></div>
					<div class="columns small-10 small-offset-1 large-8 large-offset-0">
						<div class="question">
							What is the minimum group size required for a tour package to be feasible?
						</div>
						<div class="answer">
							The minimum group size required for us to offer the package is ten tour participants.
						</div>

						<div class="question">
							Will international airfare be covered?
						</div>
						<div class="answer">
							International airfare has not been factored into the tour package’s cost.
						</div>

						<div class="question">
							Does the overall tour package's charge include entry fees to tourist locations, museums and heritage sites?
						</div>
						<div class="answer">
							Yes, it includes entry fees to tourist sites, museums and palace as part of the overall tour package cost.
						</div>

						<div class="question">
							Will the students have access to the internet and telephone?
						</div>
						<div class="answer">
							Students will have unlimited access to the web at the hotels and places of stay. Further, students will also be provided with a local sim on request, at an additional cost for making calls.
						</div>

						<div class="question">
							Will local transport be covered?
						</div>
						<div class="answer">
							All transportation including airport transfers and local transport is provided for by Immersion India - included as part of the overall tour package cost.
						</div>
					</div>


					<div class="title h2 columns small-10 small-offset-1">Accommodation &amp; Food.</div>
					<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-orange"></span></div>
					<div class="columns small-10 small-offset-1 large-8 large-offset-0">
						<div class="question">
							What type of accommodation can we expect?
						</div>
						<div class="answer">
							All accommodation will be at leading 3 or 4-star hotels. Standard room accommodation will typically be for double occupancy. In case a single occupancy is preferred, additional charges for the same will be applicable.
						</div>

						<div class="question">
							If at all, what are the extra expenditures that can be drawn up against an individuals hotel billing?
						</div>
						<div class="answer">
							Personal preferences/requirements, like - Laundry, extra meals, in-room dining, use of Spa, Business Centre and Tips, will be billed as additional amounts.
						</div>

						<div class="question">
							Will all meals be provided during this tour?
						</div>
						<div class="answer">
							The standard tour package includes breakfast, lunch and dinner. However, any extra food that you may order will be an additional amount.<br>

							Alternatively, we also have a Breakfast &amp; Lunch only option - where Dinner is not possible because of an expedition.
						</div>
					</div>

					<div class="title h2 columns small-10 small-offset-1">Safety &amp; Precautions.</div>
					<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-orange"></span></div>
					<div class="columns small-10 small-offset-1 large-8 large-offset-0">
						<div class="question">
							Is India a safe country?
						</div>
						<div class="answer">
							For its size, India has a very low crime rate and is a safe country. However, as with any international travel, please be aware of your surroundings. Albeit, make sure your purse is secure, and wallets are in sealed pockets.
						</div>

						<div class="question">
							What are the safety precautions ensured on this tour?
						</div>
						<div class="answer">
							•	Travel insurance covers all tour participants for the duration of their stay in India.<br>
							•	Local transportation will be on air-conditioned coaches provided by a government recognised travel agency.<br>
							•	The tour group will be accompanied by three responsible professionals: A local guide, security guard and a tour coordinator.
						</div>
					</div>

					<div class="title h2 columns small-10 small-offset-1">Booking &amp; Pricing.</div>
					<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-orange"></span></div>
					<div class="columns small-10 small-offset-1 large-8 large-offset-0">
						<div class="question">
							How do I make a booking with Immersion India?
						</div>
						<div class="answer">
							You can make a booking by filling in our prescribed “booking form”, that is available on our website. Our representative will then contact you and walk you through the tour schedule and incorporate any customisation you may require. On confirmation of your specifics on this tour schedule, you will receive a correlated price estimate. Further, your booking will then be duly confirmed once you pay a specified advance towards the Booking Fee.
						</div>

						<div class="question">
							What is the payment schedule and cancellation policy?
						</div>
						<div class="answer">
							•	Booking Fee : At the time of making the booking — <em><u>No Refund.</u></em><br>
							•	First Payment : 60 days before arrival — <em><u>75% Refund if cancelled 25 days in advance.</u></em><br>
							•	Final Payment : 10 days before arrival — <em><u>No Refund.</u></em>
						</div>
					</div>

					<div class="title h2 columns small-10 small-offset-1">Travel Visa.</div>
					<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-orange"></span></div>
					<div class="columns small-10 small-offset-1 large-8 large-offset-0">
						<div class="question">
							Do I require a Visa to travel to India?
						</div>
						<div class="answer">
							Yes. You do require a Visa to travel to India.
						</div>
					</div>
				</div>
			</div>
		</div><!-- END : Frequently Asked Questions (FAQs) -->



		<!-- Modal Content : Tour Post Ref -->
		<div class="modal-box-content js_modal_box_content" data-mod-id="tour-post">
			<div class="container">
				<div class="tour-post row">

					<div class="headline h1 text-cursive columns small-10 small-offset-1">April 2017</div>

					<div class="title h2 columns small-10 small-offset-1">Univeristy of Oaklahoma</div>
					<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-orange"></span></div>
					<div class="description h4 columns small-10 small-offset-1 large-8 large-offset-0">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
						tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
						quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
						consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
						cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
						proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
					</div>

					<div class="day columns small-10 small-offset-1 large-8 large-offset-3">
						<div class="day-count h3">Day 1</div>
						<div class="day-gallery">
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
						</div>
					</div>

					<div class="day columns small-10 small-offset-1 large-8 large-offset-3">
						<div class="day-count h3">Day 2</div>
						<div class="day-gallery">
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
						</div>
					</div>

					<div class="day columns small-10 small-offset-1 large-8 large-offset-3">
						<div class="day-count h3">Day 3</div>
						<div class="day-gallery">
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
							<div class="figure" tabindex="0">
								<div class="info">
									<span class="location">Bangalore</span>
									<span class="caption">This is a sample caption.</span>
								</div>
								<img src="https://via.placeholder.com/300x300">
							</div>
						</div>
					</div>

					<div class="lazy-day columns small-10 small-offset-1 large-8 large-offset-3">
						<button class="button button-small fill-pink">
							<span>Day 4</span>
							<i class="icon material-icons">sync</i>
						</button>
						<button class="button button-small fill-pink">
							<span>Day 5</span>
							<i class="icon material-icons">sync</i>
						</button>
						<button class="button button-small fill-pink">
							<span>Day 6</span>
							<i class="icon material-icons">sync</i>
						</button>
						<button class="button button-small fill-pink">
							<span>Day 7</span>
							<i class="icon material-icons">sync</i>
						</button>
						<button class="button button-small fill-pink">
							<span>Day 8</span>
							<i class="icon material-icons">sync</i>
						</button>
						<button class="button button-small fill-pink">
							<span>Day 9</span>
							<i class="icon material-icons">sync</i>
						</button>
						<button class="button button-small fill-pink">
							<span>Day 10</span>
							<i class="icon material-icons">sync</i>
						</button>
						<button class="button button-small fill-pink">
							<span>Day 11</span>
							<i class="icon material-icons">sync</i>
						</button>
					</div>

				</div>
			</div>
		</div><!-- END : Frequently Asked Questions (FAQs) -->

		<?php foreach ( $tours as $index => $tour ) : ?>
			<!-- Modal Content : Tour Post -->
			<div class="modal-box-content js_modal_box_content <?php if ( $view == $tour[ 'id' ] ) : ?> active <?php endif; ?>" data-mod-id="<?php echo $tour[ 'id' ] ?>" data-org="<?php echo $tour[ 'organisation' ] ?>">
				<div class="container">
					<div class="row">
						<div class="columns small-10 small-offset-1 medium-5 large-4">
							<img src="img/logo-light.svg?v=191023" style="margin-bottom: 40px;">
						</div>
					</div>

					<div class="tour-post row">
						<div class="hidden headline h1 text-cursive columns small-10 small-offset-1"><?php echo date( 'F Y', strtotime( $tour[ 'start_date' ] ) ) ?></div>

						<div class="title h2 columns small-10 small-offset-1"><?php echo $tour[ 'organisation' ] ?></div>
						<div class="underline columns small-5 small-offset-1 medium-3 large-2"><span class="fill-teal"></span></div>
						<div class="description h4 columns small-10 small-offset-1 large-8 large-offset-0 text-neutral"><?php echo $description ?>
							<div class="block share-url js_share_url_widget">
								<span class="share-url-title label inline-middle text-teal js_share_url_title">Share</span>
								<i class="icon material-icons inline-middle text-teal">link</i>
								<a class="share-url-label p inline-middle js_share_url" href="/?view=<?php echo $tour[ 'id' ] ?>#tours" target="_blank"><?php echo $_SERVER[ 'SERVER_NAME' ] ?>/?view=<?php echo $tour[ 'id' ] ?>#tours</a>
								<textarea class="visuallyhidden js_share_url_text"><?php echo $_SERVER[ 'SERVER_NAME' ] ?>/?view=<?php echo $tour[ 'id' ] ?>#tours</textarea>
							</div>
						</div>

						<div class="js_days_container">

							<?php if ( $view == $tour[ 'id' ] ) : ?>

							<?php
								$number_of_days = count( $tour[ 'days' ] );
								if ( $number_of_days == 0 ) {
									$minimum_days = [ ];
								}
								if ( $number_of_days < 3 ) {
									$minimum_days = range( 0, $number_of_days - 1 );
								} else {
									$minimum_days = range( 0, 2 );
								}
							?>
							<?php foreach ( $minimum_days as $index ) : ?>
								<?php
									$description = preg_replace( '/\R/', '<br>', $tour[ 'days' ][ $index ][ 'description' ] );
								?>
								<div class="day columns small-10 small-offset-1 large-8 large-offset-3 js_day">
									<div class="day-count h3 text-teal">Day <?php echo $index + 1 ?></div>
									<div class="day-description h4 text-cursive"><?php echo $description ?></div>
									<div class="day-gallery js_day_gallery">
										<?php foreach ( $tour[ 'days' ][ $index ][ 'gallery' ] as $image ) : ?>
											<div class="figure js_day_photo_frame" data-url="<?php echo $image[ 'image' ] ?>" tabindex="0">
												<div class="info">
													<span class="location"><?php echo $image[ 'location' ] ?></span>
													<span class="caption"><?php echo $image[ 'caption' ] ?></span>
												</div>
												<picture>
												<?php if ( empty( $image[ 'image' ] ) ) : ?>
													<img src="/img/placeholder.png?v=191023">
												<?php else : ?>
													<?php
														$baseImageURL = '/uploads/thumbnails/' . pathinfo( $image[ 'image' ] )[ 'filename' ];
														$fallbackImageURL = '/uploads/thumbnails/' . $image[ 'image' ];
													?>
														<source srcset="<?php echo $baseImageURL . '.webp' ?>" type="image/webp">
														<img src="<?php echo $fallbackImageURL ?>">
												<?php endif; ?>
												</picture>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endforeach; ?>

							<?php endif; ?>

						</div>

						<div class="columns small-10 small-offset-1 large-8 large-offset-3 js_text_load_images" style="display: none">
							<div class="h3 text-light">Load More Images</div>
							<hr>
							<br>
						</div>
						<div class="lazy-day columns small-10 small-offset-1 large-8 large-offset-3 js_lazy_day">
							<?php foreach ( $tour[ 'days' ] as $index => $day ) : ?>
								<?php if ( $view == $tour[ 'id' ] && $index < 3 ) : ?>
								<?php else : ?>
									<button class="button button-small fill-teal js_gallery_day_select" data-day-num="<?php echo $index + 1 ?>">
										<span>Day <?php echo $index + 1 ?></span>
										<i class="icon material-icons">sync</i>
									</button>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>

					</div>
				</div>
			</div><!-- END : Frequently Asked Questions (FAQs) -->
		<?php endforeach; ?>

	</div>

	<!-- Modal Modal Box ( i.e. inner modals ) -->
	<div class="modal-modal-box js_modal_modal_box">

		<!-- Modal Content : Slideshow Carousel -->
		<div class="modal-slideshow-carousel modal-box-content js_modal_box_content" data-mod-id="slideshow-carousel">
			<div class="container-photo-frame">
				<div class="photo-frame js_photo_frame">
					<div class="info js_info">
						<div class="location js_location"></div>
						<div class="caption js_caption"></div>
					</div>
				</div>
			</div>
			<div class="navigation">
				<button class="arrow prev js_prev"></button>
				<button class="arrow next js_next"></button>
			</div>
		</div>
		<!-- END : Slideshow Carousel -->

	</div>

</div><!-- END : Modal Wrapper -->


<!--  ☠  TEMPLATES BEGIN HERE  ☠  -->

<template class="tmpl_day_in_gallery">
	<div class="day columns small-10 small-offset-1 large-8 large-offset-3 js_day">
		<div class="day-count h3 text-teal js_day_num"></div>
		<div class="day-description h4 text-cursive js_day_description"></div>
		<div class="day-gallery js_day_gallery"></div>
	</div>
</template>
<template class="tmpl_image_in_gallery">
	<div class="figure js_day_photo_frame" tabindex="0">
		<div class="info">
			<span class="location js_day_location"></span>
			<span class="caption js_day_caption"></span>
		</div>
		<picture>
			<source class="js_day_image" type="image/webp" srcset="img/placeholder.webp">
			<img class="js_day_image" src="img/placeholder.png">
		</picture>
	</div>
</template>

<!--  ☠  TEMPLATES ENDS HERE  ☠  -->

<!--  ☠  MARKUP ENDS HERE  ☠  -->









<!-- JS Modules -->
<script type="text/javascript" src="js/modules/navigation.js?v=191023"></script>
<script type="text/javascript" src="js/modules/video_embed.js?v=191023"></script>
<script type="text/javascript" src="js/modules/modal_box.js?v=191023"></script>
<script type="text/javascript" src="js/modules/smoothscroll.js?v=191023"></script>
<script type="text/javascript" src="js/modules/form.js?v=191023"></script>
<script type="text/javascript" src="js/modules/analytics.js?v=191023"></script>
<script type="text/javascript" src="plugins/slick/slick.min.js?v=191023"></script>
<script type="text/javascript" src="plugins/cd-headline/js/main.js?v=191023"></script>

<script type="text/javascript">

<?php /* DATA */ ?>
var __DATA__ = { };
__DATA__.TOURS = <?php echo json_encode( $tours ) ?>;

// JAVASCRIPT GOES HERE
$(document).ready(function(){
	/*
	 *
	 * Lazaro Disclaimer
	 *
	 */

	 	// show the disclaimer if the user has not seen it
		function showDisclaimer () {
			// if the disclaimer has already been viewed, do not proceed
			try {
				if ( sessionStorage.getItem( "first_time_this_session" ) ) {
					return;
				}
			} catch ( e ) {}

			// show the disclaimer
			$('.lazaro-disclaimer').trigger("click");
			$('.js_modal_close').hide();

			$('.js_laz_agree').on('click', function () {

				// record the fact that the disclaimer has been viewed
				try {
					sessionStorage.setItem( "first_time_this_session", true );
				}
				catch ( e ) {}

				$('.js_modal_close').trigger("click").show();

			} );
		};
		// showDisclaimer();

	/* -- END: Lazaro Disclaimer-- */



	/*
	 *
	 * Landing Video Fallback Strategy
	 *
	 * On mobile and tablet devices, show an image first.
	 * If the video plays, then fade it out.
	 *
	 * The video is played through the YouTube API which triggers an event
	 * 	when the video starts playing. This function is called at that point.
	 *
	 */
	 	var $landingVideoFallback = $( ".js_video_fallback" );
	 	function hideFallbackImage ( event ) {
			$landingVideoFallback.addClass( "fade-out js_hidden" );
		}
		// Expose it globally
		window.hideFallbackImage = hideFallbackImage;





	/*
	 *
	 * Tour Film Modal
	 *
	 * On opening the modal, autoplay the YouTube video
	 *
	 */
	function setupTourFilm () {
		new YT.Player( $( ".js_tour_film_embed" ).get( 0 ), {
			events: {
				onReady: function ( event ) {
					// Show the play button graphic
					$( ".js_landing_play_button" ).removeClass( "hidden" );
					$( document ).on( "modal/open/tour-film", function () {
						if (
							event.data != YT.PlayerState.ENDED
							&& event.data != YT.PlayerState.PAUSED
						)
							event.target.playVideo();
					} );
					$( document ).on( "modal/close/tour-film", function () {
						event.target.pauseVideo();
					} );
				}
			}
		} );
	}
	$( document ).one( "youtube-api/ready", setupTourFilm );






	/*
	 *
	 * Landing Video Fallback Strategy #2
	 *
	 * On mobile devices, if the user does not see the video as he/she scrolls
	 * past the fold, then have the video show up later in a section of its own
	 *
	 */
	 	if ( SETTINGS.VIEWPORT_SIZE == "small" ) {
	 		$( document ).on( "click", ".js_video_controls", function ( event ) {
	 			var $playbackControls = $( event.target ).closest( ".js_video_controls" );
	 			var videoId = $playbackControls.data( "for" );
	 			var domVideo = $( "#" + videoId ).get( 0 );
	 			$playbackControls.toggleClass( "fade-out" );
	 			if ( domVideo.paused ) {
	 				$( ".js_video_poster[ data-for = " + videoId + " ]" ).addClass( "fade-out" );
	 				domVideo.play();
	 			}
	 			else {
	 				domVideo.pause();
	 			}
	 		} );
	 		var userScrollsPastTheFoldHandler = runBeforePaint( function () {

	 			var $fold = $( ".js_intro_section" );
	 			var $videoSection = $( ".js_video_section" );
	 			var $landingVideoFallback = $( ".js_video_fallback" );
	 			var domLandingVideo = $( ".js_landing_video" ).get( 0 );

	 			return function ( event ) {

		 			var scrollTop = window.scrollY;

		 			if ( scrollTop <= $fold.get( 0 ).offsetTop ) {
		 				return;
		 			}

		 			// remove the check
			 		window.removeEventListener(
			 			"scroll",
			 			userScrollsPastTheFoldHandler,
			 			supportPassiveEventHandling ? { capture: true, passive: true } : true
			 		);

		 			// if ( $landingVideoFallback.hasClass( "js_hidden" ) {
		 			if ( domLandingVideo.currentTime > 0 ) {
		 				return;
		 			}

		 			// Else, have the video show up later
		 			// and prevent the video from ever showing up above
		 			$landingVideoFallback.removeClass( "fade-out js_hidden" )
		 			domLandingVideo.pause();
		 			$( domLandingVideo ).remove();
		 			$videoSection.removeClass( "hidden" );

	 			};

	 		}() );
	 		userScrollsPastTheFoldHandler();
	 		window.addEventListener(
	 			"scroll",
	 			userScrollsPastTheFoldHandler,
	 			supportPassiveEventHandling ? { capture: true, passive: true } : true
	 		);
	 	}





	/*
	 *
	 * WebP Fallback
	 *
	 * In certain scenarios, it is not possible to integrate image formats that
	 * are unsupported seamlessly
	 * So, JavaScript is used to determine which image format is appropriate,
	 * and plug that into the markup / CSS
	 *
	 */
	$( "[ data-bg-img ]" ).each( function ( _i, el ) {
		var $el = $( el );
		var imageURL = $el.data( "bg-img" );
		if ( Modernizr.webp ) {
			imageURL = imageURL.replace( /\.(jpe?g|png)$/, ".webp" );
		}
		$el.css( {
			backgroundImage: "url( '" + imageURL + "' )"
		} );
	} );





	/*
	 *
	 * When a user attempts to enquire via e-mail,
	 *	record in analytics
	 *
	 */
		$( document ).on( "click", ".js_enquire_email", function ( event ) {
			$( document ).trigger( "::enquiry:send-email" );
		} );



	/*
	 *
	 * When a user attempts to enquire via phone,
	 *	record in analytics
	 *
	 */
		$( document ).on( "click", ".js_enquire_phone", function ( event ) {
			$( document ).trigger( "::enquiry:phone" );
		} );




	/*
	 *
	 * Main Navigation Smooth Scroll
	 *
	 */
		$('.js_smooth').on('click', function( link ) {
			link.preventDefault();

			var linkTo = link.target.getAttribute('href');
			if ( $(link.target).closest("#modal-wrapper").length ) {
				$('.js_modal_close').trigger("click");
			}

			setTimeout( function(){
				document.querySelector( linkTo ).scrollIntoView({ behavior: 'smooth' });
			}, 300);
		});




	/*
	 *
	 * Facts Gallery
	 *
	 */

		$('.facts-gallery').slick({
			autoplay: true,
			arrows: true,
			dots: true,
			infinite: true,
			speed: 800,
			autoplaySpeed: 3000,
			slidesToShow: 1,
			adaptiveHeight: true
		});




	/*
	 *
	 * Packages List
	 *
	 */

		function packagesReslick() {

			$('.packages-list').slick({
				autoplay: false,
				arrows: true,
				dots: true,
				infinite: false,
				speed: 300,
				slidesToShow: 3,
				adaptiveHeight: true,
				responsive: [
					{
						breakpoint: 1039,
						settings: "unslick"
					}
				]
			});

		}

		/* Run */
		packagesReslick();



	/*
	 *
	 * Calculating Heights of Package Days
	 *
	 */

		var max_days = <?php echo $max_days_in_a_package; ?>;
		var max_height = parseFloat($('.js_packages_list .thumbnail').css('height'));


	/*
	 *
	 * Tours List
	 *
	 */
	 	/* uncomment this once theres more than one tour */
		function toursReslick() {
			// $('.tours-list').slick({
			// 		autoplay: false,
			// 		arrows: true,
			// 		dots: true,
			// 		infinite: false,
			// 		speed: 300,
			// 		slidesToShow: 3,
			// 		adaptiveHeight: true,
			// 		responsive: [
			// 			{
			// 				breakpoint: 1039,
			// 				settings: "unslick"
			// 			}
			// 		]
			// 	});
		}

		/* Run */
		toursReslick();


	/*
	 *
	 * Natural Scrolling
	 *
	 */

	 	var carouselsResize = runBeforePaint( function () {

	 		var $sectionHeadline = $( ".tours-section .tours .headline" );

	 		var $packagesCarouselFirstItem = $( ".packages-list" ).find( ".package:first" );
	 		var $packagesCarouselLastItem = $( ".packages-list" ).find( ".package:last" );

	 		var $toursCarouselFirstItem = $( ".tours-list" ).find( ".tour:first" );
	 		var $toursCarouselLastItem = $( ".tours-list" ).find( ".tour:last" );

	 		return function () {

	 			var viewportWidth = window.innerWidth;
	 			if ( viewportWidth >= 1040 ) {
	 				$packagesCarouselFirstItem.css( "margin-left", 0 );
	 				$packagesCarouselLastItem.css( "margin-right", 0 );

	 				$toursCarouselFirstItem.css( "margin-left", 0 );
	 				$toursCarouselLastItem.css( "margin-right", 0 );

	 				packagesReslick();
	 				toursReslick();
	 			}
	 			else {
		 			var padding = Math.round( $sectionHeadline.offset().left );

		 			$packagesCarouselFirstItem.css( "margin-left", padding );
		 			$packagesCarouselLastItem.css( "margin-right", padding );

		 			$toursCarouselFirstItem.css( "margin-left", padding );
		 			$toursCarouselLastItem.css( "margin-right", padding );
	 			}

	 		};

	 	}() );
	 	carouselsResize();
	 	window.addEventListener(
	 		"resize",
	 		carouselsResize,
	 		supportPassiveEventHandling ? { capture: true, passive: true } : true
	 	);





	/*
	 *
	 * Tour Gallery
	 *
	 */

		$( ".js_tour" ).one( "click", function ( event ) {
			var modalId = $( event.target ).closest( ".js_tour" ).data( "modId" );
			var $modal = $( ".js_modal_box_content" + "[ data-mod-id = " + modalId + " ]" );

			/* Auto-show the images from the first three days */
			$modal.find( ".js_gallery_day_select" )
				.slice( 0, 3 )
				.trigger( "click" );

			/* Show the "Load More Images" if not all the days have been visited */
			var $daysLeft = $modal.find( ".js_gallery_day_select" );
			if ( $daysLeft.length )
				$modal.find( ".js_text_load_images" ).show();

		} );

		$( ".js_gallery_day_select" ).on( "click", function ( event ) {

			/* References */
			var TOURS = __DATA__.TOURS;
			var $modal = $( event.target ).closest( ".js_modal_box_content" );

			/* get the gallery associated with the selected day */
			/* 1. Get the associated tour */
			var organisation = $modal.data( "org" );
			var tourIndex;
			TOURS.forEach( function ( tour, _i ) {
				if ( tour.organisation == organisation ) {
					tourIndex = _i;
				}
			} );
			var tour = TOURS[ tourIndex ];

			/* 2. Get the associated day */
			var dayNumber = $( event.target ).data( "day-num" );
			var dayIndex;
			tour.days.forEach( function ( day, _i ) {
				if ( _i + 1 == dayNumber ) {
					dayIndex = _i;
				}
			} );
			var day = tour.days[ dayIndex ];

			/* 3. Clone a day-gallery template and plug in the data */
			// var $days = $modal.find( ".js_day:last" );
			var $daysContainer = $modal.find( ".js_days_container" );
			var $day = $( $( ".tmpl_day_in_gallery" ).html() );
			$day.find( ".js_day_num" ).text( "Day " + dayNumber );
			$day.find( ".js_day_description" ).html( day.description.trim().replace( /\n/g, "<br>" ) );
			day.gallery.forEach( function ( image ) {
				var imageURL;
				var imageMarkup;
				if ( image.image ) {
					imageURL = "/uploads/thumbnails/" + image.image;
					// markupForTheActualImage = "<source type='image/webp' srcset='" + imageURL.replace( /\.(jpe?g|png)$/, ".webp" ) + "'>"
										// + "<img src='" + imageURL + "'>"
				}
				else {
					imageURL = "/img/placeholder.png";
					// markupForTheActualImage = "<img src='" + imageURL + "'";
				}
				var $image = $( $( ".tmpl_image_in_gallery" ).html() );
				$image.data( "url", image.image );
				$image.find( ".js_day_location" ).text( image.location );
				$image.find( ".js_day_caption" ).text( image.caption );
				// try {
					$image.find( ".js_day_image[ type = 'image/webp' ]" ).attr( "srcset", imageURL.replace( /\.(jpe?g|png)$/, ".webp" ) );
				// }
				// catch ( e ) {}
				// $image.find( ".js_day_image" ).html(
				// 	"<picture>"
				// 	"<source type='image/webp' srcset='" + imageURL.replace( /\.(jpe?g|png)$/, ".webp" ) + "'>"
				// );
				$image.find( "img.js_day_image" ).attr( "src", imageURL );
				// $image.find( ".js_day_image" ).html( markupForTheActualImage )
				$day.find( ".js_day_gallery" ).append( $image );
			} );

			// $day.insertAfter( $days );
			$daysContainer.append( $day );
			$( event.target ).remove();

			/* 4. Remove the "Load More Images" once all the days have been visited */
			var $daysLeft = $modal.find( ".js_gallery_day_select" );
			if ( ! $daysLeft.length ) {
				$modal.find( ".js_text_load_images" ).remove();
			}

		} );





	/*
	 *
	 * Slideshow
	 *
	 */
	var slideshowDuration = 2 * 1000;
	var $tourSlidesContainer = $( ".js_tour .js_slides" );
	// Add a `show` class to the first slide on every tour
	$tourSlidesContainer.each( function ( _i, domSlidesContainer ) {
		$( domSlidesContainer ).find( ".slide:first" ).addClass( "show" );
	} );

	function slide () {
		$tourSlidesContainer.each( function ( _i, domSlidesContainer ) {
			var $slidesContainer = $( domSlidesContainer );
			var $slides = $slidesContainer.find( ".slide" );
			var $currentSlide = $slidesContainer.find( ".show" );
			var currentSlideIndex = $slides.index( $currentSlide ) + 1;
			var newIndex = ( currentSlideIndex + 1 ) % $slides.length;
			$currentSlide.removeClass( "show" );
			$slides.eq( newIndex ).addClass( "show" );
		} )
		setTimeout( slide, slideshowDuration );
	}
	setTimeout( slide, slideshowDuration );





	/*
	 *
	 * Get Schedule for Tour Package
	 *
	 * When a user clicks on "Get a Detailed Itinerary",
	 * we scroll down to the form and auto-select that tour package
	 *
	 */
	$( document ).on( "click", ".js_get_schedule", function ( event ) {
		event.preventDefault();
		var tourPackage = $( event.target ).data( "title" );
		var $bookTourForm = $( "#js_form_book_tour" );
		var $getDetailedItinerarySection = $( "#book_now" );
		$bookTourForm.find( ".js_form_booking_package" ).val( tourPackage );
		$getDetailedItinerarySection.get( 0 ).scrollIntoView( { behavior: "smooth" } );

		// for Google Tag Manager
		$( document ).trigger( "::pre-enquiry:get-detailed-itinerary" );
	} );





	/*
	 *
	 * Share URLs for Tours
	 *
	 * When the share button or the URL is clicked/tapped
	 * We copy the URL to the clipboard
	 *
	 */
	$( document ).on( "click", ".js_share_url", function ( event ) {
		event.preventDefault();
	} );
	$( document ).on( "click", ".js_share_url_widget", function ( event ) {

		event.preventDefault();

		var $shareURLWidget = $( event.target ).closest( ".js_share_url_widget" );
		var $feedbackMessage = $shareURLWidget.find( ".js_share_url_title" );

		var $url = $shareURLWidget.find( ".js_share_url_text" );
		$url.get( 0 ).select();
		try {
			document.execCommand( "copy" );
			$feedbackMessage.text( "Copied to Clipboard" );
		}
		catch ( e ) {}
		$url.get( 0 ).blur();

	} );





	/*
	 *
	 * Tour Images Gallery Slideshow
	 *
	 * On clicking an image, we open it in an inner modal with a carousel attached
	 *
	 */
	var domCurrentTourGalleryPhoto;
	$( document ).on( "click", ".js_day_photo_frame", function ( event ) {

		event.preventDefault();

		var $innerModal = $( ".js_modal_modal_box" );
		$innerModal.show();
		var $slideshowCarousel = $innerModal.find( "[ data-mod-id = slideshow-carousel ]" );
		$slideshowCarousel.addClass( "active" );

		var $currentPhoto = $( event.target ).closest( ".js_day_photo_frame" )
		domCurrentTourGalleryPhoto = $currentPhoto.get( 0 );

		injectImage( domCurrentTourGalleryPhoto );
		blurOutNavigationArrows( domCurrentTourGalleryPhoto );

	} );

	// Blurs out either of the two arrows if necessary
	function blurOutNavigationArrows ( domCurrentPhoto ) {

		var $slideshowCarousel = $( ".js_modal_modal_box [ data-mod-id = slideshow-carousel ]" );
		var $photo = $( domCurrentPhoto );
		if ( ! $photo.prev().length ) {
			$slideshowCarousel.find( ".js_prev" ).prop( "disabled", true );
		}
		else {
			$slideshowCarousel.find( ".js_prev" ).prop( "disabled", false );
		}
		if ( ! $photo.next().length ) {
			$slideshowCarousel.find( ".js_next" ).prop( "disabled", true );
		}
		else {
			$slideshowCarousel.find( ".js_next" ).prop( "disabled", false );
		}

	}

	function injectImage ( domCurrentPhoto ) {

		var $photo = $( domCurrentPhoto );
		var imageURL = $photo.data( "url" );
		if ( Modernizr.webp ) {
			imageURL = imageURL.replace( /\.(jpe?g|png)$/, ".webp" );
		}
		var location = $photo.find( ".location" ).text();
		var caption = $photo.find( ".caption" ).text();

		var $gallery = $( ".js_modal_modal_box" );
		$gallery.find( ".js_photo_frame" ).css( {
			backgroundImage: "url( '" + "/uploads/" + imageURL + "' )"
		} );
		$gallery.find( ".js_location" ).text( location );
		$gallery.find( ".js_caption" ).text( caption );

	}

	$( ".js_modal_modal_box" ).on( "click", ".js_prev", function ( event ) {
		domCurrentTourGalleryPhoto = $( domCurrentTourGalleryPhoto ).prev().get( 0 );
		injectImage( domCurrentTourGalleryPhoto );
		blurOutNavigationArrows( domCurrentTourGalleryPhoto );
	} );
	$( ".js_modal_modal_box" ).on( "click", ".js_next", function ( event ) {
		domCurrentTourGalleryPhoto = $( domCurrentTourGalleryPhoto ).next().get( 0 );
		injectImage( domCurrentTourGalleryPhoto );
		blurOutNavigationArrows( domCurrentTourGalleryPhoto );
	} );





});

</script>

</body>

</html>
