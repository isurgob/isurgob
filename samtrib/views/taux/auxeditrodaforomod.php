<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\models\taux\tablaAux;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\web\Session;
use app\controllers\ExportarController;
use yii\helpers\BaseUrl;

$title = 'Tipos de Modelos de Aforo';
$this->params['breadcrumbs'][] = ['label' => 'Configuración','url' => ['//site/config']];
$this->params['breadcrumbs'][] = $title;

$fabrica_buscador= isset($fabrica_buscador) ? $fabrica_buscador : null;
$marca_nom_buscador= isset($marca_nom_buscador) ? $marca_nom_buscador : null;
$tipo_nom_buscador= isset($tipo_nom_buscador) ? $tipo_nom_buscador : null;
$modelo_nom_buscador= isset($modelo_nom_buscador) ? $modelo_nom_buscador : null;

/* @var $this yii\web\View */
/* @var $model app\models\tablaAux */
/* @var $form ActiveForm */

if (isset($_GET['mensaje']) == null) $_GET['mensaje'] = '';
?>

<style type="text/css">

.asterisco{
	color:red;
	float:left;
	margin-left:100px;
}

</style>

<div class="site-auxedit">
	<table width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($title) ?></h1></td>
    	<td align='right'>
    		<?php
    			echo Html::button('Imprimir', ['class' => 'btn btn-success','onclick' => 'f_imprimir()']);
    			echo '&nbsp;&nbsp;';
				echo Html::button('Exportar', ['class' => 'btn btn-success', 'onclick' => '$("#modalExportar").modal("show")']);
    			echo '&nbsp;&nbsp;';
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::a('Nuevo', ['//objeto/rodado/aforo', 'c' => 0], ['class' => 'btn btn-success', 'id' => 'btnNuevo'])
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>

    <?php  $form = ActiveForm::begin(['id' => 'form-rodado-aforo']); ?>
    <table border='0'>
	    <tr>
	    	<td width='40px' align='left'><label>Aforo: </label></td>
			<td>
				<?=
					Html::input('text', 'aforo', '', [//$aforo,[
						'id' 		=> 'aforo',
						'class' 	=> 'form-control',
						'maxlength'	=>'8',
						'style' 	=> 'width:100px;text-align: center',
						'disabled' 	=> false,
					]);
				?>
			</td>
		</tr>
    </table>

    <table border='0'>

		<tr>
			<td width='40px' align='left'><label>Fábrica: </label></td>
			<td width='240px'><label><span class='asterisco'>(*)</span>&nbsp;Marca: </label></div></td>
			<td width='240px'><label><span class='asterisco'>(*)</span>&nbsp;Tipo: </label></td>
			<td width='240px'><label><span class='asterisco'>(*)</span>&nbsp;Modelo: </label></td>
		</tr>
		<tr>
		<td>
			<?=
				Html::input('text', 'fabrica_buscador',$fabrica_buscador,[
					'id' => 'fabrica_buscador',
					'class' => 'form-control',
					'maxlength'=>'3',
					'style' => 'width:40px;',
					'disabled' => false,
				]);
			?>
		</td>
		<td align='left'>
			<?=
				Html::input('text', 'marca_cod_buscador',$marca_nom_buscador,[
					'id' => 'marca_cod_buscador',
					'class' => 'form-control',
					'maxlength'=>'3',
					'style' => 'width:40px;',
					'disabled' => false,
				]);
			?>
			<label>-</label>
			<?=
				Html::input('text', 'marca_nom_buscador',$marca_nom_buscador,[
					'id' => 'marca_nom_buscador',
					'class' => 'form-control',
					'maxlength'=>'20',
					'style' => 'width:185px;',
					'disabled' => false,
				]);
			?>
		</td>
		<td align='right'>
			<?=
				Html::input('text', 'tipo_cod_buscador',$tipo_nom_buscador,[
					'id' => 'tipo_cod_buscador',
					'class' => 'form-control',
					'maxlength'=>'2',
					'style' => 'width:40px;',
					'disabled' => false,
				]);
			?>
			<label>-</label>
			<?=
				Html::input('text', 'tipo_nom_buscador',$tipo_nom_buscador,[
					'id' => 'tipo_nom_buscador',
					'class' => 'form-control',
					'maxlength'=>'35',
					'style' => 'width:185px;',
					'disabled' => false,
				]);
			?>
		</td>
		<td align='right'>
			<?=
				Html::input('text', 'modelo_cod_buscador',$modelo_nom_buscador,[
					'id' => 'modelo_cod_buscador',
					'class' => 'form-control',
					'maxlength'=>'3',
					'style' => 'width:40px;',
					'dsisabled' => false,
				 ]);
			?>
			<label>-</label>
			<?=
				Html::input('text', 'modelo_nom_buscador',$modelo_nom_buscador,[
					'id' => 'modelo_nom_buscador',
					'class' => 'form-control',
					'maxlength'=>'50',
					'style' => 'width:185px;',
					'disabled' => false,
				]);
			?>
		</td>
		</tr>
	</table>
	<table border='0'>
		<tr>
			<td width='775px'>
			<div style='color:red;float:left;'>(*)Se recomienda buscar o por codigo o por nombre, pero no ambos.<div>
			<?php
				echo Html::Button('Buscar', ['class' => 'btn btn-primary', 'id' => 'btnBuscar','style'=>'float:right;margin-right:-396px;margin-top:-17px;',
				'onclick' => '$.pjax.reload({container:"#idGrid",' .
						'						  data:{' .
		'												fabrica:$("#fabrica_buscador").val(),' .
		'												aforo:$("#aforo").val(),' .
		'												marca_cod:$("#marca_cod_buscador").val(),' .
		'												modelo_cod:$("#modelo_cod_buscador").val(),' .
		'												tipo_cod:$("#tipo_cod_buscador").val(),'.
		'												marca_nom:$("#marca_nom_buscador").val(),' .
		'												modelo_nom:$("#modelo_nom_buscador").val(),' .
		'												tipo_nom:$("#tipo_nom_buscador").val(), bandera:1'.
		'												},' .
		'												method:"POST"' .
		'									})'])
	    	?>
	       </td>
		</tr>
	</table>
     <?php ActiveForm::end();

	Pjax::begin(['id' => 'idGrid']);// comienza bloque de grilla

		$fabrica	= intVal( Yii::$app->request->post('fabrica', 0) );
		$aforo		= Yii::$app->request->post('aforo', '');
		$marca_cod	= intVal( Yii::$app->request->post('marca_cod', 0) );
		$tipo_cod	= intVal( Yii::$app->request->post('tipo_cod', 0) );
		$modelo_cod	= intVal( Yii::$app->request->post('modelo_cod', 0) );
		$marca_nom	= Yii::$app->request->post('marca_nom', '');
		$tipo_nom	= Yii::$app->request->post('tipo_nom', '');
		$modelo_nom	= Yii::$app->request->post('modelo_nom', '');

		echo "<script> $(document).ready(function(){
		$('tr.grilla th').css('font-size',12);
		}); </script>";

		$dp = $model->buscarModeloAforo($fabrica, $aforo, $marca_cod, $tipo_cod, $modelo_cod, $marca_nom, $tipo_nom, $modelo_nom);

		echo GridView::widget([
			'id' => 'GrillaTablaAux',
     	    'dataProvider' => $dp,
			'headerRowOptions' => ['class' => 'grilla'],
			'columns' => [
			        ['attribute'=>'aforo_id','label' => 'Cod' ,'contentOptions'=>['style'=>'width:1%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'origen','label' => 'Ori', 'contentOptions'=>['style'=>'width:1%;text-align:left;', 'class' => 'grilla']],
					['attribute'=>'fabr','label' => 'Fab' ,'contentOptions'=>['style'=>'width:1%;text-align:center;', 'class' => 'grilla']],
            		['attribute'=>'marca_id_nom','label' => 'Marca' ,'contentOptions'=>['style'=>'width:10%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'tipo_id_nom','label' => 'Tipo', 'contentOptions'=>['style'=>'width:15%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'modelo_id_nom','label' => 'Modelo' ,'contentOptions'=>['style'=>'width:15%;text-align:left;', 'class' => 'grilla']],
            		['attribute'=>'anio_min','label' => 'Desde', 'contentOptions'=>['style'=>'text-align:left;width:1%;']],
					['attribute'=>'anio_max','label' => 'Hasta', 'contentOptions'=>['style'=>'text-align:left;width:1%;']],
            		['attribute'=>'valor_max','label' => 'Valor' ,'contentOptions'=>['style'=>'width:1%;text-align:right;', 'class' => 'grilla']],

            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:4%;text-align:center;','class'=>'grilla'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{view} {update} {delete}' : ''),
            			'buttons'=>[
            				'view' => function($url, $model, $key){

            					return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['//objeto/rodado/aforo', 'id' => $model['aforo_id'], 'c' => 1]);
            				},

							'update' => function($url,$model,$key)
            						{
            							return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['//objeto/rodado/aforo', 'id' => $model['aforo_id'], 'c' => 3]);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['//objeto/rodado/aforo', 'id' => $model['aforo_id'], 'c' => 2]);
									}
						]
					],
			],
		]);
		
		Modal::begin([
			'id' => 'modalExportar',
			'header'=>'<h2>Exportar Listado</h2>',
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			]
		]);
			echo ExportarController::exportar( 'Listado de Aforos', '', '//objeto/rodado/aforoexportar', 
				"{ fabrica: $fabrica, aforo: '$aforo', marca_cod: $marca_cod, tipo_cod: $tipo_cod, modelo_cod: $modelo_cod, marca_cod: '$marca_nom', tipo_nom: '$tipo_nom', modelo_nom: '$modelo_nom' }" );

		Modal::end();

	Pjax::end(); // fin bloque de la grilla
	?>
</div><!-- site-auxedit -->



<script>

function f_imprimir(){

	var f = $("#fabrica_buscador").val();
	var a = $("#aforo").val();
	var mc = $("#marca_cod_buscador").val();
	var tc = $("#tipo_cod_buscador").val();
	var moc = $("#modelo_cod_buscador").val();
	var mn = $("#marca_nom_buscador").val();
	var tm = $("#tipo_nom_buscador").val();
	var mon = $("#modelo_nom_buscador").val();
	
	window.open("<?= BaseUrl::toRoute('//objeto/rodado/aforoimprimir');?>&f=" + f + "&a=" + a + "&mc=" + mc + "&tc=" + tc + "&moc=" + moc + "&mn=" + mn + "&tm=" + tm + "&mon=" + mon);
}

</script>
