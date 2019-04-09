<?php
use yii\helpers\Html;
use app\utils\db\utb;
/* @var $this SiteController */

$this->title=Yii::$app->name;
?>

<div style="float:left; padding-top:20px; width:90%; margin-bottom:20px">
	<div style="float:left;padding-right: 25px;">
    	<img src="/sam/images/sam_logo.gif" width="150px" ><br /><br />
        <?php if (!Yii::$app->user->isGuest) {?>
        	<center><?= Html::a('<b>Control de Versión</b>',Yii::$app->param->urlsam . 'version/index'); ?></center>
        <?php } ?>
    </div>
	<div>
        <h1><i><?php echo Yii::$app->param->sis_name == '' ? Yii::$app->param->name : Yii::$app->param->sis_name; ?></i></h1>
    
        <p>Desarrollado por <a href="http://www.aari.com.ar/" target="_blank">AARI</a></p>
    
        <p>&nbsp;</p>
        <p style="font-size:11px">Para obtener informaci&oacute;n sobre el uso <br /> del Sistema, consulte la documentaci&oacute;n y la <br />
            <a href="http://www.yiiframework.com/doc/" target="_blank">Ayuda en l&iacute;nea</a>.		
        </p>
	</div>	
</div>	

<div style="clear:both;"></div>
<?php if (!Yii::$app->user->isGuest) {?>
	<hr style="margin:10px 0px" />

	<?php if (utb::getExisteSistema()['s3_tri']){ ?>
		<div class="sist">
			<?= Html::a('<img src="/sam/images/samtrib.png" /><br /><br>Tributario','/samtrib/index.php?r=site/index' ); ?>
		</div>
	<?php } ?>

	<!--<div style="clear:both;"></div>-->

	<?php if (utb::getExisteSistema()['s1_seg'] or utb::getExisteSistema()['s7_web']){ ?>	
		<div class="sist">
			<?= Html::a('<img src="/sam/images/samseg.png" /><br /><br>Seguridad','/samseg/index.php?r=site/index' ); ?>
		</div>
	<?php } ?>
<?php } ?>