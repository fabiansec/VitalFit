<?php
class Reservas extends CActiveRecord
{
    protected function fijarNombre(): string
    {
        return 'rev';
    }
   
    protected function fijarTabla(): string
    {
        return 'cons_reservas';
    }
    protected function fijarId(): string
    {
        return 'cod_reserva';
    }

    protected function fijarAtributos(): array
    {
        return array(
            "cod_reserva", "cod_usuario","nombre_usuario","nombre_actividad",
            "cod_actividad", "metodo", "fecha" , "hora"
        );
    }
    protected function fijarDescripciones(): array
    {
        return array(
            'metodo' => 'Metodo ',
            'nombre_usuario' => 'Usuario ',
            'nombre_actividad' => 'Actividad ',
            'hora' => 'Hora '

        );

    }
    protected function fijarRestricciones(): array
    {
        return
            array(
                array(
                    "ATRI" => "cod_usuario,cod_actividad",
                    "TIPO" => "REQUERIDO",

                ),
                array(
                    "ATRI" => 'metodo',
                    'TIPO' => 'RANGO',
                    'RANGO' => array_keys([1 => 'MasterCard',2=>'Visa',3=>'PayPal']),
                ),
              


            );
    }
    protected function afterCreate(): void
    {
        $this->metodo = '';
        $this->nombre_usuario = '';
        $this->nombre_actividad = '';
        $this->hora = '';
        $this->cod_usuario = 0;
        $this->cod_reserva = 0;
        $this->fecha = date('d/m/Y'); 
        $this->metodo = '';
 

    }

    protected function afterBuscar(): void
    {
        $this->cod_reserva = intval($this->cod_reserva);
        $this->cod_actividad = intval($this->cod_actividad);
        $this->cod_usuario = intval($this->cod_usuario);
        $fecha = $this->fecha;
        $fecha = CGeneral::fechaMysqlANormal($fecha);
        $this->fecha = $fecha; 
    }

    function fijarSentenciaInsert(): string
    {


        $cod_usuario = intval($this->cod_usuario);
        $cod_actividad = intval($this->cod_actividad);
        $fecha = CGeneral::fechaNormalAMysql($this->fecha);   
        $fecha = CGeneral::addSlashes($fecha);
        $metodo = intval($this->metodo);
       


        $sentencia = "INSERT INTO `reservas`(`cod_usuario`, `cod_actividad`, 
        `fecha`, `metodo`) 
        VALUES ('$cod_usuario','$cod_actividad','$fecha','$metodo')";



        return  $sentencia;
    }

    function fijarSentenciaUpdate(): string
    {

       
        $fecha = CGeneral::addSlashes($this->fecha);
        $fecha = CGeneral::fechaNormalAMysql($fecha);
        $metodo = intval($this->metodo);
        $cod = intval($this->cod_reserva);
        

        $sentencia  = "UPDATE `reservas` SET 
        `fecha`='$fecha',
        `metodo`='$metodo',
        where cod_reserva = $cod";
        return $sentencia;
    }






}
