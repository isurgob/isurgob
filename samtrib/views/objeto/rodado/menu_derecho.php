<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
?>

<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3280)){ ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
    		'id' => 'opcBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Rodado</h2>',
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
	<?php if (utb::getExisteProceso(3282)) { ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['create'], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3281)) { ?>
	<li id='liModif' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Modificar</b>', ['update','id' => $extras['model']->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3283)) { ?>
	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>', ['delete','id' => $extras['model']->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3282) or utb::getExisteProceso(3281) or utb::getExisteProceso(3283)) { ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3280)) { ?>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir</b>', ['imprimir','id' => $extras['model']->obj_id], ['class' => 'bt-buscar-label','target'=>'_black']) ?></li>
	<?php } ?>	
	<?php if ($extras['modelObjeto']->est == 'B' and utb::getExisteProceso(3066)){ ?>
		<li id='liImprimirCertBaja' class='glyphicon glyphicon-print'> <?= Html::a('<b>Cert.Baja</b>', ['objeto/objeto/certificadobaja','id' => $extras['model']->obj_id], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3280)) { ?>
	<li id='liImprimirRevisionTecnica' class='glyphicon glyphicon-print'> 
		<?php
		 	Modal::begin([
    		'id' => 'opcImpRevTec',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Certificado Revisión Técnica</h2>',
			'toggleButton' => [
                    'label' => '<b>Revisión Téc.</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

				$form = ActiveForm::begin(['id' => 'formCertRevTec', 'action' => ['revisiontecnica'], 'method' => 'get', 'options' => ['target' => '_black'] ] );
					echo "<center style=font-family:Helvetica Neue, Helvetica, Arial, sans-serif;'>";
					echo Html::input('hidden', 'obj_id', $extras['model']->obj_id);
					echo "<label style='color:#333'> Seleccione Certificado: &nbsp;</label>" ;
					echo Html::dropDownList('texto', null, utb::getAux('texto','texto_id','nombre',0,'tuso=13'), ['class' => 'form-control']);
					echo "<br><br>";
					echo Html::submitButton('Imprimir', ['class' => 'btn btn-success']);
					echo "</center>";
				ActiveForm::end();	

			Modal::end();
		?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3280) or utb::getExisteProceso(3066)){ ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)) { ?>
	<li id='liCtaCte' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Cta. Cte.</b>', ['//ctacte/ctacte/index','obj_id' => $extras['model']->obj_id], ['class' => 'bt-buscar-label']) ?></li> 
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3280)) { ?>
	<li id='liMisc' class='glyphicon glyphicon-comment'> <?= Html::a('<b>Misceláneas</b>', ['//objeto/objeto/miscelaneas','id' => $extras['model']->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<li id='liVinc' class='glyphicon glyphicon-paperclip'> <?= Html::a('<b>Vínculos</b>', ['//objeto/objeto/vinculos','id' => $extras['model']->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3284)) { ?>
	<li id='liHistDomi' class='glyphicon glyphicon-calendar'> <?= Html::a('<b>Históricos</b>', ['//objeto/objeto/historico','id' => $extras['model']->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<li id='liAdhe' class='glyphicon glyphicon-calendar'> <?= Html::a('<b>Adhesiones</b>', ['caja/debito/view', 'obj' => $extras['model']->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php if (utb::getExisteProceso(3280) or utb::getExisteProceso(3284)) { ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3282)) { ?>
	<li id='liAccAct' class='glyphicon glyphicon-ok'> <?= Html::a('<b>Activar</b>', ['//objeto/objeto/activar','id' => $extras['model']->obj_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3287)) { ?>
	<li id='liTrans' class='glyphicon glyphicon-share'> <?= Html::a('<b>Transferencia</b>', ['//objeto/objeto/transferencia','id' => $extras['model']->obj_id, 'taccion' => 5, 'b' => 1], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3287)) { ?>
	<li id="liDenunciaImpositiva" class="glyphicon glyphicon-tasks"> <?= Html::a('<b>Denuncia Imp.</b>', ['//objeto/objeto/denunciaimpositiva', 'id' => $extras['model']->obj_id, 'b' => 1], ['class' => 'bt-buscar-label']); ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3288)) { ?>
	<li id='liAccEx' class='glyphicon glyphicon-star'> <?= Html::a('<b>Exento</b>', ['//objeto/objeto/estado','id' => $extras['model']->obj_id,'taccion' => 6], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3289)) { ?>
	<li id='liDomPostal' class='glyphicon glyphicon-globe'> <?= Html::a('<b>Dom. Postal</b>', ['//objeto/objeto/cambiodomi','id' => $extras['model']->obj_id,'taccion' => 7], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3282) or utb::getExisteProceso(3287) or utb::getExisteProceso(3288) or utb::getExisteProceso(3289)) { ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3291)) { ?>
	<li id='liCambioChasis' class='glyphicon glyphicon-retweet'> <?= Html::a('<b>Cabio. Chasis</b>', ['cambio', 'id' => $extras['modelObjeto']->obj_id, 'taccion' => 10], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3291)) {?>
	<li id='liCambioMotor' class='glyphicon glyphicon-random'> <?= Html::a('<b>Cabio. Motor</b>', ['cambio', 'id' => $extras['modelObjeto']->obj_id, 'taccion' => 11], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3290)) { ?>
	<li id='liAccOtr' class='glyphicon glyphicon-cog'> <?= Html::a('<b>Otras Acciones</b>', ['objeto/objeto/accion','id' => $extras['model']->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
</ul>

<script type="text/javascript">


$("#ulMenuDer").css("pointer-events", "none");
$("#ulMenuDer li").css("color", "#ccc");
$("#ulMenuDer li a").css("color", "#ccc");

<?php	
if($extras['consulta'] == 1){
	
	?>
	//habilito buscar y nuevo
	$("#liBuscar").css("pointer-events", "all");
	$("#liBuscar").css("color", "#337ab7");
	$("#liNuevo").css("pointer-events", "all");
	$("#liNuevo a").css("color", "#337ab7");
	$("#liNuevo").css("color", "#337ab7");
	
	<?php
	//se desactiva activar en caso de que el objeto haya sido dado de baja
	if($extras['modelObjeto']->est === 'B'){
		?>
		$("#liAccAct").css("pointer-events", "all");
		$("#liAccAct a").css("color", "#337ab7");
		$("#liAccAct").css("color", "#337ab7");
		
		$("#liCtaCte").css("pointer-events", "all");
		$("#liCtaCte a").css("color", "#337ab7");
		$("#liCtaCte").css("color", "#337ab7");
		
		// si tiene permiso para imprimir certificado de baja, habilito la opción
		$("#liImprimirCertBaja").css("pointer-events", "all");
		$("#liImprimirCertBaja a").css("color", "#337ab7");
		$("#liImprimirCertBaja").css("color", "#337ab7");
		<?php
	}
	else if($extras['modelObjeto']->obj_id !=  null && strlen($extras['modelObjeto']->obj_id) > 0){
	
		?>
		$("#ulMenuDer").css("pointer-events", "all");
		$("#ulMenuDer li").css("color", "#337ab7");
		$("#ulMenuDer li a").css("color", "#337ab7");
		
		$("#liAccAct").css("pointer-events", "none");
		$("#liAccAct a").css("color", "#ccc");
		$("#liAccAct").css("color", "#ccc");
		<?php
		
		if($extras['modelObjeto']->est === 'E'){
			echo '<script>$("#liAccEx").css("pointer-events", "none");</script>';
			echo '<script>$("#liAccEx").css("color", "#ccc");</script>';
			echo '<script>$("#liAccEx a").css("color", "#ccc");</script>';
		}	
	}	
}



$cant = utb::getCampo("objeto_taccion","tobj=5 and estactual like '%".$extras['modelObjeto']->est."%' and interno='N'","count(*)");
if ($cant == 0) echo '$("#liAccOtr").css("display", "none");';
?>
</script>