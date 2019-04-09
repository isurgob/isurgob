<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
use yii\web\Session;
use app\models\ctacte\Judi;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

$title = 'Resultado ';
$this->params['breadcrumbs'][] = ['label' => 'Administración de Apremio', 'url' => ['view']];
$this->params['breadcrumbs'][] = ['label' => 'Listado'];
$this->params['breadcrumbs'][] = $title;
?>
<div class="view-list_inm">

	<table width='100%'>
		<tr>
			<td><h1 id='h1titulo'><?= $title ?></h1></td>
			<td align='right'>
				<?php
					echo Html::a('Imprimir', ['//site/pdflist', 'format' => 'A4-L'], ['class' => 'btn btn-success','target' => '_black']);
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

						echo $this->render('//site/exportar',['titulo'=>'Listado de Apremio Judicial','desc'=>$descr,'grilla'=>'Exportar']);

					Modal::end();
				?>
				<?= Html::a('Volver', ['listado'],['class' => 'btn btn-primary']) ?>
			</td>
		</tr>
	</table>
	
	<p>
		<label>Condición: </label><br>
		<textarea class='form-control' style='width:780px;height:70px;max-width:780px;max-height:140px;background:#E6E6FA' disabled ><?= $descr?></textarea>
	</p>

<?php
	$session = new Session;
	$session->open();
	
	if ($session['order'] == "") $session['order'] = 'obj_id';
	if ($session['by'] == "") $session['by'] = 'asc';	
	$model = new Judi();
	
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
		
		$session['titulo'] = "Listado de Apremio Judicial";
		$session['condicion'] = $descr;
		$session['sql'] = "select judi_id,obj_id,expe,caratula,to_char(fchalta, 'dd/MM/yyyy') as fchalta,procurador_nom,est_nom,(nominal+accesor+multa+multa_omi+hono_jud+gasto_jud) as deuda from v_judi ".($cond != '' ? ' where '.$cond : '');
		$session['columns'] = [
			['attribute'=>'judi_id','label' => 'Judi','contentOptions'=>['style'=>'width:10px;text-align:center']],
            		['attribute'=>'expe','label' => 'Expediente','contentOptions'=>['style'=>'width:80px;text-align:center']],
            		['attribute'=>'obj_id','label' => 'Objeto','contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'caratula','label' => 'Carátula','contentOptions'=>['style'=>'width:200px; text-align:left']],
            		['attribute'=>'fchalta','label' => 'Alta', 'contentOptions'=>['style'=>'width:15px']],
            		['attribute'=>'procurador_nom','label' => 'Procurador', 'contentOptions'=>['style'=>'width:15px']],
            		['attribute'=>'deuda','label' => 'Deuda', 'contentOptions'=>['style'=>'width:15px;text-align:right']],
            		['attribute'=>'est_nom','label' => 'Estado', 'contentOptions'=>['style'=>'width:100px;text-align:center']],	            		
            		
            		    

        ];
		
    	$session->close();
    	
    	/**
    	 * Judi [judi_id], Expediente [expe], Carátula [caratula], Alta [fchalta], Procurador [abogado_nom], Estado [estado_nom]
    	 */

		echo GridView::widget([
			'id' => 'GrillaListFacilida',
			'rowOptions' => ['class' => 'grilla'],
			'headerRowOptions' => ['class' => 'grilla'], 
			'dataProvider' => $dataProvider,
			'columns' => [
            		['attribute'=>'judi_id','header' => Html::a('Judi','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"judi_id"},method:"POST"})']),'contentOptions'=>['style'=>'width:10px;text-align:center']],
            		['attribute'=>'expe','header' => Html::a('Expediente','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"expe"},method:"POST"})']),'contentOptions'=>['style'=>'width:80px;text-align:center']],
            		['attribute'=>'obj_id','header' => Html::a('Objeto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"obj_id"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'caratula','header' => Html::a('Carátula','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"caratula"},method:"POST"})']),'contentOptions'=>['style'=>'width:200px; text-align:left']],
            		['attribute'=>'fchalta','header' => Html::a('Alta','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"fchalta"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px']],
            		['attribute'=>'procurador_nom','header' => Html::a('Procurador','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"procurador_nom"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px']],
            		['attribute'=>'deuda','header' => Html::a('Deuda','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"deuda"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px;text-align:right']],
            		['attribute'=>'est_nom','header' => Html::a('Estado','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListComer",data:{order:"est_nom"},method:"POST"})']), 'contentOptions'=>['style'=>'width:100px;text-align:center']],	            		
            		['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:10px'],'template' => '{view}',],
            		           		            		
            	], 
    	]); 
	Pjax::end();

	?>
</div>