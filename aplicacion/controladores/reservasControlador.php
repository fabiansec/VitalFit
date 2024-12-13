<?php
	 
class reservasControlador extends CControlador
{

	function __construct()
	{
		
	}

	public function accionIndex()
	{
		if(!Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->irAPagina(array('registro','login'), []);
			exit;
		}
		
		if(!(Sistema::app()->Acceso()->puedePermiso(9))){
			Sistema::app()->paginaError(404,'No tienes permisos');
			exit;
		}

		$this->barraUbi = [
			[
				"texto" => "Inicio", 
				"enlace" => ["inicial"]
			], 
		];
		$_SESSION['accion'] = ["reservas"];
		$reservas = new Reservas();
		$nick = Sistema::app()->Acceso()->getNick();
		$cod =Sistema::app()->ACL()->getCodUsuario($nick);
		$fecha = CGeneral::fechaNormalAMysql(date('d/m/Y'));
		$errores = [];
		if(!isset($_SESSION[$nick])){
			$_SESSION[$nick] = [];
		}
       //Recogida de datos para la insercion
	   if (isset($_POST['rev'])) {
		if (isset($_SESSION[$nick]['actividades'])) {
			if(isset($_POST['id_0'])){
				foreach ($_SESSION[$nick]['actividades'] as $ind => $valor) {
					Sistema::app()->sesion =$_SESSION[$nick]['actividades'];
					$reservas->setValores($_POST['rev']);
					$reservas->fecha = CGeneral::addSlashes(date('Y-m-d'));  // Asegurar formato de fecha
					$reservas->cod_usuario = intval($cod);
					$reservas->cod_actividad = intval($valor['id_actividad']);
		
					if ($reservas->validar()) {
						$susActividad = $reservas->buscarTodos([
							"where" => "cod_actividad = " . intval($valor['id_actividad']) . " AND hora = '" . CGeneral::addSlashes($valor['hora']) . "' AND fecha = '" . $fecha . "' AND cod_usuario = " . intval($cod)

						]);
		
						if (count($susActividad) === 0) {
							$reservas->guardar();
							// Actualizar el aforo de las actividades reservadas
							$act = new Actividades;
							$act->buscarPorId(intval($valor['id_actividad']));
							$act->aforo = $act->aforo +1;
							$act->guardar();

						}else{
							$errores['Actividad'] = 'Ya tienes una actividad en esa hora o ya tienes esa actividad reservada';
						}
					}
				}
			}
			$_SESSION[$nick]['actividades'] = [];
		}
	
		

	}
	

		//muestro las reservas del dia actual
		$filas = $reservas->buscarTodos(['where' => 'cod_usuario = ' . $cod . ' AND fecha = ' . "'$fecha'"]);
		$metodos = [1=> 'MasterCard',2=>'Visa',3=>'Paypal'];		
		$this->dibujaVista(
			"index",
			['filas' => $filas, 'modelo' => $reservas,'datos' => $_SESSION[$nick],'metodos' => $metodos, 'errores' => $errores],
			"Pagina principal"
		);		

	}

}