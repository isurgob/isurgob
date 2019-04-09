<?php
use \yii\bootstrap\Modal;
use app\utils\db\utb;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \yii\widgets\Pjax;
use yii\grid\GridView;

	/**
	 * @param $consulta es una variable que:
	 * 		=> $consulta == 1 => El formulario se dibuja en el index
	 * 		=> $consulta == 0 => El formulario se dibuja en el create
	 * 		=> $consulta == 3 => El formulario se dibuja en el update
	 * 		=> $consulta == 2 => El formulario se dibuja en el delete
	 * 
	 * @param $model
	 */
	 
	 if (!isset($consulta)) $consulta = 1;
	 
?>
<style>
#menuDerecho_btCopiarCC, #menuDerecho_btMoverDJ {
	background-color:#fff;
	border-width: 0px;
	text-align: left;
	padding: 0px;
	font-weight: bold;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
}

#DDJJBuscar .modal-content{
	
	width: 70% !important;
	margin-left: 15%;
	
}
#ImpForm .modal-content{
	width:750px !important;
}

#ImpForm .btn{
	color:#fff !important;
}
</style>

<ul id='ulMenuDer' class='menu_derecho'>
	<?php if (utb::getExisteProceso(3012)){ ?>
	<li id='liBuscar' class='glyphicon glyphicon-search'>
		<?php
		 	Modal::begin([
    		'id' => 'DDJJBuscar',
			'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Fiscalización</h2>',
			'toggleButton' => [
                    'label' => '<b>Buscar</b>',
                    'class' => 'bt-buscar-label'],
            'closeButton' => [
                  'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
            'size' => 'modal-lg',
			]);
			
			echo $this->render('buscar');

			Modal::end();
		?>		
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3013)){ ?>
	<li id='liNuevo' class='glyphicon glyphicon-plus'> <?= Html::a('<b>Nuevo</b>', ['create'], ['class' => 'bt-buscar-label','data-pjax' => 'false']) ?></li> 
	<li id='liModificar' class='glyphicon glyphicon-pencil'> <?= Html::a('<b>Modificar</b>', ['update', 'id' => $model->fisca_id], ['class' => 'bt-buscar-label']) ?></li> 
	<li id='liEliminar' class='glyphicon glyphicon-trash'> <?= Html::a('<b>Eliminar</b>', ['delete', 'id' => $model->fisca_id], ['class' => 'bt-buscar-label']) ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3012)){ ?>
	<li id='liImprimir' class='glyphicon glyphicon-print'> <?= Html::a('<b>Imprimir</b>', ['imprimir', 'id' => $model->fisca_id], ['class' => 'bt-buscar-label','target'=>'_black']) ?></li> 
	<li id='liFormulario' class='glyphicon glyphicon-list-alt'>  
		<?php 
		Modal::begin([
            	'id' => 'ImpForm',
            	'header' => '<h4 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;"><b>Reporte de Fiscalización</b></h4>',
            	'toggleButton' => [
                    'label' => '<b>Formulario</b>',
                    'class' => 'bt-buscar-label'],
				'closeButton' => [
                	'label' => '<b style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">X</b>',
                  	'class' => 'btn btn-danger btn-sm pull-right',
                	]
            ]);
				
				echo  GridView::widget([
						'dataProvider' => utb::DataProviderGeneralCons('Select * From sam.rpt_Fiscaliza Order by Cod',20,'cod'),
						'id' => 'GrillaPerAv',
						'headerRowOptions' => ['class'=>'grilla','style' => 'font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#333'],
						'rowOptions' => ['class'=>'grilla','style' => 'font-family:Helvetica Neue, Helvetica, Arial, sans-serif;color:#333'],
						'summary' => false,
        				'columns' => [
        						['class' => '\yii\grid\CheckboxColumn','checkboxOptions' => ['style'=>'vertical-align:inherit;width:20px','class' => 'simple']],	
        						['attribute'=>'cod','header' => 'Cod.','contentOptions'=>['style'=>'vertical-align:inherit;text-align:center;width:20px']],
        						['attribute'=>'nombre','header' => 'Nombre','contentOptions'=>['style'=>'vertical-align:inherit;text-align:left;width:120px']],
            					['attribute'=>'detalle','header' => 'Detalle','contentOptions'=>['style'=>'vertical-align:inherit;text-align:left;']],
					   	],
   				]);
   				
   				$form = ActiveForm::begin(['action' => ['imprimirformulario'],'id'=>'frmImpForm','options'=>['target'=>'_black']]);
	   				echo '<br>';
	   				echo '<input type="text" name="arrayReport" id="arrayReport" value="" style="display:none">';
	   				echo '<input type="text" name="txFisc" id="txFisc" value="" style="display:none">';
	   				echo Html::a('Aceptar',null, ['class' => 'btn btn-success','style'=>'font-family:Helvetica Neue, Helvetica, Arial, sans-serif;', 'onclick' => 'btAceptarImpFormClick()']);
	   				echo "&nbsp;";
					echo Html::Button('Cancelar', ['class' => 'btn btn-primary','style'=>'font-family:Helvetica Neue, Helvetica, Arial, sans-serif;', 'onClick' => '$("#ImpForm, .window").modal("toggle");']);
                ActiveForm::end();
                
                echo '<div id="errorimpform" style="display:none;font-family:Helvetica Neue, Helvetica, Arial, sans-serif;margin-top:5px" class="alert alert-danger alert-dismissable"></div>';
                        
            Modal::end(); 
		?>
	</li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3330)){ ?>
	<li id='liDDJJ' class='glyphicon glyphicon-folder-close'>  <?= Html::a('<b>DDJJ</b>', ['ctacte/ddjj/listadj','id' => $model->obj_id, 'fs' => 1], ['class' => 'bt-buscar-label']) ?></li>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3300)){ ?>
	<li id='liCtaCte' class='glyphicon glyphicon-usd'> <?= Html::a('<b>Cta. Cte.</b>', ['ctacte/ctacte/index','obj_id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li> 
	<?php } ?>
	<?php if (utb::getExisteProceso(3013)){ ?>
	<li id='liCopiarCC' class='glyphicon glyphicon-floppy-disk'>  <?= Html::button('Copiar CC', [
																		'id' => 'menuDerecho_btCopiarCC',
																		'onclick' => 'fiscalizaMov(1)',
																		]);
															 ?></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3331)){ ?>
	<li id='liMoverDJ' class='glyphicon glyphicon-share'> <?= Html::button('Mover DJ', [
																		'id' => 'menuDerecho_btMoverDJ',
																		'onclick' => 'fiscalizaMov(2)',
																		]);
														 	?></li> 
	<?php } ?>
	<?php if (utb::getExisteProceso(3331) or utb::getExisteProceso(3300) or utb::getExisteProceso(3013)){ ?>
	<li><hr style="color: #ddd; margin:1px" /></li>
	<?php } ?>
	<?php if (utb::getExisteProceso(3012)){ ?>
	<li id='liMisc' class='glyphicon glyphicon-comment'> <?= Html::a('<b>Misceláneas</b>', ['objeto/objeto/miscelaneas','id' => $model->obj_id], ['class' => 'bt-buscar-label']) ?></li> 
	<li id='liListado' class='glyphicon glyphicon-list-alt'>  <?= Html::a('<b>Listado</b>', ['//ctacte/listadofiscalizacion/index'], ['class' => 'bt-buscar-label', 'data' => ['method' => 'post']])  ?></li>
	<?php } ?>
</ul>

<?php 

		//Deshabilito todas las opciones 
		echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
		echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
		echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
		
		//Habilito Buscar, Nuevo y Listado
		echo '<script>$("#liBuscar").css("pointer-events", "all");</script>';
		echo '<script>$("#liBuscar a").css("color", "#337ab7");</script>';
		echo '<script>$("#liBuscar").css("color", "#337ab7");</script>';
		echo '<script>$("#liNuevo").css("pointer-events", "all");</script>';
		echo '<script>$("#liNuevo a").css("color", "#337ab7");</script>';
		echo '<script>$("#liNuevo").css("color", "#337ab7");</script>';
		echo '<script>$("#liListado").css("pointer-events", "all");</script>';
		echo '<script>$("#liListado a").css("color", "#337ab7");</script>';
		echo '<script>$("#liListado").css("color", "#337ab7");</script>';
		
		//Si se presiona "Nuevo" o "Modificar" o "Eliminar", se deshabilita todo
		if ($consulta == 0 || $consulta == 3 || $consulta == 2)
		{
			//Deshabilito todas las opciones 
			echo '<script>$("#ulMenuDer").css("pointer-events", "none");</script>';
			echo '<script>$("#ulMenuDer li").css("color", "#ccc");</script>';
			echo '<script>$("#ulMenuDer li a").css("color", "#ccc");</script>';
			
		} else 
		{
			//Si hay alguna fiscalización cargada, se habilita todo
			if ($model->fisca_id != '' || $model->fisca_id != null)
			{
				/*
				 * Si hay alguna fiscalización cargada, se deben habilitar los botones:
				 * 		+ Modificar
				 * 		+ Eliminar
				 * 		+ DDJJ
				 * 		+ CtaCte
				 * 		+ Copiar CC
				 * 		+ Mover DJ
				 * 		+ Misc
				 */
				 
				 //Habilito todas las opciones 
				echo '<script>$("#ulMenuDer").css("pointer-events", "all");</script>';
				echo '<script>$("#ulMenuDer li").css("color", "#337ab7");</script>';
				echo '<script>$("#ulMenuDer li a").css("color", "#337ab7");</script>';
				
				//Si el estado es 0, deshabilito modificar y eliminar
				if ($model->est == 0)
				{
					echo '<script>$("#liModificar").css("pointer-events", "none");</script>';
					echo '<script>$("#liModificar a").css("color", "#ccc");</script>';
					echo '<script>$("#liModificar").css("color", "#ccc");</script>';
					echo '<script>$("#liEliminar").css("pointer-events", "none");</script>';
					echo '<script>$("#liEliminar a").css("color", "#ccc");</script>';
					echo '<script>$("#liEliminar").css("color", "#ccc");</script>';
				}
					 
			} else 
			{
				
			}
		}
			
?>

<script>
function fiscalizaMov(cod)
{
	$.pjax.reload({
		container: "#manejadorMovimiento",
		type: "GET",
		replace: false,
		push: false,
		data: {
			actionMovimiento: cod,
		}
	});
	
	$("#manejadorMovimiento").on("pjax:end", function() {
		
		$("#ModalMovimientos").modal("show");
		$("#manejadorMovimiento").off("pjax:end");
	});
	
}

function btAceptarImpFormClick()
{
	var keys = $('#GrillaPerAv').yiiGridView('getSelectedRows');
	
	if (keys.length > 0)
	{
		$("#txFisc").val($("#fiscaliza_txCod").val());
		$("#arrayReport").val(keys);
		$("#frmImpForm").submit();
		$("#ImpForm, .window").modal("toggle");
	}else {
		$("#errorimpform").html('Seleccione al menos un Informe.');
		$("#errorimpform").css("display", "block");
	}
	
}
</script>