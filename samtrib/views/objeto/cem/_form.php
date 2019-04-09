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

use app\models\objeto\Domi;
use app\utils\db\Fecha;
/* @var $this yii\web\View */
/* @var $model app\models\objeto\Persona */
/* @var $modelobjeto app\models\objeto\Objeto */
/* @var $form yii\widgets\ActiveForm */

$templ = '{label}{input}{hint}';
$templ2 = '{label}<br>{input}{hint}';
$param = Yii::$app->params;
?>
<script type="text/javascript">
function cambiarNomeclatura(attrCambiado){
	
	var cuadro = $("#cemCuadro").val();
	var cuerpo = $("#cemCuerpo").val();
	var tipo = $("#cemTipo").val();
	var piso = $("#cemPiso").val();
	var fila = $("#cemFila").val();
	var nume = $("#cemNume").val();
	var bis = $("#cemBis").val();
	
	var accion = 0;
	
	if(attrCambiado == 'tipo')
		accion = 3;
	else if(attrCambiado == 'cuadro')
		accion = 1;
	else if(attrCambiado == 'cuerpo')
		accion = 2;
		
	$.pjax.reload({
		container : "#pjaxCambia", 
		type : "GET",
		replace : false,
		push : false,
		data : {
			"cuadro_id" : cuadro,
			"cuerpo_id" : cuerpo,
			"tipo" : tipo,
			"piso" : piso,
			"fila" : fila,
			"nume" : nume,
			"bis" : bis,
			"accion" : accion
		}
	});
}

function cambiarTipo(valor){
	
	//cuando es nicho, se deshabilita superficie
	$("#cemSup").prop("readonly", valor == 'NI');
	
	//se habilita la categoria cuando se elije el tipo
	$("#cenCat").prop("readonly", valor != '');
	
}

function cambiarExenta(valor){
	
	//edicto
	$("#cemEdicto").prop("readonly", valor != "1");
}
</script>


<div class="cem-form">

	<?php
	$form = ActiveForm::begin(['id' => 'formCem','fieldConfig' => ['template' => "{label}{input}",], 'validateOnSubmit' => false]);
	?>
