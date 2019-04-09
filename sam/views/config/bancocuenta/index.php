<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\models\config\BancoCuenta;
use \yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use app\utils\db\Fecha;
use yii\bootstrap\Alert;
use app\utils\db\utb;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = 'Cuentas Bancarias';
$this->params['breadcrumbs'][]= ['label' => 'Configuraciones', 'url' => Yii::$app->param->sis_url.'site/config'];
$this->params['breadcrumbs'][] = $title;
if (isset($consulta)==null) $consulta = 1;
?>
<div class="banco-cuenta-index">

    <h1><?= Html::encode($title) ?></h1>

    <p  style='float:right;'>
        <?php
        	if (utb::getExisteProceso(4661))
        		echo Html::Button('Nuevo',['class' => 'btn btn-success','onclick' => 'btNuevaCuentaClick()']); 
    	?>
    </p>

    <?= GridView::widget([
    	'id' => 'GrillaTablaBcoCta',
        'dataProvider' => $dataProvider,
        'headerRowOptions' => ['class' => 'grilla'],
        'rowOptions' => function ($model,$key,$index,$grid){
        					return EventosGrilla($model);
        					
        					},
        'columns' => [
            ['attribute' => 'bcocta_id', 'header' => 'Código', 'contentOptions' => ['style' => 'width:5%;','class' => 'grilla']],
            ['attribute' => 'titular', 'header' => 'Titular', 'contentOptions' => ['style' => 'width:20%;','class' => 'grilla']],
            ['attribute' => 'cbu', 'header' => 'CBU', 'contentOptions' => ['class' => 'grilla']],
                        
            ['class' => 'yii\grid\ActionColumn', 'contentOptions' => ['style' => 'width:7%;','align'=>'center','class' => 'grilla'], 'template' => (utb::getExisteProceso(4661) ? '{update}{delete}' : ''),
               'buttons' => [
							'update' => function($url,$model,$key)
            						{   
            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
            										[
            											'class' => 'bt-buscar-label',
            											'style' => 'color:#337ab7;',
            										    'onclick' => 'btModifCuentaClick("'.$model['bcocta_id'].'","'.$model['titular'].'","'.$model['cbu'].'","'.$model['tipo'].'","'.$model['tmoneda'].'","'.$model['tuso'].'","'.$model['ultcheque'].'","'.$model['modif'].'")'
													]											
            									);
            						},
            						
            				'delete' => function($url,$model,$key)
            						{      
            							
    							        return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
    										[
    											'class' => 'bt-buscar-label',
    											'style' => 'color:#337ab7;',
    										    'onclick' => 'btEliminarCuentaClick("'.$model['bcocta_id'].'","'.$model['titular'].'","'.$model['cbu'].'","'.$model['tipo'].'","'.$model['tmoneda'].'","'.$model['tuso'].'","'.$model['ultcheque'].'","'.$model['modif'].'")'
											]											
    									);          									
            						}		
	                            ]],
					        ],
					    ]); ?>
    
    
   <?php
    
   Pjax::begin(['id' => 'formDatos']);
       
        if (isset($_POST['consulta'])) $consulta=$_POST['consulta'];
        if (isset($_POST['bcocta_id'])) $bcocta_id=$_POST['bcocta_id'];
    	if (isset($_POST['titular'])) $titular=$_POST['titular'];
    	if (isset($_POST['cbu'])) $cbu=$_POST['cbu']; 
    	if (isset($_POST['tipo'])) $tipo=$_POST['tipo'];
    	if (isset($_POST['tmoneda'])) $tmoneda=$_POST['tmoneda'];
    	if (isset($_POST['tuso'])) $interna=$_POST['tuso']; 
		if (isset($_POST['ultcheque'])) $ultcheque=$_POST['ultcheque']; 
    	if (isset($_POST['fchmod'])) $fchmod=$_POST['fchmod'];
        
    	if (isset($_POST['bcocta_id'])){
        		
    		$model = BancoCuenta::findOne($bcocta_id);
    		
    	}else {
    		$model = new BancoCuenta();	
    	}
    	
    	echo $this->render('_form',['model' => $model,'consulta' => $consulta]);
    	   
    Pjax::end();
    
  ?>
    
    
     <?php 
	function EventosGrilla ($m) 
	{        
       $par = "consulta:1,bcocta_id:".$m['bcocta_id'].",titular:".$m['titular'].",cbu:".$m['cbu'].",tipo:".$m['tipo'];
       $par .= ",tmoneda:'".$m['tmoneda']."',tuso:'".$m['tuso']."',ultcheque:'".$m['ultcheque']."',fchmod:'".$m['modif']."'";     
      
       return ['onclick' => '$.pjax.reload({container:"#formDatos",data:{'.$par.'},method:"POST"})'];
       
                  
    }
?> 



<?php
		//-------------------------seccion de mensajes-----------------------
		if(!empty($_GET['mensaje'])){
	
			switch ($_GET['mensaje'])
			{
					case 'grabado' : $mensaje = 'Datos Grabados.'; break;
					case 'delete' : $mensaje = 'Datos Borrados.'; break;
					default : $mensaje = '';
			}
		}
	
		Alert::begin([
			'id' => 'MensajeInfoCB',
			'options' => [
			'class' => 'alert-info',
			'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
			],
		]);	

		if ($mensaje !== '') echo $mensaje;
		
		Alert::end();
		
		if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#MensajeInfoCB').alert('close'); }, 5000)</script>"; 	
		
		
		//--------------------------seccion de errores------------------------
		
		/*if(isset($_GET['error']) and $_GET['error'] !== '') {  
		echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $_GET['error'] . '</ul></div>';
	     }*/ 


?>

<script>
                                 
    function btModifCuentaClick(bcocta_id,titular,cbu,tipo,tmoneda,tuso,ultcheque,fchmod){
    
     event.stopPropagation();
	 $.pjax.reload(
		{
			container:"#formDatos",
			data:{
					consulta:3,
					bcocta_id:bcocta_id,
					titular:titular,
					cbu:cbu,
					tipo:tipo,
					tmoneda:tmoneda, 
					tuso:tuso, 
					ultcheque:ultcheque,
					fchmod:fchmod 
				},
			method:"POST"
		});
      }
      
     function btEliminarCuentaClick(bcocta_id,titular,cbu,tipo,tmoneda,tuso,ultcheque,fchmod){
    
     event.stopPropagation();
	 $.pjax.reload(
		{
			container:"#formDatos",
			data:{
					consulta:2,
					bcocta_id:bcocta_id,
					titular:titular,
					cbu:cbu,
					tipo:tipo,
					tmoneda:tmoneda, 
					tuso:tuso, 
					ultcheque:ultcheque,
					fchmod:fchmod 
				},
			method:"POST"
		});
      }
      
     function btNuevaCuentaClick(){
    
     event.stopPropagation();
	 $.pjax.reload(
		{
			container:"#formDatos",
			data:{
					consulta:0

				},
			method:"POST"
		});
      } 

</script>

</div>
