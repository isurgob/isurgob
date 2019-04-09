<?php

use yii\helpers\Html;
use \yii\widgets\Pjax;
use app\models\ctacte\Plan;
use app\utils\db\utb;
use \yii\bootstrap\Modal;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\jui\DatePicker;
?>

<div class="form" style='padding:5px;overflow:hidden;margin-bottom:5px;'>
	<table width='100%'>
	<tr>
		<td width='75px'><label>Total Deuda:</label></td>
		<td width='100px'>
			<?= Html::input('text', 'txTotalDeuda', (isset($totalapagar) ? $totalapagar : null), [
					'class' => 'form-control',
					'id'=>'txTotalDeuda',
					'maxlength' => '10',
					'onkeypress' => 'return justDecimal( $(this).val(), event )',
					'style'=>'width:70px; text-align:right'
				]);
			?>
		</td>
		<td width='100px'><label>Cantidad Cuotas:</label></td>
		<td>
			<?= Html::input('text', 'txCantCuota', (isset($cantcuotas) ? $cantcuotas : null), [
					'class' => 'form-control',
					'id'=>'txCantCuota',
					'maxlength' => '3',
					'onkeypress' => 'return justNumbers( event )',
					'style'=>'width:40px; text-align:center'
				]);
			?>
		</td>
		<td width='100px' align='right'><label>Total Calculado:</label></td>
		<td align='right' width='80px'>
			<?= Html::input('text', 'txSubTotal', null, ['class' => 'form-control','id'=>'txSubTotal', 'readonly'=>'true','style'=>'width:70px; background:#E6E6FA;text-align:right']); ?>
		</td>
	</tr>
	</table>
</div>

