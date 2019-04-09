<?php
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\grid\GridView;
use app\models\objeto\Inm;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use yii\web\Session;
use app\utils\db\utb;

/**
 * Recibo:
 * 		$arregloMejoras es un arreglo con los valores de items activos
 */
 
 //La variable action identificará el tipo de acción que se debe realizar en el modal
$action = '';
	
	//	<!-- INICIO Botón de búsqueda de mejora -->
	
	Modal::begin([
			'id' => 'modal-nuevaMejora',
			'header'=>'<h2>Mejoras<h2>',
			'toggleButton' => [
				'class' => 'btn btn-success pull-right',
				'id'=>'inm-btnmodal-nuevoTitular',
				'style'=>'display:none',
			],
			'closeButton' => [
			  'label' => '<b>X</b>',
			  'class' => 'btn btn-danger btn-sm pull-right',
			],
			 ]);
				  
		
		echo $this->render('//objeto/inm/nuevaMejora', ['model' => $model, 'arregloMejoras' => $arregloMejoras,]);
	
	Modal::end();
	
	//<!-- FIN Botón de búsqueda de mejora -->

?>

<div class="form-mejoras" id="consultaMejoras" >

	<table width="100%">
		<tr>
			<td>
				<?= Html::checkbox('ckPartProv',true, ['id' => 'inm-filtraMejoras','label' => 'Incluir Polígonos no Vigentes y Bajas','onchange' => 'filtraMejoras()']); ?></td>
			</td>
			<td align="right">
				<?php 
					echo Html::button('Nuevo', [
						'class' => 'btn btn-success',
						'onclick'=>'$.pjax.reload({container:"#nuevaMejora",method:"POST",data:{action:1}});'
					]); 
				?>
			</td>
		</tr>
	</table>
	
	<div style="margin-top: 8px">

<?php
	
	//$periodoActual = utb::PerActual(21); //Obtengo el periodo actual para filtrar los datos que se van a mostrar en la grilla
	
