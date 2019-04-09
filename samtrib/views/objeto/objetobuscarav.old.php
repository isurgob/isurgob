<?php
use \yii\widgets\Pjax;
use yii\grid\GridView;

$idpx = (isset($idpx) ? $idpx : '');
if (!isset($id)) $id = 'default';
if (!isset($txCod)) $txCod = 'cod';
if (!isset($txNom)) $txNom = 'nom';

//$tobjeto = (isset($tobjeto) ? $tobjeto : 4);

Pjax::begin(['id' => 'PjaxObjBusAv'.$idpx]);
	$tobj = (isset($_POST['tobjeto']) ? $_POST['tobjeto'] : 4);
	
	switch ($tobj) {
    	case 1: 
    		Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false]);
	    		echo "<div id='DivInm".$id."'>";
	    		echo $this->render('//objeto/inm/buscarav',[
						'id' => $id, 'txCod' => $txCod, 'txNom' => $txNom
	        		]);
	        	echo "</div>";
        	Pjax::end();
        	break;
        case 2: 
    		Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false]);
	    		echo "<div id='DivCom".$id."'>";
	    		echo $this->render('//objeto/comer/buscarav',[
						'id' => $id, 'txCod' => $txCod, 'txNom' => $txNom
	        		]);
	        	echo "</div>";
	        Pjax::end();
        	break;	
        case 3:
        	Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false]);
	        	echo "<div id='DivPer".$id."'>";
	        	echo $this->render('//objeto/persona/buscarav',[
						'id' => $id, 'txCod' => $txCod, 'txNom' => $txNom
	        		]);
	        	echo "</div>";
	        Pjax::end();
        	break;
        case 4:
        	Pjax::begin(['enableReplaceState' => false, 'enablePushState' => false]);
	        	echo "<div id='DivCem".$id."'>";
	        	echo $this->render('//objeto/cem/buscarav',[
						'id' => $id, 'txCod' => $txCod, 'txNom' => $txNom
	        		]);
	        	echo "</div>";
	        Pjax::end();
        	break;
        case 5:
        	echo 'buscar rodados';
    }
    
Pjax::end();    

?>