<div class="form" style="padding-right:5px;padding-bottom:15px; margin-bottom:5px; width:640px;">

	<table border="0" width="100%">
		<tr>
			<td align="left">
				<?= $form->field($model, 'obj_id', ['template' => '{label}<br>{input}{hint}'])->textInput(['readonly' => true, 'style' => 'width:80px; background:#E6E6FA;'])->label('Objeto'); ?>				
			</td>
			<td width="5px"></td>
			<td align="left">
				<?= $form->field($modelObjeto, 'nombre', ['template' => '{label}<br>{input}{hint}'])->textInput(['maxlength' => 50, 'style' => 'width:350px'])->label('Nombre') ?>
			</td>
			<td width="5px"></td>
			<td align="left">
				<?= $form->field($model, 'cod_ant', ['template' => '{label}<br>{input}{hint}'])->textInput(['maxlength' => 15, 'style' => 'width:80px'])->label('Cód. Anterior') ?>
			</td>
			<td width="5px"></td>
			<td align="left">
				<?= $form->field($modelObjeto, 'est_nom', ['template' => '{label}<br>{input}{hint}'])->textInput(['maxlength' => 20, 'readonly' => true, 'style' => 'width:75px;','class' => ($modelObjeto->est == 'B' ? 'form-control baja' : 'form-control solo-lectura')])->label('Estado') ?>
			</td>
		</tr>
	</table>
	

	
	<?php
	Pjax::begin(['id' => 'pjaxCambia', 'enableReplaceState' => false, 'enablePushState' => false]);
	
	//se cargan los datos que ya habia modificado para que no se borren, los que no modifico se obtienen del modelo (datos originales)
	$model->cua_id = Yii::$app->request->get('cuadro_id', $model->cua_id);
	$model->cue_id = Yii::$app->request->get('cuerpo_id', $model->cue_id);
	$model->tipo = Yii::$app->request->get('tipo', $model->tipo);
	$model->piso = Yii::$app->request->get('piso', $model->piso);
	$model->fila = Yii::$app->request->get('fila', $model->fila);
	$nume = Yii::$app->request->get('nume', $model->nume);
	$model->bis = Yii::$app->request->get('bis', $model->bis);
	$model->cat = Yii::$app->request->get('cat', $model->cat);
	//se termina de cargar los datos
	
	/*
	 * determina la accion que realiza el usuario
	 * 	1 = cambio el cuadro
	 * 	2 = cambio el cuerpo
	 * 	3 = cambio el tipo
	 */
	$accion = isset($_GET['accion']) ? intval($_GET['accion']) : 0;
	
	$categorias = [];
	$noTieneTipo = false;
	$tienePiso = false;
	$tieneFila = false;
	$tieneNume = false;
	$tieneBis	= false;
	
	switch($accion){
		
		//cambia el cuadro
		case 1 :
		
			$datosCuadro = $model::getCuadro($model->cua_id);
			$tieneCuerpo = $model::cuadroTieneCuerpos($model->cua_id);
			
			if($datosCuadro !== false){
				$model->cua_id = $datosCuadro['cua_id'];
				$model->tipo = $datosCuadro['tipo'];
				
				$noTieneTipo = $model->tipo == '';
				$tienePiso = $datosCuadro['piso'] == 1;
				$tieneFila = $datosCuadro['fila'] == 1;
				$tieneNume = $datosCuadro['nume'] == 1;
				$tieneBis = $datosCuadro['bis'] == 1;
			} else {
				$model->cuerpo_id = null;
				$model->tipo = null;
				$model->piso = null;
				$model->fila = null;
				$model->nume = null;
				$model->cat = null;
				$model->bis = null;
				
				$tieneCuerpo = false;
				$noTieneTipo = false;
				$tienePiso = false;
				$tieneFila = false;
				$tieneNume = false;
				$tieneBis = false;
			}
		
			break;
			
		//cambia el cuerpo
		case 2 :
		
			$datosCuadro = $model::getCuadro($model->cua_id);
			$datosCuerpo = $model::getCuerpo($model->cua_id, $model->cue_id);
	
			$noTieneTipo = $model->tipo == null || $model->tipo == '';
			
			if($datosCuadro !== false){
				$tienePiso = $datosCuadro['piso'] == 1;
				$tieneFila = $datosCuadro['fila'] == 1;
				$tieneNume = $datosCuadro['nume'] == 1;
				$tieneBis = $datosCuadro['bis'] == 1;
			}
			
			if($datosCuerpo !== false){
							
				if($noTieneTipo){
					
					$model->tipo = $datosCuerpo['tipo'];
					$noTieneTipo = $model->tipo == '';
				}
			}
		
			break;
			
		//cambia el tipo
		case 3 :
			
			if($model->tipo == null) $model->tipo = '';
			$noTieneTipo = true;
					
			break;
		
		//se vuelven a recuperar los datos si es que se ha elegido un cuadro	
		default :
			
			//hay un cuadro elegido
			if($model->cua_id != null && trim($model->cua_id) != ''){
			
				$datosCuadro = $model::getCuadro($model->cua_id);
				
				if($datosCuadro !== false){
					
					$noTieneTipo = trim($model->tipo) == '';
					$tienePiso = $datosCuadro['piso'] == 1;
					$tieneFila = $datosCuadro['fila'] == 1;
					$tieneNume = $datosCuadro['nume'] == 1;
					$tieneBis = $datosCuadro['bis'] == 1;
				}
			}
	}
	
	$condCategoria = $model->tipo == null ? '' : $model->tipo;
	

	$categorias= utb::getAux('cem_tcat', 'cat', 'nombre', 0, "trim(both ' ' from tipo) = '$condCategoria' Or trim(both ' ' from tipo) = ''");
	$cuerpos= utb::getAux('cem_cuerpo', 'cue_id', 'nombre', 0, "cua_id='$model->cua_id' Or trim(both ' ' from cua_id) = ''")
	?>
	<div class="form">
	<table border="0" width="100%">
		<tr>
			<td align="left" width="100px">
				<?= $form->field($model, 'cua_id', ['template' => $templ2])->dropDownList(utb::getAux('cem_cuadro', 'cua_id'), ['onchange' => 'cambiarNomeclatura("cuadro");', 'id' => 'cemCuadro', 'prompt' => ''])->label('Cuadro') ?>
			</td>
			
			<td align="left" width="100px">
				<?= $form->field(
								$model, 
								'cue_id', 
								[
								'template' => $templ2
								])
								->dropDownList(
									$cuerpos, 
									[
									'style' => 'width:100px', 
									'prompt' => '', 
									'id' => 'cemCuerpo', 
									'disabled' => $model->cua_id === null || count($cuerpos) === 0, 
									'onchange' => 'cambiarNomeclatura("cuerpo")'
									])
								->label('Cuerpo') ?>
			</td>
			
			<td align="left" width="100px">
				<?= $form->field($model, 'tipo', ['template' => $templ2])
				->dropDownList(utb::getAux('cem_tipo'), ['width' => '100%', 'disabled' => !$noTieneTipo, 'prompt' => '', 'id' => 'cemTipo', 'onchange' => 'cambiarNomeclatura("tipo"); cambiarTipo($(this).val())'])
				->label('Tipo') ?>
				<?= $form->field($model, 'tipo')->input('hidden')->label(false) ?>
			</td>
			
			<td align="left" width="35px">
				<?= $form->field($model, 'piso', ['template' => $templ2])->textInput(['maxlength' => 3, 'style' => 'width:35px', 'readonly' => $tienePiso == false, 'id' => 'cemPiso'])->label('Piso') ?>
			</td>
			
			<td align="left" width="35px">
				<?= $form->field($model, 'fila', ['template' => $templ2])->textInput(['maxlength' => 4, 'style' => 'width:35px', 'readonly' => $tieneFila == false, 'id' => 'cemFila'])->label('Fila') ?>
			</td>
			
			<td align="left" width="35px">
				<?= $form->field($model, 'nume', ['template' => $templ2])->textInput(['maxlength' => 5, 'style' => 'width:50px', 'readonly' => $tieneNume == false, 'id' => 'cemNume'])->label('Nume') ?>
			</td>
			
			<td align="left" width="35px">
				<?= $form->field($model, 'bis', ['template' => $templ2])->textInput(['maxlength' => 1, 'style' => 'width:50px', 'readonly' => $tieneBis == false, 'id' => 'cemBis'])->label('Bis') ?>
			</td>
			
			<td align="left" width="100px">
				<?= $form->field($model, 'cat', ['template' => $templ2])->dropDownList($categorias, ['prompt' => '', 'id' => 'cemCat', 'disabled' => ($model->tipo == null || trim($model->tipo) == '')])->label('Categoria') ?>
			</td>
		</tr>
	</table>
	</div>
	<?php	
	Pjax::end();
	?>
	
	<table border="0" width="640px">
		<tr>
			<td width="100px">
				<label>Domicilio Postal:</label>
			</td>
			<td width="26px">
				
			<?php
				Modal::begin([
	                'id' => 'BuscaDomiP',
					'header' => '<h2>Búsqueda de Domicilio</h2>',
					'toggleButton' => [
	                    'label' => '<i class="glyphicon glyphicon-search"></i>',
	                    'class' => 'bt-buscar',
	                    'disabled' => ($consulta == 1 || $consulta == 2)
	                ],
	                'closeButton' => [
	                  'label' => '<b>X</b>',
	                  'class' => 'btn btn-danger btn-sm pull-right'
	                ]
	            ]);
	            
	            echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modelDomicilio,'tor' => 'OBJ']);
	            
	            Modal::end();
	            ?>
	            
	        </td>
	        
	        <td>
			<?php 
			Pjax::begin(['id' => 'CargarModeloDomi', 'enablePushState' => false, 'enableReplaceState' => false]);
			
			$modelDomicilio = isset($modelDomicilio) ? $modelDomicilio : new Domi();
			
			if(isset($_POST['tor']) && $_POST['tor'] == 'OBJ'){
				
				$modelDomicilio->torigen = 'OBJ';
	 			$modelDomicilio->obj_id = $model->obj_id;
	 			$modelDomicilio->id = 0;
				$modelDomicilio->prov_id = isset($_POST['prov_id']) ? $_POST['prov_id'] : 0;
				$modelDomicilio->loc_id = isset($_POST['loc_id']) ? $_POST['loc_id'] : 0;
	 			$modelDomicilio->cp = isset($_POST['cp']) ? $_POST['cp'] : "";
	 			$modelDomicilio->barr_id = isset($_POST['barr_id']) ? $_POST['barr_id'] : 0;
	 			$modelDomicilio->calle_id = isset($_POST['calle_id']) ? $_POST['calle_id'] : 0;
	 			$modelDomicilio->nomcalle = isset($_POST['nomcalle']) ? $_POST['nomcalle'] : "";
	 			$modelDomicilio->puerta = isset($_POST['puerta']) ? $_POST['puerta'] : "";
	 			$modelDomicilio->det = isset($_POST['det']) ? $_POST['det'] : "";
	 			$modelDomicilio->piso = isset($_POST['piso']) ? $_POST['piso'] : "";
	 			$modelDomicilio->dpto = isset($_POST['dpto']) ? $_POST['dpto'] : "";
				
//				$modelDomicilio->domicilio = $modelDomicilio->nomcalle.'  '.$modelDomicilio->puerta.($modelDomicilio->det != '' ? ' ('.$modelDomicilio->det.') ' : '').($modelDomicilio->piso != '' ? ' Piso: '.$modelDomicilio->piso : '');
//				$modelDomicilio->domicilio .= ($modelDomicilio->dpto !='' ? ' Dpto: '.$modelDomicilio->dpto : '');
//				$modelDomicilio->domicilio .= ($modelDomicilio->barr_id !='' ? ' - B°: '.utb::getCampo("domi_barrio","barr_id=".$modelDomicilio->barr_id,"nombre") : '');
				
				echo '<script>$("#domicilioPostal").val("'.$modelDomicilio->armarDescripcion().'")</script>';
 				echo '<script>$("#arrayDomicilioPostal").val("'.urlencode(serialize($modelDomicilio)).'")</script>';
			}
			
			Pjax::end();
			
			echo Html::input('text', 'domi', $modelDomicilio->domicilio, ['class' => 'form-control','id'=>'domicilioPostal','style'=>'background:#E6E6FA; width:480px;','disabled'=>'true']);
			echo '<input type="text" name="arrayDomicilioPostal" id="arrayDomicilioPostal" value="'.urlencode(serialize($modelDomicilio)).'" style="display:none">';
			?>
			</td>
		</tr>
	</table>
		
	<table border="0" width="640px">
		<tr>
			<td colspan="4">
				<?= $form->field($model, 'deleg', ['template' => $templ])->dropDownList(utb::getAux('cem_tdeleg'), ['prompt' => ''])->label('Delegación:') ?>
			</td>
			<!-- 110 -->
			
			<td><label>Superficie:</label></td>
			<td>
				<?= $form->field($model, 'sup', ['template' => $templ])->textInput(['maxlength' => 3, 'style' => 'width:50px', 'id' => 'cemSup'])->label('') ?>
			</td>
			<!-- 10 -->
			
			<!-- 140 -->
			
			<td width="150px">
				<?= $form->field($model, 'exenta', ['template' => $templ, 'options' => ['style' => 'width:150px; margin-right:0px;']])->dropDownList(utb::getAux('cem_texenta'), ['prompt' => '', 'onchange' => 'cambiarExenta($(this).val())'])->label('Exenta:') ?>
			</td>
			
			
			<td>
				<?= $form->field($model, 'edicto', ['template' => $templ])->textInput(['maxlength' => 3, 'style' => 'width:50px', 'id' => 'cemEdicto', 'readonly' => $model->exenta != 1])->label('Edicto:') ?>
			</td>
		</tr>		
		
		<tr>
			<td align="left" width="74px" colspan="2">
				<?= $form->field($model, 'tomo', ['template' => $templ])->textInput(['maxlength' => 5, 'style' => 'width:45px'])->label('Tomo:') ?>
			</td>
			
			<td align="left" width="80px">
				<?= $form->field($model, 'folio', ['template' => $templ])->textInput(['maxlength' => 5, 'style' => 'width:45px'])->label('Folio:') ?>
			</td>
			
			<td width="35px"></td>
			
			<td><label>Compra:</label></td>
			
			<td align="left">
				<?php
					if($consulta != 1){
						echo DatePicker::widget(['model' => $model, 'attribute' => 'fchcompra', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control ', 'style' => 'width:80px', 'id' => 'cemCompra']]);
					}
					else {
						if($model->fchcompra != null && $model->fchcompra != '')
							$model->fchcompra = Fecha::bdToUsuario($model->fchcompra);
						echo $form->field($model, 'fchcompra', ['template' => $templ])->textInput(['style' => 'width:80px'])->label(false);
					}
				?>
			</td>
			
		</tr>
		<tr>
			<td align="left" colspan="4">
				<?= $form->field($modelObjeto, 'tdistrib', ['template' => $templ])->dropDownList(utb::getAux('objeto_tdistrib'), ['prompt' => ''])->label('Distribución:') ?>
			</td>
			<td align="left" colspan="4">
				<?= $form->field($modelObjeto, 'distrib', ['template' => $templ])->dropDownList(utb::getAux('sam.sis_usuario', 'usr_id', 'apenom', 1, 'distrib=1'), ['prompt' => ''])->label('Distribuidor:') ?>
			</td>
		</tr>
	</table>
		
	<div class='form' style='padding-bottom:5px'>
	<table border="0" width="100%">
		<tr>
			<td>
				<label>Ingreso:</label>
				
				<?php
				if($consulta != 1 && $consulta != 2){
					echo DatePicker::widget(['model' => $model, 'attribute' => 'fchingreso', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'style' => 'width:100px']]);
				}
				else {
					if($model->fchingreso != null && $model->fchingreso != '')
						$model->fchingreso = Fecha::bdToUsuario($model->fchingreso);
					echo $form->field($model, 'fchingreso', ['options' => ['style' => 'margin:0px;']])->textInput(['style' => 'width:80px'])->label(false);
				} 
				?>
				
			</td>	
			
			<td width="5px"></td>		
			
			<td>
				<label>Venc.:</label>
				<?php
					if($consulta != 1 && $consulta != 2){
						echo DatePicker::widget(['model' => $model, 'attribute' => 'fchvenc', 'dateFormat' => 'dd/MM/yyyy', 'options' => ['class' => 'form-control', 'style' => 'width:100px']]);
					}
					else {
						if($model->fchvenc != null && $model->fchvenc != '')
							$model->fchvenc = Fecha::bdToUsuario($model->fchvenc);
							
						echo $form->field($model, 'fchvenc', ['options' => ['style' => 'margin:0px;']])->textInput(['style' => 'width:80px'])->label(false);
					} 
				?>
			</td>
			
			<td width="5px"></td>
			
			<td>
				<label>Alta:</label><br>
				
				<?php
				
				$alta = null;
				
				if($modelObjeto->fchalta != null && $modelObjeto->fchalta != '')
					$alta = Fecha::bdToUsuario($modelObjeto->fchalta);
				
				echo Html::textInput(null, $alta, ['maxlength' => 3, 'readonly' => true, 'style' => 'width:80px; background:#E6E6FA;', 'class' => 'form-control']);
				?>	
			</td>
			
			<td width="5px"></td>
			
			<td>
				<label>Baja:</label><br>
				
				<?php
				
					$baja = null;
					
					if($modelObjeto->fchbaja != null && $modelObjeto->fchbaja != '')
						$baja = Fecha::bdToUsuario($modelObjeto->fchbaja);
				
					echo Html::textInput(null, $baja, ['readonly' => true, 'style' => 'width:80px; background:#E6E6FA;', 'class' => 'form-control']);	
				?>
				
			</td>
			<td width="5px"></td>
			<td>
				<label>Modif:</label><br>
				
				<?php
				
					$modificacion = null;
					
					if($modelObjeto->usrmod != null && $modelObjeto->fchmod != null)
						$modificacion = utb::getFormatoModif($modelObjeto->usrmod, $modelObjeto->fchmod);
				
					echo Html::textInput(null, $modificacion, ['readonly' => true, 'style' => 'width:150px; background:#E6E6FA;', 'class' => 'form-control']);	
				?>				
			</td>
		</tr>
	</table>
	</div>
	<?php 
		if ($consulta==2) { 
	?>
		<p>&nbsp;</p>
		<label><u>Información sobre la Baja</u></label>
		<div style='border:1px solid #ddd;border-radius:5px;padding:5px;'>
		<table border='0'>
		<tr>
			<?= $form->field($modelObjeto, 'tbaja',['template' => $param['T_TAB_COL1']])->dropDownList(utb::getAux('objeto_tbaja', 'cod', 'nombre', 0, 'tobj=4'),['prompt'=>'Seleccionar...','onchange' => 'VariablesBaja()', 'id' => 'objeto-tbaja']) ?>
			<td width='10px'></td>
			<?= $form->field($modelObjeto, 'elimobjcondeuda',['template' => $param['T_TAB_COL1']])->checkbox(['onchange' => 'VariablesBaja()', 'id' => 'objeto-elimobjcondeuda', 'uncheck' => 0]) ?>
		</tr>
		</table>
		</div>
	<?php } ?>
</div>

<?php
$tab = $consulta == 2 ? 6 : 1;
?>

<div style="width:640px;">
	<table border="0" width="640px">
		<tr>
			<td width="620px">
			<?= Tabs::widget([
				'items' => [
					['label' => 'Titulares', 'content' => $this->render('_titulares', ['model' => $model, 'modelObjeto' => $modelObjeto, 'consulta' => $consulta, 'extras' => $extras, 'form' => $form]), 'options' => ['class' => 'tabItem'], 'active' => $tab == 1],
					['label' => 'Fallecidos', 'content' => $this->render('fallecidos', ['extras' => $extras,'consulta' => $consulta]), 'options' => ['class' => 'tabItem'], 'active' => $tab == 2],
					['label' => 'Alquiler', 'content' => $this->render('_alquileres', ['extras' => $extras]), 'options' => ['class' => 'tabItem'], 'active' => $tab == 3],
					['label' => 'Asignaciones de Items' , 'content' => $this->render('//objeto/objetoasignacioneslist',['modelobjeto' => $modelObjeto]), 'options' => ['class'=>'tabItem'], 'active' => $tab == 4],
					['label' => 'Acciones' , 'content' => $this->render('//objeto/objetoaccioneslist',['modelobjeto' => $modelObjeto]), 'options' => ['class'=>'tabItem'], 'active' => $tab == 5],
					['label' => 'Observaciones' , 
					'content' => $form->field($modelObjeto, 'obs',['template' => $param['T_DIV']])->textarea(['maxlength' => 1000,'style' => 'width:600px;height:100px; max-width:600px; max-height:150px;','onblur' => 'VariablesBaja()']),
					'options' => ['class'=>'tabItem'],
					'active' => $tab == 6]
				],
			]);
			?>
			</td>
		</tr>
	</table>
</div>	

<?php
 	if ($consulta !== 1){ 
 ?>  
	<div class="form-group" style='margin-top:10px'>
		<?php 
			$elimobjcondeuda = (isset($_GET['elimobjcondeuda'])) ? $_GET['elimobjcondeuda'] : 0;
			$tbaja = (isset($_GET['tbaja'])) ? $_GET['tbaja'] : 0;
			$obs = (isset($_GET['obs'])) ? $_GET['obs'] : '';
				
			Pjax::begin(['id' => 'btOpciones']);
			
			if ($consulta==2){   
				
				
				if (isset($_POST['elimobjcondeuda'])) $elimobjcondeuda = $_POST['elimobjcondeuda'];
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
										'id' => $model->obj_id,
										'accion'=>1, 
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
      		
     		 }else {   
				echo Html::submitButton('Grabar', ['class' => 'btn btn-success']);
			 }
			 
			 echo "&nbsp;&nbsp;";
			 echo Html::a('Cancelar', ['view', 'id' => $model->obj_id], [
            			'class' => 'btn btn-primary',
        			]);
        	Pjax::end();
		?> 
	</div>
	
<?php
}
?>	

	
<?php
	ActiveForm::end();
	?>

<?php 
	
	if(isset($error) && is_array($error) && count($error) > 0)
	{  
		
		echo '<div class="error-summary" style="width:640px;">Por favor corrija los siguientes errores:<br/><ul>';
		//var_dump($error);
		foreach($error as $e)
			echo "<li>$e</li>";
		//var_dump($error);
		echo '</ul></div>';
		
		
	} 
	
	//echo $form->errorSummary([$model, $modelObjeto, $modelDomicilio]); 
	
	if ($consulta==1 || $consulta==2) 
    	{
    		echo "<script>";
			echo "DesactivarFormPost('formCem');";
			echo "</script>";
    	} 
    	
	if ($consulta==2){
    ?>
    	<script type="text/javascript">
    		$("#objeto-elimobjcondeuda").prop("disabled", false);
    		$("#objeto-tbaja").prop("disabled", false);
    		$("#objeto-obs").prop("readonly", false);
    		$("#objeto-obs").prop("disabled", false);
    		$("#objeto-obs").val("<?=$obs?>");
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
</script>
</div>