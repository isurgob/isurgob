<?php

use yii\helpers\Html;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use yii\bootstrap\Alert;

/**
 * Forma que se utiliza para las acciones "Copiar CC" y "Movimientos DDJJ".
 * 
 * Recibo:
 * 	
 * 	+ $model => Modelo de "Fiscaliza".
 * 
 * 	+ $action => Identificador del tipo de acción.
 * 		== 1 => Copiar CC
 * 		== 2 => Movimiento DDJJ
 * 
 */

echo Html::input('hidden','txFiscaID',$model->fisca_id,['id' => 'fiscalizaMov_txFiscaID']);

?>

<!-- DIV Copiar CC - Movimientos DDJJ -->
<div>

<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">
				
			<?php 

			Pjax::begin(['id'=>'errorFormFiscalizacionMov','enablePushState' => false, 'enableReplaceState' => false]);
			
				$mensaje = Yii::$app->request->get('mensajeMov','');
				$m = Yii::$app->request->get('m',2);
			
				if($mensaje != "")
				{ 
			
			    	Alert::begin([
			    		'id' => 'AlertaFormFiscalizacionMov',
						'options' => [
			        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
			        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
			    		],
					]);
			
					echo $mensaje;
							
					Alert::end();
					
					echo "<script>window.setTimeout(function() { $('#AlertaFormFiscalizacionMov').alert('close'); }, 5000)</script>";
				 }
			 
			 Pjax::end();
		
			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<table>
	<tr>
		<td width="85px"><label for="comercio">Comercio</label></td>
		<td>
			<?= Html::input('text','txComerID',$model->obj_id,[
					'id' => 'fiscalizaMov_txComerID',
					'class' => 'form-control solo-lectura',
					'style' => 'width:70px',
				]);
			?>
		<label> - </label>
			<?= Html::input('text','txComerNom',utb::getCampo('v_comer',"obj_id = '" . $model->obj_id . "'",'nombre'),[
					'id' => 'fiscalizaMov_txComerNom',
					'class' => 'form-control solo-lectura',
					'style' => 'width:200px',
				]);
			?>
		</td>
	</tr>
	<tr>
		<td><label for="fiscalizaMov_dlTributo">Tributo</label></td>
		<td colspan="1">
			<?= Html::dropDownList('dlTributo','',utb::getAux('trib','trib_id','nombre',0,'tobj = 2 AND tipo = 2'),[
					'id' => 'fiscalizaMov_dlTributo',
					'class' => 'form-control',
					'style' => 'width:100%',
				]); 
			?>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td width="85px"><label for="fiscalizaMov_txPerdesdeAnio">Período Desde</label></td>
		<td width="40px">
			<?= Html::input('text','txPerdesdeAnio','',[
					'id' => 'fiscalizaMov_txPerdesdeAnio',
					'class' => 'form-control',
					'style' => 'width:100%;text-align:center',
					'onkeypress' => 'return justNumbers(event)',
					'maxlength' => 4,
				]); 
			?>
		</td>
		<td width="30px">
			<?= Html::input('text','txPerdesdeCuota','',[
					'id' => 'fiscalizaMov_txPerdesdeCuota',
					'class' => 'form-control',
					'style' => 'width:100%;text-align:center',
					'onkeypress' => 'return justNumbers(event)',
					'maxlength' => 3,
				]); 
			?>
		</td>
		<td width="20px"></td>
		<td><label for="fiscalizaMov_txPerhastaAnio">Período Hasta</label></td>
		<td width="40px">
			<?= Html::input('text','txPerhastaAnio','',[
					'id' => 'fiscalizaMov_txPerhastaAnio',
					'class' => 'form-control',
					'style' => 'width:100%;text-align:center',
					'onkeypress' => 'return justNumbers(event)',
					'maxlength' => 4,
				]); 
			?>
		</td>
		<td width="30px">
			<?= Html::input('text','txPerhastaCuota','',[
					'id' => 'fiscalizaMov_txPerhastaCuota',
					'class' => 'form-control',
					'style' => 'width:100%;text-align:center',
					'onkeypress' => 'return justNumbers(event)',
					'maxlength' => 3,
				]); 
			?>
		</td>
	</tr>
</table>

<table style="margin-top:10px">
	<tr>
		<td><?= Html::button('Aceptar',[
					'id' => 'fiscalizaMov_btAceptar',
					'class' => 'btn btn-success',
					'onclick' => 'fiscalizaMov_btnAceptar('.$action.')',
				]);
			?>
		</td>
		<td width="20px"></td>
		<td><?= Html::button('Cancelar',[
					'id' => 'fiscalizaMov_btCancelar',
					'class' => 'btn btn-primary',
				]);
			?>
		</td>
	</tr>
</table>
</div> 

<script>
function fiscalizaMov_btnAceptar(cod)
{
	var subcta = $("#fiscalizaMov_txSubcta").val();
	var trib = $("#fiscalizaMov_dlTributo").val();
	var adesde = $("#fiscalizaMov_txPerdesdeAnio").val();
	var cdesde = $("#fiscalizaMov_txPerdesdeCuota").val();
	var ahasta = $("#fiscalizaMov_txPerhastaAnio").val();
	var chasta = $("#fiscalizaMov_txPerhastaCuota").val();
	var obj_id = $("#fiscalizaMov_txComerID").val();
	var fiscaID = $("#fiscalizaMov_txFiscaID").val();
	
	var error = '';
	
	if (adesde == '' && cdesde == '')
		error += '<li>Ingrese un período desde.</li>';
		
	if ((adesde != '' && cdesde == '') || (adesde == '' && cdesde != ''))
		error += '<li>Ingrese un período desde válido.</li>';
	
	if ((ahasta != '' && chasta == '') || (ahasta == '' && chasta != ''))
		error += '<li>Ingrese un período hasta válido.</li>';
	
	if (ahasta != '' && chasta != '')
	{
		if (((parseInt(adesde) * 1000) + cdesde) > ((parseInt(ahasta) * 1000) + chasta))
			error += '<li>Rangos de período mal ingresado.</li>';
	} 
	
	if (error == '')
	{
		if (ahasta == '' && chasta == '')
		{
			$("#fiscalizaMov_txPerhastaAnio").val(9999);
			$("#fiscalizaMov_txPerhastaCuota").val(99);
		}
		
		var ahasta = $("#fiscalizaMov_txPerhastaAnio").val();
		var chasta = $("#fiscalizaMov_txPerhastaCuota").val();
		
		var datos = new Object();
		
		datos.trib_id = trib;
		datos.perdesde = ((parseInt(adesde) * 1000 ) + parseInt(cdesde));
		datos.perhasta = ((parseInt(ahasta) * 1000 ) + parseInt(chasta));
		datos.obj_id = obj_id;
		datos.fisca_id = fiscaID;
		
		$("#ModalMovimientos").toggle();
		
		if (cod == 1)
			$.pjax.reload({
				container: "#PjaxCopiarCC",
				type: "POST",
				replace: false,
				push: false,
				data: {
					datos:datos,
					ejecutarCC:1,
				}
			});
		else
			$.pjax.reload({
				container: "#PjaxMoverDJ",
				type: "POST",
				replace: false,
				push: false,
				data: {
					datos:datos,
					ejecutarDJ:1,
				}
			});
	
	} else
		$.pjax.reload({
			container: "#errorFormFiscalizacionMov",
			type: "GET",
			replace: false,
			push: false,
			data: {
				mensajeMov: error,
		}});
}
</script>