//INICIO Bloque de código que actualiza los datos en la grilla.
Pjax::begin(['id'=>'mejora-actualizaGrilla']);
			
			
	$arreglo = [];		
	
	$filtro = Yii::$app->request->post( 'filtro', 1 );	
	
	$session = new Session;
	$session->open();
	
	//Le paso al arreglo en el modelo el valor del arreglo que está en memoria
	if (isset($session['arregloMejoras'])) 
		$model->arrayMejoras = $session['arregloMejoras'];

	if ( $filtro == 0 ) //Código que se ejecuta en caso de que no se deseen ver las mejoras inactivas
	{
		//Tengo que generar el código para filtrar los datos en sesión
		$array = $session['arregloMejoras'];
		
		$arrayKey = array_keys($array);
		
		foreach($arrayKey as $clave)
	 	{
    	 	//if ($array[$clave]['estado'] == 'A' and $array[$clave]['perdesde'] <= $periodoActual and $array[$clave]['perhasta'] >= $periodoActual)
    	 		$arreglo[$clave] = $array[$clave];
	 	}
		
	} else 
	{
		$arreglo = $session['arregloMejoras'];
	}
	
	$session->close();

	 echo GridView::widget([
	    'dataProvider' => $model->CargarMejoras($arreglo),
	    'headerRowOptions' => ['class' => 'grilla'],
		'rowOptions' => ['class' => 'grilla'],
		'id' => 'inm_mejoras_grilla',
	    'columns' => [
					            
            ['attribute'=>'pol','header' => 'Pol'],
            ['attribute'=>'tori','header' => 'Orig'],
            ['attribute'=>'nivel','header' => 'Niv'],
            ['attribute'=>'anio','header' => 'Año'],
            ['attribute'=>'tdest_nom','header' => 'Destino'],
            ['attribute'=>'tobra_nom','header' => 'Obra'],
            ['attribute'=>'est_nom','header' => 'EstM'],
            ['attribute'=>'supcub','header' => 'SCub'],
            ['attribute'=>'supsemi','header' => 'SSemi'],
            ['attribute'=>'cat','header' => 'Cat'],
            ['attribute'=>'tform','header' => 'Form'],    
            ['attribute'=>'estado','header' => 'Est'],
            ['attribute'=>'perdesde_nom','header' => 'P.Desde'],


           ['class' => 'yii\grid\ActionColumn',
            'buttons' => [	    
	            'view' => function( $url, $model, $key )
            			{
            				return Html::button('<span class="glyphicon glyphicon-eye-open"></span>', [
										'class' => 'bt-buscar-label',
										'style' => 'color:#337ab7',
										'onclick'=>'$.pjax.reload({' .
														'container:"#nuevaMejora", ' .
														'method:"POST", ' .
														'data:{' .
															'action:0,' .
															'pol:"'.$model['pol'].'",' .
														    'perdesde:"'.$model['perdesde'].'",' .
														    'perdesde_nom:"'.$model['perdesde_nom'].'",' .
														    'perhasta:"'.$model['perhasta'].'",' .
														    'tori:"'.$model['tori'].'",' .
														    'tori_nom:"'.$model['tori_nom'].'",' .
														    'tform:"'.$model['tform'].'",' .
														    'nivel:"'.$model['nivel'].'",' .
														    'tdest:"'.$model['tdest'].'",' .
														    'tdest_nom:"'.$model['tdest_nom'].'",' .
														    'tobra:"'.$model['tobra'].'",' .
														    'tobra_nom:"'.$model['tobra_nom'].'",' .
														    'anio:"'.$model['anio'].'",' .
														    'est:"'.$model['est'].'",' .
														    'est_nom:"'.$model['est_nom'].'",' .
														    'supcub:"'.$model['supcub'].'",' .
														    'supsemi:"'.$model['supsemi'].'",' .
														    'plantas:"'.$model['plantas'].'",' .
														    'cat:"'.$model['cat'].'",' .
														    'item01:"'.$model['item01'].'",' .
														    'item02:"'.$model['item02'].'",' .
														    'item03:"'.$model['item03'].'",' .
														    'item04:"'.$model['item04'].'",' .
														    'item05:"'.$model['item05'].'",' .
														    'item06:"'.$model['item06'].'",' .
														    'item07:"'.$model['item07'].'",' .
														    'item08:"'.$model['item08'].'",' .
														    'item09:"'.$model['item09'].'",' .
														    'item10:"'.$model['item10'].'",' .
														    'item11:"'.$model['item11'].'",' .
														    'item12:"'.$model['item12'].'",' .
														    'item13:"'.$model['item13'].'",' .
														    'item14:"'.$model['item14'].'",' .
														    'item15:"'.$model['item15'].'",' .
														    'estado:"'.$model['estado'].'",' .
														    'BD:"'.$model['BD'].'"' .
														    		'}});']) ;			
            			},					
    			'update' => function($url, $model, $key) use ( $consulta )
    						{            		
    							if ( $consulta == 0 || $consulta == 3 )					
    							return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
										'class' => 'bt-buscar-label',
										'style' => 'color:#337ab7',
										'onclick'=>'$.pjax.reload({' .
														'container:"#nuevaMejora", ' .
														'method:"POST", ' .
														'data:{' .
															'action:2,' .
															'pol:"'.$model['pol'].'",' .
														    'perdesde:"'.$model['perdesde'].'",' .
														    'perdesde_nom:"'.$model['perdesde_nom'].'",' .
														    'perhasta:"'.$model['perhasta'].'",' .
														    'tori:"'.$model['tori'].'",' .
														    'tori_nom:"'.$model['tori_nom'].'",' .
														    'tform:"'.$model['tform'].'",' .
														    'nivel:"'.$model['nivel'].'",' .
														    'tdest:"'.$model['tdest'].'",' .
														    'tdest_nom:"'.$model['tdest_nom'].'",' .
														    'tobra:"'.$model['tobra'].'",' .
														    'tobra_nom:"'.$model['tobra_nom'].'",' .
														    'anio:"'.$model['anio'].'",' .
														    'est:"'.$model['est'].'",' .
														    'est_nom:"'.$model['est_nom'].'",' .
														    'supcub:"'.$model['supcub'].'",' .
														    'supsemi:"'.$model['supsemi'].'",' .
														    'plantas:"'.$model['plantas'].'",' .
														    'cat:"'.$model['cat'].'",' .
														    'item01:"'.$model['item01'].'",' .
														    'item02:"'.$model['item02'].'",' .
														    'item03:"'.$model['item03'].'",' .
														    'item04:"'.$model['item04'].'",' .
														    'item05:"'.$model['item05'].'",' .
														    'item06:"'.$model['item06'].'",' .
														    'item07:"'.$model['item07'].'",' .
														    'item08:"'.$model['item08'].'",' .
														    'item09:"'.$model['item09'].'",' .
														    'item10:"'.$model['item10'].'",' .
														    'item11:"'.$model['item11'].'",' .
														    'item12:"'.$model['item12'].'",' .
														    'item13:"'.$model['item13'].'",' .
														    'item14:"'.$model['item14'].'",' .
														    'item15:"'.$model['item15'].'",' .
														    'estado:"'.$model['estado'].'",' .
														    'BD:"'.$model['BD'].'"' .
														    		'}});']) ;			
    						},
    					
    			'delete' => function($url, $model, $key) use ( $consulta )
    						{            		
    							if ( $consulta == 0 || $consulta == 3 )         							
    							return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
										'class' => 'bt-buscar-label',
										'style' => 'color:#337ab7',
										'onclick'=>'$.pjax.reload({' .
														'container:"#nuevaMejora", ' .
														'method:"POST", ' .
														'data:{' .
															'action:3,' .	
															'pol:"'.$model['pol'].'",' .
														    'perdesde:"'.$model['perdesde'].'",' .
														    'perdesde_nom:"'.$model['perdesde_nom'].'",' .
														    'perhasta:"'.$model['perhasta'].'",' .
														    'tori:"'.$model['tori'].'",' .
														    'tori_nom:"'.$model['tori_nom'].'",' .
														    'tform:"'.$model['tform'].'",' .
														    'nivel:"'.$model['nivel'].'",' .
														    'tdest:"'.$model['tdest'].'",' .
														    'tdest_nom:"'.$model['tdest_nom'].'",' .
														    'tobra:"'.$model['tobra'].'",' .
														    'tobra_nom:"'.$model['tobra_nom'].'",' .
														    'anio:"'.$model['anio'].'",' .
														    'est:"'.$model['est'].'",' .
														    'est_nom:"'.$model['est_nom'].'",' .
														    'supcub:"'.$model['supcub'].'",' .
														    'supsemi:"'.$model['supsemi'].'",' .
														    'plantas:"'.$model['plantas'].'",' .
														    'cat:"'.$model['cat'].'",' .
														    'item01:"'.$model['item01'].'",' .
														    'item02:"'.$model['item02'].'",' .
														    'item03:"'.$model['item03'].'",' .
														    'item04:"'.$model['item04'].'",' .
														    'item05:"'.$model['item05'].'",' .
														    'item06:"'.$model['item06'].'",' .
														    'item07:"'.$model['item07'].'",' .
														    'item08:"'.$model['item08'].'",' .
														    'item09:"'.$model['item09'].'",' .
														    'item10:"'.$model['item10'].'",' .
														    'item11:"'.$model['item11'].'",' .
														    'item12:"'.$model['item12'].'",' .
														    'item13:"'.$model['item13'].'",' .
														    'item14:"'.$model['item14'].'",' .
														    'item15:"'.$model['item15'].'",' .
														    'estado:"'.$model['estado'].'",' .
														    'BD:"'.$model['BD'].'"' .
														    		'}});']) ;		
    						}
    			]
    		]
    	]
    ]);
    
