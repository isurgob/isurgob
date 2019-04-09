<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\grid\GridView;
use app\models\objeto\Inm;
use \yii\widgets\Pjax;
use yii\web\Session;
use app\utils\db\utb;
use yii\widgets\ActiveForm;

/*
 * Grilla que se dibuja cuando se están agregando recibos manuales.
 *
 * Debe obener los elementos de un arreglo en sesión.
 *
 *
 * Elementos: 	* Recibo
 * 				* Acta
 * 				* Fecha
 * 				* Area
 * 				* Monto
 *
 * Recibo:
 *
 * 	@param $model => Modelo de Ticket.
 * 	@param $consulta => Indica el modo en que se debe dibujar la forma
 * 			=> 1 => El formulario se dibuja en el index
 * 			=> 0 => El formulario se dibuja en el create
 * 			=> 3 => El formulario se dibuja en el update
 * 			=> 2 => El formulario se dibuja en el delete
 */

 Pjax::begin([ 'id' => 'PjaxGrillaRecibo', 'enablePushState' => false, 'enableReplaceState' => false ]);

 $session = new Session;
 $session->open();
 $arreglo = $session['arregloRecibosManual'];
 $montoTotal = 0;
 if (count($arreglo)){
	 foreach ($arreglo as $array)
	 {
		$montoTotal += (double)$array['monto'];
	 }
}

 $session->close();


 echo GridView::widget([
				    'dataProvider' => $model->obtenerRecibos($arreglo),
				    //'rowOptions' => function ($model,$key,$index,$grid) {return EventosGrilla($model);},
				    'columns' => [
			            ['attribute'=>'recibo','header' =>'Recibo','contentOptions'=>['style'=>'text-align:center']],
			            ['attribute'=>'acta','header' => 'Acta','contentOptions'=>['style'=>'text-align:center']],
			            ['attribute'=>'fecha','header' => 'Fecha','contentOptions'=>['style'=>'width:60px']],
			            ['attribute'=>'area_nom','header' => 'Área'],
			            ['attribute'=>'monto','header' => 'Monto.','contentOptions'=>['style'=>'text-align:right']],
						[
							'class' => 'yii\grid\ActionColumn',
							'contentOptions'=>['style'=>'width:6px'],
							'template' =>'{recibomanual}',
							'buttons'=>[
								'recibomanual' =>  function($url, $model, $key) use ( $consulta )
											{
												if ( $consulta == 0 || $consulta == 3 )
													return Html::Button('<span class="glyphicon glyphicon-trash"></span>', [
															'class'=>'bt-buscar-label',
															'style'=>'color:#337ab7',
															'onclick'=>'$.pjax.reload({container:"#obtenerRecibo",method:"POST",data:{id:'.$model["recibo"].',eliminar:1}})',
															]);
											},
							]
						]
						]]);
?>

<table width="100%">
	<tr>
		<td align="right">
			<label>Monto Total:</label>
			<?= Html::input('text','montoTotal',number_format($montoTotal, 2, '.', ''),[
					'id'=>'montoTotal',
					'class'=>'form-control solo-lectura',
					'style'=>'text-align:right;width:100px',
					'tabindex' => -1]) ?></td>
	</tr>
</table>

<?php

Pjax::end();

?>
