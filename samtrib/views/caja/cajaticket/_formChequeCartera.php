<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\jui\DatePicker;
use yii\bootstrap\Tabs;
use yii\web\Session;
use yii\bootstrap\Alert;
use app\utils\db\Fecha;

    /**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 */

$form = ActiveForm::begin(['id' => 'formChequeCartera',
							'action' => ['chequecartera']]);
							
echo Html::input('hidden','formChequeCartera_txCartID',$model->cart_id);							
							
?>
<style>

.form-panel {
	
	padding-bottom: 8px;
	padding-top: 8px;
	padding-right: 8px;
	margin-right: 0px;
}
</style>

<div class="form-panel">
<table>
	<tr>
		<td width="60px"><label>Plan:</label></td>
		<td width="60px"><?= Html::input('text','formChequeCartera_txPlan1',$model->convenio1,['id'=>'formChequeCartera_txPlan1','class'=>'form-control','style'=>'width:100%', 'maxlength'=>6]) ?></td>
		<td width="15px"></td>
		<td width="50px"><label>Plan 2:</label></td>
		<td width="60px"><?= Html::input('text','formChequeCartera_txPlan2',$model->convenio2,['id'=>'formChequeCartera_txPlan2','class'=>'form-control','style'=>'width:100%', 'maxlength'=>6]) ?></td>
		<td width="15px"></td>
		<td width="60px"><label>Monto:</label></td>
		<td width="100px"><?= Html::input('text','formChequeCartera_txMonto',$model->monto,['id'=>'formChequeCartera_txMonto','class'=>'form-control','style'=>'width:100%;text-align:right','onkeypress'=>'return justDecimal($(this).val(),event)']) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="60px"><label>Banco:</label></td>
		<td width="60px">
			<?= Html::input('text','formChequeCartera_txBancoID',$model->banco,[
				 	'id'=>'formChequeCartera_txBancoID',
				 	'class'=>'form-control',
				 	'style'=>'width:60px;',
				 	'onchange'=>'actualizaBanco()',
				 ]);
			?>
		</td>
		
		<td>
			<!-- INICIO Botón Búsqueda Banco -->
