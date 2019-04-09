<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use app\controllers\objeto\CemController;
use yii\widgets\Pjax;
?>
<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3230)){ ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
    		'id' => 'opcBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Fallecido</h2>',
			'toggleButton' => [
                    'label' => '<b>Buscar</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			echo $this->render('buscar_fall');

			Modal::end();
		?>
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3232)){ ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['createfall'], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3231)){ ?>
	<li id='liModif' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Modificar</b>', ['updatefall','id' => $model->fall_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3233)){ ?>
	<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>', ['deletefall','id' => $model->fall_id,'accion' => 0], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']]) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3231)){ ?>
	<li id='liCbioParc' class='glyphicon glyphicon-pencil'> <?= Html::button('<b>Cbio.Parcela</b>', ['class' => 'bt-buscar-label', 'onclick' => 'f_modalCbioParcela()']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3231) or utb::getExisteProceso(3232) or utb::getExisteProceso(3233)){ ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3238)){ ?>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir</b>', ['imprimirfall','id' => $model->fall_id], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3233)){ ?>
	<li id='liCta' class='glyphicon glyphicon-inbox'> <?= Html::a('<b>Cuenta</b>', ['objeto/cem/view','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li> 
	<?php } ?>
	<?php if (utb::getExisteProceso(3230)){ ?>
	<li id='liServ' class='glyphicon glyphicon-cog'> <?= Html::a('<b>Servicios</b>', ['objeto/cem/servicios','id' => $model->fall_id], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
</ul>

<div align='left'>
	<?php
			
	Modal::begin([
			'id' => 'ModalCbioParcela',
			'class' => 'container',
			'size' => 'modal-normal',
			'header' => '<h2><b>Cambiar Parcela</b></h2>',
			'closeButton' => [
				'label' => '<b>X</b>',
				'class' => 'btn btn-danger btn-sm pull-right'
				],
		]);

		Pjax::begin([ 'id' => 'pjaxCbioParcela' ]);
			echo CemController::viewCbioparcela( $model->fall_id, $model->obj_id, "#ModalCbioParcela", "#pjaxCbioParcela" );
		Pjax::end();	

	Modal::end();

	?>
</div>

<?php 
if ($model->fall_id == null || $model->fall_id <= 0)
{
	// dashabilito todas las opciones 
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	
	// y luego solo habilito buscar y nuevo
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
}else
{
	if ($model->obj_id === '')
	{
		// el codigo de objeto es vacio
		echo '<script>$("#liCta").css("pointer-events", "none");</script>';
		echo '<script>$("#liCta").css("color", "#ccc");</script>';
		echo '<script>$("#liCta a").css("color", "#ccc");</script>';
	}

	if($model->fall_id > 0){

		echo '<script>$("#liModif").css("pointer-events", "all");</script>';
		echo '<script>$("#liModif").css("color", "#337ab7");</script>';
		echo '<script>$("#liModif a").css("color", "#337ab7");</script>';

		echo '<script>$("#liElim").css("pointer-events", "all");</script>';
		echo '<script>$("#liElim").css("color", "#337ab7");</script>';
		echo '<script>$("#liElim a").css("color", "#337ab7");</script>';

		echo '<script>$("#liCbioParc").css("pointer-events", "all");</script>';
		echo '<script>$("#liCbioParc").css("color", "#337ab7");</script>';
		echo '<script>$("#liCbioParc button").css("color", "#337ab7");</script>';
		
		echo '<script>$("#liImprimir").css("pointer-events", "all");</script>';
		echo '<script>$("#liImprimir").css("color", "#337ab7");</script>';
		echo '<script>$("#liImprimir a").css("color", "#337ab7");</script>';

		echo '<script>$("#liServ").css("pointer-events", "all");</script>';
		echo '<script>$("#liServ").css("color", "#337ab7");</script>';
		echo '<script>$("#liServ a").css("color", "#337ab7");</script>';
	}
}

if($consulta != 1){
	
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
}
?>

<script>

function f_modalCbioParcela(){
	
	$.pjax.reload({
		container	: "#pjaxCbioParcela",
		type 		: "POST",
		replace		: false,
		push		: false,
        timeout     : 100000
	});
	
	$( "#pjaxCbioParcela" ).on( "pjax:end", function() {
		$("#ModalCbioParcela").modal("show");
	});
}

</script>