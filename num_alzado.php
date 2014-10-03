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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript" src="js/downloadify.min.js"></script>
	
</head>


<?php

//Valores default por si no se establecen via GET.
$from = $_GET['from']?:1;
$to = $_GET['to']?:100;
$length = $_GET['length']?:4;
$times = $_GET['times']?:1;
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
<p><strong>Numeración alzada de tickets by Gràfiques Muntaner</strong></p>

<form action="num_alzado.php" method="GET">
<p>De 
  <input name="from" type="text" id="from" value="<?php echo $from ?>" size="10" maxlength="10" /> 
  a 
  <input name="to" type="text" id="to" value="<?php echo $to ?>" size="10" maxlength="10" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Longitud <input name="length" type="text" id="length" value="<?php echo $length ?>" size="2" maxlength="10" /><br />
  Repetir <input name="times" type="text" id="times" value="<?php echo $times ?>" size="2" maxlength="10" /> veces <br /><br />
  <b>Impresión</b><br />
  Tickets por hoja  <input name="tickets_hoja" type="text" id="tickets_hoja" value="<?php echo $tickets_hoja ?>" size="10" maxlength="10" /><br />
  Número final de hojas (redondeado hacia arriba): <b><?php echo $hojas; ?></b><br /><br />
  <input type="checkbox" name="formatted" id="formatted" value="true" <?php echo isset($_GET['formatted']) ? "checked" : ""; ?>>XML con formato <br /><br />
  <input type="submit" value="Generar XML" /><br /><br />
 <span style="color:red; font-weight: bold;"> <?php echo $message; ?></span>
</p>

<p><strong>Descarga de fichero con XML generado</strong></p>
Nombre del archivo a descargar: <input type="text" value="<?php echo $filename; ?>" id="filename" name="filename" />
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
<p><strong>XML generado        </strong> Nº de elementos: <?php echo $numberOfElements; ?></p>
<p>
  <label for="resultadoXML"></label>
  <textarea name="resultadoXML" cols="60" rows="25" readonly id="resultadoXML"><?php print($xml); ?></textarea>
</p></form>
</body>
</html>
<?php
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';
?>
