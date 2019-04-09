<?php

namespace app\controllers\ctacte;

use Yii;
use app\models\ctacte\PlanConfig;

use yii\data\ArrayDataProvider;
use yii\data\SqlDataProvider;
use yii\data\Sort;

use yii\db\Query;
use yii\db\Expression;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Session;

use yii\filters\VerbFilter;
use app\utils\db\utb;



/**
 * PlanconfigController implements the CRUD actions for PlanConfig model.
 */
class PlanconfigController extends Controller
{	
	
	//contiene los parametros extras a enviar al formulario
	private $extras = [];
	private $model = null;
	
	const CACHE_USUARIOS_SIN_ASIGNAR = 'planConfigOriginalUsuariosSinAsignar';
	const CACHE_USUARIOS_ASIGNADOS = 'planConfigOriginalUsuariosAsignados';
	const CACHE_CAMBIOS_USUARIOS = 'planConfigCambiosUsuario';
	 
	const CACHE_TRIBUTOS_SIN_ASIGNAR = 'planConfigOriginalTributosSinAsignar';
	const CACHE_TRIBUTOS_ASIGNADOS = 'planConfigOriginalTributosAsignados';  
	const CACHE_CAMBIOS_TRIBUTOS = 'planConfigCambiosTributo';
	
	const CACHE_TOKEN = 'planConfigToken';
	
	
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

	/**
	 * remueve las variables de session que utiliza esta clase
	 */
	private function removerSession()
	{
		$session = Yii::$app->session;
		
		$session->open();
		
		$session->remove(self::CACHE_CAMBIOS_USUARIOS);
		$session->remove(self::CACHE_CAMBIOS_TRIBUTOS);
		
		$session->remove(self::CACHE_TRIBUTOS_SIN_ASIGNAR);
		$session->remove(self::CACHE_TRIBUTOS_ASIGNADOS);
		$session->remove(self::CACHE_USUARIOS_SIN_ASIGNAR);
		$session->remove(self::CACHE_USUARIOS_ASIGNADOS);
		
		$session->close();
	}
	
	/**
	 * Carga en la variable $extras los dataproviders correspondientes a los usuarios y a los tributos
	 * 
	 * @param boolean $mismaVista - true si la clase se ejecuto en la misma vista que la vez anterior y se deben obtener los datos de session, false de lo contrario y se borran los datos de session
	 */
	private function cargarDPs($mismaVista = false)
	{
		$usuarios = $this->model->getUsuarios();
		$usuariosAsignados = $this->model->getUsuariosAsignados();
		
		$cambiosUsuario = [];
		
		$tributos = $this->model->getTributos();
		$tributosAsignados = $this->model->getTributosAsignados();
		
		$cambiosTributo = [];
		
		
		$cache = Yii::$app->session;
		$cache->open();
		
		if($mismaVista)
		{
			$cambiosUsuario = $cache->get(self::CACHE_CAMBIOS_USUARIOS, []);
			$cambiosTributo = $cache->get(self::CACHE_CAMBIOS_TRIBUTOS, []);
		}
		else
		{
			$cache->remove(self::CACHE_CAMBIOS_USUARIOS);
			$cache->remove(self::CACHE_CAMBIOS_TRIBUTOS);
		}
		
		$cache->close();
		
		
		//se filtran las listas de tributos dependiendo los cambios hechos
		$cambiosUsuario = array_fill_keys($cambiosUsuario, '1');
				
		$interseccionNoAsignados = array_intersect_key( $usuarios, $cambiosUsuario );
		$interseccionUsuariosAsignados = array_intersect_key($usuariosAsignados, $cambiosUsuario);
				
		$usuarios = array_diff_key($usuarios, $interseccionNoAsignados);
		$usuariosAsignados = array_diff_key($usuariosAsignados, $interseccionUsuariosAsignados);
		
		$usuarios += $interseccionUsuariosAsignados;
		$usuariosAsignados += $interseccionNoAsignados;
		
		
		
		
		//se filtran las listas de tributos dependiendo los cambios hechos	
		$cambiosTributo = array_fill_keys($cambiosTributo, '1');
		
		$interseccionNoAsignados = array_intersect_key( $tributos, $cambiosTributo );
		$interseccionTributosAsignados = array_intersect_key($tributosAsignados, $cambiosTributo);
		
		$tributos = array_diff_key($tributos, $interseccionNoAsignados);
		$tributosAsignados = array_diff_key($tributosAsignados, $interseccionTributosAsignados);
		$tributos += $interseccionTributosAsignados;
		$tributosAsignados += $interseccionNoAsignados;
		
		
		
		$dpUsuariosSinAsignar = new ArrayDataProvider([
													
												'allModels' => $usuarios,
												'key' => 'usr_id',
												'totalCount' => count($usuarios),
												'pagination' => false
												]);
				
		$dpUsuariosAsignados = new ArrayDataProvider([
													
													'allModels' => $usuariosAsignados,
													'key' => 'usr_id',
													'totalCount' => count($usuariosAsignados),
													'pagination' => false
													]);  
													
													
		$dpTributosSinAsignar = new ArrayDataProvider([
													
												'allModels' => $tributos,
												'key' => 'trib_id',
												'totalCount' => count($tributos),
												'pagination' => false
												]);
				
		$dpTributosAsignados = new ArrayDataProvider([
													
													'allModels' => $tributosAsignados,
													'key' => 'trib_id',
													'totalCount' => count($tributosAsignados),
													'pagination' => false
													]);
													
		
		$this->extras['dpUsuariosSinAsignar'] = $dpUsuariosSinAsignar;
		$this->extras['dpUsuariosAsignados'] = $dpUsuariosAsignados;
		$this->extras['dpTributosSinAsignar'] = $dpTributosSinAsignar;
		$this->extras['dpTributosAsignados'] = $dpTributosAsignados;
	}

