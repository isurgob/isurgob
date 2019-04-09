<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use yii\bootstrap\Modal;
use yii\jui\DatePicker;
use yii\widgets\Pjax;
use yii\helpers\BaseUrl;
use yii\grid\GridView;
?>

<?php 

$form = ActiveForm::begin([
    'id' => 'form_mejora_obra',
    'options' => [
		'enctype' => 'multipart/form-data',
	]
]);

?>
<div class="form" style="padding:10px;">
	<table width = '100%'  >
		<tr>
			<td> <label> Cod.: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'obra_id', [
						'id' => 'obra_id',
						'class' => 'form-control solo-lectura',
						'style' => 'width:95%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
			<td> <label> Tipo: </label> </td>
			<td colspan='3'>
				<?= Html::activeDropDownList( $model, 'tobra', utb::getAux('mej_tobra'), [
							'id' => 'tobra',
							'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
							'style' => 'width:95%'
						]);
					?>
			</td>
			<td> <label> Estado: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'est_nom', [
						'id' => 'est_nom',
						'class' => 'form-control solo-lectura',
						'style' => 'width:100%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
		</tr>
		
		<tr>
			<td> <label> Nombre: </label> </td>
			<td colspan='3'>
				<?=
					Html::activeInput( 'text', $model, 'nombre', [
						'id' => 'nombre',
						'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
						'style' => 'width:98%',
						'maxlenght' => '40',
						'tabIndex' => ( in_array( $consulta, [1,2] ) ? '-1' : '0' ),
					]);
				?>
			</td>
			<td> <label> Decreto: </label> </td>
			<td colspan='3'>
				<?=
					Html::activeInput( 'text', $model, 'decreto', [
						'id' => 'decreto',
						'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
						'style' => 'width:100%',
						'maxlenght' => '20',
						'tabIndex' => ( in_array( $consulta, [1,2] ) ? '-1' : '0' ),
					]);
				?>
			</td>
		</tr>
		
		<tr>
			<td> <label> Cálculo: </label> </td>
			<td colspan='3'>
				<?= Html::activeDropDownList( $model, 'tcalculo', utb::getAux('mej_tcalculo', 'cod', 'nombre', 3), [
							'id' => 'tcalculo',
							'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
							'style' => 'width:98%',
							'onchange' => 'HabilitarCamposCalculo()'
						]);
					?>
			</td>
			<td> <label> Total Fre.: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'totalfrente', [
						'id' => 'totalfrente',
						'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
						'style' => 'width:95%',
						'maxlenght' => '8',
						'onkeypress' => 'return justDecimal( $(this).val(), event )',
						'tabIndex' => ( in_array( $consulta, [1,2] ) ? '-1' : '0' ),
					]);
				?>
			</td>
			<td width='10%'> <label> Total Sup.: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'totalsupafec', [
						'id' => 'totalsupafec',
						'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
						'style' => 'width:100%',
						'maxlenght' => '8',
						'onkeypress' => 'return justDecimal( $(this).val(), event )',
						'tabIndex' => ( in_array( $consulta, [1,2] ) ? '-1' : '0' ),
					]);
				?>
			</td>
		</tr>
		
		<tr>
			<td> <label> Valor Total: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'valortotal', [
						'id' => 'valortotal',
						'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
						'style' => 'width:95%',
						'maxlenght' => '8',
						'onkeypress' => 'return justDecimal( $(this).val(), event )',
						'tabIndex' => ( in_array( $consulta, [1,2] ) ? '-1' : '0' ),
					]);
				?>
			</td>
			<td width='11%'> <label> Valor Metro: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'valormetro', [
						'id' => 'valormetro',
						'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
						'style' => 'width:95%',
						'maxlenght' => '8',
						'onkeypress' => 'return justDecimal( $(this).val(), event )',
						'tabIndex' => ( in_array( $consulta, [1,2] ) ? '-1' : '0' ),
					]);
				?>
			</td>
			<td> <label> Fijo: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'fijo', [
						'id' => 'fijo',
						'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
						'style' => 'width:95%',
						'maxlenght' => '8',
						'onkeypress' => 'return justDecimal( $(this).val(), event )',
						'tabIndex' => ( in_array( $consulta, [1,2] ) ? '-1' : '0' ),
					]);
				?>
			</td>
			<td> <label> Bonif.: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'bonifobra', [
						'id' => 'bonifobra',
						'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
						'style' => 'width:100%',
						'maxlenght' => '8',
						'onkeypress' => 'return justDecimal( $(this).val(), event )',
						'tabIndex' => ( in_array( $consulta, [1,2] ) ? '-1' : '0' ),
					]);
				?>
			</td>
		</tr>
		
		<tr>
			<td> <label> Cuenta: </label> </td>
			<td colspan='3'>
				<?= Html::activeDropDownList( $model, 'cta_id', utb::getAux('cuenta', 'cta_id', 'nombre', 1, 'tcta=1'), [
							'id' => 'cta_id',
							'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
							'style' => 'width:98%'
						]);
					?>
			</td>
			<td> <label> Texto: </label> </td>
			<td colspan='3'>
				<?= Html::activeDropDownList( $model, 'texto_id', utb::getAux('texto', 'texto_id', 'nombre', 1, 'tuso=10'), [
							'id' => 'texto_id',
							'class' => 'form-control' . ( in_array($consulta, [1,2]) ? ' solo-lectura' : '' ),
							'style' => 'width:100%'
						]);
					?>
			</td>
		</tr>
		<tr>
			<td> <label> Incio: </label> </td>
			<td>
				<?= DatePicker::widget([
							'model' => $model,
							'attribute' => 'fchini',
							'dateFormat' => 'dd/MM/yyyy', 
							'options' => [
									'class' => 'form-control ' . ( in_array($consulta, [1,2]) ? 'solo-lectura' : '' ),
									'style'=>'width:95%; text-align:center'
								]
						]);
					?>
			</td>
			<td> <label> Fin: </label> </td>
			<td>
				<?= DatePicker::widget([
							'model' => $model,
							'attribute' => 'fchfin',
							'dateFormat' => 'dd/MM/yyyy', 
							'options' => [
									'class' => 'form-control ' . ( in_array($consulta, [1,2]) ? 'solo-lectura' : '' ),
									'style'=>'width:95%; text-align:center'
								]
						]);
					?>
			</td>
			<td> <label> Modificación: </label> </td>
			<td colspan='3'>
				<?=
					Html::activeInput( 'text', $model, 'modif', [
						'id' => 'modif',
						'class' => 'form-control solo-lectura',
						'style' => 'width:100%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
		</tr>
		
		<tr>
			<td> <label> Observación: </label> </td>
			<td colspan='7'>
				<?=
					Html::activeTextarea( $model, 'obs', [
						'class'	=> 'form-control' . ( in_array( $consulta, [1,2] ) ? ' solo-lectura' : '' ),
						'style'	=> 'width:100%;resize:none',
						'tabIndex' => ( in_array( $consulta, [1,2] ) ? '-1' : '0' ),
						'spellcheck' => true,
						'maxlength' => 500
					]);
				?>
			</td>
		</tr>
		
	</table>
	
</div>

<div class="form" style="padding:10px;margin-top:10px;">
	<div class="pull-left"> <label> <u> Cuadras </u> </label> </div>
	
	<div class='pull-right'>
		<?php
			if ( in_array($consulta, [0, 3]) ) {
				echo Html::button( 'Nueva', [
					'id' => 'btNuevaCuadra',
					'class' => 'btn btn-success',
					'onclick' => 'f_abmCuadra(0,0)'
				]);
			}	
		?>
	</div>
	
	<div class='clearfix' style='margin-bottom:5px' > </div>
	<?php
	
		Pjax::begin([ 'id' => 'PjaxGrillaCuadra', 'enablePushState' => false, 'enableReplaceState' => false ]);
			
			echo GridView::Widget([
				'dataProvider' => $dataProviderCuadras,
				'id' => 'grillaCuota',
				'headerRowOptions' => [ 'class' => 'grilla' ],
				'summaryOptions' => ['style' => 'display:none'],
				'rowOptions' => ['class' => 'grilla'],
				'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
				'columns' => [
					[ 'attribute' => 'ncm', 'label' => 'NCM', 'contentOptions' => [ 'style' => 'width:1%'], ],
					[ 'attribute' => 'calle_id', 'label' => 'Calle', 'contentOptions' => [ 'style' => 'width:1%;text-align:center'], ],
					[ 'attribute' => 'calle_nom', 'label' => 'Calle Nombre', ],
					
					[
						'class' => 'yii\grid\ActionColumn',
						'contentOptions'=>['style'=>'width:1%;text-align:center'],
						'template' => ( in_array( $consulta, [0,3] ) ? '{delete}' : ''),
						'buttons'=>[

							'delete' => function($url, $model, $key)
							{
								return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
									'class'=>'bt-buscar-label',
									'onclick' => "f_abmCuadra(2, " . $model['cuadra_id'] . ")",
								]);
							},
						]
					]
				],
			]);
			
			echo '<input type="text" name="listaCuadra" id="listaCuadra" value="'.urlencode(serialize($model->cuadras)).'" style="display:none">'; 
		
		Pjax::end();
	?>
