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
	 */
	 
	 if (!isset($consulta)) $consulta = 1;
	 if (!isset($accion)) $accion = '';
	 if (!isset($id)) $id = '';
						
?>

<style>
#btModificarAcep,
#btEliminarAcep,
#btAprobarAcep,
#btProcesoLoteAcep,
#btEtapaMas,
#btEtapaMenos {
	background-color:#fff;
	border-width: 0px;
	text-align: left;
	padding: 0px;
	font-weight: bold;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}

#ApremioBuscar .modal-content {
	
	width: 70%;
	margin-left: 15%;
}
</style>

<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3380)) { ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
    		'id' => 'ApremioBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Apremio</h2>',
			'toggleButton' => [
                    'label' => '<b>Buscar</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-sm',
			]);
			
			$form = ActiveForm::begin([
			'id'=>'frmBuscaLote',
			'action'=>['buscar'],]);

			?>
			
			<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
				<tr>
					<td width="50px"><label style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;font-size: 12px;line-height: 1.42857143;color: #333;">Judi Nº:</label></td>
					<td>
						<?= Html::input('text','txBuscar',null,['id'=>'ApremioBuscar-txBuscar','class'=>'form-control','style'=>'width:110px;','onkeypress'=>'return justNumbers(event)','maxlength'=>'8']); ?>
					</td>
				</tr>
				
			</table>
			
			<div class="text-center">
				
				<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
					<tr>
						<td width="80px" height="50px" valign="bottom"><?= Html::SubmitButton('Buscar',['class' => 'btn btn-success']); ?></td>
						<td height="50px" valign="bottom"><?= Html::Button('Cancelar', ['id'=>'btApremioBuscarCanc', 'class' => 'btn btn-primary', 'onclick'=>'$("#ApremioBuscar").modal("hide")']); ?></td>
					</tr>
	 			</table>
	 		
	 		</div>
	 		
	 		<?php
	 		ActiveForm::end();

			Modal::end();
		?>		
	</li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3381)) { ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['view','consulta'=>0,'action'=>0], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>
	<li id='liEditar' class='glyphicon glyphicon-pencil'> <?= Html::Button('<b>Editar</b>', ['id' => 'btModificarAcep',
																							'data' => [
																								'toggle' => 'modal',
																								'target' => '#ModalModificarObsApremio',
																							],]) ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>																						
	<li id='liEtapaMas' class='glyphicon glyphicon-plus'> <?= Html::Button('<b> Etapa </b>', ['id' => 'btEtapaMas',
																							'data' => [
																								'toggle' => 'modal',
																								'target' => '#ModalEtapaMas',
																							],]) ?></li>
	<li id='liEtapaMenos' class='glyphicon glyphicon-minus'> <?= Html::Button('<b> Etapa</b>',['id' => 'btEtapaMenos',
																							'data' => [
																								'toggle' => 'modal',
																								'target' => '#ModalEtapaMenos',
																							],]) ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3380)) { ?>
	<li id='liListado' class='glyphicon glyphicon-list-alt'> <?= Html::a('<b> Listado</b>', ['//ctacte/listadojudi/index'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3385)) { ?>
	<li id='liImpExpe' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imp. Expe.</b>',['imprimirexpe','id'=>$id],['class' => 'bt-buscar-label','target'=>'_black']); ?></li>
	<li id='liImpImp' class='glyphicon glyphicon-print'> 
		<?php
		 	Modal::begin([
	    		'id' => 'ApremioCertif',
				'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Impresión de Planilla Judicial</h2>',
				'toggleButton' => [
	                    'label' => '<b>Certificado</b>',
	                    'class' => 'bt-buscar-label'],
	            'closeButton' => [
	                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
	                  'class' => 'btn btn-danger btn-sm pull-right',
	                ],
	            'size' => 'modal-sm',
				]);
			
				$est = utb::getCampo('judi','judi_id='.($id=='' ? 0 : $id),'est');
				$form = ActiveForm::begin(['id'=>'frmImpCertif','action'=>['imprimircertif'],
					'options'=>['style'=>'font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#000',
					'target'=> ($est == 'B' ? '' : '_black')]]);
				
				?>	
					<?= Html::input('hidden','txImpCertLote',$id,[
							'id' => 'txImpCertLote',
							
						]);
					?>
					
					<table style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
						<tr>
							<td><label>Texto:</label></td>
							<td> 
								<?= Html::dropDownList('dlImpCertTexto', null,utb::getAux('texto','texto_id','nombre',0,'tuso=6'), [ 
										'id'=>'dlImpCertTexto', 'class' => 'form-control',
									]);
								?>
							</td>
						</tr>
						<tr>
							<td><label>Firma1:</label></td>
							<td>
								<?= Html::dropDownList('dlImpCertFirma1', null, utb::getAux('intima_firma','firma_id','nombre',0,'tuso=6'), [ 
										'id'=>'dlImpCertFirma1', 'class' => 'form-control',
									]);
								?>
							</td>
						</tr>
						<tr>
							<td><label>Firma2:</label></td>
							<td>
								<?= Html::dropDownList('dlImpCertFirma2', null, utb::getAux('intima_firma','firma_id','nombre',0,'tuso=6'), [ 
										'id'=>'dlImpCertFirma2', 'class' => 'form-control',
									]);
								?>
							</td>
						</tr>
					</table>
					
					<div class="text-center" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px;margin-top: 8px">
					
					<?= Html::Button('Imprimir',[
							'id'=>'btImprimirCertif',
							'class'=>'btn btn-success',
						]);
					?>
					
					&nbsp;&nbsp;
					
					<?= Html::Button('Cancelar',[
							'class'=>'btn btn-primary',
							'onclick'=>'$("#ApremioCertif").modal("hide")',
						]);
					?>
					
					</div>
					<div id="formImpCerft_errorSummary" class="error-summary" style="display:none;margin-top: 8px; margin-right: 15px">
						<ul style="font-size: 12px;line-height: 20px;"></ul>
					</div>
					
					<script>
						$("#btImprimirCertif").click(function() {
							var error = "";
							
							if ($("#dlImpCertTexto").val() == null) error += "<li>Seleccione un Texto</li>";
							if ($("#dlImpCertFirma1").val() == null) error += "<li>Seleccione la Firma1</li>";
							
							if (error == ""){
								$("#frmImpCertif").submit(); 
								$("#ApremioCertif").modal("hide");
							}else {
								$("#formImpCerft_errorSummary").css("display","block"); 
								$("#formImpCerft_errorSummary ul").html(error); 
							}
								
						});
					</script>
					
				<?php
				
				ActiveForm::end();
			
			Modal::end();
		?>		
	</li>
	<?php } ?>
</ul>

<?php 

//LA variable baja indica si el recibo que se muestra se encuentra dado de baja
if (($id == '' || $id == null) && $consulta == 1)
{
	// dashabilito todas las opciones 
	echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
	echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
	echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	
	// y luego solo habilito buscar ,nuevo y listado
	echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
	echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
	echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
	echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado").css("pointer-events", "all");</script>';
	echo '<script>$("#liListado a").css("color", "#337ab7");</script>';
	echo '<script>$("#liListado").css("color", "#337ab7");</script>';
	
}else
{
	if ($consulta != 1)
	{
		// si se esta creado, modificando o eliminando => deshabilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
	}else {
		// habilito todas las opciones
		echo '<script>$("#ulMenuDer").css("pointer-events", "all");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#337ab7");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#337ab7");</script>';
	}
	
	if ($est != 2) //Deshabilito activar
	{
		echo '<script>$("#liAprobar").css("pointer-events", "none");</script>';
		echo '<script>$("#liAprobar").css("color", "#ccc");</script>';
		echo '<script>$("#liAprobar a").css("color", "#ccc");</script>';
	}
	
	if ($est != 1) //Deshabilito borrar
	{
		echo '<script>$("#liElim").css("pointer-events", "none");</script>';
		echo '<script>$("#liElim").css("color", "#ccc");</script>';
		echo '<script>$("#liElim a").css("color", "#ccc");</script>';		
	}
}

?>