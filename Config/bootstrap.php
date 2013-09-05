<?php
if(!class_exists('Mustache_Autoloader')) {
	App::import('Vendor', 'Mustache.mustache/src/Mustache/Autoloader');
}
Mustache_Autoloader::register();
?>