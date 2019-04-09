<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use yii\bootstrap\Modal;
use yii\jui\DatePicker;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\BaseUrl;
?>

<?php

$form = ActiveForm::begin([
    'id' => 'form_mejora_individual',
    'options' => [
		'enctype' => 'multipart/form-data',
	]
]);

?>
<div class="form" style="padding:10px;">
	<table width = '100%'  >
		<tr>
			<td> <label> Plan Nº: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'plan_id', [
						'id' => 'plan_id',
						'class' => 'form-control solo-lectura',
						'style' => 'width:90%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
			<td colspan='4'></td>
			<td> <label> Estado: </label> </td>
			<td colspan='3'>
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
			<td> <label> Objeto: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'obj_id', [
						'id' => 'obj_id',
						'class' => 'form-control' . ( in_array($consulta, [1,2,4,5,6]) ? ' solo-lectura' : '' ),
						'style' => 'width:90%',
						'tabIndex' => ( in_array($consulta, [1,2,4,5,6]) ? '-1' : '' ) ,
						'onchange' => 'ObtenerObjeto()'
					]);
				?>
			</td>
			<td colspan='8' align='right'>
				<?php
					Modal::begin([
						'id' => 'modalObjeto',
						'header' => '<h1 style="text-align:left">Búsqueda de inmueble</h1>',
						'toggleButton' => [
							'label' => '<span class="glyphicon glyphicon-search"></span>',
							'class' => 'bt-buscar' . ( in_array($consulta, [1,2,4,5,6]) ? ' solo-lectura' : '' )
						],
						'closeButton' => [
							'label' => Html::button('&times', ['class' => 'btn btn-danger pull-right'])
						]
					]);

					echo "<div style='text-align:left'>";
						echo $this->render('//objeto/objetobuscarav', ['id' => 'busquedaObjeto', 'txNom' => 'obj_nom', 'txCod' => 'obj_id', 'tobjeto' => 1, 'selectorModal' => '#modalObjeto']);
					echo "</div>";

					Modal::end();
				?>
				<?=
					Html::activeInput( 'text', $model, 'obj_nom', [
						'id' => 'obj_nom',
						'class' => 'form-control solo-lectura',
						'style' => 'width:93%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
		</tr>

		<tr>
			<td> <label> Domicilio: </label> </td>
			<td colspan='9'>
				<?=
					Html::activeInput( 'text', $model, 'dompar', [
						'id' => 'dompar',
						'class' => 'form-control solo-lectura',
						'style' => 'width:100%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
		</tr>

		<tr>
			<td> <label> Obra: </label> </td>
			<td colspan='3'>
				<?= Html::activeDropDownList( $model, 'obra_id', utb::getAux('mej_obra', 'obra_id', 'nombre', 3, "est='A'"), [
						'id' => 'obra_id',
						'class' => 'form-control' . ( in_array($consulta, [1,2,4,5,6]) ? ' solo-lectura' : '' ),
						'style' => 'width:97%',
						'onchange' => 'CargarDatosObra()'
					]);
				?>
			</td>
			<td> <label id='lbcuadra'> Cuadra: </label> </td>
			<td colspan='5'>
				<?php
					Pjax::begin([ 'id' => 'PjaxCuadra' ]);
						$cuadras = utb::getAux('v_mej_cuadra', 'cuadra_id', "coalesce(calle_nom,'') || ' - ' || ncm", 0, "obra_id=" . $model->obra_id);
												
						echo Html::activeDropDownList( $model, 'cuadra_id', $cuadras, [
							'id' => 'cuadra_id',
							'class' => 'form-control pull-right' . ( in_array($consulta, [1,2,4,5,6]) ? ' solo-lectura' : '' ),
							'style' => 'width:100%;display:' . ( count($cuadras) == 0 ? 'none' : 'block' )
						]);
						
						if ( count($cuadras) == 0 )
							echo "<script>$('#lbcuadra').css('display','none')</script>";
						else 
							echo "<script>$('#lbcuadra').css('display','block')</script>";
					Pjax::end();
				?>
			</td>
		</tr>

		<tr>
			<td> <label> Cálculo: </label> </td>
			<td colspan='3'>
				<?= Html::activehiddenInput( $model, 'tcalculo', ['id' => 'tcalculo']); ?>
				<?=
					Html::activeInput( 'text', $modelTCalculo, 'nombre', [
						'id' => 'tcalculo_nombre',
						'class' => 'form-control solo-lectura',
						'style' => 'width:97%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
		</tr>
		<tr>
			<td> <label> Valor Mtr.: </label> </td>
			<td width='13%'>
				<?=
					Html::activeInput( 'text', $model, 'valormetro', [
						'id' => 'valormetro',
						'class' => 'form-control solo-lectura',
						'style' => 'width:95%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
			<td> <label> Total: </label> </td>
			<td width='13%'>
				<?=
					Html::activeInput( 'text', $model, 'valortotal', [
						'id' => 'valortotal',
						'class' => 'form-control solo-lectura',
						'style' => 'width:95%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
			<td> <label> Bonif.: </label> </td>
			<td width='13%'>
				<?=
					Html::activeInput( 'text', $model, 'bonifobra', [
						'id' => 'bonifobra',
						'class' => 'form-control solo-lectura',
						'style' => 'width:95%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
			<td> <label> Fijo: </label> </td>
			<td width='13%'>
				<?=
					Html::activeInput( 'text', $model, 'fijo', [
						'id' => 'fijo',
						'class' => 'form-control solo-lectura',
						'style' => 'width:95%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
		</tr>

		<tr>
			<td> <label> Frente: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'frente', [
						'id' => 'frente',
						'class' => 'form-control' . ( in_array($consulta, [1,2,4,5,6]) ? ' solo-lectura' : '' ),
						'style' => 'width:95%',
						'tabIndex' => ( in_array($consulta, [1,2,4,5,6]) ? '-1' : '' ) ,
						'onchange' => 'CalcularTotal()'
					]);
				?>
			</td>
			<td> <label> Sup.Afec.: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'supafec', [
						'id' => 'supafec',
						'class' => 'form-control' . ( in_array($consulta, [1,2,4,5,6]) ? ' solo-lectura' : '' ),
						'style' => 'width:95%',
						'tabIndex' => ( in_array($consulta, [1,2,4,5,6]) ? '-1' : '' ) ,
						'onchange' => 'CalcularTotal()'
					]);
				?>
			</td>
			<td> <label> Coef.: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'coef', [
						'id' => 'coef',
						'class' => 'form-control' . ( in_array($consulta, [1,2,4,5,6]) ? ' solo-lectura' : '' ),
						'style' => 'width:95%',
						'tabIndex' => ( in_array($consulta, [1,2,4,5,6]) ? '-1' : '' ) ,
						'onchange' => 'CalcularTotal()'
					]);
				?>
			</td>
			<td> <label> Monto: </label> </td>
			<td width='13%'>
				<?=
					Html::activeInput( 'text', $model, 'monto', [
						'id' => 'monto',
						'class' => 'form-control' . ( in_array($consulta, [1,2,4,5,6]) ? ' solo-lectura' : '' ),
						'style' => 'width:95%',
						'tabIndex' => ( in_array($consulta, [1,2,4,5,6]) ? '-1' : '' ) ,
						'onchange' => 'CalcularTotal()'
					]);
				?>
			</td>
		</tr>

		<tr>
			<td> <label> Item: </label> </td>
			<td colspan='5'>
				<?= Html::activeDropDownList( $model, 'item_id', utb::getAux('item', 'item_id', 'nombre', 1, 'trib_id=3'), [
							'id' => 'item_id',
							'class' => 'form-control' . ( in_array($consulta, [1,2,4,5,6]) ? ' solo-lectura' : '' ),
							'style' => 'width:98%',
							'onchange' => 'ItemSelec()'
						]);
					?>
			</td>
			<td> <label> Valor: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'item_monto', [
						'id' => 'item_monto',
						'class' => 'form-control' . ( in_array($consulta, [1,2,4,5,6]) ? ' solo-lectura' : '' ),
						'style' => 'width:95%;',
						'tabIndex' => ( in_array($consulta, [1,2,4,5,6]) ? '-1' : '' ) ,
						'onchange' => 'CalcularTotal()'
					]);
				?>
			</td>
			<td> <label> Total: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'total', [
						'id' => 'total',
						'class' => 'form-control' . ( in_array($consulta, [1,2,4,5,6]) ? ' solo-lectura' : '' ),
						'style' => 'width:100%',
						'tabIndex' => ( in_array($consulta, [1,2,4,5,6]) ? '-1' : '' )
					]);
				?>
			</td>
		</tr>

		<tr>
			<td> <label> Alta: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'fchalta', [
						'id' => 'fchalta',
						'class' => 'form-control solo-lectura',
						'style' => 'width:95%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
			<td> <label> Baja: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'fchbaja', [
						'id' => 'fchbaja',
						'class' => 'form-control solo-lectura',
						'style' => 'width:95%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
			<td> <label> Desaf: </label> </td>
			<td>
				<?=
					Html::activeInput( 'text', $model, 'fchdesaf', [
						'id' => 'fchdesaf',
						'class' => 'form-control solo-lectura',
						'style' => 'width:95%',
						'tabIndex' => '-1'
					]);
				?>
			</td>
			<td> <label> Modif.: </label> </td>
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
			<td> <label> Obs.: </label> </td>
			<td colspan='9'>
				<?=
					Html::activeTextarea( $model, 'obs', [
						'class'	=> 'form-control' . ( in_array( $consulta, [1,2,4,6] ) ? ' solo-lectura' : '' ),
						'style'	=> 'width:100%;resize:none',
						'tabIndex' => ( in_array( $consulta, [1,2,4,6] ) ? '-1' : '0' ),
						'spellcheck' => true,
						'maxlength' => 500
					]);
				?>
			</td>
		</tr>

	</table>
	<div class="text-center" style="margin-top: 8px">

	<?php

		if ( !in_array($consulta, [1, 4, 6]) )
		{
			echo Html::submitbutton( 'Grabar', [
				'id' => 'btGrabar',
				'class' => $consulta == 2 ? 'btn btn-danger' : 'btn btn-success',
			]);

			echo '&nbsp;&nbsp;';

			echo Html::a( 'Cancelar', ['index', 'id' => $model->plan_id], [
				'id' => 'btCancelar',
				'class' => 'btn btn-primary'
			]);

		}

	?>

	</div>
</div>

<?php if ( ($model->est != 'A' and $consulta == 1) or in_array($consulta, [4, 6]) ) { // si es a ctacte o consulta o vencimiento ?>
	<div class="form" style="padding:10px;margin-top:10px;">
		<label> <u> Financiación </u> </label>
		<table width='100%' >
			<tr>
				<td> <label> Tipo Plan: </label> </td>
				<td colspan='7'>
					<?php
						$cond = "cod in (select tplan from plan_config_usuario where usr_id=".Yii::$app->user->id.")";
						$cond .= " and cod in (select tplan from plan_config_trib where trib_id=3)";
						$cond .= " and (VigenciaHasta is null or to_char(VigenciaHasta,'YYYY/mm/dd')>='".date('Y/m/d')."')";

						echo Html::activeDropDownList( $model, 'tplan', utb::getAux('plan_config', 'cod', 'nombre', 1, $cond), [
							'id' => 'tplan',
							'class' => 'form-control' . ( in_array($consulta, [1, 2, 6]) ? ' solo-lectura' : '' ),
							'style' => 'width:100%',
							'onchange' => 'TipoPlanSelec()'
						]);
					?>
				</td>
			</tr>
			<tr>
				<td> <label> Anticipo: </label> </td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'anticipo', [
							'id' => 'anticipo',
							'class' => 'form-control' . ( in_array($consulta, [1, 2 , 6]) ? ' solo-lectura' : '' ),
							'style' => 'width:95%',
							'tabIndex' => ( in_array( $consulta, [1, 2 , 6] ) ? '-1' : '0' ),
						]);
					?>
				</td>
				<td> <label> Cuotas: </label> </td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'cuotas', [
							'id' => 'cuotas',
							'class' => 'form-control' . ( in_array($consulta, [1, 2, 6]) ? ' solo-lectura' : '' ),
							'style' => 'width:95%',
							'tabIndex' => ( in_array( $consulta, [1, 2, 6] ) ? '-1' : '0' ),
						]);
					?>
				</td>
				<td colspan='3' align='right'> <label> Vencimiento 1º Cuota: </label> </td>
				<td>
					<?= DatePicker::widget([
							'model' => $model,
							'attribute' => 'venccuota1',
							'dateFormat' => 'dd/MM/yyyy',
							'options' => [
									'class' => 'form-control ' . ( in_array($consulta, [1, 2]) ? 'solo-lectura' : '' ),
									'style'=>'width:100%; text-align:center'
								]
						]);
					?>
				</td>
			</tr>
			<tr>
				<td> <label> Financiación: </label> </td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'financia', [
							'id' => 'financia',
							'class' => 'form-control solo-lectura',
							'style' => 'width:95%',
							'tabIndex' => '-1',
						]);
					?>
				</td>
				<td> <label> Sellado: </label> </td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'sellado', [
							'id' => 'sellado',
							'class' => 'form-control solo-lectura',
							'style' => 'width:95%',
							'tabIndex' => '-1',
						]);
					?>
				</td>
				<td> <label> Descuento: </label> </td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'descnominal', [
							'id' => 'descnominal',
							'class' => 'form-control solo-lectura',
							'style' => 'width:95%',
							'tabIndex' => '-1',
						]);
					?>
				</td>
				<td width='13%'> <label> Monto Cuota: </label> </td>
				<td>
					<?=
						Html::activeInput( 'text', $model, 'montocuo', [
							'id' => 'montocuo',
							'class' => 'form-control solo-lectura',
							'style' => 'width:100%',
							'tabIndex' => '-1',
						]);
					?>
				</td>
			</tr>
		</table>
		<div class="text-center" style="margin-top: 8px">

		<?php

			if ( !in_array($consulta, [1] ) )
			{
				if ( $consulta != 6 ) {
					echo Html::button( 'Calcular', [
						'id' => 'btFinanCalcular',
						'class' => 'btn btn-success',
						'onclick' => 'calcularFinancia()'
					]);

					echo '&nbsp;&nbsp;';

					echo Html::button( 'Imprimir Cuotas', [
						'id' => 'btFinanImprimirCuota',
						'class' => 'btn btn-success',
						'onclick' => 'btFinanImprimirCuotaClick()',
					]);

					echo '&nbsp;&nbsp;';
				}

				echo Html::submitbutton( 'Grabar', [
					'id' => 'btGrabarACtaCteVenc',
					'class' => $consulta == 2 ? 'btn btn-danger' : 'btn btn-success',
				]);

				echo '&nbsp;&nbsp;';

				echo Html::a( 'Cancelar', ['index', 'id' => $model->plan_id], [
					'id' => 'btCancelarACtaCteVenc',
					'class' => 'btn btn-primary'
				]);

			}

		?>

		</div>
	</div>
	<div class="form" style="padding:10px;margin-top:10px;">
		<label> <u> Cuotas </u> </label>
		<?php

			Pjax::begin([ 'id' => 'PjaxGrillaCuotas', 'enablePushState' => false, 'enableReplaceState' => false ]);

				$columns[] = [ 'attribute' => 'cuota_nom', 'label' => 'Cuota', 'contentOptions' => [ 'style' => 'width:1%'], ];
				if ( $consulta == 4 ) {
					$columns[] = [ 'attribute' => 'capital', 'label' => 'Capital', 'contentOptions' => [ 'style' => 'width:1%;text-align:right'], ];
					$columns[] = [ 'attribute' => 'financia', 'label' => 'Financia', 'contentOptions' => [ 'style' => 'width:1%;text-align:right'], ];
					$columns[] = [ 'attribute' => 'sellado', 'label' => 'Sellado', 'contentOptions' => [ 'style' => 'width:1%;text-align:right'], ];
				}
				$columns[] = [ 'attribute' => 'total', 'label' => 'Total', 'contentOptions' => [ 'style' => 'width:1%;text-align:right'], ];
				$columns[] = [ 'attribute' => 'venc', 'label' => 'Vencimiento', 'contentOptions' => [ 'style' => 'width:1%;text-align:center'], ];
				if ( $consulta == 1 ) {
					$columns[] = [ 'attribute' => 'est', 'label' => 'Estado', 'contentOptions' => [ 'style' => 'width:1%;text-align:center'], ];
					$columns[] = [ 'attribute' => 'fchpago', 'label' => 'Pago', 'contentOptions' => [ 'style' => 'width:1%;text-align:center'], ];
					$columns[] = [ 'attribute' => 'caja_id', 'label' => 'Caja', 'contentOptions' => [ 'style' => 'width:1%;text-align:center'], ];
				}

				echo GridView::Widget([
					'dataProvider' => $dataProviderCuotas,
					'id' => 'grillaCuota',
					'headerRowOptions' => [ 'class' => 'grilla' ],
					'summaryOptions' => ['style' => 'display:none'],
					'rowOptions' => ['class' => 'grilla'],
					'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
					'columns' => $columns,
				]);

				echo '<input type="text" name="arrayCuotas" id="arrayCuotas" value="'.urlencode(serialize( $dataProviderCuotas->getModels() )).'" style="display:none">';

			Pjax::end();
		?>
	</div>
<?php } ?>

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
// Formulario que envia datos a imprimir de las cuotas. Llamado por el boton 'Imprimir Cuotas'
ActiveForm::begin([ 'id' => 'form_imprimir_cuotas', 'action' => BaseUrl::toRoute('imprimircuota') . '&plan=' . $model->plan_id, 'options'=>['target'=>'_blank'] ]);
	echo Html::input( 'hidden', 'imprimir_cuotas', null, ['id' => 'imprimir_cuotas'] );
ActiveForm::end();
?>

<script>

<?php if ( !in_array($consulta, [1, 2, 4, 5, 6]) ) { ?>
	CargarDatosObra();
<?php } ?>

ItemSelec();

<?php if ( $consulta == 4 ) { ?>

	TipoPlanSelec();

<?php } ?>

function CargarDatosObra(){

	var obra = $("#obra_id").val();

	$.get( "<?= BaseUrl::toRoute('datosobra');?>&obra=" + obra
	).success(function(data){
		datos = jQuery.parseJSON(data);
		$("#tcalculo").val(datos.tcalculo);
		$("#tcalculo_nombre").val(datos.tcalculo_nombre);
		$("#valormetro").val(datos.valormetro);
		$("#valortotal").val(datos.valortotal);
		$("#bonifobra").val(datos.bonifobra);
		$("#fijo").val(datos.fijo);

		$("#frente").attr('disabled', (datos.ped_frente != 1));
		if ( $("#frente").attr('disabled') ) $("#frente").val("");

		$("#supafec").attr('disabled', (datos.ped_supafec != 1));
		if ( $("#supafec").attr('disabled') ) $("#supafec").val("");

		$("#coef").attr('disabled', (datos.ped_coef != 1));
		if ( $("#coef").attr('disabled') ) $("#coef").val("");

		$("#monto").attr('disabled', (datos.ped_monto != 1));
		if ( $("#monto").attr('disabled') ) $("#monto").val("");

		$.pjax.reload({
	        container: '#PjaxCuadra',
	        type: 'GET',
	        replace: false,
	        push: false,
	        data : {
	            "obra": obra
	        }
	    });

		CalcularTotal();

	});

}

function ObtenerObjeto(){

	var objCod = $("#obj_id").val();

	$.getJSON( "<?= BaseUrl::toRoute(['//ajax/objeto']); ?>&tobj=1&obj_id=" + objCod ).success(function(data){

		$( "#obj_id" ).val( data.obj_id );
		$( "#obj_nom" ).val( data.obj_nom );
		$( "#dompar" ).val( data.domi );

		ocultarErrores( "#form_errorSummary" );

	}).error(function(){

		mostrarErrores( ["El objeto ingresado no existe."], "#form_errorSummary" );

		$( "#obj_nom" ).val( "" );
		$( "#obj_id" ).focus();

	});

}

function ItemSelec(){

	var item = ( isNaN( parseInt( $( "#item_id" ).val() ) ) ? 0 : parseInt( $( "#item_id" ).val() ) );

	$("#item_monto").attr('disabled', (item == 0));
	if ( $("#item_monto").attr('disabled') )
		$("#item_monto").val("");
	else
		CalcularTotal();

}

function CalcularTotal() {

	var objeto = $( "#obj_id" ).val();
	var obra = ( isNaN( parseInt( $( "#obra_id" ).val() ) ) ? 0 : parseInt( $( "#obra_id" ).val() ) );
	var frente = ( isNaN( parseFloat( $( "#frente" ).val() ) ) ? 0 : parseFloat( $( "#frente" ).val() ) );
	var supafec = ( isNaN( parseFloat( $( "#supafec" ).val() ) ) ? 0 : parseFloat( $( "#supafec" ).val() ) );
	var coef = ( isNaN( parseFloat( $( "#coef" ).val() ) ) ? 0 : parseFloat( $( "#coef" ).val() ) );
	var monto = ( isNaN( parseFloat( $( "#monto" ).val() ) ) ? 0 : parseFloat( $( "#monto" ).val() ) );

	$.post( "<?= BaseUrl::toRoute('calculartotal');?>", { "objeto": objeto, "obra": obra, "frente": frente, "supafec" : supafec, "coef" : coef, "monto" : monto }
	).success(function(data){
		datos = jQuery.parseJSON(data);

		$("#total").val( datos.total );
	});

}

function TipoPlanSelec() {

	var tplan = ( isNaN( parseInt( $( "#tplan" ).val() ) ) ? 0 : parseInt( $( "#tplan" ).val() ) );

	$.get( "<?= BaseUrl::toRoute('datostplan');?>&tplan=" + tplan
	).success(function(data){
		datos = jQuery.parseJSON(data);

		$("#anticipo").attr('disabled', (datos.anticipomanual != 1));
		if ( $("#anticipo").attr('disabled') ) $("#anticipo").val("");

	});
}

function calcularFinancia(){

	var total = ( isNaN( parseFloat( $( "#total" ).val() ) ) ? 0 : parseFloat( $( "#total" ).val() ) );
	var cuotas = ( isNaN( parseInt( $( "#cuotas" ).val() ) ) ? 0 : parseInt( $( "#cuotas" ).val() ) );
	var venc = $("#mejoraplan-venccuota1").val();
	var tplan = ( isNaN( parseInt( $( "#tplan" ).val() ) ) ? 0 : parseInt( $( "#tplan" ).val() ) );
	var obj_id = $("#obj_id").val();
	var anticipo = ( isNaN( parseFloat( $( "#anticipo" ).val() ) ) ? 0 : parseFloat( $( "#anticipo" ).val() ) );

	$.post( "<?= BaseUrl::toRoute('calcularfinancia');?>", { "total": total, "cuotas": cuotas, "venc" : venc, "tplan" : tplan, "obj_id" : obj_id, "anticipo" : anticipo }
	).success(function(data){
		datos = jQuery.parseJSON(data);
		if ( datos.error == "" ) {
			ocultarErrores( "#form_errorSummary" );

			$("#financia").val(datos.financia);
			$("#sellado").val(datos.sellado);
			$("#descnominal").val(datos.descuento);
			$("#montocuo").val(datos.montocuota);

			$.pjax.reload({
				container: '#PjaxGrillaCuotas',
				type: 'POST',
				replace: false,
				push: false,
				data : {
					"cuotas": datos.cuotas
				}
			});

		}else {
			mostrarErrores( [datos.error], "#form_errorSummary" );
		}

	});
}

function btFinanImprimirCuotaClick(){

	$("#imprimir_cuotas").val( $("#arrayCuotas").val() );

	$("#form_imprimir_cuotas").submit();

	//$("#imprimir_cuotas").val("");

}

</script>
