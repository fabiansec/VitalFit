<?php

class salasControlador extends CControlador
{

	function __construct()
	{
	}

	public function accionIndex()
	{



		$_SESSION['accion'] = ["salas"];
		$this->barraUbi = [
			[
				"texto" => "Inicio",
				"enlace" => ["inicial"]
			],
			[
				"texto" => "Salas",
				"enlace" => ["salas"]
			],
		];



		$filtrado = [
			"nombre" => "",
			"orden" => ''
			
		];

		// Llamamos al boton de filtrar
		$nombre = '';
		// Validamos el nombre de Actividad
		if (isset($_REQUEST["nombre"])) {
			$nombre = $_REQUEST["nombre"];
			$nombre = trim($nombre);
			if($nombre != ''){
				$filtrado['nombre'] = $nombre;
			}
		}

		$orden = '';
		// Validamos el nombre de Actividad
		if (isset($_REQUEST["orden"])) {
			$orden = $_REQUEST["orden"];
			$orden = trim($orden);
			if($orden != ''){
				$filtrado['orden'] = $orden;
			}
		}

	
		$tamPagina = 3;

		if (isset($_GET["reg_pag"]))
			$tamPagina = intval($_GET["reg_pag"]);

		//Hacer peticion GET 

		$enlaceCurl = curl_init();
		//se indican las opciones para una petición HTTP GET
		curl_setopt($enlaceCurl, CURLOPT_URL, "http://2daw09.sitio2daw.es/api/salas/nombre=$nombre&orden=$orden");
		curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
		curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
		curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
		//ejecuto la petición
		$res = curl_exec($enlaceCurl);
		//cierro la sesión
		curl_close($enlaceCurl);
		$res = json_decode($res, true);
		$datos['datos'] = $res;
		$registros = count($res['datos']);

		if ($res['correcto'] === false) {
			Sistema::app()->paginaError(400, $res['datos']);
			return;
		}

		$numPaginas = ceil($registros / $tamPagina);
		$pag = 1;

		if (isset($_GET["pag"])) {
			$pag = intval($_GET["pag"]);
		}

		if ($pag > $numPaginas)
			$pag = $numPaginas;

		$inicio = $tamPagina * ($pag - 1);
		$lim = "$inicio,$tamPagina";

		if (count($filtrado) != 0) { // hago peticion get 
			curl_setopt($enlaceCurl, CURLOPT_URL, "http://2daw09.sitio2daw.es/api/salas/op=$lim&nombre=$nombre&orden=$orden");
			curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
			curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
			curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
			//ejecuto la petición
			$res = curl_exec($enlaceCurl);
			//cierro la sesión
			curl_close($enlaceCurl);
			$res = json_decode($res, true);
			$datos['datos'] = $res;

			if ($res['correcto'] === false) {
				Sistema::app()->paginaError(400, $res['datos']);
				return;
			}

			$filas= $res['datos'];

		} 

		//Creo sesion con el filtrado , para luego descargar el pdf
			if(isset($_SESSION['filtrado']) && $_SESSION['filtrado'] != ''){
				$_SESSION['filtrado']['nombre'] = $nombre;
				$_SESSION['filtrado']['lim'] = $lim;
			}else{
				$_SESSION['filtrado'] = [];
			}
			
		$opcPaginador = array(
			"URL" => Sistema::app()->generaURL(array("salas", "index"), $filtrado),
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

	
		$this->dibujaVista(
			"index",
			['opcpag' => $opcPaginador, 'filtrado' => $filtrado, 'filas' => $filas],
			"Salas"
		);
	}

	public function accionIndextabla()
	{

		//Compruebo si tiene permisos
		if(!Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->irAPagina(array('registro','login'), []);
			exit;
		}
		
		if(!(Sistema::app()->Acceso()->puedePermiso(10))){
			Sistema::app()->paginaError(404,'No tienes permisos');
			exit;
		}

		$_SESSION['accion'] = ["salas", "indextabla"];

		$this->barraUbi = [
			[
				"texto" => "Inicio",
				"enlace" => ["inicial"]
			],
			[
				"texto" => "Crud salas",
				"enlace" => ["salas", "indextabla"]
			],
		];

	
		$filtrado = [
			"nombre" => "",
			"borrado" => 2
		];

		// Llamamos al boton de filtrar
		$nombre = '';
		// Validamos el nombre de Actividad
		if (isset($_REQUEST["nombre"])) {
			$nombre = $_REQUEST["nombre"];
			$nombre = trim($nombre);
			$filtrado['nombre'] = $nombre;
		}


		$borrado = 2;
		if(isset($_REQUEST['borrado'])){
			$borrado = intval($_REQUEST['borrado']);
			if($borrado != ''){
				$filtrado['borrado'] =$borrado;
			}
		}


		$tamPagina = 3;

		if (isset($_GET["reg_pag"]))
			$tamPagina = intval($_GET["reg_pag"]);

		//Hacer peticion GET 

		$enlaceCurl = curl_init();
		//se indican las opciones para una petición HTTP GET
		curl_setopt($enlaceCurl, CURLOPT_URL, "http://2daw09.sitio2daw.es/api/salas/nombre=$nombre&borrado=$borrado");
		curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
		curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
		curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
		//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
		//ejecuto la petición
		$res = curl_exec($enlaceCurl);
		//cierro la sesión
		curl_close($enlaceCurl);
		$res = json_decode($res, true);
		$datos['datos'] = $res;
		if ($res['correcto'] === false) {
			Sistema::app()->paginaError(400, $res['datos']);
			return;
		}
		$registros = count($res['datos']);

		

		$numPaginas = ceil($registros / $tamPagina);
		$pag = 1;

		if (isset($_GET["pag"])) {
			$pag = intval($_GET["pag"]);
		}

		if ($pag > $numPaginas)
			$pag = $numPaginas;

		$inicio = $tamPagina * ($pag - 1);
		$lim = "$inicio,$tamPagina";

		if (count($filtrado) != 0) { // hago peticion get 
			  //hago peticion get por nombre
			curl_setopt($enlaceCurl, CURLOPT_URL, "http://2daw09.sitio2daw.es/api/salas/op=$lim&nombre=$nombre&borrado=$borrado");
			curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
			curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
			curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
			//ejecuto la petición
			$res = curl_exec($enlaceCurl);
			//cierro la sesión
			curl_close($enlaceCurl);
			$res = json_decode($res, true);
			$datos['datos'] = $res;

			if ($res['correcto'] === false) {
				Sistema::app()->paginaError(400, $res['datos']);
				return;
			}

			$filas= $res['datos'];

			

			
		}
			
		

		foreach ($filas as $clave => $fila) {
			$fila['oper'] =
				CHTML::link(
					CHTML::imagen('/imagenes/24x24/ver.png'),
					Sistema::app()->generaURL(
						['salas', 'ver'],
						[
							'id' => $fila['cod_sala']
						]
					)
				) .
				CHTML::link(
					CHTML::imagen('/imagenes/24x24/modificar.png'),
					Sistema::app()->generaURL(
						['salas', 'modificar'],
						[
							'id' => $fila['cod_sala']
						]
					)
				) .
				CHTML::link(
					CHTML::imagen('/imagenes/24x24/borrar.png'),
					Sistema::app()->generaURL(
						['salas', 'borrar'],
						[
							'id' => $fila['cod_sala']
						]
					)
				);

			$fila['foto'] = CHTML::imagen('../../imagenes/salas/' . $fila['foto'], '', ['class' => 'imgTabla']);

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
			"URL" => Sistema::app()->generaURL(array("salas", "indextabla"), $filtrado),
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



		$this->dibujaVista(
			"indextabla",
			['opcpag' => $opcPaginador, 'filtrado' => $filtrado, 'filas' => $filas, 'cab' => $cabecera],
			"Salas"
		);
	}

	public function accionVer()
	{

		//Compruebo si tiene permisos
		if(!Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->irAPagina(array('registro','login'), []);
			exit;
		}
		
		if(!(Sistema::app()->Acceso()->puedePermiso(10))){
			Sistema::app()->paginaError(404,'No tienes permisos');
			exit;
		}

		//Obtengo el id , de la sala
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$_SESSION['accion'] = ["salas", "ver/id=$id"];



			$this->barraUbi = [
				[
					"texto" => "Inicio",
					"enlace" => ["salas", "indexTabla"]
				], [
					"texto" => "Ver sala",
					"enlace" => ["salas", "ver/id=$id"]
				],
			];
			//hago un get para recoger sus datos por el id
			$enlaceCurl = curl_init();
			curl_setopt($enlaceCurl, CURLOPT_URL, "http://2daw09.sitio2daw.es/api/salas/id=$id");
			curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
			curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
			curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
			//ejecuto la petición
			$res = curl_exec($enlaceCurl);
			//cierro la sesión
			curl_close($enlaceCurl);
			$res = json_decode($res, true);
			$datos['datos'] = $res;
			$filas = $res['datos'];

			if ($res['correcto'] === false) {
				Sistema::app()->paginaError(400, $res['datos']);
				exit;
			}

			$cab = ["nombre", "descripcion", "foto", "borrado"];

			$this->dibujaVista(
				"verSala",
				['filas' => $filas, 'cab' => $cab],
				"Ver Sala"
			);
		} else {
			Sistema::app()->paginaError(404, 'Sala no encontrada');
			exit;
		}
	}

	public function accionModificar()
	{

		//Compruebo si tiene permisos
		if(!Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->irAPagina(array('registro','login'), []);
			exit;
		}
		
		if(!(Sistema::app()->Acceso()->puedePermiso(10))){
			Sistema::app()->paginaError(404,'No tienes permisos');
			exit;
		}

		$errores = [];

		//Obtengo el id de la sala a modificar
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$_SESSION['accion'] = ["salas", "modificar/id=$id"];


			$this->barraUbi = [
				[
					"texto" => "Inicio",
					"enlace" => ["salas", "indexTabla"]
				], [
					"texto" => "Modificar sala",
					"enlace" => ["salas", "modificar/id=$id"]
				],
			];

			//Recogida de datos
			if (isset($_POST['id_3'])) {


				$nombre = '';
				if (isset($_POST['nombre'])) {
					$nombre = trim($_POST['nombre']);
				}

				$descripcion = '';
				if (isset($_POST['descripcion'])) {
					$descripcion = trim($_POST['descripcion']);
				}
	
				$borrado = '';
				if (isset($_POST['borrado'])) {
					$borrado = trim($_POST['borrado']);
				}

				$enlaceCurl = curl_init();

				//se indican las opciones para una petición HTTP PUT
				curl_setopt(
					$enlaceCurl,
					CURLOPT_URL,
					"http://2daw09.sitio2daw.es/api/salas/"
				);
				curl_setopt($enlaceCurl, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
				curl_setopt(
					$enlaceCurl,
					CURLOPT_POSTFIELDS,
					"id=$id&nombre=$nombre&descripcion=$descripcion&borrado=$borrado"
				);
				curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
				//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
				$devuelto = curl_exec($enlaceCurl);
				$res = json_decode($devuelto, true);
				if ($res['correcto'] === false) {
					$errores = $res['datos']; //guardo los errores

				} else {

					Sistema::app()->irAPagina(array("salas", "ver"), ["id" => $res['datos']]);
					exit;
				}

				
				$enlaceCurl = curl_init();
				curl_setopt($enlaceCurl, CURLOPT_URL, "http://2daw09.sitio2daw.es/api/salas/id=$id");
				curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
				curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
				curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
				//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
				//ejecuto la petición
				$res = curl_exec($enlaceCurl);
				//cierro la sesión
				curl_close($enlaceCurl);
				$res = json_decode($res, true);
				$datos['datos'] = $res;

				if ($res['correcto'] === false) {
					Sistema::app()->paginaError(400, $res['datos']);
					exit;
				}

				$filas = $res['datos'];
			} else {
				//hago peticion get y muestro los datos
				$enlaceCurl = curl_init();
				curl_setopt($enlaceCurl, CURLOPT_URL, "http://2daw09.sitio2daw.es/api/salas/id=$id");
				curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
				curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
				curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
				//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
				//ejecuto la petición
				$res = curl_exec($enlaceCurl);
				//cierro la sesión
				curl_close($enlaceCurl);
				$res = json_decode($res, true);
				$datos['datos'] = $res;
				if ($res['correcto'] === false) {
					Sistema::app()->paginaError(400, $res['datos']);
					exit;
				}

				$filas = $res['datos'];
			}

			$cab = ["nombre", "descripcion", "foto", "borrado"];
			$this->dibujaVista('modificarSala', ['filas' => $filas, 'cab' => $cab, 'errores' => $errores], 'Modificar sala');
		} else {
			Sistema::app()->paginaError(404, 'Sala no encontrada');
			exit;
		}
	}

	public function accionBorrar()
	{


		//Compruebo si tiene permisos
		if(!Sistema::app()->Acceso()->hayUsuario()){
			Sistema::app()->irAPagina(array('registro','login'), []);
			exit;
		}
		
		if(!(Sistema::app()->Acceso()->puedePermiso(10))){
			Sistema::app()->paginaError(404,'No tienes permisos');
			exit;
		}

		//Recojo el id de la sala
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$_SESSION['accion'] = ["salas", "ver/id=$id"];



			$this->barraUbi = [
				[
					"texto" => "Inicio",
					"enlace" => ["salas", "indexTabla"]
				], [
					"texto" => "Ver sala",
					"enlace" => ["salas", "ver/id=$id"]
				],
			];
			$enlaceCurl = curl_init();
			curl_setopt($enlaceCurl, CURLOPT_URL, "http://2daw09.sitio2daw.es/api/salas/id=$id");
			curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
			curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
			curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
			//ejecuto la petición
			$res = curl_exec($enlaceCurl);
			//cierro la sesión
			curl_close($enlaceCurl);
			$res = json_decode($res, true);
			$datos['datos'] = $res;
			$filas = $res['datos'];

			if ($res['correcto'] === false) {
				Sistema::app()->paginaError(400, $res['datos']);
				exit;
			}

			$cab = ["nombre", "descripcion", "foto", "borrado"];


			//si pulsa en borrar , hago delete de la sala
			if (isset($_POST['id_3'])) {
				$enlaceCurl = curl_init();
				//se indican las opciones para una petición HTTP DELETE
				curl_setopt(
					$enlaceCurl,
					CURLOPT_URL,
					"http://2daw09.sitio2daw.es/api/salas/"
				);
				curl_setopt($enlaceCurl, CURLOPT_CUSTOMREQUEST, "DELETE");
				curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
				curl_setopt($enlaceCurl, CURLOPT_POSTFIELDS, "id=$id");
				curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
				//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
				$res = curl_exec($enlaceCurl);
				//cierro la sesión
				curl_close($enlaceCurl);
				$res = json_decode($res, true);
				$datos['datos'] = $res;

				if ($res['correcto'] === false) {
					Sistema::app()->paginaError(400, $res['datos']);
					exit;
				}

				Sistema::app()->irAPagina(array("salas", "ver"), ["id" => $res['datos']]);
				exit;
			}
			$this->dibujaVista(
				"borrarSala",
				['filas' => $filas, 'cab' => $cab],
				"Borrar sala"
			);
		} else {
			Sistema::app()->paginaError(404, 'Sala no encontrada');
			exit;
		}
	}

