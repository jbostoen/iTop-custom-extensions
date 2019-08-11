<?php

	
	// Including the autoload (you need to composer install in the main directory)
	require_once( __DIR__ . '/../../libext/vendor/autoload.php');
	


	$options = new chillerlan\QRCode\QROptions([
		'version'    => 5,
		'outputType' => chillerlan\QRCode\QRCode::OUTPUT_MARKUP_SVG,
		'eccLevel'   => chillerlan\QRCode\QRCode::ECC_L,
		'scale'		 => 3
	]);

	// invoke a fresh QRCode instance
	$qrcode = new chillerlan\QRCode\QRCode($options);

	// and dump the output 
	echo $qrcode->render($_REQUEST['data']);
	