	/**
	 * carga la variable $extras con parametros necesarios para las vistas, pero con valores por defecto
	 */
	private function cargarParametrosDefault()
	{
		if($this->model  == null)
			$this->model = new PlanConfig();
			
		$this->cargarDPs();
		
		$this->extras['sistemas'] = $this->model->getSistemas();
		$this->extras['dpCuentas'] = $this->model->getDPCuentas('');
		$this->extras['gruposUsuario'] = $this->model->getGruposUsuario();
		$this->extras['texto'] = $this->model->getTextos();
		$this->extras['nombreCuentaFiltro'] = '';
	}

	
	public function beforeAction($action)
	{
		$operacion = str_replace("/", "-", Yii::$app->controller->route);
	    
	    if (!in_array($operacion, ['ctacte-planconfig-sugerenciacuenta', 'ctacte-planconfig-codigocuenta'])){
			if (!utb::getExisteAccion($operacion)) {
				echo $this->render('//site/nopermitido');
				return false;
			}
    	}
    	
		
		$nombreCuentaFiltro = null;
		$nombreVarRetorno = null;
		$idCuentaBuscar = null;
		$accion = '';
		
		
		if(!parent::beforeAction($action))
			return false;
			
			
		$this->model = new PlanConfig();
		$idPlanConfig = null;
		
		
		
		$cache = Yii::$app->session;
		$cache->open();
				
		
		if(!$cache->getIsActive())
			return false;
		
		$token = 0;
		$tokenAnterior = $cache->get(self::CACHE_TOKEN, 0);
		$id = $action->getUniqueId();
		
		switch($id)
		{
			case 'ctacte/planconfig/create' :
				$token = 1;
				break;
				
			case 'ctacte/planconfig/update' :
				$token = 3;
				break;
				
			case 'ctacte/planconfig/view' :
				$token = 2;
				break;
				
			case 'ctacte/planconfig/delete' :
				$token = 4;
				break;
				
			case 'ctacte/planconfig/index':
				$this->removerSession();
				$token= 0;
				break;
		}		
		
				
		//se carga el modelo
		switch($token)
		{					
			case 2 :						
			case 3 :			
			case 4 :													
					if(Yii::$app->request->isGet)
					{
						$idPlanConfig = Yii::$app->request->get('id', null);
					} 
					else if(Yii::$app->request->isPost)
					{
						$idPlanConfig = Yii::$app->request->post('PlanConfig', []);
					
						if(array_key_exists('cod', $idPlanConfig))
							$idPlanConfig = $idPlanConfig['cod'];
						else $idPlanConfig = null;
					}
				
					$this->model = $this->findModel($idPlanConfig);
					
			case 1 :
					if(!$cache->has(self::CACHE_TOKEN))
						$cache->set(self::CACHE_TOKEN, $token);
					
					break;
		}
		
		
		
		
		if(Yii::$app->request->isGet)
		{
			$nombreCuentaFiltro = Yii::$app->request->get('nombreCuenta', '');
			$nombreVarRetorno = Yii::$app->request->get('retorno', null);
			$idCuentaBuscar = Yii::$app->request->get('cuenta', null);
			
			$accion = Yii::$app->request->get('accion', '');
		}
		else if(Yii::$app->request->isPost)
		{
			$nombreCuentaFiltro = Yii::$app->request->post('nombreCuenta', '');
			$nombreVarRetorno = Yii::$app->request->post('retorno', null);
			$idCuentaBuscar = Yii::$app->request->post('cuenta', null);
			
			
		}
		
		
		
		$this->extras['nombreCuentaFiltro'] = $nombreCuentaFiltro;
		
		//se obtiene el DataProvider de las cuentas aplicando el filtro obtenido
		$dpCuentas = $this->model->getDPCuentas($nombreCuentaFiltro);
					
		$this->extras['dpCuentas'] = $dpCuentas;
		
					
		
		
		if($nombreVarRetorno != null && $idCuentaBuscar != null)
			$this->extras["$nombreVarRetorno"] = $this->model->getNombreCuenta($idCuentaBuscar);
		
		
		$this->extras['gruposUsuario'] = $this->model->getGruposUsuario();
		
		$this->extras['texto'] = $this->model->getTextos();
		
		$this->extras ['sistemas'] = $this->model->getSistemas();	
		

		if($token != $tokenAnterior || $token == 0)
		{						
			$this->cargarParametrosDefault();
			
			$cache->set(self::CACHE_TOKEN, $token);
			
			$cache->close();
			
			$this->removerSession();
					
			return true;
		}
		
		
		
		//var_dump($idAccion);
		
				
		switch( $token )
		{
			
			
			case 0 :
			case 2 :
			case 4 :
					$cache->set(self::CACHE_TOKEN, $token);
					$cache->close();
					
					
					$this->removerSession();
					$this->cargarParametrosDefault();
					return true;
			
			case 1 :
			case 3 :
			
			
				$cache->set(self::CACHE_TOKEN, $token);
				
				
				
				/*
				 * CAMBIOS DE USUARIOS
				 * 
				 * el usuario realiza cambios en los usuarios asignados al plan y se guaran en session hasta el proximo request
				 */						
				$usuarios = $this->model->getUsuarios();
				$usuariosAsignados = $this->model->getUsuariosAsignados();	
			
				//contiene los cambios de usuario
				$cambiosUsuario = $cache->get(self::CACHE_CAMBIOS_USUARIOS, []);
				
				
				switch($accion)
				{
					case 'switchUsuario' :
										
						$usr_id = Yii::$app->request->get('usr_id', []);
						
						$inters = array_intersect( $cambiosUsuario, $usr_id);
											
						$cambiosUsuario = array_merge( $cambiosUsuario, $usr_id );
						$cambiosUsuario = array_diff( $cambiosUsuario, $inters );
							
						break;
							
							
					case 'switchUsuarioTodos' :
						
						$activos = Yii::$app->request->get('activos', null);
						
						if($activos == null)
							break;
							
						
						$activos = filter_var($activos, FILTER_VALIDATE_BOOLEAN);
						
						if($activos)
							$cambiosUsuario = array_keys($usuariosAsignados);
						else
							$cambiosUsuario = array_keys($usuarios);
						
						break;

						
					case 'switchUsuarioPorGrupo' :
					
						$grupo = Yii::$app->request->get('grupo', 0);
						
						$usuariosGrupo = $this->model->getUsuariosPorGrupo($grupo);
						
						$cambiosUsuario = array_merge($cambiosUsuario, array_keys($usuariosGrupo));
					
						break;
				}
				
				$cache->set(self::CACHE_CAMBIOS_USUARIOS, $cambiosUsuario);
				
				/*
				 * 
				 * FIN DE CAMBIOS DE USUARIOS
				 */
													
				/*
				 * CAMBIOS DE TRIBUTO
				 * 
				 * el tributo realiza cambios en los tributos y se guardan en session hasta el proximo request
				 */
				 
				$tributos = $this->model->getTributos();
				$tributosAsignados = $this->model->getTributosAsignados();
				 //contiene los cambios de tributo
				$cambiosTributo = $cache->get(self::CACHE_CAMBIOS_TRIBUTOS, []);
				
				
				switch($accion)
				{
					case 'switchTributo' :
										
						$trib_id = Yii::$app->request->get('trib_id', []);
						
						$inters = array_intersect( $cambiosTributo, $trib_id);
						
						$cambiosTributo = array_merge( $cambiosTributo, $trib_id );						
						$cambiosTributo = array_diff( $cambiosTributo, $inters );
						
						break;
							
							
					case 'switchTributoTodos' :
						
						$activos = Yii::$app->request->get('activos', null);
						
						if($activos == null)
							break;
				
						$activos = filter_var($activos, FILTER_VALIDATE_BOOLEAN);
						
						$cambiosTributo = $activos ? array_keys($tributosAsignados) : array_keys($tributos); 
								
						break;
				}
								
				$cache->set(self::CACHE_CAMBIOS_TRIBUTOS, $cambiosTributo);
				$cache->close();
				
				$this->cargarDPs(true);				 
				/*
				 * FIN DE CAMBIOS DE TRIBUTO
				 */
				
				break;			
		}
		
		
			
		return true;
	}

