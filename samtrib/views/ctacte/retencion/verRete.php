<?php

use yii\helpers\Html;

?>

<!-- INICIO Div Principal -->
<div id="usuarioweb_verRete_divPrincipal">

    <div id="formDetalle">

		<table border="0" width='100%'>
			<tr>
				<td width="5%"><label>Agente:</label></td>
				<td colspan='2'>
					<?=
						Html::label( $model->ag_rete, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:97%;text-align: center',
						]);
					?>
				</td>
				<td>
					<?=
						Html::label( $model->ag_nom, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:100%;text-align: left',
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><label>Periodo:</label></td>
				<td width='10%'>
					<?=
						Html::label( $model->anio, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:90%;text-align: center',
						]);
					?>
				</td>
				<td width='10%'>
					<?=
						Html::label( $model->mes, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:93%;text-align: center',
						]);
					?>
				</td>
				<td align='right'>
					<label>Estado:</label>
					<?=
						Html::label( $model->est_nom, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:50%;text-align: center',
						]);
					?>
				</td>
			</tr>
		</table>
		
		<hr>
		
		<table width='100%'>
			<tr>
				<td width='5%'><label>Cuit:</label></td>
				<td width='20%'>
					<?=
						Html::label( $model->cuit, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:95%;text-align: center',
						]);
					?>
				</td>
				<td width='20%'></td>
			</tr>
			<tr>
				<td><label>Objeto:</label></td>
				<td>
					<?=
						Html::label( $model->obj_id, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:95%;text-align: center',
						]);
					?>
				</td>
				<td colspan='5'>
					<?=
						Html::label( $model->nombre, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:100%;text-align: left',
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><label>Lugar:</label></td>
				<td colspan='2'>
					<?=
						Html::label( $model->lugar, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:95%;text-align: left',
						]);
					?>
				</td>
				<td width='5%'><label>Fecha:</label></td>
				<td>
					<?=
						Html::label( $model->fecha, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:95%;text-align: center',
						]);
					?>
				</td>
				<td width='1%'><label>Nº:</label></td>
				<td width='15%'>
					<?=
						Html::label( $model->numero, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:100%;text-align: center',
						]);
					?>
				</td>
			</tr>
			<tr>
				<td colspan='3'><label>Comprobante:</label></td>
				<td><label>Tipo:</label></td>
				<td>
					<?=
						Html::label( $model->tcomprob_nom, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:95%;text-align: left',
						]);
					?>
				</td>
				<td><label>Nº:</label></td>
				<td>
					<?=
						Html::label( $model->comprob, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:100%;text-align: center',
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><label>Base:</label></td>
				<td>
					<?=
						Html::label( $model->base, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:95%;text-align: right',
						]);
					?>
				</td>
				<td></td>
				<td><label>Alícuota:</label></td>
				<td>
					<?=
						Html::label( $model->ali, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:95%;text-align: right',
						]);
					?>
				</td>
				<td><label>Monto:</label></td>
				<td>
					<?=
						Html::label( $model->monto, null, [
							'class' => 'form-control solo-lectura',
							'style' => 'width:100%;text-align: right',
						]);
					?>
				</td>
			</tr>
		</table>
	
	</div>
	
</div>