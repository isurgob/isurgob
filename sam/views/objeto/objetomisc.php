<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use \yii\widgets\Pjax;
use yii\bootstrap\Alert;

$this->title = 'Misceláneas ';
$this->params['breadcrumbs'][] = ['label' => 'Objeto: '.$id, 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = $this->title;

if (isset($consulta) == null) $consulta = 1;

?>
<div class="persona-view">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($this->title) ?></h1></td>
    	<td align='right'>
			<?= Html::button('Nueva Miscelánea', ['class' => 'btn btn-success', 'id' => 'btNuevaMisc', 'onclick' => 'btNuevaMiscClick();']) ?>
			<?= Html::a('Volver', "javascript:history.back(-1);",['id' => 'btVolver', 'class' => 'btn btn-primary']) ?>
		</td>
    </tr>
    <tr>
    	<td colspan='2'>
    	<?php

    		if (isset($mensaje) == null) $mensaje = '';
    		Alert::begin([
    			'id' => 'AlertaMisc',
				'options' => [
        		'class' => (isset($_GET['m']) and $_GET['m'] == 1) ? 'alert-success' : 'alert-info',
        		'style' => $mensaje !== '' ? 'display:block' : 'display:none'
    			],
			]);

			if ($mensaje !== '') echo $mensaje;

			Alert::end();

			if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaMisc').alert('close'); }, 5000)</script>";

    	?>
    	</td>
    </tr>
	</table>

	<p>
		<label>Objeto: </label><input class='form-control' style='width:65px;background:#E6E6FA' disabled value='<?=$id?>' />
		<label>Nombre: </label><input class='form-control' style='width:300px;background:#E6E6FA' disabled value='<?=utb::getNombObj("'".$id."'")?>' />
	</p>
	<?php
		echo GridView::widget([
			'id' => 'GrillaMisc',
			'headerRowOptions' => ['class' => 'grilla'],
			'summaryOptions' => ['class' => 'hidden'],
			'dataProvider' => $dataprovider,
			'rowOptions' => function ($model,$key,$index,$grid)
							{
								return [
									'onclick' => 'CargarControlesMisc("'.$model['orden'].'","'.$model['fecha_format'].'","'.Html::decode($model['titulo']).'","'. preg_replace("[\n|\r|\n\r]","<br>", $model['detalle']).'")',
									'class' => 'grilla',
								];
							},
			'columns' => [
            		['attribute' => 'orden', 'header' => 'Orden', 'contentOptions'=>['style'=>'width:1px; text-align: center']],
            		['attribute' => 'fecha_format', 'header' => 'Fecha', 'contentOptions'=>['style'=>'width:70px;text-align: center']],
            		['attribute' => 'titulo', 'header' => 'Título', 'contentOptions'=>['style'=>'width:150px'], 'value' => function( $data ){ return Html::decode($data['titulo']);}],
					['attribute' => 'detalle', 'header' => 'Detalle', 'contentOptions'=>['style'=>'width:150px']],
            		['attribute' => 'modif', 'header' => 'Modificación', 'contentOptions'=>['style'=>'width:150px']],

            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:20px; text-align: center'],'template' => '{update}',
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							if($model['usrmod'] == Yii::$app->user->id)
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label',
														'onclick' => 'btModifMiscClick("'.$model['orden'].'","'.$model['fecha_format'].'","'.Html::decode($model['titulo']).'","'.preg_replace("[\n|\r|\n\r]","<br>", $model['detalle']).'")'
													]
            									);
            						}
            			]
            	],
        	],
    	]);

    	$form = ActiveForm::begin(['action' => ['miscelaneas', 'id' => $id],'id'=>'frmMisc']);

    	echo Html::input('hidden', 'txAccion', null, ['id'=>'txAccion']);
    	echo Html::input('hidden', 'txOrden', null, ['id'=>'txOrden']);
	?>

