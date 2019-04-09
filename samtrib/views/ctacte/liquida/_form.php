<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\jui\DatePicker;

?>

<div class="liquida-form">
	<?php
    $form = ActiveForm::begin(['id' => 'frmLiqEvent', 'action' => '']);

		Pjax::begin(['id' => 'PjaxTributoSelect']);

        //echo '<input type="text" name="liq_id" id="liq_id" value="'.$liq_id.'" style="display:none">';
    ?>

    <div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>

		<table border='0'>
		<tr>
			<td width='100px'>
				<label> Referencia: </label>
			</td>
			<td colspan='4'>
	    		<?= Html::input('text', 'txCtaCte_Id', $arrayCtaCte['ctacte_id'], ['class' => 'form-control solo-lectura','id'=>'txCtaCte_Id', 'tabindex'=>-1,'style'=>'width:60px; ']); ?>

	    		<label>Tributo: </label>
    			<?= Html::dropDownList('dlTributo', $arrayCtaCte['trib_id'], utb::getAux('trib t inner join sam.sis_usuario_trib u on t.trib_id=u.trib_id','t.trib_id','t.nombre',0,"t.Est='A' and t.Tipo in (3, 4, 6) and not t.Trib_Id in (7,8) and u.usr_id=".Yii::$app->user->id),
    					['prompt'=>'Seleccionar...','class' => 'form-control', 'style' => 'width:300px', 'id'=>'dlTributo',
							'onchange' => 'dlTributoChange();']); ?>

    			<div style='float:right'>
	    			<label>Estado: </label>
		    		<?= Html::input('text', 'txEstado', utb::getCampo('CtaCte_TEst',"cod='".$arrayCtaCte['est']."'"), ['class' => ($arrayCtaCte['est'] == 'B' ? 'form-control baja' : 'form-control solo-lectura'),'id'=>'txEstado', 'disabled'=>'true','style'=>'width:80px;text-align:center;']); ?>
		    	</div>
			</td>
		</tr>
		<tr>
			<td>
				<label> Objeto: </label>
			</td>
			<td width='84px'>
	    		<?= Html::input('text', 'txTObjeto', utb::getTObjNomTrib($arrayCtaCte['trib_id']=='' ? 0 : $arrayCtaCte['trib_id']), ['class' => 'form-control solo-lectura','id'=>'txTObjeto', 'tabindex'=>-1,'style'=>'width:80px;']); ?>
	    	</td>
			<td width='20px'>
	    		<!-- boton de b�squeda modal -->
				<?php
					Modal::begin([
        				'id' => 'BuscaObjcc',
						'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
						'toggleButton' => [
	            			'label' => '<i class="glyphicon glyphicon-search"></i>',
	                		'class' => 'bt-buscar' . ( intVal( $arrayCtaCte['trib_id'] ) == 0 ? ' disabled' : '' ),
	                		'id' => 'btBuscarObj'
            			],
            			'closeButton' => [
            			'label' => '<b>X</b>',
                		'class' => 'btn btn-danger btn-sm pull-right',
            			],
            			'size' => 'modal-lg',
        			]);

					echo $this->render('//objeto/objetobuscarav',['id' => 'cc', 'txCod' => 'txObj_Id', 'txNom' => 'txObjeto_Nom',
							'tobjeto' => $arrayCtaCte['tobj'],'idpx' => 'Busca',  'selectorModal' => '#BuscaObjcc']);

					echo "<script>";
					echo "$('#BuscaObjcc').on('hidden.bs.modal', function () {
												$.pjax.reload({
								container:'#GetPeriodo',
								data:{
									pjGetPeriodo:1,
									obj:$('#txObj_Id').val(),
									trib:$('#dlTributo').val(),
									subcta:$('#txSubCta').val(),
									anio:$('#txAnio').val(),
									venc:$('#fchvenc').val()
								},
								method:'POST'
							});
						});";
					echo "</script>";

        		Modal::end();
        	?>
        	</td>
			<td>
        	<?php Pjax::begin(['id' => 'ObjNombre']); ?>
			<?= Html::input('text', 'txObj_Id', $arrayCtaCte['obj_id'], ['class' => 'form-control','id'=>'txObj_Id','style'=>'width:65px;text-align:center','maxlength'=>'8', 'disabled' => ($arrayCtaCte['trib_id'] <= 0),
				'onBlur'=>'CompletarObjeto()']); ?>
			<?= Html::input('text', 'txObjeto_Nom', utb::getNombObj("'".$arrayCtaCte['obj_id']."'"), ['class' => 'form-control solo-lectura','id'=>'txObjeto_Nom', 'tabindex'=>-1,'style'=>'width:290px;']); ?>
			<label> SubCta:</label>
    		<?= Html::input('text', 'txSubCta', $arrayCtaCte['subcta'], [
					'class' => 'form-control',
					'id'=>'txSubCta',
					'style'=>'width:40px;text-align:center',
					'maxlength'=>'2',
					'readonly' => ($uso_subcta == 1 ? false : true)
				]); ?>
               <?php Pjax::end(); ?>
			</td>
		</tr>
        <tr>
			<td><label>Período- Año:</label></td>
			<td colspan='6'>
				<?php Pjax::begin(['id' => 'GetPeriodo']); ?>
				<?= Html::input('text', 'txAnio', $arrayCtaCte['anio'], ['class' => 'form-control','id'=>'txAnio','style'=>'width:40px;text-align:center','maxlength'=>'4','readonly' => ($ttrib == 3 ? true : false)]); ?>

				<label>Cuota:</label>
				<?= Html::input('text', 'txCuota', ((($arrayCtaCte['cuota'] == 0 or $arrayCtaCte['cuota'] == '') and $consulta == 0) ? 1 : $arrayCtaCte['cuota']), [
						'class' => 'form-control',
						'id'=>'txCuota',
						'style'=>'width:40px;text-align:center',
						'maxlength'=>'2',
						'onchange' => '$.pjax.reload({container:"#ActBtnItem",data:{pjBtnItem:1,trib:$("#dlTributo").val(), anio:$("#txAnio").val(), cuota:$("#txCuota").val(),obj:$("#txObj_id").val(),subcta:$("#txSubcta").val()},method:"POST"});',
						'readonly' => ($ttrib == 3 ? true : false)
					]); ?>

				<label>Fecha Vencimiento:</label>
				<?=
					DatePicker::widget(
					[
						'id' => 'fchvenc',
						'name' => 'fchvenc',
						'dateFormat' => 'dd/MM/yyyy',
						'options' => [
								'style' => 'width:80px;text-align:center',
								'class' => 'form-control',
								'readonly' => ($ttrib != 4 && $consulta == 0 ? false : true)
							],
						'value' => $arrayCtaCte['fchvenc']
					]
					);
				?>
                <?php Pjax::end(); ?>
			</td>
		</tr>
		<tr>
			<td><label>Expe./Comprob.:</label></td>
			<td colspan='6'>
				<?= Html::input('text', 'txExpe', $arrayCtaCte['expe'], ['class' => 'form-control','id'=>'txExpe','style'=>'width:128px','maxlength'=>'15']); ?>
				<div style='float:right'>
					<label>Modificación:</label>
					<?= Html::input('text', 'txModif', utb::getFormatoModif($arrayCtaCte['usrmod'],$arrayCtaCte['fchmod']), ['class' => 'form-control solo-lectura','id'=>'txModif', 'tabindex'=>-1,'style'=>'width:200px;']); ?>
				</div>
			</td>
		</tr>
		<tr>
			<td valign='top'><label>Observación:</label></td>
			<td colspan='6'>
				<?= Html::textarea('txObs', $arrayCtaCte['obs'], ['class' => 'form-control','id'=>'txObs','style'=>'width:100%;height:50px;resize:none']); ?>
			</td>
		</tr>
		</table>

	</div>
	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>
		<table width='100%'>
			<tr>
				<td><h2 style="display:inline-block;"><b><u>Ítems</u></b></h2></td>
				<td align='right'>
				<?=Html::Button('Agregar Ítem', [
					'class' => 'btn btn-success',
					'id' => 'btAgregarItem',
					'onclick' => 'AbrirModal("Agrega",0,0)',
					'disabled' => ($arrayCtaCte['trib_id'] > 0 ? false : true) ,
					'style' => 'margin-bottom:5px;'
				]);?>
				</td>
			</tr>
		</table>
        <?php

		Modal::begin([
        	'id' => 'EditarItem',
        	'header' => '<h1 id="ttModal">Agregar Item</h1>',
            'closeButton' => [
            	'label' => '<b>X</b>',
                'class' => 'btn btn-danger btn-sm pull-right',
            ],
        	]);

        	Pjax::begin(['id' => 'ActBtnItem']);
				echo $this->render('itemsliq',[
						'trib_id' => $arrayCtaCte['trib_id'], 'anio' => $arrayCtaCte['anio'], 'cuota' => $arrayCtaCte['cuota'],
						'obj_id' => $arrayCtaCte['obj_id'],'subcta' => $arrayCtaCte['subcta'],
						'ItemDef' => $ItemDef,
						'param1' => $param1,
						'monto' => $monto,
						'item_id' => $item_id,
						'erroritem' => $erroritem,
						'valor_mm' => $valor_mm,
						'opera' => Yii::$app->request->post('opera', ''),
						'ItemUno' => $ItemUno
    	    		]);
			Pjax::end();

        Modal::end();
		?>
        <?php
        Pjax::begin(['id' => 'ActGrillaItem']);

		if (($consulta == 0 || $consulta == 3) && $dataProviderItem->getCount() > 0){
			echo '<script>$("#dlTributo").attr("readonly", true);</script>';
			echo '<script>$("#fchvenc").attr("readonly", true);</script>';
		}else {
			echo '<script>$("#dlTributo").attr("readonly", false);</script>';
			echo '<script>$("#fchvenc").attr("readonly", false);</script>';
		}

		echo GridView::widget([
			'dataProvider' => $dataProviderItem,
			'id' => 'GrillaLiqItems',
			'summary' => false,
			'headerRowOptions' => ['class' => 'grillaGrande'],
			'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
			'columns' => [

				['attribute'=>'item_id','contentOptions'=>['style'=>'width:40px; text-align:center','class' => 'grillaGrande'],'header' => 'Cod'],
				['attribute'=>'item_nom','contentOptions'=>['style'=>'width:300px;','class' => 'grillaGrande'],'header' => 'Item'],
				['attribute'=>'modulo','format'=>['decimal',2],
						'value' => function ($data) use ($consulta) {
							if ($data['valor_mm']==1)
								return null;
							elseif ($consulta == 3)
								return $data['item_monto'];
							else
								return $data['modulo'];
						},
						'contentOptions'=>['style'=>'width:80px;text-align:right','class' => 'grillaGrande'],'header' => 'Módulo'
					],
           		['attribute'=>'item_monto','format'=>['decimal',2],
						'value' => function ($data) use ($consulta){
							if ($consulta == 3)
								return $data['item_monto'] * $data['valor_mm'];
							else
								return $data['item_monto'];
						},
						'contentOptions'=>['style'=>'width:80px;text-align:right','class' => 'grillaGrande'],'header' => 'Monto'
					],

           		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:40px; padding:1px 10px'],'template' => (in_array($consulta, [0, 3]) ? '{update} {delete}' : ''),
            		'buttons'=>[
						'update' => function($url,$model,$key)
            						{
            							return Html::a('<span class="glyphicon glyphicon-pencil"></span>','#',
												['style' => 'font-size:8px','onclick' => 'AbrirModal("Modif",'.$model['item_id'].','.$model['orden'].')']
											);

            						},
            			'delete' => function($url,$model,$key)
            						{
            							return Html::a('<span class="glyphicon glyphicon-trash"></span>','#',
            									['style' => 'font-size:8px', 'onclick' => 'AbrirModal("Elim",'.$model['item_id'].','.$model['orden'].')'
														]
            							);
            						}
            			]
            	],
    		]

    	]);

		?>

        <table border='0' width='100%'>
			<tr>
				<td align='left'>
					<?= Html::radioList('opUCM', $ucm,[1 => 'UCM', 0 => 'Monto'],['id' => 'opUCM', 'itemOptions' => ['disabled' => true]])?>
				</td>
				<td align='right'>
					<label>Total:</label>
					<?= Html::input('text', 'txTotal',  number_format( $total, 2, '.', '' ), ['class' => 'form-control','id'=>'txTotal','style'=>'width:90px;background:#E6E6FA;text-align:right','disabled'=>'true']);?>
				</td>
			</tr>
			<tr>
				<td colspan='2'>
				<br><div id="errorliqevent" style="display:<?=($error == '' ? 'none' : 'block')?>;" class="error-summary"><?=$error?></div>
				</td>
			</tr>
    	</table>
		<?php Pjax::end(); ?>
    </div>

	<?php

		if ($consulta !== 1){
	?>
	<div class="form-group">
    	<?php

			if ($consulta==2)
			{
				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'id' => 'btEliminarLiqAcep',
							'data' => [
										'toggle' => 'modal',
        								'target' => '#ModalEmiminarLiq',
   									],]);

				Modal::begin([
        				'id' => 'ModalEmiminarLiq',
        				'size' => 'modal-sm',
        				'header' => '<h4><b>Confirmar Eliminación</b></h4>',
        				'closeButton' => [
            				'label' => '<b>X</b>',
                			'class' => 'btn btn-danger btn-sm pull-right',
                			'id' => 'btCancelarModalElim'
            				],
        			]);

        			echo "<center>";
        			echo "<p><label>Esta seguro de Eliminar la Liquidación?</label></p>";
        			echo "</center>";

        			echo "<label>Motivo</label>";

        			echo Html::textarea('txMotivo', null, ['class' => 'form-control','id'=>'txMotivo','style'=>'width:270px;height:50px;']);

					echo '<div id="errorliqelim" style="display:none;" class="alert alert-danger alert-dismissable"></div>';

        			$url = Yii::$app->request->baseUrl.'/index.php?r=ctacte/liquida/delete&id='.$arrayCtaCte['ctacte_id'].'&accion=1&motivo=';

        			echo "<center>";
        			echo Html::Button('Aceptar', ['class' => 'btn btn-success', 'id' => 'btEliminarLiqModalAcep','onClick' => 'btEliminarLiqAcepModalClick("'.$url.'");']);
        			echo "&nbsp;&nbsp;";
			 		echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btEliminarLiqModalCanc','onClick' => '$("#ModalEmiminarLiq, .window").modal("toggle");']);
					echo "</center>";
			 	Modal::end();

     		 }else {
				echo Html::Button('Grabar', ['class' => 'btn btn-success', 'id' => 'btGrabarLiq', 'onclick' => 'btGrabarLiqClick()']);
			 }

			 echo "&nbsp;&nbsp;";
			 echo Html::a('Cancelar', ['cancelar','ctacte' => ($arrayCtaCte['ctacte_id'] == '' ? 0 : $arrayCtaCte['ctacte_id'])],
			 					['class' => 'btn btn-primary']);

		?>
    </div>

    <?php
		}
    		Pjax::end(); // fin pjax PjaxTributoSelect
		ActiveForm::end();

    	if ($consulta==1 or $consulta==2)
    	{
    		echo "<script>";
			echo "DesactivarForm('frmLiqEvent');";
			echo '$("#GrillaLiqItems").css("pointer-events", "none");';
			if ($consulta==2) echo '$("#btEliminarLiqAcep").removeAttr("disabled");';
			if ($consulta==2) echo '$("#txMotivo").removeAttr("disabled");';
			if ($consulta==2) echo '$("#txMotivo").removeAttr("readonly");';
			if ($consulta==2) echo '$("#btEliminarLiqModalCanc").removeAttr("disabled");';
			if ($consulta==2) echo '$("#btEliminarLiqModalAcep").removeAttr("disabled");';
			if ($consulta==2) echo '$("#btCancelarModalElim").removeAttr("disabled");';
			echo "</script>";
		}

		if ($consulta==0)
    	{
    		echo "<script>";
			echo '$("#txCtaCte_Id").removeAttr("disabled");';
			echo '$("#txCtaCte_Id").prop("readonly", "true");';
			echo "</script>";
		}

		if ($consulta==3)
    	{
    		echo "<script>";
			echo '$("#txCtaCte_Id").removeAttr("disabled");';
			echo '$("#txCtaCte_Id").prop("readonly", "true");';
			echo '$("#txObj_Id").removeAttr("disabled");';
			echo '$("#txObj_Id").prop("readonly", "true");';
			echo '$("#txObj_Id").css("pointer-events", "none");';
			echo '$("#txSubCta").removeAttr("disabled");';
			echo '$("#txSubCta").prop("readonly", "true");';
			echo '$("#txSubCta").css("pointer-events", "none");';
			echo '$("#txAnio").removeAttr("disabled");';
			echo '$("#txAnio").prop("readonly", "true");';
			echo '$("#txAnio").css("pointer-events", "none");';
			echo '$("#txCuota").removeAttr("disabled");';
			echo '$("#txCuota").prop("readonly", "true");';
			echo '$("#txCuota").css("pointer-events", "none");';
			echo '$("#txObs").removeAttr("disabled");';
			echo '$("#txObs").removeAttr("readonly");';
			echo '$("#txExpe").removeAttr("disabled");';
			echo '$("#txExpe").removeAttr("readonly");';
			echo '$("#btAgregarItem").removeAttr("disabled");';
			echo '$("#btGrabarLiq").removeAttr("disabled");';
			echo '$("#dlTributo").attr("readonly",true);';
			echo '$("#dlTributo").css("pointer-events","none");';
			echo '$("#fchvenc").attr("readonly",false);';
			echo '$("#fchvenc").css("pointer-events", "all");';
			echo '$("#GrillaLiqItems").css("pointer-events", "all");';
			echo '$("#btBuscarObj").css("pointer-events", "none");';

			echo "</script>";
		}
    ?>

