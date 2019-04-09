<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;

/**
 * Forma que se dibuja cuando se llega a Compensaciones
 * Recibo:
 * 			=> $model -> Modelo de Débito
 */

/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */

?>
<style>

.div_grilla
{
	width: 500px;
	margin-left:50px;
	margin-top: 5px;
	margin-bottom: 5px;
}

</style>

<div class="compensacion_info">
<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorCompensa']);

			$mensaje = '';

			$mensaje = Yii::$app->request->post('mensaje','');
			$m = Yii::$app->request->post('m',2);


			if($mensaje != "")
			{

		    	Alert::begin([
		    		'id' => 'AlertaMensajeCompensa',
					'options' => [
		        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
		    		],
				]);

				echo $mensaje;

				Alert::end();

				echo "<script>window.setTimeout(function() { $('#AlertaMensajeCompensa').alert('close'); }, 5000)</script>";
			 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->
<div class="form-panel" style="padding-right:8px">
<table width="100%">
	<tr>
		<td width="50px"><label>Código:</label></td>
		<td><?= Html::input('text','txCodigo',$model->comp_id,['id'=>'compensacion_txCodigo','class'=>'form-control','style'=>'width:100%;text-align:center','readOnly'=>true]) ?></td>
		<td width="50px" style="padding-left:35px"><label>Tipo:</label></td>
		<td colspan="3"><?= Html::input('text','txTipo',$model->tipo_nom,['id'=>'compensacion_txTipo','class'=>'form-control','style'=>'width:100%;text-align:left','readOnly'=>true]) ?></td>
		<td width="15px"></td>
		<td width="50px"><label>Expe:</label></td>
		<td><?= Html::input('text','txExpe',$model->expe,['id'=>'compensacion_txExpe','class'=>'form-control','style'=>'width:100%;text-align:left','readOnly'=>true]) ?></td>
		<td width="15px"></td>
		<td width="70px"><label>Estado:</label></td>
		<td><?= Html::input('text','txEstado',$model->est_nom,['id'=>'compensacion_txEstado','class'=>($model->est == 'B' ? 'form-control baja' : 'form-control solo-lectura'),'style'=>'width:100%;text-align:center','readOnly'=>true]) ?></td>
	</tr>

	<tr>
		<td width="50px"><label>Monto:</label></td>
		<td width="50px"><label>Origen:</label></td>
		<td width="75px"><?= Html::input('text','txOrigen',$model->monto,['id'=>'compensacion_txOrigen','class'=>'form-control','style'=>'width:100%;text-align:right','readOnly'=>true]) ?></td>
		<td width="15px"></td>
		<td width="60px"><label>Aplicado:</label></td>
		<td><?= Html::input('text','txAplicado',$model->monto_aplic,['id'=>'compensacion_txAplicado','class'=>'form-control','style'=>'width:100%;text-align:right','readOnly'=>true]) ?></td>
		<td width="15px"></td>
		<td width="50px"><label>Saldo:</label></td>
		<td><?= Html::input('text','txSaldo',$model->saldo,['id'=>'compensacion_txSaldo','class'=>'form-control','style'=>'width:100%;text-align:right','readOnly'=>true]) ?></td>
	</tr>
	<tr>
		<td width="50px"><label>Fecha:</label></td>
		<td width="50px"><label>Alta:</label></td>
		<td><?= Html::input('text','txFchAlta',$model->fchalta,['id'=>'compensacion_txFchAlta','class'=>'form-control','style'=>'width:100%;text-align:center','readOnly'=>true]) ?></td>
		<td width="15px"></td>
		<td width="60px"><label>Baja:</label></td>
		<td><?= Html::input('text','txFchBaja',$model->fchbaja,['id'=>'compensacion_txFchBaja','class'=>'form-control','style'=>'width:100%;text-align:center','readOnly'=>true]) ?></td>
		<td width="15px"></td>
		<td width="50px"><label>Modif:</label></td>
		<td><?= Html::input('text','txFchModif',$model->fchmod,['id'=>'compensacion_txFchModif','class'=>'form-control','style'=>'width:100%;text-align:center','readOnly'=>true]) ?></td>
		<td width="15px"></td>
		<td width="70px"><label>Consolida:</label></td>
		<td><?= Html::input('text','txFchConsolida',$model->fchconsolida,['id'=>'compensacion_txFchConsolida','class'=>'form-control','style'=>'width:100%;text-align:center','readOnly'=>true]) ?></td>
	</tr>
</table>
</div>

<div class="form-panel" style="padding-right:8px">
<table>
	<tr>
		<td width="40px"><label>Obs:</label></td>
		<td><?= Html::textarea('txObs',$model->obs,['id'=>'compensacion_txObs','class'=>'form-control','style'=>'width:595px;max-width:595px;height:40px;max-height:120px','readOnly'=>true]) ?> </td>
	</tr>
</table>
</div>

<?php
if ($model->tipo == 3 || $model->tipo == 4)
{
?>
<!-- INICIO Grilla Origen -->
<div class="form-panel" style="padding-right:8px" id="grilla_origen">
<?php
Pjax::begin(['id'=>'PjaxGrillaOrigen']);

?>

<table>
	<tr>
		<td width="60px"><label>Origen:</label></td>
		<td width="40px"><label>Tributo:</label></td>
		<td><?= Html::input('text','txOrigenTributo',$model->trib_ori_nom,['id'=>'compensacion_txObs','class'=>'form-control','style'=>'width:150px','readOnly'=>true]) ?> </td>
		<td width="20px"></td>
		<td width="40px"><label>Objeto:</label></td>
		<td><?= Html::input('text','txOrigenObjCod',$model->obj_ori,['id'=>'compensacion_txObs','class'=>'form-control','style'=>'width:70px;text-align:center','readOnly'=>true]) ?> </td>
		<td><?= Html::input('text','txOrigenObjNom',$model->obj_ori_nom,['id'=>'compensacion_txObs','class'=>'form-control','style'=>'width:240px','readOnly'=>true]) ?> </td>
	</tr>
</table>

<div class="div_grilla">
<?php
	echo GridView::widget([
		'id' => 'GrillaCompensacionOrigen',
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => ['class' => 'grilla'],
		'dataProvider' => $dataProviderOrigen,
		'summaryOptions' => ['class' => 'hidden'],
		'columns' => [
				['attribute'=>'trib_nom','header' => 'Tributo', 'contentOptions'=>['style'=>'text-align:left','width'=>'140px']],
				['attribute'=>'obj_id','header' => 'Ojeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'100px']],
				['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'60px']],
				['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'60px']],
				['attribute'=>'saldo','header' => 'Saldo', 'contentOptions'=>['style'=>'text-align:right','width'=>'90px']],
				['attribute'=>'saldo_cub','header' => 'Cubierto', 'contentOptions'=>['style'=>'text-align:right','width'=>'90px']],
        	],
	]);

Pjax::end();
?>
</div>
</div>
<!-- FIN Grilla Origen -->

<?php
}
?>
<!-- INICIO Grilla Destino -->
<div class="form-panel" style="padding-right:8px" id="grilla_destino">
<?php
Pjax::begin(['id'=>'PjaxGrillaOrigen']);

?>

<table>
	<tr>
		<td width="60px"><label>Destino:</label></td>
		<td width="40px"><label>Tributo:</label></td>
		<td><?= Html::input('text','txDestinoTributo',$model->trib_dest_nom,['id'=>'compensacion_txObs','class'=>'form-control','style'=>'width:150px','readOnly'=>true]) ?> </td>
		<td width="20px"></td>
		<td width="40px"><label>Objeto:</label></td>
		<td><?= Html::input('text','txDestinoObjCod',$model->obj_dest,['id'=>'compensacion_txObs','class'=>'form-control','style'=>'width:70px;text-align:center','readOnly'=>true]) ?> </td>
		<td><?= Html::input('text','txDestinoObjNom',$model->obj_dest_nom,['id'=>'compensacion_txObs','class'=>'form-control','style'=>'width:240px','readOnly'=>true]) ?> </td>
	</tr>
</table>
<div class="div_grilla">
<?php

	echo GridView::widget([
		'id' => 'GrillaCompensacionDestino',
		'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => ['class' => 'grilla'],
		'dataProvider' => $dataProviderDestino,
		'summaryOptions' => ['class' => 'hidden'],
		'columns' => [
				['attribute'=>'trib_nom','header' => 'Tributo', 'contentOptions'=>['style'=>'text-align:left','width'=>'140px']],
				['attribute'=>'obj_id','header' => 'Ojeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'100px']],
				['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'60px']],
				['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'60px']],
				['attribute'=>'montoaplic','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'90px']],
				['attribute'=>'fecha','header' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center','width'=>'90px']],
        	],
	]);
Pjax::end();

?>

</div>

<?php

if (!($model->est == 'A' || $model->est == 'B'))
{
	//INICIO Modal eliminar Lote
	Modal::begin([
		'id' => 'ModalEliminarCompensa',
		'size' => 'modal-sm',
		'header' => '<h4><b>Confirmar Eliminación</b></h4>',
		'closeButton' => [
			'label' => '<b>X</b>',
			'class' => 'btn btn-danger btn-sm pull-right',
			'id' => 'btCancelarModalElim'
			],
	]);

	echo "<center>";
	echo "<p><label>¿Esta seguro que desea eliminar la compensación?</label></p><br>";

	echo Html::a('Aceptar', ['delete','id' => $id],['class' => 'btn btn-success',]);

	echo "&nbsp;&nbsp;";
	echo Html::Button('Cancelar', ['id'=>'btEliminarCanc', 'class' => 'btn btn-primary', 'onclick'=>'$("#ModalEliminarCompensa").modal("hide")']);
	echo "</center>";

	Modal::end();
	//FIN Modal eliminar Lote
}

?>
</div>
<!-- FIN Grilla Destino -->

</div>
