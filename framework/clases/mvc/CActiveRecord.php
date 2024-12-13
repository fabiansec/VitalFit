<?php
	/**
	 * Clase base para los modelos. Abstrae el comportamiento de 
	 * un archivo/tabla/objeto real.
	 * 
	 */
	class CActiveRecord implements Iterator
	{
		private  $_nombre="";
		protected  $_esNuevo=true;
		
		//soporte para base de datos
		private  $_tabla="";
		private  $_id=""; 
		
		
		/**
		 * lista de atributos del objeto (campo)
		 */
		protected $_atributos=array(); 
		/**
		 * lista de las restricciones que debe cumplir cada 
		 * atributo. Por ejemplo que sea cadena, entero, etc
		 */
		protected $_restricciones=array();
		/**
		 * descripcion de cada atributo del objeto
		 */
		protected $_descripciones=array();
		/**
		 * Mensajes de error por campo tras una validacion
		 */
		protected $_errores=array();
		
		//indice usado al iterar 
		private $_indiceIter=0;
		
		/**
		 * Constructor que se encarga de inicializar el comportamiento básico 
		 * del modelo creado.
		 */
		public function __construct()
		{
			$this->_nombre=$this->fijarNombre();
			$this->_tabla=$this->fijarTabla();
			$this->_id=$this->fijarId();
			$this->inicializarAtributos();
			$this->inicializarDescripciones();
			$this->inicializarRestricciones();
			
			$this->_esNuevo=true;
			
			$this->afterCreate();
		}

		/**
		 * Método protegido que debe ser redefinido en los modelos. Este 
		 * método define el nombre del modelo. Es usado por ejemplo en los
		 * formularios para definir los names de los elementos del formulario
		 *
		 * @return string
		 */
		protected function fijarNombre():string
		{
			return "";
		}
		
		/**
		 * Método privado encargado de rellenar los campos dinámicos que tendrá el
		 * modelo a partir del método fijar_atributos.
		 *
		 * @return boolean
		 */
		private function inicializarAtributos():bool
		{
			
			//lo primero es obtener los campos para trabajar e inicializar
			//las variables del objeto
			foreach ($this->fijarAtributos() as $nombreCampo) 
			{
				$nombreCampo=$this->ajustarNombreCampo($nombreCampo);
				//añado el campo
				$this->_atributos[$nombreCampo]='';
				
				//añado una descripcion por defecto
				$this->_descripciones[$nombreCampo]=$nombreCampo;		
			}
			
			return true;
		}
		
		/**
		 * Método privado encargado de rellenar las descripciones de los campos
		 * definidos en el modelo
		 *
		 * @return boolean
		 */
		private function inicializarDescripciones():bool
		{
			//recogemos las descripciones de los campos que se hayan puesto
			foreach ($this->fijarDescripciones() as $campo => $valor) {
					
				$campo=$this->ajustarNombreCampo($campo);
				
				//compruebo si existe el campo
				if (isset($this->_atributos[$campo]))
				{
					$this->_descripciones[$campo]=$valor;
				}
			}
			
			return true;
		}
		
		/**
		 * Método privado que recoge las restricciones que se indican en 
		 * el modelo y las verifica para que tenga todos los campos correctos
		 *
		 * @return boolean
		 */
		private function inicializarRestricciones():bool
		{
			foreach($this->fijarRestricciones() as $resOriginal)
			{
				$restriccion=array();
				$valida=false;
				
				if (!isset($resOriginal["TIPO"]))
				    $resOriginal["TIPO"]="CADENA";
				
				
				switch (strtolower($resOriginal["TIPO"]))
				{
					case "requerido":
							$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="El campo no puede estar vacio";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
							$valida=true;							
							
							break;	
					case "cadena":
							$restriccion["TIPO"]="cadena";
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="La cadena no es valida";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
							
							if (!isset($resOriginal["TAMANIO"]))
							      $restriccion["TAMANIO"]=30;
								else
								  $restriccion["TAMANIO"]=intval($resOriginal["TAMANIO"]);

							if (!isset($resOriginal["DEFECTO"]))
							      $restriccion["DEFECTO"]="";
								else
								  $restriccion["DEFECTO"]=$resOriginal["DEFECTO"];
							

							$valida=true;							
							break;

					case "entero":
							$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
						   if (!isset($resOriginal["MENSAJE"]))
								 $restriccion["MENSAJE"]="Numero no valido";
							   else
								 $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
							   
						   if (!isset($resOriginal["MIN"]))
								 $restriccion["MIN"]=-100000;
							   else
								 $restriccion["MIN"]=intval($resOriginal["MIN"]);

						   if (!isset($resOriginal["MAX"]))
								 $restriccion["MAX"]=100000;
							   else
								 $restriccion["MAX"]=intval($resOriginal["MAX"]);

						   if (!isset($resOriginal["DEFECTO"]))
								 $restriccion["DEFECTO"]=0;
							   else
								 $restriccion["DEFECTO"]=intval($resOriginal["DEFECTO"]);
						   $valida=true;							
						   break;
					case "real":
							$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
						   if (!isset($resOriginal["MENSAJE"]))
								 $restriccion["MENSAJE"]="Numero no valido";
							   else
								 $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
							   
						   if (!isset($resOriginal["MIN"]))
								 $restriccion["MIN"]=-100000;
							   else
								 $restriccion["MIN"]=floatval($resOriginal["MIN"]);

						   if (!isset($resOriginal["MAX"]))
								 $restriccion["MAX"]=100000;
							   else
								 $restriccion["MAX"]=floatval($resOriginal["MAX"]);

						   if (!isset($resOriginal["DEFECTO"]))
								 $restriccion["DEFECTO"]=0;
							   else
								 $restriccion["DEFECTO"]=floatval($resOriginal["DEFECTO"]);
						   $valida=true;							
						   break;
				  
					case "fecha":
						 	$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="Fecha no valida";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
								  
							if (!isset($resOriginal["DEFECTO"]))
							      $restriccion["DEFECTO"]="01/01/2000";
								else
								  $restriccion["DEFECTO"]=$resOriginal["DEFECTO"];
							
							$valida=true;							
							break;
					case "hora":
						 	$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="Hora no valida";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
							
							if (!isset($resOriginal["DEFECTO"]))
							      $restriccion["DEFECTO"]="00:00:00";
								else
								  $restriccion["DEFECTO"]=$resOriginal["DEFECTO"];
							
							$valida=true;							
							break;
					case "email":
						 	$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="Direccion de correo no v&aacute;lida";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
								
							if (!isset($resOriginal["DEFECTO"]))
							      $restriccion["DEFECTO"]="aa@aaa.es";
								else
								  $restriccion["DEFECTO"]=$resOriginal["DEFECTO"];
							if (!isset($resOriginal["VACIO"]))
							      $restriccion["VACIO"]=false;
								else
								  $restriccion["VACIO"]=($resOriginal["VACIO"]==true);
							
							$valida=true;							
							break;

					case "rango":
						 	$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="El campo no es valido";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
							
							if (isset($resOriginal["RANGO"]) && is_array($resOriginal["RANGO"]))
								  $restriccion["RANGO"]=$resOriginal["RANGO"];
							    else
							      $restriccion["RANGO"]=array();
							
							if (!isset($resOriginal["DEFECTO"]))
							      {
									$restriccion["DEFECTO"]="";
									if (count($restriccion["RANGO"])>0)
									    {
										 $valores=array_values($restriccion["RANGO"]);	
									     $restriccion["DEFECTO"]=$valores[0];
										}
								  }
								else
								  {
									$restriccion["DEFECTO"]=$resOriginal["DEFECTO"];
									if (!in_array($restriccion["DEFECTO"],$restriccion["RANGO"]))
									    {
											$restriccion["DEFECTO"]="";
											if (count($restriccion["RANGO"])>0)
												{
												 $valores=array_values($restriccion["RANGO"]);	
												 $restriccion["DEFECTO"]=$valores[0];
												}
										}							
								  }
								  

							$valida=true;							
							break;

					case "funcion":
						 	$restriccion["TIPO"]=strtolower($resOriginal["TIPO"]);
							if (!isset($resOriginal["MENSAJE"]))
							      $restriccion["MENSAJE"]="El campo no es valido";
								else
								  $restriccion["MENSAJE"]=$resOriginal["MENSAJE"];
							
							if (isset($resOriginal["FUNCION"]) && is_string($resOriginal["FUNCION"]))
								  $restriccion["FUNCION"]=$resOriginal["FUNCION"];
							    else
							      $restriccion["FUNCION"]="";
							
							$valida=true;							
							break;
					
				}
				
				//busco lo campos a los que indicar la restriccion
				if (isset($resOriginal["ATRI"]) && $valida)
				{
					$atributos=explode(',', $resOriginal["ATRI"]);
					foreach($atributos as $atributo)
					{
						$atributo=$this->ajustarNombreCampo(trim($atributo));
						if (isset($this->_atributos[$atributo]))
						{
							if (!isset($this->_restricciones[$atributo]))
									$this->_restricciones[$atributo]=array();
							
							$this->_restricciones[$atributo][]=$restriccion;	    
						}
					}
				}
			}
			
			
			return true;
		}
		
		
		/**
		 * Método que se debe redefinir en los modelos y que devuelve
		 * un array con todos los campos que va a tener el modelo
		 *
		 * @return array
		 */
		protected function fijarAtributos():array
		{
			return array();
		}
		

		/**
		 * Método que se debe redefinir en los modelos y que devuelve
		 * un array con las descripciones de los distintos campos.
		 * Cada elemento del array debe ser de la forma "campo"=>"descripción de campo"
		 *
		 * @return array
		 */
		protected function fijarDescripciones():array
		{
			return array();
		}
		
		/**
		 * Método que devuelve el nombre asignado al modelo
		 *
		 * @return string
		 */
		public function getNombre():string
		{
			return $this->_nombre;
		}
		
		/**
		 * Método que devuelve la descripción asignada a un campo.
		 * Si el campo no existe devuelve null
		 *
		 * @param string $campo Nombre del campo
		 * @return string|null
		 */
		public function getDescripcion(string $campo):?string
		{
			$campo=$this->ajustarNombreCampo($campo);
				
			//compruebo si existe el campo
			if (isset($this->_atributos[$campo]))
				{
					return $this->_descripciones[$campo];
				}	
			  else
			  	return null;
		}
		
		/**
		 * Método que debe ser redefinido en los modelos que devuelve
		 * un array con todas las restricciones a aplicar en el modelo
		 *
		 * @return array
		 */
		protected function fijarRestricciones():array
		{
			return array();
		}
		
		/**
		 * Método que añade un mensaje de error al campo indicado.
		 * Devuelve true si asigna el error y false en caso de que 
		 * no exista el campo
		 *
		 * @param string $campo Campo al que añadir el error
		 * @param string $mensaje  Mensaje de error a añadir
		 * @return boolean
		 */
		public function setError(string $campo, string $mensaje):bool
		{
			$campo=$this->ajustarNombreCampo($campo);
			
			if (!isset($this->_atributos[$campo]))
				return false;
			
			if (!isset($this->_errores[$campo]))
				$this->_errores[$campo]=array();
			
			$this->_errores[$campo][]=$mensaje;
			return true;
		}
		
		/**
		 * Método que devuelve un array con todos los errores del
		 * campo indicado. Si no se encuentra el campo devuelve null
		 *
		 * @param string $campo Campo para el que devolver los errores
		 * @return array|null Array con todos los errores o null si no se 
		 * encuentra el campo
		 */
		public function errorAtributo(string $campo):?array
		{
			$campo=$this->ajustarNombreCampo($campo);
			
			if (isset($this->_errores[$campo]))
				return $this->_errores[$campo];
			  else
			  	return null;
		}
		
		/**
		 * Método que devuelve un array con todos los errores que 
		 * tengan los campos del modelo
		 *
		 * @return array Array de errores
		 */
		public function getErrores():array
		{
			$listaErrores=array();
			
			foreach ($this->_errores as $campo => $errores) 
			{
				foreach ($errores as $error) 
				{
					$listaErrores[]=$this->getDescripcion($campo).": ".$error;					
				}
			}
			return $listaErrores;
		}
		
		/**
		 * Método que se encarga de verificar todas las restricción definidas
		 * en el modelo con los datos que actualmente tenga el modelo para los
		 * campos
		 * Devuelve true si no se ha producido ningún error en la validación
		 * @return boolean Devuelve true si no se ha producido ningún error
		 * al comprobar las restricciones
		 */
		public function validar():bool
		{
			$this->_errores=array();
			
			foreach ($this->_restricciones as $campo=>$restric)
			{
				foreach ($restric as $res) 
				{
					switch (strtolower($res["TIPO"])) 
					{
						case 'requerido':
								if (!(bool)$this->$campo)
								{
									$this->setError($campo, $res["MENSAJE"]);
								}
								break;
								
						case 'cadena':
							    if (!is_string($this->$campo))
								{
									$this->setError($campo, $res["MENSAJE"]);
									$this->$campo=$res["DEFECTO"];
								}
								if (mb_strlen($this->$campo)>$res["TAMANIO"])
								{
									$this->setError($campo, $res["MENSAJE"]);
									$this->$campo=$res["DEFECTO"];
								}
								
								break;
						
						case "funcion":
							   if ($res["FUNCION"]!='')
							     {
							         $aux= $res["FUNCION"];
							         $this->$aux();
							     }
							    break;  
						
						case "entero":
								//uso la funcion de validar entero
								$otra=intval($this->$campo);
								if ($otra==0 && $this->$campo!=$otra)
								{
									$this->setError($campo, $res["MENSAJE"]);
									$this->$campo=$res["DEFECTO"];
									$otra=$this->$campo;
								}
								
								if (!CValidaciones::validaEntero($otra, 
														$res["DEFECTO"], $res["MIN"], $res["MAX"]))	
									{
										$this->setError($campo, $res["MENSAJE"]);
									}
								$this->$campo=$otra;
								
								break;

						case "real":
								//uso la funcion de validar real
								$otra=floatval($this->$campo);
								if (!is_numeric($this->$campo))
								{
									$this->setError($campo, $res["MENSAJE"]);
									$this->$campo=$res["DEFECTO"];
									$otra=$this->$campo;
								}
								if (!CValidaciones::validaReal($otra, 
														$res["DEFECTO"], $res["MIN"], $res["MAX"]))	
									{
										$this->setError($campo, $res["MENSAJE"]);
									}
								$this->$campo=$otra;
								
								break;
										  
						case "email":
								//uso la funcion de validar email
								$otra=$this->$campo;
								if (!CValidaciones::validaEMail($otra, 
														$res["DEFECTO"]))	
									{
										$this->setError($campo, $res["MENSAJE"]);
									}
								$this->$campo=$otra;
								
								break;
						
						case "fecha":
								//uso la funcion de validar fecha
								$otra=$this->$campo;
								if (!CValidaciones::validaFecha($otra))	
									{
										$this->setError($campo, $res["MENSAJE"]);
										$this->$campo=$res["DEFECTO"];
									}
								$this->$campo=$otra;
								
								break;
						
						case "hora":
								//uso la funcion de validar hora
								$otra=$this->$campo;
								if (!CValidaciones::validaHora($otra))	
									{
										$this->setError($campo, $res["MENSAJE"]);
										$this->$campo=$res["DEFECTO"];
									}
								$this->$campo=$otra;
								
								break;
								
						case "rango":
								//uso la funcion de validar lista
								$otra=$this->$campo;
								if (!CValidaciones::validaLista($otra,$res["RANGO"]))	
									{
										$this->setError($campo, $res["MENSAJE"]);
										$this->$campo=$res["DEFECTO"];
									}
								$this->$campo=$otra;
								
								break;
					
					}	
				}
			}
			
			//se devuelve true si no hay ningun elemento en el array de
			//errores, es decir, no hay errores
			return ($this->_errores===array());
		}

		/**
		 * Método que se debe redefinir en los modelos y que será llamado
		 * de forma automática una vez que se está creando un nuevo objeto
		 * del modelo.
		 *
		 * @return void
		 */
		protected function afterCreate():void
		{
			
		}
		
		/**
		 * Método que debe redefinirse en el modelo y que se llama automáticamente
		 * tras buscar en la base de datos (métodos buscarPor y buscarPorId)
		 * y recoger una fila 
		 *
		 * @return void
		 */
		protected function afterBuscar():void
		{
			
		}
		
		/**
		 * Método interno que devuelve el nombre del campo en minúscula
		 *
		 * @param string $nombre Nombre del campo
		 * @return string
		 */
		protected function ajustarNombreCampo(string $nombre):string 
		{
			return strtolower($nombre);
		}
		
		/**
		 * Método que se encarga de asignar a los campos del modelo los 
		 * valores correspondientes indicados en el array de llamada.
		 * El array debe definirse con elementos de la forma "campo"=>valor
		 *
		 * @param array $arrayValores Array con los valores a asignar a los
		 * campos del modelo. Cada elemento debe ser de la forma "campo"=>valor
		 * @return void
		 */
		public function setValores(array $arrayValores):void
		{
			foreach ($arrayValores as $campo => $valor) 
			{
				if (isset($this->$campo))
					$this->$campo=$valor;	
			}
		}
		
		
		//funciones para sobrecarga de atributos
		public function __get(string $nombre):mixed
		{
			$nombre=$this->ajustarNombreCampo($nombre);
			
			if (isset($this->_atributos[$nombre]))
			  	return $this->_atributos[$nombre];
			  else
				return null;
		}
		
		public function __set(string $nombre, mixed $valor):void
		{
			$nombre=$this->ajustarNombreCampo($nombre);
			
			//no permito agregar campos al objeto si no se 
			//han definido mediante la funcion fijarAtributos
			if (isset($this->_atributos[$nombre]))
				$this->_atributos[$nombre]=$valor;
			
		}
		
		public function __isset($nombre):bool
		{
			$nombre=$this->ajustarNombreCampo($nombre);
			
			return (isset($this->_atributos[$nombre]));
			
		}
		
		public function __unset($nombre):void
		{
			$nombre=$this->ajustarNombreCampo($nombre);
			//no permito unset sobre los campos de un objeto
			//if (isset($this->_atributos[$nombre]))
			//      unset($this->_atributos[$nombre]);
			
		}
		
		//funciones para iterar sobre los campos definidos
		public function key():mixed
		{
			$claves=array_keys($this->_atributos);
			return $claves[$this->_indiceIter];
		}
		
		public function current():mixed
		{
			$valores=array_values($this->_atributos);
			return $valores[$this->_indiceIter];
			
		}
		
		public function next():void
		{
			$this->_indiceIter++;
		}
		
		public function rewind():void
		{
			$this->_indiceIter=0;
		}
		
		public function valid():bool
		{
			if (($this->_indiceIter>=0) &&
			    ($this->_indiceIter<count($this->_atributos))) 
				return true;
			  else
			  	return false;
		}
		
		
		
		//soporte de BD
		/**
		 * Método que debe redefinirse en el modelo que devuelve una cadena
		 * que corresponde al nombre de la tabla/vista a la que enlazamos el modelo
		 *
		 * @return string Nombre de tabla/vista enlazada al modelo
		 */
		protected function fijarTabla():string
		{
			return "";
		}
		
		/**
		 * Método que debe redefinirse en el modelo que devuelve una cadena
		 * que corresponde con el campo Primary Key de la tabla o vista enlazadas
		 *
		 * @return string Nombre del campo Primary Key de la tabla/vista enlazada
		 */
		protected function fijarId():string
		{
			return "";
		}
		
		/**
		 * Método que debe redefinirse en el modelo que devuelve una cadena con la
		 * sentencia Insert a ejecutar cuando se guarda un nuevo registro
		 *
		 * @return string Sentencia SQL insert para almacenar el registro
		 */
		protected function fijarSentenciaInsert():string
		{
			return "";
		}
		
		/**
		 * Método que debe ser redefinido en el modelo y que devuelve la sentencia 
		 * SQL Update que se ejecutará cuando se guarde el registro
		 *
		 * @return string Sentencia SQL Update para almacenar los cambios en el
		 * registro
		 */
		protected function fijarSentenciaUpdate():string
		{
			return "";
		}
		
		
		
		/**
		 * Método que busca en la base de datos enlazada al modelo por 
		 * la Primary Key. 
		 * Se le puede indicar otras opciones para componer la sentencia la
		 * sentencia de búsqueda.
		 * Si encuentra el registro lo carga automáticamente en el modelo.
		 * Devuelve true si encuentra el registro
		 *
		 * @param mixed $valor Valor a buscar para la Primary Key
		 * @param array $opciones. Opciones para componer la sentencia. Se usa 
		 * tan solo la opción "where" si esta definida
		 * @return boolean
		 */
		public function buscarPorId(mixed $valor, array $opciones=[]):bool
		{
			if (!isset($opciones["where"]))
			     $opciones["where"]="";
    	    $opciones["group"]="";
    	    $opciones["having"]="";
    	    $opciones["order"]="";
			$opciones["limit"]="1";
			
			if ($opciones["where"]!="")
			    $opciones["where"].=" and ";
			
			$opciones["where"].=" t.{$this->_id}=$valor";
			
			$filas=$this->ejecutarConsultaSelect($opciones);
			
			if (is_array($filas) && count($filas)!=0)
			  {
			  	$this->_esNuevo=false;
				$this->setValores($filas[0]);
				$this->afterBuscar();
			    return true;
			  }
			
			return false;  
			
			
		}
		
		/**
		 * Método que busca un registro en la tabla/vista enlazadas al modelo.
		 * En $opciones se debe indicar la cláusula "where" para la búsqueda.
		 * Si se encuentran registros, se almacenan los datos de la primera fila
		 * encontrada en el modelo
		 *
		 * @param array $opciones Cláusula "where" para la búsqueda del registro
		 * @return boolean
		 */
		public function buscarPor(array $opciones=array()):bool
		{
			if (!isset($opciones["where"]))
			     $opciones["where"]="";
			$opciones["group"]="";
			$opciones["having"]="";
			$opciones["limit"]="1";
			
			$filas=$this->ejecutarConsultaSelect($opciones);
			
			if (is_array($filas) && count($filas)!=0)
			  {
			  	$this->_esNuevo=false;
				$this->setValores($filas[0]);
				$this->afterBuscar();
			    return true;
			  }
			
			return false;  
		}
		
		/**
		 * Método que ejecuta una sentencia select sobre la vista/consulta 
		 * enlazada al modelo y que devuelve las filas que encuentra.
		 * Las opciones es un array con cláusulas posible para la sentencia:
		 * "select", "where", "from", "group", "having", "order" y "limit"
		 *
		 * @param array $opciones Cadenas para claúsulas "select", "where", "from", 
		 * "group", "having", "order" y "limit" para la sentencia a ejecutar. Hay que 
		 * tener en cuenta que automáticamente se define "from nombre_tabla t"
		 * @return array Filas encontradas
		 */
		public function buscarTodos(array $opciones=array()):bool|array
		{
			$filas=$this->ejecutarConsultaSelect($opciones);
			return $filas;
		}
		

		/**
		 * Método que devuelve el número de filas que devuelve la sentencia
		 * a ejecutar sobre la tabla/vista. 
		 * En opciones se indican cadena para las cláusulas "where", "from", 
		 * "group", "having", "order" y "limit"
		 *
		 * @param array $opciones Cadenas para las cláusulas "where", "from", "group", 
		 * "having", "order" y "limit"
		 * @return false|integer Devuelve el número de filas o false si no se
		 * ejecuta correctamente la sentencia
		 */
		public function buscarTodosNRegistros(array $opciones=array()):false|int
		{
			$opciones["select"]="coalesce(count(*),0) as numero";
			$filas=$this->ejecutarConsultaSelect($opciones);
			return isset($filas[0]["numero"])?$filas[0]["numero"]:false;
		}
		
		/**
		 * Método que guarda los datos del modelo en la tabla.
		 * Puede insertar un nuevo registro o actualizar el actual.
		 * Se usan las sentencias definidas en fijarSentenciaInsert o 
		 * fijarSentenciaUpdate.
		 * Si se guardan correctamente los datos son cargados automáticamente
		 * en el modelo
		 *
		 * @return boolean True si se guarda los datos del modelo.
		 */
		public function guardar():bool
		{
			if ($this->_tabla=="")
			     return false;
				
			if (!$this->_esNuevo)
			   {  //se guarda un registro modificado
			   	  $sentencia=$this->fijarSentenciaUpdate();
				  
				  if ($sentencia=="")
				     return false;
			   	
				  if (!$this->ejecutarSentencia($sentencia))
				        return false;
				  $campo=$this->_id;
				  $this->buscarPorId($this->$campo);	
				  return true;	
			   }
			 else
			   {
			   	  $sentencia=$this->fijarSentenciaInsert();
				  
				  if ($sentencia=="")
				     return false;
			   	  
				  $valor=$this->ejecutarSentencia($sentencia,true);
				  if (!$valor)
				      return false;
				      
				  $this->buscarPorId($valor);	
				  return true;				  	
				
			   }	  
		}
		
		/**
		 * Método que permite ejecutar una sentencia sobre la base de datos enlazada
		 * al modelo.
		 * Devuelve las filas (si es una sentencia select) o un valor booleano si
		 * se ejecuta correctamente la sentencia. Si la sentencia es insert y ponemos
		 * a true el parámetro $esInsert devuelve el id generado.
		 *
		 * @param string $sentencia Sentencia a ejecutar
		 * @param boolean $esInsert True si la sentencia es de tipo Insert 
		 * @return boolean|array|int Devuelve si se ejecuta o no correctamente 
		 * (sentencias de tipo Insert/update/delete) o las filas (sentencia select).
		 * Si hemos indicado $esInsert a true, devuelve el id generado para la 
		 * sentencia Insert
		 */
		public function ejecutarSentencia(string $sentencia, bool $esInsert=false):bool|array|int
		{
			if ($this->_tabla=="")
			     return false;
			
			if (Sistema::app()->BD())
			   {
			   	  $consulta=Sistema::app()->BD()->crearConsulta($sentencia);
				  if ($consulta->error()!=0)
				        return false;
				      else
					  	{
					  	  if ($esInsert)
						      return $consulta->idGenerado();
						  	
					  	  $filas=$consulta->filas();	
					  	  
					  	  if (is_array($filas))
					  	  		return $filas;
							  else
							    return true;
						}
			   }
			  else 
			   {
				  return false;
			   }
		
		}
		
		/**
		 * Método privado para ejecutar sentencias select sobre la tabla/vista
		 *
		 * @param array $opciones
		 * @return false|array
		 */
		private function ejecutarConsultaSelect(array $opciones=array()):false|array
		{
			if ($this->_tabla=="")
			     return false;
			
			$sentencia=$this->componerSentencia($opciones);
			
			return $this->ejecutarSentencia($sentencia);	  	 
		}
		
		/**
		 * Método privado que devuelve la sentencia correspondiente a las
		 * opciones indicadas para la tabla/vista enlazada al modelo
		 *
		 * @param array $opciones
		 * @return string
		 */
		private function componerSentencia(array $opciones=array()):string 
		{
			$sentSelect="*";
			$sentFrom=" {$this->_tabla} t";
			$sentWhere="";
			$sentGroup="";
			$sentHaving="";
			$sentOrder="";
			$sentLimit="";
			
			if (isset($opciones["select"]) &&
			          trim($opciones["select"])!="")
			   $sentSelect=$opciones["select"];
			
			if (isset($opciones["from"])&&
			          trim($opciones["from"])!="")
			    $sentFrom.=" ".$opciones["from"];
			
			if (isset($opciones["where"])&&
			          trim($opciones["where"])!="")
			    $sentWhere.=" ".$opciones["where"];
		   
		    if (isset($opciones["group"])&&
		        trim($opciones["group"])!="")
		        $sentGroup.=" ".$opciones["group"];
			
	        if (isset($opciones["having"])&&
	            trim($opciones["having"])!="")
	            $sentHaving.=" ".$opciones["having"];
		            
			if (isset($opciones["order"])&&
			          trim($opciones["order"])!="")
			    $sentOrder.=" ".$opciones["order"];
			
			if (isset($opciones["limit"]))
			    $sentLimit.=" ".$opciones["limit"];
			
			
			$sentencia="select $sentSelect".
			           "    from $sentFrom";
			if ($sentWhere!="")
				$sentencia.="     where $sentWhere";
			
			if ($sentGroup!="")
			    $sentencia.="     group by $sentGroup";
			
			if ($sentGroup!="" && $sentHaving!="")
			    $sentencia.="     having $sentHaving";
				    
			if ($sentOrder!="")
			    $sentencia.="     order by $sentOrder";
			    
			if ($sentLimit!="")
			    $sentencia.="  limit $sentLimit";
				
			
			return $sentencia;
			
		}
	}
