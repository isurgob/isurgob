<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
use yii\web\Session;
use app\models\caja\CajaTicket;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\data\ArrayDataProvider;

$title = 'Listado Recibo Manual';
$this->params['breadcrumbs'][] = 'Resultado';

?>

<div class="listadoCobranza-view">
	<div>	
		<table width="100%">
			<tr>
				<td><h1><?= Html::encode($title) ?></h1></td>
				<td align="right">
					<?= Html::a('Imprimir', ['//site/pdflist', 'format' => 'A4-L'], ['class' => 'btn btn-success','target' => '_black']) ?>
			    	<?php
						Modal::begin([
				            'id' => 'Exportar', 
							'header' => '<h2>Exportar Datos</h2>',
							'toggleButton' => [
				                'label' => 'Exportar',
				                'class' => 'btn btn-success',
				            ],
				            'closeButton' => [
				              'label' => '<b>X</b>',
				              'class' => 'btn btn-danger btn-sm pull-right',
				            ]
				        ]);
				        
				        	echo $this->render('//site/exportar',['titulo'=>'Listado de Recibo Manual','desc'=>$descr,'grilla'=>'Exportar']);
				        	
				        Modal::end();
			        ?>
					<?= Html::a('Volver', ['recibomanual_list'],['class' => 'btn btn-primary']); ?>
				</td>
			</tr>
							
		</table>
	</div>
	
	<p>
		<label>Condición: </label><br>
		<textarea class='form-control' style='width:100%;max-width:780px;height:70px;max-height:120px;background:#E6E6FA' disabled ><?= $descr ?></textarea>
	</p>


<?php

	$model = new CajaTicket();
	
	//$dataProvider = new ArrayDataProvider(['allModels' => $model->buscarReciboManual($cond, $tipo) ]);
	
	Yii::$app->session['titulo'] = "Listado de Recibo Manual";
	Yii::$app->session['condicion'] = $descr; 
	
	Pjax::begin(['id' => 'ActGrillaList']);		
	
			if ($tipo == 'R')
			{
				$sql = "Select ctacte_id,est,recibo,to_char(fecha,'dd/mm/yyyy') as fecha,acta,item_nom,area_nom,monto,ticket,obj_id ";
            	$sql .= "From V_Recibo r Where " . $cond;
            	$sql .= " Order by r.ctacte_id";
            	
            	Yii::$app->session['sql'] =  $sql;
            
				Yii::$app->session['columns'] = [
					['attribute'=>'ctacte_id','header' => 'Nro.Ref', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'est','header' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'recibo','header' => 'Recibo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'fecha','header' => 'Fecha Recibo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'acta','header' => 'Acta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'item_nom','header' => 'Ítem', 'contentOptions'=>['style'=>'text-align:center','width'=>'300px']],
					['attribute'=>'area_nom','header' => 'Área', 'contentOptions'=>['style'=>'text-align:center','width'=>'200px']],
					['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['attribute'=>'ticket','header' => 'Ticket', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					 
			    ];
			    Yii::$app->session['proceso_asig'] = 3418;
				
				echo GridView::widget([
					'id' => 'GrillaListPers',
					'headerRowOptions' => ['class' => 'grilla'],
          			'rowOptions' => ['class' => 'grilla'],
					'dataProvider' => $dataProvider,
					'columns' => [
							['attribute'=>'ctacte_id','header' => 'Nro.Ref', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'est','header' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'recibo','header' => 'Recibo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'fecha','header' => 'Fecha Recibo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'acta','header' => 'Acta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'item_nom','header' => 'Ítem', 'contentOptions'=>['style'=>'text-align:center','width'=>'150px']],
							['attribute'=>'area_nom','header' => 'Área', 'contentOptions'=>['style'=>'text-align:center','width'=>'120px']],
							['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
							['attribute'=>'ticket','header' => 'Ticket', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:6px'],
								'template' =>'{viewrecibomanual}',
								'buttons'=>[			
									'viewrecibomanual' =>  function($url, $model, $key)
												{
													if($model['est'] == 'P') 
														return null;
													else
													{
														$url .= '&accion=1&reiniciar=1';
														if ($model['est'] == 'B')
															$url .= '&baja=1';
														return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url);
													}
												},
								],
							]
							
							
		            	],
		    	]); 
		    	
		    	
			} else 
			{
				$sql = "Select r.ctacte_id, c.est, ticket, c.obj_id, count(r.*) as cant, sum(r.monto) as total, c.obs ";
	            $sql .= "from ctacte_rec r ";
	            $sql .= "left join ctacte c on r.ctacte_id = c.ctacte_id ";
	            $sql .= "left join caja_ticket t on r.ctacte_id= t.ctacte_id ";
	            $sql .= "Where " . $cond;
	            $sql .= " group by r.ctacte_id, c.est, ticket, c.obj_id, c.obs";
	            $sql .= " Order by r.ctacte_id";
            	
            	Yii::$app->session['sql'] =  $sql;
				
				Yii::$app->session['columns'] = [
					['attribute'=>'ctacte_id','header' => 'Nro.Ref', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'est','header' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'ticket','header' => 'Ticket', 'contentOptions'=>['style'=>'text-align:center','width'=>'95px']],
					['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'cant','header' => 'Can', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'total','header' => 'Total', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'obs','header' => 'Obs', 'contentOptions'=>['style'=>'text-align:center']],
					   
			    ];
			    Yii::$app->session['proceso_asig'] = 3418;
				
				echo GridView::widget([
					'id' => 'GrillaListPers',
					'dataProvider' => $dataProvider,
					'headerRowOptions' => ['class' => 'grilla'],
          			'rowOptions' => ['class' => 'grilla'],
					'columns' => [
							['attribute'=>'ctacte_id','header' => 'Nro.Ref', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'est','header' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'ticket','header' => 'Ticket', 'contentOptions'=>['style'=>'text-align:center','width'=>'95px']],
							['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'cant','header' => 'Can', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'total','header' => 'Total', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
							['attribute'=>'obs','header' => 'Obs', 'contentOptions'=>['style'=>'text-align:center']],
							[
								'class' => 'yii\grid\ActionColumn',
								'contentOptions'=>['style'=>'width:6px'],
								'template' =>'{viewrecibomanual}',
								'buttons'=>[			
									'viewrecibomanual' =>  function($url, $model, $key)
												{
													if($model['est'] == 'P') 
														return null;
													else
													{
														$url .= '&accion=1';
														if ($model['est'] == 'B')
															$url .= '&baja=1';
														return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
													}
												},
								]
							]
							
		            	],
		    	]); 

			}
			
			

    Pjax::end();
    
	?>
</div>