<?php
/**
 * Forma que se dibuja como ventana modal.
 * Se encarga de la cargar los cheques cartera
 * 
 */
 
use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use app\utils\db\Fecha;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use yii\web\Session;
use yii\data\ArrayDataProvider;
use app\models\caja\CajaCobro;

$model = new CajaCobro();

Pjax::begin([ 'id' => 'PjaxFormChequeCartera', 'enableReplaceState' => false, 'enablePushState' => false ]);

	$tipo = Yii::$app->request->post( 'tipo', '' );
	
	echo Html::input('hidden','txTipo',$tipo,['id'=>'chequeCartera_txTipo']);

?>

<div class="form_anulacion">

<div class="form" style="padding: 8px;margin-right:0px">

<table>
	<tr>
		<td><label>Plan:</label><td>
		<td>
			<?= Html::input('text','txPlan','',[
					'id'=>'chequeCartera_txPlan',
					'class'=>'form-control',
					'style'=>'width:80px;text-align:center;font-weight:bold;',
					'onkeypress' => 'return justNumbers( event )',
				]);
			?>
		</td>
		<td width="50px"></td>
		<td>
			<?= Html::button('Buscar',[
					'id' => 'chequeCartera_btBuscar',
					'class' => 'btn btn-primary',
					'onclick' => 'f_buscarCheque()',
				]);
			?>
		</td>
		
	</tr>
</table>

</div>

<!-- Grilla -->
<div id="chequeCartera_grilla" style="margin-top:8px">

<?php
	
	Pjax::begin([ 'id' => 'PjaxChequeCartera', 'enablePushState' => false, 'enableReplaceState' => false ]);
	
		$plan = Yii::$app->request->get( 'plan', 0 );
		
		if ( $plan != 0 )
		{
			
			//Obtener el plan
			$dataProvider = $model->getChequeCartera( $plan );
			
			echo GridView::widget([
				'id' => 'GrillaChequeCartera',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => function ($model) { return ['class' => 'grilla','onclick' => 'marcarFilaGrilla( "#GrillaChequeCartera", $(this) )','ondblClick' => 'f_agregaCheque('.$model['nrocheque'].','.$model['monto'].')'];},
				'dataProvider' => new ArrayDataProvider(['allModels' => $dataProvider, 'key' => 'id']),
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['attribute'=>'nrocheque','header' => 'Cheque', 'contentOptions'=>['style'=>'text-align:let','width'=>'20px']],
						['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'40px']],
						['attribute'=>'bco_ent','header' => 'Ent.', 'contentOptions'=>['style'=>'text-align:left','width'=>'20px']],
						['attribute'=>'bco_ent_nom','header' => 'Nombre Ent.', 'contentOptions'=>['style'=>'text-align:left','width'=>'300px']],
						['attribute'=>'bco_suc','header' => 'Ent.', 'contentOptions'=>['style'=>'text-align:left','width'=>'20px']],
						['attribute'=>'titular','header' => 'Titular.', 'contentOptions'=>['style'=>'text-align:left','width'=>'200px']],
						['attribute'=>'plan_id','header' => 'Plan', 'contentOptions'=>['style'=>'text-align:center','width'=>'40px']],
		
		        	],
			]);
		}
			
		
	Pjax::end();

?>

</div>

</div>
<?php

Pjax::end();

Pjax::begin(['id' => 'PjaxAgregaChequeCartera', 'enablePushState' => false, 'enableReplaceState' => false ]);

	$cheque = Yii::$app->request->get( 'cheque', 0 );
	$monto = Yii::$app->request->get( 'monto', 0 );
	$tipo = Yii::$app->request->get( 'tipo', 0 );
	
	//Cargar medio de pago
	if ( $cheque != 0 )
	{
		$res = $model->agregoMdp($tipo,13,$monto,$cheque);

		if ( $res['return'] == 1 )
		{
			if ( $tipo == 1 )
				echo '<script>$.pjax.reload({container:"#PjaxGrillaIMdp",method:"POST"});</script>';
			else 
				echo '<script>$.pjax.reload({container:"#PjaxGrillaEMdp",method:"POST"});</script>';
		}
		else 
		{
			if ( $tipo == 2 )
				echo '<script>$("#cajaCobro_txTotalEgreso").val("'.$model->opera_sobrante.'")</script>';
				
			echo '<script>$.pjax.reload({container:"#errorCajaCobro",method:"POST",data:{mensaje:"'.$res['mensaje'].'"}});</script>';
		}
		
		echo '<script>$("#ModalChequeCartera").modal("hide");</script>';
	}

Pjax::end();

?>



<script>

function f_buscarCheque()
{
	var plan = $("#chequeCartera_txPlan").val();
	
	$.pjax.reload({
		container: "#PjaxChequeCartera",
		type: "GET",
		replace: false,
		push: false,
		data:{
			plan: plan,
		}
	});
			
}

function f_agregaCheque( cheque, monto )
{
	var tipo = $("#chequeCartera_txTipo").val();
	
	$.pjax.reload({
		container: "#PjaxAgregaChequeCartera",
		type: "GET",
		replace: false,
		push: false,
		data:{
			cheque: cheque,
			monto: parseFloat( monto ),
			tipo: tipo,
		}
	});
}
</script>