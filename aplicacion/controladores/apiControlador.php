<?php
class apiControlador extends CControlador
{

    function __construct()
    {
    }

    public function accionSalas()
    {
        //-------------------------------------------
        //                METODO GET
        //------------------------------------------
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                        //petición por GET-> es una consulta
            if (isset($_GET["id"])) {
                //se ha indicado un elemento se consulta

                $id = intval($_GET["id"]);

                $sentenciaDatos = "Select * from sala where cod_sala = $id";
                $datos = Sistema::app()->BD()->crearConsulta($sentenciaDatos);

                // Si da error la sentencia
                if ($datos->error() != 0) {
                    $resultado = [
                        "datos" => "Error base de datos",
                        "correcto" => false
                    ]; //error,

                    $res = json_encode($resultado, JSON_PRETTY_PRINT);
                    echo $res;
                    exit;
                }

                //Si no hay filas, no hay sala
                if ($datos->numFilas() === 0) {
                    $resultado = [
                        "datos" => "Sala no encontrada",
                        "correcto" => false
                    ]; //error,

                    $res = json_encode($resultado, JSON_PRETTY_PRINT);
                    echo $res;
                    exit;
                }

                // si existe el id
                $datos = $datos->fila();
                $resultado = [
                    "datos" => [$id =>  $datos],
                    "correcto" => true
                ];

                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            } else {
                //hago el filtrado
                $where = '';
                $op = '';
                $sentenciaDatos = "Select * from sala";
                $datos = [];

               

                if (isset($_GET['op']) && $_GET['op'] != '') {
                    $limit = trim($_GET['op']);
                    $limit = CGeneral::addSlashes($limit);
                    $op = "limit $limit";
                }
            
                if (isset($_GET["nombre"]) && $_GET["nombre"] != "") {
                    // Filtro por nombre
                    $nombre = CGeneral::addSlashes(trim($_GET["nombre"]));
                    if ($where != "")
                        $where .= " and ";
                    $where .= " nombre regexp '$nombre'";
                }

                if (isset($_GET["descripcion"]) && $_GET["descripcion"] != "") {
                    // Filtro por descripcion
                    $descripcion = CGeneral::addSlashes(trim($_GET["descripcion"]));

                    if ($where != "")
                        $where .= " and ";
                    $where .= " descripcion = '$descripcion' ";
                }


                if (isset($_GET["foto"]) && $_GET["foto"] != "") {
                    // Filtro por foto
                    $foto = CGeneral::addSlashes(trim($_GET["foto"]));
                    if ($where != "")
                        $where .= " and ";
                    $where .= " foto = '$foto' ";
                }

                if (isset($_GET["borrado"]) && $_GET['borrado'] != '2') {
                    // Filtro por borrado
                    $borrado = intval($_GET["borrado"]);
                        if($where != "")
                        $where .= " and ";
                    $where .= " borrado = $borrado ";
                }


               $orden = '';
                if(isset($_GET['orden']) && $_GET['orden'] != ''){
                    $ord = intval($_GET['orden']);
                    if($ord === 1){
                        $orden = 'order by nombre';
                        $orden = CGeneral::addSlashes($orden);
                    }

                }


                if ($where === '') { //si no hay filtrado 
                    $sent = $sentenciaDatos . " " . $orden ." ". $op;
                    $datos = Sistema::app()->BD()->crearConsulta($sent);

                    if ($datos->error() != 0) {
                        $resultado = [
                            "datos" => "Error  base de datos",
                            "correcto" => false
                        ]; //error,

                        $res = json_encode($resultado, JSON_PRETTY_PRINT);
                        echo $res;
                        exit;
                    }
                    $datos = $datos->filas(); //devuelvo todas las filas
                    $resultado = [
                        "datos" => $datos,
                        "correcto" => true
                    ];
                    $res = json_encode($resultado, JSON_PRETTY_PRINT);
                    echo $res;
                    exit;
                } else {
                    $sent = $sentenciaDatos . " where" . $where . " " . $orden . " " . $op;
                    $datos = Sistema::app()->BD()->crearConsulta($sent);

                    if ($datos->error() != 0) {
                        $resultado = [
                            "datos" => "Error  base de datos",
                            "correcto" => false
                        ]; //error,

                        $res = json_encode($resultado, JSON_PRETTY_PRINT);
                        echo $res;
                        exit;
                    }
                    if ($datos->numFilas() === 0) {
                        $resultado = [
                            "datos" => "Sala no encontrada",
                            "correcto" => false
                        ]; //error,

                        $res = json_encode($resultado, JSON_PRETTY_PRINT);
                        echo $res;
                        exit;
                    }
                    $filas = $datos->filas(); //devuelvo las filas

                    $resultado = [
                        "datos" => $filas,
                        "correcto" => true
                    ];
                    $res = json_encode($resultado, JSON_PRETTY_PRINT);
                    echo $res;
                    exit;
                }
            }
        }

