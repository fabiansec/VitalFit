<?php
	/**
	 * Clase base que define el comportamiento que debe tener todo widget.
	 * Es una clase abstracta
	 */
	abstract class CWidget
	{
		/**
		 * Método que devuelve una cadena con el codigo HTML para dibujar
		 * el componente
		 *
		 * @return string
		 */
		public abstract function dibujate():string;
		
		/**
		 * Método que devuelve una cadena con el código HTML para dibujar
		 * la apertura del componente
		 *
		 * @return string
		 */
		public abstract function dibujaApertura():string;
		
		/**
		 * Método que devuelve una cadena con el código HTML para dibujar
		 * el cierre del componente
		 *
		 * @return string
		 */
		public abstract function dibujaFin():string;
		
		public static function requisitos():string
		{
			return '';
		}
	}
	