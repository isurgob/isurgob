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
#btModificarAcep,
#btEliminarAcep,
#btAprobarAcep,
#btProcesoLoteAcep,
#btEntregas,
#btSeguimiento
 {
	background-color:#fff;
	border-width: 0px;
	text-align: left;
	padding: 0px;
	font-weight: bold;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>

<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3360)) { ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
    		'id' => 'IntimaBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Lote</h2>',
			'toggleButton' => [
                    'label' => '<b>Buscar</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-lg',
            
			]);

			echo $this->render('buscarLote', ['model'=>$model,]);

			Modal::end();
		?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3361)) { ?>
	<li id='liModif' class='glyphicon glyphicon-pencil'> <?= Html::Button('<b>Modificar</b>', ['id' => 'btModificarAcep',
																							'data' => [
																								'toggle' => 'modal',
																								'target' => '#ModalModificarLote',
																							],]) ?></li>
																							
	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::Button('<b>Eliminar</b>', ['id' => 'btEliminarAcep',
																							'data' => [
																								'toggle' => 'modal',
																								'target' => '#ModalEliminarLote',
																							],]) ?></li>
																							
	<?php } ?>
	<?php if (utb::getExisteProceso(3362)) { ?>
	<li id='liAprobar' class='glyphicon glyphicon-ok'> <?= Html::Button('<b>Aprobar</b>', ['id' => 'btAprobarAcep',
																							'data' => [
																								'toggle' => 'modal',
																								'target' => '#ModalAprobarLote',
																							],]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3360) or utb::getExisteProceso(3362) or utb::getExisteProceso(3361)) { ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3364)) { ?>
	<li id='liNotif' class='glyphicon glyphicon-pushpin'> <?= Html::a('<b>Notificaciones</b>',['imprimirinti','id'=>$id,'previo'=>1],['class' => 'bt-buscar-label']); ?>
	</li>
	<li id='liImprimirResum' class='glyphicon glyphicon-tasks'> <?= Html::a('<b>Resumen</b>',['imprimirresu','id'=>$id],['class' => 'bt-buscar-label','target'=>'_black']); ?></li>
	<li id='liImprimirEstad' class='glyphicon glyphicon-list'> <?= Html::a('<b>Estadísticas</b>',['imprimiresta','id'=>$id],['class' => 'bt-buscar-label','target'=>'_black']); ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id='liSeguimiento' class='glyphicon glyphicon-eye-open'>&nbsp;<?= Html::a('Seguimiento', ['viewseguimiento'], ['id' => 'btSeguimiento'])?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3361)) { ?>
	<li id='liProcesoLote' class='glyphicon glyphicon-cog'> <?= Html::Button('<b>Proceso Lote</b>', ['id' => 'btProcesoLoteAcep',
																							'data' => [
																								'toggle' => 'modal',
																								'target' => '#ModalProcesoLote',
																							],]) ?></li>
	<li id='liEntregas' class='glyphicon glyphicon-file'>&nbsp;<?= Html::a('Entregas', ['entregas'], ['id' => 'btEntregas']) ?> </li>
	<?php } ?>
</ul>

<?php 

//LA variable baja indica si el recibo que se muestra se encuentra dado de baja
if (($id == '' || $id == null) && $consulta == 1 )
{
	// dashabilito todas las opciones 
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	
	// y luego solo habilito buscar, nuevo, entregas, proceso lote
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
	
	echo '<script>$("#liSeguimiento").css("pointer-events", "all");</script>';
	echo '<script>$("#liSeguimiento a").css("color", "#337ab7");</script>';
	echo '<script>$("#liSeguimiento").css("color", "#337ab7");</script>';
	
	echo '<script>$("#liEntregas").css("pointer-events", "all");</script>';
	echo '<script>$("#liEntregas a").css("color", "#337ab7");</script>';
	echo '<script>$("#liEntregas").css("color", "#337ab7");</script>';
	
	
//	echo '<script>$("#liProcesoLote").css("pointer-events", "all");</script>';
//	echo '<script>$("#liProcesoLote button").css("pointer-events", "all");</script>';
//	echo '<script>$("#liProcesoLote").css("color", "#337ab7");</script>';
//	echo '<script>$("#liProcesoLote button").css("color", "#337ab7");</script>';
	
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
}

//Si el lote se encuentra aprobado, oculto el botón Aprobar
if ( $model->est_nom == 'Aprobado' )
{
	echo '<script>$("#liAprobar").css("pointer-events", "none");</script>';
	echo '<script>$("#liAprobar").css("color", "#ccc");</script>';
	echo '<script>$("#liAprobar").css("color", "#ccc");</script>';
	
} else if( $model->est != null || $model->est != '' )
{ //Si el lote no se encuentra aprobado, oculto el botón Proceso Lote
	echo '<script>$("#liProcesoLote").css("pointer-events", "none");</script>';
	echo '<script>$("#liProcesoLote").css("color", "#ccc");</script>';
	echo '<script>$("#liProcesoLote").css("color", "#ccc");</script>';
}



?>