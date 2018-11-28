<?php
$packages = json_decode( file_get_contents( $_SERVER[ 'DOCUMENT_ROOT' ] . '/database/package_database.json' ), true );
foreach ( $packages as $current_package ) {
	if ( $current_package[ 'title' ] == $package ) {
		$schedule = $current_package[ 'schedule' ];
		break;
	}
}
?>
<p>
	Dear <?php echo $first_name ?>,
</p>

<p>Thanks for your interest in our tours!</p>

<p>
	You can find the detailed itinerary <a href="<?php echo $HOST ?>/uploads/schedules/<?php echo $schedule ?>" target="_blank">here</a>.
</p>

<p>Someone from our team will get in touch with you to provide any additional information that you may need.</p>

<p>
	Regards,
	<br>
	<br>
	<img src="<?php echo $HOST ?>/img/email_signature.jpg" width="550px">
	<br>
	Call <a href="tel:+919591658632">+91 95916 58632</a>
	<br>
	<a href="http://www.immersionindia.com">www.immersionindia.com</a>
</p>
