<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;

$this->params['breadcrumbs'][] = ['label' => 'Tablas Auxiliares','url' => ['taux']];
$this->params['breadcrumbs'][] = $model->titulo;

/* @var $this yii\web\View */
/* @var $model app\models\tablaAux */
/* @var $form ActiveForm */

if (isset($consulta) == null) $consulta = 1;
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
    <tr><td colspan='2'></td></tr>
    <tr style='border-top:1px solid #ddd;'>
    	<td style="padding: 20px 0px;"><label>Filtar por Nombre</label></td>
    	<td><?php echo Html::input('text', 'txFiltroNombre', null, ['class' => 'form-control','id'=>'txFiltroNombre','onchange'=>'txFiltroChange()', 'maxlength'=> '25','style'=>'width:550px']);  ?></td>
    </tr>
    <tr><td colspan='2'></td></tr>
    </table>
    
    <?php
		Pjax::begin(['id'=>'PjaxBuscarCalle']);
			if (!isset($_GET['page'])) Yii::$app->session['cond'] = '';
			
			$cond = (isset($_POST['condicion']) ? $_POST['condicion'] : Yii::$app->session['cond']);
			
			if ($cond != ''){
				Yii::$app->session['cond'] = $cond;
				
				$sql = "select count(*) from domi_calle t";
				$sql .= " where ".$cond;
				$count = Yii::$app->db->createCommand($sql)->queryScalar();
				
				$sql = "select t.*,t.calle_id cod,to_char(t.fchmod,'dd/mm/yyyy') || ' - ' || u.nombre as modif";
		        $sql .= " from domi_calle t inner join sam.sis_usuario u on t.usrmod=u.usr_id "; 
		        $sql .= " where ".$cond." Order By nombre ";
		        
		        $tabla = null;
		        $tabla = new SqlDataProvider([
		            'sql' => $sql,
		            'key' => 'cod',
		            'totalCount' => (int)$count,
					'pagination'=> [
						'pageSize'=>15,
					],
		        ]); 	
			}
			
			echo GridView::widget([
				'id' => 'GrillaTablaAux',
				'dataProvider' => $tabla,
				'headerRowOptions' => ['class' => 'grillaGrande'],
				'rowOptions' => function ($model,$key,$index,$grid) 
								{
									return [
										'onclick' => 'CargarControles("'.$model['calle_id'].'","'.$model['nombre'].'","'.$model['tcalle'].'","'.$model['modif'].'")'
									];
								},
				'columns' => [
	            		['attribute'=>'cod','header' => 'Cod', 'contentOptions'=>['style'=>'width:40px;text-align:right', 'class' => 'grillaGrande']],
	            		['attribute'=>'nombre','header' => 'Nombre', 'contentOptions'=>['style'=>'width:190px', 'class' => 'grillaGrande']],
	            		['attribute'=> 'tcalle_nom', 'header' => 'Tipo',
	            				'value' => function($data) { return (utb::getCampo('domi_tcalle','cod='.$data['tcalle'])); }, 
								'contentOptions'=>['style'=>'width:140px;', 'class' => 'grillaGrande']],
	            		['attribute'=>'modif','header' => 'Modificación', 'contentOptions'=>['style'=>'width:140px', 'class' => 'grillaGrande']],
	            		
	            		 
	            		['class' => 'yii\grid\ActionColumn','contentOptions'=>['style'=>'width:35px','class' => 'grillaGrande'],'template' => (($model->accesoedita > 0 && utb::getExisteProceso($model->accesoedita)) ? '{update} {delete}' : ''),
	            			'buttons'=>[
								'update' => function($url,$model,$key)
	            						{
	            							return Html::Button('<span class="glyphicon glyphicon-pencil"></span>',
	            										[
															'class' => 'bt-buscar-label', 'style' => 'color:#337ab7;font-size:9px',
															'onclick' => 'CargarControles("'.$model['calle_id'].'","'.$model['nombre'].'",'.$model['tcalle'].',"'.$model['modif'].'");' .
																	'ActivarControles(3);'
														]
	            									);
	            						},
	            				'delete' => function($url,$model,$key)
	            						{
	            							return Html::Button('<span class="glyphicon glyphicon-trash"></span>',
	            										[
															'class' => 'bt-buscar-label', 'style' => 'color:#337ab7;font-size:9px',
															'onclick' => 'CargarControles("'.$model['calle_id'].'","'.$model['nombre'].'",'.$model['tcalle'].',"'.$model['modif'].'");' .
																	'ActivarControles(2);'
														]
	            									);
	            						}
	            			]
	            	   ],
	            	   
	        	],
	    	]); 
	    
	    Pjax::end();
	    	
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
			<td><label>Tipo: </label></td>
			<td>
				<?= Html::dropDownList('tipo', $tcalle, utb::getAux('domi_tcalle'),['class' => 'form-control','id'=>'tipo','style'=>'width:200px','prompt'=>'Seleccionar...']); ?>
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
    	

</div><!-- site-auxedit -->

<script>
function ActivarControles(accion)
{
	$("#txCod").prop("readonly",(accion!==0 || <?= $model->autoinc ?> == 1));
	$("#txNombre").prop("disabled",(accion==1 || accion==2));
	$("#tipo").prop("disabled",(accion==1 || accion==2));
	$("#txAccion").val(accion);
	$("#btNuevo").prop("disabled",(accion!==1));
	$("#txFiltroNombre").prop("disabled",(accion!==1));
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

function CargarControles(cod,nombre,tipo,modif)
{
	$("#txCod").val(cod);
	$("#txNombre").val(nombre);
	$("#tipo").val(tipo);
	$("#txModif").val(modif);
	
	$('html, body').animate({scrollTop: $(".site-auxedit").height()}, 1000); //mueve escroll a la parte de los datos
}

function btNuevoClick()
{
	$("#txCod").val("");
	$("#txNombre").val("");
	$("#tipo").val("");
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
	if ($("#tipo").val()=="")
	{
		error += "<li>Seleccione un tipo</li>";
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
		container:"#PjaxBuscarCalle",
		data:{
				condicion:cond
			},
		method:"POST"
	});	
}
</script>

<?= "<script>ActivarControles(".$consulta.")</script>"; ?>