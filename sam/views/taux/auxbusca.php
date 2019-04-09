<?php

use app\utils\db\utb;
use yii\grid\GridView;
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\web\Session;

/**
 * Función que se encarga de mostrar una ventana modal para búsqueda.
 *
 * @param string tabla Nombre de la tabla de la que se extraerán los datos.
 * @param string campocod Nombre de la columna que es clave primaria. ...Por defecto campocod = 'cod'...
 * @param string camponombre Nombre de la columna de la que se extraerán los datos. ...Por defecto camponombre = 'nombre'...
 * @param string boton_id Id del botón desde el cual se llama a la ventana modal. ...Por defecto boton_id = 'BuscaAux'...
 * @param string idcampocod Id del campo en el que se agregará el código
 * @param string idcamponombre Id del campo en el que se agregará el nombre
 * @param String claseGrilla = null Clase que se le debe colocar a la grilla que muestra los datos
 * @param string idModal Identificador de la ventana modal. Se usa para cerrar la ventana modal.
 */

$claseGrilla 	= isset( $claseGrilla ) ? $claseGrilla : null;
$campocod		= isset( $campocod ) ? $campocod : 'cod';
$camponombre	= isset( $camponombre ) ? $camponombre : 'nombre';
$idcamponombre 	= isset( $idcamponombre ) ? $idcamponombre : '';
$boton_id		= isset( $boton_id ) ? $boton_id : 'BuscaAux';
$cantmostrar	= isset( $cantmostrar ) ? $cantmostrar : 12;
$idAux			= isset( $idAux ) ? $idAux : 'Aux';
$criterio		= isset( $criterio ) ? $criterio : '';
$idModal		= isset( $idModal ) ? $idModal : '';
$order			= isset( $order ) ? $order : '';
$camposextra	= isset( $camposextra ) ? $camposextra : '';

echo '<label class="control-label">Filtrar por Nombre:</label>';
echo Html::input('text'.$idAux, 'txnombre', '', ['class' => 'form-control','id'=>'txnombre'.$idAux,'style'=>'width:270px;text-transform: uppercase','maxlength'=>'250',
		'onchange'=>'BuscarNombre'.$idAux.'()']);
echo '<br><br>';

Pjax::begin([ 'id' => 'DivGrillaAux'.$idAux, 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);// comienza bloque de grilla

$session = new Session;
$session->open();

if(!isset($criterio)){
	if (isset($session['criterio'])) {

		$criterio = $session->get('criterio', '');
		$session->set('criterio', '');
	}else $criterio = "";
}

if (Yii::$app->request->post('nombre', null) !== null)
{
	if (isset($criterio) && $criterio !== "") $criterio .= " and ";
	$criterio .= " upper($camponombre) like upper('%". Yii::$app->request->post('nombre', '')."%')";

	$session->set('criterio', $criterio);
}

$session->set('id', $boton_id);
$session->close();

echo GridView::widget([
		'id' => 'GrillaAux'.$idAux,
		'dataProvider' => utb::DataProviderAuxConExtras($tabla,$criterio,$campocod,$camponombre,$camposextra,$cantmostrar,$order),
		'headerRowOptions' => ['class' => $claseGrilla],
        'rowOptions' => function ($model,$key,$index,$grid) use($claseGrilla, $boton_id)
        				{
        					return
        					[
								'ondblclick'=>'btnAuxBuscar'. $boton_id .'("'.$model['cod'].'","'.$model['nombre'].'");',
								'onclick'=>'btnAuxBuscar2'. $boton_id .'("'.$model['cod'].'","'.$model['nombre'].'");',
								'class' => $claseGrilla
							];
        				},
		'columns' => [

            ['attribute'=>'cod','header' => 'Cod','options'=>['style'=>'width:50px']],
			['attribute'=>'nombre','header' => 'Nombre'],
			['attribute'=>$camposextra,'header' => $camposextra],
        ],
    ]);
Pjax::end(); // fin bloque de la grilla

?>

<script type="text/javascript">
function BuscarNombre<?=$idAux?>()
{
	$.pjax.reload(
		{
			container	: "#DivGrillaAux<?= $idAux ?>",
			method		: "POST",
			replace 	: false,
			push		: false,
			timeout 	: 100000,
			data:{
				nombre	: $("#txnombre<?= $idAux ?>").val(),
			},
		}
	)
}

function btnAuxBuscar<?= $boton_id ?>(cod,nombre)
{
	$("#<?= $idcampocod ?>").val( cod );
	$("#<?= $idcamponombre ?>").val(nombre);

	$("#<?= $idcampocod ?>").change();

	cerrarVentanaModal<?= $boton_id ?>();
}

function btnAuxBuscar2<?= $boton_id ?>(cod,nombre)
{
	$("#<?= $idcampocod ?>").val(cod);
	$("#<?= $idcamponombre ?>").val(nombre);
}

function cerrarVentanaModal<?= $boton_id ?>(){

	if( "<?= $idModal ?>" != "" ){
		$("#<?= $idModal ?>").modal("hide")
	} else {
		$("#<?= $boton_id ?>").click();
	}

}

$(document).ready(function() {
   $('input').keypress(function(e){
    if(e.which == 13){
      return false;
    }
  });
});
</script>
