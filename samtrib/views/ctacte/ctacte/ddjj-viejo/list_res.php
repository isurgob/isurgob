<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
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
		<textarea class='form-control' style='width:780px;height:70px;background:#E6E6FA;resize:none' disabled ><?= $descr ?></textarea>
	</p>

<?php

	$order = Yii::$app->session->getFlash( 'order', 'dj_id' );
	$by = Yii::$app->session->getFlash( 'by', 'asc' );

	$model = new Ddjj();

	Pjax::begin(['id' => 'ActGrillaListDDJJ']);

		// Yii::$app->session->setFlash( 'order', Yii::$app->request->post( 'order', 'dj_id' ) );
		//
		// if(isset($_POST['order']))
    	// {
    	// 	if ($session['by'] == 'desc')
		// 	{
		// 		$session['by'] = 'asc';
		// 	}else {
		// 		$session['by'] = 'desc';
		// 	}
    	// }
    	// $dataProvider = $model->buscaDatosListado($cond, $session['order']." ".$session['by']);
    	// $session['titulo'] = "Listado de DDJJ";
		// $session['condicion'] = $descr;
		// $session['tabla'] = 'v_dj';
		// $session['sql'] = 'select * from v_dj '.($session['cond'] != '' ? ' where '.$session['cond'] : '');
		// $session['columns'] = [
		// 	['attribute'=>'dj_id','label' => 'DJ','contentOptions'=>['style'=>'width:15px']],
    	// 	['attribute'=>'trib_nom','label' => 'Tributo','contentOptions'=>['style'=>'width:15px;text-align:center']],
    	// 	['attribute'=>'obj_id','label' => 'Objeto','contentOptions'=>['sty                //tyle'=>'width:15px']],
    	// 	['attribute'=>'est','label' => 'Est','contentOptions'=>['style'=>'width:15px;text-align:center']],
    	// 	['attribute'=>'anio','label' => 'Año','contentOptions'=>['style'=>'width:15px;text-align:center']],
    	// 	['attribute'=>'cuota','label' => 'Cuota','contentOptions'=>['style'=>'width:15px;text-align:center']],
    	// 	['attribute'=>'orden_nom','label' => 'Orden','contentOptions'=>['style'=>'width:15px;text-align:center']],
    	// 	['attribute'=>'base','label' => 'Base','contentOptions'=>['style'=>'width:15px;text-align:center']],
    	// 	['attribute'=>'monto','label' => 'Monto','contentOptions'=>['style'=>'width:15px;text-align:center']],
    	// 	['attribute'=>'multa','label' => 'Multa','contentOptions'=>['style'=>'width:15px;text-align:center']],
    	// 	['attribute'=>'fchpresenta','label' => 'Presenta','contentOptions'=>['style'=>'width:15px;text-align:center']],
		//
        // ];
		//
    	// $session->close();
    	// Yii::$app->session['proceso_asig'] = 3330;

		echo GridView::widget([
			'id' => 'GrillaListFacilida',
			'rowOptions' => ['class' => 'grilla'],
			'headerRowOptions' => ['class' => 'grilla'],
			'dataProvider' => $dataProvider,
			'columns' => [
            		['attribute'=>'dj_id', 'label' => 'DJ', 'contentOptions'=>['style'=>'width:1px; text-align: center']],
            		['attribute'=>'trib_nom','label' => 'Tributo','contentOptions'=>['style'=>'width:90px;text-align:center']],
            		['attribute'=>'obj_id','label' => 'Objeto','contentOptions'=>['style'=>'width:1px; text-align:left']],
            		['attribute'=>'subcta','label' => 'Subcta', 'contentOptions'=>['style'=>'width:1px;text-align:center']],
            		['attribute'=>'num_nom','label' => 'Titular', 'contentOptions'=>['style'=>'width:190px']],
            		['attribute'=>'est','label' => 'Est', 'contentOptions'=>['style'=>'width:1px;text-align:center']],
            		['attribute'=>'anio','label' => 'Año', 'contentOptions'=>['style'=>'width:1px;text-align:center']],
            		['attribute'=>'cuota','label' => 'Cuota','contentOptions'=>['style'=>'width:1px;text-align:center']],
            		['attribute'=>'orden_nom','label' => 'Orden','contentOptions'=>['style'=>'width:1px;text-align:center']],
            		['attribute'=>'base','label' => 'Base','contentOptions'=>['style'=>'width:1px;text-align:right']],
            		['attribute'=>'monto','label' => 'Monto','contentOptions'=>['style'=>'width:1px;text-align:right']],
            		['attribute'=>'multa','label' => 'Multa','contentOptions'=>['style'=>'width:1px;text-align:right']],
            		['attribute'=>'fchpresenta','label' => 'Presenta','contentOptions'=>['style'=>'width:1px;text-align:center'], 'format' => ['date', 'php:d/m/Y'],],

            		['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:1px'],'template' => '{view}',],

            	],
    	]);
	Pjax::end();

	?>
</div>
