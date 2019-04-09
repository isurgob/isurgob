<?php
use yii\helpers\Html;
use app\utils\db\utb;

$titular = "Constancia de Inscripci&oacute;n a Ingresos Brutos";
if ($model->est_ib == 'B') $titular = "Constancia de Baja de Inscripci&oacute;n a Ingresos Brutos";

?>

<div class='body'>
	<p class='tt'><?= $titular ?></p>
	<table class='cond12'>
		<tr>
			<td><b>Ing. Brutos Nº:</b></td><td><b> <?= $model->ib ?> </b></td>
		</tr>
		<tr>
			<td><b>Cuit: </b></td><td><b><?= substr($model->cuit,0,2) . " - " . substr($model->cuit,2,8) . " - " . substr($model->cuit,10) ?> </b></td>
		</tr>
		<tr>
			<td><b>Estado: </b></td><td><b><?= utb::getCampo("v_persona","obj_id='" . $model->obj_id . "'","est_nom") ?> </b></td>
		</tr>
		<tr>
			<td><b>Estado IB: </b></td><td><b><?= utb::getCampo("v_persona","obj_id='" . $model->obj_id . "'","est_ib_nom") ?> </b></td>
		</tr>
		<tr>
			<td><b>Alta:</b></td><td><b> <?= $model->fchalta_ib ?> </b></td>
		</tr>
		<tr>
			<td><b>Nombre:</b></td><td><b> <?= utb::getNombObj($model->obj_id,false) ?> </b></td>
		</tr>
		<tr>
			<td><b>Direcci&oacute;n:</b></td><td><b> <?= ($domipost != '' ? $domipost : $domifiscal) ?> </b></td>
		</tr>
		<?php if ($model->est_ib == 'B') { ?>
		<tr>
			<td><b>Baja:</b></td><td><b> <?= $model->fchbaja_ib ?> </b></td>
		</tr>
		<?php } ?>
	</table>
	<p class='cond'>
		Se deja constancia que el presente contribuyente se halla inscripto en este Municipio como contribuyente sobre los Ingresos Brutos en las siguientes actividades:
	</p>
	<table width='100%' class='cond' cellspacing='5'>
		<tr class='border_bottom'>
			<td><b>Nomen</b></td>
			<td><b>Cod.</b></td>
			<td><b>Nombre</b></td>
			<td><b>Cant.</b></td>
			<td><b>Tipo</b></td>
			<td><b>TCalculo</b></td>
		</tr>
		<?php
			foreach ($model->rubros as $rubro){
				echo "<tr>";
				echo "<td>" . $rubro['nomen_nom'] . "</td>";
				echo "<td>" . $rubro['rubro_id'] . "</td>";
				echo "<td>" . $rubro['rubro_nom'] . "</td>";
				echo "<td>" . $rubro['cant'] . "</td>";
				echo "<td>" . $rubro['tipo_nom'] . "</td>";
				echo "<td>".utb::getCampo("v_objeto_rubro","rubro_id='".$rubro['rubro_id']."'","tcalculo")."</td>";
				echo "</tr>";
			}
		?>
	</table>

</div>
