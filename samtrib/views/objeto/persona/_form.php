<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use yii\widgets\MaskedInput;
use \yii\widgets\Pjax;
use yii\web\Session;
use \yii\bootstrap\Modal;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\objeto\Persona */
/* @var $modelobjeto app\models\objeto\Objeto */
/* @var $form yii\widgets\ActiveForm */

Pjax::begin(['id' => 'TabAct']);

	$session = new Session;
    $session->open();
	if (!isset($_GET['page'])) $session['tab'] = 0;

    if (isset($_POST['tab'])) $session['tab'] = $_POST['tab'];

    $tab = $session['tab'];

    $session->close();
Pjax::end();
?>

<div class="persona-form" >
    <?php
    	$form = ActiveForm::begin(['id' => 'form-persona',
    								'fieldConfig' => [
        										'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>",
        										],

        							'validateOnSubmit' => false,
        						]);

    	$param = Yii::$app->params;

    	// comienza bloque donde cargo los datos del modelo domi
Pjax::begin(['id' => 'CargarModeloDomi']);

	if (isset($_POST['tor']))
	{
		if ($_POST['tor'] == 'OBJ')
		{
			$modelodomipost->torigen = 'OBJ';
 			$modelodomipost->obj_id = $model->obj_id;
 			$modelodomipost->id = 0;
			$modelodomipost->prov_id = Yii::$app->request->post('prov_id', 0);
			$modelodomipost->loc_id = Yii::$app->request->post('loc_id', 0);
 			$modelodomipost->cp = Yii::$app->request->post('cp', '');
 			$modelodomipost->barr_id = Yii::$app->request->post('barr_id', 0);
 			$modelodomipost->calle_id = Yii::$app->request->post('calle_id', 0);
 			$modelodomipost->nomcalle = Yii::$app->request->post('nomcalle', '');
 			$modelodomipost->puerta = Yii::$app->request->post('puerta', '');
 			$modelodomipost->det = Yii::$app->request->post('det', '');
 			$modelodomipost->piso = Yii::$app->request->post('piso', '');
 			$modelodomipost->dpto = Yii::$app->request->post('dpto', '');

// 			$modelodomipost->domicilio = $modelodomipost->nomcalle.'  '.$modelodomipost->puerta.($modelodomipost->det != '' ? ' ('.$modelodomipost->det.') ' : '').($modelodomipost->piso != '' ? ' Piso: '.$modelodomipost->piso : '');
//			$modelodomipost->domicilio .= ($modelodomipost->dpto !='' ? ' Dpto: '.$modelodomipost->dpto : '');
//			$modelodomipost->domicilio .= ($modelodomipost->barr_id !='' ? ' - B°: '.utb::getCampo("domi_barrio","barr_id=".$modelodomipost->barr_id,"nombre") : '');

 			echo '<script>$("#domi_postal").val("'.$modelodomipost->armarDescripcion().'")</script>';
 			echo '<script>$("#arrayDomiPost").val("'.urlencode(serialize($modelodomipost)).'")</script>';
		}
		if ($_POST['tor'] == 'PLE')
		{
			$modelodomileg->torigen = 'PLE';
 			$modelodomileg->obj_id = $model->obj_id;
 			$modelodomileg->id = 0;
			$modelodomileg->prov_id = isset($_POST['prov_id']) ? $_POST['prov_id'] : 0;
			$modelodomileg->loc_id = isset($_POST['loc_id']) ? $_POST['loc_id'] : 0;
 			$modelodomileg->cp = isset($_POST['cp']) ? $_POST['cp'] : "";
 			$modelodomileg->barr_id = isset($_POST['barr_id']) ? $_POST['barr_id'] : 0;
 			$modelodomileg->calle_id = isset($_POST['calle_id']) ? $_POST['calle_id'] : 0;
 			$modelodomileg->nomcalle = isset($_POST['nomcalle']) ? $_POST['nomcalle'] : "";
 			$modelodomileg->puerta = isset($_POST['puerta']) ? $_POST['puerta'] : "";
 			$modelodomileg->det = isset($_POST['det']) ? $_POST['det'] : "";
 			$modelodomileg->piso = isset($_POST['piso']) ? $_POST['piso'] : "";
 			$modelodomileg->dpto = isset($_POST['dpto']) ? $_POST['dpto'] : "";

// 			$modelodomileg->domicilio = $modelodomileg->nomcalle.'  '.$modelodomileg->puerta.($modelodomileg->det != '' ? ' ('.$modelodomileg->det.') ' : '').($modelodomileg->piso != '' ? ' Piso: '.$modelodomileg->piso : '');
//			$modelodomileg->domicilio .= ($modelodomileg->dpto !='' ? ' Dpto: '.$modelodomileg->dpto : '');
//			$modelodomileg->domicilio .= ($modelodomileg->barr_id !='' ? ' - B°: '.utb::getCampo("domi_barrio","barr_id=".$modelodomileg->barr_id,"nombre") : '');

 			echo '<script>$("#domi_legal").val("'.$modelodomileg->armarDescripcion().'")</script>';
 			echo '<script>$("#arrayDomiLeg").val("'.urlencode(serialize($modelodomileg)).'")</script>';
		}
		if ($_POST['tor'] == 'PRE')
		{
			$modelodomires->torigen = 'PRE';
 			$modelodomires->obj_id = $model->obj_id;
 			$modelodomires->id = 0;
			$modelodomires->prov_id = isset($_POST['prov_id']) ? $_POST['prov_id'] : 0;
			$modelodomires->loc_id = isset($_POST['loc_id']) ? $_POST['loc_id'] : 0;
 			$modelodomires->cp = isset($_POST['cp']) ? $_POST['cp'] : "";
 			$modelodomires->barr_id = isset($_POST['barr_id']) ? $_POST['barr_id'] : 0;
 			$modelodomires->calle_id = isset($_POST['calle_id']) ? $_POST['calle_id'] : 0;
 			$modelodomires->nomcalle = isset($_POST['nomcalle']) ? $_POST['nomcalle'] : "";
 			$modelodomires->puerta = isset($_POST['puerta']) ? $_POST['puerta'] : "";
 			$modelodomires->det = isset($_POST['det']) ? $_POST['det'] : "";
 			$modelodomires->piso = isset($_POST['piso']) ? $_POST['piso'] : "";
 			$modelodomires->dpto = isset($_POST['dpto']) ? $_POST['dpto'] : "";

// 			$modelodomires->domicilio = $modelodomires->nomcalle.'  '.$modelodomires->puerta.($modelodomires->det != '' ? ' ('.$modelodomires->det.') ' : '').($modelodomires->piso != '' ? ' Piso: '.$modelodomires->piso : '');
//			$modelodomires->domicilio .= ($modelodomires->dpto !='' ? ' Dpto: '.$modelodomires->dpto : '');
//			$modelodomires->domicilio .= ($modelodomires->barr_id !='' ? ' - B°: '.utb::getCampo("domi_barrio","barr_id=".$modelodomires->barr_id,"nombre") : '');

 			echo '<script>$("#domi_res").val("'.$modelodomires->armarDescripcion().'")</script>';
 			echo '<script>$("#arrayDomiRes").val("'.urlencode(serialize($modelodomires)).'")</script>';
		}

	}

