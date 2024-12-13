<?php
	 
class inicialControlador extends CControlador
{

	function __construct()
	{
		
	}

	public function accionIndex()
	{
		$this->barraUbi = [
			[
				"texto" => "Inicio", 
				"enlace" => ["inicial"]
			], 
		];
		$_SESSION['accion'] = ["inicial","index"];
		
		$actividades = new Actividades();
		$act = $actividades->buscarTodos();

		$opciones=[];
		$filtrado=[
			"nombre"=>"",
			"aforo" => "",
			"orden" => 0
		];

		// Llamamos al boton de filtrar
	
			$where="";

			// Validamos el nombre de Producto
			$nombre = '';
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

			$aforo = '';
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
			$opciones["where"]=$where;
			$orden = '';
			if (isset($_REQUEST['orden'])) {
				$orden = intval($_REQUEST['orden']);
				if ($orden === 1) {
					$opciones["order"] = ' nombre';
					$filtrado["orden"] = 1;
				} else {
					$filtrado["orden"] = false;
				}
			} else {
				// Si no está presente, el checkbox no está marcado
				$filtrado["orden"] = false;
			}
			

		
			

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

		if($opciones === 0 ){
			$filas = $actividades->buscarTodos();
			$activi = [];
			foreach ($filas as $clave => $fila) {
				if(intval($fila['aforo']) >= 25){
					$activi[] = $fila;
					$filas = $activi;
				}
			}
		}else{
			$filas = $actividades->buscarTodos($opciones);
			if ($filas===false) {
				Sistema::app()->paginaError(400, "No hay actividades");
				return;
			}else{
				$activi = [];
				foreach ($filas as $clave => $fila) {
					if(intval($fila['aforo']) >= 25){
						$activi[] = $fila;
						$filas = $activi;
					}else{
						unset($filas[$clave]);
					}
				}
			}
			
		}
		



		$opcPaginador = array(
			"URL" => Sistema::app()->generaURL(array("inicial"),$filtrado),
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

		$_SESSION['filtrado'] = $where;
		$_SESSION['order'] = ' nombre';

		$this->dibujaVista(
			"index",
			['actividades' => $act ,'opcpag' => $opcPaginador, 'filtrado' => $filtrado, 'filas' => $filas, 'modelo' => $actividades],
			"Pagina principal"
		);		

	}


	public function accionDescargar(){
				// create new PDF document
				$pdf = new miTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

				// set document information
				$pdf->SetCreator(PDF_CREATOR);
				$pdf->SetAuthor('VitalFit');
				$pdf->SetTitle('Actividades');
				$pdf->SetSubject('Actividades destacadas');
		
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
				<h4>Informe actividades destacadas</h4>			
				<h5>Actividades que cumplen el filtrado</h5>
				<table border="1" cellspacing="3" cellpadding="4" style="border-collapse: collapse;">
				<tr style= "font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;background-color: #C7C8CC;text-align: center;">
					<th>Nombre</th>
					<th>Descripcion</th>
					<th>Hora</th>
					<th>Aforo</th>
					<th>Sala</th>
					<th>Categoria</th>
				</tr>			
				
				EOF;
				$actividades = new Actividades();
				if(isset($_SESSION['filtrado']) || isset($_SESSION['order'])){
					$sentWhere = $_SESSION['filtrado'];
					$orden = $_SESSION['order'];
					$prop = $actividades->buscarTodos(
						["where" => $sentWhere,
						 "order" => $orden
						]
					);
				}
				foreach($prop as $clave => $valor){
					$html.='<tr style="background-color: #F2EFE5;text-align: center;
					font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;">';
	
					foreach($valor as $propiedades => $valores){
						if ($propiedades === 'nombre') {
							$html.= "<td><p>$valores</p></td>";
						}
						if ($propiedades === 'descripcion') {
							$html.= "<td><p>$valores</p></td>";
						}
				
						if ($propiedades === 'hora') {
							$html.= "<td><p>$valores h</p></td>";
						}
				
						if ($propiedades === 'aforo') {
							$html.= "<td><p>$valores</p></td>";
						}
						if ($propiedades === 'sala') {
							$html.= "<td><p>$valores</p></td>";
						}
						if ($propiedades === 'categoria') {
							$html.= "<td><p>$valores</p></td>";
						}
					}
					$html.="</tr>";
	
				}	
		
				$html.="</table>";	
	
				// print a block of text using Write()
				$pdf->writeHTML($html, true, false, true, false, '');
		
				// ---------------------------------------------------------
		
				//Close and output PDF document
				$pdf->Output('informeActicidades.pdf', 'D');
		
				//============================================================+
				// END OF FILE
				//============================================================+
	}
	
}
