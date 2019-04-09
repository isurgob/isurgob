<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use yii\bootstrap\Alert;
use yii\grid\GridView;
use \yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\caja\CajaTicket */
/* @var $form yii\widgets\ActiveForm */

/**
 * Recibo:
 *
 * 	$model => Modelo de "Ticket"
 *  $consulta => Indica el modo en que se debe dibujar la forma
 *  $alert => Indica un mensaje, en caso de existir el mismo
 *  $botonVolverListado => Indica si debe existir un botón "Volver", y a que action debe dirigir.
 * 		-> 0. No existe botón "Volver". No redirige a ningún action.
 * 		-> 1. Existe botón "Volver". Redirige a "listado".
 */

 if ( $botonVolverListado == 0 )
 {
 		$title = 'Consulta de Operaciones de Caja';
		$this->params['breadcrumbs'][] = 'Operaciones';
 } else
 {
 		$title = 'Consulta de Operaciones de Caja';
		$this->params['breadcrumbs'][] = ['label' => 'Opciones', 'url' => ['//caja/listadocobrocticket/index']];
		$this->params['breadcrumbs'][] = 'Operaciones';
 }



?>

<style>

.div_grilla{

	padding-bottom: 10px;
}
</style>
<div class="caja-opera-form" style="width:500px">
	<table width="100%">
		<tr>
			<td>
				<h1><?= Html::encode($title) ?></h1>

			        <?php $form = ActiveForm::begin([
									'id'=>'form-caja-opera',
									'action' => ['buscar']]); ?>
			</td>
			<td align="right">
				<?= Html::a('Volver',['//caja/listadocobrocticket/index'],['class'=>'btn btn-primary', 'style'=>( $botonVolverListado ? 'display:visible' : 'display:none')]) ?>
			</td>
		</tr>
	</table>


<div class="form-panel" style="padding-bottom:4px;margin-right:0px">
<table>
	<tr>
		<td width="70px"><label>Operación:</label></td>
		<td>
			<?= Html::input('text','opera-id',$model->opera,[
					'id'=>'opera-id',
					'class'=>'form-control text-center'. ( $botonVolverListado ? ' solo-lectura' : ''),
					'style'=>'width:80px;',
					'onkeypress'=>'return justNumbers(event)',
					'maxlength'=>9,
				]);
			?>
		</td>
		<td width="50px"></td>
		<td>
			<?= Html::Button("Aceptar",[
					'class' => 'btn btn-success' . ( $botonVolverListado ? ' disabled' : ''),
					'onclick'=>'validarClick()',
				]);
			?>
		</td>
	</tr>
</table>
</div>

<div class="form-panel" style="padding-bottom:4px;margin-right:0px">
<table border='0'>
	<tr><td><h3><b>Datos de la Operación</b></h3></td></tr>
</table>

