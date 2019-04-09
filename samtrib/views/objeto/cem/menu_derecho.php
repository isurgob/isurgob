<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
?>
<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3230)) {?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
    		'id' => 'opcBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Cementerios</h2>',
			'toggleButton' => [
                    'label' => '<b>Buscar</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			echo $this->render('buscar');

			Modal::end();
		?>
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3232)) {?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['create'], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3231)) {?>
	<li id='liModif' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Modificar</b>', ['update','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3233)) {?>
	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>', ['delete','id' => $model->obj_id,'accion' => 0], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3232) or utb::getExisteProceso(3231) or utb::getExisteProceso(3233)) {?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3238)) {?>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir</b>', ['imprimir','id' => $model->obj_id], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<?php } ?>	
	<?php if ($modelObjeto->est == 'B' and utb::getExisteProceso(3066)){ ?>
		<li id='liImprimirCertBaja' class='glyphicon glyphicon-print'> <?= Html::a('<b>Cert.Baja</b>', ['objeto/objeto/certificadobaja','id' => $model->obj_id], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3238) or utb::getExisteProceso(3066)){ ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)) {?>
	<li id='liCtaCte' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Cta. Cte.</b>', ['ctacte/ctacte/index','obj_id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li> 
	<?php } ?>
	<?php if (utb::getExisteProceso(3230)) {?>
	<li id='liMisc' class='glyphicon glyphicon-comment'> <?= Html::a('<b>Misceláneas</b>', ['objeto/objeto/miscelaneas','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<li id='liVinc' class='glyphicon glyphicon-paperclip'> <?= Html::a('<b>Vínculos</b>', ['objeto/objeto/vinculos','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3237)) {?>
	<li id='liHistDomi' class='glyphicon glyphicon-calendar'> <?= Html::a('<b>Históricos</b>', ['objeto/objeto/historico','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300) or utb::getExisteProceso(3230) or utb::getExisteProceso(3237)) {?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3234)) {?>
	<li id='liAlq' class='glyphicon glyphicon-home'> <?= Html::a('<b>Alquiler</b>', ['alquiler', 'id' => $model->obj_id]); ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3232)) {?>
	<li id='liAccAct' class='glyphicon glyphicon-ok'> <?= Html::a('<b>Activar</b>', ['objeto/objeto/activar','id' => $model->obj_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3241)) {?>
	<li id='liUnif' class='glyphicon glyphicon-magnet'> <?= Html::a('<b>Unif./Desunif.</b>', ['objeto/objeto/estado','id' => $model->obj_id, 'taccion' => ($modelObjeto->est == 'U' ? 4 : 3)], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3244)) {?>
	<li id='liTrans' class='glyphicon glyphicon-share'> <?= Html::a('<b>Transferencia</b>', ['objeto/objeto/transferencia','id' => $model->obj_id, 'taccion' => 5, 'b' => 1], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3244)) { ?>
	<li id="liDenunciaImpositiva" class="glyphicon glyphicon-tasks"> <?= Html::a('<b>Denuncia Imp.</b>', ['//objeto/objeto/denunciaimpositiva', 'id' => $model->obj_id, 'b' => 1], ['class' => 'bt-buscar-label']); ?></li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3287)) { ?>
	<li id="liDenunciaImpositiva" class="glyphicon glyphicon-tasks"> <?= Html::a('<b>Denuncia Imp.</b>', ['//objeto/objeto/denunciaimpositiva', 'id' => $model->obj_id], ['class' => 'bt-buscar-label']); ?></li>
	<?php } ?>

	<li id='liTraslado' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Traslado CtaCte</b>', ['objeto/cem/traslado','id' => $model->obj_id, 'taccion' => 5], ['class' => 'bt-buscar-label']) ?></li>
	<?php if (utb::getExisteProceso(3242)) {?>
	<li id='liAccEx' class='glyphicon glyphicon-star'> <?= Html::a('<b>Exento</b>', ['objeto/objeto/estado','id' => $model->obj_id,'taccion' => 6], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3243)) {?>
	<li id='liDomPostal' class='glyphicon glyphicon-globe'> <?= Html::a('<b>Dom. Postal</b>', ['objeto/objeto/cambiodomi','id' => $model->obj_id,'taccion' => 7], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3245)) {?>
	<li id='liAccOtr' class='glyphicon glyphicon-cog'> <?= Html::a('<b>Otras Acciones</b>', ['objeto/objeto/accion','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
</ul>

<?php 
if (($model->obj_id == '' || $model->obj_id == null) && $consulta==1)
{
	// dashabilito todas las opciones 
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	
	// y luego solo habilito buscar y nuevo
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
}else
{
	if ($consulta !== 1)
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

$est = utb::getCampo("objeto","obj_id='".$model->obj_id."'","est");
if ($est != 'E' && $est != 'B')//$est == 'A' || $est == 'O') 
{
	echo '<script>$("#liAccAct").css("pointer-events", "none");</script>';
	echo '<script>$("#liAccAct").css("color", "#ccc");</script>';
	echo '<script>$("#liAccAct a").css("color", "#ccc");</script>';
	
} else {
	echo '<script>$("#liAccAct").css("pointer-events", "all");</script>';
	echo '<script>$("#liAccAct").css("color", "#337ab7");</script>';
	echo '<script>$("#liAccAct a").css("color", "#337ab7");</script>';
	
	
	echo '<script>$("#liUnif").css("pointer-events", "none");</script>';
	echo '<script>$("#liUnif").css("color", "#ccc");</script>';
	echo '<script>$("#liUnif a").css("color", "#ccc");</script>';
	
	echo '<script>$("#lirans").css("pointer-events", "none");</script>';
	echo '<script>$("#liTrans").css("color", "#ccc");</script>';
	echo '<script>$("#liTrans a").css("color", "#ccc");</script>';
	
	echo '<script>$("#liTraslado").css("pointer-events", "none");</script>';
	echo '<script>$("#liTraslado").css("color", "#ccc");</script>';
	echo '<script>$("#liTraslado a").css("color", "#ccc");</script>';
	
	echo '<script>$("#liAccEx").css("pointer-events", "none");</script>';
	echo '<script>$("#liAccEx").css("color", "#ccc");</script>';
	echo '<script>$("#liAccEx a").css("color", "#ccc");</script>';
	
	echo '<script>$("#liDomPostal").css("pointer-events", "none");</script>';
	echo '<script>$("#liDomPostal").css("color", "#ccc");</script>';
	echo '<script>$("#liDomPostal a").css("color", "#ccc");</script>';
	
	if ( $est == 'B'){
		//modificar y eliminar
		echo '<script>$("#liModif").css("pointer-events", "none");</script>';
		echo '<script>$("#liModif").css("color", "#ccc");</script>';
		echo '<script>$("#liModif a").css("color", "#ccc");</script>';
		
		echo '<script>$("#liElim").css("pointer-events", "none");</script>';
		echo '<script>$("#liElim").css("color", "#ccc");</script>';
		echo '<script>$("#liElim a").css("color", "#ccc");</script>';	
	}	
}

if($est != 'D'){
	//si el estado del objeto no es 'D' entonces no se lo deja eliminar	
	echo '<script>$("#liElim").css("pointer-events", "none");</script>';
	echo '<script>$("#liElim").css("color", "#ccc");</script>';
	echo '<script>$("#liElim a").css("color", "#ccc");</script>';
}
	


$cant = utb::getCampo("objeto_taccion","tobj=4 and estactual like '%".$est."%' and interno='N'","count(*)");
if ($cant == 0) echo '<script>$("#liAccOtr").css("display", "none");</script>';
?>