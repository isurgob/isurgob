<?php

/**
 * Nombre: _form
 * 
 * Descripción: módulo que se encarga de la visualización de las cuentas corrientes cuyo saldo sea negativo 
 * 
 */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\bootstrap\Alert;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use yii\data\ArrayDataProvider;
use app\models\ctacte\Saldoneg;

$title = 'Listado Saldos Negativos';
$this->params['breadcrumbs'][] = $title;

$model = new Saldoneg();

?>
<div class="saldoneg-view" style="width:550px">
	<h1><?= Html::encode($title) ?></h1>

<div class="form" style="padding-bottom: 8px">

<table>
	<tr>
		<td width="90px"><label for="saldoneg_dlTributo">Tributo:</label></td>
		<td><?= Html::dropDownList('dlTributo','',utb::getAux( 'trib', 'trib_id', 'nombre', 3, "est = 'A'" ),[
					'id'=>'saldoneg_dlTributo',
					'class'=>'form-control', 
					'style'=>'width:274px',
				]);
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="90px"><label for="saldoneg_dlTObj">Tipo Objeto:</label></td>
		<td><?= Html::dropDownList('dlTObj','',utb::getAux( 'objeto_tipo', 'cod', 'nombre', 3 ),[
					'id'=>'saldoneg_dlTObj',
					'class'=>'form-control', 
					'style'=>'width:274px',
				]);
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="90px"><label>Cod. de Objeto:</label></td>
		<td><label>Desde:</label></td>
		<td width="50px">
			<?= Html::input('text','txObjDesde','',[
					'id' => 'saldoneg_txObjDesde',
					'class' => 'form-control',
					'style' => 'width:60px',
					'onkeypress' => 'return justNumbers( event )',
					'maxlength' => '7',
				]);
			?>
		</td>
		<td width="48px"></td>
		<td><label>Hasta:</label></td>
		<td width="50px">
			<?= Html::input('text','txObjHasta','',[
					'id' => 'saldoneg_txObjHasta',
					'class' => 'form-control',
					'style' => 'width:60px',
					'onkeypress' => 'return justNumbers( event )',
					'maxlength' => '7',
				]);
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="90px"><label>Período:</label></td>
		<td><label>Desde:</label></td>
		<td width="90px">
			<?= Html::input('text','txPerDesdeAnio',date('Y') - 5,[
					'id' => 'saldoneg_txPerDesdeAnio',
					'class' => 'form-control',
					'style' => 'width:40px',
					'onkeypress' => 'return justNumbers( event )',
					'maxlength' => '4',
				]);
			?>
			<?= Html::input('text','txPerDesdeCuota',1,[
					'id' => 'saldoneg_txPerDesdeCuota',
					'class' => 'form-control',
					'style' => 'width:40px',
					'onkeypress' => 'return justNumbers( event )',
					'maxlength' => '3',
				]);
			?>
		</td>
		<td width="20px"></td>
		<td><label>Hasta:</label></td>
		<td width="90px">
			<?= Html::input('text','txPerHastaAnio',date('Y'),[
					'id' => 'saldoneg_txPerHastaAnio',
					'class' => 'form-control',
					'style' => 'width:40px',
					'onkeypress' => 'return justNumbers( event )',
					'maxlength' => '4',
				]);
			?>
			<?= Html::input('text','txPerHastaCuota',99,[
					'id' => 'saldoneg_txPerHastaCuota',
					'class' => 'form-control',
					'style' => 'width:40px',
					'onkeypress' => 'return justNumbers( event )',
					'maxlength' => '3',
				]);
			?>
		</td>
		
		<td width="30px"></td>
		
		<td><?= Html::button('Procesar',[
					'id' => 'saldoneg_btProcesar',
					'class' => 'btn btn-primary',	
					'onclick' => 'f_procesar()',
				]);
			?>
		</td>
	</tr>
</table>	
	
</div>

<div id="saldoneg_grilla" style="display:none;margin-top: 8px">

<table width="100%">
	<tr>
		<td><h2><b>Resultados</b></h2></td>
		<td align="right">
			<?= Html::button( 'Exportar', [
					'id' => 'saldoneg_btExportar',
					'class' => 'btn btn-success',
				]);
			?>
		</td>
	</tr>