Pjax::end();

echo '<input type="text" name="arrayDomiPost" id="arrayDomiPost" value="'.urlencode(serialize($modelodomipost)).'" style="display:none">';
echo '<input type="text" name="arrayDomiLeg" id="arrayDomiLeg" value="'.urlencode(serialize($modelodomileg)).'" style="display:none">';
echo '<input type="text" name="arrayDomiRes" id="arrayDomiRes" value="'.urlencode(serialize($modelodomires)).'" style="display:none">';

?>

<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px; width:660px'>
<table border='0'>
<tr>
	<?= $form->field($model, 'obj_id',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['disabled'=>true,'style' => 'width:80px;']) ?>
	<?= $form->field($modelobjeto, 'nombre',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['maxlength' => 50,'style' => 'width:450px;text-transform:uppercase']) ?>
	<td width='5px'></td>
	<?= $form->field($modelobjeto, 'est_nom',['template' => $param['T_TAB_COL1_LIN2']])->textInput(['disabled'=>true,
			'style' => 'width:90px;text-align:center','class' => ($modelobjeto->est == 'B' ? 'form-control baja' : 'form-control solo-lectura')]) ?>
</tr>
</table>
<table border='0' width='640px'>
<tr>
	<?= $form->field($model, 'inscrip',['template' => $param['T_TAB_COL1']])->textInput(['style' => 'width:117px;','maxlength' => 8]) ?>
	<td width='10px'></td>
	<?= $form->field($model, 'tipo',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('persona_tipo'),['style' => 'width:122px;','prompt'=>'Seleccionar...']) ?>
	<td width='10px'></td>
	<?= $form->field($model, 'sexo',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('persona_tsexo'),['prompt'=>'Seleccionar...', 'style' => 'width:117px;']) ?>
