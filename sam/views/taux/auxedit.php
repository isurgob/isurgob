<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\models\taux\tablaAux;
use app\utils\db\utb;

$title = $model->titulo;
if ($model->cod == 139)
	$this->params['breadcrumbs'][] = ['label' => 'Configuración', 'url' => Yii::$app->param->sis_url.'site/config'];
else	
	$this->params['breadcrumbs'][] = ['label' => 'Tablas Auxiliares', 'url' => Yii::$app->param->sis_url.'site/taux'];
$this->params['breadcrumbs'][] = $title;

/* @var $this yii\web\View */
/* @var $model app\models\tablaAux */
/* @var $form ActiveForm */

$anchocod = '50px';
$anchonom = '250px';
$anchoterccampo = '100px';
$mostrarmodif = 1;
$mostrarcontrolesedit = 1;
$controltercercampo = 'txbox';

if ($model->cod == 67)
{
	$anchocod = '30px';
	$anchonom = '100px';
	$anchoterccampo = '300px';
	$mostrarmodif = 0;
	$mostrarcontrolesedit = 0;
}

if ($model->cod == 65)
{
	$anchocod = '30px';
	$anchonom = '30px';
	$anchoterccampo = '370px';
	$mostrarmodif = 0;
	$controltercercampo = 'txarea';
}

if (isset($consulta) == null) $consulta = 1;

if (substr($model->tercercampotipo,0,7) === 'numeric')
	$keyPrest = 'return justDecimalAndMenos(event,$(this).val())';
elseif (strpos($model->tercercampotipo, 'var') != '') 
	$keyPrest = '';
else 
	$keyPrest = 'return justNumbers(event);';	
