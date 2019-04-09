<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use yii\widgets\MaskedInput;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

use app\models\objeto\Domi;
use app\utils\db\Fecha;
use app\utils\db\utb;

use app\views\objeto\BusquedaAvanzada;

/* @var $this yii\web\View */
/* @var $model app\models\objeto\Persona */
/* @var $modelobjeto app\models\objeto\Objeto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rodado-form">

<div class="form-panel" style="padding:10px; ">
	<?php
	$form = ActiveForm::begin(['id' => 'formRodado', 'fieldConfig' => ['template' => '{label}{input}'], 'validateOnSubmit' => false]);

	echo Html::input('hidden', 'tab', $extras['tab']);
	echo $form->field($extras['model'], 'eliminarLiquidaciones')->input('hidden', ['id' => 'formRodadoEliminarLiquidaciones'])->label(false);


	//modal para perdir confirmacion de eliminar las liquidaciones en caso de que se haya tratado de modificar el perini
	if($extras['model']->tieneLiquidaciones === 1 && $extras['model']->eliminarLiquidaciones === 0){

		Modal::begin([
			'id' => 'modalEliminarLiquidacionesRodado',
			'size' => 'modal-sm',
			'header' => '<h4><b>Cambio de Período Inicial</b></h4>',
			'closeButton' => [
				'label' => '<b>&times;</b>',
				'class' => 'btn btn-danger btn-sm pull-right'
			]
		]);

		?>
		<center>
			<p><label>¿Desea eliminar las liquidaciones anteriores al Per&iacute;odo Inicial?</label></p>


		<?= Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => '$("#formRodadoEliminarLiquidaciones").val(1); formSubmit();']); ?>
		&nbsp;&nbsp;
		<?= Html::button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => '$("#modalEliminarLiquidacionesRodado").modal("hide");']); ?>
    	</center>

		<?php

		Modal::end();

		?>
		<script>
		$(document).ready(function(){ $("#modalEliminarLiquidacionesRodado").modal("show");});
		</script>
		<?php
	}
	?>

	<table border="0" width="620px">
		<tr>
			<td align="left">
				<?= $form->field($extras['modelObjeto'], 'obj_id', ['template' => '{label}<br>{input}'])->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:80px;'])->label('Objeto'); ?>
				<?= $form->field($extras['model'], 'obj_id')->input('hidden')->label(false) ?>
			</td>

			<td align="left" width="250px">
				<?= $form->field($extras['modelObjeto'], 'nombre', ['template' => '{label}<br>{input}'])
					->textInput(['maxlength' => 50, 'style' => 'width:450px', 'class' => (($extras['consulta'] != 0 && $extras['consulta'] != 3) ? 'form-control solo-lectura' : 'form-control'), 'id' => 'formRodadoNombre'])
					->label('Nombre') ?>
			</td>
			<td width="5px"></td>
			<td align="left" width="90px">
				<?= $form->field($extras['modelObjeto'], 'est_nom', ['template' => '{label}<br>{input}'])->textInput(['maxlength' => 20, 'class' => ($extras['modelObjeto']->est == 'B' ? 'form-control baja' : 'form-control solo-lectura'), 'tabindex' => -1, 'style' => 'width:75px;text-align:center'])->label('Estado') ?>
			</td>
			<td width="10px"></td>
		</tr>
	</table>

	<table border="0" width="620px">
		<tr>
			<td width="85px"><label>Dominio: </label></td>
			<td width="5px"></td>
			<td width="80px"><?= $form->field($extras['model'], 'dominio', ['options' => ['style' => 'width:72px; display:inline-block;']])->textInput(['onkeypress' => 'return justNumbersAndStr( event )', 'style' => 'width:70px; text-transform:uppercase;', 'maxlength' => 9, 'id' => 'formRodadoDominio'])->label(false) ?></td>

			<td width="5px"></td>
			<td width="50px"><label>Anterior: </label></td>
			<td><?= $form->field($extras['model'], 'dominioant')->textInput(['style' => 'width:70px; text-transform:uppercase;', 'maxlength' => 9])->label(false) ?></td>
		</tr>
	</table>

	<table border="0" width="620px">

		<tr>
			<td><label>Categor&iacute;a: </label></td>
			<td></td>
			<td colspan="4"><?= $form->field($extras['model'], 'cat')->dropDownList(utb::getAux('rodado_tcat'), ['style' => 'width:96%;', 'prompt' => '', 'id' => 'formRodadoCategoria', 'onchange' => 'cambiaCategoria($(this).val());'])->label(false) ?></td>
			<td></td>
			<td><label>Delegaci&oacute;n: </label></td>
			<td colspan="5"><?= $form->field($extras['model'], 'deleg')->dropDownList(utb::getAux('rodado_tdeleg'), ['prompt' => '', 'style' => 'width:100%;'])->label(false) ?></td>
		</tr>

		<tr>
			<td width="85px"><label>Tipo de Alta: </label></td>
			<td width="5px"></td>
			<td colspan="4"><?= $form->field($extras['model'], 'talta')->dropDownList(utb::getAux('rodado_talta'), ['style' => 'width:96%;', 'prompt' => '', 'id' => 'formRodadoTipoAlta'])->label(false) ?></td>
			<td></td>
			<td colspan="6"><label>Per&iacute;odo Inicial: </label>

				<?= $form->field($extras['model'], 'adesde', ['options' => ['style' => 'width:45px; display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 4, 'id' => 'formRodadoADesde'])->label(false) ?>
				<?= $form->field($extras['model'], 'cdesde', ['options' => ['style' => 'width:35px; display:inline-block;']])->textInput(['style' => 'width:30px;', 'maxlength' => 3, 'id' => 'formRodadoCDesde'])->label(false) ?>

			<label>A&ntilde;o Fabric.: </label>
			<?= $form->field($extras['model'], 'anio', ['options' => ['style' => 'width:45px; display:inline-block;']])->textInput(['style' => 'width:40px;', 'maxlength' => 4, 'id' => 'formRodadoAnio', 'onchange' => 'cambiaAnio($(this).val());'])->label(false) ?></td>
		</tr>

		<tr>
			<td><label>Tipo de Liq.: </label></td>
			<td></td>
			<td colspan="4"><?= $form->field($extras['model'], 'tliq', ['options' => ['id' => 'formRodadoTipoLiquidacion']])
								->radioList(
											[1 => 'Aforo', 2 => 'Categoría y Peso'],
											['separator' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
											'item' => function($index, $label, $name, $checked, $value){

												$onclick = 'cambiaTLiq($(this).val())';

												return Html::radio($name, $checked, ['label' => $label, 'value' => $value, 'onclick' => $onclick, 'class' => 'checkTLiq']);
											}]
											)
								->label(false)
							?>
			</td>
		</tr>
	</table>
	<table  border="0" width="620px">
		<tr id="filaAforo" height="51px" class="<?= $extras['model']->tliq != 1 ? 'hidden' : null ?>">
			<td width="85px"></td>
			<td width="5px"></td>
			<td width="85px">
				<label style="display:block; visibility:hidden;">Aforo</label>
				<?= $form->field($extras['model'], 'aforo_id', ['options' => ['style' => 'margin:0px;']])->textInput(['id' => 'formRodadoAforo', 'onchange' => 'cambiaAforo();', 'style' => 'width:80px;', 'maxlength' => 8])->label(false); ?>
			</td>
			<td width="5px"></td>
			<td colspan="8">
				<label style="display:block; margin-left:30px;">Modelo</label>
				<?php
				Pjax::begin(['id' => 'pjaxModalBusquedaAforo', 'enableReplaceState' => false, 'enablePushState' => false, 'timeout' => 100000, 'options' => ['style' => 'display:inline-block;']]);

				$anio= intval(Yii::$app->request->get('anio', $extras['model']->anio));
				$aforo_id = trim(Yii::$app->request->get('aforo_id', $extras['model']->aforo_id));

				Modal::begin([
	                'id' => 'modalBusquedaAforo',
					'header' => '<h2>Búsqueda de Modelos de Aforos.' . ($anio <= 0 ? '' : ' Año ' . $anio) . '</h2>',
					'toggleButton' => [
	                    'label' => '<i class="glyphicon glyphicon-search"></i>',
	                    'class' => 'bt-buscar',
	                    'disabled' => ($extras['consulta'] == 1 || $extras['consulta'] == 2)
	                ],
	                'closeButton' => [
	                  'label' => '<b>X</b>',
	                  'class' => 'btn btn-danger btn-sm pull-right'
	                ],
	                'size' => 'modal-lg'
	            ]);

				if(in_array($extras['consulta'], [0, 3]))
					echo $this->render('_buscaaforo', ['extras' => $extras, 'selectorPjax' => '#pjaxNombreModeloAforo', 'anio' => $anio]);

	            Modal::end();
				
				$extras['model']->valor = utb::getCampo("rodado_aforo_val","aforo_id = '$aforo_id' and anioval=" . date('Y') . " and anio=$anio","valor");
				echo "<script> $('#rodadoFormModeloValor').val('" . $extras['model']->valor . "'); </script>";
				
	            Pjax::end();
	            Pjax::begin(['id' => 'pjaxNombreModeloAforo', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block']]);

	            //determina que se debe cargar el modelo de aforo
	            $cargar = filter_var(Yii::$app->request->get('cargarModeloAforo', false), FILTER_VALIDATE_BOOLEAN);

	            if($cargar){

	            	$aforo_id = trim(Yii::$app->request->get('aforo_id', ''));

	            	if($aforo_id != ''){
	            		$extras['model']->aforo_id = $aforo_id;
	            		$condicion = "aforo_id = '$aforo_id'";
						
						$datos = utb::getVariosCampos('v_rodado_aforo', $condicion, 'modelo_nom, valor_max');

	            		if($datos !== false){
	            			$extras['model']->modelo_nom = $datos['modelo_nom'];
	            			$extras['model']->valor = $datos['valor_max'];
	            		}
						
						if ($anio != 0){
							$extras['model']->valor = utb::getCampo("rodado_aforo_val","aforo_id = '$aforo_id' and anioval=2017 and anio=$anio","valor");
						}
	            	}

	            	$esModal = filter_var(Yii::$app->request->get('esModal', false), FILTER_VALIDATE_BOOLEAN);

	            	if($esModal){
	            		?>
	            		<script type="text/javascript">
	            		$(document).ready(function(){
	            			$("#modalBusquedaAforo").modal("hide");

	            			$("#formRodadoAforo").val("<?= $extras['model']->aforo_id; ?>");
	            			$("#rodadoFormModeloValor").val("<?= $extras['model']->valor; ?>");
	            		});
	            		</script>
	            		<?php
	            	}

	            }

	            echo $form->field($extras['model'], 'modelo_nom', ['options' => ['style' => 'display:inline-block;']])->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:330px;', 'id' => 'rodadoFormModeloNombre'])->label(false);
	            
				echo "<script> $('#rodadoFormModeloValor').val('" . $extras['model']->valor . "'); </script>";
				
				Pjax::end();

	            ?>
			</td>

			<td>
				<label style="display:block">Valor</label>
				<?= $form->field($extras['model'], 'valor', ['options' => ['style' => 'margin:0px;']])->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:70px;', 'id' => 'rodadoFormModeloValor'])->label(false); ?>
			</td>

		</tr>
	</table>
	<table border="0" width="620px">
		<tr id="filaCategoriaPeso" class="<?= $extras['model']->tliq != 2 ? 'hidden' : null ?>">
			<td>
				<label style="display:block; visibility:hidden;">&nbsp;</label>
				<label>Marca: </label>
			</td>
			<td></td>
			<td colspan="4">
				<label style="display:block; visibility:hidden;">&nbsp;</label>
				<?= $form->field($extras['model'], 'marca')->dropDownList(utb::getAux('rodado_marca'), ['style' => 'width:96%;', 'prompt' => '', 'onchange' => 'cambiaMarca($(this).val())', 'id' => 'formRodadoCategoriaPesoMarca'])->label(false) ?>
			</td>
			<td></td>
			<td colspan="6">
				<label style="display:block; margin-left:30px;">Modelo</label>
				<?php
				Pjax::begin(['id' => 'pjaxBusquedaModelo', 'enableReplaceState' => false, 'enablePushState' => false]);

				$marcaElegida= intval(Yii::$app->request->get('marcaElegida', 0));
				$categoriaElegida= intval(Yii::$app->request->get('categoriaElegida', 0));

				Modal::begin([
	                'id' => 'modalBusquedaModelo',
					'header' => '<h2>Búsqueda de Modelo' . ($anio > 0 ? '. Año ' . $anio : '') . '</h2>',
					'toggleButton' => [
	                    'label' => '<i class="glyphicon glyphicon-search"></i>',
	                    'class' => 'bt-buscar',
	                    'disabled' => ($extras['consulta'] == 1 || $extras['consulta'] == 2)
	                ],
	                'closeButton' => [
	                  'label' => '<b>X</b>',
	                  'class' => 'btn btn-danger btn-sm pull-right'
	                ]

	            ]);

				if($extras['consulta'] != 1 && $extras['consulta'] != 2){
					echo $this->render('_buscamodelo', ['extras' => $extras, 'selectorPjax' => '#pjaxFormRodadoBuscaModelo', 'marcaElegida' => $marcaElegida, 'categoriaElegida' => $categoriaElegida]);
				}

	            Modal::end();

	            Pjax::begin(['id' => 'pjaxFormRodadoBuscaModelo', 'enableReplaceState' => false, 'enablePushState' => false, 'options' => ['style' => 'display:inline-block; width:90%;']]);
	            $esModal = filter_var(Yii::$app->request->get('esModal', false), FILTER_VALIDATE_BOOLEAN);

				if($esModal){

					$extras['model']->modelo_nom = Yii::$app->request->get('modeloElegidoNombre', '');

					?>
					<script type="text/javascript">
						$(document).ready(function(){

							$("#formRodadoCategoriaPesoMarca").val("<?= Yii::$app->request->get('marcaElegida', ''); ?>");
							$("#formRodadoModelo").val("<?= Yii::$app->request->get('modeloElegido', ''); ?>");
							$("#modalBusquedaModelo").modal("hide");


						});
					</script>
					<?php
				}



				echo $form->field($extras['model'], 'modelo', ['options' => ['class' => 'hidden']])->input('hidden', ['id' => 'formRodadoModelo'])->label(false);

	            echo $form->field($extras['model'], 'modelo_nom', ['options' => ['style' => 'display:inline-block; width:100%;']])
	            	->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:100%;', 'id' => 'rodadoFormModeloNombreCategoriaPeso'])
	            	->label(false);

	            Pjax::end();
	            Pjax::end();
	            ?>
			</td>
		</tr>

		<tr>
			<td><label>Marca Motor: </label></td>
			<td></td>
			<td colspan="4"><?= $form->field($extras['model'], 'marcamotor')->dropDownList(utb::getAux('rodado_marca'), ['style' => 'width:96%;', 'prompt' => '', 'id' => 'formRodadoMarcaMotor'])->label(false) ?></td>
			<td></td>
			<td><label>Nº Motor: </label></td>
			<td colspan="5"><?= $form->field($extras['model'], 'nromotor')->textInput(['style' => 'width:100%;  text-transform:uppercase;', 'maxlength' => 30, 'id' => 'formRodadoNumeroMotor'])->label(false) ?></td>
		</tr>

		<tr>
			<td><label>Marca Chasis: </label></td>
			<td></td>
			<td colspan="4"><?= $form->field($extras['model'], 'marcachasis')->dropDownList(utb::getAux('rodado_marca'), ['style' => 'width:96%;', 'prompt' => '', 'id' => 'formRodadoMarcaChasis'])->label(false) ?></td>
			<td></td>
			<td><label>Nº Chasis: </label></td>
			<td colspan="5"><?= $form->field($extras['model'], 'nrochasis')->textInput(['style' => 'width:100%; text-transform:uppercase;', 'maxlength' => 30])->label(false) ?></td>
		</tr>

		<tr>
			<td><label>Peso/Valor: </label></td>
			<td></td>
			<td><?= $form->field($extras['model'], 'peso')->textInput(['style' => 'width:70px;', 'maxlength' => 9, 'onkeypress' => 'return justDecimal( $( this ).val(), event )'])->label(false); ?></td>
			<td width="5px"></td>
			<td><label>Cilindrada: </label></td>
			<td><?= $form->field($extras['model'], 'cilindrada')->textInput(['style' => 'width:50px;', 'maxlength' => 5])->label(false) ?></td>
			<td></td>
			<td><label>Color: </label></td>
			<td colspan="5"><?= $form->field($extras['model'], 'color')->textInput(['style' => 'width:100%;', 'maxlength' => 15])->label(false) ?></td>
		</tr>

		<tr>
			<td><label>Combustible:</label></td>
			<td></td>
			<td colspan="4"><?= $form->field($extras['model'], 'combustible')->dropDownList(utb::getAux('rodado_tcombustible'), ['style' => 'width:96%;', 'prompt' => ''])->label(false) ?></td>
			<td></td>
			<td><label>Uso: </label></td>
			<td colspan="5"><?= $form->field($extras['model'], 'uso')->dropDownList(utb::getAux('rodado_tuso'), ['prompt' => '', 'style' => 'width:100%;', 'id' => 'formRodadoUso'])->label(false) ?></td>
		</tr>

		<tr>
			<td><label>Distribución: </label></td>
			<td></td>
			<td colspan="4"><?= $form->field($extras['modelObjeto'], 'tdistrib')->dropDownList(utb::getAux('objeto_tdistrib'), ['style' => 'width:96%;', 'prompt' => ''])->label(false) ?></td>
			<td></td>
			<td><label>Distribuidor: </label></td>
			<td colspan="5">
				<?= $form->field($extras['modelObjeto'], 'distrib')->dropDownList(utb::getAux('sam.sis_usuario', 'usr_id', 'apenom', 1, 'distrib=1'), ['style' => 'width:100%;', 'prompt' => ''])->label(false) ?>
			</td>
		</tr>
		
		<tr>
			<td><label>Conductor: </label></td>
			<td></td>
			<td colspan="9">
				<?php
				Modal::begin([
	                'id' => 'buscaConductor',
					'header' => '<h2>Búsqueda de Persona</h2>',
					'toggleButton' => [
	                    'label' => '<i class="glyphicon glyphicon-search"></i>',
	                    'class' => 'bt-buscar',
	                    'disabled' => ($extras['consulta'] == 1 || $extras['consulta'] == 2)
	                ],
	                'closeButton' => [
	                  'label' => '<b>X</b>',
	                  'class' => 'btn btn-danger btn-sm pull-right'
	                ],
	                'size' => 'modal-lg'
	            ]);

	            if($extras['consulta'] != 1 && $extras['consulta'] != 2)
	            	echo $this->render('//objeto/objetobuscarav', ['id' => 'buscarConductorRodado', 'txCod' => 'formRodadoCodigoConductor', 'txNom' => 'formRodadoNombreConductor', 'tobjeto' => 3, 'selectorModal' => '#buscaConductor']);

	            Modal::end();

				echo $form->field($extras['model'], 'conductor', ['options' => ['class' => 'hidden']])->input('hidden', ['id' => 'formRodadoCodigoConductor'])->label(false);
				echo $form->field($extras['model'], 'nombreconductor', ['options' => ['style' => 'display:inline-block; width:252px;']])
					->textInput(['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id' => 'formRodadoNombreConductor', 'style' => 'width:100%;'])
					->label(false);
				?>
			</td>
		</tr>

		<tr>
			<td colspan="14"><label>Domicilio Postal: </label>

				<?php
				Modal::begin([
	                'id' => 'BuscaDomiP',
					'header' => '<h2>Búsqueda de Domicilio</h2>',
					'toggleButton' => [
	                    'label' => '<i class="glyphicon glyphicon-search"></i>',
	                    'class' => 'bt-buscar',
	                    'disabled' => ($extras['consulta'] == 1 || $extras['consulta'] == 2)
	                ],
	                'closeButton' => [
	                  'label' => '<b>X</b>',
	                  'class' => 'btn btn-danger btn-sm pull-right'
	                ]

	            ]);

	            if($extras['consulta'] != 1 && $extras['consulta'] != 2)
	            	echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $extras['modelDomicilio'],'tor' => 'OBJ']);

	            Modal::end();

	            $modelDomicilio = isset($extras['modelDomicilio']) ? $extras['modelDomicilio'] : new Domi();

				echo Html::input('text', 'domi', $modelDomicilio->domicilio, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'id'=>'formRodadoDomicilio','style' => 'width:350px;']);
				echo '<input type="text" name="arrayDomicilioPostal" id="arrayDomicilioPostal" value="'.urlencode(serialize($modelDomicilio)).'" style="display:none">';



				Pjax::begin(['id' => 'CargarModeloDomi', 'enablePushState' => false, 'enableReplaceState' => false]);

				if(Yii::$app->request->post('tor', '') === 'OBJ'){

					$modelDomicilio->torigen = 'OBJ';
					$modelDomicilio->obj_id = $extras['model']->obj_id;
					$modelDomicilio->id = 0;
					$modelDomicilio->prov_id = Yii::$app->request->post('prov_id', 0);
					$modelDomicilio->loc_id = Yii::$app->request->post('loc_id', 0);
					$modelDomicilio->cp = Yii::$app->request->post('cp', '');
					$modelDomicilio->barr_id = Yii::$app->request->post('barr_id', 0);
					$modelDomicilio->calle_id = Yii::$app->request->post('calle_id', 0);
					$modelDomicilio->nomcalle = Yii::$app->request->post('nomcalle', '');
					$modelDomicilio->puerta = Yii::$app->request->post('puerta', '');
					$modelDomicilio->det = Yii::$app->request->post('det', '');
					$modelDomicilio->piso = Yii::$app->request->post('piso', '');
					$modelDomicilio->dpto = Yii::$app->request->post('dpto', '');

//					$modelDomicilio->domicilio = $modelDomicilio->nomcalle.' '.$modelDomicilio->puerta.' '.$modelDomicilio->det.' Piso: '.($modelDomicilio->piso != '' ? ' Piso: '.$modelDomicilio->piso : '');
//					$modelDomicilio->domicilio .= ($modelDomicilio->dpto !='' ? ' Dpto: '.$modelDomicilio->dpto.' - ' : '').utb::getCampo("domi_localidad","loc_id=".$modelDomicilio->loc_id,"nombre");
//					$modelDomicilio->domicilio .= ' - '.utb::getCampo("domi_provincia","prov_id=".$modelDomicilio->prov_id,"nombre");

					echo '<script>$("#formRodadoDomicilio").val("'.$modelDomicilio->armarDescripcion().'")</script>';
					echo '<script>$("#arrayDomicilioPostal").val("'.urlencode(serialize($modelDomicilio)).'")</script>';
				}

				Pjax::end();
	            ?>
			</td>
		</tr>
		
		<tr>
			<td><label>Tipo Form.:</label></td>
			<td></td>
			<td colspan="4"><?= $form->field($extras['model'], 'tform')->dropDownList(utb::getAux('rodado_tform'), ['style' => 'width:96%;', 'prompt' => ''])->label(false) ?></td>
			<td></td>
			<td><label>Remito: </label></td>
			<td> <?= $form->field($extras['model'], 'remito')->textInput(['style' => 'width:90%;', 'maxlength' => 10])->label(false) ?> </td>
			<td> <label>Año: </label> </td>
			<td> <?= $form->field($extras['model'], 'remito_anio')->textInput(['style' => 'width:40%;', 'maxlength' => 4])->label(false) ?> </td>
		</tr>

		<?php
		if($extras['tieneMiscelaneas']){
			?>
			<tr>
				<td colspan="8">
					<div class="glyphicon glyphicon-comment" style="color:#337ab7">
						<?= Html::a('<b>Existen miscelaneas</b>', ['//objeto/objeto/miscelaneas', 'id' => $extras['model']->obj_id], ['class' => 'bt-buscar-label']); ?>
					</div>
				</td>
			</tr>
			<?php
		}
		?>
	</table>
</div>

<!-- INICIO Div Fechas -->
<div style="padding:10px;margin-top: 8px" class="form-panel">
	<table border="0" width="600px">
		<tr>
			<td>
				<br>
				<label>Fechas: </label>
			</td>
			<td width="15px"></td>
			<td>
				<label>Compra</label>
				<?php

				if(in_array($extras['consulta'], [1, 2])){

					if(isset($extras['model']->fchcompra) && trim($extras['model']->fchcompra) != '')
						$extras['model']->fchcompra= Fecha::bdToUsuario($extras['model']->fchcompra);

					echo Html::textInput(null, $extras['model']->fchcompra, ['style' => 'width:80px;', 'class' => 'form-control']);

				} else echo DatePicker::widget(['model' => $extras['model'], 'attribute' => 'fchcompra', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'style' => 'width:80px;']]);
				?>
			</td>
			<td width="5px"></td>
			<td>
				<label>Alta</label>
				<?php

				if(in_array($extras['consulta'], [1, 2])){

					if(isset($extras['modelObjeto']->fchalta) && trim($extras['modelObjeto']->fchalta) != '')
						$extras['modelObjeto']->fchalta= Fecha::bdToUsuario($extras['modelObjeto']->fchalta);

					echo Html::textInput(null, $extras['modelObjeto']->fchalta, ['style' => 'width:80px;', 'class' => 'form-control']);

				} else echo DatePicker::widget(['model' => $extras['modelObjeto'], 'attribute' => 'fchalta', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'style' => 'width:80px;']]);
				?>
			</td>
			<td width="5px"></td>
			<td>
				<label>Baja</label>
				<?php
					$baja = '';

					if($extras['modelObjeto']->fchbaja != null && trim($extras['modelObjeto']->fchbaja) != '')
						$baja = Fecha::bdToUsuario($extras['modelObjeto']->fchbaja);

					echo Html::textInput(null, $baja, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:80px;'])
				?>
			</td>
			<td width="5px"></td>
			<td>
				<label>Modificaci&oacute;n</label>
				<?php
					$modif = null;

					if($extras['modelObjeto']->usrmod !== null && $extras['modelObjeto']->fchmod !== null && trim($extras['modelObjeto']->fchmod) != '')
						$modif = utb::getFormatoModif($extras['modelObjeto']->usrmod, $extras['modelObjeto']->fchmod);

					echo Html::textInput(null, $modif, ['class' => 'form-control solo-lectura', 'tabindex' => -1, 'style' => 'width:250px;'])
				?>
			</td>
		</tr>
	</table>
</div>
<!-- FIN Div Fechas -->

<?php
echo $form->field($extras['modelObjeto'], 'obs', ['options' => ['class' => 'hidden']])->input('hidden', ['id' => 'modelRodadoObservacion']);

?>

<!-- INICIO Div Baja -->
<?php if ( $extras['consulta'] == 2 ){ ?>

<div id="comer_form_divBaja" class="form-panel">

	<h4><label><u>Información sobre la Baja</u></label></h4>

	<table border='0'>
		<tr>
			<td><label>Motivo de baja</label></td>
			<td>
				<?=
					Html::activeDropDownList( $extras['modelObjeto'], 'tbaja', utb::getAux('objeto_tbaja','cod','nombre',0,'tobj=5'), [
						'prompt'=>'Seleccionar...',
						'class'	=> 'form-control',
					]);
				?>
			</td>
			<td width="20px"></td>
			<td><label>Fecha Baja:</label></td>
			<td>
				<?=
					DatePicker::widget([
						'model' => $extras['modelObjeto'],
						'attribute' => 'fchbaja',
						'dateFormat' => 'php:d/m/Y',
						'options' => [
							'class'		=> 'form-control',
							'style' => 'width:80px;text-align: center',
						],
					]);
				?>
			</td>

		</tr>

		<tr>
			<td colspan="5">
				<?=
					Html::activeCheckbox( $extras['modelObjeto'], 'elimobjcondeuda', [
						'label' => 'Eliminar Objeto aún con Deuda o Saldo a favor',
					]);
				?>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<?=
					Html::activeCheckbox( $extras['modelObjeto'], 'elimobjconadhe');
				?>
			</td>
		</tr>
	</table>

</div>

<?php } ?>
<!-- FIN Div Baja -->



<?php
ActiveForm::end();

$tab = $extras['consulta'] == 2 ? 7 : $extras['tab'];
Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false, 'id' => 'pjaxPrincipalForm']);
?>

<div style="width:640px;">
	<table border="0" width="640px">
		<tr>
			<td width="620px">
			<?= Tabs::widget([
				'items' => [
					['label' => 'Titulares', 'content' => $this->render('_titulares', ['extras' => $extras, 'form' => $form, 'tab' => 1, 'consulta' => $extras['consulta'], 'idTabla' => 'titularesRodado']), 'options' => ['class' => 'tabItem'], 'active' => $tab == 1],
					['label' => 'Asignaciones' , 'content' => $this->render('//objeto/objetoasignacioneslist',['modelobjeto' => $extras['modelObjeto'], 'tab' => 4]), 'options' => ['class'=>'tabItem'], 'active' => $tab == 4],
					['label' => 'Acciones' , 'content' => $this->render('//objeto/objetoaccioneslist',['modelobjeto' => $extras['modelObjeto'], 'tab' => 5]), 'options' => ['class'=>'tabItem'], 'active' => $tab == 5],
					['label' => 'Observaciones' ,
					'content' => Html::textarea(null,
												$extras['modelObjeto']->obs,
												['id' => 'rodadoObservacion',
												'class' => 'form-control',
												'maxlength' => 1000,
												'style' => 'width:600px;height:100px; max-width:600px; max-height:150px;',
												'onblur' => 'VariablesBaja()',
												'readonly' => $extras['consulta'] == 1
												]
												),
					'options' => ['class'=>'tabItem'],
					'active' => $tab == 7]
				],
			]);
			?>
			</td>
			<td></td>
		</tr>
	</table>
</div>

<?php
Pjax::end();
?>

<?php
 	if ($extras['consulta'] !== 1){
 ?>
	<div class="form-group" style='margin-top:10px'>
		<?php
			Pjax::begin(['id' => 'btOpciones']);

			if ($extras['consulta']==2){

				echo Html::Button('Eliminar', ['class' => 'btn btn-danger', 'id' => 'btEliminarAcep',
							'data' => [
										'toggle' => 'modal',
        								'target' => '#ModalEmiminar',
   									],]);


				Modal::begin([
        				'id' => 'ModalEmiminar',
        				'size' => 'modal-sm',
        				'header' => '<h4><b>Confirmar Eliminación</b></h4>',
        				'closeButton' => [
            				'label' => '<b>X</b>',
                			'class' => 'btn btn-danger btn-sm pull-right',
                			'id' => 'btCancelarModalElim'
            				],
        			]);

        			echo "<center>";
        			echo "<p><label>¿Esta seguro que desea eliminar ?</label></p><br>";

					echo Html::button('Aceptar', ['class' => 'btn btn-success', 'onclick' => 'formSubmit();']);

        			echo "&nbsp;&nbsp;";
			 		echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btEliminarCanc','onClick' => '$("#ModalEmiminar, .window").modal("toggle");']);
			 		echo "</center>";

			 	Modal::end();

     		 }else {
				echo Html::button('Grabar', ['class' => 'btn btn-success', 'onclick' => 'formSubmit();']);
			 }

			 echo "&nbsp;&nbsp;";
			 echo Html::a('Cancelar', ['view', 'id' => $extras['model']->obj_id], [
            			'class' => 'btn btn-primary',
        			]);
        	Pjax::end();
		?>
	</div>

<?php
}
?>

<?php

	echo $form->errorSummary([$extras['model'], $extras['modelObjeto']], ['id' => 'rodadoFormErrores']);

	if ($extras['consulta']==1 || $extras['consulta']==2){
		echo "<script>";
		echo "DesactivarFormPost('formRodado');";
		echo "</script>";
	}


    if ($extras['consulta']==2){
		$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
		$reemplazar=array("", "", "", "");
		$cadena=str_ireplace($buscar,$reemplazar,$extras['modelObjeto']->obs);
    ?>
    	<script type="text/javascript">
    		$("#objeto-elimobjcondeuda").prop("disabled", false);
			$("#objeto-elimobjconadhe").prop("disabled", false);
			$("#objeto-fchbaja").prop("readOnly", false);
    		$("#objeto-tbaja").prop("disabled", false);
    		$("#rodadoObservacion").prop("readonly", false);
    		$("#rodadoObservacion").prop("disabled", false);
    		$("#rodadoObservacion").val("<?= $cadena ?>");
    		$("#btEliminarAcep").prop("disabled", false);
    		$("#btEliminarCanc").prop("disabled", false);
    		$("#ModalEmiminar").prop("disabled", false);
    		$("#btCancelarModalElim").prop("disabled", false);
    	</script>
    <?php
    }
	?>
<script type="text/javascript">
function VariablesBaja()
{
	$.pjax.reload(
		{
			container:"#btOpciones",
			replace : false,
			push : false,
			data:{
					obs:$("#objeto-obs").val(),
					elimobjcondeuda:$("#objeto-elimobjcondeuda:checked").val(),
					tbaja:$("#objeto-tbaja").val()
				},
			method:"POST"
		})
}

function mostrarErroresFormRodado(e){

	var $errores = $("#rodadoFormErrores"), $ul = $("#rodadoFormErrores ul"), $li;

	$errores.css("display" , "block");
	$ul.empty();

	$(e).each(function(indice, valor){

		$li = $("<li></li>");
		$li.text(valor);
		$li.appendTo($ul);
	});
}

function cambiaTLiq(nuevo){
	//mostrar los controles dependiendo del tipo de liquidacion que se haya elegido
	nuevo = parseInt(nuevo);

	if(!isNaN(nuevo)){

		switch(nuevo){

			case 1:
				$("#filaAforo").removeClass("hidden");
				$("#filaCategoriaPeso").addClass("hidden");
				break;

			case 2:
				$("#filaCategoriaPeso").removeClass("hidden");
				$("#filaAforo").addClass("hidden");
				break;
		}
	}
}

function formSubmit(){
	$("#modelRodadoObservacion").val($("#rodadoObservacion").val());

	//contiene los errores del formulario
	var e = new Array();

	<?php
	if($extras['consulta'] == 0 || $extras['consulta'] == 3){
	?>
	if($("#formRodadoTipoAlta").val() == "") e.push("Elija un tipo de alta");
	if($("#formRodadoAnio").val().trim() === '') e.push("Ingrese año.");
	if($("#formRodadoADesde").val() == "" || $("#formRodadoCDesde").val() == "") e.push("Ingrese el período inicial.");
	if($("#formRodadoDominio").val().trim() === '') e.push("Ingrese dominio.");
	if($("#formRodadoNumeroMotor").val().trim() === '') e.push("Ingrese el Nº de motor.");
	if($(".checkTLiq:checked").length == 0) e.push("Seleccione un tipo de liquidación.");
	if($("#formRodadoUso option:selected").text().trim() === '') e.push("Elija el uso del rodado.");
	if($("#formRodadoDomicilio").val().trim() === '') e.push("Debe ingresar el domicilio postal del rodado.");
	if($("#titularesRodado tbody tr").length === 0) e.push("Ingrese al menos un titular.");
	if($("#formRodadoTipoLiquidacion input:checked").val() == "2" && $("#formRodadoCategoria option:selected").val() == "") e.push("Elija una categoria.");
	<?php
	}
	?>

	if(e.length === 0)
		$("#formRodado").submit();
	else mostrarErroresFormRodado(e);
}

function cambiaAforo(){

	var aforo = $("#formRodadoAforo").val();
	var longitud= aforo.length;
	var valido= longitud == 7 || longitud == 8;


	$("#rodadoFormModeloNombre").val("");
	$("#rodadoFormModeloValor").val("");

	if(valido){

		var origen = aforo.substr(0, 1);
		var marca = aforo.substr(2, 3);
		var tipo = aforo.substr(6, 2);
		var modelo = aforo.substr(9);


		$.pjax.reload({
			container : "#pjaxNombreModeloAforo",
			replace : false,
			push : false,
			type : "GET",
			data : {
				"aforo_id": aforo,
				"cargarModeloAforo" : true,
				"anio" : $("#formRodadoAnio").val()
			}
		});
	} else console.log(longitud);
}

function cambiaMarca(nuevo){

	$.pjax.reload({
		container : "#pjaxBusquedaModelo",
		replace : false,
		push : false,
		type : "GET",
		data : {
			"marcaElegida" : nuevo,
			"categoriaElegida": $("#formRodadoCategoria").val()
		}
	});

	$("#formRodadoMarcaChasis").val(nuevo);
	$("#formRodadoMarcaMotor").val(nuevo);
}

function cambiaAnio(nuevo){
	<?php
	if(in_array($extras['consulta'], [0, 3])){
	?>
	$.pjax.reload({
		container: "#pjaxModalBusquedaAforo",
		type: "GET",
		replace: false,
		push: false,
		data: {
			"anio": nuevo,
			"aforo_id":$("#formRodadoAforo").val()
		}
	});
	<?php
	}
	?>
}

function cambiaCategoria(){

	cambiaMarca($("#formRodadoCategoriaPesoMarca").val());
}

$(document).ready(function(){

	<?php
	if($extras['model']->conductor !== null && trim($extras['model']->conductor) != ''){
	?>
	$("#formRodadoNombreConductor").val("<?= $extras['model']->nombreconductor ?>");
	<?php
	}
	?>
});

</script>
</div>
