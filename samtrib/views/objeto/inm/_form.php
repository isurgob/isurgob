<?php
use yii\helpers\Html;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use \yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\bootstrap\Tabs;
use app\models\objeto\Inm;
use yii\jui\DatePicker;


	/**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 */
//
// 	 $arrayLabelsNomen	= ( new Inm() )->arregloLabelsSinMapeo();
//
// 	 $nomenclatura = [
// 	 	'tipo' => 'campos',
// 	 	'label' => 'Nomenclatura',
// 	 	'cantidadCampos' => 1,
// 	 ];
//
// 	 $i = 1;
//
// 	 foreach( $arrayLabelsNomen as $array ){
//
// 	 	$nomenclatura["label$i"] = $array['nombre'];
// 		$nomenclatura["campo$i"] = $array['cod'];
//
// 	 	$i++;
// 	 }
// var_dump($nomenclatura);
?>
<div class="inm-view">

	<div class="form-inmueble">


	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>

	<?php


	$form = ActiveForm::begin([
		'id' => 'form-inmueble',
		'validateOnSubmit' => false,
		'fieldConfig' => [
		'template' => "{label}{input}",
			],

	]);

	echo "<input type='text' name='txCriterio' id='inm-txCriterio' style='display:none'>";
	echo "<input type='text' name='txDescripcion' id='inm-txDescripcion' style='display:none'>";

			Pjax::begin(['id'=>'NUMResp']);
				$NUMResp = "";
				$objetoDesde = "";
				$objetoHasta = "";

			if (isset($_POST['NUMResp'])) $NUMResp = $_POST['NUMResp'];
			if (isset($_POST['objetoDesde'])) $objetoDesde = $_POST['objetoDesde'];
			if (isset($_POST['objetoHasta'])) $objetoHasta = $_POST['objetoHasta'];


			if (strlen($NUMResp) < 8 and $NUMResp != "")
			{
			$NUMResp = utb::GetObjeto(3,$NUMResp);
			echo '<script>$("#inm-txNUMresp").val("'.$NUMResp.'")</script>';
			}
			if (strlen($objetoDesde) < 8 and $objetoDesde != "")
			{
			$objetoDesde = utb::GetObjeto(1,$objetoDesde);
			echo '<script>$("#inm-txObjetoDesde").val("'.$objetoDesde.'")</script>';
			}
			if (strlen($objetoHasta) < 8 and $objetoHasta != "")
			{
			$objetoHasta = utb::GetObjeto(1,$objetoHasta);
			echo '<script>$("#inm-txObjetoHasta").val("'.$objetoHasta.'")</script>';
			}


			Pjax::end();


			//Creo dos arreglos ocultos que almacenarán los valores de los domicilios
			echo '<input type="text" name="arrayDomiPar" id="arrayDomiPar" value="'.urlencode(serialize($modelodomipar)).'" style="display:none">';
			echo '<input type="text" name="arrayDomiPost" id="arrayDomiPost" value="'.urlencode(serialize($modelodomipost)).'" style="display:none">';

			//Bloque que se encarga de cargar los datos de los domicilios.
			//El id del Pjax debe ser "CargarModeloDomi" ya que así está creada la función.
			Pjax::begin(['id' => 'CargarModeloDomi']);

				if(isset($_POST['tor']))
				{
					//Obtengo los datos en el caso de que ingrese un domicilio parcelario
					if ($_POST['tor'] == 'INM')
					{
						$modelodomipar->torigen = 'INM';
						$modelodomipar->obj_id = $model->obj_id;
			 			$modelodomipar->id = 0;
						$modelodomipar->prov_id = isset($_POST['prov_id']) ? $_POST['prov_id'] : 0;
						$modelodomipar->loc_id = isset($_POST['loc_id']) ? $_POST['loc_id'] : 0;
			 			$modelodomipar->cp = isset($_POST['cp']) ? $_POST['cp'] : "";
			 			$modelodomipar->barr_id = isset($_POST['barr_id']) ? $_POST['barr_id'] : 0;
			 			$modelodomipar->calle_id = isset($_POST['calle_id']) ? $_POST['calle_id'] : 0;
			 			$modelodomipar->nomcalle = isset($_POST['nomcalle']) ? $_POST['nomcalle'] : "";
			 			$modelodomipar->puerta = isset($_POST['puerta']) ? $_POST['puerta'] : "";
			 			$modelodomipar->det = isset($_POST['det']) ? $_POST['det'] : "";
			 			$modelodomipar->piso = isset($_POST['piso']) ? $_POST['piso'] : "";
			 			$modelodomipar->dpto = isset($_POST['dpto']) ? $_POST['dpto'] : "";
			 	 		$modelodomipar->domicilio = 'Hola a todos';

//			 			$modelodomipar->domicilio = $modelodomipar->nomcalle.'  '.$modelodomipar->puerta.($modelodomipar->det != '' ? ' ('.$modelodomipar->det.') ' : '').($modelodomipar->piso != '' ? ' Piso: '.$modelodomipar->piso : '');
//						$modelodomipar->domicilio .= ($modelodomipar->dpto !='' ? ' Dpto: '.$modelodomipar->dpto : '');
//						$modelodomipar->domicilio .= ($modelodomipar->barr_id !='' ? ' - B°: '.utb::getCampo("domi_barrio","barr_id=".$modelodomipar->barr_id,"nombre") : '');

			 			echo '<script>$("#domi_parcelario").val("' . $modelodomipar->armarDescripcion() . '")</script>';
			 			echo '<script>$("#arrayDomiPar").val("'.urlencode(serialize($modelodomipar)).'")</script>';

					}

					//Obtengo los datos en caso de que ingrese un domicilio postal
					if($_POST['tor'] == 'OBJ')
					{

						$modelodomipost->torigen = 'OBJ';
						$modelodomipost->obj_id = $model->obj_id;
			 			$modelodomipost->id = 0;
						$modelodomipost->prov_id = isset($_POST['prov_id']) ? $_POST['prov_id'] : 0;
						$modelodomipost->loc_id = isset($_POST['loc_id']) ? $_POST['loc_id'] : 0;
			 			$modelodomipost->cp = isset($_POST['cp']) ? $_POST['cp'] : "";
			 			$modelodomipost->barr_id = isset($_POST['barr_id']) ? $_POST['barr_id'] : 0;
			 			$modelodomipost->calle_id = isset($_POST['calle_id']) ? $_POST['calle_id'] : 0;
			 			$modelodomipost->nomcalle = isset($_POST['nomcalle']) ? $_POST['nomcalle'] : "";
			 			$modelodomipost->puerta = isset($_POST['puerta']) ? $_POST['puerta'] : "";
			 			$modelodomipost->det = isset($_POST['det']) ? $_POST['det'] : "";
			 			$modelodomipost->piso = isset($_POST['piso']) ? $_POST['piso'] : "";
			 			$modelodomipost->dpto = isset($_POST['dpto']) ? $_POST['dpto'] : "";

//			 			$modelodomipost->domicilio = $modelodomipost->nomcalle.'  '.$modelodomipost->puerta.($modelodomipost->det != '' ? ' ('.$modelodomipost->det.') ' : '').($modelodomipost->piso != '' ? ' Piso: '.$modelodomipost->piso : '');
//						$modelodomipost->domicilio .= ($modelodomipost->dpto !='' ? ' Dpto: '.$modelodomipost->dpto : '');
//						$modelodomipost->domicilio .= ($modelodomipost->barr_id !='' ? ' - B°: '.utb::getCampo("domi_barrio","barr_id=".$modelodomipost->barr_id,"nombre") : '');

			 			echo '<script>$("#domi_postal").val("'.$modelodomipost->armarDescripcion().'")</script>';
			 			echo '<script>$("#arrayDomiPost").val("'.urlencode(serialize($modelodomipost)).'")</script>';
					}

				}

			Pjax::end();

		?>

	<table>
		<tr>
			<td ><label>Objeto:</label><br>
			<?= $form->field($model, 'obj_id')->textInput(['disabled' => true, 'style' => 'width:80px', 'maxlength'=>'8', 'class' => 'form-control'])->label(false) ?></td>
			<td width="30px"></td>
			<td><label>Nombre</label><br>
			<?= $form->field($modelObjeto, 'nombre')->textInput([ 'style' => 'width:450px;text-transform: uppercase','maxlength'=>'50', 'class' => 'form-control'])->label(false) ?></td>
			<td width="30px"></td>
			<td><label>Estado</label><br>
			<?= $form->field($modelObjeto, 'est_nom')->textInput(['disabled' => true, 'style' => 'width:90px;',
					'class' => ($modelObjeto->est == 'B' ? 'form-control baja' : 'form-control solo-lectura')])->label(false) ?></td>
			<td width="10px"></td>
		</tr>
	</table>

	<div class="form">
	<table>

		<tr>
			<td width="60px"><label>Part. Prov</label>
			<?= $form->field($model, 'parp')->textInput([ 'style' => 'width:100%', 'maxlength'=>'6', 'class' => 'form-control', 'onkeypress'=>'return justNumbers(event)'])->label(false) ?></td>
			<td width="10px"></td>
			<td width="60px"><label>Plano</label>
			<?= $form->field($model, 'plano')->textInput([ 'style' => 'width:100%', 'maxlength'=>'6', 'class' => 'form-control', 'onkeypress'=>'return justNumbers(event)'])->label(false) ?></td>
			<td width="10px"></td>
			<td width="60px"><label>ParP Orig.</label>
			<?= $form->field($model, 'parporigen')->textInput([ 'style' => 'width:100%', 'maxlength'=>'8', 'class' => 'form-control', 'onkeypress'=>'return justNumbers(event)'])->label(false) ?></td>
			<td width="10px"></td>
	<?php
	
	//
	if (isset ( $arregloLabels )){

		$lengthArregloLabels 	= $model->lengthArregoLabels();
		$validateArregloLabels 	= $model->validateArregloLabels();

		while ($nombre = current($arregloLabels)){

			?>

			<td width='60px'>
				<label><?= $nombre ?></label>
				<?php 
					if ( in_array(strtolower(key($arregloLabels)), ['s1','s2','s3']) ) {
						$countS = intVal( utb::getCampo("inm_" . strtolower(key($arregloLabels)), "", "count(*)") );
						
						if ( $countS == 0 ){
				?>
							<?=
								$form->field($model, key($arregloLabels))->textInput([
									'style' => 'width:100%',
									'class' => 'form-control',
									'onkeypress'	=> ( $validateArregloLabels[key($arregloLabels)] == 1 ? 'return justNumbers( event )' : '' ),
									'maxlength'		=> $lengthArregloLabels[key($arregloLabels)],
								])->label(false);
							?>
						<?php } else { ?>			
							<?= 
								$form->field($model, key($arregloLabels))->dropDownList(
									utb::getAux("inm_" . strtolower(key($arregloLabels)), key($arregloLabels), key($arregloLabels)), 
									[ 'style' => 'width:100%', 'class' => 'form-control', 'prompt' => '' ]
								)->label(false) 
							?>
						<?php } ?>	
				<?php } else { ?>					
					<?=
						$form->field($model, key($arregloLabels))->textInput([
							'style' => 'width:100%',
							'class' => 'form-control',
							'onkeypress'	=> ( $validateArregloLabels[key($arregloLabels)] == 1 ? 'return justNumbers( event )' : '' ),
							'maxlength'		=> $lengthArregloLabels[key($arregloLabels)],
						])->label(false);
					?>
				<?php } ?>	
			</td>
			<td width="5px"></td>

			<?php
			next($lengthArregloLabels);
			next($validateArregloLabels);
			next($arregloLabels);
		}

	}
	?>
			<td width='30px' ><label>UF</label><?= $form->field($model, 'uf')->textInput([ 'style' => 'width:100%', 'class' => 'form-control' ])->label(false) ?></td>
			<td width="5px"></td>
			<td width="60px"><label>Porc PH</label><?= $form->field($model, 'porcuf')->textInput(['style' => 'width:90%','class' => 'form-control','maxlength'=>'9', 'onkeypress'=>'return justDecimal($(this).val(), event)'])->label(false) ?></td>
		</tr>
	</table>

	</div>

	<table border="0">

		<tr>
			<td width="70px"><label>NC Ant.:</label></td>
			<td width="250px">
				<?= $form->field($model, 'nc_ant')->textInput([ 'style' => 'width:100%','maxlength'=>'20', 'class' => 'form-control'])->label(false) ?>
			</td>
		</tr>

		<tr>
			<td width="70px"><label>UrbSub:</label></td>
			<td width="250px"><?= $form->field($model, 'urbsub')->dropDownList(utb::getAux('inm_turbsub'), [ 'style' => 'width:100%','class' => 'form-control', 'prompt' => ''])->label(false) ?></td>
			<td width="30px"></td>
			<td width="70px"><label>Titularidad:</label></td>
			<td width="250px"><?= $form->field($model, 'titularidad')->dropDownList(utb::getAux('inm_ttitularidad'),[ 'style' => 'width:100%','class' => 'form-control', 'prompt' => ''])->label(false) ?>
		</tr>

		<tr>
			<td width="70px"><label>Barrio:</label></td>
			<td width="250px"><?= $form->field($model, 'barr_id')->dropDownList(utb::getAux('domi_barrio', 'barr_id'),[ 'style' => 'width:100%','class' => 'form-control', 'prompt' => ''])->label(false) ?>
			<td width="30px"></td>
			<td width="70px"><label>Tipo:</label></td>
			<td width="250px"><?= $form->field($model, 'tinm')->dropDownList(utb::getAux('inm_tipo'), [ 'style' => 'width:100%','class' => 'form-control', 'prompt' => ''])->label(false) ?></td>
		</tr>

		<tr>
			<td width="70px"><label>Uso:</label></td>
			<td width="250px"><?= $form->field($model, 'uso')->dropDownList(utb::getAux('inm_tuso'),[ 'style' => 'width:100%','class' => 'form-control', 'prompt' => ''])->label(false) ?>
			<td></td>
			<td width="70px"><label>Patrimonio:</label></td>
			<td width="240px"><?= $form->field($model, 'patrimonio', ['template' => '{label}{input}'])->dropDownList(utb::getAux('inm_tpatrimonio'), ['style' => 'width:100%;', 'prompt' => ''])->label( false ) ?></td>
		</tr>
	</table>
	<table>
		<tr>
			<td width="70px"><label>Comprador:</label></td>
			<td>
			<!-- boton de búsqueda modal -->
				<?php
				Modal::begin([
					'id' => 'BuscaObjBuscaAux1',
					'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Comprador</h2>',
					'size' => 'modal-lg',
					'toggleButton' => [
						'label' => '<i class="glyphicon glyphicon-search"></i>',
						'class' => 'bt-buscar',
						'id'=>'btnDomParcela'
					],
					'closeButton' => [
					  'label' => '<b>X</b>',
					  'class' => 'btn btn-danger btn-sm pull-right',
					],
					 ]);

					echo $this->render('//objeto/objetobuscarav', ['id' => 'BuscaAux1', 'txNom' => 'inm-comprador-nom', 'selectorModal' => '#BuscaObjBuscaAux1', 'tobjeto' => 3]);
