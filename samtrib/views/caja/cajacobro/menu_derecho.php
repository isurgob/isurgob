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
#cajacobro_md_btApertura,
#cajacobro_md_btReapertura,
#cajacobro_md_btCierre,
#cajacobro_md_btAnulaOperacion,
#cajacobro_md_btAnulaTicket,
#cajacobro_md_btSellado,
#cajacobro_md_btArqueo,
#cajacobro_md_btBoleto,
#cajacobro_md_btListaMDPE {
	background-color:#fff;
	border-width: 0px;
	text-align: left;
	padding: 0px;
	font-weight: bold;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}
</style>

<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3415)) { ?>
	<li id='liApertura' class='glyphicon glyphicon-plus'> <?= Html::button('Apertura',['id'=>'cajacobro_md_btApertura','onclick'=>'$("#ModalAperturaCaja").modal("show")']); ?></li>
	<li id='liReapertura' class='glyphicon glyphicon-plus'>  <?= Html::button('Reapertura',['id'=>'cajacobro_md_btReapertura','onclick'=>'$("#ModalReaperturaCaja").modal("show")']); ?></li>
	<li id='liCierre' class='glyphicon glyphicon-pencil'> <?= Html::button('Cierre',['id'=>'cajacobro_md_btCierre','onclick'=>'$("#ModalCierreCaja").modal("show")']); ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3416)) { ?>
	<li id='liAnulaOpera' class='glyphicon glyphicon-trash'> <?= Html::button('Anular Opera ',['id'=>'cajacobro_md_btAnulaOperacion','onclick'=>'$("#ModalAnulacionOpera").modal("show")']); ?></li>
	<li id='liAnulaTicket' class='glyphicon glyphicon-trash'> <?= Html::button('Anular Ticket ',['id'=>'cajacobro_md_btAnulaTicket','onclick'=>'$("#ModalAnulacionTicket").modal("show")']); ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<li id='liSellado' class='glyphicon glyphicon-list'> <?= Html::button('Sellado',['id'=>'cajacobro_md_btSellado','onclick'=>'muestraSellado()']); ?></li>
	<li id='liBoleto' class='glyphicon glyphicon-list'> <?= Html::button('Boleto',['id'=>'cajacobro_md_btBoleto','onclick'=>'$.pjax.reload({container:"#PjaxBoleto",method:"POST",data:{reiniciar:1}});$("#ModalBoleto").modal("show")']); ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php if (utb::getExisteProceso(3504)) { ?>
	<li id='liComprob' class='glyphicon glyphicon-calendar'> <?= Html::a('<b>Comprobantes</b>',['comprobante'],['id'=>'cajacobro_md_btLComprobante','class' => 'bt-buscar-label']); ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3504)) { ?>
	<li id='liEspecial' class='glyphicon glyphicon-print'> <?= Html::a('<b>Mdp Especial</b>', ['mdpespecial'], ['class' => 'bt-buscar-label']);//Html::button('Mdp Especial',['id'=>'cajacobro_md_btListaMDPE','onclick'=>'mostrarMDPE()']); ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3578)) { ?>
	<li id='liArqueo' class='glyphicon glyphicon-usd'> <?= Html::button('Arqueo',['id'=>'cajacobro_md_btArqueo','onclick'=>'mostrarArqueo()']); ?></li>
	<?php } ?>
</ul>

<?php

	Pjax::begin(['id'=>'PjaxMenuDerecho']);

		// deshabilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';

		//La caja esta "Cerrada"
		if ($model->caja_estado == 'C' && utb::getExisteProceso(3413) == 1)
		{
			// Habilito Apertura y Reapertura
			echo '<script>$("#liApertura").css("pointer-events", "all");</script>';
			echo '<script>$("#liApertura a").css("color", "#337ab7");</script>';
			echo '<script>$("#liApertura").css("color", "#337ab7");</script>';
			echo '<script>$("#liReapertura").css("pointer-events", "all");</script>';
			echo '<script>$("#liReapertura a").css("color", "#337ab7");</script>';
			echo '<script>$("#liReapertura").css("color", "#337ab7");</script>';
		}

		if ($model->caja_estado == 'A' && $model->caja_estado_estado == 'A' && utb::getExisteProceso(3413) == 1)
		{
			// Habilito sellado, boleto

			echo '<script>$("#liSellado").css("pointer-events", "all");</script>';
			echo '<script>$("#liSellado a").css("color", "#337ab7");</script>';
			echo '<script>$("#liSellado").css("color", "#337ab7");</script>';
			echo '<script>$("#liBoleto").css("pointer-events", "all");</script>';
			echo '<script>$("#liBoleto a").css("color", "#337ab7");</script>';
			echo '<script>$("#liBoleto").css("color", "#337ab7");</script>';
		}

		if ($model->caja_posicion == 'C')
		{
			echo '<script>$("#liArqueo").css("pointer-events", "all");</script>';
			echo '<script>$("#liArqueo a").css("color", "#337ab7");</script>';
			echo '<script>$("#liArqueo").css("color", "#337ab7");</script>';
			echo '<script>$("#liEspecial").css("pointer-events", "all");</script>';
			echo '<script>$("#liEspecial a").css("color", "#337ab7");</script>';
			echo '<script>$("#liEspecial").css("color", "#337ab7");</script>';
		}

		if ($model->caja_posicion == 'A1')
		{
			echo '<script>$("#liCierre").css("pointer-events", "all");</script>';
			echo '<script>$("#liCierre a").css("color", "#337ab7");</script>';
			echo '<script>$("#liCierre").css("color", "#337ab7");</script>';
			echo '<script>$("#liAnulaOpera").css("pointer-events", "all");</script>';
			echo '<script>$("#liAnulaOpera a").css("color", "#337ab7");</script>';
			echo '<script>$("#liAnulaOpera").css("color", "#337ab7");</script>';
			echo '<script>$("#liAnulaTicket").css("pointer-events", "all");</script>';
			echo '<script>$("#liAnulaTicket a").css("color", "#337ab7");</script>';
			echo '<script>$("#liAnulaTicket").css("color", "#337ab7");</script>';
			echo '<script>$("#liArqueo").css("pointer-events", "all");</script>';
			echo '<script>$("#liArqueo a").css("color", "#337ab7");</script>';
			echo '<script>$("#liArqueo").css("color", "#337ab7");</script>';
			echo '<script>$("#liEspecial").css("pointer-events", "all");</script>';
			echo '<script>$("#liEspecial a").css("color", "#337ab7");</script>';
			echo '<script>$("#liEspecial").css("color", "#337ab7");</script>';
			echo '<script>$("#liComprob").css("pointer-events", "all");</script>';
			echo '<script>$("#liComprob a").css("color", "#337ab7");</script>';
			echo '<script>$("#liComprob").css("color", "#337ab7");</script>';

		}

	Pjax::end();

?>

<script>

function mostrarArqueo()
{
	$.pjax.reload({
		container:"#PjaxArqueoCaja",
		method:"POST",
		data:{
			arqueo_caja_id:"<?= $model->caja_caja_id ?>",
			arqueo_caja_fecha:"<?= $model->caja_fecha ?>",
			arqueo_reiniciaVariables:1,
	}});

	$("#PjaxArqueoCaja").on("pjax:end", function(){

		$("#ModalArqueo").modal("show");
		$("#PjaxArqueoCaja").off("pjax:end");

	});

}

$( "#PjaxMenuDerecho" ).on( "pjax:end", function() {

	var nada = 1;

	$( "#cajaCobro_codBarra" ).focus();
});

</script>
