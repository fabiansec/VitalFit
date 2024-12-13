
<?php


echo CHTML::dibujaEtiqueta('div',['class' =>'videoC'],'',false);
echo CHTML::dibujaEtiqueta('video',['controls' => 'controls','autoplay' => 'autoplay', 'muted' => 'muted','id' => 'video'],'',false);
echo CHTML::dibujaEtiqueta('source',['src' => '../../../estilos/video/video.mp4' , 'type' => 'video/mp4']);
echo CHTML::dibujaEtiquetaCierre('video');
echo CHTML::dibujaEtiquetaCierre('div');
$ruta = RUTA_BASE . '/js/main.js';
$this->textoHead = CHTML::scriptFichero('/js/main.js',['defer' => 'defer']);;
echo CHTML::imagen('../../../imagenes/ayuda.png','',['id' => 'ayuda']);
?>


