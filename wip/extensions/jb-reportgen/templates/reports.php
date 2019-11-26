<?php

	$aReports = [

		// UserRequest
		'UserRequest' => [
			// Allowed keys: details, list
			'details' => [
				[
					// Allowed keys: 'title' (String), 'button' (true/false), 'file' (string), 'parameters' (hash table; passed in URL)
					// Hash table parameters: 'action' (special parameter)
					'title' => 'Sample report',
					'button' => true,
					'file' => 'basic_details.html'
				],
				[
					'title' => 'Sample report (PDF)',
					'button' => true,
					'file' => 'basic_details.html',
					'parameters' => [
						'action' => 'show_pdf'
					]
				],
				[
					'title' => 'Sample work order',
					'button' => true,
					'file' => 'werkbon.twig'
				]
			],
			'list' => [
				// Other reports 
			]
		]
		
	];
