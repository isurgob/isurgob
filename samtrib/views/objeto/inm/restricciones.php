<?php
use yii\helpers\Html;
use yii\grid\GridView;
use app\utils\db\utb;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use \yii\widgets\Pjax;
use yii\bootstrap\Alert;

$title = 'Restricciones ';
$this->params['breadcrumbs'][] = ['label' => 'Objeto: '.$id, 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = $title;

if (isset($consulta) == null) $consulta = 1;

?>
<div class="inm-restricciones">
	<table border='0' width='100%'>
	    <tr>
	    	<td><h1><?= Html::encode($title) ?></h1></td>
	    	<td align='right'>
	    	<?php
	    		if (utb::getExisteProceso(3071))
	    			echo Html::button('Nueva Restricción', ['class' => 'btn btn-success', 'id' => 'restriccion-btNuevaRestriccion', 'onclick' => 'btNuevaRestriccionClick();']) 
			?>
			</td>
	    </tr>
	</table>
	
	<div class="separador-horizontal"></div>
	
	<table border='0' width='100%' style='margin-top:8px'>
		<tr>
	    	<td colspan='2'>
	    	<?php
	    		
	    		if (isset($mensaje) == null) $mensaje = '';
	    		Alert::begin([
	    			'id' => 'AlertaRestriccion',
					'options' => [
	        		'class' => 'alert-success',
	        		'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
	    			],
				]);
	
				if ($mensaje !== '') echo $mensaje;
				
				Alert::end();
					
				if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaRestriccion').alert('close'); }, 5000)</script>";
	    			
	    	?>
	    	</td>
	    </tr>
	</table>
	
	<p>
		<label>Objeto: </label><input class='form-control' style='width:65px;background:#E6E6FA' disabled value='<?= $id ?>' />
		<label>Nombre: </label><input class='form-control' style='width:300px;background:#E6E6FA' disabled value='<?=utb::getNombObj("'".$id."'")?>' />
	</p>
	
		<?php
		
		echo GridView::widget([
			'id' => 'GrillaRestriccion',
			'dataProvider' => $dataProvider,
			'rowOptions' => function ($model,$key,$index,$grid) 
							{
								return [
									'onclick' => 'CargarControlesRestriccion("'.$model['orden'].'","'.Html::encode($model['sup']).'","'.Html::encode($model['inscrip']).'","'.Html::encode($model['obs']).'",'. $model['trestric'].')'
								];
							},
			'columns' => [
            		['attribute'=>'orden','header' => 'Orden', 'options'=>['style'=>'width:50px']],
            		['attribute'=>'trestric_nom','header' => 'Tipo', 'options'=>['style'=>'width:170px']],
            		['attribute'=>'sup','header' => 'Sup.'],
            		['attribute'=>'inscrip','header' => 'Inscrip.'],
            		['attribute'=>'modif','header' => 'Modificación', 'options'=>['style'=>'width:170px']],
            		
            		['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:50px'],
            			'buttons'=>[
            				'view' => function ()
            						{
            							return null;
            						},
							'update' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
														'class' => 'bt-buscar-label',
														'style' => 'display:'.(utb::getExisteProceso(3071) ? 'block' : 'none'),
														'onclick' => 'btModifRestriccionClick("'.$model['orden'].'","'.$model['sup'].'","'.$model['inscrip'].'","'.$model['obs'].'",'. $model['trestric'] .')',
													]
            									);
            						},
            				'delete' => function($url,$model,$key)
            						{
            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
            										[
														'class' => 'bt-buscar-label',
														'style' => 'display:'.(utb::getExisteProceso(3071) ? 'block' : 'none'),
														'onclick' => 'btElimRestriccionClick("'.$model['orden'].'")',
													]
            									);
            						}
            			]
            	],
        	],
    	]); 
    	
    	$form = ActiveForm::begin(['action' => ['restriccionesabm', 'id' => $id],'id'=>'frmRestriccion']);
    	
    	echo Html::input('hidden','txAccion', null, ['id'=>'txAccion']);
    	echo Html::input('hidden', 'txOrden', null, ['id'=>'txOrden']);
    	
	?>

<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>
	<table border='0'>
	<tr>
		<td width="40px" align="right"><label>Orden: </label></td>
		<td>
			<?= Html::input('text', 'restriccion-orden', null, ['class' => 'form-control','id'=>'restriccion-orden','style'=>'width:80px;background:#E6E6FA', 'disabled' => true]); ?>
		</td>
		<td width='10px'></td>
		<td><label>Tipo Restricción: </label></td>
		<td>
			<?= Html::dropDownList('restriccion-tipoRestriccion',  null, utb::getAux('inm_trestric'), [
					'class' => 'form-control',
					'id'=>'restriccion-tipoRestriccion',
					'disabled' => true,
				]); 
			?>
		</td>
	</tr>

	<tr>
		<td width="40px" align="right"><label>Sup.: </label></td>
		<td><?= Html::input('text', 'restriccion-sup', null, [
					'class' => 'form-control',
					'id'=>'restriccion-sup',
					'style'=>'width:80px;',
					'disabled' => true,
					'maxlength' => '12',
					'onkeypress' => 'return justDecimal(this.value, event)',
				]); 
			?>
		</td>
		<td width='10px'></td>
		<td align="right"><label>Inscrip.: </label></td>
		<td><?= Html::input('text', 'restriccion-inscrip', null, ['class' => 'form-control','id'=>'restriccion-inscrip','style'=>'width:80px;', 'disabled' => true]); ?></td>
	</tr>
