<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
use yii\bootstrap\Tabs;

$title = 'Históricos';
$this->params['breadcrumbs'][] = ['label' => 'Objeto: '.$id, 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = $title;

$tipo = utb::getTObj( $id );


?>
<div class="persona-view">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($title) ?></h1></td>
    </tr>
	</table>
	
	<p>
		<label>Objeto: </label><input class='form-control' style='width:65px;background:#E6E6FA' disabled value='<?=$id?>' />
		<label>Nombre: </label><input class='form-control' style='width:300px;background:#E6E6FA' disabled value='<?=utb::getNombObj("'".$id."'")?>' />
	</p>
	<?php
	echo Tabs :: widget ([ 
 		'id' => 'TabHistorico',
 		'items' => [ 
 			[
				'label' => 'Domicilio', 
	 			'content' =>   GridView::widget([
									'id' => 'GrillaHistDomi',
									'dataProvider' => $dataproviderdom,
									'headerRowOptions' => ['class' => 'grilla'],
									'rowOptions' => ['class' => 'grilla'],
	            					'columns' => 		
	            						[
	            							['attribute'=>'torigen_nom','header' => 'Tipo', 'options'=>['style'=>'width:70px']],
	            							['attribute'=>'direccion','header' => 'Dirección', 'options'=>['style'=>'width:240px']],
	            							['attribute'=>'barr_nom','header' => 'Barrio', 'options'=>['style'=>'width:130px']],
	            							['attribute'=>'locprov','header' => 'Localidad - Prov.', 'options'=>['style'=>'width:130px']],
	            						],
	    							]),
	 			'options' => ['class'=>'tabItem']
 			],
 			['label' => 'Titulares' , 
 			'content' => GridView::widget([
							'id' => 'GrillaHistTit',
							'dataProvider' => $dataprovidertit,
							'headerRowOptions' => ['class' => 'grilla'],
							'rowOptions' => ['class' => 'grilla'],
            				'columns' => 		
            					[
            						['attribute'=>'num','header' => 'Objeto', 'options'=>['style'=>'width:80px']],
            						['attribute'=>'apenom','header' => 'Nombre', 'options'=>['style'=>'width:250px']],
            						['attribute'=>'tvinc_nom','header' => 'Vínculo', 'options'=>['style'=>'width:80px']],
            						['attribute'=>'porc','header' => 'Porc.', 'options'=>['style'=>'width:70px']],
            						['attribute'=>'fchmod','header' => 'Modif.', 'format' => ['date', 'php:d/m/Y'], 'options'=>['style'=>'width:100px']],
            					],
    						]),
 			'options' => ['class'=>'tabItem'],
 			'headerOptions' => ['style' => ($tipo != 3 ? 'display:block' : 'display:none')],
 			],
 			['label' => 'Avalúos' , 
 			'content' => GridView::widget([
							'id' => 'GrillaHistAval',
							'dataProvider' => $dataprovideraval,
							'headerRowOptions' => ['class' => 'grilla'],
							'rowOptions' => ['class' => 'grilla'],
            				'columns' => 		
            					[
            						['attribute'=>'_perdesde','header' => 'PerDesde', 'options'=>['style'=>'width:60px']],
            						['attribute'=>'_perhasta','header' => 'PerHasta', 'options'=>['style'=>'width:60px']],
            						['attribute'=>'supt','header' => 'SupT', 'options'=>['style'=>'width:60px']],
            						['attribute'=>'supm','header' => 'SupM', 'options'=>['style'=>'width:60px']],
            						['attribute'=>'avalt','header' => 'AvalT', 'options'=>['style'=>'width:70px;text-align:right']],
            						['attribute'=>'avalm','header' => 'AvalM', 'options'=>['style'=>'width:70px;text-align:right']],
            						['attribute'=>'frente','header' => 'Frente', 'options'=>['style'=>'width:50px']],
            						['attribute'=>'regimen','header' => 'Reg.', 'options'=>['style'=>'width:40px']],
            						['attribute'=>'zonat','header' => 'ZnT', 'options'=>['style'=>'width:40px']],
            						['attribute'=>'zonaop','header' => 'ZonaOP', 'options'=>['style'=>'width:60px']],
            						['attribute'=>'agua','header' => 'Agua', 'options'=>['style'=>'width:40px']],
            						['attribute'=>'cloaca','header' => 'Cloaca', 'options'=>['style'=>'width:40px']],
            						['attribute'=>'gas','header' => 'Gas', 'options'=>['style'=>'width:40px']],
            						['attribute'=>'alum','header' => 'Alumbrado', 'options'=>['style'=>'width:40px']],
            					],
    						]),
 			'options' => ['class'=>'tabItem'],
 			'headerOptions' => ['style' => ($tipo == 1 ? 'display:block' : 'display:none')],
 			],
 			['label' => 'Fallecidos' , 
 			'content' => GridView::widget([
							'id' => 'GrillaHistFall',
							'dataProvider' => $dataproviderfall,
							'headerRowOptions' => ['class' => 'grilla'],
							'rowOptions' => ['class' => 'grilla'],
            				'columns' => 		
            					[
            						['attribute'=>'fall_id','header' => 'Cod.', 'options'=>['style'=>'width:60px']],
            						['attribute'=>'apenom','header' => 'Nombre', 'options'=>['style'=>'width:200px']],
            						['attribute'=>'tserv_nom','header' => 'Servicio', 'options'=>['style'=>'width:150px']],
            						['attribute'=>'fecha','header' => 'Fecha', 'options'=>['style'=>'width:60px;text-align:center']],
            						['attribute'=>'acta','header' => 'Acta', 'options'=>['style'=>'width:70px']],
            						['attribute'=>'resp_nom','header' => 'Responsable', 'options'=>['style'=>'width:150px']],
            						['attribute'=>'obj_id_dest','header' => 'Obj.Destino', 'options'=>['style'=>'width:80px;text-align:center']],
            						['attribute'=>'destino','header' => 'Destino', 'options'=>['style'=>'width:150px']],
            					],
    						]),
 			'options' => ['class'=>'tabItem'],
 			'headerOptions' => ['style' => ($tipo == 4 ? 'display:block' : 'display:none')],
 			],
 		]
	]);  
		    
	?>
</div>