<?php

use app\utils\db\utb;
use yii\grid\GridView;
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\web\Session;

/**
 * Función que se encarga de mostrar una ventana modal para búsqueda.
 *
 * @param string $tabla Nombre de la tabla de la que se extraerán los datos.
 * @param array $parametros Arreglo con los parametros como deben ser buscados en la BD y nombre con el que se identificarán.
 *          [ 'cod' =>  'Código' ]
 * @param string $campocod Nombre de la columna de la que se extraerá el código.
 * @param string $camponombre Nombre de la columna de la que se extraerá el nombre.
 * @param string boton_id Id del botón desde el cual se llama a la ventana modal. ...Por defecto boton_id = 'BuscaAux'...
 * @param string idcampocod Id del campo en el que se agregará el código
 * @param string idcamponombre Id del campo en el que se agregará el nombre
 * @param String claseGrilla = null Clase que se le debe colocar a la grilla que muestra los datos
 * @param string idModal Identificador de la ventana modal. Se usa para cerrar la ventana modal.
 * @param array $sort Arreglo con el ordenamiento de la grilla.
 */

$parametros     = isset( $parametros ) ? $parametros : [];
$alinacion     = isset( $alinacion ) ? $alinacion : [];
$claseGrilla 	= isset( $claseGrilla ) ? $claseGrilla : null;
$campocod		= isset( $campocod ) ? $campocod : 'cod';
$camponombre    = isset( $camponombre ) ? $camponombre : 'nombre';
$idcamponombre 	= isset( $idcamponombre ) ? $idcamponombre : '';
$boton_id		= isset( $boton_id ) ? $boton_id : 'BuscaAux';
$cantmostrar	= isset( $cantmostrar ) ? $cantmostrar : 12;
$idAux			= isset( $idAux ) ? $idAux : 'Aux';
$criterio		= isset( $criterio ) ? $criterio : '';
$idModal		= isset( $idModal ) ? $idModal : '';
$sort			= isset( $sort ) ? $sort : [];

echo '<label class="control-label">Filtrar por Nombre:</label>';

echo Html::input('text'.$idAux, 'txnombre', '', [
    'class'     => 'form-control',
    'id'        => 'txnombre'.$idAux,
    'style'     => 'width:270px;text-transform: uppercase',
    'maxlength' => '250',
    'onchange'  => 'BuscarNombre'.$idAux.'()',
]);

echo '<br><br>';

$campos = implode( ",", array_keys( $parametros ));

//Grabar en memoria el parámetro de búsqueda
Yii::$app->session->set( 'criterio', $criterio );

Pjax::begin([ 'id' => 'DivGrillaAux', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);// comienza bloque de grilla

    if( !isset( $criterio ) ){

        $criterio = Yii::$app->session->get( 'criterio', '' ); //Obtener criterio

    }

    $nombre = Yii::$app->request->post( 'nombre', '' );

    $criterio .= ( $criterio != "" ? " and " : "" ) . " upper($camponombre) like upper('%". $nombre ."%')";

    Yii::$app->session->set( 'criterio', $criterio );

    $columnas = [];

    foreach( $parametros as $cod => $nombre ){

        $columnas[] = ['attribute'=> $cod,'header' => $nombre, 'contentOptions'=>['style'=>'width:1px;']];
    }
	
	if (count($alinacion) > 0){
		foreach( $alinacion as $cod => $nombre ){
			$i = -1;
			foreach ($columnas as $c){
				if ($c['attribute'] == $cod )
					$c['contentOptions']['style'] = 'width:1px;text-align:' . $nombre;
				$i += 1;	
			}
		}
		
		$columnas[$i] = $c;
	}

    $dataProvider = utb::DataProviderGeneral( $tabla, $criterio, $campos, $cantmostrar);

    if( $sort != [] ){
    	$dataProvider->setSort( $sort );
    }

    echo GridView::widget([
		'id' => 'GrillaAux'.$idAux,
		'dataProvider' => $dataProvider,
		'headerRowOptions' => ['class' => $claseGrilla],
        'rowOptions' => function ($model,$key,$index,$grid) use ( $parametros, $campocod, $camponombre, $claseGrilla, $boton_id )
        				{
        					return
        					[
								'ondblclick'=>'btnAuxBuscar'. $boton_id .'("'.$model[$campocod].'","'.$model[$camponombre].'");',
								'onclick'=>'btnAuxBuscar2'. $boton_id .'("'.$model[$campocod].'","'.$model[$camponombre].'", $(this));',
								'class' => $claseGrilla,
							];
        				},
		'columns' => $columnas,

    ]);

Pjax::end(); // fin bloque de la grilla

?>

<script type="text/javascript">

function BuscarNombre<?=$idAux?>()
{
    var nombre = $( "#txnombre<?= $idAux ?>").val();

	$.pjax.reload({

		container    : "#DivGrillaAux",
        method	     : "POST",
		replace	     : false,
		push         : false,
        timeout      : 100000,
		data:{
            "nombre"  : nombre,
        },

	});
}

function btnAuxBuscar<?= $boton_id ?>(cod,nombre)
{
	$("#<?= $idcampocod ?>").val(cod);
	$("#<?= $idcamponombre ?>").val(nombre);

	cerrarVentanaModal();
}

function btnAuxBuscar2<?= $boton_id ?>(cod,nombre,$fila)
{
	$("#<?= $idcampocod ?>").val( cod );
	$("#<?= $idcamponombre ?>").val(nombre);

	$( ".<?= $claseGrilla ?>" ).each(function() {
		$( this ).removeClass( "success" );
	});

	$fila.addClass( "success" );
}

function cerrarVentanaModal(){

	if( "<?= $idModal ?>" != "" ){
		$( "<?= $idModal ?>" ).modal( "hide" );
	} else {
		$("#<?= $boton_id ?>").click();
	}

}

</script>
