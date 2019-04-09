<p class='cond'>1- Servicios públicos</p>
<table class='cond' cellspacing='0' cellpadding='4' style='border:1px solid #000' align='center' width='95%'>
	<tr class='border'><td><b>Nº de Usuario de Gas</b></td><td><b>Nº de Usuario de Energía</b></td><td><b>Nº de Registro de OSM</b></td></tr>
	<tr class='border'><td height='25px'></td><td></td><td></td></tr>
</table>

<p class='cond'>2- Información bancaria</p>
<table class='cond' cellspacing='0' cellpadding='4' style='border:1px solid #000;text-align:center' align='center' width='95%'>
	<tr class='border'><td><b>Institución</b></td><td><b>Sucursal</b></td><td><b>Tipo de cuenta</b></td><td><b>Nº de cuenta</b></td></tr>
	<?php for ($j=0; $j<6; $j++){
		echo "<tr class='border'><td height='25px'></td><td></td><td></td><td></td></tr>";	
	}?>
</table>

<p class='cond'>3- Socios y Directores</p>
<table class='cond' cellspacing='0' cellpadding='4' style='border:1px solid #000;text-align:center' align='center' width='95%'>
	<tr class='border'><td><b>Nombre y Apellido</b></td><td><b>CUIT</b></td><td><b>Cargo</b></td><td><b>% de participación</b></td></tr>
	<?php for ($j=0; $j<5; $j++){
		echo "<tr class='border'><td height='25px'></td><td></td><td></td><td></td></tr>";	
	}?>
</table>

<p class='cond'>4- Líneas telefónicas, correos electrónicos, páginas web</p>
<table class='cond' cellspacing='0' cellpadding='4' style='border:1px solid #000;text-align:center' align='center' width='95%'>
	<tr class='border'><td><b>Titular</b></td><td><b>Nº de línea directa</b></td><td><b>Correo electrónico</b></td><td><b>Página WEB</b></td></tr>
	<?php for ($j=0; $j<4; $j++){
		echo "<tr class='border'><td height='25px'></td><td></td><td></td><td></td></tr>";	
	}?>
</table>

<!-- Salto de Página --> <div style="PAGE-BREAK-AFTER: always"></div>
<?php include('fiscalizareporte_enc.php'); ?>
<p class='tt18' align='center'><b>Anexo 7 - continuación</b>  </p>

<p class='cond'>5- Contable general</p>
<p class='cond' style='text-indent:25px;'>a) Contabilidad rubricada?</p>
<p class='cond' style='text-indent:25px;'>b) Fecha de cierre de ejercicio comercial</p>
<p class='cond' style='text-indent:25px;'>c) Registros computarizados y detalle</p>

<p class='cond' style='text-align:justify;margin-top:150px'>Declaro bajo juramento que los datos consignados en el presente Anexo 7 son fiel expresión 
de la verdad y el mismo se ha confeccionado sin omitir ni falsear datos.</p>

<table class='cond' width='90%' cellspacing='10' align='center' style='margin-top:100px'>
	<tr><td class='border_top' width='40%' align='center'>Nombre y Cargo</td><td width='10%'></td><td class='border_top' width='40%' align='center'>Firma del Contribuyente/Responsable</td></tr>
</table>
