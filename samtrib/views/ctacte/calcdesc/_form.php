<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\grid\GridView;
use app\utils\db\utb;

/* @var $this yii\web\View */
/* @var $model app\models\ctacte\CalcDesc */
/* @var $form yii\widgets\ActiveForm */

?>

<style type="text/css">
div.row
{
	height : auto;
	min-height : 17px;
}

.form {
	
	padding-top: 8px;
	padding-bottom: 8px;
}

</style>

    <?php 
	    $form = ActiveForm::begin([
			'id'=>'form-calcdesc', 
		    'fieldConfig' => [
		        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>",
		        'labelOptions' => ['class' => 'col-lg-2 control-label'],
		        ]] 
	    ); 
							
		$param = Yii::$app->params; 
	?>
	
<div id="_form">

<div class="form">

<table width="100%">
	<tr>
		<td width="5%"><label>Tributo:</label> </td>
		<td>
			<!--
			<select style="width:350px;" class="form-control">
				<option selected="selected">Seleccionar...</option>
			</select>
			-->
			<?= $form->field($model, 'trib_id', ['template' => $param['T_TAB_COL1_3']])->dropDownList($model->getTrib(),
					['prompt'=>'Seleccionar...',  'style' => 'width:95%;','onchange' => 'SelectTrib()'])->label( false ); 
			?>
		</td>
	</tr>
	<tr>	
		<td colspan="4">
			<label>Item sobre el cual aplicar descuento:</label>
			<?php 
				Pjax::begin(['id' => 'PjaxItem']);
					$model->trib_id = isset($_POST['trib']) ? $_POST['trib'] : $model->trib_id;
					echo Html::dropDownList('CalcDesc[item_id]',$model->item_id,utb::getAux('item','item_id','nombre',2,'tipo=1 and trib_id='.$model->trib_id),[
						'id'=>'dlItem',
						'class'=>'form-control',
						'style'=>'width:95%;'
					]);
				Pjax::end();
			?>
		</td>
	</tr>
