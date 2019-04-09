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

?>
<style>
#btEliminarVencidas {
	color: #FFF !important;
}

#FacilidadPagoBuscar .modal-content
{
	width: 80%;
	margin-left: 10%;
}
</style>
<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3440)) { ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
    		'id' => 'FacilidadPagoBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Facilidad</h2>',
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
	<?php } ?>
	<?php if (utb::getExisteProceso(3441)) { ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['view','consulta'=>0,'action'=>0], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>

	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>', ['baja','id'=>$id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>

	<li id='liAprobar' class='glyphicon glyphicon-check'> <?= Html::a('<b>Activar</b>', ['activar','id'=>$id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id='liVencidos' class='glyphicon glyphicon-trash'>
		<?php
		 	Modal::begin([
    		'id' => 'FacilidadPagoVencidos',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Eliminar Vencidos</h2>',
			'toggleButton' => [
                    'label' => '<b>Vencidos</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			?>
			<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
				<tr>
					<td><label>Se van a eliminar las facilidades vencidas.</td>
				<tr>
			</table>

			<div class="text-center" style="margin-top:10px;color:#FFF;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
				<?= Html::a('Aceptar', ['bajavencida'], ['class' => 'btn btn-success','id'=>'btEliminarVencidas']);  ?>
				&nbsp;&nbsp;
				<?= Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("#FacilidadPagoVencidos").modal("hide");'])  ?>
			</div>

		<?php
			Modal::end();
		?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3440)) { ?>
	<li id='liImprimirEstad' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir</b>', ['imprimir','id'=>$id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post'],'target' => '_black', 'data-pjax' => "0"])  ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)) { ?>
	<li id='liCtaCte' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Cta. Cte.</b>', ['ctacte/ctacte/index','obj_id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<li id='liMiscelaneas' class='glyphicon glyphicon-comment'> <?= Html::a('<b>Misceláneas</b>', ['objeto/objeto/miscelaneas','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php if (utb::getExisteProceso(3440)) { ?>
	<li id='liListado' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b>Listado</b>', ['//ctacte/listadofacilida/index'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>
	<?php } ?>
</ul>

<?php

//LA variable baja indica si el recibo que se muestra se encuentra dado de baja
if (($id == '' || $id == null) && $consulta == 1)
{
	// dashabilito todas las opciones
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';

	// y luego solo habilito buscar, nuevo, vencido y listado
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado").css("pointer-events", "all");</script>';
	echo '<script>$("#liListado a").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado").css("color", "#337ab7");</script>';
	echo '<script>$("#liVencidos").css("pointer-events", "all");</script>';
	echo '<script>$("#liVencidos a").css("color", "#337ab7");</script>';
	echo '<script>$("#liVencidos").css("color", "#337ab7");</script>';

}else
{
	if ($consulta != 1)
	{
		// si se esta creado, modificando o eliminando => deshabilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	}else {
		// habilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "all");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#337ab7");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#337ab7");</script>';
	}

	if ($est != 2) //Deshabilito activar
	{
		echo '<script>$("#liAprobar").css("pointer-events", "none");</script>';
		echo '<script>$("#liAprobar").css("color", "#ccc");</script>';
		echo '<script>$("#liAprobar a").css("color", "#ccc");</script>';
	}

	if ($est != 1) //Deshabilito borrar
	{
		echo '<script>$("#liElim").css("pointer-events", "none");</script>';
		echo '<script>$("#liElim").css("color", "#ccc");</script>';
		echo '<script>$("#liElim a").css("color", "#ccc");</script>';
	}
}

?>