</tr>
<tr>
	<?= $form->field($model, 'tdoc',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('persona_tdoc'),['style' => 'width:117px;','prompt'=>'Seleccionar...']) ?>
	<td width='10px'></td>
	<?= $form->field($model, 'ndoc',['template' => $param['T_TAB_COL1']])->textInput(['style' => 'width:122px;','maxlength' => 11,'onkeypress' => "return justNumbers(event);"]) ?>
	<td width='10px'></td>
	<?= $form->field($model, 'fchnac',['template' => $param['T_TAB_COL4']])->widget(DatePicker::classname(),
										['dateFormat' => 'dd/MM/yyyy', 'value' => $model->fchnac,
										'options' => ['class'=>'form-control','style'=>'width:116px;']]);?>
</tr>
<tr>
	<?= $form->field($model, 'nacionalidad',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('persona_tnac'),['style' => 'width:117px;','prompt'=>'Seleccionar...']) ?>
	<td width='10px'></td>
	<?= $form->field($model, 'estcivil',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('persona_testcivil'),['style' => 'width:122px;','prompt'=>'Seleccionar...']) ?>
	<td width='10px'></td>
	<?= $form->field($model, 'clasif',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('persona_tclasif'),['style' => 'width:116px;','prompt'=>'Seleccionar...']) ?>
</tr>
<tr>
	<?= $form->field($model, 'iva',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('comer_tiva'),['style' => 'width:117px;','prompt'=>'Seleccionar...']) ?>
	<td width='10px'></td>
	<?= $form->field($model, 'cuit',['template' => $param['T_TAB_COL1']])->widget(MaskedInput::classname(),['mask' => '99-99999999-9','options' => ['class'=>'form-control', 'style'=>'width:122px;text-align:center']]) ?>
	<td width='10px'></td>
	<?= $form->field($model, 'ag_rete',['template' => $param['T_TAB_COL1']])->textInput(['maxlength' => 7,'style' => 'width:116px;']) ?>
</tr>
<tr>
	<?= $form->field($model, 'tipoliq',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('comer_tliq'),['style' => 'width:117px;','prompt'=>'Seleccionar...'])->label( 'Tipo Liq.') ?>
	<td width='10px'></td>
	<?= $form->field($model, 'tel',['template' => $param['T_TAB_COL1']])->textInput(['maxlength' => 15,'style' => 'width:117px;']) ?>
	<td width='10px'></td>
	<?= $form->field($model, 'cel',['template' => $param['T_TAB_COL1']])->textInput(['maxlength' => 15,'style' => 'width:122px;']) ?>
</tr>
<tr>
	<td><label>e-mail:</label></td>
	<td colspan="4"><?= $form->field($model, 'mail', ['template' => '{input}'])->textInput(['maxlength' => 40, 'style' => 'width:98%;'])->label(false) ?></td>
	<td width='10px'></td>
	<td colspan='2'>
		<?=
			Html::activeCheckbox( $model, 'ag_rete_manual', [
				'id'		=> 'ag_rete_manual',
				'value'		=> 1,
				'uncheck'	=> 0
			]);
		?>
	</td>
