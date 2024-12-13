<?php

class Categorias extends CActiveRecord
{
    protected function fijarNombre(): string
    {
        return 'cat';
    }

    protected function fijarTabla(): string
    {
        return 'categorias';
    }
    protected function fijarId(): string
    {
        return 'cod_categoria';
    }

    protected function fijarAtributos(): array
    {
        return array(
            "cod_categoria", "descripcion"
           
        );
    }
    protected function fijarDescripciones(): array
    {
        return array(
            'descripcion' => 'Descripcion '
        );
    }
    protected function fijarRestricciones(): array
    {
        return
            array(
                array(
                    "ATRI" => "cod_categoria,descripcion",
                    "TIPO" => "REQUERIDO"
                ),array(
                    'ATRI' => 'cod_categoria',
                    'TIPO' => 'ENTERO'
                ),
                array(
                    'ATRI' => 'descripcion',
                    'TIPO' => 'CADENA',
                    'TAMANIO' => 30
                )
               
               
            );
    }
    protected function afterCreate(): void
    {
        $this->cod_categoria = 0;
        $this->descripcion=''; 
    
    }

    protected function afterBuscar(): void
    {
        $this->cod_categoria = intval($this->cod_categoria);

    }

    function fijarSentenciaInsert(): string{

        $descripcion = trim($this->descripcion);
        $descripcion = CGeneral::addSlashes($descripcion);

        $sent = "INSERT INTO `categorias`(`descripcion`) VALUES ('$descripcion')";

        return $sent;

    }
 
    function fijarSentenciaUpdate(): string
    {
        $descripcion = trim($this->descripcion);
        $descripcion = CGeneral::addSlashes($descripcion);
        $codCategoria = intval($this->codCategoria);

        $sent = "UPDATE `categorias` SET `descripcion`='$descripcion' WHERE cod_categoria = $codCategoria";
        return $sent;
    }



    public static function dameCategorias(?int $cod_categoria = null) {
        $ob = new Categorias();
        $array = [];
        if (!isset($cod_categoria)) {
             $ob = $ob->buscarTodos();
             foreach($ob as $cat => $val){
                    $array[$val['cod_categoria']] = $val['descripcion'];
             }

             return $array;
        }

        if ($ob->buscarPorId($cod_categoria)) {
            return $ob->buscarTodos(
                [
                    "select"=>"*",
                    "where"=>"cod_categoria = $cod_categoria"
                ]
            )[0];
        }

        return false;
    }
   
}