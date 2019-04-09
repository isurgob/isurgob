<?php
use Yii;

use yii\web\Session;



if(YII_DEBUG){

$session= new Session();
if(is_array($mostrar)){
	
	foreach($mostrar as $nombre){
		
		echo '<div class="form" style="padding:5px; margin-top:5px;">';
		echo "<h2>Variable: $nombre</h2>";
		echo '<br>';
		
		$datos= $session->get($nombre, 'La variable no existe en session.');
		
		var_dump($datos);
		
		echo '</div>';
		echo '<br>';
	}
}

$session->close();
}
?>