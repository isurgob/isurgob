<?php

/**
 * Vista que se mostrará como Ventana Modal cuando se ingrese a "Mdp Especial".
 */
 
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;
use yii\web\Session;
use yii\data\ArrayDataProvider;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\bootstrap\Alert;
use app\models\estad\Caja;


$title= 'Medios de pagos especiales';

$this->params['breadcrumbs'][]= ['label' => 'Caja Cobro', 'url' => ['view']];
$this->params['breadcrumbs'][]= $title;
?>
 
 <h1><b><?= $title; ?></b></h1>
 <div class="separador-horizontal"></div>
 	<!-- INICIO DIV Caja MDPEspecial -->
 	<div id="CajaMDPEspecial">
 	
 	<div class="form-panel"  style="padding-bottom:5px">
 	
  	<table>
 		<tr>
 			<td><label>Caja:</label></td>
 			<td>
 				<?= Html::input('text','txCaja',$model->caja_caja_id,[
						'id'=>'CajaMDPEspecial_txCaja',
						'class'=>'form-control' . ( $model->caja_caja_id != "" && $model->caja_caja_id != 0 ? ' solo-lectura' : ''),
						'style'=>'width:50px;text-align:center',
						'onkeypress' => 'return justNumbers(event)',
					]);
				 ?>
			</td>
 			<td width="20px"></td>
 			<td><label>Fecha:</label></td>
 			<td><?= DatePicker::widget([
							'id' => 'CajaMDPEspecial_txFecha',
							'name' => 'txFch',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => [
								'class' => 'form-control' . ( $model->caja_caja_id != "" && $model->caja_caja_id != 0 ? ' solo-lectura' : ''),
								'style' => 'width:80px;text-align:center',
							],
							'value' => $model->caja_fecha != '' ? Fecha::usuarioToDatePicker($model->caja_fecha) : '',
						]);	
			?></td>
 			<td width="20px"></td>
 			
 			<?php
 				
 				if ( !( $model->caja_caja_id != "" && $model->caja_caja_id != 0 ))
 				{
 			?>
	 			<td>
	 				<?= Html::button('Cargar',[
							'id'=>'CajaMDPEspecial_btCargar',
							'class'=>'btn btn-primary',
							'onclick' => 'f_cajaMDPEspecial_btCargar()'
						]);
					 ?>
				</td>
				<td width="20px"></td>
			<?php
				
 				}
 			
 			?>
 			<td><?= Html::a('Imprimir',['imprimirmdpe'],['id'=>'PjaxImprimirMDPE','class'=>'btn btn-success','onclick'=>'imprimirMDPE()']); ?></td>
 			<td width="20px"></td>
 			<td><?= Html::button('Exportar',['id'=>'CajaMDPEspecial_btExportar','class'=>'btn btn-success']); ?></td>
 			
 		</tr>
 	</table>	
 	
 	</div>
 	
 	<!-- INICIO DIV Grilla-->
 	<div id ="divGrillaListaMDPE" style="padding-right:15px">
 	
 	<?php
 	
 		Pjax::begin(['id'=>'PjaxCargarGrillaMDPE']);
 			
 			$caja_id = Yii::$app->request->post('grillaMDPE_caja',$model->caja_caja_id);
 			$fecha = Yii::$app->request->post('grillaMDPE_fecha',$model->caja_fecha);
 			
 			$dataProvider = new ArrayDataProvider(['allModels' => []]);
 			
 			if ($caja_id != '' && $fecha != '')
 			{
 				$dataProvider = $model->getMDPE($caja_id,$fecha);
 			}
 				
 				echo GridView::widget([
						'id' => 'GrillaMDPEspecial',
						'headerRowOptions' => ['class' => 'grilla'],
						'rowOptions' => ['class' => 'grilla'],
						'dataProvider' => $dataProvider,
						'summaryOptions' => ['class' => 'hidden'],
						'columns' => [
								['attribute'=>'opera','header' => 'Nº Opera', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'mdp','header' => 'MDP', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'mdp_nom','header' => 'MDP Nom.', 'contentOptions'=>['style'=>'text-align:left','width'=>'100px']],
								['attribute'=>'tipo','header' => 'Tipo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'comprob','header' => 'Comprob.', 'contentOptions'=>['style'=>'text-align:left','width'=>'15px']],
								['attribute'=>'banco','header' => 'Banco', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
								['attribute'=>'titular','header' => 'Titular', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
								['attribute'=>'fchcobro','header' => 'Fch. Cobro', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
								['attribute'=>'cant','header' => 'Cant.', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
								['attribute'=>'cotiza','header' => 'Cotiza', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
								['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
								
				        	],
					]);
 				
 			 
 	
 		Pjax::end();
 		
 	?>
 	</div>
 	<!-- FIN DIV Grilla-->
 	
 	<!-- INICIO Mensajes de error -->
 	<div class="error-summary" id="erroresMdpEspeciales" style="display:none; margin-top:5px;">
 	</div>
	
	<!-- FIN Mensajes de error -->
 	
 	</div>
 	<!-- FIN DIV Caja MDPEspecial -->
 	
<script>
function imprimirMDPE()
{
	var caja = $("#CajaMDPEspecial_txCaja").val(),
		fecha = $("#CajaMDPEspecial_txFecha").val();
	
	$.pjax.reload({
		container:"#PjaxImprimirMDPE",
		method:"POST",
		data:{
			imprimir:1,
			caja:caja,
			fecha:fecha,
		}
		
	});
}

function f_cajaMDPEspecial_btCargar()
{
	var caja = $("#CajaMDPEspecial_txCaja").val(),
		fecha = $("#CajaMDPEspecial_txFecha").val(),
		error = [];
		
	$("#erroresMdpEspeciales").css("display", "none");
		
	if ( caja == "" || caja == 0 )
	{
		error.push("Ingrese una caja.");
	}
	
	if ( fecha == "" || fecha == null )
	{
		error.push("Ingrese una fecha.");
	}
	
	if ( error == "" )
	{
		$.pjax.reload({
			container: "#PjaxCargarGrillaMDPE",
			type: "POST",
			replace: false,
			push: false,
			data: {
				grillaMDPE_caja: caja,
				grillaMDPE_fecha: fecha,
			}
		});
	} else
		mostrarErrores(error, $("#erroresMdpEspeciales"));
	
	
}

</script>