</table>
<table>
	<tr>
		<td valign="top" width="40px" align="right"><label>Obs.: </label></td>
		<td>
			<?= Html::textarea('restriccion-detalle', null, [
					'class' => 'form-control',
					'id'=>'restriccion-detalle',
					'maxlength'=>'500',
					'style'=>'width:680px; height:100px; resize: none',
					'disabled' => 'disabled',
				]);
			?>
		</td>
	</tr>
</table>	
</div>
	<?php 
		
		Pjax::begin(['id' => 'btRestriccion']);
		
		if (isset($_POST['consulta'])) $consulta = $_POST['consulta'];
		 
		if ($consulta != 1)
		{
			if (utb::getExisteProceso(3071)) {
				echo Html::Button('Grabar', ['class' => ($consulta == 2 ? 'btn btn-danger' : 'btn btn-success'), 'onclick' => 'btGrabarClick()']); 
				echo "&nbsp;&nbsp;";
				echo Html::Button('Cancelar', ['class' => 'btn btn-primary','onclick' => 'CancelarNuevaRestriccion()']);
			}
		}
		Pjax::end();
    ?>
		
	<div id="inm_restricciones_errorSummary" class="error-summary" style="display:none; margin-top: 8px">
		
		<ul>
		</ul>
	
	</div>
	
	<?php 
		
		ActiveForm::end(); 
		
	?>
</div>

<script>

function btNuevaRestriccionClick()
{
	$("#txAccion").val("0");
	$("#restriccion-orden").val("");
	$("#txOrden").val("");
	$("#restriccion-sup").val("");
	$("#restriccion-inscrip").val("");
	$("#restriccion-detalle").val("");
	
	 
	ActivarControlesRestriccion(0);
	
	$.pjax.reload(
	{
		container:"#btRestriccion",
		data:{
				consulta:0
			},
		method:"POST"
	});
}

function btModifRestriccionClick(orden,sup,inscrip,detalle,tipo)
{
	$("#txAccion").val("3");
	$("#restriccion-orden").val(orden);
	$("#txOrden").val(orden);
	$("#restriccion-sup").val(sup);
	$("#restriccion-inscrip").val(inscrip);
	$("#restriccion-detalle").val(detalle);
	$("#restriccion-tipoRestriccion").val(tipo);
	 
	ActivarControlesRestriccion(3);
	
	$.pjax.reload(
		{
			container:"#btRestriccion",
			data:{
					consulta:3
				},
			method:"POST"
		});
}

function btElimRestriccionClick(orden)
{
	$("#txAccion").val("2");
	$("#txOrden").val(orden);
	
	ActivarControlesRestriccion(2);
	
	$.pjax.reload(
		{
			container:"#btRestriccion",
			data:{
					consulta:2
				},
			method:"POST"
		});
}

function btGrabarClick()
{
	error = new Array();
	
	if ( $("#restriccion-sup").val() == "" )
	{
		error.push( "Ingrese una Superficie" );
	}
	if ($("#restriccion-detalle").val()=="")
	{
		error.push( "Ingrese un Detalle" );
	}
	
	if ( error.length == 0 )
	{
		$("#frmRestriccion").submit();
	
	} else {
		mostrarErrores( error, "#inm_restricciones_errorSummary");
	}
}

function CargarControlesRestriccion(orden,sup,inscrip,detalle,tipo)
{
	$("#restriccion-orden").val(orden);
	$("#txOrden").val(orden);
	$("#restriccion-sup").val(sup);
	$("#restriccion-inscrip").val(inscrip);
	$("#restriccion-detalle").val(detalle);
	$("#restriccion-tipoRestriccion").val(tipo);
}

function CancelarNuevaRestriccion()
{
	ActivarControlesRestriccion(1);
	
	$.pjax.reload(
		{
			container:"#btRestriccion",
			data:{
					consulta:1
				},
			method:"POST"
		});
}

function ActivarControlesRestriccion(accion)
{
	$("#restriccion-sup").prop("disabled",(accion==1 || accion == 2));
	$("#restriccion-inscrip").prop("disabled",(accion==1 || accion == 2));
	$("#restriccion-detalle").prop("disabled",(accion==1 || accion == 2));
	$("#restriccion-tipoRestriccion").prop("disabled",(accion==1 || accion == 2));
	$("#restriccion-btNuevaRestriccion").prop("disabled",(accion!==1 || accion == 2));
	
	if (accion !== 1)
	{
		$("#restriccion-tipoRestriccion").removeAttr("readOnly");
		$("#GrillaRestriccion").css("pointer-events", "none");	
		$("#GrillaRestriccion Button").css("color", "#ccc");
	}else {
		$("#GrillaRestriccion").css("pointer-events", "all");
		$("#GrillaRestriccion Button").css("color", "#337ab7");	
	}
	
}

$( document ).on( "pjax:start", function() {
	
	$( "#inm_restricciones_errorSummar").css( "display", "none" );
	
});

</script>

<?= "<script>ActivarControlesRestriccion(".$consulta.")</script>"; ?>