<?php
use yii\helpers\Html;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use app\models\ctacte\Liquida;

echo '<input type="text" name="txopera" id="txopera" value="'.$opera.'" style="display:none">';
echo '<input type="text" name="txorden" id="txorden" value="'.$ItemUno['orden'].'" style="display:none">';

?>
<style>
#EditarItem .modal-content{width:660px;}
</style>

<table border='0'>
<tr>
	<td colspan='7'>
		<label>Item: </label>
    	<?php

    		$cond = "item_id in (select item_id from item_vigencia where ".($anio*1000+$cuota);
    		$cond .= " between perdesde and perhasta) ";
    		$cond .= " and Trib_Id=".$trib_id." and tipo not in (2,3,7)";

    		echo Html::dropDownList('dlItem', $item_id, utb::getAux('v_item','item_id',"(nombre)",0,$cond),
    					['prompt'=>'Seleccionar...', 'class' => 'form-control', 'style' => 'width:300px','id'=>'dlItem',
							'onchange' => '$.pjax.reload({container:"#ActBtnItem",data:{
													pjBtnItem:1,trib:'.$trib_id.', anio:'.$anio.', cuota:'.$cuota.',obj:"'.$obj_id.'",subcta:'.$subcta.',
													item:$(this).val(),opera:$("#txopera").val()
												},method:"POST"
											});',
							'disabled' => $opera == 'Agrega' ? false : true
						]);
    	?>
	</td>
</tr>
<?php if (count($ItemDef) > 0) { ?>
	<tr>
		<td>
			<label id='lbParam1'><?=$ItemDef['paramnombre1'];?></label><br>
			<?= Html::input('text', 'txParam1', $param1, [
					'class' => 'form-control',
					'id'=>'txParam1',
					'maxlength' => 12,
					'style'=>'width:90px;visibility:'.($ItemDef['paramnombre1'] == '' ? "hidden" : "visible"),
					'readonly' => ($ItemDef['tcalculo'] == 12 || $ItemDef['tcalculo'] == 15 || $opera == 'Elim' ? true : false)
				]); ?>
		</td>
		<td>
			<label id='lbParam2'><?= $ItemDef['paramnombre2']; ?></label><br>
			<?= Html::input('text', 'txParam2', $ItemUno['param2'], ['class' => 'form-control','id'=>'txParam2','maxlength' => 12,  'style'=>'width:90px;visibility:'.($ItemDef['paramnombre2'] == '' ? "hidden" : "visible"),'readonly' => $opera == 'Elim' ? true : false]); ?>
		</td>
		<td>
			<label id='lbParam3'><?= $ItemDef['paramnombre3']; ?></label><br>
			<?= Html::input('text', 'txParam3', $ItemUno['param3'], ['class' => 'form-control','id'=>'txParam3','maxlength' => 12,  'style'=>'width:90px;visibility:'.($ItemDef['paramnombre3'] == '' ? "hidden" : "visible"),'readonly' => $opera == 'Elim' ? true : false]); ?>
		</td>
		<td>
			<label id='lbParam4'><?= $ItemDef['paramnombre4']; ?></label><br>
			<?= Html::input('text', 'txParam4', $ItemUno['param4'], ['class' => 'form-control','id'=>'txParam4','maxlength' => 12,  'style'=>'width:90px;visibility:'.($ItemDef['paramnombre4'] == '' ? "hidden" : "visible"),'readonly' => $opera == 'Elim' ? true : false]); ?>
		</td>
		<td valign='bottom'>
			<?= Html::Button('Calcular',['class' => 'btn btn-primary', 'id' => 'btCalcularItem', 'onClick' => 'btCalcularAgregar("calcular")','disabled' => $opera == 'Elim' ? true : false]) ?>
		</td>
		<td>
			<label>Módulos</label><br>
			<?= Html::input('text', 'txModulo', $monto, ['class' => 'form-control','id'=>'txModuloItem','disabled'=>'true', 'style'=>'width:90px;background:#E6E6FA;text-align: right']); ?>
		</td>
		<td>
			<label>Total</label><br>
			<?= Html::input('text', 'txTotal', null, ['class' => 'form-control','id'=>'txTotalItem','disabled'=>'true', 'style'=>'width:90px;background:#E6E6FA;text-align: right']); ?>
		</td>
	</tr>
	<tr>
		<td colspan='7'>
			<?= Html::textarea('txObsItems', $ItemDef['obs'] , ['class' => 'form-control','id'=>'txObsItems','disabled'=>'true','style'=>'width:625px;height:50px;max-width:625px;max-height:100px;background:#E6E6FA']); ?>
		</td>
	</tr>
	<tr>
		<td colspan='7'>
			<?= Html::Button('Aceptar',['class' => 'btn btn-success', 'onClick' => 'btCalcularAgregar("aceptar")']) ?>
			&nbsp;
			<?= Html::Button('Cancelar',['class' => 'btn btn-primary', 'onClick' => '$("#EditarItem, .window").modal("toggle");']) ?>
		</td>
	</tr>
<?php } ?>
<tr>
	<td colspan='7'>
		<br><div id="error" style="display:none;" class="alert alert-danger alert-dismissable"></div>
	</td>
