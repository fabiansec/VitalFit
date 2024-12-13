<?php

	class CGrid extends CWidget
	{
		private $_columnas=array();
		private $_filas=array();
		private $_atributosHTML=array();
	
		public function __construct($cabecera,$filas,$atributosHTML=array())
		{
			foreach($cabecera as $cabe)
			{
				$fila=array("CAMPO"=>"jjjjjj",
							"ETIQUETA"=>"",
							"ANCHO"=>"",
							"VISIBLE"=>true,
							"ALINEA"=>"izq");
							
				if (isset($cabe["CAMPO"]))
						$fila["CAMPO"]=$cabe["CAMPO"];
				
				if (isset($cabe["ETIQUETA"]))
						$fila["ETIQUETA"]=$cabe["ETIQUETA"];
					else
						$fila["ETIQUETA"]=$fila["CAMPO"];
				
				if (isset($cabe["ANCHO"]))
						$fila["ANCHO"]=$cabe["ANCHO"];
				
				if (isset($cabe["VISIBLE"]) && is_bool($cabe["VISIBLE"]))
						$fila["VISIBLE"]=$cabe["VISIBLE"];
				
				if (isset($cabe["ALINEA"]) && 
						in_array($cabe["ALINEA"],array("izq","der","cen")))
						$fila["ALINEA"]=$cabe["ALINEA"];
				
				$this->_columnas[]=$fila;
				
			}
			
			$this->_filas=$filas;
			$this->_atributosHTML=$atributosHTML;
			
			if (!isset($this->_atributosHTML["class"]))
				$this->_atributosHTML["class"]="tabla";
			if (!isset($this->_atributosHTML["cellpadding"]))
				$this->_atributosHTML["cellpadding"]="0";
			if (!isset($this->_atributosHTML["cellspacing"]))
				$this->_atributosHTML["cellspacing"]="0";
			if (!isset($this->_atributosHTML["border"]))
				$this->_atributosHTML["border"]="0";
							
			
		}
		
		public function dibujate():string
		{
			return $this->dibujaApertura().$this->dibujaFin();
		}
		
		public function dibujaApertura():string
		{
			ob_start();
			
			echo CHTML::dibujaEtiqueta("div",$this->_atributosHTML,
										"",false);
			echo CHTML::dibujaEtiqueta("table",$this->_atributosHTML,
										"",false);
			?>							
			<tr>
				<?php
				foreach ($this->_columnas as $columna) 
				{
				  $eti=$etiqueta="th";
				  if ($columna["ANCHO"]!="")
				  		$etiqueta.=" width='".$columna["ANCHO"]."'";		
					
				  if ($columna["VISIBLE"])	
				   		echo "<$etiqueta>".$columna["ETIQUETA"]."</$eti>";	
				}
				
				?>
			</tr>	
			<?php
			$par=false;
				
			foreach ($this->_filas as $fila) 
			{
				if ($par)
						echo "<tr class='par'>\n";
				     else
					 	echo "<tr class='impar'>\n";
				$par=!$par;
				   
				foreach ($this->_columnas as $columna) 
				{
					$campo="";
					if (isset($fila[$columna["CAMPO"]]))
						$campo=$fila[$columna["CAMPO"]];
					$eti=$etiqueta="td";
					
					switch ($columna["ALINEA"]) {
						case 'izq': ;
							break;
						
						case 'der': $etiqueta.=" align='right'";
							break;
						
						case 'cen': $etiqueta.=" align='center'";
							break;
						
					}
					if ($columna["VISIBLE"])	
				  		echo "\t<$etiqueta>".$campo."</$eti>\n";	
				}		
				echo "</tr>\n";
			}
										
		    echo CHTML::dibujaEtiquetaCierre("table");								
		    echo CHTML::dibujaEtiquetaCierre("div");								
		    			
			$escrito=ob_get_contents();
			ob_end_clean();
			
			return $escrito;		
		}
				
		public function dibujaFin():string
		{
			return "";
		}
		
	}
