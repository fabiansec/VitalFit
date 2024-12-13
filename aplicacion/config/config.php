<?php

$config = array(
	"CONTROLADOR" => array("inicial"),
	"RUTAS_INCLUDE" => array("aplicacion/modelos","aplicacion/clases"),
	"URL_AMIGABLES" => true,
	"VARIABLES" => array(
		"autor" => " Fabián España ",
		"direccion" => " Madrid ",
		"cookie" => '',
		'sesion' => ''
	
	),
	"BD" => array(
		"hay" => false,
		"servidor" => "localhost",
		"usuario" => "tusdatos",
		"contra" => "tusdatos",
		"basedatos" => "gimnasio"
	),
	"sesion" => array(
		"controlAutomatico" => true,
	),
	"ACL"=>array("controlAutomatico"=>true)
);
