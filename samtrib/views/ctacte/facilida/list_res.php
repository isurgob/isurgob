<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
use yii\web\Session;
use app\models\ctacte\Facilida;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

$title = 'Resultado ';
$this->params['breadcrumbs'][] = ['label' => 'Facilidad de Pago', 'url' => ['view']];
$this->params['breadcrumbs'][] = 'Listado';
$this->params['breadcrumbs'][] = $title;
?>
<div class="view-list_inm">

	<table width='100%'>
		<tr>
			<td><h1 id='h1titulo'><?= $title ?></h1></td>
			<td align='right'>
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
			        
			        	echo $this->render('//site/exportar',['titulo'=>'Listado de Facilidades','desc'=>$descr,'grilla'=>'Exportar']);
			        	
			        Modal::end();
		        ?>
				<?= Html::a('Volver', ['listado'],['class' => 'btn btn-primary']) ?>
			</td>
		</tr>
	</table>
	
	<p>
		<label>Condición: </label><br>
		<textarea class='form-control' style='width:780px;height:70px;background:#E6E6FA' disabled ><?= $descr?></textarea>
	</p>

<?php
	$session = new Session;
	$session->open();
	
	if ($session['order'] == "") $session['order'] = 'obj_id';
	if ($session['by'] == "") $session['by'] = 'asc';	
	$model = new Facilida();
	
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
		
    	$dataProvider = $model->buscaDatosListado($cond, $session['order']." ".$session['by']);
		$session['titulo'] = "Listado de Facilidades";
		$session['condicion'] = $descr; 
		$session['sql'] = 'select * from v_facilida '.($cond != '' ? ' where '.$cond : '');
		$session['columns'] = [
			['attribute'=>'faci_id','label' => 'Cod','contentOptions'=>['style'=>'width:15px']],
    		['attribute'=>'obj_id','label' => 'Objeto','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'trib_nomredu','label' => 'Tributo','contentOptions'=>['style'=>'width:150px; text-align:left']],
    		['attribute'=>'num_nom','label' => 'Contrib','contentOptions'=>['style'=>'width:250px']],
    		['attribute'=>'total','label' => 'Deuda','contentOptions'=>['style'=>'width:15px']],
    		['attribute'=>'est_nom','label' => 'Est','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'fchalta','label' => 'Alta','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'fchvenc','label' => 'Venc','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'fchimputa','label' => 'Imputa','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		   
        ];
    	$session->close();
    	Yii::$app->session['proceso_asig'] = 3440;

		echo GridView::widget([
			'id' => 'GrillaListFacilida',
			'dataProvider' => $dataProvider,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'columns' => [
            		['attribute'=>'faci_id','header' => Html::a('Cod','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"faci_id"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px']],
            		['attribute'=>'obj_id','header' => Html::a('Objeto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"obj_id"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'trib_nomredu','header' => Html::a('Tributo','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"trib_nomredu"},method:"POST"})']),'contentOptions'=>['style'=>'width:100px; text-align:left']],
            		['attribute'=>'num_nom','header' => Html::a('Contrib','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"num_nom"},method:"POST"})']), 'contentOptions'=>['style'=>'width:250px']],
            		['attribute'=>'total','header' => Html::a('Deuda','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"total"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px']],
            		['attribute'=>'est_nom','header' => Html::a('Est','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"est_nom"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'fchalta','header' => Html::a('Alta','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"fchalta"},method:"POST"})']), 'contentOptions'=>['style'=>'width:50px;text-align:center']],
            		['attribute'=>'fchvenc','header' => Html::a('Venc','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"fchvenc"},method:"POST"})']),'contentOptions'=>['style'=>'width:50px;text-align:center']],
            		['attribute'=>'fchimputa','header' => Html::a('Imputa','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"fchimputa"},method:"POST"})']),'contentOptions'=>['style'=>'width:50px;text-align:center']],
            		            		
            		['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:20px'],'template' => '{view}',],
            		           		            		
            	], 
    	]); 
	Pjax::end();

	?>
</div>