<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ctacte\Intima */

$this->params['breadcrumbs'][] = 'Consulta de Débito';

?>
<div class="intima-view">
	<h1>Consulta de Débito</h1>
	<div class="form" style='padding:10px;'>
		<label>Datos de la Adhesión</label>
		<table width="100%">
			<tr>
				<td><label>Objeto</label></td>
				<td>
					<?= Html::input('text', 'txObj', $liq[0]['obj_id'], ['class' => 'form-control solo-lectura','id'=>'txObj','style'=>'width:40px','readonly'=>true]); ?>
					<?= Html::input('text', 'txObjNom', $liq[0]['obj_nom'], ['class' => 'form-control solo-lectura','id'=>'txObjNom','style'=>'width:200px','readonly'=>true]); ?>
				</td>				
			</tr>
			<tr>
				<td><label>SubCta</label></td>
				<td>
					<?= Html::input('text', 'txSubCta', $liq[0]['subcta'], ['class' => 'form-control solo-lectura','id'=>'txSubCta','style'=>'width:40px','readonly'=>true]); ?>
				</td>				
			</tr>
			<tr>
				<td><label>Tributo</label></td>
				<td>
					<?= Html::input('text', 'txTrib', $liq[0]['trib_id'], ['class' => 'form-control solo-lectura','id'=>'txTrib','style'=>'width:40px','readonly'=>true]); ?>
					<?= Html::input('text', 'txTribNom', $liq[0]['trib_nom'], ['class' => 'form-control solo-lectura','id'=>'txTribNom','style'=>'width:200px','readonly'=>true]); ?>
				</td>				
			</tr>
		</table>
	</div>
	<div class="form" style='padding:10px;margin-top:5px'>
		<label>Datos del Débito</label>
		<table width="100%">
			<tr>
				<td><label>Año</label></td>
				<td>
					<?= Html::input('text', 'txAnio', $liq[0]['anio'], ['class' => 'form-control solo-lectura','id'=>'txAnio','style'=>'width:40px','readonly'=>true]); ?>
					<label>Cuota</label>
					<?= Html::input('text', 'txCuota', $liq[0]['cuota'], ['class' => 'form-control solo-lectura','id'=>'txCuota','style'=>'width:40px','readonly'=>true]); ?>
					<label>Monto</label>
					<?= Html::input('text', 'txMonto', $liq[0]['monto'], ['class' => 'form-control solo-lectura','id'=>'txMonto','style'=>'width:40px','readonly'=>true]); ?>
				</td>				
			</tr>
			<tr>
				<td><label>Fecha</label></td>
				<td>
					<?= Html::input('text', 'txFechaDeb', $liq[0]['fchdeb'], ['class' => 'form-control solo-lectura','id'=>'txFechaDeb','style'=>'width:70px','readonly'=>true]); ?>
					<label>Monto Débito</label>
					<?= Html::input('text', 'txMontoDeb', $liq[0]['montodeb'], ['class' => 'form-control solo-lectura','id'=>'txMontoDeb','style'=>'width:80px','readonly'=>true]); ?>
				</td>				
			</tr>
			<tr>
				<td valign='top'><label>Obs</label></td>
				<td>
					<?= Html::textarea('txObs', $liq[0]['obs'], ['class' => 'form-control','id'=>'txObs','style'=>'width:750px;height:50px;background:#E6E6FA;resize:none','disabled'=>'true']); ?>
				</td>				
			</tr>
		</table>
	</div>
</div>
