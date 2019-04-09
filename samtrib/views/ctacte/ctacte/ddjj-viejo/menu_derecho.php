<?php
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \yii\widgets\Pjax;

	/**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 */

	 if (!isset($consulta)) $consulta = 1;
	 if (!isset($accion)) $accion = '';
	 if (!isset($id)) $id = '';

	 //Para poder eliminar una DJ, el estad odebe ser 'A'

	 $est = $model->est;

?>

<style>
#btEliminarAcep,#btListaDJ {
	background-color:#fff;
	border-width: 0px;
	text-align: left;
	padding: 0px;
	font-weight: bold;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}

#DDJJBuscar .modal-content
{
	width: 80%;
	margin-left: 10%;
}
</style>

<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3330)) {?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
	    		'id' => 'DDJJBuscar',
				'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar DDJJ</h2>',
				'toggleButton' => [
	                    'label' => '<b>Buscar</b>',
	                    'class' => 'bt-buscar-label'],
	            'closeButton' => [
	                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
	                  'class' => 'btn btn-danger btn-sm pull-right',
	                ],
	            'size' => 'modal-lg',
				]);

				echo $this->render('buscar');

			Modal::end();
		?>
	</li>
	<li id='liListaDJ' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b>Lista DJ</b>', [ 'listadj','id' => $model->obj_id ], ['class' => 'bt-buscar-label']) ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3331)) {?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['create','obj_id' => $model->obj_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3332)) {?>
	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::Button('<b>Eliminar</b>', ['id' => 'btEliminarAcep',
																						'data' => [
																							'toggle' => 'modal',
																							'target' => '#ModalEliminarDDJJ',
																						],]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3330)) {?>
	<li id='liImprimirEstad' class='glyphicon glyphicon-print'>
	<?php
		$estCC = utb::getCampo('ctacte','ctacte_id='.(isset($model->ctacte_id) ? $model->ctacte_id : 0),'est');

		if ($estCC=='P' || $estCC=='C'){
			echo Html::Button('<b>Imprimir</b>', ['id' => 'btImprimirCons','class' => 'bt-buscar-label',
														'data' => [
															'toggle' => 'modal',
															'target' => '#ModalDDJJImpCons',
														],]);
		}else {
			echo Html::a('<b>Imprimir</b>', ['imprimir','id' => $model->dj_id],['class' => 'bt-buscar-label', 'target' => '_black']);
		}

		Modal::begin([
    		'id' => 'ModalDDJJImpCons',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Imprimir DDJJ</h2>',
			'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			echo '<p style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#000;text-aling:center">';
			echo '<b>La DJJ se encuentra Paga o en Convenio. Desea Imprimir?</b>';
			echo '</p>';
			echo '<br>';
			echo '<center>';
			echo Html::a('<b>Si</b>', ['imprimir','id' => $model->dj_id],['class' => 'btn btn-success', 'target' => '_black',
					'onClick' => '$("#ModalDDJJImpCons, .window").modal("toggle");','style'=>'font-family:Helvetica Neue, Helvetica, Arial, sans-serif;font-size:12px;color:#fff !important']);
			echo "&nbsp;&nbsp;";
	 		echo Html::Button('<b>No</b>', ['class' => 'btn btn-primary', 'id' => 'btEliminarLicCanc',
					'onClick' => '$("#ModalDDJJImpCons, .window").modal("toggle");','style'=>'font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#fff;font-size:12px']);
			echo '</center>';

		Modal::end();

	?>
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3200)) {?>
	<li id='liComercio' class='glyphicon glyphicon-home'> <?= Html::a('<b>Comercio</b>', ['objeto/comer/view','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)) {?>
	<li id='liCtaCte' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Cta. Cte.</b>', ['ctacte/ctacte/index','obj_id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3200) || utb::getExisteProceso(3300)) {?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)) {?>
	<li id='liComparativa' class='glyphicon glyphicon-comment'> <?= Html::a('<b>Comparativa</b>', ['compara'], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)) {?>
	<li id='liListado' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b>Listado</b>', ['listado'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>
	<?php } ?>
</ul>


<?php

//La variable baja indica si el recibo que se muestra se encuentra dado de baja
if ( ( $model->dj_id == '' || $model->dj_id == null ) && $consulta == 1 )
{
	// dashabilito todas las opciones
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';

	// y luego solo habilito buscar ,nuevo, comparativa y listado
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
	echo '<script>$("#liComparativa").css("pointer-events", "all");</script>';
	echo '<script>$("#liComparativa a").css("color", "#337ab7");</script>';
	echo '<script>$("#liComparativa").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado").css("pointer-events", "all");</script>';
	echo '<script>$("#liListado a").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado").css("color", "#337ab7");</script>';

} else
{
	if ($consulta != 1)
	{
		// si se esta creado, modificando o eliminando => deshabilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	} else
	{	// habilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "all");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#337ab7");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#337ab7");</script>';

		if ( $est != 'A' )
		{	//Deshabilito eliminar
			echo '<script>$("#liElim").css("pointer-events", "none");</script>';
			echo '<script>$("#liElim").css("color", "#ccc");</script>';

		}

		if($est == 'B'){

			//no se deja imprimir si la DDJJ se encuentra dada de baja
			echo '<script>$("#liImprimirEstad").css("pointer-events", "none");</script>';
			echo '<script>$("#liImprimirEstad").css("color", "#ccc");</script>';
			echo '<script>$("#liImprimirEstad a").css("color", "#ccc");</script>';
		}
	}
}
?>
