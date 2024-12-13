<?php
    defined("RUTA_BASE") or define("RUTA_BASE",dirname(__FILE__));
    
	//ruta al fichero de Sistema base
	$pedrosa=RUTA_BASE.'/framework/Sistema.php';
	
	//ruta al fichero de configuración
	$configuracion=RUTA_BASE.'/aplicacion/config/config.php';
	
	//incluye los ficheros de sistema y de configuracion
	require_once($pedrosa);
	require_once($configuracion);
	
	$barraUbi = [
		"texto" => "inicio",
        "url" => "/"
	];
	//crea la aplicación y la ejecuta
	Sistema::crearAplicacion($config)->ejecutar();
	
	