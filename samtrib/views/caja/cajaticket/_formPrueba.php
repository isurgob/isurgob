<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use yii\jui\DatePicker;
use app\utils\db\utb;
use app\utils\db\Fecha;

/* @var $this yii\web\View */
/* @var $model app\models\caja\CajaTicket */
/* @var $form yii\widgets\ActiveForm */


$title = 'Prueba de Caja';
$this->params['breadcrumbs'][] = $title;

if ($model->fechaPago == '') $model->fechaPago = date('d') . '/' . date('m') . '/' . date('Y');

?>

<div class="caja-prueba-form">

	<h1><?= Html::encode($title) ?></h1>

    <?php $form = ActiveForm::begin([
					'id'=>'form-caja-prueba',
					'action' => ['prueba']]); ?>

<style>
#cajaPrueba-codBarra {
	height:30px;
	font-size:18px;
	font-weight:bold;

}

h6 {

    margin-top: 0px;
    margin-bottom: 0px;
    padding-left:3px;


}
</style>

<div style="width:500px">
	<div class="form-panel">
		<table width="100%" >
			<tr>
				<td align="right" style="padding-right:20px">
					<label>Fecha de Consolidación:</label>
					<?=  DatePicker::widget(['id' => 'cajaPrueba-fchpago','name' => 'cajaPrueba-fchpago','dateFormat' => 'dd/MM/yyyy','options' => ['class' => 'form-control', 'style' => 'width:80px', 'disabled'=> ($grilla ? true : false)],'value' => Fecha::usuarioToDatePicker($model->fechaPago),]);	?>
				</td>
			<tr>
		</table>

		<table  width="100%" >
			<tr>
				<td style="padding-right:20px">
					<h6>Ingrese Cod. de Barra o Nro.Ref</h6>
					<?= Html::input('text','cajaPrueba-codBarra', $model->codBarra, ['id'=>'cajaPrueba-codBarra','class'=>'form-control','style'=>'width:100%; margin-bottom:6px; text-align:center','maxlength'=>'42', 'disabled'=> ($grilla ? true : false)]) ?>
				</td>
			</tr>
		</table>
		<?php
			echo Html::SubmitButton("<i class='glyphicon glyphicon-search'><b>   Buscar</b></i>",['class' => 'bt-buscar', 'style'=>'display:none']);

			ActiveForm::end();
			?>

	</div>

	<div class="form-panel"  style="padding-bottom:10px">
		<table border='0'>
			<tr><td><h3><b>Datos del Comprobante</b></h3></td></tr>
		</table>

		<table>
			<tr>
				<td width="70px"><label>Tipo Objeto</label></td>
				<td><?= Html::input('text','tipo-objeto', ($model->obj_id != null ? utb::getTObjNom($model->obj_id) : null),['id'=>'tipo-objeto','class'=>'form-control','style'=>'width:140px;background-color:#E6E6FA','disabled'=>true]) ?></td>
				<td width="77px"></td>
				<td width="40px"><label>Objeto</label></td>
				<td><?= Html::input('text','cajaPrueba-obj_id', $model->obj_id,['id'=>'cajaPrueba-obj_id','class'=>'form-control','style'=>'width:70px;background-color:#E6E6FA','disabled'=>true]) ?></td>
				<td><?= Html::input('text','cajaPrueba-subcta', $model->subcta,['id'=>'cajaPrueba-subcta','class'=>'form-control','style'=>'width:30px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
			</tr>
		</table>

		<table>
			<tr>
				<td width="70px"><label>Contrib</label></td>
					<td><?= Html::input('text','num-contrib', $model->num,['id'=>'num-contrib','class'=>'form-control','style'=>'width:70px;background-color:#E6E6FA','disabled'=>true]) ?></td>
				<td><?= Html::input('text','nombre-contrib', ($model->num != null ? utb::getNombObj($model->num,false) : null),['id'=>'nombre-contrib','class'=>'form-control','style'=>'width:290px;background-color:#E6E6FA','disabled'=>true]) ?></td>
			</tr>
		</table>

		<table>
			<tr>
				<td width="70px"><label>Tributo</label></td>
				<td><?= Html::input('text','cajaPrueba-trib_id', $model->trib_id,['id'=>'cajaPrueba-trib_id','class'=>'form-control','style'=>'width:70px;background-color:#E6E6FA; text-align:center','disabled'=>true]) ?></td>
				<td><?= Html::input('text','cajaPrueba-trib_nom', $model->trib_nom,['id'=>'cajaPrueba-trib_nom','class'=>'form-control','style'=>'width:290px;background-color:#E6E6FA','disabled'=>true]) ?></td>
			</tr>
		</table>

		<table>
			<tr>
				<td width="70px"><label>Año</label></td>
				<td><?= Html::input('text','cajaPrueba-anio', $model->anio,['id'=>'cajaPrueba-anio','class'=>'form-control','style'=>'width:70px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
				<td width="35px"></td>
				<td width="30px"><label>Cuota</label></td>
				<td><?= Html::input('text','cajaPrueba-cuota', $model->cuota,['id'=>'cajaPrueba-cuota','class'=>'form-control','style'=>'width:50px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
				<td width="35px"></td>
				<td width="30px"><label>Fch.Venc</label></td>
				<td><?= Html::input('text','cajaPrueba-fchvenc', ($model->fchvenc != '' ? Fecha::bdToUsuario($model->fchvenc) : ''),['id'=>'cajaPrueba-fchvenc','class'=>'form-control','style'=>'width:80px;background-color:#E6E6FA','disabled'=>true]) ?></td>
			</tr>
		</table>

	</div>

	<div class="form-panel" style="padding-right:12px;padding-bottom: 8px">
		<table border='0'>
			<tr><td><h3><b>Detalle</b></h3></td></tr>
		</table>
			<?php

			$total = 0;

			if ($grilla)
			{

			Pjax::begin();


				echo GridView::widget([
						'id' => 'GrilaDetalle',
						'headerRowOptions' => ['class' => 'grilla'],
						'summaryOptions' => ['class' => 'hidden'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $model->cargarDetalle(),
						'columns' => [

				            ['attribute'=>'cta_id','contentOptions'=>['align'=>'center', 'style'=>'width:40px;text-align:right'],'header' => 'NºCta'],
							['attribute'=>'cta_nom','header' => 'Cuenta'],
							['attribute'=>'monto','contentOptions'=>['align'=>'center', 'style'=>'width:60px;text-align:right'],'header' => 'Monto'],

				        ],
				    ]);

			Pjax::end();

			$total = $model->sumarMonto();

			}



		?>

	</div>

	<div class="form-panel" style="padding-right:12px;padding-bottom:5px">
	<table>
		<tr>
			<td>
				<label>Total a Pagar:</label><?= Html::input('text','cajaPrueba-totalPago',number_format($total, 2, '.', ''),['id'=>'cajaPrueba-totalPago','class'=>'form-control','style'=>'width:100px;text-align:right;background-color:#E6E6FA','disabled'=>true]) ?>
			</td>
			<td width="50px"></td>
			<td><?= Html::a('Volver',['prueba'],['id'=>'botonVolver','class'=>'btn btn-success', 'style'=>($grilla ? 'display:visible' : 'display:none')]) ?></td>
		</tr>
	</table>
	</div>

<?php
//INICIO Alert Verifica Débito
if ($model->validaDebito)
{
	Alert::begin([
		'id' => 'AlertaInmuebles',
		'options' => [
		'class' => 'alert-info',
		'style' => 'display:block'
		],
	]);

	echo 'El período se encuetra adherido a Débito Automático';

	Alert::end();

}

//FIN Alert Verifica Débito


	$mensaje = '';

    if (isset($alerta)) $mensaje = $alerta;

	Pjax::begin(['id'=>'error']);

		if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

		Alert::begin([
			'id' => 'AlertaCajaPrueba',
			'options' => [
			'class' => (isset($_POST['mensaje']) ? 'alert-info' : 'alert-danger'),
			'style' => $mensaje !== '' ? 'display:block' : 'display:none'
			],
		]);

		if ($mensaje !== '') echo $mensaje;

		Alert::end();

		if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaCajaPrueba').alert('close'); }, 5000)</script>";

	Pjax::end();


?>
</div>


<?php



	?>
</div>
