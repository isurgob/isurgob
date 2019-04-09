<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

<?php
$ord = 0;
echo "<table border='0'>";
foreach ($generator->getColumnNames() as $attribute) {
    if (in_array($attribute, $safeAttributes)) {
    	
    	$ord += 1;
		if(($ord % 2) == 1)  //odd
			echo "<tr>";			
    	
        echo "<td>";
        echo "    <?= " . $generator->generateActiveField($attribute) . " ?>";
        echo "</td>";
        
		if(($ord % 2) == 1)  //odd
		    echo "<td width='20px'></td>";
		else
			echo "</tr>";			               
    }
}
echo "</table>"; 
?>
	<?= "<?php " ?>if ($consulta<>1){ ?>
	
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('Generar') ?> : <?= $generator->generateString('Grabar') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?= "<?php " ?>} ActiveForm::end(); 

    	if ($consulta==1) {
    		echo "<script>DesactivarForm('form-inm');</script>";
    	}  
    
    ?>
    
    

</div>
