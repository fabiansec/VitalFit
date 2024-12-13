<?php


class CPager extends CWidget
{
	private $_opciones = array();
	private $_atributosHTML = array();

	public function __construct($opciones, $atributosHTML = array())
	{

		$this->_opciones = array(
			"URL" => "#",
			"URL_PAGER" => "#",
			"URL_AMIGABLES" => false,
			"TOTAL_REGISTROS" => 0,
			"PAGINA_ACTUAL" => 1,
			"REGISTROS_PAGINA" => 10,
			"TAMANIOS_PAGINA" => array(
				10 => "10",
				20 => "20",
				30 => "30",
				40 => "40"
			),
			"MOSTRAR_TAMANIOS" => true,
			"PAGINAS_MOSTRADAS" => 5,
			"PAGINAS" => 0,
			"REGISTRO_DESDE" => 0,
			"REGISTRO_HASTA" => 0
		);




		//valido los datos que me pasen
		if (
			isset($opciones["TOTAL_REGISTROS"]) &&
			is_numeric($opciones["TOTAL_REGISTROS"]) &&
			$opciones["TOTAL_REGISTROS"] > 0
		)
			$this->_opciones["TOTAL_REGISTROS"] = intval($opciones["TOTAL_REGISTROS"]);

		if (
			isset($opciones["REGISTROS_PAGINA"]) &&
			is_numeric($opciones["REGISTROS_PAGINA"]) &&
			$opciones["REGISTROS_PAGINA"] > 0
		)
			$this->_opciones["REGISTROS_PAGINA"] = intval($opciones["REGISTROS_PAGINA"]);

		$this->_opciones["PAGINAS"] = intval(ceil((1.0 * $opciones["TOTAL_REGISTROS"]) / $this->_opciones["REGISTROS_PAGINA"]));

		if (
			isset($opciones["PAGINA_ACTUAL"]) &&
			is_numeric($opciones["PAGINA_ACTUAL"]) &&
			$opciones["PAGINA_ACTUAL"] > 0
		)
			$this->_opciones["PAGINA_ACTUAL"] = intval($opciones["PAGINA_ACTUAL"]);

		if ($this->_opciones["PAGINA_ACTUAL"] > $this->_opciones["PAGINAS"])
			$this->_opciones["PAGINA_ACTUAL"] = $this->_opciones["PAGINAS"];

		if ($this->_opciones["TOTAL_REGISTROS"] > 0) {
			$this->_opciones["REGISTRO_DESDE"] = (($opciones["PAGINA_ACTUAL"] - 1) *
				$this->_opciones["REGISTROS_PAGINA"]) + 1;
			$this->_opciones["REGISTRO_HASTA"] = $this->_opciones["REGISTRO_DESDE"] +
				$this->_opciones["REGISTROS_PAGINA"] - 1;
			if ($this->_opciones["REGISTRO_HASTA"] > $this->_opciones["TOTAL_REGISTROS"])
				$this->_opciones["REGISTRO_HASTA"] = $this->_opciones["TOTAL_REGISTROS"];
		}

		if (
			isset($opciones["TAMANIOS_PAGINA"]) &&
			is_array($opciones["TAMANIOS_PAGINA"])
		) {
			$this->_opciones["TAMANIOS_PAGINA"] = $opciones["TAMANIOS_PAGINA"];
			foreach ($this->_opciones["TAMANIOS_PAGINA"] as $indice => $valor) {
				$this->_opciones["TAMANIOS_PAGINA"][$indice] = intval($valor);
			}
		}



		if (
			isset($opciones["MOSTRAR_TAMANIOS"]) &&
			is_bool($opciones["MOSTRAR_TAMANIOS"])
		)
			$this->_opciones["MOSTRAR_TAMANIOS"] = $opciones["MOSTRAR_TAMANIOS"];

		if (
			isset($opciones["PAGINAS_MOSTRADAS"]) &&
			is_numeric($opciones["PAGINAS_MOSTRADAS"]) &&
			$opciones["PAGINAS_MOSTRADAS"] > 0
		)
			$this->_opciones["PAGINAS_MOSTRADAS"] = intval($opciones["PAGINAS_MOSTRADAS"]);


		if (
			isset($opciones["URL"]) &&
			is_string($opciones["URL"])
		)
			$this->_opciones["URL"] = $opciones["URL"];


		$this->_opciones["URL_PAGER"] = Sistema::app()->generaURL(
				$this->_opciones["URL"],
				array("pag" => 1)
			);


		$this->_opciones["URL"] = Sistema::app()->generaURL(
			$this->_opciones["URL"],
			array("reg_pag" => $opciones["REGISTROS_PAGINA"])
		);

		$this->_atributosHTML = $atributosHTML;
		if (!isset($this->_atributosHTML["class"]))
			$this->_atributosHTML["class"] = "pager";
	}

