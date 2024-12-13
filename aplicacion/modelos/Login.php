<?php
class Login extends CActiveRecord
{
    protected function fijarNombre(): string
    {
        return 'log';
    }
    protected function fijarAtributos(): array
    {
        return array(
            "nick","contrasenia",
        );
    }
    protected function fijarDescripciones(): array
    {
        return array(
            "nick" => "Nick ",
            'contrasenia' => 'Contraseña ',

        );

    }
    protected function fijarRestricciones(): array
    {
        return
            array(
                array(
                    "ATRI" => "nick,contrasenia",
                    "TIPO" => "REQUERIDO",

                ),
                array(
                    "ATRI" => 'nick',
                    'TIPO' => 'CADENA',
                    'TAMANIO' => 20,
                    'MENSAJE' => 'Debes introducir una cadena de longitud maxima 20',
                ),
               array(
                    "ATRI" => "contrasenia", 
                    "TIPO" => "CADENA",
                    "TAMANIO" => 20,
                )
                ,
                array(
                     "ATRI" => "contrasenia", 
                     "TIPO" => "FUNCION",
                     "FUNCION" => 'autenticar',
                 )


            );
    }
    protected function afterCreate(): void
    {
        $this->nick = '';
        $this->contrasenia = '';
    }

  
    public function autenticar(){

        if($this->nick != '' && $this->contrasenia != ''){
            if(!Sistema::app()->ACL()->esValido($this->nick,$this->contrasenia)){
                $this->setError('contrasenia','Contraseña incorrecta');
            }
        }


    }
}
