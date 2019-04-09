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
use app\models\ctacte\Ddjj;
use yii\web\Session;

/**
 * Forma que se dibuja cuando se comparan Declaraciones Juradas (DDJJ)
 * Recibo:
 * 			=> $model -> Modelo de Ddjj
 * 			=> $obj_id -> Identificador de Objeto
 * 			=> $dataProviderRubros -> Datos para la grilla de rubros
 *  		=> $dataProviderLiq	-> Datos para la grilla de liquidación
 * 			=> $dataProviderAnt -> Datos para la grilla de anicipo
 *
 * 			=> $externo = false -> true si la vista se esta dibujando en un modal, false de lo contrario
 */

/**
 * @param $consulta es una variable que:
 * 		=> $consulta == 1 => El formulario se dibuja en el index
 * 		=> $consulta == 0 => El formulario se dibuja en el create
 * 		=> $consulta == 3 => El formulario se dibuja en el update
 * 		=> $consulta == 2 => El formulario se dibuja en el delete
 */



 if (!isset($model) || $model == null) $model = new Ddjj();
 if (!isset($obj_id)) $obj_id = '';

 $obj_nom = '';

 if ($obj_id != '')	//Buscar nombre de Objeto
 	$obj_nom = utb::getNombObj("'".$obj_id."'");


//$externo = isset($externo) ? $externo : filter_var(Yii::$app->request->get('externo', Yii::$app->request->post('externo', true)), FILTER_VALIDATE_BOOLEAN);

$externo = isset($externo) ? $externo : true;

?>

<style>
#GrillaInfoddjj tbody > tr.active,
#GrillaInfoddjj tbody > tr.active > td{
	background-color: #2B6EE2 !important;
	cursor : pointer;
}
</style>

<div class="form-cuerpo">

	<?php if ($externo){  ?>

        <div class="pull-left">
            <h1>Declaración Jurada: <?= $obj_id ?>
        </div>

        <div class="pull-right" style="margin-right: 15px; margin-top: 5px">
            <?= Html::a('Volver', ['index'],['class'=>'btn btn-primary']); ?>
        </div>

        <div class="pull-right" style="margin-right: 15px; margin-top: 5px">
            <?php
                if( $estadoObjeto == 'A' ){

                    echo Html::a('Nueva DJ', ['create', 'obj_id' => $obj_id, 'n' => 0],['class'=>'btn btn-primary']);
                }
            ?>
        </div>

        <div class="clearfix"></div>

	<?php } else {?>
		<table width='100%'>
			<tr>
				<td><h1>Lista Declaraciones Juradas</td>
			</tr>
		</table>
	<?php } ?>
<!-- INICIO Mensajes de error -->
<table width="100%">

	<tr>
		<td width="100%">

			<?php

			Pjax::begin(['id'=>'errorDDJJListaDJ']);

				$mensaje = '';

				if (isset($_GET['mensaje'])) $mensaje = $_GET['mensaje'];

				if($mensaje != "")
				{

			    	Alert::begin([
			    		'id' => 'AlertaMensajeDDJJ',
						'options' => [
			        	'class' => 'alert-danger',
			        	'style' => $mensaje !== '' ? 'display:block' : 'display:none'
			    		],
					]);

					echo $mensaje;

					Alert::end();

					echo "<script>window.setTimeout(function() { $('#AlertaMensajeDDJJ').alert('close'); }, 5000)</script>";
				 }

			 Pjax::end();

			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<div class="form-panel" style="padding:8px">
