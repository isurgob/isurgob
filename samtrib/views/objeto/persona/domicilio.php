<?php
use yii\helpers\Html;
use \yii\bootstrap\Modal;
use \yii\widgets\Pjax;
use app\utils\db\utb;

Pjax::begin(['id'=>'copiarDomi']);

    if (isset($_POST['arrayDomiPost'])) $modelodomipost=unserialize(urldecode(stripslashes($_POST['arrayDomiPost'])));
    if (isset($_POST['arrayDomiLeg'])) $modelodomileg=unserialize(urldecode(stripslashes($_POST['arrayDomiLeg'])));
    if (isset($_POST['arrayDomiRes'])) $modelodomires=unserialize(urldecode(stripslashes($_POST['arrayDomiRes'])));
?>

<table border='0'>
<tr>
	<td><label class="control-label">Postal:</label></td>
	<td width='5px'></td>
	<td>
		<!-- boton de b�squeda modal -->
			<?php
			Modal::begin([
                'id' => 'BuscaDomiP',
				'header' => '<h2>Búsqueda de Domicilio</h2>',
				'toggleButton' => [
                    'label' => '<i class="glyphicon glyphicon-search"></i>',
                    'class' => 'bt-buscar',
                    'style' => 'visibility:'.(utb::getExisteProceso(3602) ? 'visible' :'hidden')
                ],
                'closeButton' => [
                  'label' => '<b>X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ]
            ]);
            
            echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modelodomipost, 'tor' => 'OBJ']);
            
            Modal::end();
            ?>
            <!-- fin de boton de b�squeda modal -->
		<?= Html::input('text', 'domi_postal', $modelodomipost->domicilio, ['class' => 'form-control','id'=>'domi_postal','style'=>'width:440px;background:#E6E6FA;','disabled'=>'true']); ?>
		<?= Html::Button('Copiar',['class' => 'btn btn-success','style' => 'visibility:'.(utb::getExisteProceso(3602) ? 'visible' :'hidden'), 'onClick' => 'btCopiarDomi()']) ?>
	</td>
</tr>
<tr>
	<td><label class="control-label">Legal:</label></td>
	<td width='5px'></td>
	<td>
		<!-- boton de b�squeda modal -->
			<?php
			Modal::begin([
                'id' => 'BuscaDomiL',
				'header' => '<h2>Búsqueda de Domicilio</h2>',
				'toggleButton' => [
                    'label' => '<i class="glyphicon glyphicon-search"></i>',
                    'class' => 'bt-buscar',
                    'style' => 'visibility:'.(utb::getExisteProceso(3603) ? 'visible' :'hidden')
                ],
                'closeButton' => [
                  'label' => '<b>X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ]
            ]);
            
            echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modelodomileg, 'tor' => 'PLE']);
            
            Modal::end();
            ?>
            <!-- fin de boton de b�squeda modal -->
		<?= Html::input('text', 'domi_legal', isset($modelodomileg->domicilio) ? $modelodomileg->domicilio : '', ['class' => 'form-control','id'=>'domi_legal','style'=>'width:500px;background:#E6E6FA;','disabled'=>'true']); ?>
	</td>
</tr>
<tr>
	<td><label class="control-label">Residencial:</label></td>
	<td width='5px'></td>
	<td>
		<!-- boton de b�squeda modal -->
			<?php
			Modal::begin([
                'id' => 'BuscaDomiR',
				'header' => '<h2>Búsqueda de Domicilio</h2>',
				'toggleButton' => [
                    'label' => '<i class="glyphicon glyphicon-search"></i>',
                    'class' => 'bt-buscar'
                ],
                'closeButton' => [
                  'label' => '<b>X</b>',
                  'class' => 'btn btn-danger btn-sm pull-right',
                ]
            ]);
            
            echo $this->render('//objeto/domiciliobusca', ['modelodomi' => $modelodomires,'tor' => 'PRE']);
            
            Modal::end();
            ?>
            <!-- fin de boton de b�squeda modal -->
		<?= Html::input('text', 'domi_res', isset($modelodomires->domicilio) ? $modelodomires->domicilio : '', ['class' => 'form-control','id'=>'domi_res','style'=>'width:500px;background:#E6E6FA;','disabled'=>'true']); ?>
	</td>
</tr>	
</table>

<?php Pjax::end(); ?>

<script>
function btCopiarDomi()
{
	if ($("#domi_postal").val() !== "")
	{
		$("#domi_res").val($("#domi_postal").val());
		$("#domi_legal").val($("#domi_postal").val());
		
		$("#arrayDomiLeg").val($("#arrayDomiPost").val());
		$("#arrayDomiRes").val($("#arrayDomiPost").val());
		
		$.pjax.reload({container:"#copiarDomi",method:"POST",data:{arrayDomiPost:$("#arrayDomiPost").val(),
			arrayDomiLeg:$("#arrayDomiPost").val(),arrayDomiRes:$("#arrayDomiPost").val()}})
	
	}
}

</script>