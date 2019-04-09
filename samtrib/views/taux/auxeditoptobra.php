<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\utils\db\utb;

$title = $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Tablas Auxiliares','url' => ['taux']];
$this->params['breadcrumbs'][] = $title;

/* @var $this yii\web\View */
/* @var $model app\models\tablaAux */
/* @var $form ActiveForm */

if (isset($consulta) == null) $consulta = 1;
?>
<div class="site-auxedit">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($this->title) ?></h1></td>
    	<td align='right'>
    		<?php 
    			if ($model->accesoedita > 0 and utb::getExisteProceso($model->accesoedita)) echo Html::button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btNuevo', 'onclick' => 'btNuevoClick();']) 
    		?>
    	</td>
    </tr>
    <tr>
    	<td colspan='2'></td>
    </tr>
    </table>
    <?php
		echo GridView::widget([
			'id' => 'GrillaTablaAux',
			'dataProvider' => $tabla,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => function ($model,$key,$index,$grid) 
							{
								return [
									'onclick' => 'CargarControles("'.$model['cod'].'","'.$model['nombre'].'","'.$model['fchfin'].'","'.$model['modif'].'")'
								];
							},
			'columns' => [
            		['attribute'=>'cod','header' => 'Cod', 'contentOptions'=>['style'=>'width:50px;text-align:right', 'class' => 'grilla']],
            		['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['style'=>'width:250px', 'class' => 'grilla']],
            		['attribute'=> 'fchfin', 'value' => function($data) { return ($data['fchfin'] == 1 ? 'SI' : 'NO'); },
            					   'header' => 'Fecha Fin', 'contentOptions'=>['style'=>'width:50px;text-align:center', 'class' => 'grilla']],
            		['attribute'=>'modif','header' => 'Modificación', 'contentOptions'=>['style'=>'width:150px', 'class' => 'grilla']],
            		
            		 
            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:20px;padding:1px 10px'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
            			'buttons'=>[
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7;font-size:9px',
														'onclick' => 'CargarControles("'.$model['cod'].'","'.$model['nombre'].'","'.$model['fchfin'].'","'.$model['modif'].'");' .
																'ActivarControles(3);'
													]
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label', 'style' => 'color:#337ab7;font-size:9px',
														'onclick' => 'CargarControles("'.$model['cod'].'","'.$model['nombre'].'","'.$model['fchfin'].'","'.$model['modif'].'");' .
																'ActivarControles(2);'
													]
            									);
            						}
            			]
            	   ],
            	   
        	],
    	]); 
    	
	 	$form = ActiveForm::begin(['action' => ['auxedit', 't' => $model->cod],'id'=>'frmTAux']);
	 	
	 	echo Html::input('hidden', 'txAccion', $consulta, ['id'=>'txAccion']);
	 	echo Html::input('hidden', 'txAutoInc', $model->autoinc, ['id'=>'txAutoInc']);
	 ?>
	<div class="form" style='padding:15px 5px; margin-bottom:5px'>
		<table border='0'>
		<tr>
			<td><label>Código: </label></td>
			<td>
				<?= Html::input('text', 'txCod', $cod, ['class' => 'form-control','id'=>'txCod','maxlength'=> '4','style'=>'width:40px;', 'readonly' => true]); ?>
			</td>
			<td width='10px'></td>
			<td><label>Nombre: </label></td>
			<td>
				<?= Html::input('text', 'txNombre', $nombre, ['class' => 'form-control','id'=>'txNombre','maxlength'=> '25','style'=>'width:550px', 'disabled' => true]); ?>
			</td>
		</tr>
		<tr>
			<td colspan='3'></td>
			<td>
				<?= Html::checkbox('ckFchFin',false,['id'=>'ckFchFin','label'=>'Fecha Fin'])?>
			</td>
		</tr>
		<tr>
			<td colspan='5' align='right'>
				<br>
				<label>Modificación: </label>
				<?= Html::input('text', 'txModif', null, ['class' => 'form-control','id'=>'txModif','style'=>'width:150px;background:#E6E6FA', 'disabled' => true]); ?>
			</td>
		</tr>
		</table>
        <div class="form-group">
            <?php 
		
		Pjax::begin(['id' => 'btTAux']);
		
		if (isset($_POST['consulta'])) $consulta = $_POST['consulta'];
		 
		if ($consulta !== 1 and $model->accesoedita > 0)
		{
			echo Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick' => 'btGrabarClick()']); 
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
    	

</div><!-- site-auxedit -->

<script>
function ActivarControles(accion)
{
	$("#txCod").prop("readonly",(accion!==0 || <?= $model->autoinc ?> == 1));
	$("#txNombre").prop("disabled",(accion==1 || accion==2));
	$("#ckFchFin").prop("disabled",(accion==1 || accion==2));
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
	
	$.pjax.reload(
		{
			container:"#btTAux",
			data:{
					consulta:accion
				},
			method:"POST"
		});
}

function CargarControles(cod,nombre,fchfin,modif)
{
	$("#txCod").val(cod);
	$("#txNombre").val(nombre);
	$("#ckFchFin").prop("checked",(fchfin == 1));
	$("#txModif").val(modif);
}

function btNuevoClick()
{
	$("#txCod").val("");
	$("#txNombre").val("");
	$("#ckFchFin").prop("checked",false);
	$("#txModif").val("");
	
	ActivarControles(0);
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
</script>

<?= "<script>ActivarControles(".$consulta.")</script>"; ?>