//					echo $this->render('//objeto/persona/buscarav',['id' => 'BuscaAux1','txNom' => 'inm-comprador-nom']);

				Modal::end();
				?>

				<!-- fin de boton de búsqueda modal -->
			</td>
			<td>
			<?= $form->field($model, 'comprador', ['template' => '{input}', 'options' => ['style' => 'margin-bottom:0;']])->textInput(['id' => 'inm-comprador-nom', 'class' => 'form-control', 'style' => 'width:310px;'])->label(false) ?>
			</td>

		</tr>
	</table>

	<table>
		<tr>
			<td width="70px"><label>T. Distrib:</label></td>
			<td width="250px"><?= $form->field($modelObjeto, 'tdistrib')->dropDownList(utb::getAux('objeto_tdistrib'),[ 'style' => 'width:100%','class' => 'form-control', 'prompt' => ''])->label(false) ?></td>
			<td width="30px"></td>
			<td width="70px"><label>Distribuidor:</label></td>
			<td width="250px"><?= $form->field($modelObjeto, 'distrib')->dropDownList(utb::getAux('sam.sis_usuario','usr_id','apenom',1,'distrib<>0','',true), [ 'style' => 'width:100%','class' => 'form-control', 'prompt' => ''])->label(false) ?></td>
		</tr>

	</table>

	<?php
		//Código que actualiza el nombre de la persona cuando ingresa el código de persona


	Pjax::begin(['id'=>'actualizaSuperp']);

		if(isset($_POST['super'])){

			$superp = $_POST['super'];

			if (strlen($superp) < 8)
			{
				$superp = utb::GetObjeto(1,(int)$superp);
				echo '<script>$("#inm-objeto_superp").val("'.$superp.'")</script>';

			}
		}

	Pjax::end();

	?>

	<table>
		<tr>
			<td><label>Expediente:</label></td><td width="80px"><?= $form->field($model, 'expe')->textInput([ 'style' => 'width:100%','maxlength'=>'12', 'class' => 'form-control'])->label(false) ?></td>
			<td width="10px"></td>
			<td><label>Año Mensura:</label></td><td width="40px"><?= $form->field($model, 'anio_mensura')->textInput([ 'style' => 'width:100%','maxlength'=>'4', 'class' => 'form-control', 'onkeypress'=>'return justNumbers(event)'])->label(false) ?></td>
			<td width="10px"></td>
			<td><label>Superposición:</label></td><td width="80px"><?= $form->field($model, 'objeto_superp')->textInput([ 'style' => 'width:100%','maxlength'=>'8', 'class' => 'form-control', 'onchange'=>'$.pjax.reload({container:"#actualizaSuperp",method:"POST",data:{super:$(this).val()}})'])->label(false) ?></td>
			<td width="10px"></td>
			<td><label>Unificado:</label></td><td width="80px"><?= $form->field($modelObjeto, 'objunifica')->textInput([ 'style' => 'width:100%','maxlength'=>'8', 'class' => 'form-control', 'readOnly' => true])->label(false) ?></td>
		</tr>
	</table>

	<?php if ($modelObjeto->existemisc > 0) {?>
	<div class='glyphicon glyphicon-comment' style="color:#337ab7">
		<?= Html::a('<b>Existen Misceláneas</b>', ['objeto/objeto/miscelaneas','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?>
	</div>
	<?php
	}

		$tab = 0; // Esta variable determinará que pestaña se mostrará del widget

		//INICIO Código que se ejecuta en caso de que estemos por eliminar un inmueble.
		 if ($consulta==2) { ?>
	<p>&nbsp;</p>
	<label>Información sobre la Baja</label>
	<div style='border:1px solid #ddd;border-radius:5px;padding:5px;'>
	<table border='0'>
	<tr>
		<?= $form->field($modelObjeto, 'tbaja')->dropDownList(utb::getAux('objeto_tbaja','cod','nombre',0,'tobj=1'),['prompt'=>'Seleccionar...','onchange' => 'VariablesBaja()']) ?>
		<td width='10px'></td>
		<?= $form->field($modelObjeto, 'elimobjcondeuda')->checkbox(['onchange' => 'VariablesBaja()']) ?>
		<td width='10px'></td>
		<?= $form->field($modelObjeto, 'elimobjconadhe')->checkbox(['onchange' => 'VariablesBaja()']) ?>
	</tr>
	</table>
	</div>
	<?php

	$tab = 6;

	}
	//FIN Código que se ejecuta en caso de que estemos por eliminar un inmueble.

?>
</div>

<div class="form" style="margin: 8px 0px">

<table>

	<tr>
		<td><label>Alta:</label></td>
		<td width="180px">
			<?=
				DatePicker::widget(['model' => $modelObjeto, 'attribute' => 'fchalta', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'style' => 'width:80px;']]);
			?>
		</td>
		<td width="20px"></td>
		<td><label>Modif.:</label></td><td width="180px"><?= $form->field($modelObjeto, 'modif')->textInput(['disabled' => true, 'style' => 'width:100%; background-color: #E6E6FA', 'class' => 'form-control'])->label(false) ?></td>
		<td width="20px"></td>
		<td><label>Baja:</label></td><td width="180px"><?= $form->field($modelObjeto, 'baja')->textInput(['disabled' => true, 'style' => 'width:100%; background-color: #E6E6FA', 'class' => 'form-control'])->label(false) ?>
		<td width="10px"></td>
	</tr>

</table>

</div>

<?php
	if ( $modelObjeto->est == 'M' ) $tab = 1;

	echo Tabs :: widget ([

    	 	'id' => 'TabDescuento',
			'items' => [
 				[
					'label' => 'Titular',
					'content' => $this->render('titular', ['form' => $form, 'modelObjeto' => $modelObjeto, 'model' => $model, 'consulta' => $consulta ]),
					'active' => ($tab==0) ?  true : false,
					'options' => ['class'=>'tabItem']
 				],
 				[
					'label' => 'Domic./Frente' ,
					'content' => $this->render('domic_frente', ['form' => $form, 'modelObjeto' => $modelObjeto, 'model' => $model,'modelodomipar' => $modelodomipar,'modelodomipost' => $modelodomipost, 'consulta' => $consulta]),
					'active' => ($tab==1) ?  true : false,
					'options' => ['class'=>'tabItem']
 				],
 				['label' => 'Mejora' ,
 				'content' => $this->render('mejoras', ['form' => $form, 'modelObjeto' => $modelObjeto, 'model' => $model, 'arregloMejoras' => $arregloMejoras, 'consulta' => $consulta ]),
 				'active' => ($tab==2) ?  true : false,
 				'options' => ['class'=>'tabItem']
 				],
 				['label' => 'Valuac',
 				'content' => $this->render('valuaciones', ['form' => $form, 'model' => $model,'modelObjeto' => $modelObjeto, 'arregloComputados' => $arregloComputados]),
 				'active' => ($tab==3) ?  true : false,
 				'options' => ['class'=>'tabItem', 'style' => 'padding: 15px 5px;']
 				],
	 			['label' => 'Asignaciones' ,
	 			'content' => $this->render('//objeto/objetoasignacioneslist',['modelobjeto' => $modelObjeto]),
	 			'options' => ['class'=>'tabItem'],
	 			'active' => $tab == 4 ? true : false,
	 			],
	 			['label' => 'Acciones' ,
	 			'content' => $this->render('//objeto/objetoaccioneslist',['modelobjeto' => $modelObjeto]),
	 			'options' => ['class'=>'tabItem'],
	 			'active' => $tab == 5 ? true : false,
	 			],
	 			['label' => 'Observaciones' ,
	 			'content' => $form->field($modelObjeto, 'obs')->textarea(['maxlength' => 1000,'style' => 'width:590px;height:72px; max-height:72px; max-width:590px;','onblur' => 'VariablesBaja()']),
	 			'options' => ['class'=>'tabItem'],
	 			'active' => $tab == 6 ? true : false,
	 			]
    	]
    ]);

	//Ingresa en el caso de que no se esté en el view
	if ($consulta != 1)

	{
		?>

		<div class="form-group" style='margin-top:10px'>

		<?php
			// elimobjcondeuda es una variable que determinará si se eliminan o no los objetos con deuda
			// elimobjcondeuda == 1 => Se eliminan objetos con deuda
			$elimobjcondeuda = (isset($_GET['elimobjcondeuda'])) ? $_GET['elimobjcondeuda'] : 0;

			// elimina las adhesiones del objeto
			$elimobjconadhe = (isset($_GET['elimobjconadhe'])) ? $_GET['elimobjconadhe'] : 0;

			$tbaja = (isset($_GET['tbaja'])) ? $_GET['tbaja'] : 0;

			$obs = (isset($_GET['obs'])) ? $_GET['obs'] : '';

			Pjax::begin(['id' => 'btOpciones']);

				//Si se va a eliminar ingresa acá
				if ($consulta == 2)
				{
					if (isset($_POST['elimobjcondeuda'])) $elimobjcondeuda = $_POST['elimobjcondeuda'];
					if (isset($_POST['elimobjconadhe'])) $elimobjconadhe = $_POST['elimobjconadhe'];
					if (isset($_POST['tbaja'])) $tbaja = $_POST['tbaja'];
					if (isset($_POST['obs'])) $obs = $_POST['obs'];

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

					echo Html::a('Aceptar', ['delete',
										'accion'=>1,
										'id' => $model->obj_id,
										'elimobjcondeuda' =>$elimobjcondeuda,
										'elimobjconadhe' =>$elimobjconadhe,
										'tbaja' => $tbaja,
										'obs' => $obs],
										[
            							'class' => 'btn btn-success',
            							'data' => [
                							'method' => 'post',
            							],
        				]);

        			echo "&nbsp;&nbsp;";
			 		echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'id' => 'btEliminarCanc','onClick' => '$("#ModalEmiminar, .window").modal("toggle");']);
			 		echo "</center>";

			 	Modal::end();

				} else
					echo Html::button('Grabar', ['class' => 'btn btn-success', 'onclick' => '$("#form-inmueble").submit();']);

				echo "&nbsp;&nbsp;";

				echo Html::a('Cancelar', ['view', 'id' => $model->obj_id,'reiniciar'=>1], [
	            			'class' => 'btn btn-primary',
	        			]);

        	Pjax::end();
		?>

	</div>

	<?php
	}

	echo $form->errorSummary($model);

	ActiveForm::end();

	//Si la consulta el cuando se dibuja el form en el "index" o en el delete
	if ( $consulta == 1 || $consulta==2 )
	{
		echo "<script>";
		echo "DesactivarForm('form-inmueble');";
		echo "$('#inm_mejoras_grilla .bt-buscar-label').prop('disabled', false)";
		echo "</script>";
	}

    if ($consulta==0 && $modelObjeto->autoinc == 0) echo '<script>$("#inm-obj_id").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#objeto-elimobjcondeuda").prop("disabled", false);</script>';
	if ($consulta==2) echo '<script>$("#objeto-elimobjconadhe").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#objeto-tbaja").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#objeto-obs").prop("readonly", false);</script>';
    if ($consulta==2) echo '<script>$("#objeto-obs").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#objeto-obs").val("'.$obs.'");</script>';

	if ( $model->regimen == 3 and $modelObjeto->est == 'A' and utb::samConfig()['inm_phmadre'] == 1 ){

		echo '<script> $("#consultaFreste *").prop("disabled", true); </script>';
		echo '<script> $("#consultaMejoras *").prop("disabled", true); </script>';
		echo '<script> $("#consultaAvaluo *").prop("disabled", true); </script>';

	}

	if ( $modelObjeto->est == 'M' ){

		echo '<script> $("#consultaDomic *").prop("disabled", true); </script>';

	}

	?>

	<script>
	function VariablesBaja()
	{
		$.pjax.reload(
			{
				container:"#btOpciones",
				data:{
						obs:$("#objeto-obs").val(),
						elimobjcondeuda:$("#objeto-elimobjcondeuda:checked").val(),
						elimobjconadhe:$("#objeto-elimobjconadhe:checked").val(),
						tbaja:$("#objeto-tbaja").val()
					},
				method:"POST"
			})
	}

	<?php if ( $modelObjeto->est == 'M' ) { ?>
		 $('#TabDescuento > li').eq(0).hide();
		 $('#TabDescuento > li').eq(4).hide();
	<?php } ?>

	</script>
	</div>
</div>
