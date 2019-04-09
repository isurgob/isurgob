<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Menu;
use yii\bootstrap\Collapse;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
	<link rel="shortcut icon" href="/sam/images/icon.png" type="image/x-icon"/>
 	
    <title><?php echo Yii::$app->param->sis_name; ?></title>
	
    <?php $this->head() ?>
</head>
<body>

<?php 
	$this->beginBody(); 
	$base = Yii::$app->request->baseUrl.'/index.php?r=';
?>

	<div id="header">
		<div style="float:left;padding:0px 10px"><img src="<?php echo Yii::$app->param->logo; ?>"> </div>
		
		<div id="header_muni">
			<span><?php echo Yii::$app->param->muni_name; ?></span><br/>
			<span><?php echo Yii::$app->param->muni_domi; ?></span>
			<span><?php echo ' - Tel: '.Yii::$app->param->muni_tel; ?></span><br/>
			<span><?php echo Yii::$app->param->muni_mail; ?></span>		
		</div>
		<div id="header_samlogo">
			<div class="header_samtexto" style='margin-top:10px'>
				&nbsp;&nbsp;Sistema para la Administraci&oacute;n Municipal 
			</div>
			<div id="header_icon">
				<?php
					if (Yii::$app->user->isGuest or Yii::$app->session['user_sinclave'] == 1) {
						$items = [
		  				  ['label' => '<i class="glyphicon glyphicon-home" width="15px"></i>', 'url' => '/sam/index.php?r=site/index'],
		  				  ['label' => '<i class="glyphicon glyphicon-info-sign"></i>', 'url' => ['/sam/site/about']],
		  				  ['label' => '<i class="glyphicon glyphicon-envelope"></i>', 'url' => ['#']], ///site/contact
		  				  ['label' => '<i class="glyphicon glyphicon-user"></i>', 'url' => ['/sam/site/login']],
						]; 
					} else {
						$items = require('items.php');

						if (isset(Yii::$app->session['sis_id']) || !is_null(Yii::$app->session['sis_id']))
						  if (Yii::$app->session['sis_id'] != 0)
							$items = require('../' . Yii::$app->param->sis_file . '/views/layouts/items.php');
					}
					echo Nav::widget([
						'id' => 'navAcc',
						'options' => ['class' => 'btn-toolbar btn-group nav-tabs'],
	    				'items' => $items,
	    				'encodeLabels' => false
					]);		
				?>
			</div>
			<p style="color:navy; font-style:italic;font-weight:bold;font-size:12px;">
				<?php if (isset(Yii::$app->session['sis_id']) and Yii::$app->session['sis_id'] == 4) echo "&nbsp;&nbsp;Ejercicio Actual: " . Yii::$app->session['fin.part_ejer']; ?> <br>
				<?php if (isset(Yii::$app->session['sis_id']) and Yii::$app->session['sis_id'] == 4) echo "&nbsp;&nbsp;Estado: " . Yii::$app->session['fin.part_ejer_estadonom']; ?>
			</p>
		</div>
		
	</div><!-- header -->


	  <div id="wrap">
	   <div class="row">
        <div class="col-md-izq">
          <?php			        
            if (Yii::$app->user->isGuest or Yii::$app->session['user_sinclave'] == 1) {
				$menu = Collapse::widget([
					'id' => 'navigation',
					'encodeLabels' => true,
					'options' => ['class' => 'list-group'],
				    'items' => [
				        [
				        	'id' => 'mnu',
				            'label' => 'Menu',
				            'content' => 
				            		'<a class="list-group-item" href="'.Yii::$app->param->urlsam.'/site/index">Inicio</a></li>'.
									'<a class="list-group-item" href="'.Yii::$app->param->urlsam.'/site/login">Registro</a></li>',
				            'contentOptions' => ['class' => 'in']
				        ],
                    ]
                 ]);
            
			} else {
				$menu = null;
				
				if (isset(Yii::$app->session['sis_id']) || !is_null(Yii::$app->session['sis_id']))
				  if (Yii::$app->session['sis_id'] != 0)
					$menu = require('../' . Yii::$app->param->sis_file . '/views/layouts/menu.php');
			}
			echo '<br>'.$menu;
          ?>

        </div>
        <div class="col-md-der" role="main">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
                        
            <?= $content ?>
	    </div>
	  </div> <!-- end columnas -->  
     </div> <!-- end container -->

    <footer id="footer">
        <div class="container">
            <p class="pull-left">&copy; <a href="http://www.aari.com.ar/" target="_blank">AARI</a> <?= date('Y') ?>&nbsp;&nbsp;-&nbsp;&nbsp;<?php echo Yii::$app->param->sis_name; ?> <?php echo Yii::$app->param->version != '' ? '- Versi&oacute;n ' . Yii::$app->param->version : ''; ?></p>
            <p class="pull-right"><?= 'Fecha: '.date('d/m/Y').(Yii::$app->user->isGuest ? '' : ' - Usuario: '.Yii::$app->user->identity->nombre); ?></p>

        </div>
    </footer>
<script>
$(document).on('pjax:error', function(event, xhr, textStatus, errorThrown, options) {
	event.preventDefault();
	var error = xhr.responseText.substring(0,xhr.responseText.indexOf("Stack"));
	error += xhr.responseText.substring(xhr.responseText.indexOf("#3"),xhr.responseText.indexOf("#4"));

	options.success(error, textStatus, xhr);
});
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