</div>	

<div class="text-center" style="margin-top: 8px">

<?php

	if ( $consulta != 1 )
	{
		echo Html::submitbutton( 'Grabar', [
			'id' => 'btGrabar',
			'class' => $consulta == 2 ? 'btn btn-danger' : 'btn btn-success',
		]);

		echo '&nbsp;&nbsp;';
		
		echo Html::a( 'Cancelar', ['index', 'id' => $model->obra_id], [
			'id' => 'btCancelar',
			'class' => 'btn btn-primary'
		]);

	}

?>

</div>

<?php

//INICIO Div Errores
echo $form->errorSummary( $model, [
    'id' => "form_errorSummary",
    'class' => "error-summary",
    'style' => "margin-top: 8px",
]);
//FIN Div Errores

ActiveForm::end();

?>

<?php

    Pjax::begin([ 'id' => 'pjaxModalCuadra', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000 ]);

        $CuadraAction = intVal(Yii::$app->request->get( 'CuadraAction', 1 ));
		
		if ( $CuadraAction == 0 )
			$titulo = 'Nueva Cuadra';
		elseif ( $CuadraAction == 2 )
			$titulo = 'Eliminar Cuadra';	
		elseif ( $CuadraAction == 3 )
			$titulo = 'Modificar Cuadra';		
		else	
			$titulo = 'Consutar Cuadra';		
		
		Modal::begin([
                'id' => 'ModalCuadra',
                'class' => 'container',
                'size' => 'modal-normal',
                'header' => '<h2><b>' . $titulo . '</b></h2>',
                'closeButton' => [
                    'label' => '<b>X</b>',
                    'class' => 'btn btn-danger btn-sm pull-right',
                    'id' => 'btCancelarModalRubros'
                    ],
            ]);

            echo $this->render( '_form_cuadra', ['cuadra' => $cuadra, 'CuadraAction' => $CuadraAction, 'modelCuadra' => $modelCuadra] );

        Modal::end();

    Pjax::end();
