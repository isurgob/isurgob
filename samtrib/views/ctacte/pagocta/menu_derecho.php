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
	 * 
	 * @param $model
	 */
	 
	 if (!isset($consulta)) $consulta = 1;
	 if (!isset($accion)) $accion = '';
	 if (!isset($id)) $id = '';
						
?>
<style>
#compensa_btEliminar {
	background-color:#fff;
	border-width: 0px;
	text-align: left;
	padding: 0px;
	font-weight: bold;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>
<ul id='ulMenuDer' class='menu_derecho'>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
    		'id' => 'CompensacionBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Pago a Cuenta</h2>',
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
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['create','consulta'=>0], ['class' => 'bt-buscar-label']) ?></li>
	<li id='liElim' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Eliminar</b>', ['delete','id'=>$model->pago_id], ['class' => 'bt-buscar-label']) ?></li>
	<li id='liElimVenc' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar Venc.</b>', ['deletevencidas'], ['class' => 'bt-buscar-label']) ?></li>
	<li id='liVenc' class='glyphicon glyphicon-trash'> Vencimiento</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id='liListado' class='glyphicon glyphicon-calendar'> <?= Html::a('<b>Listado</b>', ['//ctacte/listadopagocta/index'], ['class' => 'bt-buscar-label']) ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<li id='liImprimirAdhe' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir</b>', ['imprimir','id'=>$model->pago_id], ['class' => 'bt-buscar-label','target'=>'_black']) ?></li>
</ul>

<?php 

//La variable baja indica si el recibo que se muestra se encuentra dado de baja
if (($id == '' || $id == null) && $consulta == 1)
{
	// dashabilito todas las opciones 
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	
	// y luego solo habilito buscar, nuevo y listado
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar a").css("color", "#337ab7");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
	echo '<script>$("#liElimVenc").css("pointer-events", "all");</script>';
	echo '<script>$("#liElimVenc a").css("color", "#337ab7");</script>';
	echo '<script>$("#liElimVenc").css("color", "#337ab7");</script>';
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
		
		//Deshabilito eliminar en caso de que el estado sea distinto a 'D'
		if ($model->est != 'D')
		{
			echo '<script>$("#liElim").css("pointer-events", "none");</script>';
			echo '<script>$("#liElim a").css("color", "#ccc");</script>';
			echo '<script>$("#liElim").css("color", "#ccc");</script>';
		}
		
	}
}
//Deshabilito eliminar vencidad según ExecProceso(3332)
if (utb::getExisteProceso(3333) != 1)
{
	echo '<script>$("#liElimVenc").css("pointer-events", "none");</script>';
	echo '<script>$("#liElimVenc a").css("color", "#ccc");</script>';
	echo '<script>$("#liElimVenc").css("color", "#ccc");</script>';
}

?>