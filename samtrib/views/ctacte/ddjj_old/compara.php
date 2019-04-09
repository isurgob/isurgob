<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\jui\DatePicker;
use yii\data\ArrayDataProvider;
use app\utils\db\utb;
use yii\bootstrap\Alert;

/**
 * Forma que se dibuja cuando se comparan Declaraciones Juradas (DDJJ)
 * Recibo:
 * 			=> $model -> Modelo de Ddjj
 * 			=> $dataProviderRubros -> Datos para la grilla de rubros
 *  		=> $dataProviderLiq	-> Datos para la grilla de liquidación
 * 			=> $dataProviderAnt -> Datos para la grilla de anicipo
 */
 
/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */

//INICIO Bloque actualiza los códigos de objeto
Pjax::begin(['id' => 'ObjNombre']);

	$trib = Yii::$app->request->post( 'trib', 0 );
	$objeto_id = Yii::$app->request->post( 'objeto_id', '' );
	$objeto_nom = '';
		
	if ( $trib != 0 )
	{
		$tobj = utb::getTTrib($trib);
		
		//Actualiza el tipo de objeto para la búsqueda de objeto
		echo '<script>$.pjax.reload({container:"#PjaxObjBusAv",data:{tobjeto:'.$tobj.'},method:"POST"});</script>';
	
		if ( strlen( $objeto_id ) < 8 && $objeto_id != '' )
		{
			$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);
			
			if ( utb::verificarExistenciaObjeto( (int)$tobj, "'" . $objeto_id . "'") != 1 )
				$objeto_id = '';
		}
		
		if ( utb::GetTObj( $objeto_id ) == $tobj )
		{
			$objeto_nom = utb::getNombObj("'".$objeto_id."'");
		
		}
			
	}
	
	echo '<script>$("#ddjj_compara_txObjetoID").toggleClass("read-only",'.$trib.' == 0)</script>';
	echo '<script>$("#btnDomParcela").toggleClass("read-only",'.$trib.' == 0)</script>';
	
	echo '<script>$("#ddjj_compara_txObjetoID").val("'.$objeto_id.'")</script>';
	echo '<script>$("#ddjj_compara_txObjetoNom").val("'.$objeto_nom.'")</script>';
		
Pjax::end();
//FIN Bloque actualiza los códigos de objeto

$title = 'Comparativa de DJ';
$this->params['breadcrumbs'][] = $title;

?>
<style>
#ddjj_compara  div
{
	padding-bottom: 6px;
	padding-top: 6px;
	margin-right: 0px;
}

</style>

<div id="ddjj_compara">

<table width="100%">
	<tr>
		<td><h1><?= Html::encode($title) ?></h1></td>
		<td align="right"><?= Html::a('Volver',['view'],['class' => 'btn btn-primary']) ?></td>
	</tr>
</table>

<div class="form-panel" style="padding-right:8px">
<table width="100%">
	<tr>
		<td width="150px" valign="bottom"><label>Trib:</label></td>
		<td width="20px"></td>
		<td colspan="4" valign="bottom"><label>Objeto:</label></td>
		<td width="20px"></td>
		<td align="LEFT" valign="bottom"><label>Estado:</label></td>
		<td rowspan="2" align="right" valign="bottom">
			<?= Html::button('Buscar',[
					'class'=>'btn btn-success',
					'id' => 'ddjj_compara_btBuscar',
					'onclick' => 'buscar()',
				]);
			?>
		</td>
	</tr>
	<tr>
		<td width="190px">
			<?= Html::dropDownList('dlTrib', null, utb::getAux('trib','trib_id','nombre',3,"tipo = 2 AND dj_tribprinc = trib_id AND est = 'A'"), 
			[	'style' => 'width:100%',
				'class' => 'form-control',
				'id'=>'ddjj_compara_dlTrib',
				'onchange'=>'$.pjax.reload({container:"#ObjNombre",data:{trib:$(this).val()},method:"POST"})'
			]); ?>
		</td>
		
		<td width="20px"></td>
		
		<td width="80px">
			<?= Html::input('text','txObjetoID',null,[
					'id'=>'ddjj_compara_txObjetoID',
					'class'=>'form-control read-only',
					'style'=>'width:98%;text-align:center',
					'onchange'=>'$.pjax.reload({container:"#ObjNombre",data:{objeto_id:$(this).val(),trib:$("#ddjj_compara_dlTrib").val()},method:"POST"})',
				]);
			?>
		</td>
		<td width="30px" style="padding-left:3px">
		<!-- botón de búsqueda modal -->
		
			<?php
			//INICIO Modal Busca Objeto
			Modal::begin([
			'id' => 'BuscaObjddjj_compara_altaBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
			'size' => 'modal-lg',
			'toggleButton' => [
				'label' => '<i class="glyphicon glyphicon-search"></i>',
				'class' => 'bt-buscar read-only',
				'id'=>'btnDomParcela'
			],
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			],
			 ]);
										
			echo $this->render('//objeto/objetobuscarav',[
				'id' => 'ddjj_compara_altaBuscar',
				'txCod' => 'ddjj_compara_txObjetoID',
				'txNom' => 'ddjj_compara_txObjetoNom',
				'selectorModal' => '#BuscaObjddjj_compara_altaBuscar',
    		]);
			
			Modal::end();
			//FIN Modal Busca Objeto
			?>
		
		<!-- fin de botón de búsqueda modal -->
		</td>
		<td width="200px" colspan="2"><?= Html::input('text','txObjetoNom',null,['id'=>'ddjj_compara_txObjetoNom','class'=>'form-control','style'=>'width:100%;text-align:left','readOnly'=>true]) ?></td>	
		
		<td width="20px"></td>
		
		<td width="100px">
		<?= Html::dropDownList('dlEstado', null, utb::getAux('ddjj_test','cod','nombre', 3), 
			[	'style' => 'width:100%',
				'class' => 'form-control',
				'id'=>'ddjj_compara_dlEstado',
			]); ?>
		</td>
		
	</tr>