?>
<div class="site-auxedit">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($model->titulo) ?></h1></td>
    	<td align='right'>
    		<?php 
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btNuevo', 'onclick' => 'btNuevoClick();']) 
    		?>
    	</td>
    </tr>
    <tr style='border-top:1px solid #ddd;'>
    	<td style="padding: 20px 0px;" colspan='2'>
    		<label>Filtar por Nombre</label>
    		<?php echo Html::input('text', 'txFiltroNombre', null, ['class' => 'form-control','id'=>'txFiltroNombre','onchange'=>'txFiltroChange()', 'maxlength'=> '25','style'=>'width:550px']);  ?>
		</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>
    <?php 
		Pjax::begin(['id'=>'PjaxBuscarTAux']);
			if (!isset($_GET['page'])) Yii::$app->session['cond'] = '';
			
			$cond = (isset($_POST['condicion']) ? $_POST['condicion'] : Yii::$app->session['cond']);
			
			if ($cond != ''){
				Yii::$app->session['cond'] = $cond;
				
				$tabla = null;
		        $tabla = (new tablaAux())->CargarTabla($model->nombre, $model->tercercamponom,$cond);
		         	
			}
		
			echo GridView::widget([
				'id' => 'GrillaTablaAux',
				'dataProvider' => $tabla,
				'sorter' => 'false',
				'headerRowOptions' => ['class' => 'grillaGrande'],
				'rowOptions' => function ($model,$key,$index,$grid) 
								{
									return [
										'onclick' => 'CargarControles("'.$model['cod'].'","'.$model['nombre'].'","'.$model['tercercampo'].'","'.$model['modif'].'")'
									];
								},
				'columns' => [
	            		['attribute'=>'cod','label' => 'Cod', 'contentOptions'=>['style'=>'width:'.$anchocod.';text-align:right', 'class' => 'grillaGrande']],
	            		['attribute'=>'nombre','label' => 'Nombre', 'contentOptions'=>['style'=>'width:'.$anchonom, 'class' => 'grillaGrande']],
	            		['attribute'=> $model->tercercamponom,'header' => ucwords(strtolower($model->tercercampodesc)), 'contentOptions'=>['style'=>'width:'.$anchoterccampo.';'.($model->tercercamponom == "" ? 'display:none' : ''), 'class' => 'grillaGrande'],'headerOptions' => ['style' => ($model->tercercamponom == "" ? 'display:none' : 'display:block')]],
	            		['attribute'=>'modif','header' => 'Modificación', 'contentOptions'=>['style'=>'width:100px;', 'class' => 'grillaGrande']],
	            		
	            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:20px; padding:1px 10px'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
	            			'buttons'=>[
								'update' => function($url,$model,$key)
	            						{
	            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
	            										[
															'class' => 'bt-buscar-label', 'style' => 'color:#337ab7;font-size:9px',
															'onclick' => 'CargarControles("'.$model['cod'].'","'.$model['nombre'].'","'.$model['tercercampo'].'","'.$model['modif'].'");' .
																	'ActivarControles(3);'
														]
	            									);
	            						},
	            				'delete' => function($url,$model,$key)
	            						{
	            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
	            										[
															'class' => 'bt-buscar-label', 'style' => 'color:#337ab7;font-size:9px',
															'onclick' => 'CargarControles("'.$model['cod'].'","'.$model['nombre'].'","'.$model['tercercampo'].'","'.$model['modif'].'");' .
																	'ActivarControles(2);'
														]
	            									);
	            						}
	            			]
	            	   ],
	            	   
	        	],
	        	'pager' => [
	    				'options' => ['style' => 'padding:0px;', 'class' => 'pagination']
	    			]
	    	]); 
    	Pjax::end();
    	
    ?>
    
    <div style="margin-top:5px;">
    <?php
	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmTAux']);
	 	
	 	echo Html::input('hidden', 'txAccion', $consulta, ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);
	 ?>
	<div class="form" style='padding:15px 5px; margin:5px 0px;display:<?=($mostrarcontrolesedit == 1 ? 'block' : 'none')?>'>
		<table border='0'>
		<tr>
			<td><label>Código: </label></td>
			<td>
				<?= Html::input('text', 'txCod', $cod, [
						'class' => 'form-control',
						'id'=>'txCod','maxlength'=> $model->codlong,
						'style'=>'width:60px;', 
						'readonly' => true,
						'onkeypress' => ($model->tcod != 'N' ? '' : 'return justNumbers(event);')
					]); 
				?>
			</td>
			<td width='10px'></td>
			<td><label>Nombre: </label></td>
			<td>
				<?= Html::input('text', 'txNombre', $nombre, ['class' => 'form-control','id'=>'txNombre','maxlength'=> $model->nombrelong,'style'=>'width:550px', 'disabled' => true]); ?>
			</td>
		</tr>
		<?php if ($model->tercercamponom != "") {?>
			<tr>
				<td colspan='3'></td>
				<td valign='top'><label><?= ucwords(strtolower($model->tercercampodesc)) ?>: </label></td>
				<td>
				<?php 
					if ($controltercercampo == 'txbox')
						echo Html::input('text', 'txTercerCampo', $tercercampo, ['class' => 'form-control','id'=>'txTercerCampo',
										'maxlength'=> $model->tercercampolong,
										'style' => (strpos($model->tercercampotipo, 'var') != '' ? 'width:550px' : 'width:80px; text-align:right'), 
										'onkeypress' => $keyPrest,
										'disabled' => true]);
					
					elseif ($controltercercampo == 'txarea')
						echo Html::textarea('txTercerCampo', $tercercampo, ['class' => 'form-control','id'=>'txTercerCampo',
										'maxlength'=> $model->tercercampolong,
										'style' => 'width:550px;height:80px;resize:none',
										'disabled' => true]);  
				?>
			</td>
			</tr>
		<?php 
			}
			
			if ($model->accesoedita > 0) { 
		?>	
		<tr>
			<td colspan='5' align='right'>
				<br>
				<label>Modificación: </label>
				<?= Html::input('text', 'txModif', null, ['class' => 'form-control','id'=>'txModif','style'=>'width:150px;background:#E6E6FA', 'disabled' => true]); ?>
			</td>
		</tr>
		<?php } ?>
		</table>
        <div class="form-group">
            <?php 
		
		Pjax::begin(['id' => 'btTAux']);
		
		if (isset($_POST['consulta'])) $consulta = $_POST['consulta'];
		 
		if ($consulta !== 1 and $model->accesoedita > 0)
		{
			echo Html::Button(($consulta == 2 ? 'Eliminar' : 'Grabar'), ['class' => ($consulta == 2 ? 'btn btn-danger' : 'btn btn-success'), 'onclick' => 'btGrabarClick()']); 
			echo "&nbsp;&nbsp;";
			echo Html::Button('Cancelar', ['class' => 'btn btn-primary', 'onclick' => 'location.reload();']); 
		}
		Pjax::end();
    ?>
        </div>
    </div>
    <?php 
    	Pjax::begin(['id' => 'divError']);
		
		if (isset($_POST['err'])) $error = $_POST['err'];
		
		if(isset($error) and $error !== '')
		{  
			echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
		} 
		Pjax::end();
    	
    	ActiveForm::end(); 
    ?>
    </div>