        //-------------------------------------------
        //                METODO POST
        //------------------------------------------
        if ($_SERVER["REQUEST_METHOD"] == "POST") {


            $errores = [];
            $parametros = apiControlador::recogerParametros(); //recojo los valores
            //-----------------VALIDACION DE DATOS---------------

            $nombre = '';
            if (isset($parametros['nombre'])) {
                $nombre = trim($parametros['nombre']);
                $nombre = CGeneral::addslashes($nombre);

                if ($nombre === '') {
                    $errores['nombre'] = 'No puede estar en blanco';
                }

                if (mb_strlen($nombre) > 30) {
                    $errores['nombre'] = 'Longitud máxima de 30 caracteres';
                }

                if (is_numeric($nombre)) {
                    $errores['nombre'] = 'El nombre no puede ser un número';
                }
            }

            $descripcion = '';
            if (isset($parametros['descripcion'])) {
                $descripcion = trim($parametros['descripcion']);
                $descripcion = CGeneral::addSlashes($descripcion);

                if (mb_strlen($descripcion) > 30) {
                    $errores['descripcion'] = 'Longitud máxima de 30 caracteres';
                }
                if (is_numeric($descripcion)) {
                    $errores['desripcion'] = 'La descripcion no puede ser un número';
                }
            }

            $foto = '';
            if (isset($parametros['foto'])) {
                $foto = trim($parametros['foto']);
                $foto = CGeneral::addSlashes($foto);
                $expr = '/^[a-zA-Z0-9_-]+\.(png)$/';

                if ($foto === '') {
                    $foto = 'defecto.png';
                }
                if (!preg_match($expr, $foto)) {
                    $errores['foto'] = 'El formato debe ser nombre.png';
                }
            }

            $borrado = '';
            if (isset($parametros['borrado'])) {
                $borrado = intval($parametros['borrado']);

                if (!in_array($borrado, [0, 1])) {
                    $errores['borrado'] = 'Seleccione opcion correcta';
                }
            }

            if (!$errores) { //hago insercion etc 

                //muevo la foto al servidor
                $rutaDestino = RUTA_BASE . '/imagenes/salas/' . $foto;

                move_uploaded_file($foto, $rutaDestino);

                 //Compruebo si ese nombre existe, ya que los nombres son unicos
                 $sent = "Select nombre from sala where nombre = '$nombre'";
                 $datos = Sistema::app()->BD()->crearConsulta($sent);

                 // Si da error la sentencia
                 if ($datos->error() != 0) {
                     $resultado = [
                         "datos" => "Error base de datos",
                         "correcto" => false
                     ]; //error,

                     $res = json_encode($resultado, JSON_PRETTY_PRINT);
                     echo $res;
                     exit;
                 }

                 //si la sala no existe creo la sala , lo compruebo por el nombre ya que el nombre de la sala es unico
                 if ($datos->numFilas() === 0) {

                $sent = "INSERT INTO `sala` (
                    `nombre`, 
                    `descripcion`, 
                    `foto`, 
                    `borrado`
                ) VALUES ('$nombre', '$descripcion', '$foto', '$borrado')";

                $datos = Sistema::app()->BD()->crearConsulta($sent);

                // Si da error la sentencia
                if ($datos->error() != 0) {
                    $resultado = [
                        "datos" => "Error base de datos",
                        "correcto" => false
                    ]; //error,

                    $res = json_encode($resultado, JSON_PRETTY_PRINT);
                    echo $res;
                    exit;
                }

                // si existe el id
                $id = $datos->idGenerado();
                $resultado = [
                    "datos" => $id,
                    "correcto" => true
                ];

                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }else{ //devuelvo un error
                $errores['sala'] = 'Esa sala ya existe';
                $resultado = [
                    "datos" => $errores,
                    "correcto" => false
                ]; //error,

                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }
            }

