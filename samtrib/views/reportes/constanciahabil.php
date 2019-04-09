<?php
use yii\helpers\Html;
use app\utils\db\utb;

$titular = [];

foreach ($modelObjeto->arregloTitulares as $princ){
	if (in_array($princ['tvinc'],[1,5])) $titular = $princ;
}

$titulo = $modelObjeto->est == 'B' ? 'Constancia de Baja de Habilitación Comercial' : 'Habilitación Comercial';
?>

<!--
<div class="media">
	<div class="pull-left">
		<a href="#">
			<img src="<?= Yii::$app->param->logo; ?>" class="media-object" />
		</a>
	</div>

	<div class="pull-left">
		<b><?= Yii::$app->param->muni_name ?></b><br>
		<?= Yii::$app->params->muni_domi ?><br>
		<?= 'Tel.: '.Yii::$app->param->muni_tel ?><br>
		<?= Yii::$app->params->muni_mail ?><br>
	</div>
</div>
-->

<div class='body'>
	<p class='tt' style="font-size:26px;"><?= $titulo ?></p>
	<table class='cond' style="margin-left:auto; margin-right:auto; font-size:14px;">
		<tr>
			<td><b>Titular:</b></td><td><b> <?= $titular['num'] . " - " . $titular['apenom'] ?> </b></td>
		</tr>
		<tr>
			<td><b>CUIT:</b></td><td><b> <?= $responsablePrincipal[ 'cuit' ] ?> </b></td>
		</tr>
		<tr>
			<td><b>Ing.Brutos:</b></td><td><b> <?= $responsablePrincipal[ 'ib' ] ?> </b></td>
		</tr>
		<tr>
			<td><b>Nombre Fantas&iacute;a:</b></td><td><b> <?= $modelObjeto->nombre ?> </b></td>
		</tr>
		<tr>
			<td><b>Objeto: </b></td><td><b><?= $modelComer->obj_id ?> </b></td>
		</tr>
		<tr>
			<td><b>Liquidaci&oacute;n: </b></td><td><b><?= $responsablePrincipal[ 'tipoliq_nom' ] ?> </b></td>
		</tr>
		<tr>
			<td><b>Estado: </b></td><td><b><?= utb::getCampo("objeto_test","cod='" . $modelObjeto->est . "' and tobj=" . $modelObjeto->tobj) ?> </b></td>
		</tr>
		<tr>
			<td><b>Legajo: </b></td><td><b><?= $modelComer->legajo ?> </b></td>
		</tr>
		<tr>
			<td><b>Tipo: </b></td><td><b><?= utb::getCampo("comer_thab","cod='" . $modelComer->thab . "'") ?> </b></td>
		</tr>
		<?php if ($modelComer->thab == 'C' and $modelComer->inmueble != ''){ ?>
			<tr>
				<td><b>Datos Inmueble: </b></td><td><b><?= utb::getCampo("v_comer","obj_id='" . $modelObjeto->obj_id . "'","inm_dato") ?> </b></td>
			</tr>
		<?php } elseif ($modelComer->thab != 'C' and $modelComer->rodados != '') { ?>   <!-- Rodados (Taxi, transporte de pasajeros) -->
			<tr>
				<td><b>Datos Rodado: </b></td><td><b><?= utb::getCampo("v_comer","obj_id='" . $modelObjeto->obj_id . "'","rodado_dato") ?> </b></td>
			</tr>
		<?php } ?>
		<tr>
			<td><b>Domicilio: </b></td><td><b><?= $domicilioParcelario ?> </b></td>
		</tr>
		<tr>
			<td><b>Alta: </b></td><td><b><?= $modelComer->fchhab; ?> </b></td>
		</tr>
		<tr>
			<td><b>Vencimiento: </b></td><td><b><?= $modelComer->fchvenchab ?> </b></td>
		</tr>
		<?php if ($modelObjeto->est == 'B'){ ?>
			<tr>
				<td><b>Baja: </b></td><td><b><?= date('d/m/Y',strtotime($modelObjeto->fchbaja)) ?> </b></td>
			</tr>
		<?php } ?>
	</table>

	<div class='tt14' style='margin-top:50px; margin-left:5%; font-size:16px;'> <b><u>Rubros</u></b> </div>
	<div class='divredon' style='padding:5px; width:90%; margin-left:auto; margin-right:auto;'>
		<table class='cond' style="margin-left:auto; margin-right:auto; font-size:14px;" width='100%'>
		<tr class='border_bottom'>
			<td align='center'><b>C&oacute;digo</b></td>
			<td><b>Nombre</b></td>
			<td><b>TCalculo</b></td>
		</tr>
			<?php
				foreach ($modelComer->rubros as $key){
					echo "<tr>";
					echo "<td align='center'>".$key['rubro_id']."</td>";
					echo "<td>".$key['rubro_nom']."</td>";
					echo "<td>".utb::getCampo("v_objeto_rubro","rubro_id='".$key['rubro_id']."'","tcalculo")."</td>";
					echo "</tr>";
				}
			?>
		</table>
	</div>

	<p class='cond' align="justify" style="font-size:12px;">
		Cumple con los requisitos que exige la Ordenanza General Impositiva para el ejercicio de las actividades detalladas. Por tanto se extiende la presente Licencia Comercial INTRANSFERIBLE a favor de su titular.
		<br>
		La comprobacion de transgresiones a las leyes y/o disposiciones municipales vigentes, dara lugar a la caducidad autom&aacute;tica de &eacute;sta habilitaci&oacute;n, sin perjuicio de las acciones legales que correspondan.
	</p>
</div>