</div><!-- site-auxedit -->

<script>
function ActivarControles(accion)
{
	$("#txCod").prop("readonly",(accion!==0 || <?= $model->autoinc ?> == 1));
	$("#txNombre").prop("disabled",(accion==1 || accion==2));
	$("#txTercerCampo").prop("disabled",(accion==1 || accion==2));
	$("#txAccion").val(accion);
	$("#btNuevo").prop("disabled",(accion!==1));
	if (accion !== 1)
	{
		$("#GrillaTablaAux").css("pointer-events", "none");	
		$("#GrillaTablaAux Button").css("color", "#ccc");
	}else {
		$("#GrillaTablaAux").css("pointer-events", "all");
		$("#GrillaTablaAux Button").css("color", "#337ab7");	
	}
	
	mostrarmod = <?= $mostrarmodif ?>;
	
	if (mostrarmod == 0){
		$('#GrillaTablaAux table thead tr').find("th").eq(3).toggle();
		$('#GrillaTablaAux table tbody tr').each(function() {
			$(this).find("td").eq(3).toggle();
		});
	} 
	
	
	$.pjax.reload(
		{
			container:"#btTAux",
			data:{
					consulta:accion
				},
			method:"POST"
		});
}

function CargarControles(cod,nombre,tercercampo,modif)
{
	$("#txCod").val(cod);
	$("#txNombre").val(nombre);
	$("#txTercerCampo").val(tercercampo);
	$("#txModif").val(modif);
	
	$('html, body').animate({scrollTop: $(".site-auxedit").height()}, 1000); //mueve escroll a la parte de los datos
}

function btNuevoClick()
{
	$("#txCod").val("");
	$("#txNombre").val("");
	$("#txTercerCampo").val("");
	$("#txModif").val("");
	
	ActivarControles(0);
	$('html, body').animate({scrollTop: $(".site-auxedit").height()}, 1000); //mueve escroll a la parte de los datos
}

function btGrabarClick()
{
	error = "";
	
	if (<?= $model->autoinc ?> == 0 && $("#txCod").val()=="")
	{
		error += "<li>Ingrese un Código</li>";
	}
	if ($("#txNombre").val()=="")
	{
		error += "<li>Ingrese un Nombre</li>";
	}
	
	if (error == "")
	{
		$("#frmTAux").submit();
	}else {
		$.pjax.reload(
		{
			container:"#divError",
			data:{
					err:error
				},
			method:"POST"
		});
	}
}

function txFiltroChange()
{
	cond = '';
	if ($("#txFiltroNombre").val() != '') cond = "upper(t.nombre) like upper('%"+$("#txFiltroNombre").val()+"%')";
	
	$.pjax.reload(
	{
		container:"#PjaxBuscarTAux",
		data:{
				condicion:cond
			},
		method:"POST"
	});	
}
</script>

<?= "<script>ActivarControles(".$consulta.")</script>"; ?>