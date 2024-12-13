<?php

	mysqli_report(MYSQLI_REPORT_OFF);

	/**
	 * La clase CBaseDatos permite controlar una conexión a una Base de Datos
	 * Aporta métodos gestionar errores, realizar consultas y controlar transacciones 
	 * 
	 */
	class CBaseDatos{
		private $_conexion;
		
		/**
		 * Constructor del objeto CBaseDatos. Establece una conexión a una Base de 
		 * Datos MySQL con los parámetros indicados.  
		 *
		 * @param string $servidor Dirección del servidor
		 * @param string $usuario Nombre de usuario para el acceso al servidor
		 * @param string $contrasenia Contraseña del usuario
		 * @param string $nombreBD Base de Datos que se abrirá al establecer la conexión
		 */
		public function __construct(string $servidor, string $usuario,
							string $contrasenia, string $nombreBD)
		{
			$this->_conexion=new mysqli($servidor,$usuario,
							$contrasenia,$nombreBD);
			
			$this->_conexion->query("SET NAMES 'utf8'");
			
		}
		
		/**
		 * Método que devuelve el error que se produce en el 
		 * momento de la conexión a la Base de Datos MySQL
		 * Devolverá 0 si no se ha producido error
		 * @return integer
		 */
		public function error():int
		{
			return $this->_conexion->connect_errno;
		}
		
		/**
		 * Método que devuelve la cadena que describe el error que se
		 * ha producido en el momento de establecer la conexión
		 *
		 * @return string
		 */
		public function mensajeError():string
		{
			return $this->_conexion->connect_error;
		}
		
		/**
		 * Método que devuelve el enlace MYSQLI a la Base de Datos
		 *
		 * @return mysqli|null
		 */
		public function getEnlace():?mysqli
		{
			return $this->_conexion;
		}
		
		/**
		 * Método que permite ejecutar una sentencia sobre la Base de 
		 * Datos 
		 * Devuelve un objeto CCommand con la ejecución de la misma
		 * 
		 * @param string $sentencia Cadena que corresponde a la sentencia SQL 
		 * a ejecutar
		 * @return CCommand
		 */
		public function crearConsulta(string $sentencia):CCommand
		{
			return new CCommand($this->_conexion,$sentencia);
		}
		
		/**
		 * Método que cierra la conexión a la Base de Datos
		 *
		 * @return void
		 */
		public function cerrarConexion():void
		{
			$this->_conexion->close();
		}
		
		/**
		 * Método que permite iniciar una transacción sobre la Base de Datos
		 *
		 * @return void
		 */
		public function beginTran():void
		{
			$this->_conexion->autocommit(false);
		}
		
		/**
		 * Método que confirma la transacció (COMMIT)
		 *
		 * @return boolean
		 */
		public function commit():bool
		{
			return $this->_conexion->commit();
		}
		
		/**
		 * Método que deshace la transacción (ROLLBACK)
		 *
		 * @return boolean
		 */
		public function rollback():bool
		{
			return $this->_conexion->rollback();
		}
	}
