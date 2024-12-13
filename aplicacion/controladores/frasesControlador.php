<?php

class frasesControlador extends CControlador
{

    function __construct()
    {
    }

    public function accionIndex()
    {
        $this->barraUbi = [
            [
                "texto" => "Inicio",
                "enlace" => ["inicial"]
            ],
            [
                "texto" => "Motivacion",
                "enlace" => ["frases"]
            ],
        ];

        //Compruebo si tiene permisos
        if (!Sistema::app()->Acceso()->hayUsuario()) {
            Sistema::app()->irAPagina(array('registro', 'login'), []);
            exit;
        }

        if (!(Sistema::app()->Acceso()->puedePermiso(9))) {
            Sistema::app()->paginaError(404, 'No tienes permisos');
            exit;
        }

        $_SESSION['accion'] = ["frases", "index"];






        $this->dibujaVista(
            "index",
            [],
            "Motivacion"
        );
    }
    public function accionFrase()
    {

        //Compruebo si tiene permisos
        if (!Sistema::app()->Acceso()->hayUsuario()) {
            Sistema::app()->irAPagina(array('registro', 'login'), []);
            exit;
        }

        if (!(Sistema::app()->Acceso()->puedePermiso(9))) {
            Sistema::app()->paginaError(404, 'No tienes permisos');
            exit;
        }

        //------------------------------------GET----------------------------------

        $rutaArchivo = RUTA_BASE . '/frases/frases.json';
        $jsonContenido = file_get_contents($rutaArchivo); //obtengo el contenido del json
        $datos = json_decode($jsonContenido, true);
        $nick = Sistema::app()->Acceso()->getNick();
        $datos['usuario'] = $nick;
        $f = '';
        $autor = '';
        // Agregar las frases del usuario actual al array de frases en $datos
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_SESSION[$nick]['frases'])) {
            $datos['frases'] = array_merge($datos['frases'], $_SESSION[$nick]['frases']); //agrego a los datos, las frases agregadas por el usuario
            }
    
            echo json_encode($datos);
    
          
        }
       

        /* Si hacen click sobre una frase creo una cookie para ese usuario
           asi cada usuario va a tener un eslogan distinto */

        if (isset($_POST['frase'])) {
            if (isset($_COOKIE[$nick])) {
               
              setcookie($nick, 'user=' . $nick . ';frase=' . trim($_POST['frase']), time() + (100 * 365 * 24 * 60 * 60), "/");
                echo json_encode(true);
             }else{
                setcookie($nick, 'user=' . $nick . ';frase=' . trim($_POST['frase']),time() + (100 * 365 * 24 * 60 * 60), "/");
                echo json_encode(true);
            }
        }
        //-----------------------------------POST---------------------------------

        //--------------AGREGAR FRASE AL FICHERO JSON------------------------
        // creo una sesion por usuario donde se van almacenar todas las frases
        // que el agregue y esa sesion la agrego al final del JSON a enviar para que cada usuario tengas sus frases propias
        // y no modificar el fichero JSON

        if (isset($_POST['nuevaFrase']) && isset($_POST['nuevoAutor'])) {
           // $_SESSION[$nick]['frase'] = [];
            $nuevaFrase = trim($_POST['nuevaFrase']);
            $nuevoAutor = trim($_POST['nuevoAutor']);
            $errores = [];
            
            // Validaciones
            if ($nuevoAutor === "") {
                $errores['autor'] = "El autor no puede estar vacío.";
            } else if (!preg_match('/^[a-zA-Z]+$/', $nuevoAutor)) {
                $errores['autor'] = "El autor no puede contener números o caracteres especiales.";
            }

            if ($nuevaFrase === "") {
                $errores['frase'] = "La frase no puede estar vacía.";
            } else if (!preg_match('/^[A-Z][a-zA-Z\s]{9,19}$/', $nuevaFrase)) {
                $errores['frase'] = "La frase debe tener al menos 10 caracteres, comenzar con mayúscula, y no superar los 20 caracteres.";
            }


            if (!$errores) {

                //Creo la sesion para las frases del usuario

                if (isset($_SESSION[$nick]['frases'])) {
                    $_SESSION[$nick]['frases'][]= [
                        'autor' => $nuevoAutor,
                        'frase' => $nuevaFrase
                    ];
                    foreach($_SESSION[$nick]['frases'] as $ind => $frase){
                        if($ind === 'autor'){
                            $autor = $frase;
                        }else{
                            $f = $frase;
                        }
                        $datos['frases'][] = ['autor' => $autor, 'frase' => $f];

                    }
                } else {
                    $_SESSION[$nick]['frases'][] = [ 'autor' => $nuevoAutor,
                    'frase' => $nuevaFrase];
                }

                echo json_encode($datos);

            }
        }
    }
}
