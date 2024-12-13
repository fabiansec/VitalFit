<?php

if (isset($filas)) {

    foreach ($filas as $propiedades => $valores) {
        echo CHTML::botonHtml(CHTML::link("Ver", Sistema::app()->generaURL(["salas", 'ver/id=' . $propiedades]), ['class' => 'opciones']));
        echo CHTML::botonHtml(CHTML::link("Borrar", Sistema::app()->generaURL(["salas", 'borrar/id=' . $propiedades]), ['class' => 'opciones']));
        echo CHTML::botonHtml(CHTML::link("Volver", Sistema::app()->generaURL(["salas", 'indexTabla']), ['class' => 'opciones']));
        echo CHTML::dibujaEtiqueta('div', ["class" => 'datos']);
        echo CHTML::iniciarForm(" ", 'post', ["enctype" => "multipart/form-data"]);
        foreach ($valores as $ind => $valor) {
            if (in_array($ind, $cab)) {


                if ($ind === 'borrado') {
                    echo CHTML::dibujaEtiqueta('label', [], $ind);
                    echo CHTML::dibujaEtiqueta('br');
                    echo CHTML::campoListaDropDown(
                        'borrado',
                        $valor,
                        [-1 => 'Seleccione una opcion', 0 => 'No', 1 => 'Si'],
                        ['linea' => false]
                    );
                    if (isset($errores[$ind])) {
                        echo CHTML::dibujaEtiqueta('p', ['class' => 'error'], $ind . " => " . $errores[$ind]);
                    }
                    echo CHTML::dibujaEtiqueta('br');
                    echo CHTML::dibujaEtiqueta('br');
                } else if ($ind === 'nombre') {
                    echo CHTML::dibujaEtiqueta('label', [], $ind);
                    echo CHTML::dibujaEtiqueta('br');
                    echo CHTML::campoText($ind, $valor, []);
                    if (isset($errores[$ind])) {
                        echo CHTML::dibujaEtiqueta('p', ['class' => 'error'], $ind . " => " . $errores[$ind]);
                    }
                    echo CHTML::dibujaEtiqueta('br');
                    echo CHTML::dibujaEtiqueta('br');
                } else if ($ind === 'descripcion') {
                    echo CHTML::dibujaEtiqueta('label', [], $ind);
                    echo CHTML::dibujaEtiqueta('br');
                    echo CHTML::campoTextArea($ind, $valor, ['cols' => '40', 'rows' => '5']);
                    if (isset($errores[$ind])) {
                        echo CHTML::dibujaEtiqueta('p', ['class' => 'error'], $ind . " => " . $errores[$ind]);
                    }
                    echo CHTML::dibujaEtiqueta('br');
                    echo CHTML::dibujaEtiqueta('br');
                }
            }
        }
    }
    echo CHTML::campoBotonSubmit('Enviar', []);
    echo CHTML::finalizarForm();
    echo CHTML::dibujaEtiquetaCierre('div');
}
 // Cierra la etiqueta de la caja después de recorrer todas las tarjetas