<?php 
	$arrayDetCuotas = [];
	if ($CuotasPlan != null) $arrayDetCuotas = $CuotasPlan;	
	echo '<input type="text" name="arrayCuotas" id="arrayCuotas" value="'.urlencode(serialize($arrayDetCuotas)).'" style="display:none">';
	
	echo Html::Button('Nueva Cuota', ['class' => 'btn btn-success', 'onclick'=>'btNuevaCuotaClick();','style'=>'float:right;margin-bottom:5px']);
	
	Pjax::begin(['id'=>'PjaxABMCuotasGrilla']);
		if (isset($_POST['accioncuota'])){
			$arrayDetCuotas = (isset($_POST['arrayDetCuota']) ? unserialize(urldecode(stripslashes($_POST['arrayDetCuota']))) : null);
			
			if ($_POST['accioncuota'] == 'N'){
				$fv = $_POST['vencuota'];
				for ( $c = $_POST['nrocuota']; $c <= $_POST['nrocuotahasta']; $c++ ){
					$fp = ( $_POST['nrocuota'] == $_POST['nrocuotahasta'] ? $_POST['pagocuota'] : $fv );
					if ( $_POST['estcuota'] == 'D' ) $fp = '';
					$arrayDetCuotas[] = [
						'cuota_nom' => $c,
						'capital' => $_POST['capitalcuota'],
						'financia' => ($_POST['financiacuota'] == '' ? 0 : $_POST['financiacuota']),
						'total' => $_POST['totalcuota'],
						'fchvenc' => $fv,
						'est' => $_POST['estcuota'],
						'fchpago' => $fp,
						'boleta' => ($_POST['boletacuota'] == '' ? 0 : $_POST['boletacuota']) 
					];
					$sql = "select to_char('$fv'::date + interval '1 month', 'dd/mm/yyyy')";
					$fv = Yii::$app->db->createCommand($sql)->queryScalar();
				}	
			}
			if ($_POST['accioncuota'] == 'M'){
				for ($i=0; $i<count($arrayDetCuotas);$i++){
					if ($_POST['nrocuota'] == $arrayDetCuotas[$i]['cuota_nom']){
						$arrayDetCuotas[$i]['capital'] = $_POST['capitalcuota'];
						$arrayDetCuotas[$i]['financia'] = $_POST['financiacuota'];
						$arrayDetCuotas[$i]['total'] = $_POST['totalcuota'];
						$arrayDetCuotas[$i]['fchvenc'] = $_POST['vencuota'];
						$arrayDetCuotas[$i]['est'] = $_POST['estcuota'];
						$arrayDetCuotas[$i]['fchpago'] = ( $_POST['estcuota'] == 'D' ? '' : $_POST['pagocuota'] );
						$arrayDetCuotas[$i]['boleta'] = $_POST['boletacuota']; 
					}
				}
			}
			if ($_POST['accioncuota'] == 'E'){
				for ($i=0; $i<count($arrayDetCuotas);$i++){
					if ($_POST['nrocuota'] == $arrayDetCuotas[$i]['cuota_nom']){
						array_splice($arrayDetCuotas, $i, 1); 
					}
				}
			}
		}
		
		echo '<script>$("#arrayCuotas").val("'.urlencode(serialize($arrayDetCuotas)).'")</script>';
		
		$DataDetCuotas = new ArrayDataProvider(['allModels' => $arrayDetCuotas,'key'=>'cuota_nom', 'pagination' => ['pagesize' => count($arrayDetCuotas) ] ]);
		
		echo  GridView::widget([
			'dataProvider' => $DataDetCuotas,
			'id' => 'GrillaDetCuota',
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'summary' => false,
			'columns' => [
				['attribute'=>'cuota_nom','header' => 'Cuota','contentOptions'=>['style'=>'width:60px;text-align:center']],
				['attribute'=>'fchvenc','header' => 'Vencimiento','contentOptions'=>['style'=>'width:80px;text-align:center']],
				['attribute'=>'capital','header' => 'Capital', 'format'=>['decimal',2],'contentOptions'=>['style'=>'width:90px;text-align:right']],
				['attribute'=>'financia','header' => 'Financia','format'=>['decimal',2],'contentOptions'=>['style'=>'width:90px;text-align:right']],
				['attribute'=>'total','header' => 'Total','format'=>['decimal',2],'contentOptions'=>['style'=>'width:90px;text-align:right']],
				['attribute'=>'est','header' => 'Est','contentOptions'=>['style'=>'width:50px;text-align:center']],
				['attribute'=>'fchpago','header' => 'Fecha Pago','contentOptions'=>['style'=>'width:80px;text-align:center']],
				['attribute'=>'boleta','header' => 'Boleta','contentOptions'=>['style'=>'width:50px;text-align:center']],
				
				['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:40px;','align'=>'center'],'template' => '{update}{delete}',
					'buttons'=>[
						'update' => function($url,$model,$key)
	    						  {
	    							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
	    										['class'=>'bt-buscar-label', 'style'=>'color:#337ab7', 'onclick' => 'btModifCuotaClick('.$model['cuota_nom'].')']);
	    						  },
						'delete' => function($url,$model,$key)
	    						  {
	    							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
	    										['class'=>'bt-buscar-label','style'=>'margin-left:5px;color:#337ab7', 'onclick'=>'btElimCuotaClick('.$model['cuota_nom'].')']);
	    						  }
	    			]
				]   
		   	],
		]);
		
	Pjax::end();
	
	Modal::begin([
		'id' => 'ABMCuotas',
		'header' => '<h2 id="ttABMCuotas"></h2>',
		'closeButton' => [
	          'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
	          'class' => 'btn btn-danger btn-sm pull-right',
	        ],
		]);
		
		?>
		
		<div id="ABMCuotas">
		
		<input type="text" name="txABMAccion" id="txABMAccion" value="" style="display:none">
		
		<table id='ControlesABMCtas' width='90%' align='center'>
			<tr>
				<td><b>Nº Cuota:</b></td>
				<td>
					<?= Html::input('text', 'txABMNroCta', null, [
							'class' => 'form-control',
							'id'=>'txABMNroCta',
							'maxlength' => '3',
							'onkeypress' => 'return justNumbers( event )',
							'style'=>'width:40px;text-align:right',
							'onchange' => 'SelectdlABMEst()'
						]);
					?>
					<label id="lbCtaHata">hasta</label>
					<?= Html::input('text', 'txABMNroCtaHasta', null, [
							'class' => 'form-control',
							'id'=>'txABMNroCtaHasta',
							'maxlength' => '3',
							'onkeypress' => 'return justNumbers( event )',
							'style'=>'width:40px;text-align:right',
							'onchange' => 'SelectdlABMEst()'
						]);
					?>
				</td>
				<td><b>Vencimiento:</b></td>
				<td>
					<?= DatePicker::widget([
							'id' => 'fchABMVenc',
							'name' => 'fchABMVenc',
							'dateFormat' => 'dd/MM/yyyy',
							'value' => date('d/m/Y'),
							'options' => [
								'class' => 'form-control',
								'style' => 'width:70px;text-align:center',
							],
						]);
					?>
				</td>
			</tr>
			<tr>
				<td><b>Capital:</b></td>
				<td>
					<?= Html::input('text', 'txABMCap', null, [
							'class' => 'form-control',
							'id'=>'txABMCap',
							'maxlength' => '10',
							'onkeypress' => 'return justDecimal( $(this).val(), event )',
							'style'=>'width:70px;text-align:right',
							'onchange'=>'TotalCuotaABM()',
						]);
					?>
				</td>
				<td><b>Financia:</b></td>
				<td>
					<?= Html::input('text', 'txABMFin', null, [
							'class' => 'form-control',
							'id'=>'txABMFin',
							'maxlength' => '10',
							'onkeypress' => 'return justDecimal( $(this).val(), event )',
							'style'=>'width:70px;text-align:right',
							'onchange'=>'TotalCuotaABM()',
						]);
					?>
				</td>
				<td><b>Total:</b></td>
				<td>
					<?= Html::input('text', 'txABMTotal', null, [
							'class' => 'form-control',
							'id'=>'txABMTotal',
							'readonly'=>'true',
							'style'=>'width:70px; background:#E6E6FA;text-align:right',
							'tabindex' => -1,
						]);
					?>
				</td>
			</tr><tr>
				<td><b>Estado:</b></td>
				<td> 
					<?= Html::dropDownList('dlABMEst','D', ['D'=>'Deuda','P'=>'Pagado'],[
							'class' => 'form-control',
							'id'=>'dlABMEst', 
							'onchange' => 'SelectdlABMEst();',
						]);
					?>
				</td>
				<td><b>Fecha de Pago:</b></td>
				<td>
					<?= DatePicker::widget([
							'id' => 'fchABMPago',
							'name' => 'fchABMPago',
							'dateFormat' => 'dd/MM/yyyy',
							'value' => '',
							'options' => [
								'class' => 'form-control',
								'style' => 'width:70px;text-align:center;pointer-events:none',
								'tabindex'=>'-1',
								'readonly' => 'true'
							],
						]);
					?>
				</td>
				<td><b>N° Boleta:</b></td>
				<td>
					<?= Html::input('text', 'txABMNroBoleta', null, [
							'class' => 'form-control',
							'id'=>'txABMNroBoleta',
							'readonly'=>'true',
							'style'=>'width:100px;text-align:right',
							'maxlength' => '12',
							'onkeypress' => 'return justNumbers( event )',
						]);
					?>
				</td>
			</tr>
		</table>
		
		<div class="text-center" style="margin-top: 8px">
			<?= Html::Button('Aceptar', ['class' => 'btn btn-success', 'onclick'=>'btABMAceptarClick()']); ?>
			&nbsp;&nbsp;
			<?= Html::Button('Cancelar',['class' => 'btn btn-primary','onclick' => '$("#ABMCuotas, .window").modal("hide");']); ?>
		</div>		
		
		<div id="errorabmcuotas" style="display:none;overflow:hidden" class="alert alert-danger alert-dismissable">
		</div>
		
		</div>
				
		<?php
		
	Modal::end();
