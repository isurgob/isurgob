<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\utils\db\utb;
use yii\bootstrap\Alert;

$this->title = 'Reemplazar Persona ';
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin(['id' => 'ObjReemplazar']);
	
	if (isset($_POST['control'])) 
	{
		$control=$_POST['control'];
		$obj_id=$_POST['obj_id'];
		
		if ($control == 'O')
		{
			echo '<script>$("#obj_origen_nom").val("")</script>';
			if (strlen($obj_id) < 8)
			{
				$obj_id = utb::GetObjeto(3,(int)$obj_id);
				echo '<script>$("#txObjetoOrigen").val("'.$obj_id.'")</script>';
			}
	
			if (utb::GetTObj($obj_id)==3)
			{
				$obj_nom = utb::getNombObj("'".$obj_id."'");
	
				echo '<script>$("#obj_origen_nom").val("'.$obj_nom.'")</script>';		
			}
		}
		if ($control == 'D')
		{
			echo '<script>$("#obj_destino_nom").val("")</script>';
			if (strlen($obj_id) < 8)
			{
				$obj_id = utb::GetObjeto(3,(int)$obj_id);
				echo '<script>$("#txObjetoDestino").val("'.$obj_id.'")</script>';
			}
	
			if (utb::GetTObj($obj_id)==3)
			{
				$obj_nom = utb::getNombObj("'".$obj_id."'");
	
				echo '<script>$("#obj_destino_nom").val("'.$obj_nom.'")</script>';		
			}
		}
	}
	
Pjax::end();
?>
<div class="persona-view">	
	<table width='100%'>
	<tr>
		<td><h1 id='h1titulo'><?= $this->title ?></h1></td>
	</tr>
	</table>
	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>
	<?php 
		if (isset($mensaje) == null) $mensaje = '';
    	Alert::begin([
    		'id' => 'AlertaPersona',
			'options' => [
        	'class' => (isset($_GET['m']) and $_GET['m'] == 1) ? 'alert-success' : 'alert-info',
        	'style' => $mensaje !== '' ? 'display:block' : 'display:none' 
    		],
		]);

		if ($mensaje !== '') echo $mensaje;
				
		Alert::end();
				
		if ($mensaje !== '') echo "<script>window.setTimeout(function() { $('#AlertaPersona').alert('close'); }, 5000)</script>";
    			
		$form = ActiveForm::begin(['action' => ['reemplaza','list'=>0],'id' => 'form-persona-reemplaza']);
		
		echo Html::input('hidden', 'txAccion', 0, ['id'=>'txAccion']);
	?>      
	<table border='0'>
	<tr>
		<td><label>Origen: &nbsp;</label></td>
		<td colspan='2'>
			<?= Html::input('text', 'txObjetoOrigen', null, ['class' => 'form-control','id'=>'txObjetoOrigen','maxlength'=>'8','style'=>'width:70px', 'onchange' => '$.pjax.reload({container:"#ObjReemplazar",data:{control:"O",obj_id:$("#txObjetoOrigen").val()},method:"POST"})']); ?>
			<?php
			Modal::begin([
                'id' => 'BuscaObjori',
				'toggleButton' => [
                    'label' => '<i class="glyphicon glyphicon-search"></i>',
                    'class' => 'bt-buscar'
                ],
                'closeButton' => [
                  'label' => '<b>X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
                'size' => 'modal-lg',
            ]);
            
            echo $this->render('//objeto/objetobuscarav', ['id' => 'ori', 'txCod' => 'txObjetoOrigen', 'txNom' => 'obj_origen_nom', 'txDoc' => 'doc_origen', 'selectorModal' => '#BuscaObjori', 'tobjeto' => 3]);
            
            Modal::end();
            ?>
            <!-- fin de boton de b�squeda modal -->
			<?= Html::input('text', 'obj_origen_nom', null, ['class' => 'form-control','id'=>'obj_origen_nom','style'=>'width:340px','disabled'=>'true']); ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			Documento:
			<?= Html::input('text', 'doc_origen', null, ['class' => 'form-control','id'=>'doc_origen','style'=>'width:80px','disabled'=>'true']); ?>
		</td>
		<td align='right' style='color:#c00000; font-style:italic'>(Persona a Eliminar)</td>
	</tr>
	
	<tr>
		<td><label>Destino: &nbsp;</label></td>
		<td colspan='2'>	
			<?= Html::input('text', 'txObjetoDestino', null, ['class' => 'form-control','id'=>'txObjetoDestino','maxlength'=>'8','style'=>'width:70px', 'onchange' => '$.pjax.reload({container:"#ObjReemplazar",data:{control:"D",obj_id:$("#txObjetoDestino").val()},method:"POST"})']); ?>
			<?php
			Modal::begin([
                'id' => 'BuscaObjdes',
				'toggleButton' => [
                    'label' => '<i class="glyphicon glyphicon-search"></i>',
                    'class' => 'bt-buscar'
                ],
                'closeButton' => [
                  'label' => '<b>X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ],
                'size' => 'modal-lg',
            ]);
            
            echo $this->render('//objeto/objetobuscarav', ['id' => 'des', 'txCod' => 'txObjetoDestino', 'txNom' => 'obj_destino_nom', 'txDoc' => 'doc_destino', 'txEst' => 'txEstDest', 'selectorModal' => '#BuscaObjdes', 'tobjeto' => 3]);
            
            Modal::end();
            
            echo Html::input('hidden', 'txEstDest', null, ['id'=>'txEstDest']);
            ?>
            <!-- fin de boton de b�squeda modal -->
			<?= Html::input('text', 'obj_destino_nom', null, ['class' => 'form-control','id'=>'obj_destino_nom','style'=>'width:340px','disabled'=>'true']); ?>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
			Documento:
			<?= Html::input('text', 'doc_destino', null, ['class' => 'form-control','id'=>'doc_destino','style'=>'width:80px','disabled'=>'true']); ?>
		</td>
		<td align='right' style='color:#c00000; font-style:italic'>(Persona a Mantener)</td>
	</tr>
	<tr>
		<td colspan='3'><?= Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick' => 'btReemplazaGrabar()']); ?></td>
	</tr>
	<tr>
		<td colspan='3'>
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
	  </td>
	</tr>
	</table>
	</div>
</div>

<script>

function btReemplazaGrabar()
{
	error = "";
	est = "";
	
	if ($("#obj_origen_nom").val()=="")
	{
		error += "<li>Ingrese el Objeto Origen</li>";
	}
	if ($("#obj_destino_nom").val()=="")
	{
		error += "<li>Ingrese el Objeto Destino</li>";
	}
	if ($("#txEstDest").val() == 'B')
	{
		error += "<li>El Objeto Destino se encuentra dado de baja</li>";
	}
	
	if (error == "")
	{
		$("#txAccion").val("1");
		$("#form-persona-reemplaza").submit();
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