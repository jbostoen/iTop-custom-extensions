<?php

	/**
	* Extends Twig with some default variables, settings
	**/
	
	
	/**
	* Class Framework_TwigEnvironment. Extends Twig with some default variables, settings
	**/
	class Framework_TwigEnvironment extends Twig_Environment {
	

		/**
		* Extends Twig render function by exposing $_REQUEST as request
		*
		* @param String $sFileName Filename
		* @param Array $aVariables Associative array of variables to expose in Twig template
		*
		**/
		public function render( $sFileName, $aVariables = [] ) {
		
			$aVariables = array_merge([
				"request" => $_REQUEST
			], $aVariables);
			
			return parent::render( $sFileName, $aVariables );
		
		}
	
	}
	
	