?>
<script>
function btNuevaCuotaClick()
{
	mayorcuota = 0;
	venc = $("#fchABMVenc").val();
	$('#GrillaDetCuota table tbody tr').each(function() {
	    if ($(this).attr('data-key') && parseInt($(this).attr('data-key')) >= mayorcuota){ 
	    	mayorcuota = parseInt($(this).attr('data-key')) + 1;
	    	fecha = $(this).find("td").eq(1).html().split("/");
	    	if (parseInt(fecha[1])+1 == 13){
	    		venc = fecha[0]+"/"+01+"/"+(parseInt(fecha[2])+1);
	    	}else {
	    		venc = fecha[0]+"/"+(parseInt(fecha[1])+1)+"/"+fecha[2];	
	    	}
	    }
	 });
	
	$("#txABMAccion").val('N');
	$("#txABMNroCta").val(mayorcuota);
	$("#txABMNroCtaHasta").val(mayorcuota);
	$("#fchABMVenc").val(venc);	
	$("#ttABMCuotas").html("Nueva Cuota");
	$("#ControlesABMCtas input").attr('readonly',false);
	$("#ControlesABMCtas select").attr('readonly',false);
	$("#dlABMEst").attr('tabindex','');
	$("#dlABMEst").css('pointer-events','all');
	$("#fchABMVenc").attr('tabindex','');
	$("#fchABMVenc").css('pointer-events','all');
	$("#fchABMPago").val('');
	$("#errorabmcuotas").html("");
	$("#errorabmcuotas").css("display", "none");
	$("#txABMNroCtaHasta").css("visibility", "visible");
	$("#lbCtaHata").css("visibility", "visible");
	$("#ABMCuotas").modal();
}

