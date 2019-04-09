<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\models\ctacte\TribVenc;
use yii\bootstrap\Tabs;
use yii\widgets\ActiveForm;
use yii\bootstrap\Alert;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View\ctacte */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Vencimientos';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

$model = new TribVenc();
$criterio = "";

$request= Yii::$app->request;



?>

<style type="text/css">

.grid td,
.grid th{
	width : auto;
}

.grid th,
.grid tr > td:last-of-type{
	text-align : center;
}

.grid table tbody tr:hover{
	cursor : pointer;
}
</style>


<div class="trib-venc-index">

	<div class="pull-left">

		<h1><?= Html::encode($title) ?></h1>

	</div>

	<div class="pull-right">

		<?php

			if( utb::getExisteProceso( 3017 ) ){

				echo Html::a('Nuevo Vencimiento', ['create'], ['class' => 'btn btn-success']);
			}

		?>
	</div>

	<div class="clearfix"></div>

	<!-- INICIO Div Mensajes -->
	<div id="tribvenc_divMensaje" class="mensaje alert-success" style="display:none">
	</div>
	<!-- FIN Div Mensaje -->

	<?php

	    //Bloque de comienzo de filtrar datos
	    $form = ActiveForm::begin(['id' => 'formFiltraDatos']);

	?>

	<table style='margin-top:5px'>
		<tr>
			<td>
				<label>Filtros</label>
			</td>
			<td>


				<?php
					//dropDownLista que mostrará todos los tributos y filtrará la lista por tributo seleccionado
					echo $form->field($model, 'trib_id')->dropDownList($model->getTribVenc(), [
							'id' => 'selectTrib',
							'onchange'=>'$.pjax.reload({container:"#actualizaAnio",data:{tributo:this.value},method:"POST"})',
						])->label(false);
				?>
			</td>
			<td>
				<?php
					//Inicio bloque que actualiza el año
					Pjax::begin(['id' => 'actualizaAnio']);

						$tributo = intval(Yii::$app->request->post( 'tributo', 0 ));

						echo $form->field($model, 'anio')->dropDownList( $model->getAnio( $tributo ), [
							'id' 		=> 'selectAnio',
							'onchange'	=> 'ConsultaGrillaVenc()',
						])->label(false);

					Pjax::end();
					//Fin bloque que actualiza el año
				?>

			</td>
		</tr>
	</table>


<?php
	ActiveForm::end();// fin bloque de form

	Pjax::begin(['id' => 'idGrid']); //Inicio de bloque que muestra los datos en la grilla

		$tributo = Yii::$app->request->get( 'tributo', 0 );

		$anio = Yii::$app->request->get( 'anio', date( 'Y' ) );

		$dataProvider = new ArrayDataProvider([
				'allModels'=> $model->buscarVencimiento( $tributo, $anio),
				'key'=>'trib_id',
				'pagination' => [
					'pageSize' => 100,
				],
				'sort' => [
					'attributes' => [
						'anio',
						'cuota',

					],
					'defaultOrder' => [
						'anio' => SORT_DESC,
					]
				]]);


		echo GridView::widget([
        'dataProvider' => $dataProvider,
        'options' => ['class' => 'grid'],
        'columns' => [

           // ['class' => 'yii\grid\SerialColumn'],
            	['attribute'=>'anio', 'contentOptions'=>['align'=>'center'],'label' => 'Año'],
				['attribute'=>'cuota','contentOptions'=>['align'=>'center'],'label' => 'Cuota'],
           		['attribute'=>'fchvenc1','label' => 'Venc 1'],
				['attribute'=>'fchvenc2','label' => 'Venc 2'],
				['attribute'=>'fchvencanual','label' => 'Venc. Anual'],
				['attribute'=>'modif','label' => 'Modificación'],

				['class' => 'yii\grid\ActionColumn','template' => (utb::getExisteProceso(3017) ? '{update}{delete}' : ''),
	            'buttons' => [
		            'view' =>  function()
	            			{
	            				return null;
	            			},
	    			'update' =>  function($url, $model, $key)
	    						{
	    							$url = $url.'&trib_id=' . $model['trib_id'] . '&anio= ' . $model['anio'] . '&cuota= ' . $model['cuota'];
	    							return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
	    						},

	    			'delete' => function($url, $model, $key)
	    						{
	    							$url= $url . '&trib_id=' . $model['trib_id'] . '&anio= ' . $model['anio'] . '&cuota= ' . $model['cuota'] .'&accion=0';
	    							return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);

	    						}
        		]
        	]
        	]
    	]);

    Pjax::end();	//Fin de bloque que muestra los datos en la grilla
    ?>
</div>

<?php if( $mensaje != '' ){ ?>

	<script>
		mostrarMensaje( "<?= $mensaje ?>", "#tribvenc_divMensaje" );
	</script>
<?php } ?>

<script type="text/javascript">
function ConsultaGrillaVenc()
{
	var trib = $("#selectTrib").val();
	var anio = $("#selectAnio option:selected").text();

	$.pjax.reload({
		container: "#idGrid",
		type: "GET",
		replace: false,
		push: false,
		data: {
			tributo: trib,
			anio: anio,
		}
	});
}

$("#actualizaAnio").on("pjax:end", function() {

	ConsultaGrillaVenc();

});

$(document).ready(function()
{
	$("#selectTrib").change();
});
</script>
