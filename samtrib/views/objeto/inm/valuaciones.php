<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\grid\GridView;
use app\models\objeto\Inm;
use app\utils\db\utb;
use \yii\widgets\Pjax;

/*
 *
 * Obtengo $arregloComputados, el cual contiene el nombre de los campos que se calculan en la BD
 *
 */



?>


<div class="form-titular" id="consultaAvaluo" >

	<table>

		<tr>
			<td><?= $form->field($model, 'modificaAvaluo')->checkbox(['onchange' => 'cambiaCheck()']) ?></td>
			<td width="20px"></td>
			<td><label>Período Vigencia: </label></td>
			<td width="10px"></td>
			<td width="50px">
				<?= $form->field($model, 'avaluo_perdesde_anio')->textInput([
						'style' => 'width:95%;text-align:center',
						'class' => 'form-control',
						'maxlength'=>'4',
						'onkeypress' => 'return justNumbers(event)',
					])->label(false);
				?>
			</td>
			<td width="5px"></td>
			<td>
				<?= $form->field($model, 'avaluo_perdesde_mes')->textInput([
						'style' => 'width:30%;text-align:center',
						'class' => 'form-control',
						'maxlength'=>'2',
						'onkeypress' => 'return justNumbers(event)',
					])->label(false);
				?>
			</td>
		</tr>
		
		<tr>
			<td><?= Html::checkbox('ckSupHa',false, ['id' => 'ckSupHa','label' => 'Superficie en Ha.', 'onchange' => 'superficieHa()']); ?></td>
			<td width="20px"></td>
			<td colspan='5'>
				<label> Ha.: </label> <?= Html::input('text', 'txHa', null, ['id' => 'txHa', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px']); ?>
				<label> A: </label> <?= Html::input('text', 'txA', null, ['id' => 'txA', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:40px']); ?>
				<label> Ca.: </label> <?= Html::input('text', 'txCa', null, ['id' => 'txCa', 'maxlength'=>'8','class' => 'form-control', 'style'=>'width:80px']); ?>
			</td>
			<td>	
				<?= Html::Button('<i class="glyphicon glyphicon-ok"></i>', ['class' => 'bt-buscar', 'id' => 'btCalcularSupHa', 'onClick' => 'calcularSupHa();']) ?>
			</td>
		</tr>
	</table>
	<ul id='ulMenuDer' class='menu_derecho'>
	<li><hr style="color: #ddd; margin:1px" /></li>
	</ul>

	<table border="0" id="inm_avaluo">

        <tr>
            <td width="70px"><label>Régimen:</label></td>
            <td colspan="4"><?= $form->field($model, 'regimen')->dropDownList(utb::getAux('inm_tregimen', 'cod', 'nombre', 0, ($modelObjeto->est == 'M' ? 'cod=3' : '' ) ), [ 'style' => 'width:99%','class' => 'form-control', 'prompt' => ''])->label(false) ?></td>
			<td colspan="3"> </td>
			<td colspan="2"> <label> Unid.Hab.: </label> </td>
			<td colspan="2">
				<?= $form->field($model, 'unihab')->textInput([
						'style' => 'width:95%;text-align:center',
						'class' => 'form-control',
						'maxlength'=>'2',
						'onkeypress' => 'return justNumbers(event)',
					])->label(false);
				?>
			</td>
        </tr>

		<tr>
			<td><label>Sup. Terreno:</label></td>
			<td width="90px"><?= $form->field($model, 'supt')->textInput([ 'style' => 'width:100%;text-align:right','class' => 'form-control', 'maxlength'=>'12', 'onkeypress' => 'return justDecimal(this.value,event,4)'])->label(false) ?></td>
			<td width="5px"></td>
			<td><label>Sup. Pasillo:</label></td>
			<td width="80px"><?= $form->field($model, 'supt_pasillo')->textInput([ 'style' => 'width:95%;text-align:right','class' => 'form-control', 'maxlength'=>'9', 'onkeypress' => 'return justDecimal(this.value,event)'])->label(false) ?></td>
			<td width="5px"></td>
			<td width="80px"><label>Coef. Ajuste:</label></td>
			<td width="5px"></td>
			<td><?= $form->field($model, 'coef', ['options' => ['style' => 'display:inline-block;']])->textInput([ 'style' => 'width:50px;text-align:right','class' => 'form-control', 'maxlength'=>'5', 'onkeypress' => 'return justDecimal(this.value,event)'])->label(false) ?></td>
			<td colspan="2"><label>Val. Básico:</label></td>
			<td width="80px"><?= $form->field($model, 'valbas', ['options' => ['style' => 'display:inline-block;']])->textInput([ 'style' => 'width:100%;text-align:right','class' => 'form-control', 'maxlength'=>'12', 'onkeypress' => 'return justDecimal(this.value,event)'])->label(false) ?></td>
		</tr>
		<tr>
			<td><label>Sup. Mejoras:</label></td>
			<td><?= $form->field($model, 'supm')->textInput([ 'style' => 'width:100%;text-align:right','class' => 'form-control', 'maxlength'=>'12', 'onkeypress' => 'return justDecimal(this.value,event)'])->label(false) ?></td>
			<td></td>
			<td><label>Mts. Frente:</label></td>
			<td>
				<?php

					Pjax::begin(['id'=>'pjax-valuaciones']);
						echo "<script> $('#inm-frente').val('" . $model->frente . "') </script>";
					Pjax::end();

					echo $form->field($model, 'frente')->textInput([
						'style' => 'width:95%;text-align:right',
						'class' => 'form-control',
						'maxlength'=>'12',
						'onkeypress' => 'return justDecimal(this.value,event)',
						'readonly'=>true,
					])->label(false);

				?>
			</td>
			<td colspan="3"></td>
			<td colspan="2"><label>Zona Trib.:</label></td>
			<td colspan="2"><?= $form->field($model, 'zonat')->dropDownList(utb::getAux('inm_tzonat'),[ 'style' => 'width:100%','class' => 'form-control', 'prompt' => '<Seleccionar>'])->label(false) ?></td>
		</tr>
		<tr>
			<td><label>Av. Terreno:</label></td>
			<td><?= $form->field($model, 'avalt')->textInput([ 'style' => 'width:100%;text-align:right','class' => 'form-control', 'maxlength'=>'12', 'onkeypress' => 'return justDecimal(this.value,event)'])->label(false) ?></td>
			<td></td>
			<td><label>Mts. Fondo:</label></td>
			<td>
				<?= $form->field($model, 'fondo')->textInput([
						'style' => 'width:95%;text-align:right',
						'class' => 'form-control',
						'maxlength'=>'12',
						'onkeypress' => 'return justDecimal(this.value,event)',
					])->label(false); ?>
			</td>
			<td colspan="3"></td>
			<td colspan="2"><label>Zona Valuatoria:</label></td>
			<td colspan="2"><?= $form->field($model, 'zonav')->dropDownList(utb::getAux('inm_tzonav'),[ 'style' => 'width:100%','class' => 'form-control', 'prompt' => '<Ninguno>'])->label(false) ?></td>

		</tr>
		<tr>
			<td><label>Av. Mejoras:</label></td>
			<td><?= $form->field($model, 'avalm')->textInput([ 'style' => 'width:100%;text-align:right','class' => 'form-control', 'maxlength'=>'12', 'onkeypress' => 'return justDecimal(this.value,event)'])->label(false) ?></td>
			<td></td>
			<td><label>Pavimento:</label></td>
			<td colspan="3"><?= $form->field($model, 'pav')->dropDownList(utb::getAux('domi_tpav'),[ 'style' => 'width:75%','class' => 'form-control', 'prompt' => ''])->label(false) ?></td>
			<td colspan="1"></td>
			<td colspan="2"><label>Zona Obras Priv.:</label></td>
			<td colspan="2"><?= $form->field($model, 'zonaop')->dropDownList(utb::getAux('inm_tzonaop'),[ 'style' => 'width:100%','class' => 'form-control', 'prompt' => '<Ninguno>'])->label(false) ?></td>
		</tr>
		<tr>
			<td>
				<?= $form->field($model, 'es_esquina')->hiddenInput()->label( false ); ?>
				<?= $form->field($model, 'es_esquina')->checkbox() ?>
			</td>
			<td>
				<?= $form->field($model, 'es_calleppal')->hiddenInput()->label( false ); ?>
				<?= $form->field($model, 'es_calleppal')->checkbox() ?>
			</td>
			<td colspan="10">
				<label> Agua: </label>
				<?= Html::activeDropDownList($model, 'agua', utb::getAux('inm_tserv'), [
						'style'=>'width:15%', 'class' => 'form-control'
					]); 
				?>
				<label> Cloaca: </label>
				<?= Html::activeDropDownList($model, 'cloaca', utb::getAux('inm_tserv'), [
						'style'=>'width:15%', 'class' => 'form-control'
					]); 
				?>
				<label> Gas: </label>
				<?= Html::activeDropDownList($model, 'gas', utb::getAux('inm_tserv'), [
						'style'=>'width:15%', 'class' => 'form-control'
					]); 
				?>
				<label>Alum.:</label>
				<?= Html::activeDropDownList($model, 'alum', utb::getAux('inm_talum'), [
						'style'=>'width:20%', 'class' => 'form-control'
					]); 
				?>
			</td>
		</tr>
	</table>

	<table width="100%">
		<tr>
			<td width="100%" height="100px">
				<?php

				 echo GridView::widget([
				    'dataProvider' => $model->CargarOSM($model->obj_id),
				    'rowOptions' => ['class' => 'grilla'],
				    'headerRowOptions' => ['class' => 'grilla'],
				    //'rowOptions' => function ($model,$key,$index,$grid) {return EventosGrilla($model);},
				    'columns' => [

			            ['attribute'=>'subcta','header' =>'Cta'],
			            ['attribute'=>'ctaosm','header' => 'CtaOSM'],
			            ['attribute'=>'fchinicio','header' => 'Fch.Inicio'],
			            ['attribute'=>'tliq_nom','header' => 'Tipo Liq'],
			            ['attribute'=>'tipomedidor_nom','header' => 'Tipo Med'],
			            ['attribute'=>'nummedidor','header' => 'Nro.Med'],
			            ['attribute'=>'iva_nom','header' => 'IVA'],


			           ['class' => 'yii\grid\ActionColumn',
			            'buttons' => [
				            'view' => function()
			            			{
			            				return null;
			            			},
			    			'update' => function($url, $model, $key)
			    						{
			    							//$url .= '&desc_id=' . $model['desc_id'];
			    							return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url);
			    						},

			    			'delete' => function($url, $model, $key)
			    						{
			    							$url = $url. '&accion=0';
			    							return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url);

			    						}
			    			]
			    		]
			    	]
			    ]);

				//Deshabilito los campos que están en el arreglo
				 foreach ($arregloComputados as $array)
				 {
				 	echo '<script>$("#inm-'.$array['campo'].'").attr("disabled","disabled");</script>';
				 }

				 if (!utb::getExisteProceso(3094)){
				 	echo '<script>$("#inm-avalt").attr("disabled","disabled");</script>';
				 	echo '<script>$("#inm-avalm").attr("disabled","disabled");</script>';
				 	echo '<script>$("#inm-coef").attr("disabled","disabled");</script>';
				 	echo '<script>$("#inm-valbas").attr("disabled","disabled");</script>';
				 }
				?>

			</td>
		</tr>
	</table>
</div>

<script>

function cambiaCheck()
{
	var modifica = $("#inm-modificaavaluo").is(":checked");

	$("#inm-avaluo_perdesde_anio").toggleClass( "read-only",!modifica );
	$("#inm-avaluo_perdesde_mes").toggleClass( "read-only",!modifica );
	$("#inm_avaluo input").toggleClass( "read-only",!modifica );
	$("#inm_avaluo select").toggleClass( "read-only", !modifica );
	$("#inm_avaluo input[type='checkbox']").prop("disabled", !modifica);
	$("#ckSupHa").prop("disabled", !modifica);
	if ( !modifica )
		$("#ckSupHa").prop("checked", false);
	
	superficieHa();
}

function superficieHa()
{
	var supha = $("#ckSupHa").is(":checked");
	
	$("#txHa").prop("disabled", !supha);
	$("#txA").prop("disabled", !supha);
	$("#txCa").prop("disabled", !supha);
	$("#btCalcularSupHa").prop("disabled", !supha);
}

function calcularSupHa()
{
	var ha = isNaN( parseFloat( $("#txHa").val() ) ) ? 0 : parseFloat( $("#txHa").val() ),
		a  = isNaN( parseFloat( $("#txA").val() ) ) ? 0 : parseFloat( $("#txA").val() ),
		ca = isNaN( parseFloat( $("#txCa").val() ) ) ? 0 : parseFloat( $("#txCa").val() );
	
	var supHa = parseFloat(ha * 10000) + parseFloat(a * 100) + parseFloat(ca);
	
	$("#inm-supt").val( supHa );
}

$(document).ready(function() {

	 cambiaCheck();

});

</script>
