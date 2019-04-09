<?php

use yii\helpers\Html;
use yii\helpers\BaseUrl;
use yii\widgets\ActiveForm;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;
use yii\grid\GridView;

$action = BaseUrl::toRoute(['//ctacte/libredeuda/accion']); // Yii::$app->request->baseUrl.'/index.php?r=ctacte/libredeuda/accion';

$error = Yii::$app->session['error'];

Pjax::begin(['id' => 'ObjNombreAcc']);
	
	$objeto_id = Yii::$app->request->post( 'objeto_idAcc', '' );
	$tobj = Yii::$app->request->post( 'tobjAcc', 4 );
	$objeto_nom = '';
	
	if (strlen($objeto_id) < 8)
	{
		$objeto_id = utb::GetObjeto((int)$tobj,(int)$objeto_id);
	}
	
	
	if ( $tobj != utb::getTObj( $objeto_id ) )
			$objeto_id = '';
			
	# Validar existencia del objeto	
	if ( utb::verificarExistencia('objeto',"obj_id = '" . $objeto_id . "'") )
	{
		//Buscar el nombre de comercio y asignarlo en el Edit correspondiente
		$objeto_nom = utb::getNombObj( "'" . $objeto_id . "'" );
		
	} else 
	{
		$objeto_id = '';
	}
	
	echo '<script>$("#txObj_IdAcc").val("'.$objeto_id.'")</script>';
	echo '<script>$("#txObjNomAcc").val("'.$objeto_nom.'")</script>';		
	
	if ( $objeto_id != '' )
	{
		if (utb::getCampo('ctacte_libredeuda_bloq',"obj_id='".$objeto_id."' and est='A'",'count(*)') > 0){
			$obs_bloq = utb::getCampo('ctacte_libredeuda_bloq',"obj_id='".$objeto_id."' and est='A'",'obs');
			echo "<script>";
			echo 'mostrarErrores( ["El Objeto ' . $objeto_id . ' se encuentro bloqueado. '.$obs_bloq.'. No podrá emitir libre deuda." ], "#libreDeuda_errorSummary" );';
			echo '$("#txObj_Id").val("");';
			echo '$("#txObjNom").val("");';
			echo "</script>";
		} else {
			echo "<script>";
			echo '$("#libreDeuda_errorSummary").css("display", "none");';
			echo "</script>";
		}
	} else 
	{
		echo "<script>";
		echo '$("#libreDeuda_errorSummary").css("display", "none");';
		echo "</script>";
	}
		
	
	Modal::begin([
    	'id' => 'BuscaObjcc',
    	'header' => '<h2 style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif;">Buscar Objeto</h2>',
       	'closeButton' => [
       		'label' => '<b>X</b>',
           	'class' => 'btn btn-danger btn-sm pull-right',
       	],
       	'size' => 'modal-lg',
    ]);
      
    echo $this->render('//objeto/objetobuscarav',[
			'id' => 'cc', 'txCod' => 'txObj_IdAcc', 'txNom' => 'txObjNom', 'tobjeto' => $tobj, 'selectorModal' => '#BuscaObjcc'
    	]);
       
    Modal::end();
	
Pjax::end();

$form = ActiveForm::begin(['id' => 'frmLibreDeudaAccion', 'action' => $action,
   								'fieldConfig' => [
      										'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>",
       										],
       						]);
        
	echo '<input type="text" name="txAccion" id="txAccion" value='.$accion.' style="display:none">';
	echo '<input type="text" name="txQuitar" id="txQuitar" value=0 style="display:none">';
	echo '<input type="text" name="txObjQuitar" id="txObjQuitar" value="" style="display:none">';
?>

