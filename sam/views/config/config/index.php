<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\models\config\Config;
use \yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use app\utils\db\Fecha;
use yii\bootstrap\Alert;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Configuración General';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => Yii::$app->param->sis_url.'site/config'];
$this->params['breadcrumbs'][] = $title;

$model = isset($model) ? $model : new Config();
if ($accion==null) $accion = 1;
?>

	<style type="text/css">

		.form {

			margin-top:10px;
			padding-bottom: 8px;

		}

		#configGeneral{ margin-top:10px;}

		#formBotones{ margin-top:20px;}

		p {
			margin-bottom: 0px;
		}

		.subtitulo{
			color:#0055DD;
		}

	</style>

	<div id='configGeneral'>

	<?php

	//-------------------------seccion de mensajes-----------------------
		if(!empty($mensaje) && $mensaje!=''){

			switch ($mensaje)
			{
					case 'update' : $mensaje = 'Datos grabados correctamente.'; break;
					default : $mensaje = '';
			}
		}

		Alert::begin([
			'id' => 'MensajeInfoConfig',
			'options' => [
			'class' => 'alert-success',
			'style' => $mensaje != '' ? 'display:block' : 'display:none'
			],
		]);

		if ($mensaje != '') echo $mensaje;

		Alert::end();

		if ($mensaje != '') echo "<script>window.setTimeout(function() { $('#MensajeInfoConfig').alert('close'); }, 5000)</script>";

	?>

	    <div>
	    	<h1 style="display:inline-block;"><?= $title; ?></h1>
	        <?php
	        	if (utb::getExisteProceso(3054))
	        		echo Html::Button('Modificar', ['id' => 'btnModificar','class' => 'btn btn-primary pull-right','onclick'=>'btnModificarConfigClick()'])
	        ?>
	    </div>



	    <?php

	    	$form = ActiveForm::begin([
	    		'id' => 'config_form',
	    		'action' => ['modificarconfig'],
				'fieldConfig' => ['template' => "{label}{input}"],
			]);

		?>

	    <!-- ------------------------------------------------------------------------------------------------------------------------- -->

		<div id='configAccesorios' class='form'>
			<h4 class="subtitulo"><label>Accesorios</label></h4>

				<table border='0'>
					<tr>
						<td><label>Cuenta de Recargo:</label></td>
						<td width='45px'>
 							<?= $form->field($model, 'ctarecargo',['options'=>['id'=>'ctarecargo']])->textInput([
 									'maxlength' => 3,
 									'style' => 'width:40px',
 									'disabled'=> true,
 									'onchange' => '$.pjax.reload({container:"#cargarCtarecargo",data:{ctaRecargo:this.value},method:"POST"})',
 									'onkeypress' => 'return justNumbers( event )',
 								]);
 							?>
						</td>
						<td>

						<?php
							Modal::begin([
				                'id' => 'BuscarCtaRecargo',
				                'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Cuenta</h2>',
								'toggleButton' => [
									'id' => 'botonBuscarCtaRecargo',
				                    'label' => '<i class="glyphicon glyphicon-search"></i>',
				                    'class' => 'bt-buscar',
				                    'disabled'=> true,

				                ],
				                'closeButton' => [
				                  'label' => '<b>X</b>',
				                  'class' => 'btn btn-danger btn-sm pull-right'
				                ],
				                 ]);

				                ?>

				                <div style="margin: 8px auto 8px auto">
					                <label class="control-label">Filtrar por Nombre:</label>
									<?= Html::input('text', 'filtraCuentaIngreso', '', [
											'class' => 'form-control',
											'id'=>'filtraCuentaIngreso',
											'style'=>'width:270px;text-transform: uppercase',
											'maxlength'=>'250',
											'onkeypress'=>'f_filtraCuentaIngreso()',
										]);
									?>
								</div>

				                <?php
				                Pjax::begin([ 'id' => 'PjaxCuentaRecargo', 'enableReplaceState' => false, 'enablePushState' => false ]);

				                	$filtro = Yii::$app->request->get( 'filtro', '' );

					                $sql = 'Select c.cta_id, c.nombre, p.formatoaux from cuenta c inner join fin.part p on c.part_id = p.part_id where c.tcta = 3';

									if ( $filtro != '' )
									{
										$sql .= " AND upper(c.nombre) LIKE upper('%" . $filtro . "%')";
									}

									$data = Yii::$app->db->createCommand( $sql )->queryAll();

									$dataProvider = new ArrayDataProvider([ 'allModels' => $data ]);

									echo GridView::widget([
										'id' => 'GrillaCuentaRecargo',
										'dataProvider' => $dataProvider,
										//'headerRowOptions' => ['class' => 'grilla'],
								        'rowOptions' => function ($model,$key,$index,$grid)
								        				{
								        					return
								        					[
																'ondblclick'=>'dobleClickCuentaRecargo("'.$model['cta_id'].'","'.$model['nombre'].'");',
																'onclick'=>'clickCuentaRecargo("'.$model['cta_id'].'","'.$model['nombre'].'");',
															];
								        				},
										'columns' => [

											['attribute'=>'cta_id','header' => 'Cuenta'],
											['attribute'=>'nombre','header' => 'Nombre'],
											['attribute'=>'formatoaux','header' => 'Formato'],
										],

								    ]);

								Pjax::end();
								?>

								<script type="text/javascript">

									function f_filtraCuentaIngreso()
									{
										var filtro = $("#filtraCuentaIngreso").val();

										$.pjax.reload({
											container: "#PjaxCuentaRecargo",
											type: "GET",
											replace: false,
											push: false,
											data: {

												filtro: filtro,
											},
										});

										$("#PjaxCuentaRecargo").on("pjax:end", function() {

											$("#filtraCuentaIngreso").focus();

											$("#PjaxCuentaRecargo").off("pjax:end");
										});
									}

									function dobleClickCuentaRecargo(cod,nombre)
									{
										$("#config-ctarecargo").val(cod);
										$("#nombreCtaRecargo").val(nombre);
										$("#botonBuscarCtaRecargo").click();
									}

									function clickCuentaRecargo(cod,nombre)
									{
										$("#config-ctarecargo").val(cod);
										$("#nombreCtaRecargo").val(nombre);
									}
								</script>

							<?php

							Modal::end();

							?>
						</td>
						<td>
							<?= Html::input('text', 'nombreCtaRecargo','',[
									'id' => 'nombreCtaRecargo',
									'class' => 'form-control solo-lectura',
								 	'style' => 'width:250px;background-color:#E6E6FA;',
								 	'tabindex' => '-1',
								]);
							?>
						</td>
					</tr>
					<tr>
						<td><label>Monto Mínimo:</label></td>
						<td>
							<?= $form->field($model, 'interes_min',['options'=>['id'=>'interes_min']])->textInput([
									'maxlength' => 3,
									'style' => 'width:40px;margin-top:6px;',
									'disabled'=> true,
									'onkeypress' => 'return justNumbers( event )',
								]);
							?>
						</td>
					</tr>
				</table>

			</div>

			<!----------------------------------------------------------------------------------------------------------------------------->


			<div id='configRedondeo' class='form'>
				<h4 class="subtitulo"><label>Redondeo</label></h4>

				<table border='0'>
					<tr>
						<td><label>Cuenta Redondeo:</label></td>
						<td width='45px'>
						    <?= $form->field($model, 'ctaredondeo',['options' => ['id' => 'ctaredondeo' ]])->textInput([
						    		'maxlength' => 3,
						    		'style' => 'width:40px',
						    		'disabled'=> true,
						    		'onchange' => '$.pjax.reload({container:"#cargarCtaredondeo",data:{ctaRedondeo:this.value},method:"POST"})',
						    		'onkeypress' => 'return justNumbers( event )',
						    	]);
						    ?>
						</td>
						<td>
						<?php
							Modal::begin([
				                'id' => 'BuscarCtaRedondeo',
				                'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Cuenta</h2>',
								'toggleButton' => [
									'id' => 'botonBuscarCtaRedondeo',
				                    'label' => '<i class="glyphicon glyphicon-search"></i>',
				                    'class' => 'bt-buscar',
				                    'disabled'=> true,

				                ],
				                'closeButton' => [
				                  'label' => '<b>X</b>',
				                  'class' => 'btn btn-danger btn-sm pull-right'
				                ],
				                 ]);

				            	echo $this->render('//taux/auxbusca', [	'tabla' => 'cuenta',
					            										'campocod'=>'cta_id',
					            										'camponombre' => 'nombre',
					            										'boton_id'=>'botonBuscarCtaRedondeo',
					            										'idcampocod'=>'config-ctaredondeo',
					            										'idcamponombre'=>'nombreCtaRedondeo',
					            										'criterio' => 'tcta=1'
					            ]);
							Modal::end();
            			?>
						</td>
						<td>
							<?=
								Html::input('text', 'nombreCtaRedondeo','',[
									'id' => 'nombreCtaRedondeo',
									'class' => 'form-control solo-lectura',
								 	'style' => 'width:250px;background-color:#E6E6FA;',
								 	'tabindex' => '-1',
								]);
							?>
						</td>
					</tr>
					<tr>
						<td><label>Porcentaje:</label></td>
						<td colspan="2">
							<?php
							$tipo =[0 => '0'] + [1 => '0.1'] + [2 => '0.25'] + [3=> '0.5'] + [4 => '1'];
							echo $form->field($model, 'porcredondeo')->dropDownList($tipo, ['style' => 'width:60px;margin-top:4px;', 'disabled'=> true]);

							foreach($tipo as $clave=>$valor){
								if($model->porcredondeo==$valor){
								 echo "<script>
									$('#config-porcredondeo > option[value=".$clave."]').attr('selected', 'selected');
									</script>";
								}

							}
							?>
						</td>
					</tr>
				</table>

			</div>

		<!----------------------------------------------------------------------------------------------------------------------------->
		
		<!-- ------------------------------------------------------------------------------------------------------------------------- -->

		<div id='configActualizacionDeuda' class='form'>
			<h4 class="subtitulo"><label>Actualización Deuda</label></h4>

				<table border='0'>
					<tr>
						<td><label>Cuenta:</label></td>
						<td width='45px'>
 							<?= $form->field($model, 'cta_id_act')->textInput([
 									'maxlength' => 3,
									'id'=>'cta_id_act',
 									'style' => 'width:40px',
 									'disabled'=> true,
 									'onchange' => '$.pjax.reload({container:"#cargarCtaAct",data:{ctaAct:this.value},method:"POST"})',
 									'onkeypress' => 'return justNumbers( event )',
 								]);
 							?>
						</td>
						<td>

						<?php
							Modal::begin([
				                'id' => 'BuscarCtaAct',
				                'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Cuenta</h2>',
								'toggleButton' => [
									'id' => 'botonBuscarCtaAct',
				                    'label' => '<i class="glyphicon glyphicon-search"></i>',
				                    'class' => 'bt-buscar',
				                    'disabled'=> true,

				                ],
				                'closeButton' => [
				                  'label' => '<b>X</b>',
				                  'class' => 'btn btn-danger btn-sm pull-right'
				                ],
				                 ]);

				                ?>

				                <div style="margin: 8px auto 8px auto">
					                <label class="control-label">Filtrar por Nombre:</label>
									<?= Html::input('text', 'filtraCuentaAct', '', [
											'class' => 'form-control',
											'id'=>'filtraCuentaAct',
											'style'=>'width:270px;text-transform: uppercase',
											'maxlength'=>'250',
											'onkeypress'=>'f_filtraCuentaAct()',
										]);
									?>
								</div>

				                <?php
				                Pjax::begin([ 'id' => 'PjaxCuentaAct', 'enableReplaceState' => false, 'enablePushState' => false ]);

				                	$filtro = Yii::$app->request->get( 'filtro', '' );

					                $sql = 'Select c.cta_id, c.nombre, p.formatoaux from cuenta c inner join fin.part p on c.part_id = p.part_id where c.tcta = 3';

									if ( $filtro != '' )
									{
										$sql .= " AND upper(c.nombre) LIKE upper('%" . $filtro . "%')";
									}

									$data = Yii::$app->db->createCommand( $sql )->queryAll();

									$dataProvider = new ArrayDataProvider([ 'allModels' => $data ]);

									echo GridView::widget([
										'id' => 'GrillaCuentaAct',
										'dataProvider' => $dataProvider,
										//'headerRowOptions' => ['class' => 'grilla'],
								        'rowOptions' => function ($model,$key,$index,$grid)
								        				{
								        					return
								        					[
																'ondblclick'=>'dobleClickCuentaAct("'.$model['cta_id'].'","'.$model['nombre'].'");',
																'onclick'=>'clickCuentaAct("'.$model['cta_id'].'","'.$model['nombre'].'");',
															];
								        				},
										'columns' => [

											['attribute'=>'cta_id','header' => 'Cuenta'],
											['attribute'=>'nombre','header' => 'Nombre'],
											['attribute'=>'formatoaux','header' => 'Formato'],
										],

								    ]);

								Pjax::end();
								?>

								<script type="text/javascript">

									function f_filtraCuentaIngreso()
									{
										var filtro = $("#filtraCuentaAct").val();

										$.pjax.reload({
											container: "#PjaxCuentaAct",
											type: "GET",
											replace: false,
											push: false,
											data: {

												filtro: filtro,
											},
										});

										$("#PjaxCuentaAct").on("pjax:end", function() {

											$("#filtraCuentaAct").focus();

											$("#PjaxCuentaAct").off("pjax:end");
										});
									}

									function dobleClickCuentaAct(cod,nombre)
									{
										$("#cta_id_act").val(cod);
										$("#nombreCtaAct").val(nombre);
										$("#botonBuscarCtaAct").click();
									}

									function clickCuentaAct(cod,nombre)
									{
										$("#cta_id_act").val(cod);
										$("#nombreCtaAct").val(nombre);
									}
								</script>

							<?php

							Modal::end();

							?>
						</td>
						<td>
							<?= Html::input('text', 'nombreCtaAct','',[
									'id' => 'nombreCtaAct',
									'class' => 'form-control solo-lectura',
								 	'style' => 'width:250px;background-color:#E6E6FA;',
								 	'tabindex' => '-1',
								]);
							?>
						</td>
					</tr>
				</table>

			</div>

			<!----------------------------------------------------------------------------------------------------------------------------->

			<div id='configCaja' class='form'>
				<h4 class="subtitulo"><label>Caja</label></h4>

				<table border='0'>
					<tr>
						<td><label>Ítem para el Cobro:</label></td>
						<td>
							<?php
								$tipo = utb::getAux('item','item_id','nombre',0,'trib_id=6');
								echo $form->field($model, 'itemcobro')->dropDownList($tipo, ['id' => 'itemcobro', 'style' => 'width:150px;', 'disabled'=> true]);
							?>
						</td>
					</tr>
					<tr>
						<td><label>Ítem Comisión:</label></td>
						<td>
							<?php
								$tipo = utb::getAux('item','item_id','nombre',0,'trib_id=6');
								echo $form->field($model, 'itemcomision')->dropDownList($tipo, ['id' => 'itemcomision', 'style' => 'width:150px;', 'disabled'=> true]);
							?>
						</td>
					</tr>
					<tr>
						<td><label>Ítem Comisión Banco:</label></td>
						<td>
							<?php
								$tipo = utb::getAux('item','item_id','nombre',0,'trib_id=6');
								echo $form->field($model, 'itemcomisionbco')->dropDownList($tipo, ['id' => 'itemcomisionbco', 'style' => 'width:150px;', 'disabled'=> true]);
							?>
						</td>
					</tr>
				</table>

				<table border='0'>
					<tr>
						<td><label>Cuenta Diferencia Caja Externa:</label></td>
						<td width="45px">
							<?= $form->field($model, 'ctadiferencia',['options'=>['id'=>'ctadiferencia']])->textInput(['maxlength' => 3,'style' => 'width:40px', 'disabled'=> true,'onchange' => '$.pjax.reload({container:"#cargarCtadiferencia",data:{ctaDiferencia:this.value},method:"POST"})']); ?>
						</td>
						<td>
						<?php
							Modal::begin([
				                'id' => 'BuscarCtadiferencia',
				                'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Cuenta</h2>',
								'toggleButton' => [
									'id' => 'botonBuscarCtaDiferencia',
				                    'label' => '<i class="glyphicon glyphicon-search"></i>',
				                    'class' => 'bt-buscar',
				                    'disabled'=> true,

				                ],
				                'closeButton' => [
				                  'label' => '<b>X</b>',
				                  'class' => 'btn btn-danger btn-sm pull-right'
				                ],
				                 ]);

				            	echo $this->render('//taux/auxbusca', [	'tabla' => 'cuenta',
					            										'campocod'=>'cta_id',
					            										'camponombre' => 'nombre',
					            										'boton_id'=>'botonBuscarCtaDiferencia',
					            										'idcampocod'=>'config-ctadiferencia',
					            										'idcamponombre'=>'nombreCtadiferencia'
				            											]);
							Modal::end();
            			?>
						</td>
						<td>
							<?=
								Html::input('text', 'nombreCtadiferencia','',[
								 	'id' => 'nombreCtadiferencia',
								 	'class' => 'form-control solo-lectura',
								 	'style' => 'width:250px;background-color:#E6E6FA;',
								 	'tabindex' => '-1',
								 ]);
							?>
						</td>
					</tr>
				</table>

				<table border='0' style="margin-top: 8px">
					<tr>
						<td rowspan="3" valign="top" width="140px"><label>Verificación de Débito:</label></td>
						<td width="20px" valign="top">
							<?= Html::activeRadio($model, 'cajaverifdebito',['label' => '', 'value' => '0', 'uncheck' => null, 'disabled' => true, 'id' => 'cajaverifdebito']) ?>
						</td>
						<td>
							<p>No Controlar</p>
						</td>
					</tr>
				 	<tr>
						<td>
							<?= Html::activeRadio($model, 'cajaverifdebito',['label' => '', 'value' => '1', 'uncheck' => null, 'disabled' => true, 'id' => 'cajaverifdebito2']) ?>
						</td>
						<td>
							<p>Controlar e Informar</p>
						</td>
					</tr>
					<tr>
						<td>
							<?= Html::activeRadio($model, 'cajaverifdebito',['label' => '', 'value' => '2', 'uncheck' => null, 'disabled' => true, 'id' => 'cajaverifdebito3']) ?>
						</td>
						<td>
							<p>Controlar y Bloquear Cobro</p>
						</td>
					</tr>
				</table>
				
				<table border='0'>
					<tr>
						<td><label>Cuenta Recargo T.C. :</label></td>
						<td width='45px'>
						    <?= $form->field($model, 'ctarectc',['options' => ['id' => 'ctarectc' ]])->textInput([
						    		'maxlength' => 3,
						    		'style' => 'width:40px',
						    		'disabled'=> true,
						    		'onchange' => '$.pjax.reload({container:"#cargarCtarectc",data:{ctaRecTC:this.value},method:"POST"})',
						    		'onkeypress' => 'return justNumbers( event )',
						    	]);
						    ?>
						</td>
						<td>
						<?php
							Modal::begin([
				                'id' => 'BuscarCtaRecTC',
				                'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Cuenta</h2>',
								'toggleButton' => [
									'id' => 'botonBuscarCtaRecTC',
				                    'label' => '<i class="glyphicon glyphicon-search"></i>',
				                    'class' => 'bt-buscar',
				                    'disabled'=> true,

				                ],
				                'closeButton' => [
				                  'label' => '<b>X</b>',
				                  'class' => 'btn btn-danger btn-sm pull-right'
				                ],
				                 ]);

				            	echo $this->render('//taux/auxbusca', [	'tabla' => 'cuenta',
					            										'campocod'=>'cta_id',
					            										'camponombre' => 'nombre',
					            										'boton_id'=>'botonBuscarCtaRecTC',
					            										'idcampocod'=>'config-ctarectc',
					            										'idcamponombre'=>'nombreCtaRecTC',
					            										'criterio' => 'tcta=3'
					            ]);
							Modal::end();
            			?>
						</td>
						<td>
							<?=
								Html::input('text', 'nombreCtaRecTC','',[
									'id' => 'nombreCtaRecTC',
									'class' => 'form-control solo-lectura',
								 	'style' => 'width:250px;background-color:#E6E6FA;',
								 	'tabindex' => '-1',
								]);
							?>
						</td>
					</tr>
				</table>

			</div>
	<!-- -------------------------------------------------------------------------------------------------------------------------->
			<div id='configCuentaCorriente' class='form'>
				<h4 class="subtitulo"><label>Cuenta Corriente</label></h4>

				<table border='0'>
					<tr>
						<td width="70px"><label>Año desde:</label></td>
						<td>
							<?= $form->field($model, 'ctacte_anio_desde',['options'=>['id'=>'ctacte_anio_desde']])->textInput([
									'maxlength' => 2,
									'style' => 'width:45px',
									'disabled'=> true,
									'onkeypress' => 'return justNumbers( event )',
								]);
							?>
						</td>
					</tr>

					<tr>
						<td><label>Texto UCM:</label></td>
						<td>
							<?= $form->field($model, 'texto_ucm',['options'=>['id'=>'texto_ucm']])->textInput([
									'maxlength' => 10,
									'style' => 'width:45px;',
									'disabled'=> true,
								]);
							?>
						</td>
						<td width="15px"></td>
						<td  width="45px"><label>UCM1:</label></td>
						<td>
							<?= $form->field($model, 'ucm1',['options'=>['id'=>'ucm1']])->textInput([
									'maxlength' => 9,
									'style' => 'width:70px',
									'onkeypress' => 'return justDecimal( $(this).val(), event )',
									'disabled' => true,
								]);
							?>
						</td>
						<td width="15px"></td>
						<td  width="45px"><label>UCM2:</label></td>
						<td>
							<?= $form->field($model, 'ucm2',['options'=>['id'=>'ucm2']])->textInput([
									'class' => 'form-control',
									'maxlength' => 9,
									'style' => 'width:70px',
									'onkeypress' => 'return justDecimal( $(this).val(), event )',
									'disabled'=> true,
								]);
							?>
						</td>
					</tr>
				</table>

				<table border='0' style="margin-top: 8px">
					<tr>
						<td width="20px">
							<?= $form->field($model, 'per_plan_decaido')->checkbox(['check'=> 1, 'uncheck' => 0, 'disabled'=> true]) ?>
						</td>
						<td valign="center">
							<p style="margin-bottom: 0px">Se incluyen períodos de planes decaídos en el alta de planes.</p>
						</td>
					</tr>
				</table>

				<table border='0'>
					<tr>
						<td rowspan="3" valign="top" width="185px"><label>Generación de DDJJ Faltantes:</label></td>
						<td width="20px" valign="top">
							<input type='radio' id='djfaltantes' name='djfaltantes' value='0' disabled='disabled'>
						</td>
						<td>
							<p>Nominal = 0 (Cero)</p>
						</td>
					 </tr>
					 <tr>
						<td>
							<input type='radio' id='djfaltantes2' name='djfaltantes' value='1' disabled='disabled'>
						</td>
						<td>
							<p>Con liquidación de Multa.</p>
						</td>
					</tr>
					<tr>
						<td>
							<input type='radio' id='djfaltantes3' name='djfaltantes' value='2' disabled='disabled'>
						</td>
						<td>
							<p>Con liquidación de Multa y Mínimo.</p>
						</td>
					</tr>
				</table>

			</div>

			<!-- ------------------------------------------------------------------------------------------------------------------------ -->

			<div id='configLibreDeDeuda' class='form'>
				<h4 class="subtitulo"><label>Libre Deuda</label></h4>

				<table border='0'>
					<tr>
						<td width="145px"><label>Título si existe deuda:</label></td>
						<td>
							<?=
								$form->field($model, 'titulo_libredeuda',['options'=>['id'=>'titulo_libredeuda']])->textInput([
									'maxlength' => 100,
									'style' => 'width:530px;',
									'disabled'=> true,
								]);
							?>
						</td>
					</tr>
					<tr>
						<td><label>Título si no existe deuda:</label></td>
						<td>
							<?=
								$form->field($model, 'titulo2_libredeuda',['options'=>['id'=>'titulo2_libredeuda']])->textInput([
									'maxlength' => 100,
									'style' => 'width:530px;',
									'disabled'=> true,
								]);
							?>
						</td>
					</tr>
					<tr>
						<td><label>Mensaje:</label></td>
						<td>
							<?=
								$form->field($model, 'mensaje_libredeuda',['options'=>['id'=>'mensaje_libredeuda']])->textArea([
									'rows'=>5,
									'style'=>'width:530px;max-width:530px;max-height:75px;',
									'maxlength' => 500,
									'disabled'=> true,
								]);
							?>
						</td>
					</tr>
					</table>
					<table border='0'>
					<tr>
						<td width="20px">
							<?= $form->field($model, 'proxvenc_libredeuda')->checkbox(['check'=> 1, 'uncheck' => 0, 'disabled'=> true]) ?>
						</td>
						<td><p>Incluir próximos vencimientos.</p></td>
					</tr>
				</table>

			</div>

		<!-- --------------------------------------------------------------------------------------------------------------------------->

			<div id='configAtencionDeReclamos' class='form'>
				<h4 class="subtitulo"><label>Atención de Reclamos</label></h4>

				<table border='0'>
					<tr>
						<td><label>Reporte de Reclamo:</label></td>
					</tr>
				</table>

				<table border='0'>
					<tr>
						<td style="padding-left: 15px">
							<p>Cantidad de Copias:</p>
						</td>
						<td><input id='copias_recl' type='number' class='form-control' name='copias_recl' min='1' max='3' disabled='true'></td>
					</tr>
				</table>

				<table border='0'>
					<tr>
						<td width="20px">
							<?= $form->field($model, 'calle_recl')->checkbox(['check'=> 0, 'uncheck' => 0, 'disabled'=> true]); ?>
						</td>
						<td>
							<p>Validar ingreso de domicilio</p>
						</td>
					</tr>
				</table>
				<table border='0'>
					<tr>
						<td><label>Path de Reclamos:</label></td>
						<td>
							<?= $form->field($model, 'path_recl',['options'=>['id'=>'path_recl']])->textInput(['maxlength' => 50,'style' => 'width:300px;', 'disabled'=> true]); ?>
						</td>
					</tr>
				</table>

			</div>

		<!-- ------------------------------------------------------------------------------------------------------------------------>

			<div id='configObrasParticulares' class='form'>
				<h4 class="subtitulo"><label>Obras Particulares</label></h4>

				<table border='0'>
					<tr>
						<td width="135px">
							<label>Tributo Matrícula Prof.:</label>
						</td>
						<td>
							<?php
								$tipo = utb::getAux('trib','trib_id','nombre',1,'(tipo=3 or tipo=4) and trib_id > 20');
								echo $form->field($model, 'trib_op_matric')->dropDownList($tipo, ['id' => 'trib_op_matric', 'style' => 'width:100px;', 'disabled'=> true]);
							?>
						</td>
					</tr>
				</table>

				<table border='0'>
					<tr>
						<td width="20px">
							<?= $form->field($model, 'op_hab_plazas')->checkbox(['check'=> 1, 'uncheck' => 0,'disabled'=> true, 'disabled'=> true]) ?>
						</td>
						<td>
							<div>Se solicitan cantidad de habitaciones y plazas en las mejoras.</div>
						</td>
					</tr>
				</table>

			</div>

		<!-- -------------------------------------------------------------------------------------------------------------------------- -->

			<div id='configInmuebles' class='form'>
				<h4 class="subtitulo"><label>Inmuebles</label></h4>

				<table border='0'>
					<tr>
						<td width="20px">
							<?= $form->field($model, 'inm_valida_nc')->checkbox(['check'=> true, 'uncheck' => false, 'disabled'=> true]) ?>
						</td>
						<td>
						 	<p>Validar Nomenclatura.</p>
						</td>
					</tr>
					<tr>
						<td>
							<?= $form->field($model, 'inm_valida_frente')->checkbox(['check'=> true, 'uncheck' => false, 'disabled'=> true]) ?>
						</td>
						<td>
							<p>Validar Ingreso de Frentes.</p>
						</td>
					</tr>
					<tr>
						<td>
							<?= $form->field($model, 'inm_gen_osm')->checkbox(['check'=> true, 'uncheck' => false, 'disabled'=> true]) ?>
						</td>
						<td>
							<p>Generar Cuenta OSM.</p>
						</td>
					</tr>
					<tr>
						<td>
							<?= $form->field($model, 'usar_codcalle_loc')->checkbox(['check'=> true, 'uncheck' => false, 'disabled'=> true]) ?>
						</td>
						<td>
							<p>Codificar Calles de la Localidad.</p>
						</td>
					</tr>
					<tr>
						<td>
							<?= $form->field($model, 'inm_phmadre')->checkbox(['check'=> true, 'uncheck' => false, 'disabled'=> true]) ?>
						</td>
						<td>
							<p>PH Madre.</p>
						</td>
					</tr>
				</table>

			</div>

			<!-- -------------------------------------------------------------------------------------------------------------------------- -->

			<div id='configComercios' class='form'>
				<h4 class="subtitulo"><label>Comercios</label></h4>

				<table border='0'>
					<tr>
						<td width="20px">
							<?= $form->field($model, 'com_validar_ib')->checkbox(['check'=> true, 'uncheck' => false, 'disabled'=> true]) ?>
						</td>
						<td>
							<p>Validar inscripción a IB de Titular Principal.</p>
						</td>
					</tr>

				</table>

			</div>

			<!-- -------------------------------------------------------------------------------------------------------------------------- -->

			<div id='configApremiosJudiciales' class='form'>
				<h4 class="subtitulo"><label>Apremios Judiciales</label></h4>

				<table border='0'>
					<tr>
						<td width="160px">
							<label>Ítem Gastos Judiciales:</label>
						</td>
						<td>
							<?php
								$tipo = utb::getAux('item','item_id','nombre',0,'trib_id = 5');
								echo $form->field($model, 'judi_item_gasto')->dropDownList($tipo,['id' => 'judi_item_gasto', 'style' => 'width:200px;', 'disabled'=> true]);
							?>
						</td>
					</tr>
					<tr>
						<td>
							<label>Ítem Honorarios Judiciales:</label>
						</td>
						<td>
							<?php
								$tipo = utb::getAux('item','item_id','nombre',0,'trib_id = 5');
								echo $form->field($model, 'judi_item_hono')->dropDownList($tipo,['id' => 'judi_item_hono', 'style' => 'width:200px;', 'disabled'=> true]);
							?>
						</td>
					</tr>
				</table>

			</div>

		<!-- -------------------------------------------------------------------------------------------------------------------------- -->

			<div id='configReporte' class='form'>
				<h4 class="subtitulo"><label>Reportes</label></h4>

				<table border='0'>
					<tr>
						<td width="20px">
							<?= $form->field($model, 'repo_usu_nom')->checkbox(['check'=> 1, 'uncheck' => 0, 'disabled'=> true]) ?>
						</td>
						<td>
							<p>Incluir nombre del Usuario a los Reportes.</p>
						</td>
					</tr>
				</table>

			</div>

		<!-- -------------------------------------------------------------------------------------------------------------------------- -->

			<div id='configComercio' class='form'>
				<h4 class="subtitulo"><label>Comercio</label></h4>

				<table border='0'>
					<tr>
						<td><label>Cantidad de meses de duración de habilitación:</label></td>
					</tr>
					<tr>
						<td style="padding-left: 15px" width="325px">
							<p>Si se establece 0(cero), indica que no tiene vencimiento:</p>
						</td>
						<td>
							<?=
								$form->field($model, 'comer_hab_vence')->textInput([
									'maxlength' => 3,
									'style' => 'width:40px;',
									'disabled'=> true,
									'onkeypress' => 'return justNumbers( event )',
								]);
							?>
						</td>
					</tr>
				</table>

			</div>


			<!-- -------------------------------------------------------------------------------------------------------------------------- -->

			<div id='configJuzgadoDeFaltas' class='form'>
				<h4 class="subtitulo"><label>Juzgado de Faltas</label></h4>

				<table border='0'>
					<tr>
						<td width="125px"><label>Oficina de Transito 1:</label></td>
						<td>
							<?php
								$tipo = utb::getAux('sam.muni_oficina','ofi_id','nombre', 1);
								echo $form->field($model, 'juz_origentransito1')->dropDownList($tipo,['style' => 'width:200px;', 'disabled'=> true]);
							?>
						</td>
					</tr>
					<tr>
						<td><label>Oficina de Transito 2:</label></td>
						<td>
							<?php
								$tipo = utb::getAux('sam.muni_oficina','ofi_id','nombre',1);
								echo $form->field($model, 'juz_origentransito2')->dropDownList($tipo,['style' => 'width:200px;', 'disabled'=> true]);
							?>
						</td>
					</tr>
				</table>

			</div>

			<!-- -------------------------------------------------------------------------------------------------------------------------- -->

			<div id='configPersona' class='form'>
				<h4 class="subtitulo"><label>Persona</label></h4>

				<table border='0'>
					<tr>
						<td>
							<?=
								$form->field($model, 'per_pedir_cuit')->checkbox([
									'check'=> true,
									'uncheck' => false,
									'disabled'=> true,
									'label'		=> 'Requerir CUIT',
								]);
							?>
						</td>
					</tr>
					<tr>
						<td>
							<?=
								$form->field($model, 'per_pedir_doc')->checkbox([
									'check'=> true,
									'uncheck' => false,
									'disabled'=> true,
									'label'		=> 'Requerir documento',
								]);
							?>
						</td>
					</tr>
				</table>

			</div>


			<!-- ------------------------------------------------------------------------------------------------------------------------- -->

			<div id='configIB' class='form'>
				<h4 class="subtitulo"><label>Configuración de Ingresos Brutos</label></h4>

				<table border='0'>
					<tr>
						<td ><label>Modo ingreso Nº Ingresos Brutos:</label></td>
						<td>
							<?php
								$modosIB = [
									'A'	=> 'Automático',
									'C'	=> 'CUIT',
									'M'	=> 'Manual',
								];

								echo $form->field($model, 'ib_modo')->dropDownList( $modosIB, [
									'style' => 'width:200px;',
									'disabled'=> true,
								]);
							?>
						</td>
					</tr>
				</table>

			</div>
			
			<!-- ------------------------------------------------------------------------------------------------------------------------- -->

			<div id='configRETE' class='form'>
				<h4 class="subtitulo"><label>Configuración de Agente de Retención</label></h4>

				<table border='0'>
					<tr>
						<td ><label>Path Agente de Retención:</label></td>
						<td>
							<?= $form->field($model, 'agrete_path',['options'=>['id'=>'agrete_path']])->textInput(['maxlength' => 100,'style' => 'width:600px;', 'disabled'=> true]); ?>
						</td>
					</tr>
					<tr>
						<td colspan='2'>
							<?=
								$form->field($model, 'ret_sin_aprob')->checkbox([
									'check'=> true,
									'uncheck' => false,
									'disabled'=> true,
									'label'		=> 'Permitir Retención sin Aprobar',
								]);
							?>
						</td>
					</tr>
				</table>

			</div>
			
			<!-- ------------------------------------------------------------------------------------------------------------------------- -->

			<div id='configRETE' class='form'>
				<h4 class="subtitulo"><label>Configuración de Boletas por Mail</label></h4>

				<table border='0'>
					<tr>
						<td><label>Path Archivo:</label></td>
						<td>
							<?= $form->field($model, 'bol_path',['options'=>['id'=>'bol_path']])->textInput(['maxlength' => 50,'style' => 'width:300px;', 'disabled'=> true]); ?>
						</td>
					</tr>
					<tr>
						<td><label>Mail:</label></td>
						<td>
							<?= $form->field($model, 'bol_mail',['options'=>['id'=>'bol_mail']])->textInput(['maxlength' => 50,'style' => 'width:300px;', 'disabled'=> true]); ?>
						</td>
					</tr>
					<tr>
						<td><label>Clave:</label></td>
						<td>
							<?= $form->field($model, 'bol_mail_clave',['options'=>['id'=>'bol_mail_clave']])->textInput(['maxlength' => 50,'style' => 'width:300px;', 'disabled'=> true]); ?>
						</td>
					</tr>
					<tr>
						<td><label>HOST:</label></td>
						<td>
							<?= $form->field($model, 'bol_mail_host',['options'=>['id'=>'bol_mail_host']])->textInput(['maxlength' => 50,'style' => 'width:300px;', 'disabled'=> true]); ?>
						</td>
					</tr>
					<tr>
						<td><label>Puerto:</label></td>
						<td>
							<?= $form->field($model, 'bol_mail_port',['options'=>['id'=>'bol_mail_port']])->textInput(['maxlength' => 4,'style' => 'width:60px;', 'disabled'=> true]); ?>
						</td>
					</tr>
				</table>

			</div>

			<!-- -------------------------------------------------------------------------------------------------------------------------- -->

			<div id='formBotones' class='form-group' style='display:none'>

				<?php if (utb::getExisteProceso(3054)) echo Html::submitButton('Aceptar', ['class' => 'btn btn-success']) ?>

				<?php echo Html::Button('Cancelar', ['id'=>'btnCancelar', 'class' => 'btn btn-primary','onclick'=>'btnCancelarConfigClick()']) ?>

			</div>

		<?php

		echo $form->errorSummary($model);

		ActiveForm::end(); ?>

	</div>



	<?php

	if($accion==1){
		$nombreCargo = utb::getCampo('cuenta','cta_id='.$model->ctarecargo,'nombre');

		echo "<script> $('#nombreCtaRecargo').val('".$nombreCargo."');  </script>";
		
		$nombreAct = utb::getCampo('cuenta','cta_id='.$model->cta_id_act,'nombre');

		echo "<script> $('#nombreCtaAct').val('".$nombreAct."');  </script>";

		$nombreRedon = utb::getCampo('cuenta','cta_id='.$model->ctaredondeo,'nombre');

		echo "<script> $('#nombreCtaRedondeo').val('".$nombreRedon."');  </script>";

		$nombreDif = utb::getCampo('cuenta','cta_id='.$model->ctadiferencia,'nombre');

		echo "<script> $('#nombreCtadiferencia').val('".$nombreDif."');  </script>";

		echo "<script> $('#copias_recl').val(".$model->copias_recl."); </script>";

		if($model->djfaltantes==0){
		echo "<script> $('#djfaltantes').attr('checked',true); </script>";
		}else if($model->djfaltantes==1){
		echo "<script> $('#djfaltantes2').attr('checked',true); </script>";
		}else if($model->djfaltantes==2){
		echo "<script> $('#djfaltantes3').attr('checked',true); </script>";
		}

		if($model->cajaverifdebito==0){
		echo "<script> $('#cajaverifdebito').attr('checked',true); </script>";
		}else if($model->cajaverifdebito==1){
		echo "<script> $('#cajaverifdebito2').attr('checked',true); </script>";
		}else if($model->cajaverifdebito==2){
		echo "<script> $('#cajaverifdebito3').attr('checked',true); </script>";
		}
		
		$nombreCargoTC = utb::getCampo('cuenta','cta_id='.$model->ctarectc,'nombre');

		echo "<script> $('#nombreCtaRecTC').val('".$nombreCargoTC."');  </script>";

	}else if($accion==3){

		$nombreCargo = utb::getCampo('cuenta','cta_id='.$model->ctarecargo,'nombre');

		echo "<script> $('#nombreCtaRecargo').val('".$nombreCargo."');  </script>";

		$nombreRedon = utb::getCampo('cuenta','cta_id='.$model->ctaredondeo,'nombre');

		echo "<script> $('#nombreCtaRedondeo').val('".$nombreRedon."');  </script>";
		
		$nombreAct = utb::getCampo('cuenta','cta_id='.$model->cta_id_act,'nombre');

		echo "<script> $('#nombreCtaAct').val('".$nombreAct."');  </script>";

		$nombreDif = utb::getCampo('cuenta','cta_id='.$model->ctadiferencia,'nombre');

		echo "<script> $('#nombreCtadiferencia').val('".$nombreDif."');  </script>";

		echo "<script> $('#copias_recl').val(".$model->copias_recl."); </script>";

		if($model->djfaltantes==0){
		echo "<script> $('#djfaltantes').attr('checked',true); </script>";
		}else if($model->djfaltantes==1){
		echo "<script> $('#djfaltantes2').attr('checked',true); </script>";
		}else if($model->djfaltantes==2){
		echo "<script> $('#djfaltantes3').attr('checked',true); </script>";
		}

		if($model->cajaverifdebito==0){
		echo "<script> $('#cajaverifdebito').attr('checked',true); </script>";
		}else if($model->cajaverifdebito==1){
		echo "<script> $('#cajaverifdebito2').attr('checked',true); </script>";
		}else if($model->cajaverifdebito==2){
		echo "<script> $('#cajaverifdebito3').attr('checked',true); </script>";
		}
		
		$nombreCargoTC = utb::getCampo('cuenta','cta_id='.$model->ctarectc,'nombre');

		echo "<script> $('#nombreCtaRecTC').val('".$nombreCargoTC."');  </script>";
		?>

		<script>
		    	$('#btnModificar').css("opacity", 0.5);
				$('#formBotones').css('display','block');

				$('#config-ctarecargo').prop('disabled',false);
				$('#BuscarCtaRecargo').prop('disabled',false);
				$('#config-interes_min').prop('disabled',false);
				$('#config-ctaredondeo').prop('disabled',false);
				$('#BuscarCtaRedondeo').prop('disabled',false);
				$('#config-porcredondeo').prop('disabled',false);
				$('#itemcobro').attr("disabled",false);
				$('#itemcomision').attr("disabled",false);
				$('#itemcomisionbco').attr("disabled",false);
				$('#config-ctadiferencia').prop('disabled',false);
				$('#BuscarCtadiferencia').prop('disabled',false);
				$('#cajaverifdebito').prop('disabled',false);
				$('#cajaverifdebito2').prop('disabled',false);
				$('#cajaverifdebito3').prop('disabled',false);
				$('#config-ctacte_anio_desde').prop('disabled',false);
				$('#config-texto_ucm').prop('disabled',false);
				$('#config-ucm1').prop('disabled',false);
				$('#config-ucm2').prop('disabled',false);
				$('#config-per_plan_decaido').prop('disabled',false);
				$('#djfaltantes').prop('disabled',false);
				$('#djfaltantes2').prop('disabled',false);
				$('#djfaltantes3').prop('disabled',false);
				$('#config-titulo_libredeuda').prop('disabled',false);
				$('#config-titulo2_libredeuda').prop('disabled',false);
				$('#config-mensaje_libredeuda').prop('disabled',false);
				$('#config-proxvenc_libredeuda').prop('disabled',false);
				$('#copias_recl').prop('disabled',false);
				$('#config-calle_recl').prop('disabled',false);
				$('#config-path_recl').prop('disabled',false);
				$('#trib_op_matric').attr("disabled",false);
				$('#config-op_hab_plazas').prop('disabled',false);
				$('#config-inm_valida_nc').prop('disabled',false);
				$('#config-inm_valida_frente').prop('disabled',false);
				$('#config-inm_gen_osm').prop('disabled',false);
				$('#config-usar_codcalle_loc').prop('disabled',false);
				$('#judi_item_gasto').attr("disabled",false);
				$('#judi_item_hono').attr("disabled",false);
				$('#config-repo_usu_nom').prop('disabled',false);
				$('#config-comer_hab_vence').prop('disabled',false);
				$('#config-juz_origentransito1').prop('disabled',false);
				$('#config-juz_origentransito2').prop('disabled',false);
				$('#config-agrete_path').prop('disabled',false);
		</script>
	<?php }




		//--------------------------seccion de errores------------------------

		if(isset($error) and $error != '') {
		echo '<div id="error-summary" class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';

				}
		?>


		<?php

		Pjax::begin(['id' => 'cargarCtarecargo']);

		if(isset($_POST['ctaRecargo'])){

			 $nombreCargo = utb::getCampo('cuenta','tcta = 3 And cta_id='.$_POST['ctaRecargo'],'nombre');

			 echo "<script> $('#nombreCtaRecargo').val('".$nombreCargo."');  </script>";
		}

		Pjax::end();
		
		Pjax::begin(['id' => 'cargarCtaAct']);

		if(isset($_POST['ctaAct'])){

			 $nombreAct = utb::getCampo('cuenta','tcta = 3 And cta_id='.$_POST['ctaAct'],'nombre');

			 echo "<script> $('#nombreCtaAct').val('".$nombreAct."');  </script>";
		}

		Pjax::end();

		Pjax::begin(['id' => 'cargarCtaredondeo']);

		if(isset($_POST['ctaRedondeo'])){

			 $nombreRedondeo = utb::getCampo('cuenta','tcta = 1 And cta_id='.$_POST['ctaRedondeo'],'nombre');

			 echo "<script> $('#nombreCtaRedondeo').val('".$nombreRedondeo."');  </script>";
		}

		Pjax::end();

		Pjax::begin(['id' => 'cargarCtadiferencia']);

		if(isset($_POST['ctaDiferencia'])){

			 $nombreDiferencia = utb::getCampo('cuenta','cta_id='.$_POST['ctaDiferencia'],'nombre');

			 echo "<script> $('#nombreCtadiferencia').val('".$nombreDiferencia."');  </script>";
		}

		Pjax::end();
		
		Pjax::begin(['id' => 'cargarCtarectc']);

		if(isset($_POST['ctaRecTC'])){

			 $nombreCargoTC = utb::getCampo('cuenta','tcta = 3 And cta_id='.$_POST['ctaRecTC'],'nombre');

			 echo "<script> $('#nombreCtaRecTC').val('".$nombreCargoTC."');  </script>";
		}

		Pjax::end();
		?>

	<script>

		$('.control-label').css('display','none');

		function btnModificarConfigClick()
		{

			$("#config_form input").removeAttr('disabled');
			$("#config_form select").removeAttr('disabled');
			$("#config_form textarea").removeAttr('disabled');
			$("#config_form .bt-buscar").removeAttr('disabled');

			$('#btnModificar').css("pointer-events", "none");
			$('#btnModificar').css("opacity", 0.5);
			$('#formBotones').css('display','block');
//
//			$('#config-ctarecargo').prop('disabled',false);
//			$('#BuscarCtaRecargo').prop('disabled',false);
//			$('#config-interes_min').prop('disabled',false);
//			$('#config-ctaredondeo').prop('disabled',false);
//			$('#BuscarCtaRedondeo').prop('disabled',false);
//			$('#config-porcredondeo').prop('disabled',false);
//			$('#itemcobro').attr("disabled",false);
//			$('#itemcomision').attr("disabled",false);
//			$('#itemcomisionbco').attr("disabled",false);
//			$('#config-ctadiferencia').prop('disabled',false);
//			$('#BuscarCtadiferencia').prop('disabled',false);
//			$('#cajaverifdebito').prop('disabled',false);
//			$('#cajaverifdebito2').prop('disabled',false);
//			$('#cajaverifdebito3').prop('disabled',false);
//			$('#config-ctacte_anio_desde').prop('disabled',false);
//			$('#config-texto_ucm').prop('disabled',false);
//			$('#config-ucm1').prop('disabled',false);
//			$('#config-ucm2').prop('disabled',false);
//			$('#config-per_plan_decaido').prop('disabled',false);
//			$('#djfaltantes').prop('disabled',false);
//			$('#djfaltantes2').prop('disabled',false);
//			$('#djfaltantes3').prop('disabled',false);
//			$('#config-titulo_libredeuda').prop('disabled',false);
//			$('#config-titulo2_libredeuda').prop('disabled',false);
//			$('#config-mensaje_libredeuda').prop('disabled',false);
//			$('#config-proxvenc_libredeuda').prop('disabled',false);
//			$('#copias_recl').prop('disabled',false);
//			$('#config-calle_recl').prop('disabled',false);
//			$('#config-path_recl').prop('disabled',false);
//			$('#trib_op_matric').attr("disabled",false);
//			$('#config-op_hab_plazas').prop('disabled',false);
//			$('#config-inm_valida_nc').prop('disabled',false);
//			$('#config-inm_valida_frente').prop('disabled',false);
//			$('#config-inm_gen_osm').prop('disabled',false);
//			$('#config-usar_codcalle_loc').prop('disabled',false);
//			$('#judi_item_gasto').attr("disabled",false);
//			$('#judi_item_hono').attr("disabled",false);
//			$('#config-repo_usu_nom').prop('disabled',false);
//			$('#config-comer_hab_vence').prop('disabled',false);
//			$('#config-juz_origentransito1').prop('disabled',false);
//			$('#config-juz_origentransito2').prop('disabled',false);
	}

			function btnCancelarConfigClick(){

				event.stopPropagation();
				$('#btnModificar').css("pointer-events", "all");
				$('#btnModificar').css("opacity", 1 );
				$('#formBotones').css('display','none');
				$('.error-summary').css('display','none');

				$('#config-ctarecargo').prop('disabled',true);
				$('#BuscarCtaRecargo').prop('disabled',true);
				$('#config-interes_min').prop('disabled',true);
				$('#cta_id_act').prop('disabled',true);
				$('#botonBuscarCtaAct').prop('disabled',true);
				$('#config-ctaredondeo').prop('disabled',true);
				$('#BuscarCtaRedondeo').prop('disabled',true);
				$('#config-porcredondeo').prop('disabled',true);
				$('#itemcobro').attr("disabled","disabled");
				$('#itemcomision').attr("disabled","disabled");
				$('#itemcomisionbco').attr("disabled","disabled");
				$('#config-ctadiferencia').prop('disabled',true);
				$('#BuscarCtadiferencia').prop('disabled',true);
				$('#cajaverifdebito').prop('disabled',true);
				$('#cajaverifdebito2').prop('disabled',true);
				$('#cajaverifdebito3').prop('disabled',true);
				$('#config-ctacte_anio_desde').prop('disabled',true);
				$('#config-texto_ucm').prop('disabled',true);
				$('#config-ucm1').prop('disabled',true);
				$('#config-ucm2').prop('disabled',true);
				$('#config-per_plan_decaido').prop('disabled',true);
				$('#djfaltantes').prop('disabled',true);
				$('#djfaltantes2').prop('disabled',true);
				$('#djfaltantes3').prop('disabled',true);
				$('#config-titulo_libredeuda').prop('disabled',true);
				$('#config-titulo2_libredeuda').prop('disabled',true);
				$('#config-mensaje_libredeuda').prop('disabled',true);
				$('#config-proxvenc_libredeuda').prop('disabled',true);
				$('#copias_recl').prop('disabled',true);
				$('#config-calle_recl').prop('disabled',true);
				$('#config-path_recl').prop('disabled',true);
				$('#trib_op_matric').attr("disabled","disabled");
				$('#config-op_hab_plazas').prop('disabled',true);
				$('#config-inm_valida_nc').prop('disabled',true);
				$('#config-inm_valida_frente').prop('disabled',true);
				$('#config-inm_gen_osm').prop('disabled',true);
				$('#config-usar_codcalle_loc').prop('disabled',true);
				$('#judi_item_gasto').attr("disabled","disabled");
				$('#judi_item_hono').attr("disabled","disabled");
				$('#config-repo_usu_nom').prop('disabled',true);
				$('#config-comer_hab_vence').prop('disabled',true);
				$('#config-juz_origentransito1').prop('disabled',true);
				$('#config-juz_origentransito2').prop('disabled',true);
				$('#config-ib_modo').prop('disabled',true);
				$('#config-agrete_path').prop('disabled',true);
				$('#config-ctarectc').prop('disabled',true);
				$('#botonBuscarCtaRecTC').prop('disabled',true);
				$('#botonBuscarCtaRecargo').prop('disabled',true);
				$('#botonBuscarCtaRedondeo').prop('disabled',true);
				$('#config-com_validar_ib').prop('disabled',true);
				$('#config-per_pedir_cuit').prop('disabled',true);
				$('#config-per_pedir_doc').prop('disabled',true);
				$('#config-bol_path').prop('disabled',true);
				$('#config-bol_mail').prop('disabled',true);
				$('#config-bol_mail_clave').prop('disabled',true);
				$('#config-bol_mail_host').prop('disabled',true);
				$('#config-bol_mail_port').prop('disabled',true);

				$('#ctarecargo').removeClass('has-error');
				$('#interes_min').prop('disabled',true);
				$('#ctaredondeo').removeClass('has-error');
				$('#porcredondeo').removeClass('has-error');
				$('#itemcobro').removeClass('has-error');
				$('#itemcomision').removeClass('has-error');
				$('#itemcomisionbco').removeClass('has-error');
				$('#ctadiferencia').removeClass('has-error');
				$('#ctacte_anio_desde').removeClass('has-error');
				$('#texto_ucm').removeClass('has-error');
				$('#ucm1').removeClass('has-error');
				$('#ucm2').removeClass('has-error');
				$('#titulo_libredeuda').removeClass('has-error');
				$('#titulo2_libredeuda').removeClass('has-error');
				$('#mensaje_libredeuda').removeClass('has-error');
				$('#trib_op_matric').removeClass('has-error');
				$('#judi_item_gasto').removeClass('has-error');
				$('#judi_item_hono').removeClass('has-error');
				$('#comer_hab_vence').removeClass('has-error');
				$('#path_recl').removeClass('has-error');
				$('#agrete_path').removeClass('has-error');

				$('#ctarecargo').removeClass('has-success');
				$('#interes_min').removeClass('has-success');
				$('#ctaredondeo').removeClass('has-success');
				$('#porcredondeo').removeClass('has-success');
				$('#itemcobro').removeClass('has-success');
				$('#itemcomision').removeClass('has-success');
				$('#itemcomisionbco').removeClass('has-success');
				$('#ctadiferencia').removeClass('has-success');
				$('#titulo_libredeuda').removeClass('has-success');
				$('#titulo2_libredeuda').removeClass('has-success');
				$('#mensaje_libredeuda').removeClass('has-success');
				$('#trib_op_matric').removeClass('has-success');
				$('#judi_item_gasto').removeClass('has-success');
				$('#judi_item_hono').removeClass('has-success');
				$('#comer_hab_vence').removeClass('has-success');
				$('#path_recl').removeClass('has-success');
				$('#agrete_path').removeClass('has-success');

				$('#ctacte_anio_desde').removeClass('has-success');
				$('#texto_ucm').removeClass('has-success');
				$('#ucm1').removeClass('has-success');
				$('#ucm2').removeClass('has-success');
	}
	</script>
