<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\widgets\Pjax;

$hayDatos = $extras['hayDatos'];

Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false]);

?>

<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3363)) { ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		
		<?php
		 	Modal::begin([
    		'id' => 'opcBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Seguimiento de Intimación</h2>',
			'toggleButton' => [
                    'label' => '<b>Buscar</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			echo $this->render('_buscar_seguimiento');

			Modal::end();
		?>
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id="liEditar" class="glyphicon glyphicon-pencil">
		<?php
		 	Modal::begin([
			'id' => 'opcEditar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Editar seguimiento de intimación</h2>',
			'toggleButton' => [
                    'label' => '<b>Editar</b>',
                    'class' => 'bt-buscar-label'],
		    'closeButton' => [
		          'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
		          'class' => 'btn btn-danger btn-sm pull-right',
		        ],
		    'size' => 'modal-sm',
			]);
		
			echo $this->render('_form_seguimiento', ['extras' => $extras]);
		
			Modal::end();
		?>
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3361)) { ?>
	<li id='liNuevoEntrega' class='glyphicon glyphicon-plus'>
		<?php
		 	Modal::begin([
    		'id' => 'opcEntrega',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Nueva Entrega</h2>',
			'toggleButton' => [
                    'label' => '<b>Entrega</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm'
			]);

			echo $this->render('_form_entrega', ['extras' => $extras]);

			Modal::end();
		?>
	</li>
	
	<li id='liNuevoEtapa' class='glyphicon glyphicon-plus'>
		<?php
		 	Modal::begin([
    		'id' => 'opcEtapa',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Nueva Etapa</h2>',
			'toggleButton' => [
                    'label' => '<b>Etapa</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);

			echo $this->render('_form_etapa', ['extras' => $extras]);

			Modal::end();
		?>
	</li>
	<li id='liNuevoEspera' class='glyphicon glyphicon-plus'>
		<?php
		 	Modal::begin([
    		'id' => 'opcEspera',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Compás de Espera</h2>',
			'toggleButton' => [
                    'label' => '<b>Espera</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-normal',
			]);

			echo $this->render('_form_espera', ['extras' => $extras]);

			Modal::end();
		?>
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3363)) { ?>
	<li id='liImprimir' class='glyphicon glyphicon-print'>
		<?= Html::a('<b>Imprimir</b>',['imprimirseg','id'=>$extras['datos']['lote_id'],'obj_id'=>$extras['datos']['obj_id']],
				['class' => 'bt-buscar-label','target'=>'_blank','data-pjax' => "0"]); ?>
	</li>
	<?php } ?>
</ul>


<?php
Pjax::end();
 
// dashabilito todas las opciones 
echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	
// y luego solo habilito buscar y nuevo
echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
echo '<script>$("#liBuscar a").css("color", "#337ab7");</script>';

if($hayDatos)
{
	echo '<script>$("#ulMenuDer").css("pointer-events", "all");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#337ab7");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#337ab7");</script>';
} else {
	
	echo '<script>$("#liImprimir").css("pointer-events", "none");</script>';
	echo '<script>$("#liImprimir").css("color", "#ccc");</script>';
	echo '<script>$("#liImprimir a").css("color", "#ccc");</script>';
}

?>