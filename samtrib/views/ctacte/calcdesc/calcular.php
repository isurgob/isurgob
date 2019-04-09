<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\jui\DatePicker;
use \yii\bootstrap\Modal;
use app\utils\db\utb;

ini_set('display_errors','on');
	error_reporting(E_ALL);
 
	
if ( $trib_id > 0 ){
	
	//INICIO Modal Busca Objeto Origen
	Modal::begin([
		'id' => 'ModalBuscaObjDescuentoCalcular',
		'size' => 'modal-lg',
		'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
		'closeButton' => [
		  'label' => '<b>&times;</b>',
		  'class' => 'btn btn-danger btn-sm pull-right',
		],
	]);
								
		echo $this->render('//objeto/objetobuscarav', [
			'id' => 'ObjFacilida',
			'txCod' => 'calcularCodigoObjeto',
			'txNom' => 'calcularNombreObjeto',
			'selectorModal' => '#ModalBuscaObjDescuentoCalcular',
			'tobjeto' => utb::getTObjTrib( $trib_id ),
		]);
	
	Modal::end();
	//FIN Modal Busca Objeto Origen
}

//Habilitar o deshabilitar la búsqueda de objeto
echo '<script>$("#calcularCodigoObjeto").toggleClass("read-only", '.($trib_id <= 0 ? 'true' : 'false').' );</script>';
echo '<script>$("#calcularBotonBuscarObjeto").toggleClass("read-only", '.($trib_id <= 0 ? 'true' : 'false').');</script>';



if ( $trib_id <= 0 || $trib_id == '' ) 
	echo '<script>$("#info").css("display", "block")</script>';
else 
	echo '<script>$("#info").css("display", "none")</script>';
	
?>
<div class="row">
	<div class="col-xs-10 col-xs-offset-1">	
		<table id='info'>
			<tr>
				<td width="80px"></td>
				<td colspan='2'>
					<div class="alert alert-warning">
						Antes de realizar el cálculo debe seleccionar un elemento de la grilla.
					</div>
				</td>
			</tr>	
			<tr>
		</table>
		
		<table>
			<tr>
				<td style="width:50px">
					<label class="control-label">Tributo:</label>
				</td>
				<td><?= Html::input('text', 'trib_id', ( $trib_id == 0 || $trib_id == '' ? '' : $trib_id ), ['class' => 'form-control','id'=>'calcularCodigoTributo','style'=>'width:80px','disabled'=>'true', 'visible' => 'false']); ?></td>
				<td>
					<?= Html::input('text', 'trib_nom', utb::getNombTrib( $trib_id ), ['class' => 'form-control','id'=>'trib_nom','style'=>'width:370px','disabled'=>'true']); ?>
				</td>
			</tr>
		</table>
		
		<div class="row"></div>
		
		<table>
			<tr>
				<td style="width:50px">
				  	<label for="monto">Objeto:</label>
				</td>
				<td>
					<input type="text" id="calcularCodigoObjeto" class="form-control read-only" style="width:80px;" maxlength="8" onchange="cambiaObjeto();">
				</td>
				<td>
					<!-- botón de búsqueda modal -->
					<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
							'class' => 'bt-buscar read-only',
							'id'=>'calcularBotonBuscarObjeto',
							'onclick'=>'$("#ModalBuscaObjDescuentoCalcular, .window").modal("show")',
						]);
					?>
					<!-- fin de botón de búsqueda modal -->
				</td>
				<td>
					<?= Html::input('text', null, null, ['class' => 'form-control','id'=>'calcularNombreObjeto','style'=>'width:344px','disabled'=>'false']); ?>
				</td>
			</tr>
		</table>
		
		<div class="row"></div>
		
		<table>
			
			<tr>		
				<td style="width:50px">
					<label>Período:</label>
				</td>
				<td>
					<input type="text" id="anio" class="form-control" style="width:40px;" maxlength="4" onkeypress="return justNumbers( event );">
					<input type="text" id="cuota" class="form-control" style="width:35px;" maxlength="3" onkeypress="return justNumbers( event );">
				</td>	
				
				<td style="width:30px"></td>
				
				<td>
					<label>Monto</label>
				</td>
				<td style="width:5px"></td>
				<td>
					<input type="text" id="monto" class="form-control" style="width:100px; text-align:right;" onkeypress="return justDecimal($(this).val(), event);">
				</td>
				<td style="width:30px"></td>
				<td>
					<label>Fecha pago</label>
				</td>
				<td style="width:5px"></td>
				
				<td>
				
					<?= DatePicker::widget([
							'id' => 'fchpago',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => [
								'class' => 'form-control',
								'style' => 'width:97px; text-align: center',
							],
							'value' => date('Y/m/d'),
						]);
					?>
				
				</td>		
			</tr>
		</table>

		<div class="row"></div>
		
		<table>
			<tr>
				<td style="width:130px"></td>
				<td>
					<button type="button" class="btn btn-primary" id="botonCalcular" onclick="calcular();">Calcular</button>
				</td>
				<td width="30px"></td>
				<td><label class="control-label">Total:</label></td>
				<td>
					<input type="text" id="total" class="form-control" style="width:100px; text-align:right;" disabled>
				</td>
			</tr>
		</table>
	</div>
	
	
</div>
<div id="descuentoCalcular_errorSummary" class="error-summary" style="display:none">
</div>


<script>
rutasCalcular= {};
rutasCalcular.calcular= "<?= BaseUrl::toRoute('calcular'); ?>";
rutasCalcular.nombreObjeto= "<?= BaseUrl::toRoute(['nombreobjeto']); ?>";
</script>

<?php
$this->registerJsFile(BaseUrl::to('@web/js/ctacte/calcdesc/calcular.js'), ['depends' => [\yii\jui\JuiAsset::className()]]);
?>