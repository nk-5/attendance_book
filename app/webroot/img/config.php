<?php

define('IMAGES_DIR',dirname($_SERVER['SCRIPT_FILENAME']).'/img/images');
define('THUMBNAIL_DIR',dirname($_SERVER['SCRIPT_FILENAME']).'/img/thumbnails');

define('THUMBNAIL_WIDTH',72);
define('MAX_FILE_SIZE',307200);//300KB

error_reporting(E_ALL & ~E_NOTICE);

//GD
if(!function_exists('imagecreatetruecolor')){
	echo "GD NG";
	exit;
}