</table>		
</div>

<!-- INICIO Grilla Comparativa -->
<div class="form-panel" style="padding-right:8px">

<?php
	
	//INICIO Bloque que maneja las grillas
	Pjax::begin(['id' => 'manejadorGrillaDDJJ']);

	$dataProviderDDJJ = new ArrayDataProvider(['allModels' => []]);
	
	if (isset($_POST['trib']) && $_POST['trib'] != '')
	{
		$trib_id = Yii::$app->request->post('trib','');
		$obj_id = Yii::$app->request->post('obj_id','');
		$est = Yii::$app->request->post('est','');
		
		$dataProviderDDJJ = $model->buscarListObj($trib_id,$obj_id,$est);
		//echo  $model->buscarListObj($trib_id,$obj_id,$est);
	}

	echo GridView::widget([
			'id' => 'GrillaInfoddjj',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid) {return array_merge( EventosGrilla($model), ['class' => 'grilla']);},
			'dataProvider' => $dataProviderDDJJ,
			'summaryOptions' => ['class' => 'hidden'],
			'columns' => [
					['attribute'=>'dj_id','header' => 'Nº DJ', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'subcta','header' => 'Suc.', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'anio','header' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'cuota','header' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'orden_nom','header' => 'Orden', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'est','header' => 'Est', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'tipo_nom','header' => 'Tipo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'multa','header' => 'Multa', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'estctacte','header' => 'CC.', 'contentOptions'=>['style'=>'text-align:center','width'=>'250px']],
					['attribute'=>'fchpresenta','header' => 'Presenta', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],						
	        	],
		]);
		
	Pjax::end(); 

	?>	
</div>	
<!-- FIN Grilla Comparativa -->

<!-- INICIO Grilla Rubros -->
<div class="form-panel" style="padding-right:8px">

<h3><b>Rubros:</b></h3>
<?php

	Pjax::begin(['id' => 'manejadorGrillaRubros']);
	
		$dataProviderRubros = new ArrayDataProvider(['allModels' => []]);
		
		if (isset($_POST['dj_id']) && $_POST['dj_id'] != '')
		{
			$dj_id = Yii::$app->request->post('dj_id','');
			
			$dataProviderRubros = $model->getDatosGrillaRubros($dj_id);
		}
		
		echo GridView::widget([
				'id' => 'GrillaRubrosddjj',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProviderRubros,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['attribute'=>'trib_nom','header' => 'Trib', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'rubro_nom','header' => 'Rubro', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'tipo','header' => 'Tipo', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
						['attribute'=>'cant','header' => 'Cant', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'alicuota','header' => 'Ali', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
						['attribute'=>'minimo','header' => 'Mín', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
						['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'50px']],
		        	],
			]);
			
	Pjax::end(); 
	
 ?>

</div>
<!-- FIN Grilla Rubros -->

<?php

	//Función que carga los datos 
	function EventosGrilla ($m) 
	{
       
      $par = "dj_id:".$m['dj_id'];      
      
      return ['onclick' => '$.pjax.reload({container:"#manejadorGrillaRubros",data:{'.$par.'},method:"POST"})'];

      
    }//Fin función que carga los datos
    
?>
    
    
<div id="comparaDDJJ_errorSummary" class="error-summary" style="display:none">

	<ul>
	</ul>
	
</div>

</div>

<script>
function buscar()
{
	var error = new Array(),
		trib = $("#ddjj_compara_dlTrib").val(),
		obj_id = $("#ddjj_compara_txObjetoID").val(),
		est = $("#ddjj_compara_dlEstado").val();
	
	if (trib == '' || trib == 0)
		error.push( "Ingrese un Tributo." );
		
	if (obj_id == '')
		error.push( "Ingrese un Objeto." );
		
	if (est == '' || est == 0)
		error.push( "Ingrese un Estado." );
	
	if (error != '')
	{
		mostrarErrores( error, "#comparaDDJJ_errorSummary" );
		
	} else 
	{
		$.pjax.reload({
			container:"#manejadorGrillaDDJJ",
			method:"POST",
			data:{
				trib:trib,
				obj_id:obj_id,
				est:est,
			}
		});
	}
}

$("#manejadorGrillaDDJJ").on("pjax:end",function() {
	
	$.pjax.reload("#manejadorGrillaRubros");
});
</script>