<div class="form" style='padding:5px; margin-top:5px'>
	<table border='0'>
	<tr>
		<td><label>Fecha: </label></td>
		<td>
			<?= Html::input('text', 'txFecha', null, ['class' => 'form-control','id'=>'txFecha','style'=>'width:80px;background:#E6E6FA; text-align: center', 'disabled' => true]); ?>
		</td>
		<td width='10px'></td>
		<td><label>Título: </label>
			<?= Html::input('text', 'txTitulo', null, ['class' => 'form-control','id'=>'txTitulo','maxlength'=>'40','style'=>'width:550px', 'disabled' => true]); ?>
		</td>
	</tr>
	<tr>
		<td valign='top'><label>Detalle: </label></td>
		<td colspan='3'>
		<?= Html::textarea('txDetalle', null, ['class' => 'form-control','id'=>'txDetalle','maxlength'=>'500','style'=>'width:680px;height:100px; max-width:680px; max-height:150px;', 'disabled' => true]); ?>
		</td>
	</tr>
	</table>

	</div>
</div>

<?php

Pjax::begin(['id' => 'btMisc', 'options' => ['style' => 'margin-top:5px;']]);

if (isset($_POST['consulta'])) $consulta = $_POST['consulta'];

if ($consulta !== 1)
{
	echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick' => 'btGrabarClick()']);
	echo "&nbsp;&nbsp;";
	echo Html::a('Cancelar', ['miscelaneas', 'id' => $id], [
    			'class' => 'btn btn-primary', 'data-pjax' => 'false'
    			]);
	}
	Pjax::end();
?>

<?php
Pjax::begin(['id' => 'divError', 'options' => ['style' => 'margin-top:5px;']]);

if (isset($_POST['err'])) $error = $_POST['err'];

if(isset($error) and $error !== '')
{
	echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
	}
	Pjax::end();

	ActiveForm::end();

?>

<script>

function btNuevaMiscClick()
{
	$("#txAccion").val("0");
	$("#txFecha").val("");
	$("#txTitulo").val("");
	$("#txDetalle").val("");

	ActivarControlesMisc(0);

	$.pjax.reload(
		{
			container:"#btMisc",
			data:{
					consulta:0
				},
			method:"POST"
		});
}

function btModifMiscClick(orden,fecha,titulo,detalle)
{
	$("#txAccion").val("3");
	$("#txOrden").val(orden);
	$("#txFecha").val(fecha);
	$("#txTitulo").val(decodeURIComponent(titulo));
	$("#txDetalle").val(decodeURIComponent(detalle));

	ActivarControlesMisc(3);

	$.pjax.reload(
		{
			container:"#btMisc",
			data:{
					consulta:3
				},
			method:"POST"
		});
}

function btGrabarClick()
{
	error = "";

	if ($("#txTitulo").val()=="")
	{
		error += "<li>Ingrese un Título</li>";
	}
	if ($("#txDetalle").val()=="")
	{
		error += "<li>Ingrese un Detalle</li>";
	}

	if (error == "")
	{
		$("#frmMisc").submit();
	}else {
		$.pjax.reload(
		{
			container:"#divError",
			data:{
					err:error
				},
			method:"POST"
		});
	}
}

function CargarControlesMisc(orden,fecha,titulo,detalle)
{
	$("#txOrden").val(orden);
	$("#txFecha").val(fecha);
	$("#txTitulo").val(decodeURIComponent(titulo));
	detalle = detalle.replace("<br>", '\n');
	detalle = detalle.replace("<br>", "");
	$("#txDetalle").val(detalle);
}

function ActivarControlesMisc(accion)
{
	$("#txTitulo").prop("disabled",(accion==1));
	$("#txDetalle").prop("disabled",(accion==1));
	$("#btNuevaMisc").prop("disabled",(accion!==1));

	if (accion !== 1)
	{
		$("#GrillaMisc").css("pointer-events", "none");
		$("#GrillaMisc Button").css("color", "#ccc");
	}else {
		$("#GrillaMisc").css("pointer-events", "all");
		$("#GrillaMisc Button").css("color", "#337ab7");
	}

}

</script>

<?= "<script>ActivarControlesMisc(".$consulta.")</script>"; ?>
