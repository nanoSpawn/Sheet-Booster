<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Numeración alzada de tickets - Gràfiques Muntaner</title>

<link href='http://fonts.googleapis.com/css?family=Asap:400,700,400italic,700italic|Bree+Serif' rel='stylesheet' type='text/css'>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript" src="js/downloadify.min.js"></script>

<style>

/*


*/
	body {
		font-family: 'Asap', sans-serif;
		font-size: 15px;
		line-height: 1.5em;
		color: #444;
		margin: 0;
		padding: 0;
		background: #660033;
		background-image: url('images/pattern-djwn.png');
		background-repeat: repeat;
	}
	
	input, textarea {
		font-family: 'Asap', sans-serif;
		font-size: 15px;
		color: #444;
		border: none;
		padding: 2px 6px;
		-webkit-box-shadow: inset 0px 0px 5px 0px rgba(0, 0, 0, 0.35);
		-moz-box-shadow:    inset 0px 0px 5px 0px rgba(0, 0, 0, 0.35);
		box-shadow:         inset 0px 0px 5px 0px rgba(0, 0, 0, 0.35);
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
	}

	textarea {
		width: 680px;
	}

	h1 {
		font-family: 'Bree Serif', serif;
		font-size: 1.7em;
		line-height: 1em;
		color: #660033;
		width: 100%;
		border-bottom: solid 1px #ccc;	
	}

	h2 {
		font-family: 'Bree Serif', serif;
		font-size: 1.2em;
		color: #666;
		border-bottom: none;	
	}

	#container {
		display: block;
		margin: 20px auto;
		padding: 30px;
		width: 700px;
		background: #f4f4f4;
		-webkit-box-shadow: 0px 0px 30px 3px rgba(0, 0, 0, 0.75);
		-moz-box-shadow:    0px 0px 30px 3px rgba(0, 0, 0, 0.75);
		box-shadow:         0px 0px 30px 3px rgba(0, 0, 0, 0.75);
		-webkit-border-radius: 10px;
		-moz-border-radius: 10px;
		border-radius: 10px;
	}

	.time {
		display: block;
		margin: 0 auto 10px auto;
		text-align: center;
		color: white;
		font-size: .6em;
		opacity: .5;
	}

	.explanation {
		font-size: .8em;
		color: #aaa;
	}

	.error {
		display: block;
		padding: 15px;
		-webkit-box-shadow: 0px 0px 10px 3px rgba(0, 0, 0, 0.35);
		-moz-box-shadow:    0px 0px 10px 3px rgba(0, 0, 0, 0.35);
		box-shadow:         0px 0px 10px 3px rgba(0, 0, 0, 0.35);
		-webkit-border-radius: 6px;
		-moz-border-radius: 6px;
		border-radius: 6px;

		color: red;
		background: white;
		font-weight: bold;

	}


</style>
	
</head>


<?php

//Valores default por si no se establecen via GET.
$from = $_GET['from']?:1;
$to = $_GET['to']?:100;
$length = $_GET['length']?:4;
$times = $_GET['times']?:1;
$paged = $_GET['paged']?:false;
$formatted = $_GET['formatted']?:false;
$filename = $_GET['filename']?:"numeros.xml";

//Valores default para la impresión
$tickets_hoja = $_GET['tickets_hoja']?:1;  // Depende del montaje de impresión.
$message = "";

$numberOfElements = 0;
	
// Ahora vienen las comprobaciones de los campos para evitar burradas sin querer.	

