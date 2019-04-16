<?php

	// Autoload
	require_once('../../libext/vendor/autoload.php');

	// Still required?
	require_once('vendor/hybridauth/hybridauth/src/autoload.php');

	
	session_start();
	
	$loader = new Twig_Loader_Filesystem( iTop_FrameWork::frameworkDir );
	$twig = new iTop_TwigEnvironment($loader, array(
		//'cache' => '/path/to/compilation_cache',
		'autoescape' => false
	));
	
	
	// Default should not happen
	$sRedirectURL = 'https://google.be?q=Why did someone not specify a redirect URL?';
	
	if( isset($_REQUEST['redirect']) == true ) {
		$sRedirectURL = $_REQUEST['redirect'];
	}
	
	

	//Build configuration array
	$config = [

		// Location where to redirect users once they authenticate with Facebook
		// For this example we choose to come back to this same script
		'callback' => 'https://staging-hybridauth.izegem.be/test.php',

		// https://developers.facebook.com/docs/facebook-login/permissions
		'scope' => ['email', 'user_gender', 'user_birthday'],


		//Facebook application credentials
		'keys' => [
			'id'     => '***REMOVED***', //Required: your Facebook application id
			'secret' => '***REMOVED***'  //Required: your Facebook application secret 
		]
	];

	try {
		//Instantiate Facebook's adapter directly	
		$adapter = new Hybridauth\Provider\Facebook($config);

		//Attempt to authenticate the user with Facebook
		$adapter->authenticate();

		//Returns a boolean of whether the user is connected with Facebook
		$isConnected = $adapter->isConnected();
	 
		//Retrieve the user's profile
		$userProfile = $adapter->getUserProfile();

		//Inspect profile's public attributes
		$aPrefill = [
			'first_name' => $userProfile->firstName,
			'name' => $userProfile->lastName,
			'email' => $userProfile->email,
			'facebook_id' => $userProfile->identifier
		];

		//Disconnect the adapter 
		$adapter->disconnect();
		
		// Render template
		echo $twig->render('templates/register.html', [
			'PageTitle' => 'Registreren',
			'RedirectURL' => $sRedirectURL,
			'prefill' => $aPrefill
		]);
	
	
	}
	catch(\Exception $e){
		echo 'Onverwacht probleem, onze excuses hiervoor! ' . $e->getMessage();
	}



		