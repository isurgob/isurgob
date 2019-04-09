<?php
use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\grid\GridView;
use app\utils\db\utb;
use yii\web\Session;
use app\models\ctacte\Ajustes;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;


$this->params['breadcrumbs'][] = ['label' => 'Listado de Ajustes de Cuenta Corriente'];
$this->params['breadcrumbs'][] = 'Resultado';

?>
<div class="comercio-view">
	
<table width='100%'>
<tr>
	<td><h1 id='h1titulo'>Resultado</h1></td>
	<td align='right'>
		<?= Html::a('Nuevo', ['create'], ['class' => 'btn btn-success']) ?>
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
	        
	        	echo $this->render('//site/exportar',['titulo'=>'Listado de Ajustes de Cuenta Corriente','desc'=>$desc,'grilla'=>'Exportar']);
	        	
	        Modal::end();
        ?>
		<?= Html::a('Volver', ['listado'],['class' => 'btn btn-primary']) ?>
	</td>
</tr>
</table>
	
	<p>
		<label>Condición: </label><br>
		<textarea class='form-control SOLO-LECTURA' style='width:780px;height:70px; max-width:780px; max-height:140px;' disabled ><?=$desc?></textarea>
	</p>
	<?php
		$session = new Session;
    	$session->open();
    	
		$session['order'] = Yii::$app->request->post('order', 'aju_id');
		if ($session['by'] == "") $session['by'] = 'asc';		
	
	Pjax::begin(['id' => 'ActGrillaListComer']);
		if(isset($_POST['order'])) $session['order'] = $_POST['order'];
		
		if(isset($_POST['order']))
    	{
    		if ($session['by'] == 'desc') 
			{
				$session['by'] = 'asc';	
			}else {
				$session['by'] = 'desc';
			}
    	}
		
    	$dataprovider = Ajustes::buscarAv($cond, $session['order']." ".$session['by']);
    	
		$session['titulo'] = "Listado de Ajustes de Cuenta Corriente";
		$session['condicion'] = $desc;
		$session['sql'] = "Select * From v_ctacte_ajuste Where " . $cond . " Order By " . $session['order']." ".$session['by'];		
		$session['columns'] = [
			['attribute'=>'aju_id','label' => 'Cód', 'contentOptions'=>['style'=>'width:10px;']],
			['attribute'=>'trib_nom','label' => 'Tributo', 'contentOptions'=>['style'=>'width:200px;']],
			['attribute'=>'obj_id','label' => 'Objeto', 'headerOptions' => ['style' => 'width:40px;']],
			['attribute'=>'obj_nom','label' => 'Nombre Objeto', 'headerOptions' => ['style' => 'width:300px;']],
			['attribute'=>'anio','label' => 'Año', 'contentOptions'=>['style'=>'width:10px']],
			['attribute'=>'cuota','label' => 'Cuota', 'contentOptions'=>['style'=>'width:10px']],
			['attribute'=>'expe', 'label' => 'Expediente', 'contentOptions'=>['style'=>'width:10px']],
			['attribute'=>'fchmod', 'format' => ['date', 'php:d/m/Y'],'label' => 'Fecha', 'contentOptions'=>['style'=>'width:10px']],
			['attribute'=>'usrmod_nom','label' => 'Usuario', 'contentOptions'=>['style'=>'width:150px;']],
			    
        ];
    	$session->close();
    	Yii::$app->session['proceso_asig'] = 3303;
		
    	$session->close();
    	Pjax::begin(['id' => 'pjaxEliminar']);
    	
		echo GridView::widget([
			'id' => 'GrillaListPers',
			'dataProvider' => $dataprovider,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'columns' => [
							['attribute'=>'aju_id','header' => Html::a('Cód','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"aju_id"},method:"POST"})']), 'contentOptions'=>['style'=>'width:10px;']],
							['attribute'=>'trib_nom','header' => Html::a('Tributo','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"trib_nom"},method:"POST"})']), 'contentOptions'=>['style'=>'width:80px;']],
							['attribute'=>'obj_id','header' => Html::a('Objeto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"obj_id"},method:"POST"})']), 'headerOptions' => ['style' => 'width:40px;']],
							['attribute'=>'obj_nom','header' => Html::a('Nombre Objeto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"obj_nom"},method:"POST"})']), 'headerOptions' => ['style' => 'width:150px;']],
							['attribute'=>'anio','header' => Html::a('Año','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"anio"},method:"POST"})']), 'contentOptions'=>['style'=>'width:10px']],
							['attribute'=>'cuota','header' => Html::a('Cuota','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"cuota"},method:"POST"})']), 'contentOptions'=>['style'=>'width:10px']],
							['attribute'=>'expe', 'header' => Html::a('Expediente','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"expe"},method:"POST"})']), 'contentOptions'=>['style'=>'width:10px']],
							['attribute'=>'fchmod', 'format' => ['date', 'php:d/m/Y'],'header' => Html::a('Fecha','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"fchmod"},method:"POST"})']), 'contentOptions'=>['style'=>'width:10px']],
							['attribute'=>'usrmod_nom','header' => Html::a('Usuario','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"usrmod_nom"},method:"POST"})']), 'contentOptions'=>['style'=>'width:80px;']],
							            		
							['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:30px'],'template' => '{view} '.(utb::getExisteProceso(3430) ? '{delete}' : ''),
							'buttons' => [
								'view' => function($url, $model){
									return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view','aju_id'=>$model['aju_id']]);
								},
								'delete' => function($url, $model){
									return Html::a('<span class="glyphicon glyphicon-trash"></span>', null, ['onclick' => 'eliminarAjuste(' . $model['aju_id'] . ')']);
								}
							]
							]
						]
    	]); 
    	Pjax::end();
    Pjax::end();
    	
	?>
</div>

<script type="text/javascript">
function eliminarAjuste(id){
	
	$.pjax.reload({
		container : "#pjaxEliminar",
		url : "<?= BaseUrl::toRoute(['delete']); ?>",
		type : "POST",
		replace : false,
		push : false,
		data : {
			"aju_id" : id,
			"txcriterio" : "<?= $cond ?>",
			"txdescr" : "<?= $desc ?>"
		}
	});
}
</script>