	public static function requisitos(): string
	{
		$codigo = <<<EOF
    function cambioListaPager(combo, url) {
        location = url + "&reg_pag=" + combo.value;
    }

   
EOF;
		return CHTML::script($codigo);
	}

	public function dibujate(): string
	{
		return $this->dibujaApertura() . $this->dibujaFin();
	}

	public function dibujaApertura(): string
	{
		ob_start();

		echo CHTML::dibujaEtiqueta("div", $this->_atributosHTML, "", false);

		//informacion de la pagina
		$cadena = "<div  class='izq'>";
		if ($this->_opciones["MOSTRAR_TAMANIOS"]) {
			$cadena .= CHTML::campoListaDropDown(
				"pagina",
				$this->_opciones["REGISTROS_PAGINA"],
				$this->_opciones["TAMANIOS_PAGINA"],
				array(
					"linea" => false,
					"onchange" => "cambioListaPager(this,'{$this->_opciones["URL_PAGER"]}');"
				)
			);
		}
		$cadena .= " Registros {$this->_opciones["REGISTRO_DESDE"]} a {$this->_opciones["REGISTRO_HASTA"]} de " .
			" {$this->_opciones["TOTAL_REGISTROS"]}";
		$cadena .= "</div>";

		echo $cadena;

		//botones			
		$cadena = "<div class='der'>";
		$paginas = $this->_opciones["PAGINAS"];
		$pagActual = $this->_opciones["PAGINA_ACTUAL"];
		$pagMostradas = $this->_opciones["PAGINAS_MOSTRADAS"];
		if ($paginas > 0) {
			if ($pagActual > 1) {
				//pagina primera
				$ruta = $this->_opciones["URL"] . "&pag=1";
				$cadena .= " " . CHTML::link(CHTML::imagen("/imagenes/16x16/primero.png"), $ruta);
				//pagina anterior
				$ruta = $this->_opciones["URL"] . "&pag=" . ($pagActual - 1);
				$cadena .= " " . CHTML::link(CHTML::imagen("/imagenes/16x16/anterior.png"), $ruta);
			}
			//paginas intermedias
			$primera = $pagActual - intval($pagMostradas / 2);
			if ($primera < 1)
				$primera = 1;

			$ultima = $primera + $pagMostradas - 1;
			if ($ultima > $paginas) {
				$ultima = $paginas;
				$primera = $paginas - $pagMostradas + 1;
				if ($primera < 1)
					$primera = 1;
			}

			for ($i = $primera; $i <= $ultima; $i++) {
				if ($i == $pagActual)
					$cadena .= " $i";
				else {
					$ruta = $this->_opciones["URL"] . "&pag=$i";
					$cadena .= " " . CHTML::link($i, $ruta);
				}
			}

			if ($pagActual < $paginas) { //pagina siguiente
				$ruta = $this->_opciones["URL"] . "&pag=" . ($pagActual + 1);
				$cadena .= " " . CHTML::link(CHTML::imagen("/imagenes/16x16/siguiente.png"), $ruta);

				//pagina ultima
				$ruta = $this->_opciones["URL"] . "&pag=" . ($paginas);
				$cadena .= " " . CHTML::link(CHTML::imagen("/imagenes/16x16/ultimo.png"), $ruta);
			}
		};
		$cadena .= "</div>";
		echo $cadena;

		//para que continue el flujon normal
		$cadena = "<div class='final'></div>";
		echo $cadena;

		echo CHTML::dibujaEtiquetaCierre("div");

		$escrito = ob_get_contents();
		ob_end_clean();

		return $escrito;
	}

	public function dibujaFin(): string
	{
		return "";
	}
}
