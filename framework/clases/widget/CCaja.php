<?php
class CCaja extends CWidget
{
    private string $_titulo;
    private string $_contenido;
    private array $_atributos;

    public function __construct($titulo, $contenido = "", $atributosHTML = array())
    {
        $this->_titulo = $titulo;
        $this->_contenido = $contenido;
        $this->_atributos = $atributosHTML;

        if (!isset($this->_atributos["class"]))
				$this->_atributos["class"]="caja";
        
    }

    public function dibujate(): string
    {
        return $this->dibujaApertura() . $this->dibujaFin();
    }

    public function dibujaApertura(): string
    {
        ob_start();
        $idCaja=CHTML::generaID();
        $idBoton=CHTML::generaID();

        echo CHTML::dibujaEtiqueta("div", $this->_atributos, "", false);
        echo CHTML::dibujaEtiqueta('h1', [],$this->_titulo,true);
        echo CHTML::botonHtml('Ocultar', ["id"=>$idBoton,  "onclick"=>"ocultarContenido('$idBoton','$idCaja')"]);
        echo CHTML::dibujaEtiqueta('div', ['id' => $idCaja],'',false);
        if($this->_contenido != ''){
            echo $this->_contenido;
        }

        $escrito = ob_get_contents();
        ob_end_clean();

        return $escrito;

    }

    public function dibujaFin():string
    {
         return  CHTML::dibujaEtiquetaCierre('div')." ". CHTML::dibujaEtiquetaCierre('div').PHP_EOL;

    }

    public static function requisitos(): string
	{
		$codigo = <<<EOF
    function ocultarContenido(idBoton,idCaja) {
        {
            let formulario = document.getElementById(idCaja);
            console.log(idCaja);
            if(formulario.style.display==='none')
            {
                formulario.style.display = 'block'
            }
            else 
            {
                formulario.style.display = 'none'
            }
        }
    }
EOF;
		return CHTML::script($codigo);
	}

}
