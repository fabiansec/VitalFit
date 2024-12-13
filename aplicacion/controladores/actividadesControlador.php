<?php
	 
class actividadesControlador extends CControlador
{

	function __construct()
	{
		
	}

	public function accionIndex()
	{

		//Compruebo si el usuario tiene permisos
		if(!Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->irAPagina(array('registro','login'), []);
			exit;
		}

		if(!(Sistema::app()->Acceso()->puedePermiso(9))){
			Sistema::app()->paginaError(404,'No tienes permisos');
			exit;
		}

		$errores = [];
		/* Si se ha apuntado a una actividad guardo en una sesion todas 
		   sus actividades*/
		if (isset($_POST['apuntarse'])) {
			$nick = $_SESSION['acceso']['nick'];
			$actividad = intval($_POST['actividad']);
			$nom = trim($_POST['nom']);
			$afo = trim($_POST['afo']);
			$hor = trim($_POST['hor']);
		
			// Verificar si el usuario ya tiene actividades registradas
			if (isset($_SESSION[$nick]['actividades'])) {
				// Verificar si la actividad ya está en la lista
				$actividades_usuario = $_SESSION[$nick]['actividades'];
		
				$actividades_ids = array_column($actividades_usuario, 'id_actividad'); // Obtener los IDs de actividades
				$horas_actividades = array_column($actividades_usuario, 'hora'); // Obtener las horas de actividades
		
				if (!in_array($actividad, $actividades_ids) && !in_array($hor, $horas_actividades)) {
					// La actividad no está en la lista y la hora no está duplicada, agregarla
					$_SESSION[$nick]['actividades'][] = [
						'id_actividad' => $actividad,
						'nombre' => $nom,
						'aforo' => $afo,
						'hora' => $hor
					];

				}else{
					$errores['Actividad'] = 'Error, compruebe sus reservas';
				}
			} else {
				Sistema::app()->sesion =$_SESSION[$nick]['actividades'];
				// El usuario no tiene actividades registradas, inicializar la lista
				$_SESSION[$nick]['actividades'][] = [
					'id_actividad' => $actividad,
					'nombre' => $nom,
					'aforo' => $afo,
					'hora' => $hor
				];
			}
		}
		
		
		

		$_SESSION['accion'] = ["actividades"];
		$this->barraUbi = [
			[
				"texto" => "Inicio", 
				"enlace" => ["inicial"]
			], 
            [
				"texto" => "Actividades", 
				"enlace" => ["actividades"]
			], 
		];

		$actividades = new Actividades();
		$act = $actividades->buscarTodos();

		$opciones=[];
		$filtrado=[
			"nombre"=>"",
			"aforo" => "",
            "categoria" => ""
		];

		// Llamamos al boton de filtrar
	
			$where="";

			// Validamos el nombre de Actividad
			if (isset($_REQUEST["nombre"])) {
				$nombre=$_REQUEST["nombre"];
				$nombre=trim($nombre);
				if (!empty($nombre)) 
				{
					$filtrado["nombre"]=$nombre;
					$nombre=CGeneral::addSlashes($nombre);
					if ($where!="")
					    $where.=" and ";
					$where = " nombre regexp '$nombre'";
				}
			}

			// Validamos el borrado
			if (isset($_REQUEST["aforo"])) {
				$aforo=intval($_REQUEST["aforo"]);
				if (!empty($aforo)) {
					$filtrado["aforo"]=$aforo; 
					if ($where!="")
							$where.=" and ";
						$where .= " aforo <= $aforo ";			 
				}

			}
            // Validamos el nombre de Categoria
			if (isset($_REQUEST["categoria"])) {
				$cod_categoria=intval($_REQUEST["categoria"]);
				$cat=new Categorias();
				if ($cat->buscarPorId($cod_categoria))
				    {
						$filtrado["categoria"]=$cod_categoria;
						if ($where!="")
						    $where.=" and ";
					 
						$where.=" cod_categoria =$cod_categoria";
						
					}
			}
			$opciones["where"]=$where;

		
			/* El paginador , calculamos el numero total de registros y el numero de paginas*/
            $tamPagina = 3;

            if (isset($_GET["reg_pag"]))
                $tamPagina = intval($_GET["reg_pag"]);
    
            $registros = intval($actividades->buscarTodosNRegistros($opciones));
            $numPaginas = ceil($registros / $tamPagina);
            $pag = 1;
    
            if (isset($_GET["pag"])) {
                $pag = intval($_GET["pag"]);
            }
    
            if ($pag > $numPaginas)
                $pag = $numPaginas;
    

		
            $inicio = $tamPagina * ($pag - 1);
            $opciones["limit"]="$inicio,$tamPagina";
            
            if($opciones === 0){
                $filas = $actividades->buscarTodos();
            }else{
                $filas = $actividades->buscarTodos($opciones);
                if ($filas===false) {
                    Sistema::app()->paginaError(400, "No hay actividades");
                    return;
                }
            }


            $opcPaginador = array(
                "URL" => Sistema::app()->generaURL(array("actividades", "index"), $filtrado),
                "TOTAL_REGISTROS" => $registros,
                "PAGINA_ACTUAL" => $pag,
                "REGISTROS_PAGINA" => $tamPagina,
                "TAMANIOS_PAGINA" => array(
                    3 => "3",
                    6 => "6",
                    9 => "9",
                    12 => "12",
                    15 => "15",
                    18 => "18"
                ),
                "MOSTRAR_TAMANIOS" => true,
                "PAGINAS_MOSTRADAS" => 4,
            );

            
			
			foreach($filas as $clave => $fila){
				if(date('H:i:s') > $fila['hora']){
						$fila['deshabilitar'] = true;

				}else{
					$fila['deshabilitar'] = false;
				}
				$filas[$clave] = $fila;
			}
			
			

            $categorias = Categorias::dameCategorias();
            $this->dibujaVista(
                "index",
                ['actividades' => $actividades, 'opcpag' => $opcPaginador, 'filtrado' => $filtrado, 'filas' => $filas, 'categorias' => $categorias, 'errores' =>$errores],
                "Actividades"
            );


                

	}