function btModifCuotaClick(cuota)
{
	$('#GrillaDetCuota table tbody tr').each(function() {
		if ($(this).attr('data-key') && parseInt($(this).attr('data-key')) == cuota){ 
	    	$("#txABMAccion").val('M');
			$("#txABMNroCta").val(cuota);
			$("#fchABMVenc").val($(this).find("td").eq(1).html());
			$("#txABMCap").val($(this).find("td").eq(2).html());
			$("#txABMFin").val($(this).find("td").eq(3).html());
			$("#txABMTotal").val($(this).find("td").eq(4).html());
			$("#dlABMEst").val($(this).find("td").eq(5).html());
			$("#fchABMPago").val($(this).find("td").eq(6).html());
			$("#txABMNroBoleta").val($(this).find("td").eq(7).html());	
			
			$("#ttABMCuotas").html("Modificar Cuota");
			$("#ControlesABMCtas input").attr('readonly',false);
			$("#ControlesABMCtas select").attr('readonly',false);
			$("#dlABMEst").attr('tabindex','');
			$("#dlABMEst").css('pointer-events','all');
			$("#fchABMVenc").attr('tabindex','');
			$("#fchABMVenc").css('pointer-events','all');
			$("#txABMNroCta").attr('readonly',true);
			$("#txABMNroCtaHasta").css("visibility", "hidden");
			$("#lbCtaHata").css("visibility", "hidden");
			$("#errorabmcuotas").html("");
			$("#errorabmcuotas").css("display", "none");
			$("#ABMCuotas").modal();
	    }		
	});
}

