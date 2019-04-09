<?php
use \yii\widgets\Pjax;
use yii\widgets\MaskedInput;
use yii\web\Session;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\jui\DatePicker;


use app\controllers\objeto\BusquedaavanzadaController;

$controlador= new BusquedaavanzadaController();

//las siguientes lineas van a forzar que se carguen los archivos javascript correspondientes de cada elemento
echo MaskedInput::widget(['name' => '123abc', 'mask' => '999-99', 'options' => ['class' => 'hidden', 'disabled' => true]]);
echo GridView::widget(['dataProvider' => new ArrayDataProvider(['allModels' => []]), 'tableOptions' => ['class' => 'hidden']]);
echo DatePicker::widget(['name' => 'axowlspfjds', 'options' => ['class' => 'hidden', 'disabled' => true]]);

$idpx = (isset($idpx) ? $idpx : '');
$id = isset($id) ? $id : 'default';
$txCod = isset($txCod) ? $txCod : 'cod';
$txNom = isset($txNom) ? $txNom : 'nom';
$selectorModal= isset($selectorModal) ? $selectorModal : null;

Pjax::begin(['id' => 'PjaxObjBusAv'.$idpx, 'enableReplaceState' => false, 'enablePushState' => false]);

	$session = new Session;
	$session->open();

	$tobj = isset($tobjeto) ? $tobjeto : intval(Yii::$app->request->post('tobjeto', $session->get('busquedaAvTObj', 0)));

	$session->set('busquedaAvTObj', $tobj);
	$session->close();

	echo '<div id="divBusquedaAvanzadaGeneral' . $id . '">';
	echo $controlador->dibujar($tobj, $id, $txCod, $txNom, $selectorModal);
	echo '</div>';

Pjax::end();
?>

<script type="text/javascript">
$("PjaxObjBusAv<?= $idpx ?>").on("pjax:error", function(xhr, texto, error){

	xhr.stopPropagation();
	xhr.preventDefault();
	//console.log(error);
});
</script>