<?php
			$direc = '//objeto/persona/buscarav';
			
			Modal::begin([
                'id' => 'BuscaObjChequeCartera',
                'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Banco</h2>',
				'toggleButton' => [
				
                    'label' => '<i class="glyphicon glyphicon-search"></i>',
                    'class' => 'bt-buscar',
                    'id' => 'btn_BuscaBanco'
                ],
                'closeButton' => [
                  'label' => '<b>X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
                'size' => 'modal-normal',
            ]);
            
              echo $this->render('//taux/auxbusca', [
              			'idAux' => 'BuscaBanco',
						'tabla' => 'banco_entidad', 
						'campocod' => 'bco_ent',
						'idcampocod'=>'formChequeCartera_txBancoID',
            			'idcamponombre'=>'formChequeCartera_txBancoNom',
            			'boton_id' => 'btn_BuscaBanco',
            			'cantmostrar' => 7,
            	
            			]);
            
            Modal::end();
            
            //Se debe ejecutar la función "actualizaBanco()" para que se actualice el listado de sucursales
            echo '<script>$("#BuscaObjChequeCartera").on("hidden.bs.modal", function () {actualizaBanco()});</script>';
            
            
        ?>

		<!-- FIN Botón Búsqueda Banco -->
		</td>
		
		<td width="60px">
			<?= Html::input('text','formChequeCartera_txBancoNom',$model->bancoNom,[
					'id'=>'formChequeCartera_txBancoNom',
					'class'=>'form-control',
					'style'=>'width:272px;',
					'readOnly' => true,
				]);
			?>
		</td>
	</tr>
	<tr>
		<td width="60px"><label>Suc:</label></td>
		<td width="60px">
			<?= Html::input('text','formChequeCartera_txSucID',$model->sucursal,[
					'id'=>'formChequeCartera_txSucID',
					'class'=>'form-control read-only',
					'style'=>'width:60px;',
					'onchange'=>'$.pjax.reload({container:"#actualizaNombreSucursal",method:"POST",data:{sucursal:$(this).val(),banco2:$("#formChequeCartera_txBancoID").val()}})',
				]);
			?>
		</td>
		<td>
			<!-- INICIO Botón Búsqueda Sucursal -->
			<?php
			
			//INICIO Bloque de código para búsquda de "Sucursal"
			Pjax::begin([ 'id'=>'actualizaSucursal', 'enablePushState' => false, 'enableReplaceState' => false ]);
				
				$banco = Yii::$app->request->get( 'banco', 0 );
				$nombre = utb::getCampo('banco_entidad','bco_ent = ' . $banco);
				$ejecuta = Yii::$app->request->get( 'ejecuta', 0 );
				
				//Si se enviaron datos de un banco por GET y el banco = 0
				if ( $ejecuta )
				{
					//Limpio los datos de sucursal				
					echo '<script>$("#formChequeCartera_txSucID").val("")</script>';
					echo '<script>$("#formChequeCartera_txSucNom").val("")</script>';
					
					//Actualizo el nombre del banco
					echo '<script>$("#formChequeCartera_txBancoNom").val("'.$nombre.'")</script>';
				}
				
				$cond = "bco_ent = " . $banco;
				
				//INICIO Modal Búsqueda Sucursal
				Modal::begin([
	                'id' => 'BuscaSucursalChequeCartera',
	                'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Sucursal</h2>',
					'toggleButton' => [
	                    'label' => '<i class="glyphicon glyphicon-search"></i>',
	                    'class' => 'bt-buscar read-only',
	                    'id' => 'btn_BuscaSucursal',
	                ],
	                'closeButton' => [
	                  'label' => '<b>X</b>',
	                  'class' => 'btn btn-danger btn-sm pull-right',
	                ],
	                'size' => 'modal-normal',
	            ]);
	            
	            echo $this->render('//taux/auxbusca', [
	            			'idAux' => 'BuscaSucursal',
							'tabla' => 'banco', 
							'campocod' => 'bco_suc',
							'idcampocod'=>'formChequeCartera_txSucID',
	            			'idcamponombre'=>'formChequeCartera_txSucNom',
	            			'boton_id' => 'btn_BuscaSucursal',
	            			'cantmostrar' => 7,
	            			'criterio' => $cond,
	            			]);
	            
	            Modal::end();
	            //FIN Modal Búsqueda Sucursal
	            
	        Pjax::end();
	        //FIN Bloque de código para búsquda de "Sucursal"
	        
	        Pjax::begin(['id'=>'actualizaNombreSucursal']);
	        	
	        	$sucursal = Yii::$app->request->post( 'sucursal', 0 );
	        	$banco = Yii::$app->request->post( 'banco2', 0 );
				$nombre = utb::getCampo('banco','bco_suc = ' . $sucursal .' AND bco_ent = ' . $banco);
					
				echo '<script>$("#formChequeCartera_txSucNom").val("'.$nombre.'")</script>';
	        
	        Pjax::end();
	        
            ?>

		<!-- FIN Botón Búsqueda Sucursal -->
		</td>
		
		<td width="60px"><?= Html::input('text','formChequeCartera_txSucNom',$model->sucursalNom,['id'=>'formChequeCartera_txSucNom','class'=>'form-control','style'=>'width:272px;','readOnly'=>true]) ?></td>
	
	</tr>
</table>

<table>
	<tr>
		<td width="60px"><label>Cuenta:</label></td>
		<td width="60px"><?= Html::input('text','formChequeCartera_txCuenta',$model->cuenta,['id'=>'formChequeCartera_txCuenta','class'=>'form-control','style'=>'width:90px;',]) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="60px"><label>Titular:</label></td>
		<td><?= Html::input('text','formChequeCartera_txTitular',$model->titular,['id'=>'formChequeCartera_txTitular','class'=>'form-control','style'=>'width:362px;text-transform: uppercase']) ?></td>
	</tr>
</table>

<table>
	<tr>
		<td width="60px"><label>Cheque:</label></td>
		<td width="150px"><?= Html::input('text','formChequeCartera_txCheque',$model->cheque,['id'=>'formChequeCartera_txCheque','class'=>'form-control','style'=>'width:160px;','maxlength'=>15]) ?></td>
		<td width="20px"></td>
		<td width="100px"><label>Fecha de Cobro:</label></td>
		<td width="80px">
			<?= 
				DatePicker::widget(
					[
						'id' => 'formChequeCartera_txFechaCobro',
						'name' => 'formChequeCartera_txFechaCobro',
						'dateFormat' => 'dd/MM/yyyy',
						'options' => ['style' => 'width:80px;text-align: center','class' => 'form-control'],
						'value'=> ( $model->fechaCobro != '' ? Fecha::usuarioToDatePicker( $model->fechaCobro ) : Fecha::usuarioToDatePicker( Fecha::getDiaActual() )),
					]
				);
			?>
		</td>
	</tr>
</table>

</div>
<!-- INICIO Mensajes de error -->
<table width="100%">
	<tr>
		<td width="100%">
				
			<?php 
			
			Pjax::begin(['id'=>'errorchequeCartera']);
			
			$mensaje = '';
			
			if (isset($_POST['mensaje'])) $mensaje = $_POST['mensaje'];
			
		
			if($mensaje != ""){ 
		
		    	Alert::begin([
		    		'id' => 'AlertaMensajechequeCartera',
					'options' => [
		        	'class' => 'alert-danger',
		        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
		    		],
				]);
		
				echo $mensaje;
						
				Alert::end();
				
				echo "<script>window.setTimeout(function() { $('#AlertaMensajechequeCartera').alert('close'); }, 5000)</script>";
			 }
			 
			 Pjax::end();
		
			?>
		</td>
	</tr>
</table>
<!-- FIN Mensajes de error -->

<div style="padding-top:8px;margin-bottom:8px">
<?php
		
	if ($consulta <> 1)
	{
		
		if ($consulta == 0 || $consulta == 3)
		{  
			 echo Html::button('Grabar',['class' => 'btn btn-success', 'method'=>'POST','onclick'=>'validarDatos(1)']);
			 	 
			 echo "&nbsp;&nbsp;";
			 echo Html::a('Cancelar', ['chequecartera', 'consulta'=>1, 'id'=>$id], 
			 					['class' => 'btn btn-primary']);
			 					
				 					
		} else if ($consulta == 2) {
					
			echo Html::Button('Grabar', ['class' => 'btn btn-success', 'id' => 'btEliminarAcep', 
				'data' => [
							'toggle' => 'modal',
							'target' => '#ModalEliminar',
						],]);
				
				
				Modal::begin([
        				'id' => 'ModalEliminar',
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
        
					echo Html::a('Aceptar', ['chequecartera', 
										'accion'=>2,
										'id' => $id, 
            							],['class' => 'btn btn-success',
        				]);
        		
        			echo "&nbsp;&nbsp;";
			 		echo Html::Button('Cancelar', ['id'=>'btEliminarCanc', 'class' => 'btn btn-primary', 'onclick'=>'$("#ModalEliminar").modal("hide")']);
			 		echo "</center>";
			 		
			 	Modal::end();
				 
			 echo "&nbsp;&nbsp;";
			 echo Html::a('Cancelar', ['chequecartera', 'consulta'=>1, 'id'=>$id], 
			 					['class' => 'btn btn-primary']);
		}
		
	}
?>

</div>

<!-- INICIO Mensajes de alerta -->
<div>
<table width="100%">
	<tr>
		<td width="100%">
				
			<?php 
			
			Pjax::begin(['id'=>'alertChequeCartera']);
			
			if ($alert != '' && $m != '')
			{ 
		
		    	Alert::begin([
		    		'id' => 'AlertaMensajeChequeCartera',
					'options' => [
		        	'class' => ($m == 2 ? 'alert-danger' : 'alert-success'),
		        	'style' => $m !== '' ? 'display:block' : 'display:none' 
		    		],
				]);
		
				echo $alert;
						
				Alert::end();
				
				echo "<script>window.setTimeout(function() { $('#AlertaMensajeChequeCartera').alert('close'); }, 5000)</script>";
			 }
			 
			 Pjax::end();
		
			?>
		</td>
	</tr>
</table>
</div>
<!-- FIN Mensajes de alerta -->

<?php	
	
	echo $form->errorSummary($model, ['id' => 'chequeCartera_errorSummary'] );
			
	ActiveForm::end();
	
	if ($consulta==1 or $consulta==2) 
	{
		echo "<script>";
		echo "DesactivarForm('formChequeCartera');";
		echo "</script>";
	}
	
 
    if ($consulta==2) echo '<script>$("#btEliminarAcep").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#btEliminarCanc").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#ModalEliminar").prop("disabled", false);</script>';
    if ($consulta==2) echo '<script>$("#btCancelarModalElim").prop("disabled", false);</script>'; 
	
?>

<script>

function actualizaBanco()
{
	var banco = $("#formChequeCartera_txBancoID").val();
	
	if (banco == '')
		banco = 0;
		
	$.pjax.reload({
		container:"#actualizaSucursal",
		method: "GET",
		replace: true,
		push: false,
		data:{
			banco:banco,
			ejecuta: 1,
		}
	});
}

function mostrarError ( error )
{
	
	var $contenedor = $("#chequeCartera_errorSummary");
		$lista = $("#chequeCartera_errorSummary ul");
			
	$contenedor.css("display","block");
	
	$lista.empty();
	
	for (e in error)
	{
		$el = $("<li />");
		$el.text(error[e]);
		$el.appendTo($lista);
	}
}

function validarDatos()
{
	var error = new Array(),
		bancoID = $("#formChequeCartera_txBancoID").val(),
		bancoNombre = $("#formChequeCartera_txBancoNom").val(),
		sucursalID = $("#formChequeCartera_txSucID").val(),
		sucursalNombre = $("#formChequeCartera_txSucNom").val(),
		monto = $("#formChequeCartera_txMonto").val(),
		plan1 = $("#formChequeCartera_txPlan1").val(),
		plan2 = $("#formChequeCartera_txPlan2").val(),
		cuenta = $("#formChequeCartera_txCuenta").val(),
		cheque = $("#formChequeCartera_txCheque").val(),
		fecha = $("#formChequeCartera_txFechaCobro").val();
	
	if (plan1 == '')
	{
		error.push("Ingrese un número de Plan.");
	}
	
	if (monto == '')
	{
		error.push("Ingrese un monto.");
		
	} else if (monto <= 0)
	{
		error.push("Ingrese un monto válido.");
	}
	
	if (bancoID == '')
	{
		error.push("Ingrese un banco.");
	} else if (bancoNombre == '')
	{
		error.push("Ingrese un banco válido.");
	}	
	
	if (sucursalID == '')
	{
		error.push("Ingrese una sucursal.");
	} else if (sucursalNombre == '')
	{
		error.push("Ingrese una sucursal válida.");
	}	
	
	if (cuenta == '')
	{
		error.push("Ingrese una cuenta.");
		
	} else if (cuenta <= 0)
	{
		error.push("Ingrese una cuenta válida.");
	}
	
	if (cheque == '')
	{
		error.push("Ingrese un cheque.");
		
	} else if (cheque <= 0)
	{
		error.push("Ingrese un cheque válido.");
	}	
	
	if ( fecha == '' || fecha == null )
	{
		error.push("Ingrese una fecha.");
	}	
	
	
	if ( error.length > 0 )
	{
		mostrarError( error );
		
	} else 
	{
		$("#formChequeCartera").submit();
	}
	
	$("#BuscaObjChequeCartera").on("hidden.bs.modal", actualizaSucursal);
	
}

function cambiaBanco()
{
	banco = $("#formChequeCartera_txBancoID").val();
	
	$("#formChequeCartera_txSucID").toggleClass("read-only",banco == 0);
	$("#btn_BuscaSucursal").toggleClass("read-only",banco == 0);
}

$("#actualizaSucursal").on("pjax:end",function() {
	
	 cambiaBanco();
});

$(document).ready(function() {
	
	 cambiaBanco();
});

</script>