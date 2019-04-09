<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use app\models\objeto\Inm;
?>
<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3070)) { ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
    		'id' => 'opcBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Inmuebles</h2>',
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
	<?php if (utb::getExisteProceso(3072)) { ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['create'], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3071)) { ?>
	<li id='liModif' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Modificar</b>', ['update','id' => $modelObjeto->obj_id, 'm' => ($modelObjeto->est == 'M' ? 1 : 0) ], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3073)) { ?>
	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>', ['delete','id' => $modelObjeto->obj_id, 'accion' => 0], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3071) || utb::getExisteProceso(3072) || utb::getExisteProceso(3073)) { ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if ( utb::getExisteProceso(3072) and $model->regimen == 3 and $modelObjeto->est == 'A' and utb::samConfig()['inm_phmadre'] == 1  ) { // muestro si es PH, esta activo y se usa ph madre ?>
	<li id='liPHMadre' class='glyphicon glyphicon-ok'> <?= Html::a('<b>PH Madre</b>', ['irphmadre', 'id' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if ( utb::samConfig()['inm_phmadre'] == 1 ) { ?>
	<li id='liPHMadreNueva' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nueva PH M.</b>', ['create', 'm' => 1], ['class' => 'bt-buscar-label']) ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3079)) { ?>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir</b>', ['imprimir','id' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Cert.Val.</b>', ['certificadovaluacion','id' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<?php } ?>
	<?php if (utb::getCampo("objeto","obj_id='".$modelObjeto->obj_id."'","est") == 'B' and utb::getExisteProceso(3066)){ ?>
		<li id='liImprimirCertBaja' class='glyphicon glyphicon-print'> <?= Html::a('<b>Cert.Baja</b>', ['objeto/objeto/certificadobaja','id' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3079) or utb::getExisteProceso(3066)) { ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)) { ?>
	<li id='liCtaCte' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Cta. Cte.</b>', ['ctacte/ctacte/index','obj_id' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3070)) { ?>
	<li id='liMisc' class='glyphicon glyphicon-comment'> <?= Html::a('<b>Misceláneas</b>', ['objeto/objeto/miscelaneas','id' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<li id='liVinc' class='glyphicon glyphicon-paperclip'> <?= Html::a('<b>Vínculos</b>', ['objeto/objeto/vinculos','id' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3071)) { ?>
	<li id='liRest' class='glyphicon glyphicon-retweet'> <?= Html::a('<b>Restricciones</b>', ['restricciones', 'id' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3077)) { ?>
	<li id='liHist' class='glyphicon glyphicon-calendar'> <?= Html::a('<b>Históricos</b>', ['objeto/objeto/historico', 'id' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<li id='liAdhe' class='glyphicon glyphicon-calendar'> <?= Html::a('<b>Adhesiones</b>', ['caja/debito/view', 'obj' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php if (utb::getExisteProceso(3000) || utb::getExisteProceso(3070) || utb::getExisteProceso(3071) || utb::getExisteProceso(3077)) { ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id='liObrasPart' class='glyphicon glyphicon-home hidden'> <?= Html::a('<b>Obras Partic.</b>', null, ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3072)) { ?>
	<li id='liAccAct' class='glyphicon glyphicon-ok'> <?= Html::a('<b>Activar</b>', ['objeto/objeto/activar','id' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3082)) { ?>
	<li id='liUnifDesunif' class='glyphicon glyphicon-magnet'> <?= Html::a('<b>Unif./Desunif.</b>', ['objeto/objeto/estado','id' => $modelObjeto->obj_id, 'taccion' => ($modelObjeto->est == 'U' ? 4 : 3)], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3104)) { ?>
	<li id='liTrans' class='glyphicon glyphicon-share'> <?= Html::a('<b>Transferencia</b>', ['objeto/objeto/transferencia','id' => $modelObjeto->obj_id, 'taccion' => 5, 'b' => true], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3104)) { ?>
	<li id="liDenunciaImpositiva" class="glyphicon glyphicon-tasks"> <?= Html::a('<b>Denuncia Imp.</b>', ['//objeto/objeto/denunciaimpositiva', 'id' => $modelObjeto->obj_id, 'b' => 1], ['class' => 'bt-buscar-label']); ?></li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3083)) { ?>
	<li id='liAccEx' class='glyphicon glyphicon-star'> <?= Html::a('<b>Exento</b>', ['objeto/objeto/estado','id' => $modelObjeto->obj_id,'taccion' => 6], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3076)) { ?>
	<li id='liHistDomi1' class='glyphicon glyphicon-globe'> <?= Html::a('<b>Dom. Parcelario</b>', ['objeto/objeto/cambiodomi','id' => $modelObjeto->obj_id,'taccion' => 9], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3075)) { ?>
	<li id='liHistDomi2' class='glyphicon glyphicon-globe'> <?= Html::a('<b>Dom. Postal</b>', ['objeto/objeto/cambiodomi','id' => $modelObjeto->obj_id,'taccion' => 7], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3105)) { ?>
	<li id='liAccOtr' class='glyphicon glyphicon-cog'> <?= Html::a('<b>Otras Acciones</b>', ['accion','id' => $modelObjeto->obj_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
</ul>

<?php
if (($modelObjeto->obj_id == '' || $modelObjeto->obj_id == null) && $consulta==1)
{
	// dashabilito todas las opciones
	echo '<script>$("#ulMenuDer li").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';

	// y luego solo habilito buscar y nuevo y nueva PH Madre 
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
	echo '<script>$("#liPHMadreNueva").css("pointer-events", "all");</script>';
	echo '<script>$("#liPHMadreNueva a").css("color", "#337ab7");</script>';
	echo '<script>$("#liPHMadreNueva").css("color", "#337ab7");</script>';
	
}else
{
	if ($consulta !== 1)
	{
		// si se esta creado, modificando o eliminando => deshabilito todas las opciones
		echo '<script>$("#ulMenuDer li").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	}else {
		// habilito todas las opciones
		echo '<script>$("#ulMenuDer li").css("pointer-events", "all");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#337ab7");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#337ab7");</script>';
	}
}

$est = $modelObjeto->est;
if ($est == 'A')
{
	echo '<script>$("#liAccAct").css("pointer-events", "none");</script>';
	echo '<script>$("#liAccAct").css("color", "#ccc");</script>';
	echo '<script>$("#liAccAct a").css("color", "#ccc");</script>';
}

if ($est == 'B')
{
	// si se esta creado, modificando o eliminando => deshabilito todas las opciones
	echo '<script>$("#ulMenuDer li").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';

	// y luego solo habilito buscar y nuevo
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';

	echo '<script>$("#liAccAct").css("pointer-events", "all");</script>';
	echo '<script>$("#liAccAct").css("color", "#337ab7");</script>';
	echo '<script>$("#liAccAct a").css("color", "#337ab7");</script>';

	echo '<script>$("#liCtaCte").css("pointer-events", "all");</script>';
	echo '<script>$("#liCtaCte").css("color", "#337ab7");</script>';
	echo '<script>$("#liCtaCte a").css("color", "#337ab7");</script>';

	// si tiene permiso para imprimir certificado de baja, habilito la opción
	echo '<script>$("#liImprimirCertBaja").css("pointer-events", "all");</script>';
	echo '<script>$("#liImprimirCertBaja a").css("color", "#337ab7");</script>';
	echo '<script>$("#liImprimirCertBaja").css("color", "#337ab7");</script>';

//	echo '<script>$("#liModif").css("pointer-events", "none");</script>';
//	echo '<script>$("#liModif").css("color", "#ccc");</script>';
//	echo '<script>$("#liModif a").css("color", "#ccc");</script>';
//
//	echo '<script>$("#liElim").css("pointer-events", "none");</script>';
//	echo '<script>$("#liElim").css("color", "#ccc");</script>';
//	echo '<script>$("#liElim a").css("color", "#ccc");</script>';
//
//	//Deshabilito Unif./Desunif.
//	echo '<script>$("#liUnifDesunif").css("pointer-events", "none");</script>';
//	echo '<script>$("#liUnifDesunif").css("color", "#ccc");</script>';
//	echo '<script>$("#liUnifDesunif a").css("color", "#ccc");</script>';
//
//	//Deshabilito Dom.Parc y Dom.Postal
//	echo '<script>$("#liHistDomi1").css("pointer-events", "none");</script>';
//	echo '<script>$("#liHistDomi1").css("color", "#ccc");</script>';
//	echo '<script>$("#liHistDomi1 a").css("color", "#ccc");</script>';
//
//	echo '<script>$("#liHistDomi2").css("pointer-events", "none");</script>';
//	echo '<script>$("#liHistDomi2").css("color", "#ccc");</script>';
//	echo '<script>$("#liHistDomi2 a").css("color", "#ccc");</script>';
//
//	echo '<script>$("#liTrans").css("pointer-events", "none");</script>';
//	echo '<script>$("#liTrans").css("color", "#ccc");</script>';
//	echo '<script>$("#liTrans a").css("color", "#ccc");</script>';
//
//	//Deshabilito Exento
//	echo '<script>$("#liAccEx").css("pointer-events", "none");</script>';
//	echo '<script>$("#liAccEx").css("color", "#ccc");</script>';
//	echo '<script>$("#liAccEx a").css("color", "#ccc");</script>';
}

if($est == 'E'){
	echo '<script>$("#liAccEx").css("pointer-events", "none");</script>';
	echo '<script>$("#liAccEx").css("color", "#ccc");</script>';
	echo '<script>$("#liAccEx a").css("color", "#ccc");</script>';
}

$cant = utb::getCampo("objeto_taccion","tobj=1 and estactual like '%".$est."%' and interno='N'","count(*)");
if ($cant == 0) echo '<script>$("#liAccOtr").css("display", "none");</script>';
?>
