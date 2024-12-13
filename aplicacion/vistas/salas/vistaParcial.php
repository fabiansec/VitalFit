<?php
echo CHTML::dibujaEtiqueta('div', ["class" => 'card', "style" => "height:430px;" ]);
foreach ($valores as $campo => $valor) {
    if ($campo === 'nombre') {
        echo CHTML::dibujaEtiqueta('p', [], $valor);
    }
    if ($campo === 'descripcion') {
        echo CHTML::dibujaEtiqueta('p', [], 'Descripcion: ' . $valor);
    }

    if ($campo === 'foto') {
        echo CHTML::imagen('../../imagenes/salas/' . $valor, '', []);
    }
}
echo CHTML::dibujaEtiquetaCierre('div'); // Cierra la etiqueta de la tarjeta, no la caja
