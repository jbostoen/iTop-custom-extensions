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

error_reporting(E_ALL | E_STRICT);
require('../../libext/vendor/blueimp/jquery-file-upload/server/php/UploadHandler.php');
$upload_handler = new UploadHandler([
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
			//'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
			//'upload_url' => $this->get_full_url().'/thumb/',
			// Uncomment the following to force the max
			// dimensions and e.g. create square thumbnails:
			// 'auto_orient' => true,
			// 'crop' => true,
			'jpeg_quality' => 60,
			// 'no_cache' => true, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
			// 'strip' => true, (this strips EXIF tags, such as geolocation)
			'max_width' => 500, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
			'max_height' => 0 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
		)
	),
]);

 
