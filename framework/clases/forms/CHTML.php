<?php
	/**
	 * Clase estática con métodos para "dibujar" las etiquetas HTML.
	 * Aporta una serie de métodos que facilitan el dibujar las 
	 * etiquetas HTML mas usuales
	 * 
	 */
	class CHTML{
		
		private static $_errorCSS="error";
		private static $_cerrarEtiquetasUnicas=true;
		private static $_prefijoID="id_";
		private static $_numID=0;
		
		/**
		 * Método que transforma un array de atributos en una cadena
		 * que representa a los mismos 
		 *
		 * @param array $atributosHTML Array con los atributos HTML
		 * @return string Cadena generada con los atributos
		 */
		public static function dibujaOpciones(array $atributosHTML=[]):string
		{
			$especiales=array("async"=>1, "autofocus"=>1, "autoplay"=>1, 
								"checked"=>1, "controls"=>1, 
								"declare"=>1, "default"=>1,
								"defer"=>1, "disabled"=>1, 
								"formnovalidate"=>1, "hidden"=>1, "ismap"=>1, 
								"loop"=>1, "multiple"=>1, 
								"muted"=>1, "nohref"=>1, 
								"noresize"=>1, "novalidate"=>1,
								"open"=>1, "readonly"=>1, "required"=>1, 
								"reversed"=>1, "scoped"=>1, "seamless"=>1, 
								"selected"=>1, "typemasmatch"=>1);
								
			$cadena="";
			foreach($atributosHTML as $atributo=>$valor)
			{
				if ($cadena<>'')
				    $cadena.=" ";
				
				if (isset($especiales[$atributo]))
				    $cadena.=$atributo.'="'.$atributo.'"';
				  else
				  	$cadena.=$atributo.'="'.$valor.'"';
			}
			return $cadena;
			
		}
		
		/**
		 * Método que devuelve una cadena que se corresponde con la etiqueta 
		 * HTML del tipo que se indica.
		 * Debe indicarse al menos el tipo de etiqueta para la que generar el código HTML
		 * Se le indicarán los atributos HTML que se añadirán en la etiqueta.
		 * Se puede indicar el contenido que aparecerá entre la etiqueta de apertura
		 * y cierre. Si no se indica contenido se considera una etiqueta de solo apertura
		 * Si se indica false en $cerrarEtiqueta, no nos devolverá etiqueta de cierre
		 *
		 * @param string $tipoEtiqueta Cadena con tipo de etiqueta (B, DIV, SPAN, ....)
		 * @param array $atributosHTML Atributos a incorporar en la etiqueta de apertura
		 * @param string|null $contenido Contenido a poner entre la etiqueta de apertura y cierre. 
		 * Si no se indica se considera que es etiqueta única (sin cierre)
		 * @param boolean $cerrarEtiqueta Genera la etiqueta de cierre
		 * @return string Devuelve el código HTML que corresponde a la etiqueta
		 */
		public static function dibujaEtiqueta(string $tipoEtiqueta,array $atributosHTML=[],string $contenido=null,
											bool $cerrarEtiqueta=true):string
		{
			$cadena="";
			$cadena.="<".$tipoEtiqueta." ".self::dibujaOpciones($atributosHTML);
			if ($contenido===null) //no hay contenido, etiqueta unica
			     {
			     	if (self::$_cerrarEtiquetasUnicas)
					     $cadena.='/';
					$cadena.=">";
			     }
				else //hay un contenido
				 {
				    $cadena.=" >".$contenido;
					if ($cerrarEtiqueta)
			    			$cadena.="</".$tipoEtiqueta.">";
				 }
			return $cadena;
			
		}
		
		/**
		 * Método que devuelve una cadena con la etiqueta de cierre para el tipo
		 * indicado
		 *
		 * @param string $tipoEtiqueta tipo de etiqueta para la que
		 *  generar la etiqueta de cierre
		 * @return string
		 */
		public static function dibujaEtiquetaCierre(string $tipoEtiqueta):string
		{
			$cadena="</".$tipoEtiqueta.">";
			
			return $cadena;
		}
		
		/**
		 * Método que genera un identificador distinto una etiqueta
		 * Tendrá la forma prefijo1. El prefijo se establece en una propiedad.
		 * El número se rellenará en secuencia 
		 * @return string
		 */
		public static function generaID():string
		{
			$cadena=self::$_prefijoID.self::$_numID;
			self::$_numID++;
			
			return $cadena;
		}
		/**
		 * Método que devuelve un input de tipo Button rellena con los 
		 * parámetos indicados.
		 *
		 * @param string $value Valor a mostrar en el botón
		 * @param array $atributosHTML
		 * @return string
		 */
		public static function boton(string $value,array $atributosHTML=[]):string
		{
			if (!isset($atributosHTML["name"]))
			{
				$atributosHTML["name"]=self::generaID();
			}
			
			if (!isset($atributosHTML["type"]))
				$atributosHTML["type"]="button";
			
			if (!isset($atributosHTML["value"]))
				$atributosHTML["value"]=$value;
			
			return self::dibujaEtiqueta("input",$atributosHTML);
		}	
		
		/**
		 * Método que devuelve una etiqueta style con los parámetros indicados
		 *
		 * @param string $texto Contenido que aparecerá entre la etiqueta 
		 * de apertura y cierre 
		 * @param string $medio Medio que se pondrá en el atributo media
		 * @return string
		 */
		public static function css(string $texto,string $medio=""):string
		{
			if ($medio!=="")
				$medio=' media="'.$medio.'"';
			
			$cadena="<style type=\"text/css\"{$medio}>\n{$texto}\n</style>";
			
			return $cadena; 
		}

		/**
		 * Método que devuelve una cadena HTML para un enlace a un fichero CSS
		 *
		 * @param string $url URL del fichero CSS a enlazar 
		 * @param string $medio Atributos HTML
		 * @return string
		 */
		public static function cssFichero(string $url,string $medio=""):string
		{
			return self::linkHead("stylesheet","text/css",$url,
									$medio!=='' ? $medio : null);
		}
		
		/**
		 * Método que genera una etiqueta BUTTON con texto $etiqueta
		 *
		 * @param string $etiqueta Texto del boton
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function botonHtml(string $etiqueta,array $atributosHTML=[]):string
		{
		   if (!isset($atributosHTML["name"]))
		   		$atributosHTML["name"]=self::generaID();
		   
		   if (!isset($atributosHTML["type"]))
		   		$atributosHTML["type"]="button";
		   
		   return self::dibujaEtiqueta("button",$atributosHTML,$etiqueta);
		}
		
		/**
		 * Método que devuelve una etiqueta IMG para la imagen $src.
		 *
		 * @param string $src Ruta a la imagen
		 * @param string $alt Texto alternativo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function imagen(string $src,string $alt="",array $atributosHTML=[]):string
		{
			$atributosHTML["src"]=$src;
			$atributosHTML["alt"]=$alt;
			
			return self::dibujaEtiqueta("img",$atributosHTML);
		}
		
		/**
		 * Método que devuelve una etiqueta a para la dirección indicada
		 *
		 * @param string $texto Texto a poner entre las etiquetas de apertura y cierre 
		 * @param string $url Url que poner en el atributo HREF. Puede ser una 
		 * cadena o array en la forma [controlador, accion]
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function link(string $texto,string|array $url="#",array $atributosHTML=[]):string 
		{
			if ($url!=="")
				$atributosHTML["href"]=self::normalizaURL($url);
			
			return self::dibujaEtiqueta("a",$atributosHTML,$texto);
		}
		
		/**
		 * Método que devuelve una etiqueta de tipo link que se utiliza
		 * para indicar en el head el enlace a un recurso externo.
		 *
		 * @param string|null $rel Atributo rel de la etiqueta link
		 * @param string|null $type Atributo type de la etiqueta link
		 * @param string|null $href Dirección del recurso
		 * @param string|null $media Atributo media de la etiqueta link
		 * @param array $atributosHTML Otros atributos HTML
		 * @return string
		 */
		public static function linkHead(string $rel=null, string $type=null,
					string $href=null, string $media=null,array $atributosHTML=[]):string
		{
			if ($rel!==null)
				$atributosHTML["rel"]=$rel;
			if ($type!==null)
				$atributosHTML["type"]=$type;
			if ($href!==null)
				$atributosHTML["href"]=$href;
			if ($media!==null)
				$atributosHTML["media"]=$media;
			
			return self::dibujaEtiqueta("link",$atributosHTML);
		}
		
		/**
		 * Método que devuelve una etiqueta META para indicar en el HEAD con
		 * una cabecera HTML
		 *
		 * @param string $content Contenido del Meta
		 * @param string|null $name Name del Meta
		 * @param string|null $httpEquiv http-equiv del meta
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function metaHead(string $content, string $name=null,
								string $httpEquiv=null, array $atributosHTML=[]):string
		{
			if ($name!==null)
				$atributosHTML["name"]=$name;
			if ($httpEquiv!==null)
				$atributosHTML["http-equiv"]=$httpEquiv;
			$atributosHTML["content"]=$content;
			
			return self::dibujaEtiqueta("meta",$atributosHTML);
		}
		
		/**
		 * Método que devuelve la URL correspondiente al parámetro indicado
		 *
		 * @param array|string $url 
		 * @return string
		 */
		public static function normalizaURL(array|string $url):string
		{
			if (is_array($url))
				return Sistema::app()->generaURL($url);
			if ($url=="")
				$url="#";
			return $url;
		}
		
		/**
		 * Método que devuelve una etiqueta SCRIPT con tipo Javascript y contenido
		 * $texto. La etiqueta tendrá los atributos que se le indican
		 *
		 * @param string $texto Contenido es la etiqueta script
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function script(string $texto,array $atributosHTML=[]):string
		{
			if (!isset($atributosHTML["type"]))
				$atributosHTML["type"]="text/javascript";
			
			
			return self::dibujaEtiqueta("script",$atributosHTML,$texto); 
		}

		/**
		 * Método que devuelve una etiqueta SCRIPT enlazada al fichero que se indica 
		 * con su URL
		 *
		 * @param string $url URL del fichero a enlazar
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function scriptFichero(string $url,array $atributosHTML=[]):string
		{
			if (!isset($atributosHTML["type"]))
				$atributosHTML["type"]="text/javascript";
			if (!isset($atributosHTML["src"]))
				$atributosHTML["src"]=$url;
			
			
		
			return self::dibujaEtiqueta("script",$atributosHTML,"");
		}
		
		
		//***********************************************
		//metodos para dibujar componentes de formulario 
		//***********************************************
		
		/**
		 * Método protegido que devuelve un NAME válido para una etiqueta del 
		 * formulario a partir de $name
		 *
		 * @param string $name Cadena a convertir en NAME
		 * @return string
		 */
		protected static function dameIdDeNombre(string $name):string
		{
			return str_replace(array('[]','][','[',']',' '),array('','_','_','','_'),$name);
		}
		
		
		/**
		 * Método protegido que devuelve una cadena correspondiente a una etiqueta
		 * para un formulario. Se le indica el tipo (type), nombre (name), valor (value)
		 * de la etiqueta y atributos HTML que tendrá.
		 *
		 * @param string $tipo Tipo de etiqueta de formulario
		 * @param string $nombre Name de la etiqueta
		 * @param string|integer|float $valor  Value de la etiqueta
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		protected static function campoInput(string $tipo,string $nombre,
					string|int|float $valor,array $atributosHTML=[]):string
		{
			$atributosHTML["type"]=$tipo;
			$atributosHTML["value"]=$valor;
			$atributosHTML["name"]=$nombre;
			if (!isset($atributosHTML["id"]))
			     $atributosHTML["id"]=self::dameIdDeNombre($nombre);
			
			if ($atributosHTML["id"]===false)
			    unset($atributosHTML["id"]);
			
			return self::dibujaEtiqueta("input",$atributosHTML);
			
		}
		
		/**
		 * Método que dibuja una etiqueta input de Tipo Checkbox con el nombre indicado.
		 * Se puede indicar en los Atributos HTML el valor uncheckValor que creará una
		 * etiqueta input de tipo HIDDEN con el valor indicado para el caso de que 
		 * no se marche el checked
		 *
		 * @param string $nombre Name para el input Checked
		 * @param boolean $checked  Value
		 * @param array $atributosHTML Atributos HTML. Se tendrá el posible atributo
		 * uncheckValor que va a permitir incluir una etiqueta input HIDDEN con el valor 
		 * indicado para el caso de que no se marque el checked.
		 * @return string
		 */
		public static function campoCheckBox(string $nombre,bool $checked=false, 
				array $atributosHTML=[]):string
		{
			if($checked)
		        $atributosHTML['checked']='checked';
		    else
		        unset($atributosHTML['checked']);
			
		    $valor=isset($atributosHTML['value']) ? $atributosHTML['value'] : 1;
		
		    if(array_key_exists('uncheckValor',$atributosHTML))
		    {
		        $uncheckValor=$atributosHTML['uncheckValor'];
		        unset($atributosHTML['uncheckValor']);
		    }
		    else
		        $uncheckValor=null;
		
		    if($uncheckValor!==null)
		    {
		        /**
				 * Si en $atributosHTML aparece la opcion uncheckValor, se añade un
				 * campo oculo con el valor de uncheck para que siempre se envie el campo
				 * en el formulario: value en el caso de que este marcado y valor unchecked 
				 * en caso de que no este marcado.
				 */
				 
				 if(isset($atributosHTML['id']) && $atributosHTML['id']!==false)
			            $atributosUncheck=array('id'=>"ocul_".$atributosHTML['id']);
			        else
			            $atributosUncheck=array('id'=>false);
					
		        $oculto=self::campoHidden($nombre,$uncheckValor,$atributosUncheck);
		    }
		    else
		        $oculto='';
		
			if(array_key_exists('etiqueta',$atributosHTML))
			{
				$etiqueta=$atributosHTML['etiqueta'];
				unset($atributosHTML['etiqueta']);
				$etiqueta=" ".self::campoLabel($etiqueta, isset($atributosHTML["id"])?$atributosHTML["id"]:"");
			}
			else
				$etiqueta="";
		    return $oculto . self::campoInput('checkbox',$nombre,$valor,$atributosHTML)." ".$etiqueta;
		}
		
		/**
		 * Método que permite dibujar una serie de input de tipo CHECKBOX.
		 * En los atributos HTML se puede indicar unchekValor.
		 *
		 * @param string $nombre Nombre que tendrán los distintos inputs 
		 * @param array|integer|string $seleccionado Se puede indicar un solo
		 * valor o un array de valores que representan los inputs que apareceran 
		 * con valor checked a 1
		 * @param array $datos Array con todos los elementos para los que se 
		 * crearán etiquetas input CHECKBOX. Cada elemento del array debe ser de
		 * la forma value (para el input)=> texto (texto que aparecerá junto al input) 
		 * @param string $separador Cadena para indicar como se separa un input del siguiente
		 * @param array $atributosHTML Atributos HTML. Hay un atributo especial uncheckValor
		 * que hace que se cree automáticamente un input HIDDEN con el valor que se indica
		 * y que se asignaría en el envío del formulario en caso de que no se marcará 
		 * ningún CHECKED
		 * @return string
		 */
		public static function campoListaCheckBox(string $nombre,array|int|string $seleccionado, 
						array $datos,string $separador="<br/>\n",
						array $atributosHTML=[]):string
		{
			if(substr($nombre,-2)!=='[]')
				$nombre.='[]';
				
			$elementos=array();
			$IDBase=self::dameIdDeNombre($nombre);

			$id=0;
			
			if(array_key_exists('uncheckValor',$atributosHTML))
		    {
		        $uncheckValor=$atributosHTML['uncheckValor'];
		        unset($atributosHTML['uncheckValor']);
		    }
		    else
		        $uncheckValor=null;
		
		    if($uncheckValor!==null)
		    {
		        /**
				 * Si en $atributosHTML aparece la opcion uncheckValor, se añade un
				 * campo oculto con el valor de uncheck para que siempre se envie el campo
				 * en el formulario: value en el caso de que este marcado y valor unchecked 
				 * en caso de que no este marcado.
				 */
				 $nombreUncheck=str_replace('[]','',$nombre);
				 if(isset($atributosHTML['id']) && $atributosHTML['id']!==false)
			            $atributosUncheck=array('id'=>"ocul_".$atributosHTML['id']);
			        else
			            $atributosUncheck=array('id'=>false);
					
		        $oculto=self::campoHidden($nombreUncheck,$uncheckValor,$atributosUncheck);
				
		    }
			else
					$oculto='';
			

			foreach($datos as $valor=>$textoEtiqueta)
			{
				$checked=(!is_array($seleccionado) && ($valor==$seleccionado)) || 
						  (is_array($seleccionado) && in_array($valor,$seleccionado));
				$atributosHTML['value']=$valor;
				$atributosHTML['id']=$IDBase.'_'.$id++;
				$opcion=self::campoCheckBox($nombre,$checked,$atributosHTML);
				$etiqueta=self::campoLabel($textoEtiqueta,$atributosHTML['id']);
				$elementos[]=$opcion." ".$etiqueta;
			}
			
			return $oculto.implode($separador,$elementos);
			
			//return implode($separador,$elementos);
		}
		
		/**
		 * Método que dibuja una ediqueta input de tipo DATE con el nombre indicado
		 *
		 * @param string $nombre  Name para la etiqueta
		 * @param string $valor   Value para la etiqueta
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoDate(string $nombre,string $valor="",
							array $atributosHTML=[]):string
		{
			return self::campoInput("date", $nombre, $valor,$atributosHTML);
		}
		/**
		 * Método que dibuja una etiqueta SELECT con el nombre y opciones indicadas
		 *
		 * @param string $nombre Name de la etiqueta
		 * @param integer|string $seleccionado Opción seleccionada
		 * @param array $datos Array con las opciones a incluir. Será un array donde 
		 * cada elemento será de la forma value => texto
		 * @param array $atributosHTML Atributos HTML. Hay una opción especial llamada
		 * linea que permite indicar cual es el texto de la primera línea. Por defecto,
		 * toma el valor "Seleccione una opción". Si se asigna false a este atributo no
		 * se pone una linea inicial.
		 * @return string
		 */
		public static function campoListaDropDown(string $nombre,
						int|string $seleccionado, array $datos,array $atributosHTML=[]):string
		{
			$atributosHTML['name']=$nombre;
			
			if(!isset($atributosHTML['id']))
					$atributosHTML['id']=self::dameIdDeNombre($nombre);
				else
					if($atributosHTML['id']===false)
						unset($atributosHTML['id']);
			
			$linea="Seleccione una opcion";
			if (isset($atributosHTML["linea"]))
			   {
				$linea=$atributosHTML["linea"];
				unset($atributosHTML["linea"]);
			   }
			 
			$elementos=array();
			 
			if ($linea!==false)
			    {
					$atribu=array();
					$atribu["value"]="";

					$elementos[]=self::dibujaEtiqueta("option",$atribu,$linea);
				}				   			
			
			foreach($datos as $valor=>$textoEtiqueta)
				{
					$atribu=array();
					if ($valor==$seleccionado)
						$atribu["selected"]="selected";
					$atribu["value"]=$valor;
					
					$elementos[]=self::dibujaEtiqueta("option",$atribu,$textoEtiqueta);
				}
				
			$opciones=implode("\n",$elementos);

			return self::dibujaEtiqueta('select',$atributosHTML,$opciones);
				
			
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo
		 * email
		 *
		 * @param string $nombre  Name para el input
		 * @param string $valor Value para el input
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoEmail(string $nombre,string $valor="",array $atributosHTML=[]):string
		{
			return self::campoInput("email", $nombre, $valor,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena correspondiente a un input de tipo FILE
		 *
		 * @param string $nombre Name para el input
		 * @param string $valor Value para el input
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoFile(string $nombre, string $valor="",array $atributosHTML=[]):string
		{
			return self::campoInput("file", $nombre, $valor,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena correspondiente a un input de tipo HIDDEN
		 *
		 * @param string $nombre Name del input
		 * @param string $valor Value del input
		 * @param array $atributosHTML atributos HTML
		 * @return string
		 */
		public static function campoHidden(string $nombre, string $valor="", array $atributosHTML=[]):string
		{
			return self::campoInput("hidden", $nombre, $valor,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una etiqueta LABEL
		 *
		 * @param string $etiqueta Texto de la etiqueta
		 * @param string $for Name del input al que está enlazado. Si vale false
		 * se quita el atributo FOR 
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoLabel(string $etiqueta,string $for,array $atributosHTML=[]):string
		{
			if ($for===false)
			     unset($atributosHTML["for"]);
				else
				 $atributosHTML["for"]=$for;
			
			return self::dibujaEtiqueta("label",$atributosHTML,$etiqueta);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo NUMBER
		 *
		 * @param string $nombre Name para el input
		 * @param string $valor Value para el input
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoNumber(string $nombre, string|int|float $valor="",
							array $atributosHTML=[]):string
		{
			return self::campoInput("number", $nombre, $valor,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo PASSWORD
		 *
		 * @param string $nombre Name para el input
		 * @param string $valor Value para el input
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoPassword(string $nombre, string $valor="", array $atributosHTML=[]):string
		{
			return self::campoInput("password", $nombre, $valor,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo RADIO
		 * Entre los atributos HTML se puede indicar "etiqueta" y "uncheckValor" que 
		 * permiten respectivamente indicar un texto que acompañará al input y el valor 
		 * que se enviará en el formulario para el name indicado en caso de no marcarse
		 * el radio.
		 *
		 * @param string $nombre Name para el input
		 * @param boolean $checked Si aparecerá o no el radio marcado inicialmente
		 * @param array $atributosHTML Atributos HTML. Se pueden indicar dos atributos
		 * especiales: linea y uncheckValor.
		 * - Linea: añado un label con el texto indicado que acompachará al input
		 * - uncheckValor: indica el value que se enviará si no se marca el Radio
		 * @return string
		 */
		public static function campoRadioButton(string $nombre, bool $checked=false, array $atributosHTML=[]):string
		{
			if($checked)
		        $atributosHTML['checked']='checked';
		    else
		        unset($atributosHTML['checked']);
			
		    $valor=isset($atributosHTML['value']) ? $atributosHTML['value'] : 1;
		
		    if(array_key_exists('uncheckValor',$atributosHTML))
		    {
		        $uncheckValor=$atributosHTML['uncheckValor'];
		        unset($atributosHTML['uncheckValor']);
		    }
		    else
		        $uncheckValor=null;
		
		    if($uncheckValor!==null)
		    {
		        /**
				 * Si en $atributosHTML aparece la opcion uncheckValor, se añade un
				 * campo oculo con el valor de uncheck para que siempre se envie el campo
				 * en el formulario: value en el caso de que este marcado y valor unchecked 
				 * en caso de que no este marcado.
				 */
				 
				 if(isset($atributosHTML['id']) && $atributosHTML['id']!==false)
			            $atributosUncheck=array('id'=>"ocul_".$atributosHTML['id']);
			        else
			            $atributosUncheck=array('id'=>false);
					
		        $oculto=self::campoHidden($nombre,$uncheckValor,$atributosUncheck);
		    }
		    else
		        $oculto='';
			
			if(array_key_exists('etiqueta',$atributosHTML))
			{
				$etiqueta=$atributosHTML['etiqueta'];
				unset($atributosHTML['etiqueta']);
				$etiqueta=" ".self::campoLabel($etiqueta, isset($atributosHTML["id"])?$atributosHTML["id"]:"");
			}
			else
				$etiqueta="";
		    
		    return $oculto . self::campoInput('radio',$nombre,$valor,$atributosHTML)." ".$etiqueta;
		}
		
		/**
		 * Método que devuelve una cadena con varios input de tipo RADIO según
		 * lo indicado en datos.
		 * En atributos HTML hay un valor especial "uncheckValor" que será el value
		 * a enviar en el formulario si no se pulsa ningún RADIO
		 *
		 * @param string $nombre Name para los inputs
		 * @param string|integer|array $seleccionado Input que aparecerá marcado
		 * @param array $datos Radios a crear. Es un array donde cada elemento será de la forma
		 * value => etiqueta, siendo respectivamente el value para el input y la etiqueta para
		 * el input
		 * @param string $separador Cadena con código HTML que se usa entre dos Radios consecutivos
		 * @param array $atributosHTML Atributos HTML. Tenemos el atributo especial uncheckValor que
		 * será el value que se enviará en el formulario en caso de no marcar ningún 
		 * Radio
		 * @return string
		 */
		public static function campoListaRadioButton(string $nombre,string|int|array $seleccionado, 
					array $datos, string $separador="<br/>\n", array $atributosHTML=[]):string
		{
				
			$elementos=array();
			$IDBase=self::dameIdDeNombre($nombre);

			$id=0;
			
			if(array_key_exists('uncheckValor',$atributosHTML))
		    {
		        $uncheckValor=$atributosHTML['uncheckValor'];
		        unset($atributosHTML['uncheckValor']);
		    }
		    else
		        $uncheckValor=null;
		
		    if($uncheckValor!==null)
		    {
		        /**
				 * Si en $atributosHTML aparece la opcion uncheckValor, se añade un
				 * campo oculo con el valor de uncheck para que siempre se envie el campo
				 * en el formulario: value en el caso de que este marcado y valor unchecked 
				 * en caso de que no este marcado.
				 */
				 
				 if(isset($atributosHTML['id']) && $atributosHTML['id']!==false)
			            $atributosUncheck=array('id'=>"ocul_".$atributosHTML['id']);
			        else
			            $atributosUncheck=array('id'=>false);
					
		        $oculto=self::campoHidden($nombre,$uncheckValor,$atributosUncheck);
				
		    }
			else
					$oculto='';

			foreach($datos as $valor=>$textoEtiqueta)
			{
				$checked=(!is_array($seleccionado) && ($valor==$seleccionado)) || 
						  (is_array($seleccionado) && in_array($valor,$seleccionado));
				$atributosHTML['value']=$valor;
				$atributosHTML['id']=$IDBase.'_'.$id++;
				$opcion=self::campoRadioButton($nombre,$checked,$atributosHTML);
				$etiqueta=self::campoLabel($textoEtiqueta,$atributosHTML['id']);
				$elementos[]=$opcion." ".$etiqueta;
			}
			
			
			return $oculto.implode($separador,$elementos);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo RANGE
		 *
		 * @param string $nombre Name para el input
		 * @param string $valor Value para el input
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoRange(string $nombre, string $valor="", array $atributosHTML=[]):string
		{
			return self::campoInput("range", $nombre, $valor,$atributosHTML);
		}
		

		/**
		 * Método que devuelve una cadena que corresponde a un boton RESET
		 * para el formulario
		 *
		 * @param string $etiqueta Etiqueta que aparecerá en el botón
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoBotonReset(string $etiqueta="Limpiar",array $atributosHTML=[]):string
		{
			$atributosHTML["type"]="reset";
			return self::boton($etiqueta,$atributosHTML);
		}
		
		/**
		 * Método que devuelve un string que corresponde a un botón SUBMIT para el
		 * formulario
		 *
		 * @param string $etiqueta Etiqueta que aparecerá en el botón
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoBotonSubmit(string $etiqueta="Enviar", array $atributosHTML=[]):string
		{
			$atributosHTML["type"]="submit";
			return self::boton($etiqueta,$atributosHTML);
		}
		
		/**
		 * Método que devuelve un input de tipo TEXT
		 *
		 * @param string $nombre Name para el input
		 * @param string $valor Value para el input
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoText(string $nombre, string $valor="", array $atributosHTML=[]):string
		{
			return self::campoInput("text", $nombre, $valor,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a una etiqueta TEXTAREA
		 *
		 * @param string $nombre Name para el Textarea
		 * @param string $valor Contenido en el textarea
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoTextArea(string $nombre, string $valor="", array $atributosHTML=[]):string
		{
			$atributosHTML["name"]=$nombre;
			if (!isset($atributosHTML["id"]))
			     $atributosHTML["id"]=self::dameIdDeNombre($nombre);
			
			
			return self::dibujaEtiqueta("textarea",$atributosHTML,$valor);
		
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo TIME
		 *
		 * @param string $nombre Name para el input
		 * @param string $valor Value para el input
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoTime(string $nombre, string $valor="", array $atributosHTML=[]):string
		{
			return self::campoInput("time", $nombre, $valor,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena correspondiente a un input de tipo URL
		 *
		 * @param string $nombre Name para el input
		 * @param string $valor Value para el input
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function campoUrl(string $nombre, string $valor="", array $atributosHTML=[]):string
		{
			return self::campoInput("url", $nombre, $valor,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a una etiqueta FORM
		 *
		 * @param string $accion Acción a realizar al enviar el formulario. Puede ser una URL o
		 * un array en el formato [controlador, accion]
		 * @param string $metodo Método para el formulario. Valores posibles get o post
		 * @param array $atributosHTML Atributos HTML 
		 * @return string
		 */
		public static function iniciarForm(string|array $accion="",string $metodo="post", array $atributosHTML=[]):string
		{
			
			$url=self::normalizaURL($accion);
			$atributosHTML["action"]=$url;
			$metodo=strtolower($metodo);
			$atributosHTML["method"]=$metodo;
			
			$formulario=self::dibujaEtiqueta("form",$atributosHTML,"",false);
			$ocultos=array();
			
			if (($metodo=="get") && (($pos=strpos($url, "?"))!==false))
			{
				foreach(explode("&",substr($url,$pos+1)) as $atributo)
				{
					if (($pos=strpos($atributo,"="))!=false)
					      $ocultos[]=self::campoHidden(substr($atributo,0,$pos),
						  								substr($atributo,$pos+1),
						  								array("id"=>false));
						else
						  $ocultos[]=self::campoHidden($atributo,"",array("id"=>false));
				}
			}
			
			if ($ocultos!==array())
			   {
			   	$ocultos=implode("\n", $ocultos);
				$formulario.="\n".self::dibujaEtiqueta("div",array("display"=>"none"),$ocultos);
			   }
			   
			return $formulario;
		} 
		
		/**
		 * Método que devuelve una cadena que corresponde con el cierre de la
		 * etiqueta Form
		 *
		 * @return string
		 */
		public static function finalizarForm():string
		{
			return "</form>";
		}
		

		//***************************************************************
		//metodos para dibujar componentes de formulario desde un modelo
		//***************************************************************
		
		protected static function addErrorCss(array &$atributosHTML)
		{
			if (isset($atributosHTML["class"]))
				$atributosHTML["class"].=" ".self::$_errorCSS;
			  else
				$atributosHTML["class"]=self::$_errorCSS;
			  	
			
		}
		
		/**
		 * Método que añade en los atributos HTML una entrada para el name  y otra 
		 * para el ID de un campo de un Modelo
		 *
		 * @param CActiveRecord $modelo Modelo 
		 * @param string $atributo Nombre del atributo en el Modelo
		 * @param array $atributosHTML Atributos HTML. Se pasa por referencia.
		 * En este array se generan dos entradas para el NAME y el ID a partir del
		 * Modelo y campo en el modelo.
		 * @return void
		 */
		public static function dameModeloNombreId(CActiveRecord $modelo, string $atributo,array &$atributosHTML)
		{
			if(!isset($atributosHTML['name']))
				$atributosHTML['name']=$modelo->getNombre()."[".$atributo."]";
			
			if(!isset($atributosHTML['id']))
					$atributosHTML['id']=self::dameIdDeNombre($atributosHTML['name']);
				else
					if($atributosHTML['id']===false)
						unset($atributosHTML['id']);
		}
		
		
		/**
		 * Método privado que devuelve una etiqueta input del tipo indicado para el
		 * campo atributo del modelo Modelo
		 *
		 * @param string $tipo Tipo de input
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo en el modelo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		protected static function modeloInput(string $tipo, CActiveRecord $modelo, string $atributo,
							array $atributosHTML=[]):string 
		{
			$atributosHTML["type"]=$tipo;
			if (!isset($atributosHTML["value"]))
					$atributosHTML["value"]=$modelo->$atributo;
				
			return self::dibujaEtiqueta("input",$atributosHTML);
				
		}
		
		/**
		 * Método que devuelve una cadena que corresponde con un DIV con clase ERROR
		 * que servirá para mostrar los errores del campo Atributo del Modelo. Si el
		 * campo no tiene errores, se devolverá la cadena vacía.
		 *
		 * @param CActiveRecord $modelo Modelo sobre el que trabajar
		 * @param string $atributo Campo en el modelo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function modeloError(CActiveRecord $modelo, string $atributo, array $atributosHTML=[]):string
		{
			$errores=$modelo->errorAtributo($atributo);
			if ($errores)
					{
						self::addErrorCss($atributosHTML);
						
						return self::dibujaEtiqueta("div",$atributosHTML,implode("<br>\n",$errores));
					}
				else
					return ""; 
		}
		
		/**
		 * Método que devuelve una cadena con un div de clase error en el que se
		 * mostrarán todos los errores que tiene el Modelo.
		 *
		 * @param CActiveRecord $modelo Módelo para el que mostrar los errores
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function modeloErrorSumario(CActiveRecord $modelo, array $atributosHTML=[]):string
		{
			$errores=$modelo->getErrores();
			if ($errores)
					{
						self::addErrorCss($atributosHTML);
						
						return self::dibujaEtiqueta("div",$atributosHTML,implode("<br>\n",$errores));
					}
				else
					return ""; 
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo CHECKBOX para
		 * el campo Atributo del Modelo.
		 * Se pueden indicar dos atributos HTML especiales: etiqueta y uncheckValor.
		 * Etiqueta sería el texto que acompaña al input y unchekValor sería el valor 
		 * que se enviaría en el formulario en caso de no marcar el checkbox
		 *
		 * @param CActiveRecord $modelo Modelo sobre el que trabajar
		 * @param string $atributo Campo en el modelo
		 * @param array $atributosHTML Atributos HTML. Se tienen dos atributos adicionales: etiqueta y uncheckValor
		 * - etiqueta permite indicar la etiqueta que acompañará al input
		 * - uncheckValor: es el value que se enviará en el formulario en caso
		 * de que no se marque el input
		 * @return string
		 */
		public static function modeloCheckBox(CActiveRecord $modelo, string $atributo, array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo,$atributosHTML);
			
			if(!isset($atributosHTML['value']))
				$atributosHTML['value']=1;
			if(!isset($atributosHTML['checked']) && 
					$modelo->$atributo==$atributosHTML['value'])
				$atributosHTML['checked']='checked';
			
			if(array_key_exists('uncheckValor',$atributosHTML))
			{
				/**
				 * Si en $atributosHTML aparece la opcion uncheckValor, se añade un
				 * campo oculo con el valor de uncheck para que siempre se envie el campo
				 * en el formulario: value en el caso de que este marcado y valor unchecked
				 * en caso de que no este marcado.
				 */
				$uncheck=$atributosHTML['uncheckValor'];
				unset($atributosHTML['uncheckValor']);
			}
			else
				$uncheck='0';
			
			$oculto=$uncheck!==null ? self::campoHidden($atributosHTML['name'],$uncheck,array("id"=>false)) : '';
			
			
			if(array_key_exists('etiqueta',$atributosHTML))
			{
				$etiqueta=$atributosHTML['etiqueta'];
				unset($atributosHTML['etiqueta']);
				$etiqueta=" ".self::campoLabel($etiqueta, $atributosHTML["id"]);
			}
			else
				$etiqueta="";
			
			return $oculto . self::modeloInput('checkbox',$modelo,$atributo,$atributosHTML).$etiqueta;
		}
		
		/**
		 * Método que devuelve una cadena con una serie de inputs de tipo Checkbox
		 * para el campo atributo del Modelo.
		 * Se le indica un array con todas las opciones posibles y se marcarán automáticamente
		 * las correspondientes al valor campo atributo del modelo.
		 * En los atributos HTML se puede indicar el atributo unchekValor que permitirá devolver
		 * un valor con el formulario si no hay ninguna opción marcada
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del Modelo
		 * @param array $datos Array con las opciones a mostrar. Cade elemento será de la forma
		 * value => etiqueta.
		 * @param string $separador Cadena HTML con el texto que separará una opción de la siguiente
		 * @param array $atributosHTML Atributos HTML. Se tiene un atributo especial uncheckValor que 
		 * será el valor que se enviará en el formulario si no hay ninguna opción seleccionada
		 * @return string
		 */
		public static function modeloListaCheckBox(CActiveRecord $modelo, string $atributo,
						array $datos, string $separador="<br/>\n",array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			
			$nombre=$atributosHTML["name"];
			unset($atributosHTML["name"]);
			
			if(array_key_exists('uncheckValor',$atributosHTML))
			    {
			        $uncheck=$atributosHTML['uncheckValor'];
			        unset($atributosHTML['uncheckValor']);
			    }
			    else
			        $uncheck='';
		
		    $opcionesHidden=isset($atributosHTML['id']) ? array('id'=>"ocul_".$atributosHTML['id']) : array('id'=>false);
		    $oculto=$uncheck!==null ? self::campoHidden($nombre,$uncheck,$opcionesHidden) : '';

    		return $oculto . self::campoListaCheckBox($nombre,$modelo->$atributo,$datos,$separador,$atributosHTML);
		}
		
		
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo DATE para
		 * el campo atributo del modelo
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del modelo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function modeloDate(CActiveRecord $modelo,string $atributo, array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("date", $modelo,$atributo,$atributosHTML);
		}
		
		/**
		 * Metodo que devuelve una cadena correspondiente a un select para el campo 
		 * atributo del Modelo dado. Las opciones posibles se indican en datos
		 * En los atributos HTML hay un atributo especial etiqueta que corresponde
		 * con la opción primera que aparecerá en el select
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del Modelo
		 * @param array $datos array con las opciones posibles a mostrar en el select.
		 * SErá un array donde cada elemento será de la forma value => texto opción
		 * @param array $atributosHTML Atributos HTML. Se tiene un atributo especial 
		 * etiqueta que corresponde con la opción primera que aparecerá en el select. 
		 * Por defecto toma el valor "Seleccione una opción". Si se asigna false a este 
		 * atributo, no se mostrará
		 * @return string
		 */
		public static function modeloListaDropDown(CActiveRecord $modelo, string $atributo, array $datos,
								array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			$seleccionado=$modelo->$atributo;
			$nombre=$atributosHTML["name"];
			
			$linea="Seleccione una opcion";
			if (isset($atributosHTML["linea"]))
			   {
				$linea=$atributosHTML["linea"];
				unset($atributosHTML["linea"]);
			   }
			 
			$elementos=array();
			 
			if ($linea!==false)
			    {
					$atribu=array();
					$atribu["value"]="";

					$elementos[]=self::dibujaEtiqueta("option",$atribu,$linea);
				}				   			
				
			foreach($datos as $valor=>$textoEtiqueta)
			{
				$atribu=array();
				if ($valor==$seleccionado)
					$atribu["selected"]="selected";
				$atribu["value"]=$valor;
					
				$elementos[]=self::dibujaEtiqueta("option",$atribu,$textoEtiqueta);
			}
			$opciones=implode("\n",$elementos);
		
			
			return self::dibujaEtiqueta('select',$atributosHTML,$opciones);
		
				
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo EMAIL
		 * para el campo correspondiente del modelo
		 *
		 * @param CActiveRecord $modelo  Modelo
		 * @param string $atributo Campo del Modelo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function modeloEmail(CActiveRecord $modelo, string $atributo, array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("email", $modelo,$atributo,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo File
		 * para un campo del Modelo dado
		 *
		 * @param CActiveRecord $modelo  Modelo
		 * @param string $atributo Campo del Modelo
		 * @param array $atributosHTML Atributo HTML
		 * @return string
		 */
		public static function modeloFile(CActiveRecord $modelo, string $atributo,  array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("file", $modelo,$atributo,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde con un input de tipo HIDDEN
		 * para el campo del modelo indicado
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del modelo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function modeloHidden(CActiveRecord $modelo, string $atributo, array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("hidden", $modelo,$atributo,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un Label para el campo
		 * del modelo indicado
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del modelo
		 * @param array $atributosHTML Atributos HTML. Si se indican label y for sustituyen
		 * a los valores obtenidos directamente del modelo para el campo correspondiente
		 * @return string
		 */
		public static function modeloLabel(CActiveRecord $modelo, string $atributo, array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			
			$nombreInput=$atributosHTML["name"];
			$for=$atributosHTML["id"];
			$etiqueta=$modelo->getDescripcion($atributo);
			
			unset($atributosHTML["name"]);
			unset($atributosHTML["id"]);	
			
			if (isset($atributosHTML["label"]))
			{
				$etiqueta=$atributosHTML["label"];
				unset($atributosHTML["label"]);
			}
			     
			if (isset($atributosHTML["for"]))
			{
				$for=$atributosHTML["for"];
				unset($atributosHTML["for"]);
			}
			
			
			return self::campoLabel($etiqueta, $for,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo NUMBER
		 * para el campo del modelo indicado
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del Modelo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function modeloNumber(CActiveRecord $modelo, string $atributo, array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("number", $modelo,$atributo,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo
		 * PASSWORD para el campo del modelo indicado
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del Modelo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function modeloPassword(CActiveRecord $modelo, string $atributo, array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("password", $modelo,$atributo,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde con un input de tipo 
		 * RADIO para un campo de un Modelo.
		 * Entre los atributos HTML se tienen dos especiales: etiqueta y uncheckValor que
		 * indican respectivamente una etiqueta que acompañará al input y el valor
		 * que se enviará en el formulario si no se marca el RadioButton
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del Modelo
		 * @param array $atributosHTML Atributos HTML. Se tendrán dos atributos especiales:
		 * - etiqueta: indica el texto que acompañará al input
		 * - uncheckValor: valor que se enviará en el formulario en caso de que no se
		 * seleccione el RadioButton.
		 * @return string
		 */
		public static function modeloRadioButton(CActiveRecord $modelo, string $atributo, array $atributosHTML=[]):string 
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			$nombre=$atributosHTML["name"];
			
			if (!isset($atributosHTML["value"]))
			      $atributosHTML["value"]=1;
			
			if (!isset($atributosHTML["checked"]) && ($modelo->$atributo==$atributosHTML["value"]))
					$atributosHTML["checked"]="checked";
			
				
			$valor=isset($atributosHTML['value']) ? $atributosHTML['value'] : 1;
		
			if(array_key_exists('uncheckValor',$atributosHTML))
				{
					$uncheckValor=$atributosHTML['uncheckValor'];
					unset($atributosHTML['uncheckValor']);
				}
				else
					$uncheckValor="0";
		
					
			if(isset($atributosHTML['id']) && $atributosHTML['id']!==false)
					$atributosUncheck=array('id'=>"ocul_".$atributosHTML['id']);
				else
					$atributosUncheck=array('id'=>false);

			if(array_key_exists('etiqueta',$atributosHTML))
			{
				$etiqueta=$atributosHTML['etiqueta'];
				unset($atributosHTML['etiqueta']);
				$etiqueta=" ".self::campoLabel($etiqueta, $atributosHTML["id"]);
			}
			else
				$etiqueta="";


					
			$oculto=self::campoHidden($nombre,$uncheckValor,$atributosUncheck);
			
			return $oculto . self::modeloInput('radio',$modelo,$atributo,$atributosHTML)." ".$etiqueta;
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a una serie de inputs de 
		 * tipo RADIO que corresponden con el campo indicado del Modelo.
		 * Se le indica un array con los values/etiquetas que tendrá cada RADIO y los 
		 * atributos HTML, con una opción especial (uncheckValor) que indica el valor 
		 * que se enviará en el formulario si no se selecciona ningún Radio
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del Modelo
		 * @param array $datos Array que contiene los distintos Radio a dibujar. Cada 
		 * elemento del array es de la forma value => etiqueta, donde el indice es el
		 * value del Radio y etiqueta es el texto que acompaña al Input.
		 * @param string $separador Cadena con la secuencia de caracteres que se usará
		 * como separador entre una opción y la siguiente
		 * @param array $atributosHTML Atributos HTML. Se tiene el atributo especial
		 * unckeckValor que corresponde con el value que se enviará en el formulario
		 * en caso de no seleccionar ningún Radio
		 * @return string
		 */
		public static function modeloListaRadioButton(CActiveRecord $modelo, string $atributo,
					array $datos, string $separador="<br/>\n", array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			$nombre=$atributosHTML["name"];
			$seleccionado=$modelo->$atributo;
			
			if(array_key_exists('uncheckValor',$atributosHTML))
				{
					$uncheckValor=$atributosHTML['uncheckValor'];
					unset($atributosHTML['uncheckValor']);
				}
				else
					$uncheckValor="0";
		
					
			if(isset($atributosHTML['id']) && $atributosHTML['id']!==false)
					$atributosUncheck=array('id'=>"ocul_".$atributosHTML['id']);
				else
					$atributosUncheck=array('id'=>false);
					
			$oculto=self::campoHidden($nombre,$uncheckValor,$atributosUncheck);
			
			return $oculto . self::campoListaRadioButton($nombre, $seleccionado, $datos,$separador,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo RANGE 
		 * para el campo del modelo indicado
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del Modelo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function modeloRange(CActiveRecord $modelo, string $atributo, array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("range", $modelo,$atributo,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde con un input de tipo TEXT 
		 * para el campo del modelo indicado
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del Modelo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function modeloText(CActiveRecord $modelo, string $atributo, array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("text", $modelo,$atributo,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un Textarea para el 
		 * campo del modelo indicado
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del modelo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function modeloTextArea(CActiveRecord $modelo, string $atributo,array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
				
				
			return self::dibujaEtiqueta("textarea",$atributosHTML,$modelo->$atributo);
		
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo TIME
		 * para el campo del Modelo indicado
		 *
		 * @param CActiveRecord $modelo Modelo
		 * @param string $atributo Campo del Modelo
		 * @param array $atributosHTML Atributos HTML
		 * @return string
		 */
		public static function modeloTime(CActiveRecord $modelo, string $atributo, array $atributosHTML=[]):string
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("time", $modelo,$atributo,$atributosHTML);
		}
		
		/**
		 * Método que devuelve una cadena que corresponde a un input de tipo URL para
		 * el campo del modelo indicado
		 *
		 * @param CActiveRecord $modelo
		 * @param string $atributo
		 * @param array $atributosHTML
		 * @return void
		 */
		public static function modeloUrl(CActiveRecord $modelo, string $atributo, array $atributosHTML=[])
		{
			self::dameModeloNombreId($modelo, $atributo, $atributosHTML);
			return self::modeloInput("url", $modelo,$atributo,$atributosHTML);
		}
		
}
