<?php


	define( 'izIFW_prefix', 'izIFW' );
	define( 'izIFW_rootDir', dirname( __DIR__ , 3) );
	
	
	/**
	* Class iTop_FrameWorkSettings. Contains common settings
	*/
	abstract class iTop_FrameWork {
	
	
		/**
		* @var String frameworkDir Location of the framework
		*/
		public const frameworkDir = izIFW_rootDir . '/frontend/framework';
		
		
		
		/**
		* @var String prefix Prefix for framework. Used for sessions, cookies, ...
		*/
		public const prefix = izIFW_prefix;
		
		
		/**
		* @var String rootDir Location of the framework
		*/
		public const rootDir = izIFW_rootDir;
		
		
		
		/**
		* Translates a string, similar to Dict::s.
		*
		* @var String $sInput Reference to translation
		*
		* @return Translated string
		*/
		public static function S( $sInput ) {
				
			// Get iTop's Dict::S('string') so it can be exposed to Twig as well 
			$sDir = izIFW_rootDir;
			require_once( $sDir . '/approot.inc.php' );
			require_once( $sDir . '/application/utils.inc.php' );
			require_once( $sDir . '/core/coreexception.class.inc.php' );
			require_once( $sDir . '/core/dict.class.inc.php' );
			
			return Dict::S( $sInput );
			
		}
		
		
	
	}
	
	