<?php
echo CHTML::botonHtml(CHTML::link("Modicar", Sistema::app()->generaURL(["actividades", 'modificar/id='. $modelo->cod_actividad]),['class' => 'opciones']));
echo CHTML::botonHtml(CHTML::link("Ver", Sistema::app()->generaURL(["actividades", 'ver/id='. $modelo->cod_actividad]),['class' => 'opciones']));
echo CHTML::botonHtml(CHTML::link("Volver", Sistema::app()->generaURL(["actividades", 'indexTabla']), ['class' => 'opciones']));
echo CHTML::iniciarForm(" ",'post',["enctype" => "multipart/form-data"]);
echo CHTML::dibujaEtiqueta('div', ["class" => 'datos']);
foreach($modelo as $propiedades => $valores){
        
        if(in_array($propiedades, $cab)){    
            echo CHTML::dibujaEtiqueta('h3',[],$propiedades);
            echo CHTML::dibujaEtiqueta('br');
            if($propiedades === 'foto'){
                echo CHTML::imagen('../../imagenes/actividades/'.$valores,'',['class' => 'imgTabla']);
                echo CHTML::dibujaEtiqueta('br');
                echo CHTML::dibujaEtiqueta('br');
        
            }else if($propiedades === 'borrado'){
                    if($valores === 0){
                        echo CHTML::dibujaEtiqueta('p',[],'No');
                        echo CHTML::dibujaEtiqueta('br');
                        echo CHTML::dibujaEtiqueta('br');
        
                    }else{
                        echo CHTML::dibujaEtiqueta('p',[],'Si');
                        echo CHTML::dibujaEtiqueta('br');
                        echo CHTML::dibujaEtiqueta('br');
        
                    }
            }else{
                echo CHTML::dibujaEtiqueta('p',[],$valores);
                echo CHTML::dibujaEtiqueta('br');
                echo CHTML::dibujaEtiqueta('br');
        
            }
        }
       
}

echo CHTML::dibujaEtiquetaCierre('div'); // Cierra la etiqueta de la caja después de recorrer todas las tarjetas
echo CHTML::campoBotonSubmit('Enviar',['class' => 'opciones']);
echo CHTML::finalizarForm();