	public function accionIndextabla(){


		//Comprobar permisos del usuario
		if(!Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->irAPagina(array('registro','login'), []);
			exit;
		}

		if(!(Sistema::app()->Acceso()->puedePermiso(10))){
			Sistema::app()->paginaError(404,'No tienes permisos');
			exit;
		}

		$_SESSION['accion'] = ["actividades","indexTabla"];
		$this->barraUbi = [
			[
				"texto" => "Inicio", 
				"enlace" => ["inicial"]
			], 
            [
				"texto" => "CRUD Actividades", 
				"enlace" => ["actividades", "indextabla"]
			], 
		];

		$actividades = new Actividades();
		$act = $actividades->buscarTodos();

		$opciones=[];
		$filtrado=[
			"nombre"=>"",
			"aforo" => "",
            "categoria" => "",
			"orden" => ""
		];

		// Llamamos al boton de filtrar
	
			$where="";
			$orden = "";
			// Validamos el nombre de Actividad
			if (isset($_REQUEST["nombre"])) {
				$nombre=$_REQUEST["nombre"];
				$nombre=trim($nombre);
				if (!empty($nombre)) 
				{
					$filtrado["nombre"]=$nombre;
					$nombre=CGeneral::addSlashes($nombre);
					if ($where!="")
					    $where.=" and ";
					$where = " nombre regexp '$nombre'";
				}
			}

			// Validamos el borrado
			if (isset($_REQUEST["aforo"])) {
				$aforo=intval($_REQUEST["aforo"]);
				if (!empty($aforo)) {
					$filtrado["aforo"]=$aforo; 
					if ($where!="")
							$where.=" and ";
						$where .= " aforo <= $aforo ";			 
				}

			}
            // Validamos el nombre de Categoria
			if (isset($_REQUEST["categoria"])) {
				$cod_categoria=intval($_REQUEST["categoria"]);
				$cat=new Categorias();
				if ($cat->buscarPorId($cod_categoria))
				    {
						$filtrado["categoria"]=$cod_categoria;
						if ($where!="")
						    $where.=" and ";
					 
						$where.=" cod_categoria =$cod_categoria";
						
					}
			}

			 // Validamos el nombre de Categoria
			 if (isset($_REQUEST["orden"])) {
				if($_REQUEST['orden'] === '1'){
					$filtrado["orden"]= true; 
						$orden = " nombre";	
						$orden = CGeneral::addSlashes($orden);
				}
				
			}


			$opciones["where"]=$where;
			$opciones["order"] = $orden;

		
			
           	
            $tamPagina = 3;

            if (isset($_GET["reg_pag"]))
                $tamPagina = intval($_GET["reg_pag"]);
    
            $registros = intval($actividades->buscarTodosNRegistros($opciones));
            $numPaginas = ceil($registros / $tamPagina);
            $pag = 1;
    
            if (isset($_GET["pag"])) {
                $pag = intval($_GET["pag"]);
            }
    
            if ($pag > $numPaginas)
                $pag = $numPaginas;
    
            $inicio = $tamPagina * ($pag - 1);
            $opciones["limit"]="$inicio,$tamPagina";
            
            
            if($opciones === 0){
                $filas = $actividades->buscarTodos();
            }else{
                $filas = $actividades->buscarTodos($opciones);
                if ($filas===false) {
                    Sistema::app()->paginaError(400, "No hay actividades");
                    return;
                }
            }
            
            
           

			
		foreach ($filas as $clave => $fila) {
			$fila['oper'] =
				CHTML::link(
					CHTML::imagen('/imagenes/24x24/ver.png'),
					Sistema::app()->generaURL(
						['actividades', 'ver'],
						[
							'id' => $fila['cod_actividad']
						]
					)
				) .
				CHTML::link(
					CHTML::imagen('/imagenes/24x24/modificar.png'),
					Sistema::app()->generaURL(
						['actividades', 'modificar'],
						[
							'id' => $fila['cod_actividad']
						]
					)
				) .
				CHTML::link(
					CHTML::imagen('/imagenes/24x24/borrar.png'),
					Sistema::app()->generaURL(
						['actividades', 'borrar'],
						[
							'id' => $fila['cod_actividad']
						]
					)
				);

			$fila['foto'] = CHTML::imagen('../../imagenes/actividades/' . $fila['foto'], '',['class' => 'imgTabla']);

			if ($fila['borrado'] === '0') {
				$fila['borrado'] = 'No';
			} else {
				$fila['borrado'] = 'Si';
			}

			$filas[$clave] = $fila;
		}


			$cabecera = [
				[
					'ETIQUETA' => 'NOMBRE',
					'CAMPO' => 'nombre',
					'ALINEA' => 'cen'
				],
				[
					'ETIQUETA' => 'DESCRIPCION',
					'CAMPO' => 'descripcion',
					'ALINEA' => 'izq'
				],
				[
					'ETIQUETA' => 'HORA',
					'CAMPO' => 'hora',
					'ALINEA' => 'cen'
				],
				[
					'ETIQUETA' => 'AFORO',
					'CAMPO' => 'aforo',
					'ALINEA' => 'cen'
				],
				
				[
					'ETIQUETA' => 'SALA',
					'CAMPO' => 'sala',
					'ALINEA' => 'cen'
				],
				[
					'ETIQUETA' => 'CATEGORIA',
					'CAMPO' => 'categoria',
					'ALINEA' => 'cen'
				],
				[
					'ETIQUETA' => 'FOTO',
					'CAMPO' => 'foto',
					'ALINEA' => 'cen'
				],
				[
					'ETIQUETA' => 'BORRADO',
					'CAMPO' => 'borrado',
					'ALINEA' => 'cen'
				],
				[
					'ETIQUETA' => 'OPERACIONES',
					'CAMPO' => 'oper',
					'ALINEA' => 'cen'
				],
			];

            $opcPaginador = array(
                "URL" => Sistema::app()->generaURL(array("actividades", "indexTabla"), $filtrado),
                "TOTAL_REGISTROS" => $registros,
                "PAGINA_ACTUAL" => $pag,
                "REGISTROS_PAGINA" => $tamPagina,
                "TAMANIOS_PAGINA" => array(
                    3 => "3",
                    6 => "6",
                    9 => "9",
                    12 => "12",
                    15 => "15",
                    18 => "18"
                ),
                "MOSTRAR_TAMANIOS" => true,
                "PAGINAS_MOSTRADAS" => 4,
            );

         $categorias = Categorias::dameCategorias();
		$this->dibujaVista(
			"indexTabla",
			['actividades' => $act,'opcpag' => $opcPaginador, 'cab' => $cabecera, 'filas' => $filas, 'filtrado' => $filtrado,'categorias' => $categorias],
			"Crud Actividades"
		);


	}

