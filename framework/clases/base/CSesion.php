<?php

class CSesion {

    public function __construct()
    {
        if(!$this->haySesion()){
            $this->crearSesion();
        }
    }

    /**
     * Función que se encarga de crear e iniciar la sesión
     *
     * @return void
     */
    public function crearSesion()
    {
        if (!$this->haySesion())
            session_start();
    }

    /**
     * Función que comprueba si existe una sesión actualmente.
     *
     * @return boolean --> True si existe una sesión actualmente, 
     * false en caso contrario.
     */
    public function haySesion() :bool
    {
        if(isset($_SESSION))
            return true;
        
        return false;
    }

    /**
     * Función que se encarga de destruir la sesión actual.
     *
     * @return void
     */
    public function destruirSesion()
    {
        if ($this->haySesion())
            session_destroy();
    }
}