</tr>
<tr>
	<?= $form->field($modelobjeto, 'tdistrib',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('objeto_tdistrib'),['style' => 'width:117px;','prompt'=>'Seleccionar...'])->label( 'Distribución') ?>
	<td width='10px'></td>
	<?= $form->field($modelobjeto, 'distrib',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('sam.sis_usuario','usr_id','apenom',1,'distrib=1'),['style' => 'width:117px;','prompt'=>'Seleccionar...']) ?>
</tr>
</table>

<?php if ($model->exis_foto == 1 || $model->exis_doc == 1 || $model->exis_insc == 1 || $modelobjeto->existemisc > 0) { ?>

	<div style="color:#337ab7;margin-top: 8px">

	<?php if ($model->exis_foto == 1 || $model->exis_doc == 1 || $model->exis_insc == 1) {?>
		<div class='glyphicon glyphicon-film' style="margin-right: 20px">
			<?= Html::a('<b>Existen Doc.Adjuntos</b>', ['objeto/persona/adjuntos','id' => $model->obj_id], ['class' => 'bt-buscar-label' . ( $consulta == 2 ? ' disabled' : '')]) ?>
		</div>
	<?php } ?>

	<?php if ($modelobjeto->existemisc > 0) {?>
		<div class='glyphicon glyphicon-comment'>
			<?= Html::a('<b>Existen Misceláneas</b>', ['objeto/objeto/miscelaneas','id' => $model->obj_id], ['class' => 'bt-buscar-label' . ( $consulta == 2 ? ' disabled' : '')]) ?>
		</div>
	<?php } ?>

	</div>

<?php } ?>

</div>

<?php if( $consulta == 1 && $model->est_ib != 'N' ){ ?>

<!-- INICIO Div Datos IB -->
<div id="persona_ib_divDatosIB" class="form" style="margin-bottom: 8px">

	<h3><label>Datos Ingresos Brutos</label></h3>

	<table border="0" width='90%'>
		<tr>
			<td colspan="2"> <label>Nombre de Fantasía:</label> </td>
			<td colspan='4'>
				
				<?=
					Html::label( $model->nombre_fantasia, null, [
						'class'     => 'form-control solo-lectura',
						'style'     => 'text-align:center; width: 100%',
					]);
				?>
			</td>
		</tr>
		
		<tr>
			<td> <label>N° IB:</label> </td>
			<td>
				<?=
					Html::label( $model->ib, null, [
						'class'     => 'form-control solo-lectura',
						'style'     => 'text-align:center; width: 80px',
					]);
				?>
			</td>
			<td><label>Org. Juri.</label></td>
			<td>
				<?=
					Html::label( $model->orgjuri_nom, null, [
						'class'     => 'form-control solo-lectura',
						'style'     => 'text-align:left; width: 160px',
					]);
				?>
			</td>
			<td><label>Tipo. Liq.</label></td>
			<td>
				<?=
					Html::label( $model->tipoliq_nom, null, [
						'class'     => 'form-control solo-lectura',
						'style'     => 'text-align:left; width: 130px',
					]);
				?>
			</td>
		</tr>
		
		<tr>
			<td><label>Contador:</label></td>
			<td  colspan="4">
				<?=
					Html::label( $model->contador, null, [
						'class'     => 'form-control solo-lectura',
						'style'     => 'text-align:center; width: 80px',
					]);
				?>
				<?=
					Html::label( $model->contador_nom, null, [
						'class'     => 'form-control solo-lectura',
						'style'     => 'text-align:left; width: 248px',
					]);
				?>
			</td>
			<td>
				<?=
					Html::checkBox( 'ckContadorVerDeuda', $model->contador_verdeuda, [
						'label'     => 'Ver deuda',
						'disabled'  => true,
					]);
				?>
			</td>
		</tr>
		
	</table>
	
	
	<table border="0">
		<tr>
			<td><label>Fecha Alta:</label></td>
			<td>
				<?=
					Html::label( $model->fchalta_ib, null, [
						'class'     => 'form-control solo-lectura',
						'style'     => 'text-align:center; width: 80px',
					]);
				?>
			</td>
			<td><label>Fecha Baja:</label></td>
			<td>
				<?=
					Html::label( $model->fchbaja_ib, null, [
						'class'     => 'form-control solo-lectura',
						'style'     => 'text-align:center; width: 80px',
					]);
				?>
			</td>
			<td><label>Tipo Baja IB:</label></td>
			<td>
				<?=
					Html::label( $model->tbaja_ib_nom, null, [
						'class'     => 'form-control solo-lectura',
						'style'     => 'text-align:center; width: 80px',
					]);
				?>
			</td>
			<td><label>Estado:</label></td>
			<td>
				<?=
					Html::label( $model->est_ib_nom, null, [
						'class'     => 'form-control solo-lectura',
						'style'     => 'text-align:center; width: 80px',
					]);
				?>
			</td>
		</tr>
		
	</table>

</div>
<!-- FIN Div Datos IB -->
<?php } ?>