</div>

<script>
<?php if ($error != ""){ ?>
	mostrarErrores(<?= $error ?>, $("#errorliqevent"));
<?php } ?>

function dlTributoChange()
{
	var codigoTributo= parseInt($("#dlTributo").val());

	if(!isNaN(codigoTributo) && codigoTributo > 0){

		$.pjax.reload({
			container : "#PjaxTributoSelect",
			replace : false,
			push : false,
			type :"POST",
			data : {
				"pjTribSelec" : 1,
				"trib_id" : $("#dlTributo").val()
			}
		});

	}
}

function CompletarObjeto()
{
	$.pjax.reload({
			container : "#ObjNombre",
			replace : false,
			push : false,
			type :"POST",
			data : {
				"pjObjNom" : 1,
				"trib_id" : $("#dlTributo").val(),
				"obj_id" : $("#txObj_Id").val(),
				"subcta" : $("#txSubCta").val()
			}
		});

	$("#ObjNombre").on("pjax:complete", function(){

		$.pjax.reload({
			container:"#GetPeriodo",
			data:{
				pjGetPeriodo:1,
				obj:$("#txObj_Id").val(),
				trib:$("#dlTributo").val(),
				subcta:$("#txSubCta").val(),
				anio:$("#txAnio").val(),
				venc:$("#fchvenc").val()
			},
			method:"POST"
		});

		$("#ObjNombre").off("pjax:complete");
	});

	$("#GetPeriodo").on("pjax:complete", function(){

		if ($("#fchvenc").val() == null){
			$("#errorliqevent").html("No están definidos los Vencimientos para el Período");
			$("#errorliqevent").css("display", "block");
			$("#txCuota").val(0);
		}else {
			$("#errorliqevent").html("");
			$("#errorliqevent").css("display", "none");
		}

		if ($("#dlTributo").val() == 4 && $("#txCuota").val() != 0)
			$("#fchvenc").prop("readonly", true);
		else
			$("#fchvenc").prop("readonly", false);

		if ($("#txSubCta").prop("readonly") == false){
			$("#txSubCta").focus();
		 }else if ($("#txAnio").prop("readonly") == false) {
			$("#txAnio").focus();
		 }else {
			$("#txExpe").focus();
		 }

		$("#GetPeriodo").off("pjax:complete");
	});
}