<table width="100%">
	<tr>
		<td width="150px" valign="bottom"><label>Tributo:</label></td>
		<td width="0px"></td>
		<td colspan="4" valign="bottom"><label>Objeto:</label></td>
		<td width="5px"></td>
		<td align="left" valign="bottom"><label>Estado:</label></td>
		<td rowspan="2" align="right" valign="bottom"><?= $externo ? Html::button('Buscar',['class'=>'btn btn-success', 'id' => 'ddjj_listaDJ_btBuscar', 'onclick' => 'buscar(1)']) : null ?> </td></td>
	</tr>
		<td width="190px">
			<?= Html::dropDownList('dlTrib', null, utb::getAux('trib','trib_id','nombre',0,"tipo = 2 AND dj_tribprinc = trib_id AND est = 'A'"),
			[	'style' => 'width:100%',
				'class' => 'form-control',
				'id'=>'ddjj_listaDJ_dlTrib',
				'disabled' => !$externo
			]); ?>
		</td>

		<td width="20px"></td>

		<td width="80px"><?= Html::input('text','txObjetoID',$obj_id,['id'=>'ddjj_listaDJ_txObjetoID','class'=>'form-control solo-lectura','style'=>'width:98%;text-align:center']); ?>
		</td>
		<td></td>
		<td width="200px" colspan="2"><?= Html::input('text','txObjetoNom',$obj_nom,['id'=>'ddjj_listaDJ_txObjetoNom','class'=>'form-control solo-lectura','style'=>'width:100%;text-align:left']) ?></td>

		<td width="20px"></td>

		<td width="100px">
		<?= Html::dropDownList('dlEstado', null, utb::getAux('ddjj_test','cod','nombre',2),
			[	'style' => 'width:90px',
				'class' => 'form-control',
				'id'=>'ddjj_listaDJ_dlEstado',
				'disabled' => !$externo,
			]); ?>
		</td>

	</tr>
</table>
</div>

<?php

//INICIO Bloque que maneja las grillas
Pjax::begin(['id' => 'manejadorGrillaDDJJ', 'enableReplaceState' => false, 'enablePushState' => false]);

?>
<!-- INICIO Grilla Comparativa -->
<div class="form-panel" style="padding:8px">

<?php

	echo GridView::widget([
			'id' => 'GrillaInfoddjj',
			'options' => ['class'=>'lista'],
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
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
					['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['attribute'=>'multa','header' => 'Multa', 'contentOptions'=>['style'=>'text-align:right','width'=>'15px']],
					['attribute'=>'estctacte','header' => 'Est. CC.', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],
					['attribute'=>'fchpresenta','header' => 'Presenta', 'contentOptions'=>['style'=>'text-align:center','width'=>'15px']],

					['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:1px'],'template' => '{view}',
						'buttons'=>[
						'view' => function($url,$model,$key)
	    						  {
	    							return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',['//ctacte/ddjj/view','id' => $model['dj_id']], ['id'=>'ddjj_listaDJ_btVer','data-pjax'=>'false']);
	    						  }
	    			]
					],
	        	],
		]);

	?>
</div>
<!-- FIN Grilla Comparativa -->

<?php

	Pjax::end();

	if($externo) Html::button('Cancelar',['class'=>'btn btn-primary','onclick'=>'$("#ModalListarDDJJ").modal("hide")']);

?>

</div>

<script>

function buscar(boton)
{
	var error = '';

	<?php
		if($externo)
		{
	?>
			var trib = $("#ddjj_listaDJ_dlTrib").val();
			var est = $("#ddjj_listaDJ_dlEstado").val();

			if (trib == '' || trib == 0)
				error += "<li>Ingrese un tributo.</li>";
	<?php
		}
	?>


	var obj_id = $("#ddjj_listaDJ_txObjetoID").val();

	if (obj_id == '')
		error += "<li>Ingrese un Objeto.</li>";

	if (boton == 0)
		error = '';

	if (error != '')
	{
		$.pjax.reload({
			container:"#errorDDJJListaDJ",
			replace : false,
			push : false,
			data:{
				mensaje:error
			},
			type:"GET"
		});

	} else
	{
		var datos = {};
		datos.obj_id = obj_id;

		<?php
		if($externo){
			?>
			datos.trib = trib;
			datos.est = est;
			<?php
		}
		?>

		$.pjax.reload({
			container:"#manejadorGrillaDDJJ",
			type:"GET",
			replace : false,
			push : false,
			data: datos
		});
	}
}

function clickFila($fila)
{
	$("#GrillaInfoddjj .active").removeClass("active");
	$fila.addClass("active");
}

<?php
if($obj_id !== null && !empty(trim($obj_id))){
?>
$(document).ready(function(){buscar(0);});
<?php
}
?>
</script>
