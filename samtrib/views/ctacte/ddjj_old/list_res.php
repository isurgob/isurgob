<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
use yii\web\Session;
use app\models\ctacte\Ddjj;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

$title = 'Resultado ';
$this->params['breadcrumbs'][] = ['label' => 'Declaraciones Juradas', 'url' => ['view']];
$this->params['breadcrumbs'][] = 'Listado';
$this->params['breadcrumbs'][] = $title;
?>
<div class="view-list_inm">

	<table width='100%'>
		<tr>
			<td><h1 id='h1titulo'><?= $title ?></h1></td>
			<td align='right'>
				<?php 
				if (utb::getExisteProceso(3330)) {
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
			        
			        	echo $this->render('//site/exportar',['titulo'=>'Listado de DDJJ','desc'=>$descr,'grilla'=>'Exportar']);
			        	
			        Modal::end();
				}
		        ?>
				<?= Html::a('Volver', ['listado'],['class' => 'btn btn-primary']) ?>
			</td>
		</tr>
	</table>
	
	<p>
		<label>Condición: </label><br>
		<textarea class='form-control' style='width:780px;height:70px;background:#E6E6FA;resize:none' disabled ><?= $descr?></textarea>
	</p>

<?php
	$session = new Session;
	$session->open();
	
	if ($session['order'] == "") $session['order'] = 'dj_id';
	if ($session['by'] == "") $session['by'] = 'asc';	
	$model = new Ddjj();
	
	Pjax::begin(['id' => 'ActGrillaListDDJJ']);
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
    	$session['titulo'] = "Listado de DDJJ";
		$session['condicion'] = $descr; 
		$session['tabla'] = 'v_dj'; 
		$session['sql'] = 'select * from v_dj '.($session['cond'] != '' ? ' where '.$session['cond'] : '');
		$session['columns'] = [
			['attribute'=>'dj_id','label' => 'DJ','contentOptions'=>['style'=>'width:15px']],
    		['attribute'=>'trib_nom','label' => 'Tributo','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'obj_id','label' => 'Objeto','contentOptions'=>['style'=>'width:15px; text-align:left']],
    		['attribute'=>'obj_nom','label' => 'Comercio','contentOptions'=>['style'=>'width:15px']],
    		['attribute'=>'num_nom','label' => 'Titular','contentOptions'=>['style'=>'width:15px']],
    		['attribute'=>'est','label' => 'Est','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'anio','label' => 'Año','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'cuota','label' => 'Cuota','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'orden_nom','label' => 'Orden','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'base','label' => 'Base','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'monto','label' => 'Monto','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'multa','label' => 'Multa','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		['attribute'=>'fchpresenta','label' => 'Presenta','contentOptions'=>['style'=>'width:15px;text-align:center']],
    		
        ];
		
    	$session->close();
    	Yii::$app->session['proceso_asig'] = 3330;
    	
//dj_id,trib_nom,obj_id,obj_nom,num_nom,est,anio,cuota,orden_nom,base,monto,multa,to_char(fchpresenta, 'dd/MM/yy') as fchpresenta
		echo GridView::widget([
			'id' => 'GrillaListFacilida',
			'rowOptions' => ['class' => 'grilla'],
			'headerRowOptions' => ['class' => 'grilla'], 
			'dataProvider' => $dataProvider,
			'columns' => [
            		['attribute'=>'dj_id','header' => Html::a('DJ','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"dj_id"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px']],
            		['attribute'=>'trib_nom','header' => Html::a('Tributo','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"trib_nom"},method:"POST"})']),'contentOptions'=>['style'=>'width:90px;text-align:center']],
            		['attribute'=>'obj_id','header' => Html::a('Objeto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"obj_id"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px; text-align:left']],
            		['attribute'=>'subcta','header' => Html::a('Subcta','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"subcta"},method:"POST"})']), 'contentOptions'=>['style'=>'width:10px;text-align:center']],
            		['attribute'=>'num_nom','header' => Html::a('Titular','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"num_nom"},method:"POST"})']), 'contentOptions'=>['style'=>'width:90px']],
            		['attribute'=>'est','header' => Html::a('Est','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"est"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'anio','header' => Html::a('Año','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"anio"},method:"POST"})']), 'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'cuota','header' => Html::a('Cuota','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"cuota"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'orden_nom','header' => Html::a('Orden','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"orden_nom"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		['attribute'=>'base','header' => Html::a('Base','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"base"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:right']],
            		['attribute'=>'monto','header' => Html::a('Monto','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"monto"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:right']],
            		['attribute'=>'multa','header' => Html::a('Multa','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"multa"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:right']],
            		['attribute'=>'fchpresenta','header' => Html::a('Presenta','#',['onclick'=>'$.pjax.reload({container:"#ActGrillaListDDJJ",data:{order:"fchpresenta"},method:"POST"})']),'contentOptions'=>['style'=>'width:15px;text-align:center']],
            		
            		['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:1px'],'template' => '{view}',],
            		           		            		
            	], 
    	]); 
	Pjax::end();

	?>
</div>