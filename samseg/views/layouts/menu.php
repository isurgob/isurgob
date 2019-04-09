<?php
use yii\bootstrap\Collapse;

$base = Yii::$app->param->sis_url;
$sam = Yii::$app->param->urlsam;

$menu = Collapse::widget([
					'id' => 'navigation',
					'encodeLabels' => true,
					'options' => ['class' => 'list-group'],
				    'items' => [
				        [
				        	'id' => 'mnuUsr',
				            'label' => 'Usuario',
				            'content' =>
				            		'<a class="list-group-item" href="'.$base.'/usuario/usuario/index">Usuario</a></li>',
				            'contentOptions' => ['class' => 'submenu']
				        ],
				        [
				        	'id' => 'mnuUsrWeb',
				            'label' => 'Usuario Web',
				            'content' =>
				            		'<a class="list-group-item" href="'.$base.'/usuarioweb/usuarioweb/index">Usuario Web</a></li>',
				            'contentOptions' => ['class' => 'submenu']
				        ],
				    ]
				]);
return $menu;
?>
