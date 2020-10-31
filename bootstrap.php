<?php
require_once __DIR__.'/config.php';

if(file_exists(__DIR__."/vendor/autoload.php")){
	require_once __DIR__ . "/vendor/autoload.php";
}

spl_autoload_register(function ($name) {
	$filename = BASEDIR . DIRECTORY_SEPARATOR . str_replace('\\', '/', $name) . '.php';
	if (file_exists($filename)) {
		require_once $filename;
	} else {
		if(isset($_SERVER['SERVER_PROTOCOL']))
			header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
		echo "Class '$filename' Not Found";
		exit;
	}
});
