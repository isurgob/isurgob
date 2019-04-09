<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\utils\db\utb;
use \yii\widgets\Pjax;
use \yii\bootstrap\Modal;

?>
<style>
#FiscalizacionBuscar .modal-content{
    width:610px !important;
}
</style>
<?php

Pjax::begin(['id' => 'PjaxBuscarComercio', 'enablePushState' => false, 'enableReplaceState' => false]);
    
    $comer_id = Yii::$app->request->post('objeto_id', '');
    
    if ($comer_id != '')
    {
        
        if (strlen($comer_id) < 8)
        {
            $comer_id = utb::GetObjeto(2,(int)$comer_id);
        }
            
        $objeto_nom = utb::getNombObj("'".$comer_id."'");
        
        if ($objeto_nom != '')
        {
            echo '<script>$("#txObj_Id").val("'.$comer_id.'")</script>';
            echo '<script>$("#txObjNom").val("'.$objeto_nom.'")</script>';  
        
        } else 
        {
            ?>
            <script>
            $("#txObj_Id").val("");
            $("#txObjNom").val("");             
            
            $("#PjaxBuscarComercio").on("pjax:end", function () {
                
                $("#txObj_Id").focus();
                $("#PjaxBuscarComercio").off("pjax:end");   
            });
            </script>
            <?php
                
                
        }
    }
    
Pjax::end();

$form = ActiveForm::begin(['action' => ['buscar'],'id'=>'frmBuscarFiscalizacion']);
?>

<!-- Tabla para Nº de Fiscalización -->
<table border="0" style="color:#000;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">
    <tr>
        <td width="115px"><?= Html::radio('rbID',true,['id'=>'rbID','label'=>'ID Fiscalización:','onchange' => 'ControlesBuscarFiscalizacion("txCodigo")'])?></td>
        <td>
            <?= Html::input('text','fiscalizacion_id',null,[
                    'id'=>'fiscalizacionBuscar_txIDFiscalizacion',
                    'class'=>'form-control',
                    'style'=>'width:50px;',
                    'onkeypress'=>'return justNumbers(event)',
                    'maxlength'=>'8']);
            ?>
        </td>
    </tr>
    <tr>
        <td><?= Html::radio('rbComercio',false,['id'=>'rbComercio','label'=>'Comercio:','onchange' => 'ControlesBuscarFiscalizacion("rbComercio")'])?></td>
        <td>
            <?= Html::input('text', 'fiscalizacion_obj_id', null, [
                    'id'=>'txObj_Id',
                    'disabled' => true,
                    'class' => 'form-control',
                    'style'=>'width:70px;text-align:center',
                    'maxlength'=>'8',
                    'onchange'=>'$.pjax.reload({container:"#PjaxBuscarComercio",method:"POST",data:{objeto_id:$("#txObj_Id").val()}})'
                ]); 
            ?>
        </td>
        <td>
            <?= Html::Button("<i class='glyphicon glyphicon-search'></i>",[
                    'class' => 'bt-buscar', 
                    'id' => 'btBuscarObj',
                    'disabled' => true,
                    'onclick' => '$("#fiscalizaBuscaComer").css("display", "block");']); ?>
        </td>
        <td>        
            <?= Html::input('text', 'txObjNom', null, ['class' => 'form-control','id'=>'txObjNom','style'=>'width:200px','disabled'=>true]);    ?>
        </td>
    </tr>
</table>

<div id='fiscalizaBuscaComer' style='color:#000 !important;font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px;display:none;margin:20px 0px;'>
    <div align='right'>
        <?= Html::Button("x",['class' => 'bt-rojo', 'onClick' => '$("#fiscalizaBuscaComer").css("display", "none");'])?>
    </div>
        <div>
        <?php
			echo $this->render('//objeto/objetobuscarav', ['id' => 'fiscalizacionBuscar', 'txCod' => 'txObj_Id', 'txNom' => 'txObjNom', 'tobjeto' => 2]);
        ?>
    </div>
</div>

<table style="font-family:Helvetica Neue, Helvetica, Arial, sans-serif; font-size:12px">    
    <tr>
        <td colspan='3'>
            <br><div id="errorbuscafacilida" style="display:none;" class="alert alert-danger alert-dismissable"></div>
        </td>
    </tr>   
    <tr>
        <td>
            <?= Html::Button('Aceptar',['class' => 'btn btn-success', 'id' => 'btBuscarPlanAceptar', 'onClick' => 'ControlesBuscarFiscalizacion("btAceptar");'])?>
        </td>
    </tr>
</table>


<?php ActiveForm::end(); ?>

<script>
function ControlesBuscarFiscalizacion(control)
{
    $("#BuscaObjddjjbuscar").css("display", "none");
    
    if (control=="rbComercio" || control=="txCodigo")
    {
        $("#txObj_Id").val("");
        $("#txObjNom").val("");
        $("#txNumFiscalizacion").val("");
        
        checkObj = control=="rbComercio" && $('input:radio[name=rbComercio]:checked').val()==1 ? true : false;
        checkNroConv = control=="txCodigo" && $('input:radio[name=rbID]:checked').val() ==1 ? true : false; 
                                
        $("#dlTrib").prop("disabled",!checkObj);
        $("#btBuscarObj").prop("disabled",!checkObj);
        $("#txObj_Id").prop("disabled",!checkObj);
        $("#fiscalizacionBuscar_txIDFiscalizacion").prop("disabled",!checkNroConv);
        
        $("#rbComercio").prop("checked",checkObj);
        $("#rbID").prop("checked",checkNroConv);
    }
    
    if (control=="btAceptar")
    {
        var error;
        error ='';
        
        if ($("#txNumFiscalizacion").val()=='' && $('input:radio[name=rbID]:checked').val()==1) error += '<li>Ingrese un Código de Facilidad</li>';
        if ($("#txObjNom").val() == "" && $("#txObj_Id").val() == "" && $('input:radio[name=rbComercio]:checked').val()==1) error += "<li>Ingrese un objeto</li>";
                
        if (error=='')
        {
            $("#frmBuscarFiscalizacion").submit();
        } else 
        {
            $("#errorbuscafacilida").html(error);
            $("#errorbuscafacilida").css("display", "block");
        }
    }
    
}

</script>