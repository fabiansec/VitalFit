<?php

if (isset($filas)) {

    foreach ($filas as $propiedades => $valores) {
        echo CHTML::botonHtml(CHTML::link("Ver", Sistema::app()->generaURL(["salas", 'ver/id=' . $propiedades]), ['class' => 'opciones']));
        echo CHTML::botonHtml(CHTML::link("Modificar", Sistema::app()->generaURL(["salas", 'modificar/id=' . $propiedades]), ['class' => 'opciones']));
        echo CHTML::botonHtml(CHTML::link("Volver", Sistema::app()->generaURL(["salas", 'indexTabla']), ['class' => 'opciones']));
        echo CHTML::dibujaEtiqueta('div', ["class" => 'datos']);
        echo CHTML::iniciarForm(" ", 'post', ["enctype" => "multipart/form-data"]);
        foreach ($valores as $ind => $valor) {
            if (in_array($ind, $cab)) {
                echo CHTML::dibujaEtiqueta('h3', [], $ind);
                echo CHTML::dibujaEtiqueta('br');
                if ($ind === 'foto') {
                    echo CHTML::imagen('../../imagenes/salas/' . $valor, '', ['class' => 'imgTabla']);
                    echo CHTML::dibujaEtiqueta('br');
                    echo CHTML::dibujaEtiqueta('br');
                } else if ($ind === 'borrado') {
                    if ($valor === '0') {
                        echo CHTML::dibujaEtiqueta('p', [], 'No');
                        echo CHTML::dibujaEtiqueta('br');
                        echo CHTML::dibujaEtiqueta('br');
                    } else {
                        echo CHTML::dibujaEtiqueta('p', [], 'Si');
                        echo CHTML::dibujaEtiqueta('br');
                        echo CHTML::dibujaEtiqueta('br');
                    }
                } else {
                    echo CHTML::dibujaEtiqueta('p', [], $valor);
                    echo CHTML::dibujaEtiqueta('br');
                    echo CHTML::dibujaEtiqueta('br');
                }
            }
        }
        echo CHTML::dibujaEtiquetaCierre('div'); // Cierra la etiqueta de la caja después de recorrer todas las tarjetas
        echo CHTML::campoBotonSubmit('Enviar', ['class' => 'opciones']);
        echo CHTML::finalizarForm();
    }
}
