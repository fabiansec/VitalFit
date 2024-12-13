<?php
    defined("RUTA_FRAMEWORK") or define("RUTA_FRAMEWORK",dirname(__FILE__));

	/**
	 * Clase estática en la que definen los métodos que permiten iniciar
	 * correctamente la aplicación 
	 */
	class Sistema
	{
		static private $_clasesBase=array("CAplicacion"=>"/base/CAplicacion.php",
											"CGeneral"=>"/general/CGeneral.php",
											"CValidaciones"=>"/general/CValidaciones.php",
											"CControlador"=>"/mvc/CControlador.php",
											"CActiveRecord"=>"/mvc/CActiveRecord.php",
											"CHTML"=>"/forms/CHTML.php",
											"CBaseDatos"=>"/bd/CBaseDatos.php",
											"CCommand"=>"/bd/CCommand.php",
											"CWidget"=>"/widget/CWidget.php",
											"CGrid"=>"/widget/CGrid.php",
											"CPager"=>"/widget/CPager.php",
											"CMenu"=>"/widget/CMenu.php",
											"CCaja"=>"/widget/CCaja.php",
											"CSesion" => "/base/CSesion.php",
											"CACLBase" => "/acceso/CACLBase.php",
											"CACLBD" => "/acceso/CACLBD.php",
											"CAcceso" => "/acceso/CAcceso.php"

										);
		static private $_rutasInclude=array();
		static private $_app;
		
		/**
		 * Método que permite la autocarga de clases. Buscará en el array
		 * estático $_clasesBase donde están definidas las clase del framework y
		 * en las rutas que se indican en configuración 
		 * (posición RUTAS_INCLUDE en /aplicacion/config/config.php) 
		 *
		 * @param string $clase nombre de la clase a cargar
		 * @return void
		 */
		static public function autoload(string $clase):void
		{
			if (isset(self::$_clasesBase[$clase])) // existe una entrada en $_clasesBase
			     include(RUTA_FRAMEWORK."/clases".self::$_clasesBase[$clase]);
				else 
				{
					foreach(self::$_rutasInclude as $ruta)
					{
						$ruta.=$clase.".php";
						if (file_exists($ruta))   //existe el fichero en una de las rutas
						   {
						     include($ruta);
							 break;
						   }
					}	
				}
			
			//se comprueba si se ha cargado
			if (!class_exists($clase,false))
				throw new ErrorException("clase $clase no encontrada",0,1);
		}
		
		static public function nuevaRuta($ruta)
		{
			if (substr($ruta, -1)!='/')
				$ruta.="/";
			
			self::$_rutasInclude[]=$ruta;
			
		}
		
		/**
		 * método estático que crea el objeto inicial de aplicación
		 *
		 * @param array $config Opciones para el inicio de la aplicacion
		 * @return CAplicacion
		 */
		static public function crearAplicacion(array $config):CAplicacion
		{
			if (!self::$_app)
			 	self::$_app=new CAplicacion($config);
			
			return self::$_app;
		}	
		
		static public function app() :CAplicacion
		{
			return self::$_app;
		}
		
	}

	spl_autoload_register(array("Sistema","autoload"));
	
	