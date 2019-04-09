<?php

use yii\helpers\Html;
use yii\grid\GridView;

use app\models\config\Rubro;
use app\models\config\RubroVigencia;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\bootstrap\Alert;
use \yii\bootstrap\Modal;
use app\utils\db\Fecha;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use app\controllers\ExportarController;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */


$title = 'Rubros';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;

if (isset($model) == null) $model = new Rubro();
if (isset($RubroVigencia) == null) $RubroVigencia = new RubroVigencia();
?>
	<table width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
		<tr>
			<td>
		    	<h1><?= Html::encode($title) ?></h1>
		    </td>
	    </tr>
	</table>
	
    <table  border='0' width="100%">
    <!-- ------------------------------------------------Inicio Filtro por nomeclador------------------------------------------------------- -->
    		<tr>
    			<td width='1%'><b>Nomeclador:</b></td>
    			<td width="1%"></td>
    			<td><?=
    				Html::dropDownList('selectNomec', $filtroNomec,utb::getAux( 'rubro_tnomen', 'nomen_id' ),['id'=>'filtroNomec','style'=>'width:100%;',
					'onchange'=>'filtrar();','class' => 'form-control']); 
    			?></td>
    			<td width="1%"></td>
    			<td width='1%'><b>Nombre:</b></td>
    			<td width="1%"></td>
    			<td><?= Html::textInput(null, $filtroNombre, ['class' => 'form-control', 'id' => 'filtroNombre', 'onchange' => 'filtrar();', 'style' => 'width:100%']); ?></td>
			</tr>
			<tr>	
    			<td><b>Código:</b></td>
    			<td></td>
    			<td><?= Html::textInput(null, $filtroCodigo, ['class' => 'form-control', 'id' => 'filtroCodigo', 'onchange' => 'filtrar();','style' => 'width:100%']); ?></td>
    			<td></td>
    			<td><b>Grupo:</b></td>
    			<td></td>
    			<td><?= Html::dropDownList(null, $filtroGrupo, utb::getAux('rubro_grupo'), ['id' => 'filtroGrupo', 'class' => 'form-control', 'onchange' => 'filtrar();', 'style' => 'width:100%;', 'prompt' => '']); ?></td>
    		</tr>
    </table>
    <table width='100%' height='700px' border='0'>		
    <!-- --------------------------------------------------Fin Filtro por nomeclador--------------------------------------------------------- -->	
    <tr>
    <td width='375px' valign='top'>
    <div class="rubro-index">
    <?php
    
    Pjax::begin(['id' => 'idGrid', 'enableReplaceState' => false, 'enablePushState' => false]);// comienza bloque de grilla
   
    	$cond = '';
		
		if ( $filtroNomec !== '')
			$cond .= "-Nomeclador: ".utb::getCampo("rubro_tnomen","nomen_id='".$filtroNomec."'");
		
		if ( $filtroNombre !== '')
			$cond .= "<br>-Nombre contiene '$filtroNombre'";
		
		if ( $filtroGrupo !== '')
			$cond .= "<br>-Grupo " . utb::getCampo("rubro_grupo","cod='".$filtroGrupo."'");
		
		if ( $filtroCodigo !== '')
			$cond .= "<br>-Código $filtroCodigo";
    	 
    	echo "<script> $('tr.grilla th').css('font-size',12); </script>";
    
		echo GridView::widget([
			'dataProvider' => $dpRubros,
			'headerRowOptions' => [
				'class' => 'grillaGrande',
			],
			'rowOptions' => function($model,$key,$index,$grid){
								return ['onclick' =>  'mostrarDatos("' . $model['rubro_id'] . '")',
								'class' => 'grillaGrande'
								];
								},
			'options' => ['style'=>'width:370px;margin-top:10px;'],			
			'columns' => [
            
					['attribute' => 'rubro_id', 'label' => 'Cód.', 'options' => ['style' => 'width:10%']],
					['attribute' => 'nombre', 'label' => 'Nombre', 'options' => ['style' => 'width:75%']],
					//['attribute' => 'grupo_nom', 'label' => 'Grupo','options' => ['style' => 'width:20%']],
					['class' => 'yii\grid\ActionColumn', 'options'=>['style'=>'width:20px;'], 'template' => (utb::getExisteProceso(3031) ? '{update}&nbsp;{delete}' : ''),        
						'buttons' => [   
					
							'update' => function($url,$model,$key)
									{
										
										return Html::a('<span class="glyphicon glyphicon-pencil"></span>',$url);							
									},									    							
								
							'delete' => function($url,$model,$key)
									{   
										
										return Html::a('<span class="glyphicon glyphicon-trash"></span>',$url);	            														
												 }	
											],
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
			
			echo ExportarController::exportar( 'Listado de Rubros', $cond, 'exportar', "{ fm: '$filtroNomec', fn: '$filtroNombre', fg: '$filtroGrupo', fc: '$filtroCodigo' }" );

		Modal::end();
		
	Pjax::end(); // fin bloque de la grilla														    
?>
</div>
</td>
<td  valign='top'>
<div style='width:280px;margin-top:27px;margin-left:-10;'>
<?php        
	Pjax::begin(['id' => 'formDatos']);
		echo $this->render('_formIndex',['model' => $model, 'modelVigencia' => $modelVigencia]);
	Pjax::end();
?>
</div>
</td>
	<td style='float:right;margin-top:20px;width:100px'>
		<?= $this->render('menu_derecho'); ?>
	</td>
</tr>
</table>


<script type="text/javascript">
function mostrarDatos(codigoRubro){
	
	$.pjax.reload({
		container: "#formDatos",
		type: "GET",
		replace: false,
		push: false,
		data: {
			"id": codigoRubro
		}
	});
}

function filtrar(){
	
	var filtroNomec= $("#filtroNomec").val();
	var filtroNombre= $("#filtroNombre").val();
	var filtroGrupo= $("#filtroGrupo").val();
	var filtroCodigo= $("#filtroCodigo").val();
	
	$.pjax.reload({
		container: "#idGrid",
		type: "GET",
		replace: false,
		get: false,
		data: {
			"fm": filtroNomec,
			"fn": filtroNombre,
			"fg": filtroGrupo,
			"fc":filtroCodigo
		}
	});
	
}

$(document).ready(function(){

	filtrar();
});
</script>