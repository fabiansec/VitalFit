<?php

echo CHTML::iniciarForm(' ','post',['class' => 'login']);

echo CHTML::modeloLabel($modelo, 'nick');
echo CHTML::modeloText(
    $modelo,
    "nick",
    ['placeholder' => 'Introduce el nick']
    
);
echo CHTML::modeloError($modelo, "nick");
echo "<br>";

echo CHTML::modeloLabel($modelo, 'contrasenia');
echo CHTML::modeloPassword(
    $modelo,
    "contrasenia",
    ['placeholder' => 'Introduce la contraseña']
    
);
echo CHTML::modeloError($modelo, "contrasenia");
echo "<br>";


echo CHTML::campoBotonSubmit("Iniciar sesion");
echo CHTML::campoBotonSubmit("Volver");
echo CHTML::finalizarForm();