<div id="libreDeuda_accesos">	    
	
	<div class="form" style='padding:5px 10px; margin-bottom:10px;width:660px'>
		<table border='0'>
		<tr>
			<td><label>Objeto:</label></td>
			<td>
		    	<?= Html::dropDownList('dlTObjetoAcc', null, utb::getAux('objeto_tipo'), ['class' => 'form-control','id'=>'dlTObjetoAcc',
		    		'onchange'=>'$.pjax.reload({container:"#ObjNombreAcc",data:{objeto_idAcc:$("#txObj_IdAcc").val(),tobjAcc:$("#dlTObjetoAcc").val()},method:"POST"})']); ?>
		    	
		    	<?= Html::input('text', 'txObj_IdAcc', null, ['class' => 'form-control','id'=>'txObj_IdAcc','style'=>'width:80px','maxlength'=>'8',
					'onchange'=>'$.pjax.reload({container:"#ObjNombreAcc",data:{objeto_idAcc:$("#txObj_IdAcc").val(),tobjAcc:$("#dlTObjetoAcc").val()},method:"POST"})']); ?>
			</td>
			<td>
				<!-- boton de b�squeda modal -->
				<?= Html::button('<i class="glyphicon glyphicon-search"></i>',[
           				'class' => 'bt-buscar',
           				'id' => 'libreDeuda_btBuscaObjAcc',
           				'onclick' => '$("#BuscaObjcc").modal("show")',
       				]);
       			?>
	        </td>
	        <td>
		        <!-- fin de boton de b�squeda modal -->
				<?= Html::input('text', 'txObjNomAcc', null, ['class' => 'form-control','id'=>'txObjNomAcc','style'=>'width:340px','disabled'=>'true']); ?>
			</td>
		</tr>
		<?php if ($accion == 'bloq'){ ?>
			<tr>
				<td valign="top"><b>Observación:</b></td>
				<td colspan="3" >
					<?= Html::textarea('txObsAcc', null, ['class' => 'form-control','id'=>'txObsAcc','style'=>'width:550px;height:70px;resize:none']); ?>
				</td>
			</tr>
		<?php } ?>
	</table>
	
	</div>
	
	<table style="margin-bottom: 8px">
		<tr>
			<td colspan='5'>
				<?php echo Html::Button('Aceptar',['class' => 'btn btn-success','onClick' => 'btAceptarAcc()']); ?>
			</td>
		</tr>
	</table>
	
	<div id="libreDeuda_accesos_errorSummary" class="error-summary" style="display:none;margin-bottom: 8px">
		
		<ul>
		</ul>
	
	</div>
	
</div>
	<?php 
		
		if( isset( $error ) && $error !== '' )
		{  
			?>
			<script>
			
			$(document).ready(function() {
				
				mostrarErrores( [<?= $error ?>], "#libreDeuda_errorSummary" );
			});
			
			<?php
		} 
		
		$tabla = "ctacte_libredeuda_".$accion." b left join objeto o on b.obj_id=o.obj_id ";
        $tabla .= "left join sam.sis_usuario u ON b.usrmod = u.usr_id ";
        
		$dataprovider = utb::DataProviderGeneral($tabla, "", 
			"'".$accion."' acc,b.obj_id, o.nombre as obj_nom, b.est,(u.nombre || ' - ') || to_char(b.fchmod, 'DD/MM/YYYY') AS modif", 50);
		
		echo GridView::widget([
			'id' => 'GrillaListLDeuda',
			'dataProvider' => $dataprovider,
			'headerRowOptions' => ['class' => 'grilla'],
			'rowOptions' => ['class' => 'grilla'],
			'columns' => [
				['attribute'=>'obj_id','label' => 'Objeto', 'contentOptions'=>['style'=>'width:40px']],
	    		['attribute'=>'obj_nom','label' => 'Nombre','contentOptions'=>['style'=>'width:200px']],
	    		['attribute'=>'est','label' => 'Est','contentOptions'=>['style'=>'width:40px;text-align:center']],
	    		['attribute'=>'modif','label' => 'Modificación', 'contentOptions'=>['style'=>'width:150px']],
	    		
	    		['class' => 'yii\grid\ActionColumn','options'=>['style'=>'width:20px'],'template' => '{delete}',
					'buttons'=>[
						'delete' => function($url,$model,$key)
	    						  {
	    							return Html::a('<span class="glyphicon glyphicon-trash"></span>',null,['onclick' => 'btQuitarAcc("'.$model['obj_id'].'")']);
	    						  }
	    			]
				]    
	        ]
    	]); 
		
		ActiveForm::end(); 
	?>


<script>
function btAceptarAcc()
{
	var error = new Array();
	var acc = "<?=$accion?>";
	
	if ($("#txObj_IdAcc").val() == '' ) 
		error.push( "Ingrese un objeto." );
	
	if (acc == "bloq")
		if ($("#txObsAcc").val() == '' ) 
			error.push( "Ingrese una observación." );
	
	if ( error.length == 0 )
	{
		$("#libreDeuda_accesos_errorSummary").css("display", "none");
		
		$("#txObjQuitar").val('');
		$("#txQuitar").val(0);
		$("#frmLibreDeudaAccion").submit();
		$("#<?=$accion?>, .window").modal("hide");
	
	} else 
	{
		mostrarErrores( error, "#libreDeuda_accesos_errorSummary" );
	}
}

function btQuitarAcc(obj)
{
	$("#txObjQuitar").val(obj);
	$("#txQuitar").val(1);
	$("#frmLibreDeudaAccion").submit();
}
</script>