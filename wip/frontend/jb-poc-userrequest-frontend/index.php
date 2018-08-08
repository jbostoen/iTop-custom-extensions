<?php

	require_once '../libext/vendor/autoload.php';

	$loader = new Twig_Loader_Filesystem('templates');
	$twig = new Twig_Environment($loader, array(
		//'cache' => '/path/to/compilation_cache',
	));

	echo $twig->render('report.html', [] );


?>