function btElimCuotaClick(cuota)
{
	$('#GrillaDetCuota table tbody tr').each(function() {
		if ($(this).attr('data-key') && parseInt($(this).attr('data-key')) == cuota){ 
	    	$("#txABMAccion").val('E');
			$("#txABMNroCta").val(cuota);
			$("#fchABMVenc").val($(this).find("td").eq(1).html());
			$("#txABMCap").val($(this).find("td").eq(2).html());
			$("#txABMFin").val($(this).find("td").eq(3).html());
			$("#txABMTotal").val($(this).find("td").eq(4).html());
			$("#dlABMEst").val($(this).find("td").eq(5).html());
			$("#fchABMPago").val($(this).find("td").eq(6).html());
			$("#txABMNroBoleta").val($(this).find("td").eq(7).html());	
			
			$("#ttABMCuotas").html("Eliminar Cuota");
			$("#ControlesABMCtas input").attr('readonly',true);
			$("#ControlesABMCtas select").attr('readonly',true);
			$("#dlABMEst").attr('tabindex','-1');
			$("#dlABMEst").css('pointer-events','none');
			$("#fchABMVenc").attr('tabindex','-1');
			$("#fchABMVenc").css('pointer-events','none');
			$("#errorabmcuotas").html("");
			$("#errorabmcuotas").css("display", "none");
			$("#txABMNroCtaHasta").css("visibility", "hidden");
			$("#lbCtaHata").css("visibility", "hidden");
			$("#ABMCuotas").modal();
	    }		
	});
}

function SelectdlABMEst()
{
	var soloLectura = ( $("#dlABMEst").val() != 'P' || $("#txABMNroCta").val() != $("#txABMNroCtaHasta").val() );
	
	$("#fchABMPago").attr('readonly', soloLectura);
	$("#fchABMPago").attr('tabindex',(soloLectura ? '-1' : ''));
	$("#fchABMPago").css('pointer-events',(soloLectura ? 'none' : 'all'));
	$("#fchABMPago").val('');
	$("#txABMNroBoleta").attr('readonly',soloLectura);
}

function TotalCuotaABM()
{
	$("#txABMTotal").val(
		($("#txABMCap").val() == '' ? 0 : parseFloat($("#txABMCap").val()))
		 + ($("#txABMFin").val() == '' ? 0 : parseFloat($("#txABMFin").val()))
	);
}

function btABMAceptarClick()
{
	error = '';
	if ($("#txABMNroCta").val() == '') error += 'Ingrese el Número de Cuota desde';
	if ($("#txABMNroCtaHasta").val() == '') error += 'Ingrese el Número de Cuota hasta';
	if ($("#txCantCuota").val() == '') $("#txCantCuota").val(0);
	if (parseInt($("#txABMNroCta").val()) < 0 || parseInt($("#txABMNroCta").val()) > parseInt($("#txCantCuota").val())) error += 'Número de Cuota desde incorrecto';
	if (parseInt($("#txABMNroCtaHasta").val()) < 0 || parseInt($("#txABMNroCtaHasta").val()) > parseInt($("#txABMNroCtaHasta").val())) error += 'Número de Cuota hasta incorrecto';
	if (parseInt($("#txABMNroCta").val()) > parseInt($("#txABMNroCtaHasta").val())) error += 'Rango de cuotas mal ingresada';
	if (parseInt($("#txABMCap").val()) == 0 || $("#txABMCap").val() == '') error += 'Ingrese el Capital';
	
	if (error != "")
    {
    	$("#errorabmcuotas").html(error);
		$("#errorabmcuotas").css("display", "block");
    }else {
    	$("#errorabmcuotas").html("");
		$("#errorabmcuotas").css("display", "none");
		
		$.pjax.reload({
			container:"#PjaxABMCuotasGrilla",
			data:{
				accioncuota:$("#txABMAccion").val(),
				nrocuota:$("#txABMNroCta").val(),
				nrocuotahasta:$("#txABMNroCtaHasta").val(),
				capitalcuota:$("#txABMCap").val(),
				financiacuota:$("#txABMFin").val(),
				totalcuota:$("#txABMTotal").val(),
				vencuota:$("#fchABMVenc").val(),
				estcuota:$("#dlABMEst").val(),
				pagocuota:$("#fchABMPago").val(),
				boletacuota:$("#txABMNroBoleta").val(),
				arrayDetCuota:$("#arrayCuotas").val()
			},
			method:"POST"
		});	
		
		$("#ABMCuotas, .window").modal("hide");
    } 
}
</script>