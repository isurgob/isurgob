<?php
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;

	/**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 * 
	 * @param $model
	 */
	 
?>

<ul id='ulMenuDer' class='menu_derecho'>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['view', 'consulta' => 0], ['class' => 'bt-buscar-label','data-pjax' => 'false']) ?></li> 
</ul>

<?php 

		//Deshabilito todas las opciones 
		echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
		
		//Si se presiona "Nuevo" o "Modificar" o "Eliminar", se deshabilita todo
		if ( $consulta == 1 )
		{
			 //Habilito todas las opciones 
			echo '<script>$("#ulMenuDer").css("pointer-events", "all");</script>';
			echo '<script>$("#ulMenuDer li").css("color", "#337ab7");</script>';
			echo '<script>$("#ulMenuDer li a").css("color", "#337ab7");</script>';
				
		}
			
?>