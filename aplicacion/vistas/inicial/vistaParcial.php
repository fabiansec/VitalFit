<?php
echo CHTML::dibujaEtiqueta('div', ["class" => 'card']); 
    echo CHTML::dibujaEtiqueta('div', ["class" => 'ribbon'], 'Destacado'); // Texto del ribbon
    foreach ($valores as $campo => $valor) {
        if ($campo === 'nombre') {
            echo CHTML::dibujaEtiqueta('p', [], $valor);
        }
        if ($campo === 'aforo') {
            echo CHTML::dibujaEtiqueta('p', [], 'Aforo: ' . $valor);
        }

        if ($campo === 'categoria') {
            echo CHTML::dibujaEtiqueta('p', [], 'Categoria: ' . $valor);
        }

        if ($campo === 'foto') {
            echo CHTML::imagen('../../imagenes/actividades/' . $valor, '', []);
        }

        if ($campo === 'sala') {
            echo CHTML::dibujaEtiqueta('p', [], 'Sala: ' . $valor);
        }
    }
    echo CHTML::dibujaEtiquetaCierre('div'); // Cierra la etiqueta de la tarjeta, no la caja