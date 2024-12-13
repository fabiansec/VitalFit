<?php
 if (isset($errores['Actividad'])) {
    echo CHTML::dibujaEtiqueta('p', ['class' => 'error'], $errores['Actividad']);
}
echo CHTML::iniciarForm(" ", 'post', ["enctype" => "multipart/form-data"]); //muestro donde el cliente quiere reservar ahora
if(isset($datos['actividades']) and count($datos['actividades']) != 0){
    echo CHTML::dibujaEtiqueta('div', ['class' => 'reserva']);
    foreach ($datos['actividades'] as $act => $valor) {
            echo CHTML::dibujaEtiqueta('p', [], 'Actividad: ' . $valor['nombre']);
            echo CHTML::dibujaEtiqueta('p', [], 'Hora: ' . $valor['hora']);
            echo CHTML::modeloHidden($modelo,'cod_actividad',['value' => $valor['id_actividad'] , 'name' => 'cod_actividad_' .$valor['id_actividad']]);
            // Utiliza el índice único también para los nombres de los botones
        
        
            echo CHTML::dibujaEtiqueta('hr');
    }

    
    echo CHTML::modeloListaDropDown(
        $modelo,
        'metodo',
        $metodos,
        []
    );
    echo CHTML::modeloError($modelo, 'metodo');
    echo CHTML::campoBotonSubmit('Reservar'); // Asegúrate de ajustar según sea necesario
    echo CHTML::finalizarForm();
    echo CHTML::dibujaEtiquetaCierre('div');
}else{  
    foreach ($filas as $act => $valor) {
        echo CHTML::dibujaEtiqueta('div', ['class' => 'reservas']); //muestro todas sus reservas que tiene hoy
        foreach($valor as $ind => $reser){
            if($ind === 'nombre_usuario'){
                echo CHTML::dibujaEtiqueta('p', [],$reser);
            }
            if($ind === 'nombre_actividad'){
                echo CHTML::dibujaEtiqueta('p', [],'Actividad: ' .$reser);
            }

            if($ind === 'fecha'){
                echo CHTML::dibujaEtiqueta('p', [],'Fecha: ' .CGeneral::fechaMysqlANormal($reser));
            }  

            if($ind === 'hora'){
                echo CHTML::dibujaEtiqueta('p', [],'Hora: ' . $reser . ' h');
            } 
        }
        echo CHTML::dibujaEtiquetaCierre('div');
    }
}




?>
