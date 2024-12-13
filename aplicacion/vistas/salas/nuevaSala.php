<?php
// echo CHTML::botonHtml(CHTML::link("Volver", Sistema::app()->generaURL(["actividades", 'indexTabla']), ['class' => 'opciones']));
if(isset($errores['sala'])){
    echo CHTML::dibujaEtiqueta('p',['class' => 'error'], 'Sala ' . " => " .$errores['sala']);
}
echo CHTML::dibujaEtiqueta('div', ["class" => 'datos']);
echo CHTML::iniciarForm(" ", 'post', ["enctype" => "multipart/form-data"]);
    foreach($cab as $val => $ind){
             if ($ind === 'borrado') {
                echo CHTML::dibujaEtiqueta('label',[],$ind );
                echo CHTML::dibujaEtiqueta('br');
            echo CHTML::campoListaDropDown(
                'borrado',
                 $datos['borrado'],
                [-1 => 'Seleccione una opcion',0 => 'No', 1 => 'Si'],['linea' => false]
            );
            if(isset($errores[$ind])){
                echo CHTML::dibujaEtiqueta('p',['class' => 'error'],$ind . " => " .$errores[$ind]);
            }                
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::dibujaEtiqueta('br');
        } else if ($ind === 'nombre') {
            echo CHTML::dibujaEtiqueta('label',[],$ind );
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::campoText($ind,$datos['nombre'], []);
            if(isset($errores[$ind])){
                echo CHTML::dibujaEtiqueta('p',['class' => 'error'],$ind . " => " .$errores[$ind]);
            }             
             echo CHTML::dibujaEtiqueta('br');
            echo CHTML::dibujaEtiqueta('br');
        }else if($ind === 'descripcion'){
            echo CHTML::dibujaEtiqueta('label',[],$ind );
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::campoTextArea($ind,$datos['descripcion'], ['cols' => '40', 'rows' => '5']);
            if(isset($errores[$ind])){
                echo CHTML::dibujaEtiqueta('p',['class' => 'error'],$ind . " => " .$errores[$ind]);
            }
            echo CHTML::dibujaEtiqueta('br');
            echo CHTML::dibujaEtiqueta('br');
        }
    }
    
    

echo CHTML::campoBotonSubmit('Enviar', []);
echo CHTML::finalizarForm();
echo CHTML::dibujaEtiquetaCierre('div'); // Cierra la etiqueta de la caja después de recorrer todas las tarjetas
