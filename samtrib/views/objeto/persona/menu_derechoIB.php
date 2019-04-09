<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
?>
<ul id='ulMenuDer' class='menu_derecho'>

	<?php if (utb::getExisteProceso(3606)){ ?>
		<li id='liModif' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Modificar</b>', ['ib','id' => $model->obj_id, 'action' => 3 ], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3608)){ ?>
		<li id='liElim' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>', ['ib','id' => $model->obj_id, 'action' => 2 ], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3606)){ ?>
		<li id='liExen' class='glyphicon glyphicon-star'> <?= Html::a('<b>Exento</b>', ['exentoib','id' => $model->obj_id ], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3606)){ ?>
		<li id='liAccAct' class='glyphicon glyphicon-ok'> <?= Html::a('<b>Activar</b>', ['ib','id' => $model->obj_id, 'action' => 4 ], ['class' => 'bt-buscar-label']) ?></li>
	<?php } ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php if (utb::getExisteProceso(3609)){ ?>
		<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir IB</b>', ['constanciaib','id' => $model->obj_id ], ['class' => 'bt-buscar-label', 'target' => '_black']) ?></li>
	<?php } ?>


</ul>

<?php

// dashabilita todas las opciones
echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';


if( $consulta == 1 ){

	//Habilitar "Imprimir"
	echo '<script>$("#liImprimir").css("pointer-events", "all");</script>';
	echo '<script>$("#liImprimir").css("color", "#337ab7");</script>';
	echo '<script>$("#liImprimir a").css("color", "#337ab7");</script>';

	if( $model->est_ib == 'B' || $model->est_ib == 'E' ){

		echo '<script>$("#liAccAct").css("pointer-events", "all");</script>';
		echo '<script>$("#liAccAct").css("color", "#337ab7");</script>';
		echo '<script>$("#liAccAct a").css("color", "#337ab7");</script>';

	} else {

		echo '<script>$("#liModif").css("pointer-events", "all");</script>';
		echo '<script>$("#liModif").css("color", "#337ab7");</script>';
		echo '<script>$("#liModif a").css("color", "#337ab7");</script>';

		echo '<script>$("#liElim").css("pointer-events", "all");</script>';
		echo '<script>$("#liElim").css("color", "#337ab7");</script>';
		echo '<script>$("#liElim a").css("color", "#337ab7");</script>';

		echo '<script>$("#liExen").css("pointer-events", "all");</script>';
		echo '<script>$("#liExen").css("color", "#337ab7");</script>';
		echo '<script>$("#liExen a").css("color", "#337ab7");</script>';

	}
}

?>