<!-- INICIO Div Fechas -->
<div id="persona_ib_divFechas" class="form" style='padding:5px' align='right'>

	<table border='0' align='center'>
		<tr>
			<td><label>Alta:</label></td>
			<td>
				<?=
				 	Html::label( $modelobjeto->alta, null, [
						'class'	=> 'form-control solo-lectura',
						'style'	=> 'width:150px',
					]);
				?>
			</td>
			<td width="25px"></td>
			<td><label>Baja:</label></td>
			<td>
				<?=
				 	Html::label( $modelobjeto->baja, null, [
						'class'	=> 'form-control solo-lectura',
						'style'	=> 'width:150px',
					]);
				?>
			</td>
			<td width="25px"></td>
			<td><label>Modif.:</label></td>
			<td>
				<?=
				 	Html::label( $modelobjeto->modif, null, [
						'class'	=> 'form-control solo-lectura',
						'style'	=> 'width:150px',
					]);
				?>
			</td>
		</tr>
	</table>

</div>
<!-- FIN Div Fechas -->


<!-- INICIO Div Baja -->
<?php if ($consulta==2) { ?>

<div id="persona_form_divBaja" class="form" style="margin-top: 8px;padding-bottom: 8px">

	<h4><label><u>Información sobre la Baja</u></label></h4>

	<table border='0'>
		<tr>
			<?= $form->field($modelobjeto, 'tbaja',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('objeto_tbaja','cod','nombre',0,'tobj=3'),['prompt'=>'Seleccionar...','onchange' => 'VariablesBaja()']) ?>
			<td width='10px'></td>
			<?= $form->field($modelobjeto, 'elimobjcondeuda',['template' => $param['T_TAB_COL1']])->checkbox(['onchange' => 'VariablesBaja()']) ?>
		</tr>
	</table>

</div>

<?php } ?>
<!-- FIN Div Baja -->

<!-- INICIO Div Tabs -->
<div id="persona_ib_divTabs" style="margin-top: 8px">

<?php

// muestro tab de datos
	echo Tabs :: widget ([
 		'id' => 'TabPersona',
		'items' => [
 			['label' => 'Domicilio',
 			'content' =>  $this->render('domicilio',[
													'model' => $model,'modelobjeto' => $modelobjeto,
													'modelodomipost' => $modelodomipost, 'modelodomileg' => $modelodomileg,
													'modelodomires' => $modelodomires,
													]),
 			'options' => ['class'=>'tabItem']
 			],
			[
				'label' => 'Rubros',
				'content' => $this->render('//objeto/rubro/rubro', [
					'pjaxAActualizar'		=> 'persona_rubro_pjaxGrilla',
					'tipoObjeto'			=> 3,
					'modelObjeto'			=> $modelobjeto,
					'action' 		=> 1,
					'dadosDeBaja' 			=> $dadosDeBaja,
					'dataProvider'	=> new ArrayDataProvider([
						'allModels'	=> $model->rubros,
					]),
				]),
				'options' => ['class' => 'tabItem'],
				'active' => false,
				'headerOptions'	=> [ 'class' => ( $model->est_ib == 'N' || $consulta != 1 ? 'hidden' : '' ) ],
			],
 			['label' => 'Asignaciones de Items' ,
 			'content' => $this->render('//objeto/objetoasignacioneslist',['modelobjeto' => $modelobjeto]),
 			'options' => ['class'=>'tabItem'],
 			'active' => $tab == 1 ? true : false,
 			],
 			['label' => 'Acciones' ,
 			'content' => $this->render('//objeto/objetoaccioneslist',['modelobjeto' => $modelobjeto]),
 			'options' => ['class'=>'tabItem'],
 			'active' => $tab == 2 ? true : false,
 			],
			['label' => 'Inscrip.', 'content' => $this->render('//objeto/objetotributos', ['modelObjeto' => $modelobjeto, 'tab' => 3]), 'options' => ['class' => 'tabItem'], 'active' => $tab == 3],
 			['label' => 'Observaciones' ,
 			'content' => $form->field($modelobjeto, 'obs',['template' => '{input}'])->textarea(['maxlength' => 1000,'style' => 'max-width:600px;width:600px;height:100px; max-height:150px;','onblur' => 'VariablesBaja()'])->label(false),
 			'options' => ['class'=>'tabItem'],
 			'active' => $consulta == 2 ? true : false,
 			]
 		],
 		'headerOptions' => ['onclick' => '$.pjax.reload({container:"#TabAct",data:{tab:$(this).index()},method:"POST"})']
	]);