<table>
	<tr>
		<td width="30px"><label>Caja</label></td>
		<td><?= Html::input('text', 'caja_id', $model->caja_id,['class'=>'form-control','style'=>'width:39px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
		<td><?= Html::input('text', 'caja_nom', $model->caja_nom,['class'=>'form-control','style'=>'width:174px;background-color:#E6E6FA','disabled'=>true]) ?></td>
		<td width="20px"></td>
		<td width="75px"><label>Fecha</label></td>
		<td><?= Html::input('text', 'fecha', $model->fecha,['class'=>'form-control','style'=>'width:70px;background-color:#E6E6FA','disabled'=>true]) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="70px"><label>Tesorería</label></td>
		<td><?= Html::input('text', 'teso_nom', $model->teso_nom,['class'=>'form-control','style'=>'width:175px;background-color:#E6E6FA','disabled'=>true]) ?></td>
		<td width="20px"></td>
		<td width="75px"><label>Estado</label></td>
		<td><?= Html::input('text', 'est', $model->est,['maxlength' => 1,'class'=>'form-control','style'=>'width:70px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
	</tr>
</table>


<table>
	<tr>
		<td width="70px"><label>Monto Total</label></td>
		<td><?= Html::input('text', 'monto', $model->monto,['style' => 'width:200px;','class'=>'form-control','style'=>'width:80px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
		<td width="115px"></div>
		<td width="75px"><label>Cant.Tickets</label></td>
		<td><?= Html::input('text', 'cant', $model->cant,['class'=>'form-control','style'=>'width:70px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="70px"><label>Entregado</label></td>
		<td><?= Html::input('text', 'entregado', $model->entregado,['class'=>'form-control','style'=>'width:80px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
		<td width="115px"></div>
		<td width="75px"><label>Vuelto</label></td>
		<td><?= Html::input('text', 'vuelto', $model->vuelto,['class'=>'form-control','style'=>'width:70px;background-color:#E6E6FA;text-align:center','disabled'=>true]) ?></td>
	</tr>

</table>

</div>

<div class="form-panel div_grilla" style="padding-right:10px;margin-right:0px">
<table border='0'>
	<tr><td><h3><b>Tickets</b></h3></td></tr>
</table>
<?php

	//Obtengo el ID de la operación

	$opera_id = $model->opera;

	//IINICIO Pjax Grilla Detalle de Ticket
	Pjax::begin();

		echo GridView::widget([
			'id' => 'GrilaDetalle',
			'headerRowOptions' => ['class' => 'grilla'],
			'summaryOptions' => ['class' => 'hidden'],
			'rowOptions' => ['class' => 'grilla'],
			'dataProvider' => $model->CargarDetalle(),
			'columns' => [

	            ['attribute'=>'ticket','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Ticket'],
				['attribute'=>'trib_id','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Trib'],
				['attribute'=>'obj_id','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Objeto'],
	            ['attribute'=>'subcta','contentOptions'=>['align'=>'center', 'style'=>'width:30px'],'header' => 'Cta'],
				['attribute'=>'anio','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Año'],
	            ['attribute'=>'cuota','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Cuota'],
	            ['attribute'=>'monto','contentOptions'=>['align'=>'right', 'style'=>'width:60px'],'header' => 'Monto'],
				['attribute'=>'est','contentOptions'=>['align'=>'center', 'style'=>'width:30px'],'header' => 'Est'],

				['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:20px; padding:1px 10px'],'template' => '{view}',
	            			'buttons'=>[
								'view' => function($url,$model,$key) use ( $opera_id )
	            						{
	            							return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['ticket','id' => $model['ticket'], 'opera' => 1, 'oid' => $opera_id, 'rei' => 0],
	            										['class' => 'bt-buscar-label', 'style' => 'color:#337ab7;font-size:9px', 'data-pjax' => "0"]
	            									);
	            						}
	            			]
	            	   ],
	        ],
	    ]);

	Pjax::end();
	//FIN Pjax Grilla Detalle de Ticket

?>

</div>

<div class="form-panel div_grilla" style="padding-right:10px;margin-right:0px">
<table border='0'>
	<tr><td><h3><b>Medios de Pago</b></h3></td></tr>
</table>

<?php
Pjax::begin();

echo GridView::widget([
		'id' => 'GrilaDetalle',
		'headerRowOptions' => ['class' => 'grilla'],
		'summaryOptions' => ['class' => 'hidden'],
		'rowOptions' => ['class' => 'grilla'],
		'dataProvider' => $model->CargarMDP(),
		'columns' => [

            ['attribute'=>'mdp','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Cod'],
			['attribute'=>'mdp_nom','header' => 'Nombre'],
            ['attribute'=>'cant','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Cant'],
			['attribute'=>'cotiza','contentOptions'=>['align'=>'center', 'style'=>'width:40px'],'header' => 'Cotiz'],
			['attribute'=>'monto','contentOptions'=>['align'=>'center', 'style'=>'width:60px'],'header' => 'Monto'],

        ],
    ]);

Pjax::end();

?>

</div>



	<?php

	$mensaje = '';

    if (isset($alerta))
    {
    	switch ($alerta)
    	{
    		case 0:
    			$mensaje = '';
    			break;
    		case 1:

    			$mensaje = 'No se encontró información de la Operación o la consulta no está permitida.';
    			break;

    	}
    }
    	Pjax::begin(['id'=>'error']);

    		if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];

			Alert::begin([
				'id' => 'AlertaInmuebles',
				'options' => [
    			'class' => (isset($_POST['mensaje']) ? 'alert-info' : 'alert-danger'),
    			'style' => $mensaje !== '' ? 'display:block' : 'display:none'
				],
			]);

			if ($mensaje !== '') echo $mensaje;

			Alert::end();

			if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaInmuebles').alert('close'); }, 5000)</script>";

		Pjax::end();
?>

    <script>
    function validarClick()
    {
    	if($("#opera-id").val() == '')
    	{
    		$.pjax.reload({container:"#error",method:"POST",data:{mensaje:'Ingrese un código de Operación.'}})
    	} else {

    		$("#form-caja-opera").submit();
    	}
    }

    </script>

</div>
