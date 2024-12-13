<?php

	class CValidaciones
	{
		
			
		/**
		 * Permite validar un DNI obteniendo sus partes
		 * 
		 * 
		 * @param string $dni  Dni a analizar
		 * @param array $partes  Partes analizadas
		 * @return boolean Devuelve true si es correcto y false 
		 * en cualquier otro caso
		 */

		/**
		 * Método que verifica si una cadena dada corresponde a un dni válido.
		 * Los formatos correctos son A9999999A o 99999999A
		 * Se devuelve en partes las partes del DNI
		 * 
		 * @param string $dni
		 * @param array $partes
		 * @return boolean
		 */ 
		public static function validaDNI(string $dni, array &$partes):bool
		{
			$expre="/^(?|([a-z]\d{7})|(\d{1,8}))([a-z])$/i";
			
			$dni=trim($dni);
			$dni=strtoupper($dni);
			
			
			if (preg_match($expre, $dni,$partes))
			     {
			     	if (is_numeric($partes[1]))
						{
							$partes[1]=substr("00000000".$partes[1], -8);
							$partes[0]=$partes[1].$partes[2];
						}
			     	return true;
			     }
					
			return false;
		}
		
		/**
		 * Permite comprobar si un numero esta dentro de un rango dado. Si no esta 
		 * se inicializa el numero con el valor por defecto
		 *
		 * @param integer $numero Número a validar
		 * @param integer $valorDefecto Valor que se asignará en caso de error
		 * @param integer $min Valor mínimo del rango a validar
		 * @param integer $max Valor máximo del rango a validar
		 * @return boolean Devuelve true/false si es correcto o no
		 */ 
		public static function validaEntero(int &$numero, int $valorDefecto, int $min, int $max):bool
		{
			$numero=intval($numero);
			
			if (($numero>=$min) && ($numero<=$max))
			     return true;
				else
					{
						$numero=$valorDefecto;
						return false;
					}
			
		}
		
		
		/**
		 * Método que verofoca si un número dado está dentro de un rango. En caso de que
		 * no esté asigna al número el valor por defecto
		 *
		 * @param float $numero Número a verificar
		 * @param float $valorDefecto Valor a asignar en caso de que no este en el rango
		 * @param float $min Mínimo del rango
		 * @param float $max Máximo del rango
		 * @return boolean
		 */
		public static function validaReal(float &$numero, float $valorDefecto, float $min, float $max):bool
		{
			$numero=floatval($numero);
			
			if (($numero>=$min) && ($numero<=$max))
			     return true;
				else
					{
						$numero=$valorDefecto;
						return false;
					}
			
		}
		
		/**
		 * Método que verifica si el codpostal es válido. En caso de que no sea válido
		 * le asigna 00000
		 *
		 * @param string $codpostal
		 * @return boolean
		 */
		public static function validaCodPostal(string &$codpostal):bool
		{
			$codpostal=trim($codpostal);
			if (!is_numeric($codpostal))
			{
				$codpostal='00000';
				return false;
			}
			$expresion="/^(\d{1,2})(\d{3})$/";
			$partes=array();
			if (!preg_match($expresion, $codpostal,$partes))
			{
				$codpostal='00000';
				return false;
			}
			
			if (($partes[1]<1) || ($partes[1]>52))
			{
				$codpostal='00000';
				return false;
			}
			$codpostal=substr("00000".$partes[0], -5);
			return true;
		}
		
		/**
		 * Método que valida si el email es correcto. Si no es, le asigna el 
		 * email por defecto
		 *
		 * @param string $email Email a verificar
		 * @param string $valorDefecto Email a asignar en caso de que no sea correcto
		 * @return boolean
		 */
		public static function validaEMail(string &$email, string $valorDefecto="aa@aaa.es"):bool
		{
			$email=trim ($email);
			$expresion="/^[[:alnum:]\-_]+(\.[[:alnum:]\-_]+)*@[[:alnum:]\-_]+(\.[[:alnum:]\-_]+)*\.[a-z]{2,4}$/i";
			$partes=array();
			if (!preg_match($expresion, $email,$partes))
			{
				$email=$valorDefecto;
				return false;
			}
			
			$email=$partes[0];
			return true;
		}
		

		/**
		 * Metodo que comprueba si un valor se encuentra dentro de una lista.
		 * Devuelve true si se encuentra y false en caso contrario
		 *
		 * @param mixed $elemento
		 * @param array $lista
		 * @return void
		 */
		public static function validaLista(mixed &$elemento, array $lista)
		{
			$encontrado=false;
			$elemento=trim($elemento);
			
			foreach ($lista as $valor) 
			{
				if ($elemento==$valor)
				{
					$encontrado=true;
				}	
				
			}
			return $encontrado;
		}
		
		/**
		 * Este método comprueba que la fecha tiene el formato dd/mm/aaaa y además
		 * es correcta.
		 * Completa la parte dia/mes con 0 para que tenga dos cifras
		 * Devuelve true si es correcta
		 *
		 * @param string $fecha Fecha a comprobar que esta en el formato dd/mm/aaaa y
		 * es válida. La sanea para que el dia/mes esté con dos cifras.
		 * @return boolean
		 */
		public static function validaFecha(string &$fecha):bool
		{
			$fecha=trim($fecha);
			$expresion="/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/";
			$partes=array();
			if (!preg_match($expresion, $fecha,$partes))
			{
				return false;
			}
			
			$aux=mktime(0,0,0,$partes[2],$partes[1],$partes[3]);
			if (!$aux)
			    return false;
			
			if ($partes[1]!=date('d',$aux) ||
			    $partes[2]!=date('m',$aux) ||
			    $partes[3]!=date('Y',$aux))
			   {
			   	return false;
			   }	
			$fecha=substr("00".$partes[1],-2)."/".
					substr("00".$partes[2],-2)."/".
					substr("0000".$partes[3],-4);
			return true;
		}
		
		/**
		 * Método que comprueba que $hora es una hora válida en el formato hh:mm:ss
		 * La sanea para que todas las partes tengan dos cifras.
		 * @param string $hora Hora a comprobar en formato hh:mm:ss. La sanea para que
		 * todas las partes tenga dos cifras numéricas.
		 * @return boolean
		 */
		public static function validaHora(string &$hora):bool
		{
			$hora=trim($hora);
			$expresion="/^(\d{1,2}):(\d{1,2}):(\d{1,2})$/";
			$partes=array();
			if (!preg_match($expresion, $hora,$partes))
			{
				return false;
			}
			
		    $aux=mktime($partes[1],$partes[2],$partes[3]);
			if ($partes[1]!=date('H',$aux) ||
			    $partes[2]!=date('i',$aux) ||
			    $partes[3]!=date('s',$aux))
			   {
			   	return false;
			   }	
			$hora=substr("00".$partes[1],-2).":".
					substr("00".$partes[2],-2).":".
					substr("00".$partes[3],-2);
			return true;
			
			
			
		}
	}
