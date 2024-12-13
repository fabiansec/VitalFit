<?php
class CMenu extends CWidget
{
    private string $_contenido;
    private array $_atributos;

    public function __construct($contenido = "", $atributosHTML = array())
    
    {
        $this->_contenido = $contenido;
        if (!isset($this->_atributos["class"]))
        $this->_atributos["class"]="barraMenu";
       
        
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
        
        echo CHTML::dibujaEtiqueta("nav",$this->_atributos, "", false);
        if($this->_contenido != ''){
            echo $this->_contenido;
        }

        $escrito = ob_get_contents();
        ob_end_clean();

        return $escrito;

    }

    public function dibujaFin():string
    {
         return  CHTML::dibujaEtiquetaCierre('nav').PHP_EOL;

    }

   

}
