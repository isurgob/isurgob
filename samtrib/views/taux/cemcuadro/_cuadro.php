
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
?>

<?php $form= ActiveForm::begin(['id' => 'formCuadro', 'action' => ['grabarcuadro'] ]); ?>
		
	<?= Html::input( 'hidden', 'txScenario', $scenario, ['id' => 'txScenario'] ); ?>
	
	<table width='100%' id='datosCuerpo'>
		<tr>
			<td width='1%'><label> Id.: </label></td>
			<td width='10%'>
				<?=
                    Html::activeInput( 'text', $model, 'cua_id', [
                        'class' 	=> 'form-control text-center ' . ( $scenario == 'nuevo' ? '' : 'solo-lectura' ),
                        'style'		=> 'width:90%; font-weight: bold',
                        'tabIndex'	=> ( $scenario == 'nuevo' ? '0' : '-1' ),
						'marlenght' => 3,
						'onKeyUp' 	=> "this.value=this.value.toUpperCase();"
                    ]);
                ?>
			</td>
			<td width='5%'><label> Nombre: </label></td>
			<td>
				<?=
                    Html::activeInput( 'text', $model, 'nombre', [
                        'class' 	=> 'form-control ' . ( in_array($scenario, ['nuevo', 'modificar']) ? '' : 'solo-lectura' ),
                        'style'		=> 'width:95%;',
                        'tabIndex'	=> ( in_array($scenario, ['nuevo', 'modificar']) ? '0' : '-1' ),
						'marlenght' => 20
                    ]);
                ?>
			</td>
			<td width='5%'><label> Tipo: </label></td>
			<td>
				<?=
    				Html::activeDropDownList( $model, 'tipo', $tipos, [
    					'class' 	=> 'form-control ' . ( in_array($scenario, ['nuevo', 'modificar']) ? '' : 'solo-lectura' ),
    					'style'	=> 'width:100%',
    					'tabIndex'	=> ( in_array($scenario, ['nuevo', 'modificar']) ? '0' : '-1' )
    				]);
    			?>
			</td>
		</tr>
		<tr> <td colspan='6'> &nbsp; </td> </tr>
		<tr>
			<td colspan='2'> <label> Pedir: </label> </td>
			<td colspan='4'>
				<?=
    				Html::activeCheckbox( $model, 'piso', [
    					'class' 	=> 'form-control',
						'disabled' 	=> ( in_array($scenario, ['nuevo', 'modificar']) ? false : true ),
    					'tabIndex'	=> ( in_array($scenario, ['nuevo', 'modificar']) ? '0' : '-1' )
    				]);
    			?>
				<?=
    				Html::activeCheckbox( $model, 'fila', [
    					'class' 	=> 'form-control',
						'disabled' 	=> ( in_array($scenario, ['nuevo', 'modificar']) ? false : true ),
    					'tabIndex'	=> ( in_array($scenario, ['nuevo', 'modificar']) ? '0' : '-1' )
    				]);
    			?>
				<?=
    				Html::activeCheckbox( $model, 'nume', [
    					'class' 	=> 'form-control',
						'disabled' 	=> ( in_array($scenario, ['nuevo', 'modificar']) ? false : true ),
    					'tabIndex'	=> ( in_array($scenario, ['nuevo', 'modificar']) ? '0' : '-1' )
    				]);
    			?>
				<?=
    				Html::activeCheckbox( $model, 'bis', [
    					'class' 	=> 'form-control',
						'disabled' 	=> ( in_array($scenario, ['nuevo', 'modificar']) ? false : true ),
    					'tabIndex'	=> ( in_array($scenario, ['nuevo', 'modificar']) ? '0' : '-1' )
    				]);
    			?>
			</td>
		</tr>
	</table>
	
	<hr>
	
	<label> <u> Cuerpos: </u> </label> <br>
	
	<div style='margin:5px 0px'>
		<?= Html::input( 'hidden', 'txActionCuerpo', null, ['id' => 'txActionCuerpo'] ); ?>
		
		<label> Id.: </label>
		<?=
			Html::input( 'text', 'txCueId', null, [
				'id' 		=> 'txCueId',
				'class' 	=> 'form-control text-center solo-lectura',
				'style'		=> 'width:10%; font-weight: bold',
				'onKeyUp' 	=> "this.value=this.value.toUpperCase();",
				'tabIndex'	=> '-1',
				'marlenght' => 3
			]);
		?>
		
		<label> Nombre: </label>
		<?=
			Html::input( 'text', 'txCueNombre', null, [
				'id' 		=> 'txCueNombre',
				'class' 	=> 'form-control solo-lectura',
				'style'		=> 'width:35%;',
				'marlenght' => 3
			]);
		?>
		
		<?php  
			if ( !in_array($model->scenario, ["", "eliminar"]) )
				echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btnNuevoCuerpo', 'onclick' => 'f_abmCuerpo( "", "", 0 );'])
		?>
		
		<?= 
			Html::button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btnAceptarCuerpo', 'style' => 'visibility:hidden', 'onclick' => 'f_grabarCuerpo();'])
		?>
		
		<?= 
			Html::button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btnCancelarCuerpo', 'style' => 'visibility:hidden', 'onclick' => 'f_cancelarCuerpo();'])
		?>
	</div>	
	
	<?php  
		Pjax::begin([ 'id' => 'pjaxGrillaCuerpos' ]);
			echo GridView::Widget([
				'dataProvider' => $model->cuerpos,
				'id' => 'grillaCuerpos',
				'headerRowOptions' => [ 'class' => 'grilla' ],
				'rowOptions' => ['class' => 'grilla'],
				'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
				'summary' => '',
				'columns' => [
					[ 'attribute' => 'cue_id', 'label' => 'Cuerpo', 'contentOptions' => [ 'style' => 'width:1%;text-align:center'] ],
					[ 'attribute' => 'nombre', 'label' => 'Nombre' ],
					[
						'class' => 'yii\grid\ActionColumn',
						'contentOptions'=>['style'=>'width:7%;text-align:center'],
						'template' => '{update}{delete}',
						'buttons'=>[

							'update' => function($url, $model, $key) use ($scenario)
							{
								if ( !in_array($scenario, ["", "eliminar"]) )
									return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
										'class'=>'bt-buscar-label',
										'onclick' => "f_abmCuerpo( '" . $model['cue_id'] . "', '" . $model['nombre'] . "', 3 );",
										'style' => 'color:#337ab7'
									]);
							},
							'delete' => function($url, $model, $key) use ($scenario)
							{
								if ( !in_array($scenario, ["", "eliminar"]) )
									return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
										'class'=>'bt-buscar-label',
										'onclick' => "f_abmCuerpo( '" . $model['cue_id'] . "', '" . $model['nombre'] . "', 2 );",
										'style' => 'color:#337ab7'
									]);
							},
						]
					]
				],
			]);
			
			echo '<input type="text" name="arrayCuerpos" id="arrayCuerpos" value="'.urlencode(serialize($model->cuerpos->getModels())).'" style="display:none">';
						
			echo $form->errorSummary( $model,[
				'id'	=> 'form_errorSummary',
				'style' => 'margin-top:8px; margin-right: 15px',
			]);
			
		Pjax::end();	
	?>