	public function accionNuevo()
	{

		//Compruebo permisos del usuario
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
				"enlace" => ["salas", "indexTabla"]
			], [
				"texto" => "Nueva sala",
				"enlace" => ["salas", "nuevo"]
			],
		];

		$errores = [];
		$datos = [
			'nombre' => '',
			'descripcion' => '',
			'foto' => '',
			'borrado' => ''
		];

		//si le da al boton enviar, valido datos
		if (isset($_POST['id_0'])) {
			$nombre = '';
			if (isset($_POST['nombre'])) {
				$nombre = trim($_POST['nombre']);
			}
			$datos['nombre'] = $nombre;

			$descripcion = '';
			if (isset($_POST['descripcion'])) {
				$descripcion = trim($_POST['descripcion']);
			}
			$datos['descripcion'] = $descripcion;

			$foto = '';
			if (isset($_FILE['foto'])) {
				$foto = trim($_FILE['foto']['name']);
			}
			$datos['foto'] = $foto;

			$borrado = '';
			if (isset($_POST['borrado'])) {
				$borrado = trim($_POST['borrado']);
			}
			$datos['borrado'] = $borrado;

			//creo una sesión CUrl
			$enlaceCurl = curl_init();
			//se indican las opciones para una petición HTTP Post
			curl_setopt(
				$enlaceCurl,
				CURLOPT_URL,
				"http://2daw09.sitio2daw.es/api/salas/"
			);
			curl_setopt($enlaceCurl, CURLOPT_POST, 1);
			curl_setopt(
				$enlaceCurl,
				CURLOPT_POSTFIELDS,
				"nombre=$nombre&descripcion=$descripcion&foto=$foto&borrado=$borrado"
			);
			curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
			curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
			//ejecuto la petición
			$res = curl_exec($enlaceCurl);
			//cierro la sesión
			curl_close($enlaceCurl);
			$res = json_decode($res, true);

			if ($res['correcto'] === false) {
				$errores = $res['datos']; //guardo los errores
			} else {
				Sistema::app()->irAPagina(array("salas", "ver"), ["id" => $res['datos']]);
				exit;
			}
		}


		$cab = ["nombre", "descripcion", "foto", "borrado"];
		$this->dibujaVista('nuevaSala', ['cab' => $cab, 'errores' => $errores, 'datos' => $datos], 'Nueva sala');
	}

	public function accionDescargar()
	{

		// create new PDF document
		$pdf = new miTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('VitalFit');
		$pdf->SetTitle('Salas');
		$pdf->SetSubject('Salas filtradas');

		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
			require_once(dirname(__FILE__) . '/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// ---------------------------------------------------------

		// set font
		$pdf->SetFont('times', 'BI', 12);

		// add a page
		$pdf->AddPage();

		// set some text to print
		$html = <<<EOF
		<h5>Salas que cumplen el filtrado</h5>
		<table border="1" cellspacing="3" cellpadding="4" style="border-collapse: collapse;">
		<tr style= "font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;background-color: #C7C8CC;text-align: center;">
			<th>Nombre</th>
			<th>Descripcion</th>
			<th>Borrado</th>
		</tr>			
		
		EOF;
		$enlaceCurl = curl_init();
		if (isset($_SESSION['filtrado']) && $_SESSION['filtrado'] != '') {
			$nombre =trim( $_SESSION['filtrado']['nombre']);
			$lim = trim( $_SESSION['filtrado']['lim']);
			curl_setopt($enlaceCurl, CURLOPT_URL, "http://2daw09.sitio2daw.es/api/salas/nombre=$nombre&op=$lim");

			curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
			curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
			curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
			//ejecuto la petición
			$res = curl_exec($enlaceCurl);
			//cierro la sesión
			curl_close($enlaceCurl);
			$res = json_decode($res, true);
			$datos['datos'] = $res;

			if ($res['correcto'] === false) {
				Sistema::app()->paginaError(400, $res['datos']);
				return;
			}

			$filas = $res['datos'];


			foreach ($filas as $clave => $valor) {
				$html .= '<tr style="background-color: #F2EFE5;text-align: center;
			font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;">';
				foreach ($valor as $propiedades => $valores) {

					if ($propiedades === 'nombre') {
						$html .= "<td><p>$valores</p></td>";
					}
					if ($propiedades === 'descripcion') {
						$html .= "<td><p>$valores</p></td>";
					}
			
				}
				$html .= "</tr>";
			}
		} else {
			curl_setopt($enlaceCurl, CURLOPT_URL, "http://2daw09.sitio2daw.es/api/salas/");


			curl_setopt($enlaceCurl, CURLOPT_HTTPGET, 1);
			curl_setopt($enlaceCurl, CURLOPT_HEADER, 0);
			curl_setopt($enlaceCurl, CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($enlaceCurl, CURLOPT_PROXY, "192.168.2.254:3128");
			//ejecuto la petición
			$res = curl_exec($enlaceCurl);
			//cierro la sesión
			curl_close($enlaceCurl);
			$res = json_decode($res, true);
			$datos['datos'] = $res;

			if ($res['correcto'] === false) {
				Sistema::app()->paginaError(400, $res['datos']);
				return;
			}

			$filas[] = $res['datos'];


			foreach ($filas as $clave => $valor) {
				foreach ($valor as $propiedades => $valores) {
					$html .= '<tr style="background-color: #F2EFE5;text-align: center;
			font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;">';
					foreach ($valores as $ind => $val) {
						if ($ind === 'nombre') {
							$html .= "<td><p>$val</p></td>";
						}
						if ($ind === 'descripcion') {
							$html .= "<td><p>$val</p></td>";
						}
						if ($ind === 'borrado') {
							$html .= "<td><p>$val</p></td>";
						}
					}
					$html .= "</tr>";
				}
			}
		}

		$html .= "</table>";

		$pdf->writeHTML($html, true, false, true, false, '');

		// ---------------------------------------------------------

		//Close and output PDF document
		$pdf->Output('informeSalas.pdf', 'D');

	
	}
}
