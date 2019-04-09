<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;


$title = 'Tipos de Acciones';
$this->params['breadcrumbs'][] = ['label' => 'Configuraciones', 'url' => ['site/config']];
$this->params['breadcrumbs'][] = $title;


?>

<div class="objeto-taccion-index">
    
    <table width='100%' style='border-bottom:1px solid #ddd;'>
	    <tr>
	    	<td><h1><?= Html::encode($title) ?></h1></td>
	    	<td align='right'>
	       		 <?= Html::Button('Nuevo', ['class' => 'btn btn-success', 'id' => 'btNuevo', 'onclick'=> 'cambiaObjeto( 0, "nuevo", "" )']) ?>
	    	</td>
	    </tr>
	    <tr colspan='2'>
	    	
	    </tr>
    </table>
   
    <div class="separador-horizontal"></div>
    
    <div id="form_divMensaje" class="mensaje alert-success" style="margin: 10px 0px; display: none"></div>

	<!-- -------------------- Inicio comboBox que filtra grilla por tipo de objeto ---------------  -->
	
	<table width='100%' border='0'>
	<tr>		
	    <td height='35px'>
	    	<label>Filtrar por Tipo de Objeto</label>
			<?= Html::dropDownList('selectTObj', "", $tipoObjeto,[
					'id'=>'selectTObj',
					'class' => 'form-control',
					'style'=>'width:199px',
					'onchange' => 'cambiaTipoObjeto()',  
				]);
			?>
	    </td>
	</tr>
</table>

	<div class="pull-left" style="width:49%;">
	<?php
	
		Pjax::begin(['id' => 'idGrid']);// comienza bloque de grilla
   
	    	echo GridView::widget([
		    	'id' => 'GrillaTablaTAccion',   		  
		        'dataProvider' => $dpTAccion,
		        'headerRowOptions' => ['class' => 'grilla','style'=>'width:100%'],
		        'summaryOptions' => ['style' => 'display:none'],
		        'rowOptions' => function ($model) { return [
								'class' => 'grilla seleccionable',
								'onclick' => 'cambiaObjeto( ' . $model['cod'] . ', "consulta", $(this) )'];},
		        'columns' => [
		
					['attribute' => 'cod', 'header' => 'Código', 'contentOptions' => function ($model, $key, $index, $column){
																								return [ 'style' => 'width:40px', 'class' => 'grilla', 'id' => "tdCod" . $model['cod'] ];
																							}],
		        	['attribute' => 'nombre', 'header' => 'Nombre', 'contentOptions' => ['style' => 'width:220px','class'=>'grilla']],
		        	['attribute' => 'interno_nom', 'header' => 'Interno','contentOptions' => ['style' => 'width:60px','class'=>'grilla']],	
													 
		            ['class' => 'yii\grid\ActionColumn', 
					'contentOptions' => ['style' => 'width:40px;','class'=>'grilla'],
					'template' =>'{update}{delete}',
		            'buttons' => [   
						'update' => function($url,$model,$key)
									{
										if($model['interno']!='S')
										{
		    								return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
		        								[
		        									'class' => 'bt-buscar-label',
		        									'style' => 'color:#337ab7;',
		        									'onclick' => 'cambiaObjeto( '.$model['cod'].', "modifica", "" ); event.stopPropagation()',  
												]											
		        							);
		        						}	
									},
		            							
						'delete' => function($url,$model,$key)
									{      	 
										if($model['interno']!='S'){
											return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
												[
														'class' => 'bt-buscar-label',
														'style' => 'color:#337ab7;',
													    'onclick' => 'cambiaObjeto( '.$model['cod'].', "elimina", "" ); event.stopPropagation()',  
												]);												
											}
									}
		            	],
			        ],
				]
			]);
					 
		Pjax::end(); // fin bloque de la grilla	       
          ?>             
   	</div>              
         
	<div class="pull-right" style="width:49%;">               
	     <?php        
		   
		  Pjax::begin(['id' => 'formDatos']);
		        
		        echo $this->render('_form', [ 'model' => $model, 'listaEstado' => $listaEstado ] ); 
		    	     	   
		    Pjax::end();
		 ?>
	  </div>
	
</div>

<?php if ( $mensaje != '' ) { //Si existen mensajes ?>
	<script>

	$( document ).ready(function() {

		mostrarMensaje( "<?= $mensaje; ?>", "#form_divMensaje" );

	});

	</script>
<?php }  ?>

<script type="text/javascript">
	
	function cambiaTipoObjeto(){
		$.pjax.reload({
	        container: '#idGrid',
	        type: 'POST',
	        replace: false,
	        push: false,
	        data : {
	            "filtroTObj": $("#selectTObj").val()
	        }
	    });
	}

	function cambiaObjeto( cod, scenario, $fila){
		
		if ( $fila == '' )
			$fila = $( "#tdCod" + cod ).parent("tr");
		
		marcarFilaGrilla( "#GrillaTablaTAccion", $fila );

		$.pjax.reload({
	        container: '#formDatos',
	        type: 'POST',
	        replace: false,
	        push: false,
	        data : {
	            "cod": cod,
	            "scenario": scenario
	        }
	    });

		$( "#formDatos" ).on( "pjax:end", function() {

	        $("#selectTObj").attr('disabled', (scenario != 'consulta') );

	        $("#btNuevo").attr('disabled', (scenario != 'consulta') );

	        $("#idGrid *").css('pointer-events', (scenario != 'consulta' ? 'none' : 'all') );

	    });
	}

</script>