</table>
<?php

	Pjax::begin(['id' => 'PjaxSaldosNegativos', 'enablePushState' => false, 'enableReplaceState' => false]);
			
			# Obtener datos
			
			$trib = Yii::$app->request->get( 'trib', 0 );
			$tobj = Yii::$app->request->get( 'tobj', 0 );
			$objdesde = Yii::$app->request->get( 'objdesde', 0 );
			$objhasta = Yii::$app->request->get( 'objhasta', 0 );
			$perdesde_anio = Yii::$app->request->get( 'perdesde_anio', 0 );
			$perdesde_cuota = Yii::$app->request->get( 'perdesde_cuota', 0 );
			$perhasta_anio = Yii::$app->request->get( 'perhasta_anio', 0 );
			$perhasta_cuota = Yii::$app->request->get( 'perhasta_cuota', 0 );
			
			$dataProvider = new ArrayDataProvider([ 'allModels' => [] ]);
			
			if ( $trib != 0 )
			{
				# Generar el código de objeto
				$objdesde = utb::GetObjeto( (int)$tobj, (int)$objdesde );
				$objhasta = utb::GetObjeto( (int)$tobj, (int)$objhasta );
				
				# Generar el arreglo de datos para la grilla
				$dataProvider = new ArrayDataProvider([
					'allModels' => $model->cargarDatos($trib, $objdesde, $objhasta, $perdesde_anio, $perdesde_cuota, $perhasta_anio, $perhasta_cuota),
				]);
				
			}
			
			echo GridView::widget([
				'id' => 'GrillaSaldosNegativos',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProvider,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['attribute'=>'ctacte_id','label' => 'Nro.Ref.', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'text-align:left','width'=>'150px']],
						['attribute'=>'anio','label' => 'Año', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'cuota','label' => 'Cuota', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
						['attribute'=>'saldo','label' => 'Saldo', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
						[
							'class' => 'yii\grid\ActionColumn',
							'contentOptions'=>['style'=>'width:6px'],
							'template' =>'{ctacte}',
							'buttons'=>[	
							
								'ctacte' =>  function($url, $model, $key)
											{
													return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['//ctacte/ctacte/index', 'obj_id' => $model['obj_id'] ],[
																'class'=>'bt-buscar-label',
																'style'=>'color:#337ab7',
															]);
											},
							]
						]
		        ],
			]);
				
	Pjax::end();
?>

</div>

<div id="saldoneg_errorSummary" class="error-summary" style="display:none;margin-top:8px;">
	
	<ul>
	</ul>
	
</div>

</div>

<script>

function f_procesar()
{
	var error = new Array(),
		datos = {},
		trib = $("#saldoneg_dlTributo").val(),
		tobj = $("#saldoneg_dlTObj").val(),
		objdesde = $("#saldoneg_txObjDesde").val(),
		objhasta = $("#saldoneg_txObjHasta").val(),
		perdesde_anio = $("#saldoneg_txPerDesdeAnio").val(),
		perdesde_cuota = $("#saldoneg_txPerDesdeCuota").val(),
		perhasta_anio = $("#saldoneg_txPerHastaAnio").val(),
		perhasta_cuota = $("#saldoneg_txPerHastaCuota").val();
		
	if ( trib == '' || trib == 0 )
		error.push( "Ingrese un tributo." );
	
	if ( tobj == '' || tobj == 0 )
		error.push( "Ingrese un tipo de objeto." );
	
	if ( objdesde == '' || objhasta == '' )
		error.push( "Ingrese un rango de objeto." );
	
	else if ( parseInt( objdesde ) > parseInt( objhasta ) )
		error.push( "Ingrese un rango de objeto válido." );
	
	if ( perdesde_anio == '' || perdesde_cuota == '' || perhasta_anio == '' || perhasta_cuota == '' )
		error.push( "Ingrese un rango de período." );
	else
	{
		if ( perdesde_anio.length < 4 )
			error.push( "Ingrese año desde válido." );
		
		if ( !( parseInt( perdesde_cuota ) > 0 ) )
			error.push( "Ingrese una cuota desde válida." );
		
		if ( perhasta_anio.length < 4 )
			error.push( "Ingrese año hasta válido." );
		
		if ( !( parseInt( perhasta_cuota ) > 0 ) )
			error.push( "Ingrese una cuota hasta válida." );
	}
	
	if ( error.length == 0 )
	{
		//Procesar
		datos.trib = trib;
		datos.tobj = tobj;
		datos.objdesde = objdesde;
		datos.objhasta = objhasta;
		datos.perdesde_anio = perdesde_anio;
		datos.perdesde_cuota = perdesde_cuota;
		datos.perhasta_anio = perhasta_anio;
		datos.perhasta_cuota = perhasta_cuota;
		
		$("#saldoneg_errorSummary").css("display","none");
		
		$("#saldoneg_grilla").css("display","block");
		
		$.pjax.reload({
			container: "#PjaxSaldosNegativos",
			type: "GET",
			replace: false,
			push: false,
			data: datos,
		});
		
	} else 
	{
		//Ocultar grilla 
		$("#saldoneg_grilla").css("display","none");
		
		mostrarErrores( error, "#saldoneg_errorSummary" );
	}
		
}

</script>