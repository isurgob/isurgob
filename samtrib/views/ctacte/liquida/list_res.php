<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
use yii\web\Session;
use app\models\ctacte\Liquida;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

$this->params['breadcrumbs'][] = ['label' => 'Tributo'];
$this->params['breadcrumbs'][] = ['label' => 'Liquidaciones Eventuales','url' => ['view']];
$this->params['breadcrumbs'][] = ['label' => 'Resultado'];

?>
<div class="liquida-view">
	
<table width='100%'>
<tr>
	<td><h1 id='h1titulo'>Resultado</h1></td>
	<td align='right'>
		<?php 
		if (utb::getExisteProceso(3320)) {
			echo Html::a('Imprimir', ['//site/pdflist'], ['class' => 'btn btn-success','target' => '_black']);
    		echo '&nbsp;';
    		
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
	        
	        	echo $this->render('//site/exportar',['titulo'=>'Listado de Liquidación','desc'=>$desc,'grilla'=>'Exportar']);
	        	
	        Modal::end();
		}
        ?>
		<?= Html::a('Volver', ['list_op'],['class' => 'btn btn-primary']) ?>
	</td>
</tr>
</table>
	
	<p>
		<label>Condición: </label><br>
		<textarea class='form-control' style='width:780px;height:70px;background:#E6E6FA;resize:none' disabled ><?=$desc?></textarea>
	</p>
	<?php
		$session = new Session;
    	$session->open();
    	
		if ($session['order'] == "") $session['order'] = 'obj_id';
		if ($session['by'] == "") $session['by'] = 'asc';		
	
	Pjax::begin(['id' => 'ActGrillaListLiq']);
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
		
    	$dataprovider = (new Liquida)->BuscarLiquida($session['cond'], $session['order']." ".$session['by'],$eventual);
		$session['titulo'] = "Listado de Liquidaciones";
		$session['condicion'] = $desc; 
		$session['sql'] = "select ctacte_id,trib_nom_red,obj_id,num_nom,anio,cuota,monto,to_char(venc2, 'dd/MM/yyyy') as venc2,est_nom from V_Emision_Print as c ".($session['cond'] != '' ? " where ".$session['cond'] : '');
		$session['columns'] = [
			['attribute'=>'ctacte_id','label' => 'Ref.','contentOptions'=>['style'=>'width:60px;text-align:center']],
        	['attribute'=>'trib_nom_red','label' => 'Tributo','contentOptions'=>['style'=>'width:200px;']],
        	['attribute'=>'obj_id','label' => 'Objeto','contentOptions'=>['style'=>'width:60px;text-align:center']],
        	['attribute'=>'num_nom','label' => 'Contribuyente','contentOptions'=>['style'=>'width:300px']],
        	['attribute'=>'anio','label' => 'Año','contentOptions'=>['style'=>'width:40px;text-align:center']],
            ['attribute'=>'cuota','label' => 'Cuota','contentOptions'=>['style'=>'width:40px; text-align:center']],
        	['attribute'=>'monto','label' => 'Monto','contentOptions'=>['style'=>'width:50px;text-align:right']],
        	['attribute'=>'venc2','label' => 'Venc.','contentOptions'=>['style'=>'width:60px; text-align:center']],
        	['attribute'=>'est_nom','label' => 'Estado','contentOptions'=>['style'=>'width:60px']],
        ];
    	$session->close();
    	Yii::$app->session['proceso_asig'] = 3320;
    	
		echo GridView::widget([
			'id' => 'GrillaListLiq',
			'dataProvider' => $dataprovider,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'columns' => [
            		['attribute'=>'ctacte_id','header' => Html::a('Cod','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListLiq",data:{order:"ctacte_id"},method:"POST"})']), 'contentOptions'=>['style'=>'width:60px']],
            		['attribute'=>'obj_id','header' => Html::a('Objeto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListLiq",data:{order:"obj_id"},method:"POST"})']), 'contentOptions'=>['style'=>'width:40px']],
            		['attribute'=>'obj_nom','header' => Html::a('Nombre','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListLiq",data:{order:"obj_nom"},method:"POST"})']), 'contentOptions'=>['style'=>'width:300px']],
            		['attribute'=>'trib_nom_red','header' => Html::a('Tributo','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListLiq",data:{order:"trib_nom_red"},method:"POST"})']), 'contentOptions'=>['style'=>'width:90px;']],
            		['attribute'=>'anio','header' => Html::a('Año','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListLiq",data:{order:"anio"},method:"POST"})']), 'contentOptions'=>['style'=>'width:40px;text-align:center']],
            		['attribute'=>'cuota','header' => Html::a('Cuota','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListLiq",data:{order:"cuota"},method:"POST"})']), 'contentOptions'=>['style'=>'width:40px; text-align:center']],
            		['attribute'=>'monto','header' => Html::a('Monto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListLiq",data:{order:"monto"},method:"POST"})']), 'contentOptions'=>['style'=>'width:80px; text-align:right']],
            		['attribute'=>'venc2','header' => Html::a('Venc.','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListLiq",data:{order:"venc2"},method:"POST"})']), 'contentOptions'=>['style'=>'width:90px;text-align:center']],
            		['attribute'=>'est','header' => Html::a('Est','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListLiq",data:{order:"est"},method:"POST"})']), 'contentOptions'=>['style'=>'text-align:center']],
            		['attribute'=>'modif','header' => Html::a('Modificación','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListLiq",data:{order:"modif"},method:"POST"})']), 'contentOptions'=>['style'=>'width:200px;']],
            		            		
            		['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:20px'],'template' => '{view}',],
            		           		            		
            	],
    	]); 
    Pjax::end();
    	
	?>
</div>