<?php
    /**
     * Clase ACLBase
     * Clase abstracta que define el comportamiento
     *  de una ACL 
     */
    abstract class CACLBase{
        /**
         * Añade un role a nuesta ACL
         * 
         * @param string $nombre Nombre del role a añadir 
         * @param array $permisos Permisos que tendrá el role. Array con hasta 10 permisos
         * @return bool True si se ha podido crear, false en caso contrario
         */
        abstract function anadirRole(string $nombre, array $permisos=array()):bool;

        /**
         * Función que localiza un role y devuelve el código de ese role o false si no
         * lo encuentra
         *
         * @param string $nombre nombre del role
         * @return integer|false Devuelve el código de role para el nombre indicado o false si no encuentra el role
         */
        abstract function getCodRole(string $nombre): int|false;

        /**
         * Función que comprueba la existencia de un role
         *
         * @param integer $codRole role a buscar
         * @return boolean Devuelve true si lo encuentra o false en caso contrario 
         */
        abstract function existeRole(int $codRole):bool;

        /**
         * Función que devuelve los permisos de un role dado
         *
         * @param integer $codRole Role a buscar
         * @return array|false Devuelve los permisos o false si no encuentra el role
         */
        abstract function getPermisosRole(int $codRole):array|false;

        /**
         * Función que devuelve si un role tiene o no un permiso concreto
         *
         * @param integer $codRole Role a buscar
         * @param integer $numero Número de permiso
         * @return boolean True si encuentra el role y lo tiene. False en cualquier otro caso
         */
        abstract function getPermisoRole(int $codRole, int $numero): bool;

        /**
         * Función que añade un nuevo usuario a nuestra ACL
         *
         * @param string $nombre Nombre del usuario
         * @param string $nick Nick unico para el usuario
         * @param string $contrasena contraseña del usuario
         * @param integer $codRole Role a asignarle
         * @return boolean Devuelve true si puede crearlo. False en caso contrario
         */
        abstract function anadirUsuario(string $nombre, string $nick, string $contrasena, int $codRole):bool;

        /**
         * Obtiene el código de usuario para un nick dado
         *
         * @param string $nick nick del usuario a buscar
         * @return integer|false Devuelve el codigo del usuario o false si no lo encuentra
         */
        abstract function getCodUsuario(string $nick):int|false;

        /**
         * Verifica si existe un usuario dado un código
         *
         * @param integer $codUsuario Código del usuario a verificar
         * @return boolean Devuelve si existe o no el usuario
         */
        abstract function existeCodUsuario(int $codUsuario):bool;

        /**
         * Verifica si existe o no un usuario con el nick dado
         *
         * @param string $nick Nick del usuario a comprobar
         * @return boolean Devuelve true si encuentra el usuario y 
         * false en caso contrario
         */
        abstract function existeUsuario(string $nick):bool;

        /**
         * Función que comprueba que existe un usuario y la contraseña indicada es la correcta
         *
         * @param string $nick Nick del usuario a comprobar
         * @param string $contrasena Contraseña del usuario a comprobar
         * @return boolean Devuelve true si existe el usuario y tiene la contraseña indicada. 
         * False en otro caso
         */
        abstract function esValido(string $nick, string $contrasena):bool;

        /**
         * Función que comprueba si un usuario tiene un permiso concreto
         *
         * @param integer $codUsuario Usuario a buscar
         * @param integer $numero Permiso a buscar
         * @return boolean Devuelve true si existe el usuario y tiene el permiso. 
         * False en otro caso
         */
        abstract function getPermiso(int $codUsuario, int $numero):bool;

        /**
         * Función que devuelve los permisos de un usuario
         *
         * @param integer $codUsuario Usuario a buscar
         * @return array|false Devuelve los permisos del usuario o false si 
         * no existe el usuario
         */
        abstract function getPermisos(int $codUsuario):array|false;

        /**
         * Función que devuelve el nombre de un usuario
         *
         * @param integer $codUsuario Usuario a buscar
         * @return string|false Devuelve el nombre del usuario o false si no existe
         */
        abstract function getNombre(int $codUsuario):string|false;

        /**
         * Devuelve si un usuario está borrado
         *
         * @param integer $codUsuario Usuario a buscar.
         * @return boolean true si el usuario existe y no está borrado.
         * False en otro caso
         */
        abstract function getBorrado(int $codUsuario):bool;

        /**
         * Devuelve el role que tiene un usuario concreto
         *
         * @param integer $codUsuario Usuario a buscar
         * @return integer|false Devuelve el role del usuario o false si no existe.
         */
        abstract function getUsuarioRole(int $codUsuario):int|false;

        /**
         * Función que asigna un nombre a un usuario
         *
         * @param integer $codUsuario Usuario a buscar
         * @param string $nombre Nombre a asignar
         * @return boolean Devuelve true si ha podido asignar el nombre, false en otro caso
         */
        abstract function setNombre(int $codUsuario,string $nombre):bool;

        /**
         * Función que asigna una contraseña a un usuario
         *
         * @param integer $codUsuario usuario a buscar
         * @param string $contrasenia contraseña a asignar
         * @return boolean Devuelve true si ha podido asignar la contraseña
         * False en otro caso
         */
        abstract function setContrasenia(int $codUsuario, string $contrasenia):bool;

        /**
         * Función que borra/desborra lógicamente un usuario 
         *
         * @param integer $codUsuario Usuario a buscar
         * @param boolean $borrado Estado a asignar
         * @return boolean Devuelve true si ha podido asignar el estado. 
         * False en otro caso
         */
        abstract function setBorrado(int $codUsuario, bool $borrado):bool;

        /**
         * Función que cambia el role de un usuario
         *
         * @param integer $codUsuario Usuario a buscar
         * @param integer $role Role a asignar
         * @return boolean Devuelve true si ha podido asignar el role al usuario.
         * False si no existe el usuario, role o no ha podido asignarlo
         */
        abstract function setUsuarioRole(int $codUsuario, int $role):bool;

        /**
         * Devuelve un array con todos los usuarios existentes. 
         * La clave es el codigo de usuario, el valor es el nick del usuario 
         *
         * @return array Array con todos los usuarios existentes
         */
        abstract function dameUsuarios():array;

        /**
         * Devuelve un array con todos los roles existentes. 
         * La clave es el codigo de role, el valor es el nombre del role 
         *
         * @return array Array con todos los roles existentes
         */
        abstract function dameRoles():array;
    }