<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\ExcelGrid;


if (!isset($titulo)) $titulo="";
if (!isset($desc)) $desc="";
if (!isset($grilla)) $grilla="Exportar";
?>
<style>
#<?=$grilla ?> .modal-dialog{text-align:left !important;}
#<?=$grilla ?> .modal-content{width:640px !important;}
</style>
<?php $form = ActiveForm::begin(['action' => ['//site/exportar'],'id'=>'frmExportar'.$grilla,'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="form" style='padding:5px 10px;float:left;margin-bottom:5px;width:135px;height:175px;'>
	<label>Formato</label><br>
	<?= Html::radio('rbFormato',true,['id'=>'rbFormato', 'value' => 'L', 'label'=>'Libre Office','onclick'=>'$("#FrmTexto").css("display","none");$("#FrmOffExc").css("display","block");'])?><br>
	<?= Html::radio('rbFormato',false,['id'=>'rbFormato','value' => 'E', 'label'=>'Microsoft Excel','onclick'=>'$("#FrmTexto").css("display","none");$("#FrmOffExc").css("display","block");'])?><br>
	<?= Html::radio('rbFormato',false,['id'=>'rbFormato','value' => 'T', 'label'=>'Archivo de Texto','onclick'=>'$("#FrmTexto").css("display","block");$("#FrmOffExc").css("display","none");'])?>
</div>
<div class="form" style='float:right;padding:5px 10px;height:175px;margin-bottom:5px'>
	<label>Parámetros de Exportación</label><br />
	<div id='FrmOffExc' style='display:block;width:447px'>
		<table>
		<tr>
			<td>Título:</td><td> <?= Html::input('text', 'txTitulo', $titulo, ['class' => 'form-control','id'=>'txTitulo','maxlength'=>'150','style'=>'width:400px']); ?></td>
		</tr>
		<tr>	
			<td valign='top'>Detalle:</td><td> <?= Html::textarea('txDetalle', str_replace("<br>","\n",$desc), ['class' => 'form-control','id'=>'txDetalle','style'=>'width:400px;height:100px;resize:none']); ?></td>
		</tr>
		</table>
	</div>
	<div id='FrmTexto' style='display:none;width:447px'>
		<div class="form" style="width:50%;margin-bottom:5px;padding:5px;float:left;">
			<u>Delimitador de Campos</u><br>
			<?= Html::radio('rbDelimitador',true,['id'=>'rbDelimitador','value'=>'T', 'label'=>'Tabulación','onclick'=>'$("#txOtroDelim").prop("readonly",true)'])?><br>
			<?= Html::radio('rbDelimitador',false,['id'=>'rbDelimitador','value'=>'L','label'=>'Línea Vertical','onclick'=>'$("#txOtroDelim").prop("readonly",true)'])?><br>
			<?= Html::radio('rbDelimitador',false,['id'=>'rbDelimitador','value'=>'C','label'=>'Coma','onclick'=>'$("#txOtroDelim").prop("readonly",true)'])?><br>
			<?= Html::radio('rbDelimitador',false,['id'=>'rbDelimitador','value'=>'P','label'=>'Punto y Coma','onclick'=>'$("#txOtroDelim").prop("readonly",true)'])?><br>
			<?= Html::radio('rbDelimitador',false,['id'=>'rbDelimitador','value'=>'O','label'=>'Otro:','onclick'=>'$("#txOtroDelim").prop("readonly",false)'])?>&nbsp;
			<?= Html::input('text', 'txOtroDelim', null, ['class' => 'form-control','id'=>'txOtroDelim','maxlength'=>'1','style'=>'width:30px;','readonly'=>'true']); ?>
		</div>
		<div class="form" style="width:215px;float:right;padding:5px;height:80px;">
			<u>Separador de Filas</u><br>
			<?= Html::radio('rbSepFila',true,['id'=>'rbSepFila','value'=>'LF','label'=>'LF'])?><br>
			<?= Html::radio('rbSepFila',false,['id'=>'rbSepFila','value'=>'CR','label'=>'CR'])?>
		</div>
		<div class="form" style="width:215px;float:right;padding:5px;height:60px;margin-top:5px">
			<u>Título</u><br>
			<?= Html::checkbox('ckIncTitulo',true,['id'=>'ckIncTitulo','label'=>'Incluir Fila de Título'])?>
		</div>
	</div>	
</div>
<div style='clear:both;margin-top:10px'>
	<?php
		echo Html::Button('Exportar',['class' => 'btn btn-success', 'onClick' => 'btExportarClick'.$grilla.'()']); 
		echo "&nbsp;&nbsp;";
		echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onClick' => '$("#'.$grilla.', .window").modal("toggle");']);
	?>
</div>
<div id="errorExportar<?=$grilla ?>" style="display:none;clear:both;margin-top:5px" class="alert alert-danger alert-dismissable"></div>


<?php ActiveForm::end(); ?>

<script>
function btExportarClick<?=$grilla?>()
{
	error = '';
	if ($('input:radio[name=rbFormato]:checked').val() == 'T' && $('input:radio[name=rbDelimitador]:checked').val() == 'O' && $("#txOtroDelim").val() == '') error = 'Ingrese Otro Delimitador';
	
	if (error == '')
	{
		$("#frmExportar<?=$grilla ?>").submit();
		$("#<?=$grilla ?>, .window").modal("toggle");
	}else {
		$("#errorExportar<?=$grilla ?>").html(error);
		$("#errorExportar<?=$grilla ?>").css("display", "block");
	} 
}
</script>
