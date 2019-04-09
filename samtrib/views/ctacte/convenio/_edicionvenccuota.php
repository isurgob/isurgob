<?php
use yii\helpers\Html;
use app\utils\db\utb;
use yii\jui\DatePicker;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\helpers\BaseUrl;

Modal::begin([
'id' => 'ModalEditarVencCuota',
'header' => '<h2>Editar vencimiento de Cuota</h2>',
'options' => ['data-pjax' => '0'],
'closeButton' => [
	  'label' => '<b>X</b>',
	  'class' => 'btn btn-danger btn-sm pull-right',
	],
'size'=>'modal-sm'	
]);
	
		echo Html::input("text",'ctacte_id', null, [
								'id' => 'ctacte_id',
								'class' => 'form-control solo-lectura',
								'readOnly' => true,
								'style' => 'width:60px;text-align:center;display:none'
							]);
						
?>
		<div class="form">
			<table border="0" width="100%" cellpadding="3">
				<tr>
					<td> <label>Nº Conv.:</label> </td>
					<td>
						<?= Html::input("text",'plan_id', null, [
								'id' => 'plan_id',
								'class' => 'form-control solo-lectura',
								'readOnly' => true,
								'style' => 'width:60px;text-align:center'
							]);
						?>
						<label>Cuota:</label> 
						<?= Html::input("text",'cuota', null, [
								'id' => 'cuota',
								'class' => 'form-control solo-lectura',
								'readOnly' => true,
								'style' => 'width:60px;text-align:center'
							]);
						?>
					</td>
				</tr>
				<tr>
					<td> <label>Vencimiento:</label> </td>
					<td>
						<?= DatePicker::widget([
								'name' => 'fchvenc',
								'id' => 'fchvenc',
								'value' => null,
								'dateFormat' => 'dd/MM/yyyy',
								'options' => ['data-pjax' => '0','class'=>'form-control','style'=>'width:70px;']
							]);
						?>
					</td>
				</tr>
			</table>
		</div>
		<div id='DivBotones' style="margin-top:10px;">
		<?php
			echo Html::button('Aceptar', ['class' => 'btn btn-success','id'=>'btAceptarVencCuota','onclick' => 'btAceptarVencCuotaClick()']);
			echo '&nbsp;&nbsp;';
			echo Html::button('Cancelar', ['class' => 'btn btn-primary','id'=>'btCancelarVencCuota','onclick' => '$("#ModalEditarVencCuota").modal("hide")']);
		?>
		</div>

<?php
	
	echo '<div class="error-summary" style="overflow:hidden;display:none;margin-top:10px" id="DivErroVencCuota"><ul></ul></div>';

Modal::end();

?>

<script type="text/javascript">
function btAceptarVencCuotaClick()
{
	$("#DivErroVencCuota").html("");
	$("#DivErroVencCuota").css("display", "none");
	
	$.post("<?= BaseUrl::toRoute(['editarvenccuota']); ?>", {"_cc" : $("#ctacte_id").val(),"_p" : $("#plan_id").val(), "_c" : $("#cuota").val(), "_fv" : $("#fchvenc").val()}, 
		function(resultado){
			if (resultado == ""){
				$("#ModalEditarVencCuota").modal("hide");
			}else{
				$("#DivErroVencCuota").html(resultado);
				$("#DivErroVencCuota").css("display", "block");
			}
		}
	); 
}
</script>