</table>

	<table border='0'>
		<tr>
			<?= $form->field($model, 'anual',['template' => $param['T_TAB_COL1']])->checkbox(['onchange' => 'anualActive()']); ?>
			<td width='170'></td>
			<td align='center'><label>Año/Cuota desde: </label></td>
			<?=	$form->field($model, 'aniodesde',['template' => $param['T_TAB_COL1']])->textInput(['style' => 'width:40px;', 'maxlength' => '4'])->label( false ); ?>
			<?= $form->field($model, 'cuotadesde',['template' => $param['T_TAB_COL1']])->textInput(['style' => 'width:35px;', 'maxlength' => '3'])->label( false ); ?>
			<td width='63'></td>
			<?= $form->field($model, 'aniohasta', ['template' => $param['T_TAB_COL1']])->textInput(['style' => 'width:40px;', 'maxlength' => '4']); ?>
			<?= $form->field($model, 'cuotahasta', ['template' => $param['T_TAB_COL1']])->textInput(['style' => 'width:35px;', 'maxlength' => '3'])->label( false ); ?>

		</tr>
	</table>
	
	<table border='0'>
		<tr>
			<?= $form->field($model, 'aplicavenc',['template' => $param['T_TAB_COL1']])->checkbox(['onchange' => 'aplicaVenc()']); ?>
			<td width='88'></td>
			<td><label>Pago Desde: </label></td>
			<td width='32'></td>
			<?= $form->field($model, 'pagodesde',['template' => $param['T_TAB_COL1']])->widget(DatePicker::classname(), [
					'dateFormat' => 'dd/MM/yyyy',
					 'value' => $model->pagodesde,
					 'options' => [ 
						'class'=>'form-control',
						'style' => 'width: 80px; text-align: center',
					],
				])->label( false );?>
			<td width='73'></td>
			<td><label>Hasta: </label></td>
			<td width='16'></td>
	
			<?= $form->field($model, 'pagohasta',['template' => $param['T_TAB_COL1']])->widget(DatePicker::classname(), [
					'dateFormat' => 'dd/MM/yyyy',
					'value' => $model->pagohasta,
					'options' => [
						'class'=>'form-control',
						'style' => 'width: 80px; text-align: center',
					],
				])->label( false );?>
		</tr>
	</table>
	
	<table border='0'>		
		<tr>
			<td><label>Monto Desde:</label></td>
			<td width='10'></td>
			<?= $form->field($model, 'montodesde', ['template' => $param['T_TAB_COL1']])->textInput([
					'style' => 'width:100px;text-align: right',
					'maxlength' => 10,
					'onkeypress' => 'return justDecimal( $(this).val(), event )',
				])->label( false );
			?>	 
			<td width='21'></td>
			<td><label>Hasta:</label></td>
			<td width='48'></td>
			<?= $form->field($model, 'montohasta', ['template' => $param['T_TAB_COL1']])->textInput([
					'style' => 'width:100px;text-align: right',
					'maxlength' => 10,
					'onkeypress' => 'return justDecimal( $(this).val(), event )',
				])->label( false );
			?>		
			<td width='72'></td>
			<?= $form->field($model, 'verificadeuda', ['template' => $param['T_TAB_COL1']])->checkbox(['onchange' => 'verificaDeuda()' , 'value' => 1]);  ?>
			<td width='3'></td>
			<?php
			if (isset($model->verificaDeuda) && $model->verificaDeuda == 0) {
				
				echo $form->field($model, 'existedeuda', ['template' => $param['T_TAB_COL1']])->dropDownList(['0'=>'Buen Pagador','1'=>'Todo Pago','2'=>'Con Deuda'],['style' => 'width:120px;'])->label( false );
			} else {
				echo $form->field($model, 'existedeuda', ['template' => $param['T_TAB_COL1']])->dropDownList(['0'=>'Buen Pagador','1'=>'Todo Pago','2'=>'Con Deuda'],['style' => 'width:120px;'])->label( false );   
			}	
				?></td></td>
		
		</tr>
	</table>
	
	<table border='0'>
		<tr>
			<?= $form->field($model, 'verificadebito', ['template' => $param['T_TAB_COL1']])->checkbox();  ?>
			<td width='107'></td>
			<td><label>Desc. 1er. Vencimiento </label></td>
			<td width='4 px'></td>
			<?= $form->field($model, 'desc1', ['template' => $param['T_TAB_COL1']])->textInput([
					'style' => 'width:50px;',
					'maxlength' => 5,
					'onkeypress' => 'return justDecimal( $(this).val(), event )',
				])->label( false );
			?>	
			<td width='15' align='right'><label>%</label></td>
			<td width='57'></td>
			<td><label>Desc. 2do. Vencimiento </label></td>
			<td width='4 px'></td>
			<?= $form->field($model, 'desc2', ['template' => $param['T_TAB_COL1']])->textInput([
					'style' => 'width:50px;',
					'maxlength' => 5,
					'onkeypress' => 'return justDecimal( $(this).val(), event )',
				])->label( false );
			?>
			<td width='15' align='right'><label>%</label></td>	
		</tr>
	</table>
	
	<table border='0'>		
		<tr>	
			<?= $form->field($model, 'verificaexen', ['template' => $param['T_TAB_COL1']])->checkbox();  ?>		  
			<td width='73'></td> 
			
		<?php
		
			Pjax::begin(['id' => 'cuentaCliente']);
				
				if (isset($_POST['cta_id'])){
					
					$valor = $model->getNombreCuenta($_POST['cta_id'],"tcta=2");
					
					echo "<script>actualizaNombreCuenta('". $valor . "');</script>";
					
				}
			
			Pjax::end();
		
		?>
			
			<?= $form->field($model, 'cta_id', ['template' => $param['T_TAB_COL1']])->textInput([
					'onchange' => '$.pjax.reload({container:"#cuentaCliente",data:{cta_id:this.value},method:"POST"})',
					'style' => 'width:40px; text-align: center',
				]); 
			?>	
			<td>
				<!-- boton de búsqueda modal -->
				<?php
				Modal::begin([
	                'id' => 'BuscaAux',
					'toggleButton' => [
	                    'label' => '<i class="glyphicon glyphicon-search"></i>',
	                    'class' => 'bt-buscar'
	                ],
	                'closeButton' => [
	                  'label' => '<b>X</b>',
	                  'class' => 'btn btn-danger btn-sm pull-right',
	                ],
	                 ]);                      
	           
		           	 echo $this->render('//taux/auxbusca', [	
							'tabla' => 'cuenta',
							'campocod'=>'cta_id',
							'idcampocod'=>'calcdesc-cta_id',
							'idcamponombre'=>'cuenta_nombre',
							'criterio' => 'tcta=2'
						]);
	
				Modal::end();
	            ?>
	            <!-- fin de boton de búsqueda modal -->
       		</td>
			<td>
				<?= Html::input('text', 'cuenta_nombre', $model->getNombreCuenta($model->cta_id), [
						'id' => 'cuenta_nombre',
						'class' => 'form-control',
						'style' => 'width:364px; background-color:#E6E6FA',
						'readonly' => 'readonly',
					]);
				?>
			</td>
		</tr>
	</table>
	
	<table border='0'>	   
   <tr>
   		<td width='340'></td>
   		<td><label>Modificación: </label></td>
   		<td><?= $form->field($model, 'modif', ['template' => $param['T_TAB_COL1']])->textInput(['style' => 'width:284px; background-color:#E6E6FA', 'readOnly'=>'readOnly'])->label( false ); ?> </td>
   		
     </tr>
	</table>

	<table border="0">
		<tr>

		</tr>
	</table>
	
