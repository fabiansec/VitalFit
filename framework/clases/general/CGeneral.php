<?php

	/**
	 * GGeneral es una clase que aporta diversos métodos estáticos
	 */
	class CGeneral{
		
		/**
		 * Método que convierte una cadena de fecha en el formato "aa-mm-dd"
		 * usado por Mysql al formato de fecha español (dd/mm/aa) devolviendo la
		 * cadena obtenida
		 *
		 * @param string $fecha Fecha original en formato "aa-mm-dd"
		 * @return string Devuelve una cadena con la fecha en formato dd/mm/aa
		 */
		public static function fechaMysqlANormal(string $fecha):string 
		{
			$fechaAux=explode("/",$fecha);
			if (count($fechaAux)==3)
			    return $fecha;
			
			$fecha=explode("-", $fecha);
			$fecha=date('d/m/Y',mktime(0,0,0,$fecha[1],$fecha[2],$fecha[0]));
			
			return $fecha;
		}

		/**
		 * Método que convierte una cadena de fecha en el formato dd/mm/aa al
		 * formato aa-mm-dd, devolviendo la cadena.
		 *
		 * @param string $fecha Fecha en formato dd/mm/aa
		 * @return string Cadena con la fecha en formato aa-mm-dd
		 */
		public static function fechaNormalAMysql(string $fecha):string
		{
			$fechaAux=explode("-",$fecha);
			if (count($fechaAux)==3)
			    return $fecha;
			
			$fecha=explode("/", $fecha);
			$fecha=date('Y-m-d',mktime(0,0,0,$fecha[1],$fecha[0],$fecha[2]));
			
			return $fecha;
			
		}

		
		/**
		 * Método que escapa en la cadena de entrada el carácter '.
		 * Se usa para prevenir el ataque por inyección de código en 
		 * SQL
		 * 
		 * @param string $cadena Cadena a escapar
		 * @return string
		 * 
		 */
		public static function addSlashes(string $cadena):string 
		{
			return str_replace("'", "''", $cadena);
		}
		
		
		/**
		 * Elimina el escape para una cadena dada
		 * 
		 * @param string $cadena 
		 * @return string
		 * 
		 */
		public static function stripSlashes($cadena)
		{
			return str_replace("''", "'", $cadena);
		}
		
		
		
	}
