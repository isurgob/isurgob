<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\Session;
use app\models\caja\CajaTicket;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use app\utils\db\Fecha;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
/*
 *
 * View que se encarga de listar las operaciones a anular por un supervisor.
 *
 * Recibo:
 * 		$supervisor => Nombre del supervisor
 */


$title = 'Anular';
$this->params['breadcrumbs'][] = 'Supervisión';
$this->params['breadcrumbs'][] = $title;


?>
<div class="anulacion-view">
	<table width="100%">
		<tr>
			<td><h1><?= Html::encode($title) ?></h1></td>
		</tr>
	</table>
</div>

<?php


$form = ActiveForm::begin([
		'id'=>'form-anulacion',
		'action' => ['anula'],
		]);

Pjax::begin(['id'=>'errorAnulacion']);

	if (isset($_POST['m'])) $m = $_POST['m'];
	if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

	Alert::begin([
		'id' => 'AlertaAnulacion',
		'options' => [
		'class' => ($m == 1 ? 'alert-info' : 'alert-danger'),
		'style' => ($m != 0 ? 'display:block' : 'display:none')
		],
	]);

	if ($mensaje !== '') echo $mensaje;

	Alert::end();

	if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaAnulacion').alert('close'); }, 5000)</script>";

Pjax::end();



?>

<table>
	<tr>
		<td>
		<div>
<div class="form-panel" style="padding-bottom:4px">
<table>
	<tr>
		<td width="80px"><label>Supervisor</label></td>
		<td width="150px"><?= Html::input('text','anulacion-nombreSupervisor', $supervisor, ['id'=>'anulacion-nombreSupervisor', 'style'=>'width:230px;background-color:#E6E6FA', 'class' => 'form-control', 'disabled'=>true]); ?></td>
	</tr>
</table>
</div>

<div style="margin-right:15px">
<?php
	Pjax::begin(['id'=>'datosGrilla']);

		echo GridView::widget([
				'id' => 'GrillaDetalle',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $model->getPedidosAnulacion(),
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
		            ['content'=> function($model, $key, $index, $column) {return Html::checkbox('listaAnulacion[]',false, [
												'value'=>$model['comprob'] . "-" . $model['tipo'],
												'style' => 'width:11px;height:11px;margin:0px']);},'contentOptions'=>['style'=>'width:4px'],],
		            ['attribute'=>'tipo','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Tipo'],
					['attribute'=>'comprob','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Comprob'],
					['attribute'=>'caja_id','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Caja'],
					['attribute'=>'cajero','contentOptions'=>['align'=>'left', 'style'=>'width:150px'],'header' => 'Cajero'],
					['attribute'=>'fchpedido','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'FchPedido','format'=>['date','php:d/m/Y']],
					['attribute'=>'monto','contentOptions'=>['align'=>'right', 'style'=>'width:60px'],'header' => 'Monto'],
					['attribute'=>'motivo','contentOptions'=>['align'=>'center', 'style'=>'width:260px;text-align:left'],'header' => 'Motivo'],

		        ],
		    ]); 

	Pjax::end();

 ?>
</div>
<table style="margin-top:8px">
	<tr>
		<td><?= Html::Button('Confirmar',['class'=>'btn btn-primary','onclick'=>'enviarDatos(2)']); ?></td>
		<td width="20px"></td>
		<td><?= Html::Button('Rechazar',['class'=>'btn btn-danger', 'onclick' => 'enviarDatos(1)']); ?></td>
	</tr>
</table>

<?= Html::input('text','confRech',0,['id'=>'confRech','style'=>'display:none']); ?>

<?php

	ActiveForm::end();

?>
</div>
		</td>
		<td width="20%" valign="top">
			<div class="menu_derecho">
			<?php

			echo $this->render('_supervision_menu_derecho');

			?>
			</div>
		</td>
	</tr>
</table>
<script>
function enviarDatos(cod){
	$("#confRech").val(cod);

	var codigos = 0;
 	$("input[type=checkbox]:checked").each(function(){

		codigos = codigos + 1;
	});

	if (codigos > 0)
 	{
 		$("#form-anulacion").submit();
 	}
}
</script>
