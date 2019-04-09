<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\widgets\Pjax;
use app\models\objeto\Comer;

Modal::begin([
	'id' => 'opcBuscar',
	'header' => '<h2>Buscar Comercio</h2>',
	'toggleButton' => false,
	'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
  ],
	'size' => 'modal-sm',
]);

	echo $this->render('buscar');

Modal::end();

?>

<ul id='ulMenuDer' class='menu_derecho'>

	<?php if (utb::getExisteProceso(3200)) { ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?=
			Html::a('<b>Buscar</b>', null, [
				'class' 	=> 'bt-buscar-label',
				'onclick'	=> "f_comerMenuDerecho_btBuscar()",
				'style' => 'cursor:pointer'
			]);
		?>
	</li>

	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3202)) { ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'>
		<?= Html::a('<b>Nuevo</b>', ['create'], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3201)) {?>
	<li id='liModif' class='glyphicon glyphicon-pencil'>
		<?= Html::a('<b>Modificar</b>', ['update','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3203)) {?>
	<li id='liElim' class='glyphicon glyphicon-trash'>
		<?= Html::a('<b>Eliminar</b>', ['delete','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3201) || utb::getExisteProceso(3202) || utb::getExisteProceso(3203)) {?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3209)) {?>
	<li id='liImprimir' class='glyphicon glyphicon-print'>
		<?= Html::a('<b>Imprimir</b>', [ 'imprimir', 'id' => $model->obj_id ], ['class' => 'bt-buscar-label', 'target' => '_black']) ?>
	</li>
	<li id='liImprimirConstHabil' class='glyphicon glyphicon-print'>
		<?= Html::a('<b>Cons. Habil.</b>', [ 'constanciahabil', 'id' => $model->obj_id ], ['class' => 'bt-buscar-label', 'target' => '_black']) ?>
	</li>
	<?php } ?>
	<?php if ( $estado == 'B' && utb::getExisteProceso(3066) ){ ?>
		<li id='liImprimirCertBaja' class='glyphicon glyphicon-print'>
			<?= Html::a('<b>Cert.Baja</b>', ['objeto/objeto/certificadobaja','id' => $model->obj_id ], ['class' => 'bt-buscar-label', 'target' => '_black']) ?>
		</li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3209) || ( $estado == 'B' && utb::getExisteProceso(3066) )){ ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3300)) {?>
	<li id='liCtaCte' class='glyphicon glyphicon-usd'>
		<?= Html::a('<b>Cta. Cte.</b>', ['ctacte/ctacte/index','obj_id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
	<?php if ( utb::getExisteProceso(3330) && $realizaDDJJ ) {?>
	<li id='liDDJJ' class='glyphicon glyphicon-book'>
		<?= Html::a('<b>DDJJ</b>', ['//ctacte/ddjj/listadj','id' => $model->obj_id, 'fs' => 0 ], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3330)) {?>
	<li id='liDDJJAnual' class='glyphicon glyphicon-book'>
		<?= Html::a('<b>DDJJ anual</b>', ['//ctacte/ddjj/ddjjanual', 'obj_id' => $model->obj_id ], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3300) || utb::getExisteProceso(3330)) {?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3200)) {?>
	<li id='liMisc' class='glyphicon glyphicon-comment'>
		<?= Html::a('<b>Misceláneas</b>', ['objeto/objeto/miscelaneas','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
	</li>
	<li id='liVinc' class='glyphicon glyphicon-paperclip'>
		<?= Html::a('<b>Vínculos</b>', ['objeto/objeto/vinculos','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3209)) {?>
	<li id='liHistDomi' class='glyphicon glyphicon-calendar'>
		<?= Html::a('<b>Históricos</b>', ['objeto/objeto/historico','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
	<li id='liAdhe' class='glyphicon glyphicon-calendar'>
		<?= Html::a('<b>Adhesiones</b>', ['caja/debito/view', 'obj' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
	</li>

	<?php if (utb::getExisteProceso(3200) || utb::getExisteProceso(3209)) {?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3202)) {?>
	<li id='liAccAct' class='glyphicon glyphicon-ok'>
		<?= Html::a('<b>Activar</b>', ['objeto/objeto/activar','id' => $model->obj_id], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3212)) {?>
	<li id='liUnif' class='glyphicon glyphicon-magnet'>
		<?= Html::a('<b>Unif./Desunif.</b>', ['objeto/objeto/estado','id' => $model->obj_id, 'taccion' => ( $estado == 'U' ? 4 : 3)], [
			'class' => 'bt-buscar-label',
			'data' => ['method' => 'post'],
		]); ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3217)) {?>
	<li id='liTrans' class='glyphicon glyphicon-share'>
		<?= Html::a('<b>Transferencia</b>', ['objeto/objeto/transferencia','id' => $model->obj_id, 'taccion' => 5, 'b' => 1], [
			'class' => 'bt-buscar-label',
			'data' => ['method' => 'post']
		]); ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3217)) { ?>
	<li id="liDenunciaImpositiva" class="glyphicon glyphicon-tasks">
		<?= Html::a('<b>Denuncia Imp.</b>', ['//objeto/objeto/denunciaimpositiva', 'id' => $model->obj_id, 'b' => 1], ['class' => 'bt-buscar-label']); ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3213)) {?>
	<li id='liAccEx' class='glyphicon glyphicon-star'>
		<?= Html::a('<b>Exento</b>', ['objeto/objeto/estado','id' => $model->obj_id,'taccion' => 6], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3201)) {?>
	<li id='liDomParcelario' class='glyphicon glyphicon-globe'>
		<?= Html::a('<b>Dom. Parcelario</b>', ['objeto/objeto/cambiodomi','id' => $model->obj_id,'taccion' => 32], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3205)) {?>
	<li id='liDomPostal' class='glyphicon glyphicon-globe'>
		<?= Html::a('<b>Dom. Postal</b>', ['objeto/objeto/cambiodomi','id' => $model->obj_id,'taccion' => 7], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?>
	</li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3202) || utb::getExisteProceso(3212) || utb::getExisteProceso(3217) || utb::getExisteProceso(3213) || utb::getExisteProceso(3201) || utb::getExisteProceso(3205)) {?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>

	<?php if (utb::getExisteProceso(3214)) {?>
	<li id='liHabilitacion' class='glyphicon glyphicon-certificate'>
		<?= Html::a('<b>Habilitación</b>', ['habilitacion','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3204)) {?>
	<li id='liCambioDenominacion' class='glyphicon glyphicon-random'>
		<?= Html::a('<b>Cbio. Denom.</b>', ['denominacion','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3219)) {?>
	<li id='liCambioRubro' class='glyphicon glyphicon-transfer'>
		<?= Html::a('<b>Cbio. Rubro</b>', ['rubro','id' => $model->obj_id, 'taccion' => 13], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3219)) {?>
	<li id='liAnexoRubro' class='glyphicon glyphicon-link'>
		<?= Html::a('<b>Anexo Rubro</b>', ['rubro','id' => $model->obj_id, 'taccion' => 12], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3218)) {?>
	<li id='liAccOtr' class='glyphicon glyphicon-cog'>
		<?= Html::a('<b>Otras Acciones</b>', ['objeto/objeto/accion','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
	</li>
	<?php } ?>
</ul>

<script>

	function f_comerMenuDerecho_btBuscar(){

		$( "#opcBuscar" ).modal( "show" );
	}

	$("#ulMenuDer").css("pointer-events", "none");
	$("#ulMenuDer li").css("color", "#ccc");
	$("#ulMenuDer li a").css("color", "#ccc");

	//Cuando se accede al módulo de "Habilitaciones"
	<?php if( $action == 1 ){ ?>

		<?php if( $model->obj_id == '' ){ ?>

		   //Habilita Buscar, Nuevo y Listado
		   $("#liBuscar").css("pointer-events", "all");
		   $("#liBuscar").css("color", "#337ab7");
		   $("#liBuscar a").css("color", "#337ab7");

		   $("#liNuevo").css("pointer-events", "all");
		   $("#liNuevo").css("color", "#337ab7");
		   $("#liNuevo a").css("color", "#337ab7");

		   $("#liListado").css("pointer-events", "all");
		   $("#liListado").css("color", "#337ab7");
		   $("#liListado a").css("color", "#337ab7");

	   <?php } ?>

	   //Cuando se selecciona un objeto
		<?php if( $model->obj_id != '' ){ ?>

			//Habilita todo
		  	$("#ulMenuDer").css("pointer-events", "all");
		  	$("#ulMenuDer li").css("color", "#337ab7");
		  	$("#ulMenuDer li a").css("color", "#337ab7");

			//Deshabilita Activar
			$("#liAccAct").css("pointer-events", "none");
			$("#liAccAct").css("color", "#ccc");
			$("#liAccAct a").css("color", "#ccc");

			//Cuando se selecciona un objeto dado de baja
			<?php if( $estado == 'B' or $estado == 'E' ){ ?>

				<?php if( $estado == 'B' ){ ?>
					//Deshabilita Modificar y Eliminar
					$("#liModif").css("pointer-events", "none");
					$("#liModif").css("color", "#ccc");
					$("#liModif a").css("color", "#ccc");

					$("#liElim").css("pointer-events", "none");
					$("#liElim").css("color", "#ccc");
					$("#liElim a").css("color", "#ccc");
				<?php } ?>	

				//Habilita Activar
			  	$("#liAccAct").css("pointer-events", "all");
			  	$("#liAccAct").css("color", "#337ab7");
			  	$("#liAccAct a").css("color", "#337ab7");

				//Deshabilita Unif/Desunif, Transferencia, Denun. Imp.,Exento, Dom. Parc y Dom. Post.
				$("#liUnif").css("pointer-events", "none");
			  	$("#liUnif").css("color", "#ccc");
			  	$("#liUnif a").css("color", "#ccc");

				$("#liTrans").css("pointer-events", "none");
			  	$("#liTrans").css("color", "#ccc");
			  	$("#liTrans a").css("color", "#ccc");

				$("#liDenunciaImpositiva").css("pointer-events", "none");
			  	$("#liDenunciaImpositiva").css("color", "#ccc");
			  	$("#liDenunciaImpositiva a").css("color", "#ccc");

				$("#liAccEx").css("pointer-events", "none");
			  	$("#liAccEx").css("color", "#ccc");
			  	$("#liAccEx a").css("color", "#ccc");

				$("#liDomParcelario").css("pointer-events", "none");
			  	$("#liDomParcelario").css("color", "#ccc");
			  	$("#liDomParcelario a").css("color", "#ccc");

				$("#liDomPostal").css("pointer-events", "none");
			  	$("#liDomPostal").css("color", "#ccc");
			  	$("#liDomPostal a").css("color", "#ccc");

				//Deshabilita Habilitación, Cbio. Denom., Cbio. Rubro, Anexo Rubro y Otras Acciones
				$("#liHabilitacion").css("pointer-events", "none");
			  	$("#liHabilitacion").css("color", "#ccc");
			  	$("#liHabilitacion a").css("color", "#ccc")

				$("#liCambioDenominacion").css("pointer-events", "none");
			  	$("#liCambioDenominacion").css("color", "#ccc");
			  	$("#liCambioDenominacion a").css("color", "#ccc")

				$("#liCambioRubro").css("pointer-events", "none");
			  	$("#liCambioRubro").css("color", "#ccc");
			  	$("#liCambioRubro a").css("color", "#ccc")

				$("#liAnexoRubro").css("pointer-events", "none");
			  	$("#liAnexoRubro").css("color", "#ccc");
			  	$("#liAnexoRubro a").css("color", "#ccc")

				$("#liAccOtr").css("pointer-events", "none");
			  	$("#liAccOtr").css("color", "#ccc");
			  	$("#liAccOtr a").css("color", "#ccc")

		  	<?php } ?>

		<?php } ?>

	<?php } ?>

</script>
