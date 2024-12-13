<?php
$this->textoHead = CPager::requisitos();
$this->textoHead .= CCaja::requisitos();

$cajaFiltrado = new CCaja(
    "Filtrar",
    "",
    array("style" => "'width:80%; border-radius:10px;'")
);

// --------------USANDO DIBUJA APERTURA Y CIERRE----------------------
echo $cajaFiltrado->dibujaApertura();
echo CHTML::iniciarForm(" ", 'post', []);
echo CHTML::dibujaEtiqueta('fieldset', [], '', false);
echo CHTML::dibujaEtiqueta('legend', [], 'Filtrado');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::campoLabel('Nombre: ', 'nombre', []);
echo CHTML::campoText('nombre', $filtrado['nombre'], []);

echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');

echo CHTML::campoLabel('Aforo: ', 'aforo', []);
echo CHTML::campoNumber('aforo', $filtrado['aforo'], []);

echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::campoLabel('Orden por nombre: ', 'orden', []);
echo CHTML::campoListaRadioButton('orden',$filtrado['orden'],[1=>'Si',0=>'No'],'',[]);

echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::campoBotonSubmit('Enviar', []);
echo CHTML::link(
    CHTML::imagen('/imagenes/24x24/descargar.png','',['id' => 'descargar']),
    Sistema::app()->generaURL(
        ['inicial', 'descargar']

    )
);
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiquetaCierre('fieldset');
echo CHTML::finalizarForm();
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo $cajaFiltrado->dibujaFin();



echo CHTML::dibujaEtiqueta('div', ["class" => 'actividades']);
foreach ($filas as $propiedades => $valores) {
    $this->dibujaVistaParcial('vistaParcial',['valores' => $valores]);

}
echo CHTML::dibujaEtiquetaCierre('div'); // Cierra la etiqueta de la caja después de recorrer todas las tarjetas
  
$paginado = new CPager($opcpag);

echo $paginado->dibujate();
