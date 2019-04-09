<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\BaseUrl;

?>

<?php
	$form = ActiveForm::begin([ 'id'	=> 'formExportar', 'action' => BaseUrl::toRoute( '//exportar/exportar' ) ]);
?>
	<div class="row" style='display: flex; padding:5px 10px;'>
	  <div class="col-md-4 form">
		<label>Formato</label> <br>
		<?=
			Html::activeRadio( $model, 'formato', [
				'label' => 'Libre Office',
				'onchange' => 'cambiaCheckExportar()',
				'value' => 1,
				'uncheck' => null
			]);
		?>
		<br>
		<?=
			Html::activeRadio( $model, 'formato', [
				'label' => 'Microsoft Excel',
				'onchange' => 'cambiaCheckExportar()',
				'value' => 2,
				'uncheck' => null
			]);
		?>
		<br>
		<?=
			Html::activeRadio( $model, 'formato', [
				'label' => 'Archivo de Texto',
				'onchange' => 'cambiaCheckExportar()',
				'value' => 3,
				'uncheck' => null
			]);
		?>
	  </div>
	  <div class="col-md-8 form" style='margin-left:5px;'>
		<label>Parámetros de Exportación</label> <br>
		
		<div id='TituloDetalleExportar'>
			<div class="form-group">
				<label class="col-sm-2">Título:</label>
				<div class="col-sm-10">
					<?=
						Html::activeInput( 'text', $model, 'titulo', [
							'style'		=> 'width:100%',
							'class'		=> 'form-control',
							'maxlength'	=> '50'
						]);
					?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2">Detalle:</label>
				<div class="col-sm-10">
					<?= Html::activeTextarea( $model, 'detalle', [
							'class' => 'form-control',
							'maxlength' => 250,
							'style' => 'width:100%;height:100px;resize:none;'
						]);
					?>
				</div>
			</div>
		</div>
		
		<div id='OpcionesTextoExportar' style='display:none;padding:5px 0px;'>
			<div class="pull-left form" style='width:50%'>
				<label>Delimitador de Campos</label><br>
				<?=
					Html::activeRadio( $model, 'delimitador', [
						'label' => 'Tabulación',
						'onchange' => 'cambiaCheckDelimitadorExportar()',
						'value' => 1,
						'uncheck' => null
					]);
				?>
				<br>
				<?=
					Html::activeRadio( $model, 'delimitador', [
						'label' => 'Línea Vertical',
						'onchange' => 'cambiaCheckDelimitadorExportar()',
						'value' => 2,
						'uncheck' => null
					]);
				?>
				<br>
				<?=
					Html::activeRadio( $model, 'delimitador', [
						'label' => 'Coma',
						'onchange' => 'cambiaCheckDelimitadorExportar()',
						'value' => 3,
						'uncheck' => null
					]);
				?>
				<br>
				<?=
					Html::activeRadio( $model, 'delimitador', [
						'label' => 'Punto y Coma',
						'onchange' => 'cambiaCheckDelimitadorExportar()',
						'value' => 4,
						'uncheck' => null
					]);
				?>
				<br>
				<?=
					Html::activeRadio( $model, 'delimitador', [
						'label' => 'Otro',
						'onchange' => 'cambiaCheckDelimitadorExportar()',
						'value' => 5,
						'uncheck' => null
					]);
				?>
				<?=
					Html::activeInput( 'text', $model, 'delimitadorotro', [
						'style'		=> 'width:50%',
						'class'		=> 'form-control solo-lectura',
						'maxlength'	=> '1'
					]);
				?>
			</div>
			<div class="pull-right" style='margin-left:5px;width:50%;'>
				<div class="form" style='margin-bottom:5px;'>
					<label>Separador de Filas</label><br>
					<?=
						Html::activeRadio( $model, 'separadorfila', [
							'label' => 'LF',
							'value' => 'LF',
							'uncheck' => null
						]);
					?>
					<br>
					<?=
						Html::activeRadio( $model, 'separadorfila', [
							'label' => 'CR',
							'value' => 'CR',
							
						]);
					?>
				</div>
				<div class="form" style='height:48%'>
					<label>Título</label><br>
					<?=
						Html::activecheckbox( $model, 'incluirtitulo', [
							'label'		=> 'Incluir Fila de Título',
							'uncheck'	=> '0'
						]);
					?>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		
	  </div>
	</div>
<?php 
	ActiveForm::end(); 
	
	echo Html::button( 'Exportar', [
				'id'	=> 'btSubmitButtonExportar',
				'class'	=> 'btn btn-success',
				'onclick'	=> 'formExportarSubmit()',
			]);
			
	echo Html::button( 'Cancelar', [
				'id'	=> 'comer_form_btGrabar',
				'class'	=> 'btn btn-primary',
				'onclick'	=> '$("#modalExportar").modal( "hide" )',
			]);		
	
	echo	$form->errorSummary( $model, [
				'id'	=> 'errorSummaryExportar',
				'style'	=> 'margin-top: 8px',
			]);
?>

<script>

function cambiaCheckExportar(){
	
	var formato = $('input:radio[id=exportar-formato]:checked').val();
	
	$("#TituloDetalleExportar").css( "display", ( formato != 3 ? 'block' : 'none' ) );
	$("#OpcionesTextoExportar").css( "display", ( formato == 3 ? 'flex' : 'none' ) );
}

function cambiaCheckDelimitadorExportar(){

	var delimitador = $('input:radio[id=exportar-delimitador]:checked').val();
	
	if ( delimitador == 5 )
		$("#exportar-delimitadorotro").removeClass( "solo-lectura" );
	else
		$("#exportar-delimitadorotro").addClass( "solo-lectura" );
}

function formExportarSubmit(){

	ocultarErrores( "#errorSummaryExportar" );

	if ( $('input:radio[id=exportar-formato]:checked').val() == 3 && $('input:radio[id=exportar-delimitador]:checked').val() == 5 && $("#exportar-delimitadorotro").val() == '' )
		mostrarErrores( ['Ingrese un Delimitador'], "#errorSummaryExportar" );
	else{
		
		// llamo a la funcion que me devolvera los datos
		$.post( "<?= BaseUrl::toRoute( $model->action );?>", <?= $model->parametros ?>
		).success(function(data){
			result = jQuery.parseJSON(data); 
			
			$("#formExportar").append( 
				$("<input>", 
					{ 
						type: 'hidden', 
						name: 'Exportar[datos]', 
						value: JSON.stringify(result.datos)
					}
				 )
			);
			
			$("#formExportar").append( 
				$("<input>", 
					{ 
						type: 'hidden', 
						name: 'Exportar[campos_desc]', 
						value: result.campos_desc
					}
				 )
			);
			
			$("#formExportar").submit();
			
			$("#modalExportar").modal( "hide" );
		});
		
	}	
		
}

</script>