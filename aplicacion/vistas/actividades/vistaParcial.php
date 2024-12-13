<?php
    echo CHTML::dibujaEtiqueta('div', ["class" => 'card', "style" => "height:430px;"]);
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

        if ($campo === 'hora') {
            echo CHTML::dibujaEtiqueta('p', [], $valor . " h");
        }
    }
    if(isset($valores['deshabilitar']) && $valores['deshabilitar'] ===true){
        echo CHTML::dibujaEtiqueta('p', ['style' => 'color: rgb(162, 215, 200); text-shadow: 0px 0px 8px white; border-bottom: 1px solid white;'], "No puedes realizar reserva, cumplio su hora");
    }else
    if ($valores['aforo'] != '30') {
        echo CHTML::iniciarForm(" ", 'post', []);
        echo CHTML::modeloHidden($modelo,$valores['cod_actividad'],['name' => 'actividad','value' => $valores['cod_actividad']]);
        echo CHTML::modeloHidden($modelo,$valores['nombre'],['name' => 'nom', 'value' => $valores['nombre']]);
        echo CHTML::modeloHidden($modelo,$valores['hora'],['name' => 'hor', 'value' => $valores['hora']]);
        echo CHTML::modeloHidden($modelo,$valores['aforo'],['name' => 'afo', 'value' => $valores['aforo']]);
        echo CHTML::campoBotonSubmit('Enviar', ['name' => 'apuntarse']);
        echo CHTML::finalizarForm();
    }else if($valores['borrado'] === '1'){
        echo CHTML::dibujaEtiqueta('p', ['style' => 'color: rgb(162, 215, 200); text-shadow: 0px 0px 8px white; border-bottom: 1px solid white;'], "No disponible");

    }else{
        echo CHTML::dibujaEtiqueta('p', ['style' => 'color: rgb(162, 215, 200); text-shadow: 0px 0px 8px white; border-bottom: 1px solid white;'], "Completado");

    }
    


    echo CHTML::dibujaEtiquetaCierre('div'); // Cierra la etiqueta de la tarjeta, no la caja