<?php 
	ActiveForm::end();
?>	

<div id="idBotonesCuadro" style='margin-top:10px'>
	<?php  
		if ( $model->scenario != "" )
			echo Html::button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btnAceptarCuadro', 'onclick' => 'f_grabarCuadro();'])
	?>

	<?= 
		Html::button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btnCancelarCuadro', 'onclick' => '$("' . $idModal . '").modal( "hide" )'])
	?>
</div>
	
<script>

function f_abmCuerpo( cue_id, nombre, action ){

	$("#txActionCuerpo").val(action);
	$("#txCueId").val(cue_id);
	$("#txCueNombre").val(nombre);
	
	$("#txCueNombre").addClass('solo-lectura');
	$("#txCueId").addClass('solo-lectura');
	
	$("#idBotonesCuadro button").attr("disabled", false);
	$("#datosCuerpo *").attr("disabled", false);
	
	ocultarErrores( "#form_errorSummary" );
	
	if ( action != 1 ){
		$("#btnNuevoCuerpo").css("visibility", "hidden");
		$("#grillaCuerpos button").css("visibility", "hidden");
		$("#btnAceptarCuerpo").css("visibility", "visible");
		$("#btnCancelarCuerpo").css("visibility", "visible");
		if ( action != 2 )
			$("#txCueNombre").removeClass('solo-lectura');
		if ( action == 0 )
			$("#txCueId").removeClass('solo-lectura');
			
		$("#idBotonesCuadro button").attr("disabled", true);	
		$("#datosCuerpo *").attr("disabled", true);
	}
}

function f_cancelarCuerpo(){

	$("#txCueNombre").addClass('solo-lectura');
	$("#txCueId").addClass('solo-lectura');
	
	$("#txCueNombre").val('');
	$("#txCueId").val('');
	$("#txActionCuerpo").val('');
	
	$("#btnNuevoCuerpo").css("visibility", "visible");
	$("#btnAceptarCuerpo").css("visibility", "hidden");
	$("#btnCancelarCuerpo").css("visibility", "hidden");
	
	$("#idBotonesCuadro button").attr("disabled", false);
	$("#datosCuerpo *").attr("disabled", false);
	
	ocultarErrores( "#form_errorSummary" );

}

function f_grabarCuerpo(){

	$.pjax.reload({
		container	: "#pjaxGrillaCuerpos",
		type 		: "GET",
		replace		: false,
		push		: false,
        timeout     : 100000,
		data:{
            "cue_id"    	: $("#txCueId").val(),
			"cue_nombre"	: $("#txCueNombre").val(),
			"cue_action"	: $("#txActionCuerpo").val(),
			"cue_cuerpos"	: $("#arrayCuerpos").val(),
			"cue_scenario"	: "<?= $scenario ?>"
		},
	});
	
	$( "#pjaxModal" ).on( "pjax:end", function() {
		
		if ( $("#form_errorSummary").css("display") == "none" )
			f_cancelarCuerpo();
	});
}

function f_grabarCuadro(){

	$.ajax({
		url: $("#formCuadro").attr( "action" ),
		type: 'post',
		data: $("#formCuadro").serialize(),
		success: function(data) {
			datos = jQuery.parseJSON(data); 
			
			if ( datos.error == "" ){
				
				alert("dd")
			
			}else	
				mostrarErrores( datos.error, "#form_errorSummary" );
		}
	});
}

</script>