            //sino mando los errores
            $resultado = [
                "datos" => $errores,
                "correcto" => false
            ];
            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }

        //-------------------------------------------
        //                METODO PUT
        //------------------------------------------
        if ($_SERVER["REQUEST_METHOD"] == "PUT") {

            $errores = [];
            $id = '';
            $parametros = apiControlador::recogerParametros(); //recojo los parametros
            if (isset($parametros["id"])) { //se ha pasado id 
                $id = intval($parametros["id"]);

                $sent = "SELECT * from sala where cod_sala = $id";
                $datos = Sistema::app()->BD()->crearConsulta($sent); 

                // Si da error la sentencia
                if ($datos->error() != 0) {
                    $resultado = [
                        "datos" => "Error base de datos",
                        "correcto" => false
                    ]; //error,

                    $res = json_encode($resultado, JSON_PRETTY_PRINT);
                    echo $res;
                    exit;
                }

                if ($datos->numFilas() === 0) {  // 1 tiene filas , 0 no tiene
                    $resultado = [
                        "datos" => "Sala no encontrada",
                        "correcto" => false
                    ]; //error,

                    $res = json_encode($resultado, JSON_PRETTY_PRINT);
                    echo $res;
                    exit;
                }

                $filas = $datos->fila();
                //-----------VALIDACION DE DATOS------------------
                $nombre = '';
                if (isset($parametros['nombre'])) {
                    $nombre = trim($parametros['nombre']);
                    $nombre = CGeneral::addslashes($nombre);

                    if ($nombre === '') {
                        $errores['nombre'] = 'No puede estar en blanco';
                    }

                    if (mb_strlen($nombre) > 30) {
                        $errores['nombre'] = 'Longitud máxima de 30 caracteres';
                    }
                }

                $descripcion = '';
                if (isset($parametros['descripcion'])) {
                    $descripcion = trim($parametros['descripcion']);
                    $descripcion = CGeneral::addSlashes($descripcion);

                    if (mb_strlen($descripcion) > 30) {
                        $errores['descripcion'] = 'Longitud máxima de 30 caracteres';
                    }
                }

                $foto = '';
                if (isset($parametros['foto'])) {
                    $foto = trim($parametros['foto']);
                    $foto = CGeneral::addSlashes($foto);
                    $expr = '/^[a-zA-Z0-9_-]+\.(png)$/';

                    if ($foto === '') {
                        $foto = $filas['foto']; //si no introduce imagen le dejo la que tenia
                    }
                    if (!preg_match($expr, $foto)) {
                        $errores['foto'] = 'El formato debe ser nombre.png';
                    }
                }

                $borrado = '';
                if (isset($parametros['borrado'])) {
                    $borrado = intval($parametros['borrado']);

                    if (!in_array($borrado, [0, 1])) {
                        $errores['borrado'] = 'Seleccione opcion correcta';
                    }
                }

                if (!$errores) {

                    //muevo la foto al servidor
					$rutaDestino = RUTA_BASE . '/imagenes/salas/' . $foto;
					move_uploaded_file($foto, $rutaDestino);

                    //Compruebo si ese nombre existe, ya que los nombres son unicos
                    $sent = "Select nombre from sala where nombre = '$nombre'";
                    $datos = Sistema::app()->BD()->crearConsulta($sent);

                    // Si da error la sentencia
                    if ($datos->error() != 0) {
                        $resultado = [
                            "datos" => "Error base de datos",
                            "correcto" => false
                        ]; //error,

                        $res = json_encode($resultado, JSON_PRETTY_PRINT);
                        echo $res;
                        exit;
                    }

                    if ($datos->numFilas() === 0) {  // 1 tiene filas , 0 no tiene

                        // Nombre único, realizar la actualización en la base de datos
                        $sent = "UPDATE sala SET  
                                nombre = '$nombre',
                                descripcion='$descripcion',
                                foto='$descripcion',
                                borrado='$borrado'
                                WHERE cod_sala = $id";

                        $datos = Sistema::app()->BD()->crearConsulta($sent);
                        // Si da error la sentencia
                        if ($datos->error() != 0) {
                            $resultado = [
                                "datos" => "Error base de datos",
                                "correcto" => false
                            ]; //error,

                            $res = json_encode($resultado, JSON_PRETTY_PRINT);
                            echo $res;
                            exit;
                        }

                        //una vez realizada la actualizacion devolvemos el id
                        $resultado = [
                            "datos" => $id,
                            "correcto" => true
                        ];

                        $res = json_encode($resultado, JSON_PRETTY_PRINT);
                        echo $res;
                        exit;
                    } else { //actualizamos todo menos el nombre

                        // Nombre único, realizar la actualización en la base de datos
                        $sent = "UPDATE sala SET  
                                    descripcion='$descripcion',
                                    foto='$foto',
                                    borrado='$borrado'
                                    WHERE cod_sala = $id";

                        $datos = Sistema::app()->BD()->crearConsulta($sent);
                        // Si da error la sentencia
                        if ($datos->error() != 0) {
                            $resultado = [
                                "datos" => "Error base de datos",
                                "correcto" => false
                            ]; //error,

                            $res = json_encode($resultado, JSON_PRETTY_PRINT);
                            echo $res;
                            exit;
                        }

                        //una vez realizada la actualizacion devolvemos el id
                        $resultado = [
                            "datos" => $id,
                            "correcto" => true
                        ];

                        $res = json_encode($resultado, JSON_PRETTY_PRINT);
                        echo $res;
                        exit;
                    }
                }

                //sino mando los errores
                $resultado = [
                    "datos" => $errores,
                    "correcto" => false
                ];
                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }else{
                $resultado = [
                    "datos" => "Sala no encontrada",
                    "correcto" => false
                ]; //error,

                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }
        }

        //-------------------------------------------
        //                METODO DELETE
        //-------------------------------------------
        if ($_SERVER["REQUEST_METHOD"] == "DELETE") {


            $id = '';
            $parametros = apiControlador::recogerParametros(); //recojo los datos
            if (isset($parametros["id"])) {
                $id = intval($parametros["id"]);
            }

            $sent = "SELECT * from sala where cod_sala = $id";
            $datos = Sistema::app()->BD()->crearConsulta($sent);

            // Si da error la sentencia
            if ($datos->error() != 0) {
                $resultado = [
                    "datos" => "Error base de datos",
                    "correcto" => false
                ]; //error,

                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }


            if ($datos->numFilas() === 0) {  // 1 tiene filas , 0 no tiene
                $resultado = [
                    "datos" => "Sala no encontrada",
                    "correcto" => false
                ]; //error,

                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }

            $borrado = "UPDATE sala SET
                                    borrado = '1'
                                    WHERE cod_sala = $id";


            $datos = Sistema::app()->BD()->crearConsulta($borrado);
            // Si da error la sentencia
            if ($datos->error() != 0) {
                $resultado = [
                    "datos" => "Error base de datos",
                    "correcto" => false
                ]; //error,

                $res = json_encode($resultado, JSON_PRETTY_PRINT);
                echo $res;
                exit;
            }

            //una vez realizado el borrado devolvemos el id
            $resultado = [
                "datos" => $id,
                "correcto" => true
            ];

            $res = json_encode($resultado, JSON_PRETTY_PRINT);
            echo $res;
            exit;
        }
    }

    /**
     * Funcion que recoge los datos que llegan de una solicitud
     * del flujo de entrada y devuelve un array
     *
     * @return array
     */
    static function recogerParametros() : array
    {
        // Abro el flujo de entrada para obtener los parámetros desde la solicitud
        $ficEntrada = fopen("php://input", "r");
        $datos = "";
    
        // Leo los datos del flujo de entrada en bloques de 1024 bytes
        while ($leido = fread($ficEntrada, 1024)) {
            $datos .= $leido;
        }
    
        // Cierro el flujo de entrada
        fclose($ficEntrada);
    
        // Inicializo un array para almacenar los parámetros
        $par = [];
    
        // Divido los datos en partes utilizando el carácter "&"
        $partes = explode("&", $datos);
    
        // Recorro cada parte y la divido en clave y valor utilizando el carácter "="
        foreach ($partes as $parte) {
            $p = explode("=", $parte);
    
            // Si la parte tiene exactamente dos elementos, la considero como parámetro válido
            if (count($p) == 2)
                $par[$p[0]] = $p[1];
        }
    
        // Devuelvo el array con los parámetros
        return $par;
    }
    
}