if (!is_numeric($from) || !is_numeric($to) || !is_numeric($length) || !is_numeric($times) || !is_numeric($tickets_hoja)) {
	$message = "Introduce valores numéricos.";
	$xml = "Arregla los valores y vuelve a probar.";
} elseif ($from > $to) {
	$message = "El campo 'de' no debe ser mayor al campo 'a'.";
	$xml = "Arregla los valores y vuelve a probar.";
} elseif ($from < 0 || $to < 0) {
	$message = "Han de ser valores positivos y mayores que 1.";
	$xml = "Arregla los valores y vuelve a probar.";
} elseif ($length < 1 || $times < 1) {
	$message = "La longitud mínima ha de ser 1. Y repetirse mínimo 1 vez";
	$xml = "Arregla los valores y vuelve a probar.";
} elseif ($tickets_hoja < 1) {
	$message = "Debería haber al menos 1 ticket por hoja.";
	$xml = "Arregla los valores y vuelve a probar.";
} else {
	// Una vez capturados y comprobados los valores, hacemos cálculos
	$numberOfElements = ($to-$from+1)*$times; // Total de elementos en el XML, tiene en cuenta repeticiones y todo.
	$total_tickets = $to-$from+1; // Total de tickets. 
	$hojas = ceil($total_tickets / $tickets_hoja); // Total de hojas, se redondea hacia arriba.
		
	$Root = new SimpleXMLElement('<Root/>'); // inicializamos el XML
	$xml = $Root->addChild('xml');  // Añadimos el hijo xml, que será el padre de todos
		
	for ($h = 1; $h <= $hojas; $h++) {  // Iteramos entre todas las hojas
		if ($paged) {$xml = $Root->addChild('page');} // Si lo queremos paginado, añadimos el tag al xml
		for ($i = 0; $i < $tickets_hoja; $i++) {  // Ahora entre los tickets de cada hoja
			for ($t = 1; $t <= $times; $t++) { // bucle para repetir $times veces cada número
				$j = str_pad($h + ($from-1) + ($hojas * $i), $length, '0', STR_PAD_LEFT); // Número calculado. Añadimos ceros.
				if ($j > $to) {$j = ' ';} // Para que no salgan números mayores que el máximo. Salen los tickets en blanco.
				$n = $xml->addChild('n'.$t, $j); //  Las etiquetas son n1, n2...
			}
				
		}
	}

	// Todo este código es para formatear el XML resultante, con line breaks, indentado, etc.
	// Más que nada para poder aplicar estilos de parágrafo a cada línea.
	if ($formatted) {
		$xml = new DOMDocument('1.0');
		$xml->preserveWhiteSpace = false;
		$xml->formatOutput = true;
		$xml->loadXML($Root->asXML());
		$xml = $xml->saveXML();		
	} else {
		$xml = $Root->saveXML();
	}	
}

?>

<body style="font-family: Helvetica, sans-serif;" onload="load();">
<div id="container">
<h1>Numeración alzada de tickets by Gràfiques Muntaner</h1>
<h2>Generador de números</h2>

<form action="num_alzado.php" method="GET" id="generador">
	<p>De <input name="from" type="text" id="from" value="<?php echo $from ?>" size="10" maxlength="10" /> 
  a <input name="to" type="text" id="to" value="<?php echo $to ?>" size="10" maxlength="10" /><br />
  Longitud <input name="length" type="text" id="length" value="<?php echo $length ?>" size="2" maxlength="10" /><br />
  Repetir <input name="times" type="text" id="times" value="<?php echo $times ?>" size="2" maxlength="10" /> veces <br /><br />
  <h2>Impresión</h2>
  Tickets por hoja  <input name="tickets_hoja" type="text" id="tickets_hoja" value="<?php echo $tickets_hoja ?>" size="10" maxlength="10" /><br />
  Número final de hojas (redondeado hacia arriba): <b><?php echo $hojas; ?></b><br /><br />
  <input type="checkbox" name="formatted" id="formatted" value="true" <?php echo isset($_GET['formatted']) ? "checked" : ""; ?>>XML con formato<br />
  <span class="explanation">Necesario para que haya line breaks en inDesign</span><br />
  <input type="checkbox" name="paged" id="paged" value="true" <?php echo isset($_GET['paged']) ? "checked" : ""; ?>>XML paginado<br />
  <span class="explanation">Para separar el XML también por páginas</span><br /><br />
  <input type="submit" value="Generar XML" /><br /><br />
  <?php if ($message<>"") {
  	echo '<span class="error">'.$message.'</span>';
  }
  ?>
</p>

<h2>Descarga de fichero con XML generado</h2>
<span class="explanation">Recuerda generar el XML de nuevo si has cambiado algún dato</span><br />
Nombre del archivo a descargar: <input type="text" value="<?php echo $filename; ?>" id="filename" name="filename" /><br />
<p id="downloadify">
				<i>You must have Flash 10 installed to download this file.<i>
			</p>
			


</form>
<script type="text/javascript">
    $("form").submit(function() {
        $("resultadoXML").val="";
    });
</script>

<script type="text/javascript">
			function load(){
				Downloadify.create('downloadify',{
					filename: function(){
						return document.getElementById('filename').value;
					},
					data: function(){ 
						return document.getElementById('resultadoXML').value;
					},
					onComplete: function(){ alert('¡Archivo guardado!'); },
					onCancel: function(){ alert('Descarga cancelada.'); },
					onError: function(){ alert('El contenido del fichero no puede ser nulo.'); },
					swf: 'media/downloadify.swf',
					downloadImage: 'images/download.png',
					width: 100,
					height: 18,
					transparent: true,
					append: false
				});
			}
		</script>





<p>&nbsp;</p>
<form>
<p><strong>XML generado        </strong> Nº de elementos: <?php echo $numberOfElements; ?></p>
<span class="explanation">Último XML generado, puedes hacer copy paste o guardarlo en archivo</span>

<p>
  <label for="resultadoXML"></label>
  <textarea name="resultadoXML" cols="60" rows="25" readonly id="resultadoXML"><?php print($xml); ?></textarea>
</p></form></div>
</body>
</html>
<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo '<span class="time">Page generated in '.$total_time.' seconds.</span>';
?>
