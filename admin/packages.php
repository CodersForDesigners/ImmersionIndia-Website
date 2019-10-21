<?php

ini_set( "display_errors", "On" );
ini_set( "error_reporting", E_ALL );

// prevent search crawling
header( 'X-Robots-Tag: none' );

$root_path = $_SERVER[ 'DOCUMENT_ROOT' ];

require ( "{$root_path}/inc/lazaro.php" );

$database_file = "{$root_path}/database/package_database.json";
$packages = json_decode( file_get_contents( $database_file ) );

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml"
	prefix="og: http://ogp.me/ns# fb: http://www.facebook.com/2008/fbml">

<head>


	<!-- Page Meta -->
	<meta charset="utf-8">
	<title>Immersion India | Packages</title>

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
						<h2>
							Packages
							<a href="/admin/" class="button button-icon button-secondary float-right" style="margin-top: 6px;"><i class="material-icons">home</i></a>
						</h2>
					</div>
				</div>
			</div>
		</header>
		<article class="section package js_package js_package_form_add" style="background: #F2F2F2;">
			<div class="container">
				<div class="row">
					<div class="columns ten offset-by-one"><br></div>
					<div class="columns ten offset-by-one">
						<h4>New Package</h4>
					</div>
					<div class="form-row columns five offset-by-one clearfix">
						<label>Label<br>
							<input class="block" type="text" name="label">
						</label>
					</div>
					<div class="form-row columns five clearfix">
						<label>Title<br>
							<input class="block" type="text" name="title">
						</label>
					</div>
					<div class="form-row columns five offset-by-one clearfix">
						<label>Price<br>
							<input class="block" type="text" name="price">
						</label>
					</div>
					<div class="form-row columns five clearfix">
						<label>Description<br>
							<textarea class="block" name="description"></textarea>
						</label>
					</div>
					<div class="form-row columns five offset-by-one">
						<label>Schedule<br>
							<label class="input-file button button-icon inline">
								<input type="file" accept=".pdf" name="schedule">
								<i class="inline-middle material-icons">file_upload</i>
							</label>
							<small class="file-name js_file_name"></small>
						</label>
					</div>
				</div>
				<div class="columns ten offset-by-one"><br></div>
				<div class="columns ten offset-by-one js_package_places">
					<!-- New Location -->
				</div>
				<div class="columns ten offset-by-one"><br></div>
				<div class="row">
					<div class="columns five offset-by-one">
						<h5 class="text-uppercase">Add A Location</h5>
					</div>
					<div class="columns five text-right">
						<button class="button button-icon js_package_place_add inline"><i class="material-icons">add_location</i></button>
					</div>
				</div>
				<div class="columns ten offset-by-one"><br></div>
				<div class="columns ten offset-by-one">
					<button class="block button-primary js_package_add">Create</button>
				</div>
				<div class="columns ten offset-by-one"><br></div>
			</div>
		</article>



		<section><!-- LIST OF EXISITING PACKAGES -->
		<?php foreach ( $packages as $index => $package ) : ?>
			<article class="section package js_package js_package_form_update" data-index="<?php echo $index ?>" style="border-bottom: solid 10px #F2F2F2;">
				<div class="container">
					<div class="row">
						<div class="columns six offset-by-one">
							<h4 class="inline">
								<?php
									if ( $package->processing ) {
										echo "[processing] ";
									}
								?>
								<?php echo $package->label ?>
							</h4>
						</div>
						<div class="columns four text-right">
							<button class="button button-icon button-secondary inline js_package_remove" <?php if ( $package->processing === true ) { echo "disabled"; } ?> ><i class="material-icons">delete_forever</i></button>
							<button class="button button-icon button-secondary inline package-move-up js_package_move_up" <?php if ( $package->processing === true ) { echo "disabled"; } ?> ><i class="material-icons">arrow_drop_up</i></button>
							<button class="button button-icon button-secondary inline package-move-down js_package_move_down" <?php if ( $package->processing === true ) { echo "disabled"; } ?> ><i class="material-icons">arrow_drop_down</i></button>
							<button class="button button-icon button-secondary inline show-more-toggle js_show_more_toggle"><i class="material-icons">expand_more</i></button>
						</div>
					</div>
					<div class="show-more-content js_show_more_content">
						<div class="row">
							<div class="form-row columns five offset-by-one">
								<label>Label<br>
									<input class="block" type="text" name="label" value="<?php echo $package->label ?>">
								</label>
							</div>
							<div class="form-row columns five">
								<label>Title<br>
									<input class="block" type="text" name="title" value="<?php echo $package->title ?>">
								</label>
							</div>
							<div class="form-row columns five offset-by-one">
								<label>Price<br>
									<input class="block" type="text" name="price" value="<?php echo $package->price ?>">
								</label>
							</div>
							<div class="form-row columns five">
								<label>Description<br>
									<textarea class="block" name="description"><?php echo $package->description ?></textarea>
								</label>
							</div>
							<div class="form-row columns three offset-by-one">
								<label>Schedule<br>
									<label class="input-file button button-icon inline">
										<input type="file" accept=".pdf" name="schedule" data-path="<?php echo $package->schedule ?>">
										<i class="inline-middle material-icons">file_upload</i>
									</label>
									<small class="file-name js_file_name"><?php echo preg_replace( '/(__\d+(\.\d+)?)?\.(.*)/', '.${3}', $package->schedule ); ?></small>
								</label>
							</div>
							<div class="columns ten offset-by-one"><br></div>
							<div class="columns ten offset-by-one js_package_places">
								<?php if ( ! empty( $package->locations ) ) { ?>
									<?php foreach ( $package->locations as $location ) : ?>
										<div class="form-row row package-place js_package_place" draggable="true">
											<div class="columns four">
												<label>City<br>
													<input class="block" type="text" name="city" value="<?php echo $location->city ?>">
												</label>
											</div>
											<div class="columns two">
												<label>Days<br>
													<input class="block" type="text" name="days" value="<?php echo $location->days ?>">
												</label>
											</div>
											<div class="columns four">
												<label>Image<br>
													<label class="input-file button button-icon  inline">
														<input type="file" accept="image/*" name="image" data-path="<?php echo $location->image ?>">
														<i class="inline-middle material-icons">add_a_photo</i>
													</label>
													<small class="file-name js_file_name">
														<img src="/uploads/<?php echo $location->image ?>" alt="<?php echo preg_replace( '/(__\d+(\.\d+)?)?\.(.*)/', '.${3}', $location->image ); ?>">
													</small>
												</label>
											</div>
											<div class="columns two text-right">
												<label class="invisible">Sort/Remove</label>
												<!-- <button class="button button-icon small inline package-place-move-up js_package_place_move_up"><i class="material-icons">arrow_drop_up</i></button>
												<button class="button button-icon small inline package-place-move-down js_package_place_move_down"><i class="material-icons">arrow_drop_down</i></button> -->
												<button class="button button-icon inline js_package_place_remove"><i class="material-icons">delete_forever</i></button>
												<button class="button button-icon movable"><i class="material-icons">open_with</i></button>
											</div>
										</div>
									<?php endforeach; ?>
								<?php } else { ?>
									<p class="columns ten offset-by-one">No Locations Added.</p>
								<?php } ?>
							</div>
							<div class="columns ten offset-by-one"><br></div>
							<div class="columns five offset-by-one">
								<h5 class="text-uppercase">Add A Location</h5>
							</div>
							<div class="columns five text-right">
								<button class="button button-icon js_package_place_add"><i class="material-icons">add_location</i></button>
							</div>
							<div class="columns ten offset-by-one">
								<div class="row">

								</div>
							</div>
							<div class="columns ten offset-by-one"><br></div>
							<div class="row">
								<div class="columns ten offset-by-one">
									<button class="block button-primary js_package_update">Update</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</article>
		<?php endforeach; ?>
		</section><!-- END : LIST OF EXISITING PACKAGES -->


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
<template class="tmpl_package_place">
	<div class="form-row row package-place js_package_place" draggable="true">
		<div class="columns four">
			<label>City<br>
				<input class="block" type="text" name="city">
			</label>
		</div>
		<div class="columns two">
			<label>Days<br>
				<input class="block" type="text" name="days">
			</label>
		</div>
		<div class="columns four">
			<label>Image<br>
				<label class="input-file button button-icon  inline">
					<input type="file" accept="image/*" name="image">
					<i class="inline-middle material-icons">add_a_photo</i>
				</label>
				<small class="file-name js_file_name"></small>
			</label>
		</div>
		<div class="columns two text-right">
			<label class="invisible">Sort/Remove</label>
			<!-- <button class="button button-icon small inline package-place-move-up js_package_place_move_up"><i class="material-icons">arrow_drop_up</i></button>
			<button class="button button-icon small inline package-place-move-down js_package_place_move_down"><i class="material-icons">arrow_drop_down</i></button> -->
			<button class="button button-icon inline js_package_place_remove"><i class="material-icons">delete_forever</i></button>
			<button class="button button-icon inline movable"><i class="material-icons">open_with</i></button>
		</div>
	</div>
</template>
<!-- END: All Templates Here -->




<!--  ☠  MARKUP ENDS HERE  ☠  -->









<!-- JS Modules -->
<script type="text/javascript" src="/js/modules/video_embed.js"></script>
<script type="text/javascript" src="/js/modules/modal_box.js"></script>
<script type="text/javascript" src="/js/modules/smoothscroll.js"></script>
<script type="text/javascript" src="/plugins/slick/slick.min.js"></script>
<script type="text/javascript" src="/js/admin_packages.js"></script>

<script type="text/javascript">

	window.__DATA__ = { };
	window.__DATA__.PACKAGES = <?php echo json_encode( $packages ) ?>;

</script>

<script type="text/javascript">

// JAVASCRIPT GOES HERE
$(document).ready(function(){
	$('.js_show_more_toggle').on('click', function(){
		$(this).toggleClass('flip');
		$(this).closest('.js_package').toggleClass('show');
	});
});

</script>

</body>

</html>
