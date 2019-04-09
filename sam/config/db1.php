<?php

namespace app\config;

use Yii;
use yii\db\Connection;

class db1 extends Connection
{
	public $dsn;
	public $username;
	public $password;
	public $charset;
	public $muni;
		
	function __construct(){
		
		$municipiosxml = simplexml_load_file('../sam/config/municipios.xml');
		$muni = isset(Yii::$app->session['muni']) ? Yii::$app->session['muni'] : 1;

		foreach ($municipiosxml as $datos){
			if ($muni == (string)$datos->cod){
				$this->dsn = (string)$datos->dsn;
				$this->username = (string)$datos->username;
				$this->password = (string)$datos->password;
				$this->charset = (string)$datos->charset;
				$this->muni = $muni;
			}
		}
	}
	
}
 ?>
