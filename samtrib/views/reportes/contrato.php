<?php
use yii\helpers\Html;
?>

<div class='body'>
	<br><br><br>
	<p class='tt'><?= $datos['titulo'] ?></p>
	<p class='cond' style='text-align:justify'><?= nl2br($datos['detalle']) ?></p>
	<table width='100%' border='0' class='desc' style='margin-top:100px'>
	<tr>
		<td align='center' style='width:20%; border-top:1px solid #000'><?= $condicion ?></td>
		<td width='10px'></td>
		<td align='center' style='width:20%; border-top:1px solid #000'>Firma Responsable</td>
		<td width='10px'></td>
		<td align='center' style='width:20%; border-top:1px solid #000'>Aclaraci&oacute;n</td>
		<td width='10px'></td>
		<td align='center' style='width:20%; border-top:1px solid #000'>D.N.I.</td>
	</tr>
	<tr><td colspan='7' height='50px'></td></tr>
	<tr>
		<td style='width:20%;'><?= $usuario ?></td>
		<td width='10px'></td>
		<td align='center' style='width:20%; border-top:1px solid #000'>Domicilio</td>
		<td width='10px'></td>
		<td align='center' style='width:20%; border-top:1px solid #000'>Tel&eacute;fono</td>
	</tr>
	</table>
</div>

