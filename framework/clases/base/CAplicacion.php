<?php
	/**
	 * Clase que gestiona la ejecución de la aplicación. Entre otras cosas
	 * se encarga de analizar la petición y llamar al controlador-acción apropiado
	 * , redirigir a otra dirección, etc
	 */
	class CAplicacion
	{
		private $_controlDefecto="inicial";
		private $_BD;
		private $_URL_AMIGABLES=false;
        
		private $_prop=array();
		private $_sesion;
		private $_Acceso;
		private $_ACL;
		
		/**
		 * Constructor al que se le indica las opciones de la aplicación
		 * y se encarga de crear todos los objetos necesarios
		 *
		 * @param array $config
		 */
		public function __construct(array $config)
		{
			//se carga la configuracion
			
			//controlador inicial
			if (isset($config["CONTROLADOR"]))
			    {
			      if (is_array($config["CONTROLADOR"]))	
			      		$this->_controlDefecto=$config["CONTROLADOR"][0];
				      else
						$this->_controlDefecto=$config["CONTROLADOR"];
				}
				      
			//rutas para incluir las clases
			if (isset($config["RUTAS_INCLUDE"]))
			{
				foreach ($config["RUTAS_INCLUDE"] as $ruta) 
				{
					if(substr($ruta, 0,1)!='/')
						$ruta=RUTA_BASE."/".$ruta;
					
					Sistema::nuevaRuta($ruta);
				}
			}
			
			//url amigables
			if (isset($config["URL_AMIGABLES"]))
			{
				$this->_URL_AMIGABLES=(boolean)$config["URL_AMIGABLES"];
			}
			
			//variable disponibles en app
			if (isset($config["VARIABLES"]))
			  {
			  	foreach($config["VARIABLES"] as $clave=>$valor)
			  	  {
			  	  	$this->_prop[$clave]=$valor;
			  	  }
			  }
			
			if (isset($config["BD"]) && $config["BD"]["hay"]===true)
			{ 
				$this->_BD=new CBaseDatos($config["BD"]["servidor"],
							  				$config["BD"]["usuario"],
							  				$config["BD"]["contra"],
							  				$config["BD"]["basedatos"]);
			}

			$this->_sesion = new CSesion(); //crear la sesion
			//comprobar si hay sesion


			$this->_Acceso = new CAcceso(); //crear el acceso
			
			if (isset($config["ACL"]) && $config["ACL"]["controlAutomatico"]===true && $config["BD"]["hay"]===true)
			{
				$this->_ACL= new CACLBD($config["BD"]["servidor"],
							  				$config["BD"]["usuario"],
							  				$config["BD"]["contra"],
							  				$config["BD"]["basedatos"]);
			}
		}
		
		/**
		 * Método que devuelve un enlace al objeto CBaseDatos que 
		 * se crea automáticamente en el constructor
		 *
		 * @return CBaseDatos|null
		 */
		public function BD() :?CBaseDatos
		{
			return $this->_BD;
		}

		/**
		 * Método que devuelve un enlace al objeto CSesion
		 * que se crea automáticamente en el constructor
		 *
		 * @return CSesion|null
		 */
		public function SESION() :?CSesion
		{
			return $this->_sesion;
		}

		/**
		 * Método que devuelve un enlace al objeto CAcceso
		 * que se crea automáticamente en el constructor
		 *
		 * @return CAcceso|null
		 */
		public function Acceso() :?CAcceso
		{
			return $this->_Acceso;
		}

		/**
		 * Método que devuelve un enlace al objeto CACLBD que
		 * se crea automáticamente en el constructor
		 *
		 * @return CACLBD|null
		 */
		public function ACL() :?CACLBD
		{
			return $this->_ACL;
		}
        
		/**
		 * Método que analiza los parámetros de la llamada GET/POST
		 * para obtener un array que corresponde con el controlador/accion
		 * pedido. 
		 *
		 * @return array|null Devuelve el array de la forma [controlador,accion]
		 */
		public function analizaURL():?array
		{
			$ac="";
			$co="";
			
			if (isset($_REQUEST["co"]))
					$co=trim($_REQUEST["co"]);
			
			if (isset($_REQUEST["ac"]))
					$ac=trim($_REQUEST["ac"]);
			
			unset($_REQUEST["co"]);
			unset($_REQUEST["ac"]);
			unset($_GET["co"]);
			unset($_GET["ac"]);
			unset($_POST["co"]);
			unset($_POST["ac"]);
			
			$res=array();
			
			if ($co!="")
			    {
			    	$res[]=$co;
					if ($ac!="")
					    {
					    	$res[]=$ac;
					    }
			    }
				
			if ($res!=array())
			    return $res;
			  else
				return null;	
		
		}
		
		/**
		 * Este método devuelve una cadena que se corresponde con la URL
		 * que se genera a partir de los parámetros indicados.
		 *
		 * @param array|string $accion Cadena con URL o array en formato [controlador,accion]
		 * @param array|null $parametros Array con parámetros que se añadirán a la URL
		 * @return string Cadena con URL generada
		 */
		public function generaURL(array|string $accion, array $parametros=null):string
		{
			$ruta="";
            
            if (is_array($accion))
                {
                	if (!$this->_URL_AMIGABLES)
					  {
						if ($accion)
	        			{
	        				$ruta="co=".$accion[0];
	        				if (isset($accion[1]))
	        				   $ruta.="&ac=".$accion[1];
	        			}
	        			
	        			if ($parametros)
	        			{
	        				
	        				if ($ruta!="")
	        				   	$ruta.="&";	
	        				$ruta.=$this->generaCadenaParametros($parametros);	
	        				
	        			}
	        			$final="/index.php";
	        			if ($ruta!="")
	        			   {
	        			   	$final.="?".$ruta;
	        			   }
					  }
					else
					  {
					  	if ($accion)
	        			{
	        				$ruta="$accion[0]";
	        				if (isset($accion[1]))
	        				   $ruta.="/$accion[1]";
						    $ruta.="";
	        			}
	        			
	        			if ($parametros)
	        			{
	        				$aux=$this->generaCadenaParametros($parametros);
							$ruta.="/$aux";
	        			}
	        			$final="/";
	        			if ($ruta!="")
	        			   {
	        			   	$final.=$ruta;
	        			   }
					  }	 
                }
              else 
			    {
			    	
			        $final="";
					
					if ($parametros)
	        			{
							$final=$this->generaCadenaParametros($parametros);
	        			}
					if (strpos($accion,"=")!==false)
					    {
					    	$final="&amp;$final";
					    }
					
			  		if (strpos($accion,".")!==false)
			  			{
			  				if (strpos($accion,"?")===false)
							  {
							  	$accion.="?";
							  }
			  			   	$final=$accion.$final;
						}	
					  else
					  	{
					  		if (strpos($accion,"=")===false) 
					  				$final="/$final";
						    $final=$accion.$final;
					  	}	
					  	
			    }
			return $final;
		}


		/**
		 * Método privado que convierte un array con opciones para una 
		 * URL en la cadena correspondiente
		 *
		 * @param array $parametros Array con opciones para la URL
		 * @return string Cadena obtenida
		 */
		private function generaCadenaParametros(array $parametros):string
		{
			$aux="";
			foreach ($parametros as $clave => $valor) 
			{
			   if (is_array($valor))
			       {
			       	  foreach($valor as $c=>$v)
					  {
					  	if ($aux!="")
					     	$aux.="&";	
					    $aux.="{$clave}[$c]=$v";
					  }
			       }
			     else 
				   {	
					   if ($aux!="")
					     	$aux.="&";	
					   $aux.="$clave=$valor";
				   }	
			}
			
			return $aux;
		}
		
		/**
		 * Método encargado de ejecutar el código correspondiente al 
		 * controlador/accion indicados como parámetro
		 *
		 * @param array $accion Acción a ejecutar
		 * @return void
		 */
		public function ejecutaControlador(array $accion)
		{
			$contro=$this->_controlDefecto;
			if ($accion)
			{
				$contro=$accion[0];
			}
			$contro=$contro."Controlador";
			
			//compruebo si existe el controlador
			$ruta=RUTA_BASE."/aplicacion/controladores/".$contro.".php";
			if (!file_exists($ruta))
					{
						$this->paginaError(404,"Pagina no encontrada");
						exit;
					}
			
			if (!class_exists($contro,false))
			      include($ruta);
				
			//creo una instancia para el controlador
			$contro=new $contro();
			
			$acc=$contro->accionDefecto;
			if (isset($accion[1]))
			 	$acc=$accion[1];
			
			if (!$contro->ejecutar($acc))
					{
						$this->paginaError(404,"Pagina no encontrada");
						exit;
					}
		}
		
		/**
		 * Método que redirige el navegador hacia la URL obtenida a partir de
		 * los parámetros indicados 
		 *
		 * @param array|string Cadena o array (en forma [controlador,accion]) que corresponde con la URL
		 * @param array|null $parametros Parámetros a indicar en la URL
		 * @return void
		 */
		public function irAPagina(array|string $accion,array $parametros=null)
		{
		    $url=$this->generaURL($accion,$parametros);
                  
			header("location: ".$url);
		}
		
		/**
		 * Método que ejecuta la acción que se ha indicado en la llamada
		 *
		 * @return void
		 */
		public function ejecutar()
		{
			$accion=$this->analizaURL();
			if (!$accion)
			{
				$accion=array($this->_controlDefecto);
			}
			
			$this->ejecutaControlador($accion);
		}
		
		/**
		 * Método que muestra la ventana de error con contenido el indicado
		 * en los parámetros de llamada
		 *
		 * @param integer $numError
		 * @param string|null $mensaje
		 * @return void
		 */
		public function paginaError(int $numError,string $mensaje=null)
		{
			$errores=array(404=>"Pagina no encontrada",
							400=>"Solicitud incorrecta");
							
							
			$numError=intval($numError);
			
			if ($mensaje==null)
			   {
			   	 if (isset($errores[$numError]))
				      $mensaje=$errores[$numError];
				     else
				 	  $mensaje="Se ha producido un error."; 
			   }
			
			$ruta=RUTA_BASE."/aplicacion/vistas/plantillas/error.php";
			
			if (file_exists($ruta))
			    include($ruta);
			   else 
			    {
			    	echo "Error $numError: $mensaje";				
				}
			exit;
		}
		
		/**
		 * Métodos que controlan la sobrecarga dinámica para las propiedades
		 * de aplicación creada en el constructor e indicadas en el archivo
		 * config.php
		 */
		public function __set($nombre,$valor)
		{
			if (!isset($this->_prop[$nombre]))
			       throw new Exception("La propiedad no es valida", 1);
				
			$this->_prop[$nombre]=$valor;
		}
		
		public function __get($nombre)
		{
			if (!isset($this->_prop[$nombre]))
			       throw new Exception("La propiedad no es valida", 1);
				
			return ($this->_prop[$nombre]);
		}
		
		public function __isset($nombre)
		{
			return isset($this->_prop[$nombre]);
		}
		
		public function __unset($nombre)
		{
			if (isset($this->_prop[$nombre]))
			      unset($this->_prop[$nombre]);
			
		}
	}