?>

<script>
HabilitarCamposCalculo();

function HabilitarCamposCalculo(){

	var tcalculo = $("#tcalculo").val();
	
	$.get( "<?= BaseUrl::toRoute('datostcalculo');?>&id=" + tcalculo
	).success(function(data){
		datos = jQuery.parseJSON(data); 
		$("#totalfrente").attr( "disabled", (datos.ped_frente == 0) );
		$("#totalsupafec").attr( "disabled", (datos.ped_supafec == 0) );
		$("#valortotal").attr( "disabled", (datos.ped_valortotal == 0) );
		$("#valormetro").attr( "disabled", (datos.ped_valormetro == 0) );
		$("#fijo").attr( "disabled", (datos.ped_fijo == 0) );
		
		if ( $("#totalfrente").attr( "disabled") )
			$("#totalfrente").val("");
			
		if ( $("#totalsupafec").attr( "disabled") )
			$("#totalsupafec").val("");

		if ( $("#valortotal").attr( "disabled") )
			$("#valortotal").val("");

		if ( $("#valormetro").attr( "disabled") )
			$("#valormetro").val("");	

		if ( $("#fijo").attr( "disabled") )
			$("#fijo").val("");			
	});

}

function f_abmCuadra( action, cuadra ){
		
	$.pjax.reload({
        container: '#pjaxModalCuadra',
        type: 'GET',
        replace: false,
        push: false,
        data : {
            "CuadraAction": action,
            "CuadraId": cuadra,
			"listaCuadra": $("#listaCuadra").val()
        }
    });
	
}

$( document ).ready( function() {

    $( "#pjaxModalCuadra" ).on( "pjax:end", function() {

        $( "#ModalCuadra" ).modal( "show" );

    });

});

</script>