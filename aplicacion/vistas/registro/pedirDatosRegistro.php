<?php
echo CHTML::iniciarForm();

echo CHTML::modeloLabel($modelo, 'nick');
echo CHTML::modeloText(
    $modelo,
    "nick",
    
);
echo CHTML::modeloError($modelo, "nick");
echo "<br>";
echo CHTML::modeloLabel($modelo, 'nif');
echo CHTML::modeloText(
    $modelo,
    "nif",
    
);
echo CHTML::modeloError($modelo, "nif");
echo "<br>";

echo CHTML::modeloLabel($modelo, 'fecha_nacimiento');
echo CHTML::modeloText(
    $modelo,
    "fecha_nacimiento",
    
);
echo CHTML::modeloError($modelo, "fecha_nacimiento");
echo "<br>";

echo CHTML::modeloLabel($modelo, 'provincia');
echo CHTML::modeloText(
    $modelo,
    "provincia",
    
);
echo CHTML::modeloError($modelo, "provincia");
echo "<br>";

echo CHTML::modeloLabel($modelo, "estado");
echo CHTML::modeloListaRadioButton(
    $modelo,
    "estado",
    DatosRegistro::dameEstado(), " ",
    array("uncheckValor" => -1)
);
echo CHTML::modeloError($modelo, "estado");
echo "<br>";

echo CHTML::modeloLabel($modelo, 'contrasenia');
echo CHTML::modeloPassword(
    $modelo,
    "contrasenia",

    
);
echo CHTML::modeloError($modelo, "contrasenia");
echo "<br>";

echo CHTML::modeloLabel($modelo, 'confirmar_contrasenia');
echo CHTML::modeloPassword(
    $modelo,
    "confirmar_contrasenia",

    
);
echo CHTML::modeloError($modelo, "confirmar_contrasenia");
echo "<br>";


echo CHTML::campoBotonSubmit("Crear");
echo CHTML::finalizarForm();
