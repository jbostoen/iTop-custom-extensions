<?php
/*
 * jQuery File Upload Plugin PHP Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */

 
	// Careful: upload handler needs some protection
	if( !isset($_FILES['files']) ) {
		die("No access");
	}
	
	
	
	 
	error_reporting(E_ALL | E_STRICT);
	require('../../libext/vendor/blueimp/jquery-file-upload/server/php/UploadHandler.php');
	
	/**
	 *  Class izUploadHandler. Overrides get_file_name()
	 */
	class izUploadHandler extends uploadHandler {
		
		/**
		 *  
		 *  Custom handler (slightly different filename)
		 *  
		 *  @param String $file_path Path
		 *  @param String $name Name
		 *  @param Float $size File size
		 *  @param String $type MIME Type (content type)
		 *  @param String $error ?
		 *  @param Float $index Index
		 *  @param [in] $content_range Range
		 *   
		 *  @return String
		 *   
		 */
		protected function get_file_name($file_path, $name, $size, $type, $error,
				$index, $content_range) {
			$name = $this->trim_file_name($file_path, $name, $size, $type, $error,
				$index, $content_range);
				
			return date("YmdHis") . '_' . $this->get_unique_filename(
				$file_path,
				$this->fix_file_extension($file_path, $name, $size, $type, $error,
					$index, $content_range),
				$size,
				$type,
				$error,
				$index,
				$content_range
			);
			
		}
		
	}
	
	$upload_handler = new izUploadHandler([
	
		'image_versions' => array(
			// The empty image version key defines options for the original image.
			// Keep in mind: these image manipulations are inherited by all other image versions from this point onwards. 
			// Also note that the property 'no_cache' is not inherited, since it's not a manipulation.
			'' => array(
				// Automatically rotate images based on EXIF meta data:
				'auto_orient' => true
			),
			'thumbnail' => array(
				// Uncomment the following to use a defined directory for the thumbnails
				// instead of a subdirectory based on the version identifier.
				// Make sure that this directory doesn't allow execution of files if you
				// don't pose any restrictions on the type of uploaded files, e.g. by
				// copying the .htaccess file from the files directory for Apache:
				// 'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
				// 'upload_url' => $this->get_full_url().'/thumb/',
				// Uncomment the following to force the max
				// dimensions and e.g. create square thumbnails:
				// 'auto_orient' => true,
				// 'crop' => true,
				'jpeg_quality' => 65,
				// 'no_cache' => true, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
				// 'strip' => true, (this strips EXIF tags, such as geolocation)
				'max_width' => 1000, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
				'max_height' => 1000 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
			)
		),
	]);

 
