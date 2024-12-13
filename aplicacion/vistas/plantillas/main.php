<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo $titulo; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="/estilos/principal.css" />

	<link rel="icon" type="image/png" href="/imagenes/favicon.png" />
	<?php
	if (isset($this->textoHead)){
		echo $this->textoHead;
	}
		

	?>

</head>

<body>
	<div id="todo">
		<header>
			<div class="logo">
				<a href="/index.php"><img src="/imagenes/logo.png" width="50px" height="50px" /></a>
			</div>
			<div class="titulo">
				<a href="/index.php">
					<h1>VitalFit</h1>
				</a>
			</div>
			<!-- #barra Login -->
			<div class="barraLogin">
				<?php
				echo CHTML::dibujaEtiqueta('span', [], Sistema::app()->Acceso()->hayUsuario() ? Sistema::app()->Acceso()->getNombre() : 'Usuario no conectado');
				?>
				<form action="" method="post">
					<?php
					//si hay usuario registrado muestro el botn cerrar sesion, si no esta registrado muestro el iniciar sesion
					if (Sistema::app()->Acceso()->hayUsuario()) {
						//	echo CHTML::campoBotonSubmit('Cerrar Sesion', ['name' => 'cerrar', 'value' => 'cerrar']);
						echo CHTML::botonHtml(CHTML::link("Cerrar sesion", Sistema::app()->generaURL(["registro", 'cerrar'])));
					} else {
						echo CHTML::botonHtml(CHTML::link("Login", Sistema::app()->generaURL(["registro", 'login'])));
					}
					?>
				</form>
			</div>
		</header><!-- #header -->
		<!-- #barra eslogan -->
		<div class="eslogan">
			<?php
			if (Sistema::app()->Acceso()->hayUsuario()) {
				$nick = Sistema::app()->Acceso()->getNick();
				if (isset($_COOKIE[$nick])) {
					$cad = mb_split(';',$_COOKIE[$nick]);
					$fra = mb_split('=',$cad[1]);
					$frase = $fra[1];
					
					Sistema::app()->cookie = $_COOKIE[$nick];
				
					echo CHTML::dibujaEtiqueta('h2', [], $frase);

					}else{
						echo CHTML::dibujaEtiqueta('h2', [], 'Potencia tu vitalidad, es el camino a la grandeza.');
					}
				}else{
					echo CHTML::dibujaEtiqueta('h2', [], 'Potencia tu vitalidad, es el camino a la grandeza.');
				}
			
			?>
		</div>

		<!-- #barra Menú -->

		<?php

		$nav = new CMenu(
			"",
		);

		echo $nav->dibujaApertura();
		echo CHTML::link(
			"Inicio",
			Sistema::app()->generaURL(["inicial"])
		);
		echo CHTML::link(
			"Salas",
			Sistema::app()->generaURL(["salas"])
		);
		if ((Sistema::app()->Acceso()->puedePermiso(9))) {
			echo CHTML::link(
				"Actividades",
				Sistema::app()->generaURL(["actividades"])
			);
			echo CHTML::link(
				"Reservas",
				Sistema::app()->generaURL(["reservas"])
			);
			echo CHTML::link(
				"Motivación",
				Sistema::app()->generaURL(["frases", "index"])
			);
		}

		if ((Sistema::app()->Acceso()->puedePermiso(10))) {
			echo CHTML::link(
				"Crud Actividades",
				Sistema::app()->generaURL(["actividades", "indextabla"])
			);
			echo CHTML::link(
				"Crud Salas",
				Sistema::app()->generaURL(["salas", "indextabla"])
			);
		}

	

		echo $nav->dibujaFin();
		?>

		<!-- #barra Ubicacion -->
		<div class="barraUbi">
			<?php
			if (isset($this->barraUbi)) {
				$totalOpciones = count($this->barraUbi);
				$contador = 1;
				foreach ($this->barraUbi as $opcion) {
					echo CHTML::link($opcion["texto"], $opcion["enlace"]);
					if ($contador < $totalOpciones) {
						echo " >> ";
					}

					$contador++;
				}
			}
			?>
		</div>
		<div class="contenido">
			<article>
				<?php echo $contenido; ?>
			</article><!-- #content -->
		</div>
		<footer>
			<h2><span>Copyright:</span> <?php echo Sistema::app()->autor ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Dirección:</span><?php echo Sistema::app()->direccion ?></h2>
		</footer><!-- #footer -->

	</div><!-- #wrapper -->
</body>

</html>