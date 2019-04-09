<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
use yii\web\Session;
use app\models\ctacte\Pagocta;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

$title = 'Resultado ';
$this->params['breadcrumbs'][] = ['label' => 'Pago a Cuenta', 'url' => ['view']];
$this->params['breadcrumbs'][] = 'Listado';
$this->params['breadcrumbs'][] = $title;

$session = new Session;
$session->open();

$cond = $session['cond'];
$descr = $session['descr'];
?>
<div class="view-list_inm">

	<table width='100%'>
		<tr>
			<td><h1 id='h1titulo'><?= $title ?></h1></td>
			<td align='right'>
				<?= Html::a('Volver', ['listado','reinicia'=>1],['class' => 'btn btn-primary']) ?>
			</td>
		</tr>
	</table>
	
	<p>
		<label>Condición: </label><br>
		<textarea class='form-control' style='width:780px;max-width:780px;height:70px;max-height:140px;background:#E6E6FA' disabled ><?= $descr?></textarea>
	</p>

<?php
	
	
	if ($session['order'] == "") $session['order'] = 'pago_id';
	if ($session['by'] == "") $session['by'] = 'asc';	
	$model = new Pagocta();
	$dataProvider = $model->buscaDatosListado($cond, $session['order']." ".$session['by']);
	Pjax::begin(['id' => 'ActGrillaListCompensa']);
	
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
		
    	$session->close();

		echo GridView::widget([
			'id' => 'GrillaListFacilida',
			'rowOptions' => ['class' => 'grilla'],
			'headerRowOptions' => ['class' => 'grilla'], 
			'dataProvider' => $dataProvider,
			'columns' => [
            		['attribute'=>'pago_id','header' => Html::a('Cod.','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"pago_id"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px; text-align:center']],
            		['attribute'=>'trib_nom','header' => Html::a('Tributo','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"trib_nom"},method:"POST"})']),'contentOptions'=>['style'=>'width:80px; text-align:left']],
            		['attribute'=>'obj_id','header' => Html::a('Objeto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"obj_id"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px']],
            		['attribute'=>'obj_nom','header' => Html::a('Nombre','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"obj_nom"},method:"POST"})']), 'contentOptions'=>['style'=>'width:200px']],
            		['attribute'=>'subcta','header' => Html::a('Subcta','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"subcta"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'anio','header' => Html::a('Año.','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"anio"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'cuota','header' => Html::a('Cuota.','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"cuota"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'monto','header' => Html::a('Monto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"monto"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px;text-align:right']],
            		['attribute'=>'fchlimite','header' => Html::a('Fch. Límite','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"fchlimite"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'fchpago','header' => Html::a('Fch. Pago','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"fchpago"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'est','header' => Html::a('Est','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"est"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:center']],

            		['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:20px'],'template' => '{view}',],
            		           		            		
            	], 
    	]);
    	 
	Pjax::end();

	?>
</div>