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

<?php
    //INICIO Bloque actualiza los códigos de objeto
    Pjax::begin(['id' => 'compara_pjaxCambiaObjeto', 'enableReplaceState' => false, 'enablePushState' => false ]);
?>


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
			<?= Html::dropDownList('dlTrib', $trib, utb::getAux('trib','trib_id','nombre',3,"tipo = 2 AND dj_tribprinc = trib_id AND est = 'A'"),
			[	'style' => 'width:100%',
				'class' => 'form-control',
				'id'=>'ddjj_compara_dlTrib',
				'onchange'=> 'f_cambiaObjeto()',
			]); ?>
		</td>

		<td width="20px"></td>
		<td width="80px">
			<?= Html::input('text','txObjetoID', $objeto_id,[
					'id'           =>'ddjj_compara_txObjetoID',
					'class'        =>'form-control' . ( $trib != 0 ? '' : ' solo-lectura' ),
					'style'        =>'width:98%;text-align:center',
                    'tabIndex'     => ( $trib != 0 ? '0' : '-1' ),
					'onchange'     =>'f_cambiaObjeto()',
				]);
			?>
		</td>
		<td width="30px" style="padding-left:3px">
		<!-- botón de búsqueda modal -->


        <?=
            Html::button( '<i class="glyphicon glyphicon-search"></i>', [
                'class'     => 'bt-buscar'  . ( $trib != 0 ? '' : ' solo-lectura' ),
                'id'        => 'btBusquedaObjeto',
                'tabIndex'     => ( $trib != 0 ? '0' : '-1' ),
                'onclick'   => 'f_mostrarModalObjeto()',
            ]);
        ?>

		<!-- fin de botón de búsqueda modal -->
		</td>
		<td width="200px" colspan="2">
            <?=
                Html::input('text','txObjetoNom', $objeto_nom,[
                    'id'        =>'ddjj_compara_txObjetoNom',
                    'class'     =>'form-control solo-lectura',
                    'style'     =>'width:100%;text-align:left',
                    'tabIndex'  => '-1',
                ]);
            ?>
        </td>

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

<?php

    echo '<script>$("#ddjj_compara_txObjetoID").toggleClass("read-only",'.$trib.' == 0)</script>';
    echo '<script>$("#btBusquedaObjeto").toggleClass("read-only",'.$trib.' == 0)</script>';

    echo '<script>$("#ddjj_compara_txObjetoID").val("'.$objeto_id.'")</script>';
    echo '<script>$("#ddjj_compara_txObjetoNom").val("'.$objeto_nom.'")</script>';

    Pjax::end();
    //FIN Bloque actualiza los códigos de objeto

?>
</div>

<!-- INICIO Grilla Comparativa -->
<div class="form-panel" style="padding-right:8px">

<?php

	//INICIO Bloque que maneja las grillas
	Pjax::begin(['id' => 'compara_manejadorGrillaDDJJ']);

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

	Pjax::begin(['id' => 'compara_manejadorGrillaRubros']);

		echo GridView::widget([
				'id' => 'GrillaRubrosddjj',
				'headerRowOptions' => ['class' => 'grilla'],
				'rowOptions' => ['class' => 'grilla'],
				'dataProvider' => $dataProviderRubros,
				'summaryOptions' => ['class' => 'hidden'],
				'columns' => [
						['attribute'=>'trib_nom','header' => 'Trib', 'contentOptions'=>['style'=>'text-align:left','width'=>'50px']],
						['attribute'=>'rubro_nom','header' => 'Rubro', 'contentOptions'=>['style'=>'text-align:left','width'=>'250px']],
						['attribute'=>'tipo','header' => 'Tipo', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						['attribute'=>'base','header' => 'Base', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
						['attribute'=>'cant','header' => 'Cant', 'contentOptions'=>['style'=>'text-align:center','width'=>'1px']],
						['attribute'=>'alicuota','header' => 'Ali', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
						['attribute'=>'minimo','header' => 'Mín', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
						['attribute'=>'monto','header' => 'Monto', 'contentOptions'=>['style'=>'text-align:right','width'=>'1px']],
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

      return ['onclick' => '$.pjax.reload({container:"#compara_manejadorGrillaRubros",data:{'.$par.'},method:"GET",replace:false,push:false})'];


    }//Fin función que carga los datos

?>


<div id="comparaDDJJ_errorSummary" class="error-summary" style="display:none">
</div>

</div>

<?php
    //INICIO Modal Busca Objeto
    Modal::begin([
        'id' => 'compara_modalBusquedaObjeto',
        'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
        'size' => 'modal-lg',
        'closeButton' => [
            'label' => '<b>X</b>',
            'class' => 'btn btn-danger btn-sm pull-right',
        ],
     ]);

        echo $this->render('//objeto/objetobuscarav',[
            'id'            => 'ddjj_compara_altaBuscar',
            'txCod'         => 'ddjj_compara_txObjetoID',
            'txNom'         => 'ddjj_compara_txObjetoNom',
            'selectorModal' => '#compara_modalBusquedaObjeto',
        ]);

    Modal::end();
    //FIN Modal Busca Objeto
?>

<script>

function f_mostrarModalObjeto(){

    $( "#compara_modalBusquedaObjeto" ).modal( "show" );
}

function f_cambiaObjeto(){

    $.pjax.reload({
        container   : "#compara_pjaxCambiaObjeto",
        type        : "GET",
        replace     : false,
        push        : false,
        data:{
            objeto_id   : $("#ddjj_compara_txObjetoID").val(),
            trib        : $("#ddjj_compara_dlTrib").val(),
        },
    });
}

function buscar()
{

    ocultarErrores( "#comparaDDJJ_errorSummary" );

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
		    container   :"#compara_manejadorGrillaDDJJ",
			method      :"GET",
            replace     : false,
            push        : false,
			data:{
				trib:trib,
				obj_id:obj_id,
				est:est,
			}
		});
	}
}

$("#compara_manejadorGrillaDDJJ").on("pjax:end",function() {

	$.pjax.reload("#compara_manejadorGrillaRubros");
});

function limpiarGrillas(){

    $.pjax.reload( "#compara_manejadorGrillaDDJJ" );

}

$( "#compara_pjaxCambiaObjeto" ).on( "pjax:end", function() {

    limpiarGrillas();
});

</script>
