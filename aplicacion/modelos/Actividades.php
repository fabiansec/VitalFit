<?php
class Actividades extends CActiveRecord
{
    protected function fijarNombre(): string
    {
        return 'act';
    }

    protected function fijarTabla(): string
    {
        return 'cons_actividades';
    }
    protected function fijarId(): string
    {
        return 'cod_actividad';
    }

    protected function fijarAtributos(): array
    {
        return array(
            "cod_actividad", "nombre",
            "descripcion", "hora",
            "aforo", "borrado", "categoria", "sala", "foto", "cod_sala", "cod_categoria"
        );
    }
    protected function fijarDescripciones(): array
    {
        return array(
            "nombre" => "Nombre ",
            'descripcion' => 'Descripcion ',
            'aforo' => 'Aforo',
            'borrado' => 'Borrado ',
            'cod_sala' => 'Sala ',
            'cod_categoria' => 'Categoria ',
            'foto' => 'Foto ',
            'categoria' => 'Categoria ',
            'sala' => 'Sala ',

        );
    }
    protected function fijarRestricciones(): array
    {
        return
            array(
                array(
                    "ATRI" => "nombre,cod_categoria,cod_sala,hora,aforo",
                    "TIPO" => "REQUERIDO"
                ),
                array(
                    'ATRI' => 'nombre',
                    'TIPO' => 'CADENA',
                    'TAMANIO' => 30
                ),
                array(
                    'ATRI' => 'cod_categoria',
                    'TIPO' => 'RANGO',
                    "RANGO" => array_keys(Categorias::dameCategorias()),

                ),
                array(
                    'ATRI' => 'cod_sala',
                    'TIPO' => 'RANGO',
                    "RANGO" => array_keys(Actividades::dameSalas()),

                ),
                array(
                    'ATRI' => 'descripcion',
                    'TIPO' => 'CADENA',
                    'TAMANIO' => 30,
                    'DEFECTO' => 'Animo, es hora de entrenar'
                ),
                array(
                    'ATRI' => 'hora',
                    'TIPO' => 'HORA',
                ),
                array(
                    'ATRI' => 'aforo',
                    'TIPO' => 'ENTERO',
                    'DEFECTO' => 10,
                    'MIN' => 0,
                    'MAX' => 30,
                    "MENSAJE" => 'Maximo 30 personas'
                ),
                array(
                    'ATRI' => 'foto',
                    'TIPO' => 'CADENA',
                    'TAMANIO' => 30,
                    'DEFECTO' => 'defecto.png'
                ),
                array(
                    'ATRI' => 'foto',
                    'TIPO' => 'FUNCION',
                    'TAMANIO' => 30,
                    'DEFECTO' => 'defecto.png',
                    'FUNCION' => 'comprobacion'
                ),
                array(
                    'ATRI' => 'borrado',
                    'TIPO' => 'RANGO',
                    "RANGO" => [0, 1],
                    "DEFECTO" => 0
                ),
            );
    }
    protected function afterCreate(): void
    {

        $this->descripcion = '';
        $this->foto = 'defecto.png';
        $this->precio_base = 0;
        $this->borrado = 0;
        $this->cod_actividad = 0;
        $this->nombre = '';
        $this->hora = '';
        $this->aforo = 10;
        $this->sala = '';
        $this->categoria  = '';
        $this->cod_categoria = 0;
        $this->cod_sala = 0;

    }

    protected function afterBuscar(): void
    {


        $this->cod_actividad = intval($this->cod_actividad);
        $this->cod_categoria = intval($this->cod_categoria);
        $this->cod_sala = intval($this->cod_sala);
        $this->aforo = intval($this->aforo);
        $this->borrado = intval($this->borrado);
    }


    function fijarSentenciaInsert(): string
    {


        $nombre = CGeneral::addSlashes($this->nombre);
        $descripcion = CGeneral::addSlashes($this->descripcion);
        $hora = CGeneral::addSlashes($this->hora);
        $aforo = intval($this->aforo);
        $borrado = trim($this->borrado);
        if ($borrado === 'Si') {
            $borrado = mb_strtolower($borrado);
            $this->borrado = 1;
            $borrado = intval($this->borrado);
        } else if ($borrado === 'No') {
            $borrado = mb_strtolower($borrado);
            $this->borrado = 0;
            $borrado = intval($this->borrado);
        }
        $codSala = intval($this->cod_sala);
        $codCategoria = intval(Categorias::dameCategorias($this->cod_categoria));
        $foto = CGeneral::addSlashes($this->foto);


        $sentencia = "INSERT INTO `actividades`(`nombre`, `descripcion`, 
        `hora`, `aforo`, `borrado`, 
        `cod_sala`, `cod_categoria`,`foto`) 
        VALUES ('$nombre','$descripcion','$hora','$aforo','$borrado',
        '$codSala','$codCategoria','$foto')";



        return  $sentencia;
    }

    function fijarSentenciaUpdate(): string
    {

        $nombre = CGeneral::addSlashes($this->nombre);
        $descripcion = CGeneral::addSlashes($this->descripcion);
        $hora = CGeneral::addSlashes($this->hora);
        $aforo = intval($this->aforo);
        $borrado = trim($this->borrado);
        if ($borrado === 'Si') {
            $borrado = mb_strtolower($borrado);
            $this->borrado = 1;
            $borrado = intval($this->borrado);
        } else if ($borrado === 'No') {
            $borrado = mb_strtolower($borrado);
            $this->borrado = 0;
            $borrado = intval($this->borrado);
        }
        $codSala = intval(Actividades::dameSalas($this->cod_sala));
        $codCategoria = intval(Categorias::dameCategorias($this->cod_categoria));
        $foto = CGeneral::addSlashes($this->foto);

        $sentencia  = "UPDATE `actividades` SET `cod_categoria`='$codCategoria',
        `nombre`='$nombre',
        `descripcion`='$descripcion',
        `hora`='$hora',
        `aforo`='$aforo',
        `borrado`='$borrado',
        `cod_sala`='$codSala',
        `foto`='$foto'
        where cod_actividad = {$this->cod_actividad}";
        return $sentencia;
    }

    function dameSalas(?int $cod_sala = null)
    {
        $enlaceCurl = curl_init();
        //se indican las opciones para una petición HTTP GET
        curl_setopt($enlaceCurl, CURLOPT_URL, "http://www.2daw09.sitio2daw.es/api/salas/");
        curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
        curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
        curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
        //ejecuto la petición
        $res = curl_exec($enlaceCurl);
        //cierro la sesión
        curl_close($enlaceCurl);
        $res = json_decode($res, true);
        $salas = [];

        if($res['correcto'] === true){

            foreach ($res['datos'] as $sala) {
             
                    $salas[$sala['cod_sala']] = $sala['nombre'];
                
            }
            return $salas;
        }
        return false;
    }

    function comprobacion(){

        $extension = pathinfo($this->foto, PATHINFO_EXTENSION);

        if(strtolower($extension) != 'png'){
            $this->setError('foto', 'Debe ser tipo png');
        }
    }
}
