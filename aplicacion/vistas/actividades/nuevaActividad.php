<?php
echo CHTML::botonHtml(CHTML::link("Volver", Sistema::app()->generaURL(["actividades", 'indexTabla']), ['class' => 'opciones']));
echo CHTML::dibujaEtiqueta('div', ["class" => 'datos']);
echo CHTML::iniciarForm(" ", 'post', ["enctype" => "multipart/form-data"]);
foreach ($modelo as $propiedades => $valores) {

    if (in_array($propiedades, $cab)) {


        if ($propiedades === 'foto') {
            echo CHTML::modeloLabel($modelo, $propiedades);
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::campoFile('foto', $valores, []);
            echo CHTML::modeloError($modelo, $propiedades);
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::dibujaEtiqueta('br');
        } else
             if ($propiedades === 'borrado') {
            echo CHTML::modeloLabel($modelo, $propiedades);
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::modeloListaRadioButton(
                $modelo,
                "borrado",
                [0 => 'No', 1 => 'Si']
            );
            echo CHTML::modeloError($modelo, 'borrado');

            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::dibujaEtiqueta('br');
        } else if ($propiedades === 'nombre'|| $propiedades === 'hora') {
            echo CHTML::modeloLabel($modelo, $propiedades);
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::modeloText($modelo, $propiedades, ['value' => $valores]);
            echo CHTML::modeloError($modelo, $propiedades);
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::dibujaEtiqueta('br');
        }else if($propiedades === 'descripcion'){
            echo CHTML::modeloLabel($modelo, $propiedades);
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::modeloTextArea($modelo, $propiedades, ['value' => $valores, 'cols' => '40', 'rows' => '5']);
            echo CHTML::modeloError($modelo, $propiedades);
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::dibujaEtiqueta('br');
        }if( $propiedades === 'aforo'){
            echo CHTML::modeloLabel($modelo, $propiedades);
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::modeloNumber($modelo, $propiedades, ['value' => $valores]);
            echo CHTML::modeloError($modelo, $propiedades);
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::dibujaEtiqueta('br');
        }
    }
}

echo CHTML::modeloLabel($modelo, 'cod_categoria');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::modeloListaDropDown(
    $modelo,
    "cod_categoria",
    $categorias,
    array("linea" => "Selecciona la categoria")
);
echo CHTML::modeloError($modelo, 'cod_categoria');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::modeloLabel($modelo, 'cod_sala');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::modeloListaDropDown(
    $modelo,
    "cod_sala",
    $salas,
    array("linea" => "Selecciona la sala")
);
echo CHTML::modeloError($modelo, 'cod_sala');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::dibujaEtiqueta('br');
echo CHTML::campoBotonSubmit('Enviar', []);
echo CHTML::finalizarForm();
echo CHTML::dibujaEtiquetaCierre('div'); // Cierra la etiqueta de la caja después de recorrer todas las tarjetas
