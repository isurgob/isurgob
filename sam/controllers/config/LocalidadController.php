<?php

namespace app\controllers\config;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

use app\models\taux\Localidad;
use app\utils\db\utb;

/**
 * VajmejController implements the CRUD actions for ValMej model.
 */
class LocalidadController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    
                ],
            ],
        ];
    }
    
    public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }
    	
    	return true;
    } 
    

    /**
     * Lists all ValMej models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$model = null;
    	$grabado = null;
    	$resultadoBuscador = [];
    	$consulta = intval(Yii::$app->request->post('consulta', Yii::$app->request->get('consulta', 1)));
    	$tipo = Yii::$app->request->post('tipo', Yii::$app->request->get('tipo', ''));
    	$codigoPais = intval(Yii::$app->request->post('codigoPais', Yii::$app->request->get('codigoPais', 0)));
    	$codigoProvincia = intval(Yii::$app->request->post('codigoProvincia', Yii::$app->request->get('codigoProvincia', 0)));
    	$codigoLocalidad = intval(Yii::$app->request->post('codigoLocalidad', Yii::$app->request->get('codigoLocalidad', 0)));
    	
    	$provincias = $localidades = [];
    	
    	$codigo = 0;
    	$foranea = 0;
    	
    	if($tipo != ''){
    		
    	
	    	switch($tipo){
	    		
	    		case 'pais': 
	    			$model = new Localidad(Localidad::TIPO_PAIS);
	    			$provincias = Localidad::getProvincias($codigoPais);
	    			$localidades = [];
	    			$codigo = $codigoPais;
	    			break;
	    		
	    		case 'provincia':
	    			$model = new Localidad(Localidad::TIPO_PROVINCIA);
	    			$provincias = Localidad::getProvincias($codigoPais);
	    			$localidades = Localidad::getLocalidades($codigoProvincia);
	    			$codigo = $codigoProvincia;
	    			$foranea = $codigoPais;
	    			break;
	    		
	    		case 'localidad':
	    			$model = new Localidad(Localidad::TIPO_LOCALIDAD);
	    			$provincias = Localidad::getProvincias($codigoPais);
	    			$localidades = Localidad::getLocalidades($codigoProvincia);
	    			$codigo = $codigoLocalidad;
	    			$foranea = $codigoProvincia;
	    			break;
	    		
	    		default:
					$model = new Localidad(Localidad::TIPO_LOCALIDAD);
					$provincias = [];
					$localidades = [];
					$codigo = 0;
					break;
	    	}
	    	
	    	$model = $model->buscarUno($codigo, $foranea);
	    	
    	} else $model = new Localidad(Localidad::TIPO_LOCALIDAD);
    	
    	if(Yii::$app->request->isPost){
    		
    		switch($consulta){
    			case 0 : $model->setScenario('insert'); break;
    			case 2 : $model->setScenario('delete'); break;
    			case 3 : $model->setScenario('update'); break;
    		}
    		
    		
    		if($model->load(Yii::$app->request->post())){
    		
    			$res = false;
    		
    			if($consulta === 2 || $consulta === 3){
    				
    				$codigo = 0;
    				$foranea = 0;
    				
    				switch($tipo){
    					case Localidad::TIPO_PAIS:
    						$codigo = $model->pais_id;
    						$foranea = 0;
    						break;
    						
    					case Localidad::TIPO_PROVINCIA:
    						$codigo = $model->prov_id;
    						$foranea = $model->pais_id;
    						break;
    						
    					case Localidad::TIPO_LOCALIDAD:
    						$codigo = $model->loc_id;
    						$foranea = $model->prov_id;
    						break; 
    				}
    				
    				$model = $model->buscarUno($codigo, $foranea);
    				$model->load(Yii::$app->request->post());
    			}
    		
    			if($consulta === 2){
    				$res = $model->borrar();
    				
    				if($res){
    					switch($tipo){
    						case Localidad::TIPO_PAIS: $model->pais_id= 1; break;
    						case Localidad::TIPO_PROVINCIA: $model->prov_id= 1; break;
    						case Localidad::TIPO_LOCALIDAD: $model->loc_id= 1; break;
    					}
    				}
    			}
    			else if($consulta === 0 || $consulta === 3)
    				$res = $model->grabar();
    				
    				
   				$grabado = $res;
    		}
    	}
    	
    	/**
    	 * OPCIONES DE BUSCAR
    	 */
		$buscar = filter_var(Yii::$app->request->get('buscar', false), FILTER_VALIDATE_BOOLEAN);
		
		$extras['filtroPais']= '';
		$extras['filtroProvincia']= '';
		$extras['filtroLocalidad']= '';
		$extras['filtroCodigoPostal']= '';
		
		if($buscar){
			
			$nombrePais= trim(Yii::$app->request->get('buscarNombrePais', ''));
			$nombreProvincia= trim(Yii::$app->request->get('buscarNombreProvincia', ''));
			$nombreLocalidad= trim(Yii::$app->request->get('buscarNombreLocalidad', ''));
			$codigoPostal= intval(Yii::$app->request->get('buscarCodigoPostal', 0));
			
			$extras['filtroPais']= $nombrePais;
			$extras['filtroProvincia']= $nombreProvincia;
			$extras['filtroLocalidad']= $nombreLocalidad;
			$extras['filtroCodigoPostal']= $codigoPostal;
			
			$resultadoBuscador= Localidad::buscar($nombrePais, $nombreProvincia, $nombreLocalidad, $codigoPostal);
		}

    	$extras['paises'] = Localidad::getPaises();
    	$extras['dpProvincia'] = new ArrayDataProvider(['allModels' => $provincias, 'pagination' => ['pageSize' => 35], 'sort' => ['attributes' => ['prov_id', 'nombre'], 'defaultOrder' => ['nombre' => SORT_ASC]]]);
    	$extras['dpLocalidad'] = new ArrayDataProvider(['allModels' => $localidades, 'pagination' => ['pageSize' => 35], 'sort' => ['attributes' => ['loc_id', 'nombre'], 'defaultOrder' => ['nombre' => SORT_ASC]]]);
    	$extras['dpBuscador'] = new ArrayDataProvider(['allModels' => $resultadoBuscador, 'pagination' => ['pageSize' => 35], 'sort' => ['attributes' => ['nombre_pais', 'nombre_provincia', 'nombre_localidad'], 'defaultOrder' => ['nombre_localidad' => SORT_ASC]]]);
    	
    	$extras['tipo'] = $tipo;
    	$extras['model'] = $model;
		
		if($grabado)
			return $this->render('//taux/auxlocalidad', ['extras' => $extras, 'consulta' => 1, 'mensaje' => 'Datos grabados correctamente', 'grabado' => $grabado]);
			
		return $this->render('//taux/auxlocalidad', ['extras' => $extras, 'consulta' => $consulta]);

    }
}
