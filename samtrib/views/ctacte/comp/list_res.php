<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
use yii\web\Session;
use app\models\ctacte\Comp;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

$title = 'Resultado ';
$this->params['breadcrumbs'][] = ['label' => 'Listado de Compensaciones', 'url' => ['listado', 'reinicia' => 1]];
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
		<textarea class='form-control' style='width:780px;height:60px;max-width:780px;max-height:120px;background:#E6E6FA' disabled ><?= $descr?></textarea>
	</p>

<?php


	if ($session['order'] == "") $session['order'] = 'comp_id';
	if ($session['by'] == "") $session['by'] = 'asc';
	$model = new Comp();
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
            		['attribute'=>'comp_id','header' => Html::a('Cod.','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"comp_id"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'tipo_nom','header' => Html::a('Tipo','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"tipo_nom"},method:"POST"})']),'contentOptions'=>['style'=>'width:80px;text-align:center']],
            		['attribute'=>'trib_ori_nom','header' => Html::a('Trib_ori','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"trib_ori_nom"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px; text-align:center']],
            		['attribute'=>'obj_ori','header' => Html::a('Origen','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"obj_ori"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px']],
            		['attribute'=>'trib_dest_nom','header' => Html::a('Trib_dest','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"trib_dest_nom"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px']],
            		['attribute'=>'obj_dest','header' => Html::a('Dest.','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"obj_dest"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'monto','header' => Html::a('Monto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"monto"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px;text-align:right']],
            		['attribute'=>'monto_aplic','header' => Html::a('Aplic.','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"monto_aplic"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:right']],
            		['attribute'=>'saldo','header' => Html::a('Saldo','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"saldo"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:right']],
            		['attribute'=>'est_nom','header' => Html::a('Est','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListCompensa",data:{order:"est_nom"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:center']],

            		['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:20px'],'template' => '{view}',],

            	],
    	]);

	Pjax::end();

	?>
</div>