    /**
     * Lists all PlanConfig models.
     * @return mixed
     */
    public function actionIndex()
    {
    	/*
    	 * Obtencion de filtros
    	 */
    	$arreglo = Yii::$app->request->isPost ? 'post' : 'get';
    	 
    	$trib_id = Yii::$app->request->$arreglo('trib_id', 0);
   		$nombre = Yii::$app->request->$arreglo('nombre', '');
   		$vigentes = Yii::$app->request->$arreglo('vigentes', true);
    	 
    	/*
    	 * fin de obtencion de filtros
    	 */
    	 
    	 
    	 /*
    	  * validacion de filtros
    	  */

   			
   		//captura de filtro solo vigentes. Por defecto se muestran los vigentes
		if($vigentes != null)
			$vigentes = filter_var($vigentes, FILTER_VALIDATE_BOOLEAN);// boolval($vigentes);
		else $vigentes = true;
    	 
    	/*
    	 * FIN VALIDACION DE FILTROS
    	 */
    	 
    	
    	$sql = "Select Distinct p.cod As cod, p.nombre As nombre, pt.nombre As sistema, to_char(p.vigenciadesde, 'DD/MM/YYYY') As vigenciadesde, to_char(p.vigenciahasta, 'DD/MM/YYYY') as vigenciahasta ";
    	$sql .= " From plan_config As p, plan_tsistema As pt, plan_config_trib As pct ";
    	$sql .= " Where p.sistema = pt.cod and p.cod<>0 ";    	
    	
    	$params = [];
    	$sqlExtra = "";
    	
    	if($trib_id > 0)
    	{
    		$sqlExtra .= " And (p.cod = pct.tplan And pct.trib_id = :_trib_id)";
    		$params = array_merge($params, [':_trib_id' => $trib_id]);
    	}
    	
    	if($vigentes)
    		$sqlExtra .= " And (p.vigenciadesde <= current_timestamp And (p.vigenciahasta >= current_timestamp Or p.vigenciahasta Is Null))";
    		
    	
    	//nombre
    	$sqlExtra .= " And lower(p.nombre) Like lower(('%' || :_nombre || '%'))";
    	$params = array_merge($params, [':_nombre' => $nombre]);
    	
    	$sql .= $sqlExtra;
    	$sqlCount = "Select count(Distinct p.cod) From plan_config As p, plan_tsistema As pt, plan_config_trib As pct Where p.sistema = pt.cod " . $sqlExtra;
    	
    	$count = Yii::$app->db->createCommand($sqlCount, $params)->queryScalar();
    	
    	$dataProvider = new SqlDataProvider([
    		'sql' => $sql,
    		'key' => 'cod',
    		'totalCount' => $count,
    		'params' => $params,
    		
    		'sort' =>[
				'attributes' => [
					
					'nombre' => [
								'asc' => ['nombre' => SORT_ASC],
								'desc' => ['nombre' => SORT_DESC],
								'default' => SORT_ASC
								],
					'cod',
					'sistema',
				],
				
				'defaultOrder' => [
					'nombre' => SORT_ASC
				],
				
				'params' => [
					'trib_id' => $trib_id,
					'nombre' => $nombre,
					'vigentes' => $vigentes,
					'sort' => Yii::$app->request->get('sort', null)
				]
			],//fin sort
			
			'pagination' => [
				'totalCount' => $count,
				'pageSize' => $count
			],//fin pagination
    	]);
    	
		
//		$sort = $dataProvider->getSort();


        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PlanConfig model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {		    	
    	return $this->render('view', ['model' => $this->model , 'extras' => $this->extras ] );
    }

    /**
     * Creates a new PlanConfig model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
		if(Yii::$app->request->isPost)
		{
			
			$cache = Yii::$app->session;
			$cache->open();
			

			$this->model->scenario = 'insert';
			
			$this->model->cambiosUsuario = $cache->get(self::CACHE_CAMBIOS_USUARIOS, []);
			$this->model->cambiosTributo = $cache->get(self::CACHE_CAMBIOS_TRIBUTOS, []);
			
			$cache->close();
			
			$this->model->load( Yii::$app->request->post() );
				 
			
			if($this->model->hasErrors() || !$this->model->validate())
				return $this->render('create', ['model' => $this->model, 'extras' => $this->extras]);
						
			
			$res = $this->model->grabar();
				
			if(!$res)
				return $this->render('create', ['model' => $this->model, 'extras' => $this->extras]);
				
					
			$this->removerSession();
			return $this->redirect(['view', 'id' => $this->model->cod, 'a' => 'create']);
		}
			
        
        return $this->render('create', 
                ['model' => $this->model, 'extras' => $this->extras ]
            );
    }

    /**
     * Updates an existing PlanConfig model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {		
    	
    	if(Yii::$app->request->isPost)
		{
			$cache = Yii::$app->session;
			$cache->open();
			
			$cambiosUsuario = $cache->get(self::CACHE_CAMBIOS_USUARIOS, []);
			$cambiosTributo = $cache->get(self::CACHE_CAMBIOS_TRIBUTOS, []);
			
			$cache->close();
			
			$this->model->scenario = 'update';
			$this->model->cambiosUsuario = $cambiosUsuario;
			$this->model->cambiosTributo = $cambiosTributo;
						
			
			if(!$this->model->load(Yii::$app->request->post()) || !$this->model->validate())
				return $this->render('update', ['model' => $this->model, 'extras' => $this->extras]);
			
			
			if(!$this->model->grabar())
				return $this->render('update', ['model' => $this->model, 'extras' => $this->extras]);
				
			
			$this->removerSession();
			return $this->redirect(['view', 'id' => $this->model->cod, 'a' => 'update']);
		}
		
		
		return $this->render('update', ['model' => $this->model, 'extras' => $this->extras]);
    }

    /**
     * Deletes an existing PlanConfig model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
    	
    	
    	if(Yii::$app->request->isGet)
    		return $this->render('delete', ['model' => $this->model , 'extras' => $this->extras ] );
    	
    		
    	if(Yii::$app->request->isPost)
    	{
    		
    		$this->model->scenario = 'delete';
    		
    		if($this->model->hasErrors() || !$this->model->validate() || !$this->model->borrar())
    			return $this->render('delete', ['model' => $this->model, 'extras' => $this->extras]);
    	
    	
    		return $this->redirect(['index', 'a' => 'delete']);
    	}
    	
        

        return $this->render('delete', ['model' => $this->model, 'extras' => $this->extras]);
    }
    
    /**
    * Busca las cuentas que coincidan con $term
    *
    */
    public function actionSugerenciacuenta($term = '',$tcta=0){


    	$ret= [];

    	if($term == '') return json_encode($ret);

    	$ret= utb::getAux('cuenta', 'nombre', 'nombre', 0, "upper(nombre) Like upper('%$term%')".($tcta != 0 ? " and tcta=".$tcta : "") );

    	if($ret === false) $ret= [];
    	return json_encode($ret);
    }

    /**
    * Busca el codigo de cuenta que coincide con $nombre
    */
    public function actionCodigocuenta($nombre= ""){

    	$ret= utb::getCampo('cuenta', "nombre = '$nombre'", 'cta_id');
    	return $ret;
    }
    

    /**
     * Finds the PlanConfig model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PlanConfig the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cod)
    {
    	if($cod == null)
    		$cod = 0;
    	
    	$ret = new PlanConfig();
    	
    	$ret->cod = $cod;
    	return $ret->buscarUno();
    }
}