?>

</div>
<!-- FIN Div Tabs -->

<?php
 	if ($consulta !== 1){
 ?>
	<div class="form-group" style='margin-top:10px'>
		<?php

			if ($consulta==2)
			{
				echo Html::Button('Grabar', [
					'class' => 'btn btn-success',
					'id' => 'btEliminarAcep',
					'data' => [
						'toggle' => 'modal',
						'target' => '#ModalEmiminar',
					],
				]);
			} else {
			   echo Html::button( 'Grabar', [
				   'class' => 'btn btn-success',
				   'onclick'	=> 'f_persona_form_submit()',
			   ]);
			}

			echo "&nbsp;&nbsp;";

			echo Html::a( 'Cancelar', [ 'view', 'id' => $model->obj_id ], [
					   'class' => 'btn btn-primary',
					   'data-pjax'	=> 0,
				   ]);

		?>
	</div>

<?php
	}

	ActiveForm::end();

	echo $form->errorSummary($model, ['id' => 'errorSummaryModel']);

	if(isset($error) && $error !== '')
	{
		?>
		<script type="text/javascript">
		$(document).ready(function(){

			$("#errorSummaryModel").css("display", "none");

		});
		</script>

		<div class="error-summary"><ul><?= $error; ?></ul></div>
		<?php

	}

	if ($consulta==1 || $consulta==2)
    	{
    		echo "<script>";
			echo "DesactivarForm('form-persona');";
			echo "</script>";
    	}

    if ($consulta==0 and $modelobjeto->autoinc==0) echo '<script>$("#persona-obj_id").prop("disabled", false);</script>'; 
	if ($consulta==1) echo '<script>$("#rubro_filtroBaja").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#objeto-elimobjcondeuda").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#objeto-tbaja").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#objeto-obs").prop("readonly", false);</script>';
    if ($consulta==2) echo '<script>$("#objeto-obs").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#btEliminarAcep").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#btEliminarCanc").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#ModalEmiminar").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#btCancelarModalElim").prop("disabled", false);</script>';

?>

</div>

<?php

//INICIO Pjax Eliminar Objeto
Pjax::begin(['id' => 'btOpciones']);

	$elimobjcondeuda = (isset($_GET['elimobjcondeuda'])) ? $_GET['elimobjcondeuda'] : 0;
	$tbaja = (isset($_GET['tbaja'])) ? $_GET['tbaja'] : 0;
	$obs = (isset($_GET['obs'])) ? $_GET['obs'] : '';

	if (isset($_POST['elimobjcondeuda'])) $elimobjcondeuda = $_POST['elimobjcondeuda'];
	if (isset($_POST['tbaja'])) $tbaja = $_POST['tbaja'];
	if (isset($_POST['obs'])) $obs = $_POST['obs'];

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

Pjax::end();
//FIN Pjax Eliminar Objeto

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
					tbaja:$("#objeto-tbaja").val()
				},
			method:"POST"
		})
}

function f_persona_form_submit(){

	$( "#form-persona" ).submit();
}
</script>
