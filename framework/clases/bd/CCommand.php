<?php

	/**
	 * CCommand es la clase que permite gestionar una consulta/sentencia a
	 * ejecutar sobre una base de datos (CBaseDatos)
	 */
	class CCommand
	{
		private bool|mysqli_result $_resultado;
		private mysqli $_conexion;
		
		/**
		 * Constructor de la clase CCommand.
		 * Recibe la conexión a la Base de Datos y la sentencia a ejecutar.
		 * Se encarga de ejecutar la sentencia en la Base de Datos
		 *
		 * @param mysqli $conexion Conexión a la Base de Datos
		 * @param string $sentencia Cadena que corresponde a la sentencia SQL
		 * a ejecutar
		 */
		public function __construct(mysqli $conexion, string $sentencia)
		{
			$this->_conexion=$conexion;
			$this->_resultado=$this->_conexion->query($sentencia);
		}
		
		/**
		 * Método que libera el objeto
		 */
		public function __destruct()
		{
			$this->free();	
		}
		
		/**
		 * Método que devuelve si se ha producido error al ejecutar la sentencia
		 *
		 * @return bool|integer
		 */
		public function error():bool|int
		{
			if ($this->_resultado===false)
			       return 1;
			if ($this->_conexion->errno!=0)
			     return $this->_conexion->errno;
			return 0;
		}
		
		/**
		 * Método que devuelve el mensaje de error correspondiente 
		 * al error en la ejecución de la sentencia. Si no se ha producido
		 * error devuelve la cadena vacía
		 *
		 * @return string
		 */
		public function mensajeError():string
		{
			return ($this->_conexion->error);
		}
		
		/**
		 * Método que devuelve el número de filas del conjunto resultado
		 * tras la ejecución de la sentencia. 
		 * Si se ha producido error o no se devuelven filas, devolverá false
		 *
		 * @return false|integer
		 */
		public function numFilas():false|int
		{
		   if ($this->error()!=0)
		        return false;
			if (!is_object($this->_resultado))
			    return false;
				
			return $this->_resultado->num_rows;
		}
		
		/**
		 * Método que devuelve todas las filas del conjunto resultado
		 * para la sentencia ejecutada. Se devolverá un array asociativo.
		 * Se devolverá false si se tiene error o la sentencia no devuelve filas
		 *
		 * @return false|array
		 */
		public function filas():false|array
		{
			if ($this->error()!=0)
			   return false;
			
			if (is_object($this->_resultado))
					return ($this->_resultado->fetch_all(MYSQLI_ASSOC));
				else
					return false; 
		}
		
		/**
		 * Método que devuelve la fila actual del conjunto resultado
		 * para la sentencia ejecutada. Se devolverá un array asociativo.
		 * Se devolverá false si se tiene error o la sentencia no devuelve filas
		 *
		 * @return false|array
		 */
		public function fila():false|array|null
		{
			if ($this->error()!=0)
			   return false;
			
			if (is_object($this->_resultado))
					return ($this->_resultado->fetch_assoc());
				else
					return false;
		}
		
		/**
		 * Método que devuelve el último id para la clave autonumérica
		 * en sentencias Insert que se ejecuten 
		 *
		 * @return integer
		 */
		public function idGenerado():int
		{
			return ($this->_conexion->insert_id);
		}
		
		/**
		 * Método que libera la memoria asignada al objeto
		 *
		 * @return void
		 */
		public function free()
		{
			if (is_resource($this->_resultado))
				$this->_resultado->free();
		}
	}
