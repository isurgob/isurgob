<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use app\models\objeto\Persona;

?>
<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3220)){ ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
    		'id' => 'opcBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Personas</h2>',
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
	<?php if (utb::getExisteProceso(3222)){ ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['create'], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3221)){ ?>
	<li id='liModif' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Modificar</b>', ['update','id' => $model->obj_id,'ajuste'=>isset($_GET['ajuste']) ? $_GET['ajuste'] : 0], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3223)){ ?>
	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>', ['delete','id' => $model->obj_id,'accion' => 0], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3222) or utb::getExisteProceso(3221) or utb::getExisteProceso(3223)){ ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3229)){ ?>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir</b>', ['imprimir','id' => $model->obj_id], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Completo</b>', ['imprimir','id' => $model->obj_id,'comp' => 1], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<?php } ?>
	<?php if( utb::getExisteProceso(3609) and $model->est_ib != 'N' ){ ?>
		<li id='liImprimirIB' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir IB</b>', ['constanciaib','id' => $model->obj_id], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3229)){ ?>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Vínculos</b>', ['imprimir','id' => $model->obj_id,'vinc'=>1], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3229) or utb::getExisteProceso(3066)){ ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)){ ?>
	<li id='liCtaCte' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Cta. Cte.</b>', ['ctacte/ctacte/index','obj_id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if ( utb::getExisteProceso(3330) && Persona::realizaDDJJ() ) {?>
	<li id='liDDJJ' class='glyphicon glyphicon-book'>
		<?= Html::a('<b>DDJJ</b>', ['//ctacte/ddjj/listadj','id' => $model->obj_id, 'fs' => 0 ], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3220)){ ?>
	<li id='liMisc' class='glyphicon glyphicon-comment'> <?= Html::a('<b>Misceláneas</b>', ['objeto/objeto/miscelaneas','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3220)){ ?>
	<li id='liVinc' class='glyphicon glyphicon-paperclip'> <?= Html::a('<b>Vínculos</b>', ['objeto/objeto/vinculos','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3604)){ ?>
	<li id='liHistDomi' class='glyphicon glyphicon-calendar'> <?= Html::a('<b>Históricos</b>', ['objeto/objeto/historico','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<li id='liAdhe' class='glyphicon glyphicon-calendar'> <?= Html::a('<b>Adhesiones</b>', ['caja/debito/view', 'obj' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php if (utb::getExisteProceso(3604) or utb::getExisteProceso(3220) or utb::getExisteProceso(3300)){ ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if( utb::getExisteProceso(3607) && $estado != 'B' ){ ?>
		<li id='liIB' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Inscrip. IB</b>', ['ib','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3223)){ ?>
	<li id='liAccAct' class='glyphicon glyphicon-ok'> <?= Html::a('<b>Activar</b>', ['objeto/objeto/activar','id' => $model->obj_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3228)){ ?>
	<li id='liAccEx' class='glyphicon glyphicon-star'> <?= Html::a('<b>Exento</b>', ['objeto/objeto/estado','id' => $model->obj_id,'taccion' => 6], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3601)){ ?>
	<li id='liAccOtr' class='glyphicon glyphicon-cog'> <?= Html::a('<b>Otras Acciones</b>', ['objeto/objeto/accion','id' => $model->obj_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3222)){ ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id='liDocEsc' class='glyphicon glyphicon-film'> <?= Html::a('<b>Doc.Adjuntos</b>', ['objeto/persona/adjuntos','id' => $model->obj_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
</ul>

<?php
if (($model->obj_id == '' or $model->obj_id == null) and $consulta==1)
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
if ($est == 'A')
{
	echo '<script>$("#liAccAct").css("pointer-events", "none");</script>';
	echo '<script>$("#liAccAct").css("color", "#ccc");</script>';
	echo '<script>$("#liAccAct a").css("color", "#ccc");</script>';
} else if($est == 'E'){

	echo '<script>$("#liAccEx").css("pointer-events", "none");</script>';
	echo '<script>$("#liAccEx").css("color", "#ccc");</script>';
	echo '<script>$("#liAccEx a").css("color", "#ccc");</script>';
}


$cant = utb::getCampo("objeto_taccion","tobj=3 and estactual like '%".$est."%' and interno='N'","count(*)");
if ($cant == 0) echo '<script>$("#liAccOtr").css("display", "none");</script>';
?>
