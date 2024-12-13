<?php
	/**
	 * Clase base que controla el funcionamiento de los controladores en
	 * nuestr FrameWork
	 * 
	 */
	class CControlador
	{
		public $accionDefecto="index";
		public $plantilla="main";
		
		public function __construct()
		{
			;
		}
		
		/**
		 * Método que ejecuta la acción indicada. Si no se encuentra devuelve
		 * false
		 *
		 * @param string $accion Cadena con la acción a ejecutar
		 * @return boolean True si se encuentra la acción. False en caso
		 * contrario
		 */
		public function ejecutar(string $accion):bool
		{
			$accion=strtolower($accion);
			//comprobar si se permite ejecutar la accion
			
			
			//busco el método para la acción	
			$_nombreFuncion='accion'.strtoupper(substr($accion,0,1)).
									substr($accion,1);
			
			
			if (!method_exists($this, $_nombreFuncion))
			    return false;
			
			//ejecuto la accion
			$this->$_nombreFuncion();
			
			return true;
		}
		
		/**
		 * Método que rellena la vista dada con las variables indicadas.
		 * Devuelve la cadena correspondiente al código html generado 
		 * (parámetro $devolver a true) o un valor booleano si se ha 
		 * podido o no generar la vista
		 * La vista no contiene la parte correspondiente a la plantilla
		 *
		 * @param string $vista
		 * @param array $variables
		 * @param boolean $devolver
		 * @return boolean|string
		 */
		public function dibujaVistaParcial(string $vista, array $variables=array(),bool $devolver=false):bool|string
		{
			//existe la vista
			$_ruta=get_class($this);
			$_ruta=str_replace('Controlador', '', $_ruta);
			$_ruta=RUTA_BASE.'/aplicacion/vistas/'.$_ruta.'/'.$vista.'.php';
			if (!file_exists($_ruta))
			    return false;
			
			//definir las variables
			foreach($variables as $_var=>$_valor)
			{
				$$_var=$_valor;
			}
			//iniciar captura de salida
			ob_start();
			
			//incluir el fichero de la vista
			include($_ruta);
			//finalizar captura de salida
			$_salida=ob_get_contents();
			
			ob_end_clean();
			//operar segun $devolver
			
			if ($devolver)
			    return $_salida;
			   else
			   	{
			   		echo $_salida;
			   		return true;
				}
		}
		
		/**
		 * Método que se encarga de dibujar la vista indicada.
		 * Se le pasan como parámetros el nombre de la vista, variables locales 
		 * a la vista y el titulo de la página
		 *
		 * @param string $vista Cadena que contiene el nombre de la vista. Debe corresponderse
		 * con un archivo php del mismo nombre que se sitúe en /aplicacion/vistas/nombre_controlador
		 * @param array $variables Array con variables locales que se usarán en la vista
		 * @param string $titulo Título que aparecerá en el navegador para la ventana
		 * @return void
		 */
		public function dibujaVista(string $vista, array $variables=[], string $titulo="aplicacion"):bool
		{
			//comprobamos si existe la plantilla
			$_ruta=RUTA_BASE.'/aplicacion/vistas/plantillas/'.
						$this->plantilla.'.php';
						
		    if (!file_exists($_ruta))
			     return false;
			
			//cargo la vista parcial
			$contenido=$this->dibujaVistaParcial($vista, $variables,true);
			
			//incluyo la plantilla
			include($_ruta);
			return true;
		}
		
		
		
		
	}
