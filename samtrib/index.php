<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require('../yii/autoload.php');
require('../yii/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/config/web.php');

// comment out the following two lines when deployed to production
//error_reporting(E_ERROR | E_WARNING | E_NOTICE);
//ini_set('display_errors', 1);

(new yii\web\Application($config))->run();