</div>
<?php 

	/**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 */
	 
	
	if(!isset($consulta)){
		
		$consulta = 1;
		
	}

	if($consulta == 0 || $consulta == 2 || $consulta == 3) { ?> 
		
	 <div class="form-group" style="margin-top: 8px;margin-bottom: 8px">
        <?php
        
        	if ($consulta == 0 or $consulta == 3)
        	{
        		echo Html::submitButton($model->isNewRecord ? 'Grabar' : 'Grabar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-success']);
        		
        	} else 
        	{
        		  
				echo Html::a('Eliminar', ['delete', 'accion'=>1,'id' => $model->desc_id], [
			            'class' => 'btn btn-danger',
			            'data' => [
			                'confirm' => 'Esta seguro que desea eliminar ?',
			       	         'method' => 'post',
		            	],
    				]);
        	} 
        	
        	echo '&nbsp;&nbsp;';
        	echo Html::a('Cancelar',['index'],[
        			'class' => 'btn btn-primary',
        	]);
        	
        ?>
    </div>
	
	    <?php
	    
		}
		
		echo $form->errorSummary($model);
		
		?>
		
		</div>
		<?php
		
	 	ActiveForm::end(); 
	 	
	 	if ($consulta == 1 || $consulta == 2) 
    	{
    		echo "<script>";
			echo "DesactivarForm('form-calcdesc');";
			echo "</script>";
			
    	} 

		if ($consulta == 3) {
    		
    		?>
    			
			<script>
	    		$(document).ready(function(){
	    			anualActive();
	    			document.getElementById('calcdesc-trib_id').disabled = true;
	    		});
	    		
    		</script>
    		
    		<?php
    		
    	}

	    ?>
	    
<script type="text/javascript">

function actualizaNombreCuenta(dato)
{

	var elementoNombreCuenta = document.getElementById('cuenta_nombre');
	
	elementoNombreCuenta.value = dato;

}

function seleccionaValue()
{
	
	var elementoIdCuenta = document.getElementById('cuenta_id'),
		value = elementoIdCuenta.value;
	
	return value;
	
}

function aplicaVenc()
{
	
	var pagoDesde = document.getElementById('calcdesc-pagodesde'),
		pagoHasta = document.getElementById('calcdesc-pagohasta'),
		aplicaVencimiento = document.getElementById('calcdesc-aplicavenc');
	
	if (aplicaVencimiento.checked) {

		aplicaVencimiento.value = 1;
		pagoDesde.disabled = true;
		pagoHasta.disabled = true;
		
	} else {
					
		aplicaVencimiento.value = 0;
		pagoDesde.disabled = false;
		pagoHasta.disabled = false;

	}
	
}
	
function anualActive()
{
	
	var cuotaDesde = document.getElementById('calcdesc-cuotadesde'),
		cuotaHasta = document.getElementById('calcdesc-cuotahasta'),
		anual = document.getElementById('calcdesc-anual');
	
	if (anual.checked) {

		cuotaDesde.readOnly = true;
		cuotaDesde.disabled = true;
		cuotaHasta.readOnly = true;
		cuotaHasta.disabled = true;

		
	} else {
					
		cuotaDesde.readOnly = false;
		cuotaDesde.disabled = false;
		cuotaHasta.readOnly = false;
		cuotaHasta.disabled = false;

	}
	
}

function verificaDeuda()
{
	
	var verificaDeuda = document.getElementById('calcdesc-verificadeuda'),
		existeDeuda = document.getElementById('calcdesc-existedeuda');
	
	if (verificaDeuda.checked)
	{
		
		existeDeuda.style.readOnly = false;
		existeDeuda.disabled = false;
		verificaDeuda.value = 1;
		
	} else 
	{
		existeDeuda.style.readOnly = true;
		existeDeuda.disabled = true;
		verificaDeuda.value = 0;
		
	}	
	
}
		
function btnObjetoBuscar(cod,nombre)
{
	$("#calcdesc-cta_id").val(cod);
	$("#cuenta_nombre").val(nombre);
}

function SelectTrib()
{
	$.pjax.reload({
			container:"#PjaxItem",
			method:"POST",
			data:{
				trib:$("#calcdesc-trib_id").val()
			},
		});
}

$(document).ready(function() {
	
	verificaDeuda();
	aplicaVenc();
});


</script>