<?php

class registroControlador extends CControlador
{


	public function accionLogin(){
		$this->barraUbi = [
			[
				"texto" => "Inicio",
				"enlace" => ["inicial"]
			], [
				"texto" => "Login",
				"enlace" => ["registro", 'login']
			],
		];

		$registro = new Login();
		$nombre = $registro->getNombre();

		if(isset($_POST['id_1'])){
			if(isset($_SESSION['accion'])){
				Sistema::app()->irAPagina($_SESSION['accion'],[]);
				exit;

			}else{
				Sistema::app()->irAPagina(array('inicial'),[]);
				exit;

			}
		}

		if(isset($_POST['id_0'])){
			if (isset($_POST[$nombre])) {
				$registro->setValores($_POST[$nombre]);
	
				if($registro->validar()){

					$nick = CGeneral::addSlashes($_POST['log']['nick']);
					$cod = Sistema::app()->ACL()->getCodUsuario($nick);
					$nombre = Sistema::app()->ACL()->getNombre($cod);
					$permisos = Sistema::app()->ACL()->getPermisos($cod);
					$borrado = Sistema::app()->ACL()->getBorrado($cod);
					$acceso =  Sistema::app()->Acceso();

					if($borrado === true){
						Sistema::app()->paginaError(404,'El usuario esta borrado');
						exit;
					}

					$aceptado = $acceso->registrarUsuario($nick,$nombre,$permisos);

					if($aceptado === true){

					
					
							Sistema::app()->irAPagina(array('inicial'),[]);
							exit;

						

					}else{
						Sistema::app()->paginaError(404,'No se ha podido registrar el usuario');
						exit;

					}

				}
				
				
			}
		}
		
		$this->dibujaVista(
			"login",
			['modelo' => $registro],
			"Login"
		);


	}

	public function accionCerrar(){

		if(Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->Acceso()->quitarRegistroUsuario();
			Sistema::app()->irAPagina(array('registro','login'));
		}
	}
}