function AbrirModal(op,it,ord)
{
	$.pjax.reload({
		container:"#ActBtnItem",
		data:{
			pjBtnItem:1,
			trib:$("#dlTributo").val(),
			anio:$("#txAnio").val(),
			cuota:$("#txCuota").val(),
			obj:$("#txObj_id").val(),
			subcta:$("#txSubcta").val(),
			venc:$("#fchvenc").val(),
			opera:op,
			item:it,
			orden:ord
			},
		method:"POST"
	});

	$("#EditarItem").modal();
}

function btGrabarLiqClick()
{
	error = [];
	if ($("#dlTributo").val() == '') error.push('Seleccione un Tributo');
	if ($("#txObj_Id").val() == '' || $("#txObjeto_Nom").val() == '') error.push('Ingrese un Objeto válido');
	if(!$("#txSubCta").attr("readonly") && $("#txSubCta").val() == '') error.push("Ingrese una subcuenta");

	if (error.length == 0)
		$("#frmLiqEvent").submit();
	else
		mostrarErrores(error, $("#errorliqevent"));
}

function btEliminarLiqAcepModalClick(url)
{
	error = '';
	if ($("#txMotivo").val() == '') error = 'Ingrese Motivo de Baja';

	if (error !== '')
	{
		$("#errorliqelim").html("Ingrese motivo");
		$("#errorliqelim").css("display","block");
	}else {
		$("#errorliqelim").html("");
		$("#errorliqelim").css("display","none");

		$(location).attr('href', url+$("#txMotivo").val());
	}
}

</script>
