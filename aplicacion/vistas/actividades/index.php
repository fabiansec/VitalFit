<?php
$cajaFiltrado = new CCaja(
    "Filtrar",
    "",
    array("style" => "width:80%;border-radius:10px;")
);

$this->textoHead = CPager::requisitos();
$this->textoHead .= CCaja::requisitos();
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


echo CHTML::campoLabel('Categoria', 'categoria', []);
echo CHTML::campoListaDropDown(
    'categoria',
    $filtrado['categoria'],
    $categorias,
    array('linea' => 'Selecciona la categoria')
);
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::campoBotonSubmit('Enviar', []);
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiquetaCierre('fieldset');
echo CHTML::finalizarForm();
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo $cajaFiltrado->dibujaFin();

echo CHTML::dibujaEtiqueta('div', ["class" => 'actividades']);
foreach ($filas as $propiedades => $valores) {
    $this->dibujaVistaParcial('vistaParcial',['valores' => $valores, 'modelo' => $actividades]);
}
echo CHTML::dibujaEtiquetaCierre('div'); // Cierra la etiqueta de la caja después de recorrer todas las tarjetas


$paginado = new CPager($opcpag);

echo $paginado->dibujate();
