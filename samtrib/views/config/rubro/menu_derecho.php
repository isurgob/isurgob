<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\helpers\BaseUrl;
?>
<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3031)) {?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['create'], ['class' => 'bt-buscar-label']) ?></li>
	<li id='liActMasiva' class='glyphicon glyphicon-refresh'> <?= Html::a('<b>Act. Masiva</b>', ['actualizarmasiva']) ?></li>
	<li id='liVigGeneral' class='glyphicon glyphicon-calendar'> <?= Html::a('<b>Vig. General</b>', ['//config/vigenciageneral/index'], ['class' => 'bt-buscar-label','data' => ['method' => 'post']]) ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::button('<b>Imprimir</b>', ['class' => 'bt-buscar-label', 'onclick' => 'f_imprimir()']) ?></li>
	<li id='liExportar' class='glyphicon glyphicon-export'> <?= Html::button('<b>Exportar</b>', ['class' => 'bt-buscar-label', 'onclick' => '$("#modalExportar").modal("show")']) ?> </li>
</ul>

<script>

function f_imprimir(){

	var fm = $("#filtroNomec").val();
	var fn = $("#filtroNombre").val();
	var fg = $("#filtroGrupo").val();
	var fc = $("#filtroCodigo").val();
	
	window.open("<?= BaseUrl::toRoute('imprimir');?>&fm=" + fm + "&fn=" + fn + "&fg=" + fg + "&fc=" + fc);
}

</script>
