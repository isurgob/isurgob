<?php
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \yii\widgets\Pjax;


	/**
	 * @param $model
	 */
	 
	 if (!isset($consulta)) $consulta = 1;
	 if (!isset($accion)) $accion = '';
	 if (!isset($id)) $id = '';
	
?>
<style>
#menuDerecho_btBlanqueo {
	background-color:#fff;
	border-width: 0px;
	text-align: left;
	padding: 0px;
	font-weight: bold;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>

<ul id='ulMenuDer' class='menu_derecho'>
	<?php if ( utb::getExisteProceso(1022) ) { ?>
	<li id='liBlanqueo' class='glyphicon glyphicon-trash'> <?= Html::button('Blanqueo',[
																'id' => 'menuDerecho_btBlanqueo',
																'onclick' => '$.pjax.reload({' .
																				'container:"#limpiaClave",' .
																				'method:"POST",' .
																				'data:{' .
																					'limpiaClave_usuario:'.$model->usr_id.'}});']); 
															?></li>
	<?php } ?>
	<?php if ( utb::getExisteProceso(1023) ) { ?>
	<li id='liAccesos' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b>Accesos</b>',['acceso', 'id' => $model->usr_id], ['class' => 'bt-buscar-label']); ?></li>
	<?php } ?>
</ul>

<?php 

Pjax::begin(['id' => 'limpiaClave', 'enableReplaceState' => false, 'enablePushState' => false]);

	$usr_id = Yii::$app->request->post('limpiaClave_usuario',0);
	
	if ( $usr_id != 0 )
	{
		$res = $model->claveLimpiar($usr_id);
		
		if ( $res == 1 )
		{
			# Los datos se modificaron correctamente
			echo '<script>$.pjax.reload({' .
				'container: "#errorFormSeguridad_editaUsuario",' .
				'type: "POST",' .
				'data: { '.
					'mensaje: "Los datos se modificaron correctamente.",' .
					'm: 1,' .
					'} '. 
			' });</script>';
			
		} else
		{
			# Error al grabar los datos
			# Los datos se modificaron correctamente
			echo '<script>$.pjax.reload({' .
				'container: "#errorFormSeguridad_editaUsuario",' .
				'type: "POST",' .
				'data: { '.
					'mensaje: "Hubo un error al grabar los datos.",' .
					'm: 2,' .
					'} '. 
			' });</script>';
		}
		
	}

Pjax::end();

if ( $consulta == 0 )
{
	//Deshabilito todas las opciones 
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
}

?>

<script>

</script>