<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

/**
 * $taccion = 6 => Exento
 * $taccion = 3 => Unifica
 * $taccoin = 4 => Desunifica
 */
 

$title = "Objeto ".utb::getCampo("objeto_taccion","cod=".$taccion);
$this->params['breadcrumbs'][] = ['label' => 'Objeto: '.$id, 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = $title;

Pjax::begin(['id' => 'txObjetoUnif']);
	
	if (isset($_POST['obj_unif'])) 
	{
		$obj_id=$_POST['obj_unif'];
		
		echo '<script>$("#obj_unif_nom").val("")</script>';
		if (strlen($obj_id) < 8)
		{
			$obj_id = utb::GetObjeto(utb::GetTObj($obj_id),(int)$obj_id);
			echo '<script>$("#obj_unif").val("'.$obj_id.'")</script>';
		}
		if (utb::GetTObj($obj_id)==utb::GetTObj($id))
		{
			$obj_nom = utb::getNombObj("'".$obj_id."'");
	
			echo '<script>$("#obj_unif_nom").val("'.$obj_nom.'")</script>';		
		}
		
	}
	
Pjax::end();
?>

<div class="persona-view">
	<table border='0' width='100%' style='border-bottom:1px solid #ddd; margin-bottom:10px'>
    <tr>
    	<td><h1><?= Html::encode($title) ?></h1></td>
    </tr>
	</table>
	
	<p>
		<label>Objeto: </label><input class='form-control' style='width:65px;background:#E6E6FA' disabled value='<?=$id?>' />
		<label>Nombre: </label><input class='form-control' style='width:300px;background:#E6E6FA' disabled value='<?=utb::getNombObj("'".$id."'")?>' />
		<label>Estado Actual: </label><input class='form-control' style='width:60px;background:#E6E6FA' disabled value='<?=utb::getCampo("v_objeto","obj_id='".$id."'","est_nom")?>' />
	</p>
	
	<div class="form" style='padding-right:5px;padding-bottom:15px; margin-bottom:5px'>
		<?php 
			$form = ActiveForm::begin(['action' => ['estado', 
														'id' => $id,
														'taccion' => $taccion, 
														'accion' => 1
													],
										'id' => 'formObjetoEstado']);
			
		?>
		
		<table border='0'>
		<?php 
		if ($taccion == 3)
		{
		?>
		<tr>
		<td><label>Objeto con el que Unifica:&nbsp;</label></td>
		<td>
			<?= Html::input('text', 'obj_unif', null, ['class' => 'form-control','id'=>'obj_unif','maxlength'=>'8','style'=>'width:70px', 'onchange' => '$.pjax.reload({container:"#ObjUnifica",data:{obj_unif:$("#txObjetoUnif").val()},method:"POST"})']); ?>
			<?php
//			$direc = '//objeto/persona/buscarav';
//			
//			switch(utb::GetTObj($id))
//			{
//				case 1:
//					$direc = '//objeto/inm/buscarav';
//					break;
//				
//				case 2:
//					$direc = '//objeto/comer/buscarav';
//					break;
//					
//				case 3:
//					$direc = '//objeto/persona/buscarav';
//					break;
//					
//				case 4:
//					$direc = '//objeto/cem/buscarav';
//					break;
//					
//				case 5:
//					$direc = '//objeto/rodado/buscarav';
//					break;
//				
//			}
		
			
			Modal::begin([
                'id' => 'BuscaObjunif',
                'header' => '<h1>Búsqueda de objeto</h1>',
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
            
            echo $this->render('//objeto/objetobuscarav',
            						['id' => 'unif', 'txCod' => 'obj_unif', 'txNom' => 'obj_unif_nom',
									 'txDoc' => 'doc_origen', 'tobj' => utb::GetTObj($id), 'selectorModal' => '#BuscaObjunif'
            					]);
            
            Modal::end();
            ?>
            <!-- fin de boton de búsqueda modal -->
			<?= Html::input('text', 'obj_unif_nom', null, ['class' => 'form-control','id'=>'obj_unif_nom','style'=>'width:340px','disabled'=>'true']); ?>
		</td>
		</tr>
		<?php } ?>
		<tr>
		<td><label>Expediente: &nbsp;</label></td>
		<td>
			<?= Html::input('text', 'expe', null, ['class' => 'form-control','id'=>'expe','maxlength'=>'20','style'=>'width:200px']); ?>
		</td>
		</tr>
		<tr>
		<td valign='top'><label>Observación: &nbsp;</label></td>
		<td>
			<?= Html::textarea('obs', null, ['class' => 'form-control','id'=>'obs','maxlength'=>'500','style'=>'width:440px; height:100px; max-width:440px; max-height:150px;']); ?>
		</td>
		</tr>
			
		</table>
	</div>
</div>


<div style="margin-top:5px;">
	<?= Html::Button('Grabar', ['class' => 'btn btn-success', 'onclick' => 'btEstadoGrabar()']); ?>
</div>

<?php
		
	Pjax::begin(['id' => 'divError', 'options' => ['style' => 'margin-top:5px;']]);

		if (isset($_POST['err'])) $error = $_POST['err'];

		if(isset($error) and $error !== '')
		{  
			echo '<div class="error-summary">Por favor corrija los siguientes errores:<br/><ul>' . $error . '</ul></div>';
		} 
	Pjax::end();

	ActiveForm::end(); 
?>

<script>

function btEstadoGrabar()
{
	
	error = "";
	est = "";
	
	if ($("#obs").val()=="")
	{
		error = "<li>Ingese una observación</li>";
	} 
	
	if (<?= $taccion ?> == 3 && $("#obj_unif_nom").val()=="")
	{
		error += "<li>Ingrese el Objeto con el que se va a Unificar</li>";
	}
		
	if (error == "")
	{
		$("#formObjetoEstado").submit();
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