	public function accionVer(){


		//Comprobar permisos del usuario
		if(!Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->irAPagina(array('registro','login'), []);
			exit;
		}

		
		if(!(Sistema::app()->Acceso()->puedePermiso(10))){
			Sistema::app()->paginaError(404,'No tienes permisos');
			exit;
		}

		//Obtengo el id de la actividad
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$_SESSION['accion'] = ["actividades","ver/id=$id"];

			

			$this->barraUbi = [
				[
					"texto" => "Inicio",
					"enlace" => ["actividades", "indexTabla"]
				], [
					"texto" => "Ver actividad",
					"enlace" => ["actividades", "ver/id=$id"]
				],
			];

		

			$actividades = new Actividades();
			$act = $actividades->buscarPorId($id);
			if ($act === false) {
				Sistema::app()->paginaError(404, 'Actividad no encontrada');
				exit;
			} else {
				$cab = ["nombre", "descripcion", "hora","aforo","foto", "borrado", "sala", "categoria"];

				$this->dibujaVista(
					"verActividad",
					['modelo' => $actividades, 'cab' => $cab],
					"Ver Actividad"
				);
			}
		} else {
			Sistema::app()->paginaError(404, 'Actividad no encontrada');
			exit;
		}
	}

	public function accionModificar(){

		//Compruebo permisos del usuario
		if(!Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->irAPagina(array('registro','login'), []);
			exit;
		}

		
		if(!(Sistema::app()->Acceso()->puedePermiso(10))){
			Sistema::app()->paginaError(404,'No tienes permisos');
			exit;
		}

		//Obtengo id de la actividad
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$_SESSION['accion'] = ["actividades","modificar/id=$id"];

			
			$this->barraUbi = [
				[
					"texto" => "Inicio",
					"enlace" => ["actividades", "indexTabla"]
				], [
					"texto" => "Modificar actividad",
					"enlace" => ["actividades", "modificar/id=$id"]
				],
			];

			$actividades = new Actividades();
			$act = $actividades->buscarPorId($id);
			$nombre = $actividades->getNombre();

			if ($act != false) { //si la actividad existe
				if (isset($_POST[$nombre])) {
					$actividades->setValores($_POST[$nombre]);
					if(isset($_FILES['foto'])){
						$foto = $_FILES['foto']['name']; //actualizo foto
						$foto = CGeneral::addSlashes($foto);
						if ($foto != '' ) {
							$actividades->foto =  $foto;
						}
					}
					
					if ($actividades->validar()) { //valido la actividad

						$actividades->guardar(); //hago el update
						$fotoTemp = $_FILES['foto']['tmp_name'];
						$rutaDestino = RUTA_BASE . '/imagenes/actividades/' . $foto;

						move_uploaded_file($fotoTemp, $rutaDestino); //muevo la foto al servidor


						Sistema::app()->irAPagina(array("actividades", "ver"), ["id" => $id]);
						exit;
					}
				}
			} else {
				Sistema::app()->paginaError(404, 'Actividad no encontrada');
				exit;
			}


			$cab = ["nombre", "descripcion", "hora","aforo","foto", "borrado", "sala", "categoria"];
			$categorias = Categorias::dameCategorias();
			$salas = $actividades->dameSalas();
			$this->dibujaVista(
				"modificarActividad",
				['modelo' => $actividades, 'cab' => $cab, 'categorias' => $categorias, 'salas' => $salas],
				"Modificar actividad"
			);
		} else {
			Sistema::app()->paginaError(404, 'Actividad no encontrada');
			exit;
		}


	}

	public function accionBorrar(){


		//Compruebo si tiene permisos
		if(!Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->irAPagina(array('registro','login'), []);
			exit;
		}

		
		if(!(Sistema::app()->Acceso()->puedePermiso(10))){
			Sistema::app()->paginaError(404,'No tienes permisos');
			exit;
		}

		//Obtengo el id de la actividad para borrar
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$_SESSION['accion'] = ["actividades","borrar/id=$id"];

			
			$this->barraUbi = [
				[
					"texto" => "Inicio",
					"enlace" => ["actividades", "indexTabla"]
				], [
					"texto" => "Borrar producto",
					"enlace" => ["actividades", "borrar/id=$id"]
				],
			];

			$actividades = new Actividades();
			$act = $actividades->buscarPorId($id);

			if ($act != false) { //si el producto existe
				if (isset($_POST['id_0'])) {
					$actividades->borrado = 1; //modifico el borrado
					if ($actividades->validar()) { //valido la actividad
						$actividades->guardar(); //hago update de la actividad

						Sistema::app()->irAPagina(array("actividades", "ver"), ["id" => $id]);
						exit;
					}
				}
			} else {
				Sistema::app()->paginaError(404, 'Actividad no encontrada');
				exit;
			}


			$cab = ["nombre", "descripcion", "hora","aforo", "sala", "categoria", "borrado", 'foto'];
			$categorias = Categorias::dameCategorias();
			$salas = $actividades->dameSalas();
			$this->dibujaVista(
				"borrarActividad",
				['modelo' => $actividades, 'cab' => $cab, 'categorias' => $categorias, 'salas' => $salas],
				"Modificar actividad"
			);
		} else {
			Sistema::app()->paginaError(404, 'Actividad no encontrada');
			exit;
		}
	

	}

	public function accionNuevo(){

		//Comrpuebo si el usuario tiene permisos
		if(!Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->irAPagina(array('registro','login'), []);
			exit;
		}

		
		if(!(Sistema::app()->Acceso()->puedePermiso(10))){
			Sistema::app()->paginaError(404,'No tienes permisos');
			exit;
		}
		
		$this->barraUbi = [
			[
				"texto" => "Inicio",
				"enlace" => ["actividades", "indexTabla"]
			], [
				"texto" => "Nueva actividad",
				"enlace" => ["actividades", "nuevo"]
			],
		];

		$actividades = new Actividades();
		$nombre = $actividades->getNombre();
		$_SESSION['accion'] = ["actividades","nuevo"];
		if (isset($_POST[$nombre])) {
			$actividades->setValores($_POST[$nombre]);
			$foto = $_FILES['foto']['name'];
			$foto = CGeneral::addSlashes($foto);
			if ($foto != '') {
				$actividades->foto =  $foto;
			}
			if ($actividades->validar()) { //valido los nuevos datos 
				if ($actividades->buscarTodos([
					"where" => "nombre=" . "'$actividades->nombre'" //compruebo si esa actividad ya existe por el nombre , ya que el nombre es unico
				])) {
					Sistema::app()->paginaError(404, 'Esta actividad ya existe');
				} else {
					$actividades->guardar(); //hago el insert
					$fotoTemp = $_FILES['foto']['tmp_name'];
					$rutaDestino = RUTA_BASE . '/imagenes/actividades/' . $foto;

					move_uploaded_file($fotoTemp, $rutaDestino); //muevo la imagen nueva al servidor


					Sistema::app()->irAPagina(array("actividades", "indexTabla"));
					exit;
				}
			}
		}

		$cab = ["nombre", "descripcion", "hora","aforo","sala", "categoria", "borrado", 'foto'];


		$categorias = Categorias::dameCategorias(); //obtengo las categorias
		$salas = $actividades->dameSalas(); //obtengo las salas
		$this->dibujaVista(
			"nuevaActividad",
			['modelo' => $actividades, 'cab' => $cab, 'categorias' => $categorias, 'salas' => $salas],
			"Nuevo actividad"
		);
	}

	


	

	
}
