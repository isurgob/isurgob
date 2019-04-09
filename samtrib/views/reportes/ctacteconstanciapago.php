<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\Session;
use yii\data\ArrayDataProvider;


?>
<div class='body'>
	<p class='tt'><?= $titulo ?></p>
	<div class='divredon' style='padding:5px 15px'>
		<table class='cond' cellspacing='5'>
		<tr>
			<td><b>Tributo:&nbsp;</b></td><td width='350px'><?= $datos[0]['trib_nom'] ?></td>
			<td><b>Período (año/cuota):&nbsp;</b></td><td><?= $datos[0]['anio'].'/'.$datos[0]['cuota'] ?></td>
		</tr>
		<tr>
			<td><b>Objeto:&nbsp;</b></td><td><?= $datos[0]['obj_id'].' - '.$datos[0]['obj_nom'] ?></td>
			<td><b>Monto:&nbsp;</b></td><td><?= $datos[0]['pagado'] ?></td>
		</tr>
		<tr>
			<td><b>Dato:&nbsp;</b></td><td><?= $datos[0]['obj_dato'] ?></td>
			<td><b>Vencimiento:&nbsp;</b></td><td><?= $datos[0]['venc1'] ?></td>
		</tr>
		<tr>
			<td><b>Titular:&nbsp;</b></td><td><?= $datos[0]['num_nom'] ?></td>
			<td><b>Fecha Pago:&nbsp;</b></td><td><?= $datos[0]['fchpago'] ?></td>
		</tr>	
		<tr>
			<td><b>Medio de Pago:&nbsp;</b></td><td><?= $mdp ?></td>
		</tr>		
		</table>
	</div>
</div>
	
