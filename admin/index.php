<?php

ini_set( "display_errors", "On" );
ini_set( "error_reporting", E_ALL );

// prevent search crawling
header( 'X-Robots-Tag: none' );

$root_path = $_SERVER[ 'DOCUMENT_ROOT' ];

require ( "{$root_path}/inc/lazaro.php" );

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
	prefix="og: http://ogp.me/ns# fb: http://www.facebook.com/2008/fbml">

<head>


	<!-- Page Meta -->
	<meta charset="utf-8">
	<title>Immersion India | Admin Panel</title>

	<!-- Prevent crawlers from well, crawling! ~~~~~ -->
	<meta name="robots" content="noindex, nofollow">

	<meta name="author" content="Lazaro Advertising">
	<link rel="canonical" href="http://immersionindia.com/">

	<!-- Viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

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

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600" rel="stylesheet">

	<!-- Icons -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!-- Stylesheet -->
	<link rel="stylesheet" type="text/css" href="/admin.css">

	<!-- jQuery 3 -->
	<script type="text/javascript" src="/js/jquery-3.0.0.min.js"></script>


</head>









<body id="body" class="body">

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PK46W6N"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!--  ★  MARKUP GOES HERE  ★  -->

<div id="page-wrapper"><!-- Page Wrapper -->

	<!-- Page Content -->
	<div id="page-content">




		<!-- NEW PACKAGE -->
		<header class="section"">
			<div class="container">
				<div class="row">
					<div class="columns ten offset-by-one">
						<h2>Admin Panel</h2>
					</div>
				</div>
			</div>
		</header>
		<section class="section" style="background: #F2F2F2;">
			<div class="container">
				<div class="row">
						<div class="columns five offset-by-one text-center">
							<a href="packages.php" class="page-links block">
								<i class="material-icons">loyalty</i>
								<br>Packages
							</a>
						</div>
						<div class="columns five text-center">
							<a href="tours.php" class="page-links block">
								<i class="material-icons">public</i>
								<br>Tours
							</a>
						</div>
					</a>
				</div>
			</div>
		</section>


	</div> <!-- END : Page Content -->


	<!-- Lazaro Signature -->
	<?php lazaro(); ?>
	<!-- END : Lazaro Signature -->

</div><!-- END : Page Wrapper -->









<!-- ⬇ All Modals below this point ⬇ -->

<div id="modal-wrapper"><!-- Modal Wrapper -->
	<div class="modal-box js_modal_box">
		<!-- Modal Content : Sample Video -->
		<div class="modal-box-content js_modal_box_content" data-mod-id="sample-video">
			<div class="container">
				<div class="row">
					<div class="columns small-12">
						<!-- video embed -->
						<div class="youtube_embed ga_video" data-src="https://www.youtube.com/embed/lncVHzsc_QA?rel=0&amp;showinfo=0" data-ga-video-src="Sample - Video">
							<div class="youtube_load"></div>
							<iframe width="1280" height="720" src="" frameborder="0" allowfullscreen></iframe>
						</div>
					</div>
				</div>
			</div>
		</div><!-- END : Sample Video -->

		<!-- Modal Content : Disclaimer -->
		<div class="modal-box-content js_modal_box_content" data-mod-id="disclaimer">
			<div class="container">
				<?php lazaro_disclaimer(); ?>
			</div>
		</div><!-- END : Disclaimer -->

		<!-- Modal Close Button -->
		<div class="modal-close js_modal_close">&times;</div>
	</div>

</div><!-- END : Modal Wrapper -->









<!-- 🔩 All Templates Below this Point 🔩 -->
<template></template>
<!-- END: All Templates Here -->




<!--  ☠  MARKUP ENDS HERE  ☠  -->









<!-- JS Modules -->
<script type="text/javascript" src="/js/modules/video_embed.js"></script>
<script type="text/javascript" src="/js/modules/modal_box.js"></script>
<script type="text/javascript" src="/js/modules/smoothscroll.js"></script>
<script type="text/javascript" src="/plugins/slick/slick.min.js"></script>

<script type="text/javascript">

// JAVASCRIPT GOES HERE
$(document).ready(function(){

});

</script>

</body>

</html>