Pjax::end();
//FIN Bloque de código que actualiza los datos en la grilla.


?>

	</div>

</div>
<?php
//INICIO Bloque de código que carga los datos en el arreglo.
Pjax::begin(['id'=>'nuevaMejora']);

	if(isset($_POST['action'])) $action = $_POST['action'];

		if ( $action != '' )
		{
					    							
			$session = new Session;
			$session->open(); 
								 
			$session['pol'] = Yii::$app->request->post( 'pol', '' );
		    $session['perdesde'] = Yii::$app->request->post( 'perdesde', '' );
		    $session['perdesde_nom'] = Yii::$app->request->post( 'perdesde_nom', '' );
		    $session['perhasta'] = Yii::$app->request->post( 'perhasta', '' );
		    $session['tori'] = Yii::$app->request->post( 'tori', '' );
		    $session['tori_nom'] = Yii::$app->request->post( 'tori_nom', '' );
		    $session['tform'] = Yii::$app->request->post( 'tform', '' );
		    $session['nivel'] = Yii::$app->request->post( 'nivel', '' );
		    $session['tdest'] = Yii::$app->request->post( 'tdest', '' );
		    $session['tdest_nom'] = Yii::$app->request->post( 'tdest_nom', '' );
		    $session['tobra'] = Yii::$app->request->post( 'tobra', '' );
		    $session['tobra_nom'] = Yii::$app->request->post( 'tobra_nom', '' );
		    $session['anio'] = Yii::$app->request->post( 'anio', '' );
		    $session['est'] = Yii::$app->request->post( 'est', '' );
		    $session['est_nom'] = Yii::$app->request->post( 'est_nom', '' );
		    $session['supcub'] = Yii::$app->request->post( 'supcub', '' );
		    $session['supsemi'] = Yii::$app->request->post( 'supsemi', '' );
		    $session['plantas'] = Yii::$app->request->post( 'plantas', '' );
		    $session['cat'] = Yii::$app->request->post( 'cat', '' );
		    $session['item01'] = Yii::$app->request->post( 'item01', 0 );
		    $session['item02'] = Yii::$app->request->post( 'item02', 0 );
		    $session['item03'] = Yii::$app->request->post( 'item03', 0 );
		    $session['item04'] = Yii::$app->request->post( 'item04', 0 );
		    $session['item05'] = Yii::$app->request->post( 'item05', 0 );
		    $session['item06'] = Yii::$app->request->post( 'item06', 0 );
		    $session['item07'] = Yii::$app->request->post( 'item07', 0 );
		    $session['item08'] = Yii::$app->request->post( 'item08', 0 );
		    $session['item09'] = Yii::$app->request->post( 'item09', 0 );
		    $session['item10'] = Yii::$app->request->post( 'item10', 0 );
		    $session['item11'] = Yii::$app->request->post( 'item11', 0 );
		    $session['item12'] = Yii::$app->request->post( 'item12', 0 );
		    $session['item13'] = Yii::$app->request->post( 'item13', 0 );
		    $session['item14'] = Yii::$app->request->post( 'item14', 0 );
		    $session['item15'] = Yii::$app->request->post( 'item15', 0 );
		    $session['estado'] = Yii::$app->request->post( 'estado', '' );
		    $session['BDMejora'] = Yii::$app->request->post( 'BD', 1 );				    
	
			if(isset($_POST['action'])) $session['actionMEJORA'] = $_POST['action'];
	
			$session->close();
			
			?>
			
			<script>
			/*	Se encarga de actualizar los datos de modal	*/
			$.pjax.reload({container:"#form-nuevaMejora",method:"POST"});
			
			/*	Se encarga de mostrar el modal	*/
			window.setTimeout(function() { $("#modal-nuevaMejora").modal("show"); }, 300);
			
			</script>
<?php

	}
	
Pjax::end();

?>

<script>
function filtraMejoras()
{
	if ($("#inm-filtraMejoras").is(":checked"))
	{
		$.pjax.reload({container:"#mejora-actualizaGrilla",method:"POST",data:{filtro:1}});
	} else 
	{
		$.pjax.reload({container:"#mejora-actualizaGrilla",method:"POST",data:{filtro:0}});
	}
}
</script>