</tr>
</table>

<div id="liquida_items_errorSummary" class="error-summary" style="display:none;margin-top: 8px;margin-right: 15px">

</div>

<?php
Pjax::begin(['id' => 'Calcular']);
?>
	<script>
		$('#txModuloItem').val('<?= $valor_mm == 1 ? '' : number_format( $monto, 2, '.', '' ) ?>');
		$('#txTotalItem').val('<?= number_format( $monto*$valor_mm , 2) ?>');

		<?php if ($erroritem != '') {?>
			mostrarErrores( ["<?= $erroritem ?>"], "#liquida_items_errorSummary" );
		<?php }else { ?>
			$( "#liquida_items_errorSummary" ).css( "display", "none" );
		<?php } ?>
	</script>
<?php
Pjax::end();
?>

<script>
function btCalcularAgregar(acc)
{

	if ($("#dlItem").val() == 0)
	{
		mostrarErrores( ['Seleccione un Ítem'], "#liquida_items_errorSummary" );

	}else {

		//Ocultar div errores
		$( "#liquida_items_errorSummary" ).css( "display", "none" );

		item_id = $("#dlItem").val();
		par1 = (($("#txParam1").val()=='') ? 0 : $("#txParam1").val());
		par2 = (($("#txParam2").val()=='') ? 0 : $("#txParam2").val());
		par3 = (($("#txParam3").val()=='') ? 0 : $("#txParam3").val());
		par4 = (($("#txParam4").val()=='') ? 0 : $("#txParam4").val());

		$.pjax.reload(
		{
			container:"#Calcular",
			data:{
				pjCalcular:1,
				item:item_id,
				param1:par1,
				param2:par2,
				param3:par3,
				param4:par4,
				anio:$("#txAnio").val(),
				cuota:$("#txCuota").val(),
				venc:$("#fchvenc").val(),
				accion:acc,
				opera:$("#txopera").val(),
				trib:$("#dlTributo").val(),
				obj:$("#txObj_Id").val(),
				subcta:$("#txSubCta").val(),
				orden:$("#txorden").val()
				},
			method:"POST",
			timeout: 2000
		});
	}

	$("#Calcular").on("pjax:complete", function(){
		if (acc == "aceptar" && $( "#liquida_items_errorSummary" ).css( "display") == "none" ){
			$.pjax.reload(
			{
				container:"#ActGrillaItem",
				data:{
					pjActGrilla:1,
					venc:$("#fchvenc").val(),
					trib:$("#dlTributo").val(),
					ctacte:$("#txCtaCte_Id").val()
					},
				method:"POST"
			});

			$("#EditarItem, .window").modal('hide');
		}

		$("#Calcular").off("pjax:complete");
	});
}

</script>
