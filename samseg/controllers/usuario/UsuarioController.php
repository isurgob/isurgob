<?php

namespace app\controllers\usuario;

use Yii;
use app\models\usuario\Usuario;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\utils\db\utb;
use yii\data\ArrayDataProvider;

/**
 * UsuarioController implements the CRUD actions for Usuario model.
 */
class UsuarioController extends Controller
{

    const MENSAJE_ID = 'mensaje_id';
    const MENSAJE_ALERT = 'mensaje_alert';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
    	$operacion = str_replace("/", "-", Yii::$app->controller->route);
		
		$permitirSiempre = ['usuario-usuario-obteneradministradores', 'usuario-usuario-obtenerdatosusuario'];

	    if (in_array($operacion, $permitirSiempre)) {
	        return true;
	    }

	    if ( !utb::getExisteAccion( $operacion ) ) {
	        echo $this->render('//site/nopermitido');
	        return false;
	    }

    	return true;
    }

    /**
     * Función que se utiliza para validar que el usuario que ingresa a la forma
     * disponga de los permisos necesarios para ello.
     */
    public function actionIndex()
    {
        if (utb::getExisteProceso(1020) == 1)
        {
        	return $this->redirect(['view']);
        } else
        {
        	return $this->redirect(['//site/index']);
        }
    }

    /**
     * Displays a single Usuario model.
     * @param integer $id
     * @return mixed
     */
    public function actionView()
    {
    	$model = new Usuario();
		
		$grupos = utb::getAux('sam.sis_grupo','gru_id','nombre',2);
		$oficina = utb::getAux('sam.muni_oficina','ofi_id','nombre',2);
		
		$id_grupo = Yii::$app->request->get( 'id_grupo', 0 );
		$id_oficina = Yii::$app->request->get( 'oficina', 0 );
		$usr_nom = Yii::$app->request->get( 'usr_nom', "" );
		$estado = Yii::$app->request->get( 'estado', "" );
		
		$datos = $model->getUsuarios( $id_grupo, $id_oficina, $usr_nom, $estado );
		
		$dataProviderUsuario = new ArrayDataProvider([
				'allModels'=> $datos,
				'key'=>'usr_id',
				'pagination' => [
					'pageSize' => count( $datos ),
				],
				'sort' => [
					'attributes' => [
						'usr_id',
						'nombre',
					],
				]]);

        return $this->render('view', [
            'model' => $model,
            'm'=> Yii::$app->session->getFlash( self::MENSAJE_ID, 1 ),
            'alert'=> $this->getAlert( Yii::$app->session->getFlash( self::MENSAJE_ALERT, 0 ) ),
            'grupos' => $grupos,
			'oficinas' => $oficina,
			'dataProviderUsuario' => $dataProviderUsuario
        ]);
    }
	
	public function actionObteneradministradores(){
	
		$gru_id = Yii::$app->request->post( 'grupo', 0 );
		$administradores = "";
		
		if ( $gru_id != 0 )
		{
			$adm1 = utb::getCampo( 'sam.sis_grupo', 'gru_id = ' . $gru_id, 'usradm1' );
			$adm1_nom = utb::getCampo( 'sam.sis_usuario', 'usr_id = ' . $adm1, 'nombre' );

			$adm2 = utb::getCampo( 'sam.sis_grupo', 'gru_id = ' . $gru_id, 'usradm2' );
			$adm2_nom = utb::getCampo( 'sam.sis_usuario', 'usr_id = ' . $adm2, 'nombre' );

			$administradores = '<label>Administradores:    '. ( $adm1_nom != '' ? '1. ' . $adm1_nom : '' ) . ( $adm2_nom != '' ? '       -       2. ' . $adm2_nom : '' ) . '</label>';
		} 
		
		$devolver = [
    			'administradores'	=> $administradores
    		];
			
    	return json_encode($devolver); 
	}
	
	public function actionObtenerdatosusuario(){
	
		$usuario_id = Yii::$app->request->post( 'usuario', 0 );
		$usuario_desc = "";
		$grupo_desc = "";
		$usuario_gru = 0;
		
		if ( $usuario_id != 0 )
		{
			$usuario_user = utb::getCampo('sam.sis_usuario','usr_id = ' . $usuario_id,'nombre');
			$usuario_nom = utb::getCampo('sam.sis_usuario','usr_id = ' . $usuario_id,'apenom');
			$usuario_gru = utb::getCampo('sam.sis_usuario','usr_id = ' . $usuario_id,'grupo');
			$usuario_gru_nom = utb::getCampo('sam.sis_grupo','gru_id = ' . $usuario_gru,'nombre');
			$usuario_oficina = utb::getCampo('sam.sis_usuario','usr_id = ' . $usuario_id,'oficina');
			$usuario_oficina_nom = utb::getCampo('sam.muni_oficina','ofi_id = ' . $usuario_oficina,'nombre');
			
			$usuario_desc = $usuario_id . ' - ' . $usuario_user . ' - ' . $usuario_nom ;
			$grupo_desc = $usuario_gru_nom . ' - ' . $usuario_oficina_nom;
		} 
		
		$devolver = [
    			'usuario_desc'	=> $usuario_desc,
				'grupo_desc' => $grupo_desc,
				'usuario_gru' => $usuario_gru
    		];
			
    	return json_encode($devolver); 
	}

	/**
	 * Método que se utiliza para dar de alta usuarios o para
	 * mostrar la ventana de inserción de datos.
	 */
   	public function actionCreate()
   	{
   		$model = new Usuario();

   		if($model->load(Yii::$app->request->post()))
   		{
   			if ($model->validate())
   			{
   				$res = $model->grabarUsuario();

				if ($res['return'] == 0)
					return $this->render('editarusuario',[
			   			'model' => $model,
			   			'consulta' => 0,
		   			]);
		   		else
                {
                    Yii::$app->session->setFlash( self::MENSAJE_ID, 1 );
                    Yii::$app->session->setFlash( self::MENSAJE_ALERT, 1 );

                    $this->redirect(['view']);
                }
   			}
   		}

		return $this->render('editarusuario',[
   			'model' => $model,
   			'consulta' => 0,
   		]);
   	}

   	/**
   	 * Método que se utiliza para actualizar los datos de un usuario o
   	 * mostrar la ventana de modificación.
   	 * @param integer $id Identificador de usuario
   	 */
   	public function actionUpdate($id)
   	{
   		$model = $this->findModel($id);

   		if($model->load(Yii::$app->request->post()))
   		{
   			$model->validate();

			$res = $model->grabarUsuario();

			if ($res['return'] == 0)
				return $this->render('editarusuario',[
		   			'model' => $model,
		   			'consulta' => 3,
	   			]);
	   		else
            {
                Yii::$app->session->setFlash( self::MENSAJE_ID, 1 );
                Yii::$app->session->setFlash( self::MENSAJE_ALERT, 1 );

	   			$this->redirect(['view']);
            }

   		} else
   		{
   			return $this->render('editarusuario',[
	   			'model' => $model,
	   			'consulta' => 3,

	   		]);
   		}
   	}

   	/**
   	 * Método que se utiliza para visualizar los datos de un usuario
   	 * @param integer $id Identificador de usuario
   	 */
   	public function actionViewusuario($id)
   	{
   		$model = $this->findModel($id);

		return $this->render('editarusuario',[
   			'model' => $model,
   			'consulta' => 1,

   		]);
   	}

   	/**
   	 * Función que se utiliza para actualizar los procesos activos para un usuario
   	 */
   	public function actionProcesos()
   	{
   		$model = new Usuario();

   		$model->load(Yii::$app->request->post());

		$model->grabarPermisos();

        Yii::$app->session->setFlash( self::MENSAJE_ID, 1 );
        Yii::$app->session->setFlash( self::MENSAJE_ALERT, 1 );

		$this->redirect(['view',
				'id' => $model->usr_id,
        ]);

   	}

	/**
	 * Método que se utiliza para dar de alta grupos o para
	 * mostrar la ventana de inserción de datos.
	 */
   	public function actionGrupocreate()
   	{
   		$model = new Usuario();

   		if( $model->load( Yii::$app->request->post() ) )
   		{
   			$res = $model->grabarGrupo();

			if ( $res['return'] == 0 )
            {

				return $this->render('editargrupo',[
		   			'model' => $model,
		   			'consulta' => 0,
	   			]);
	   		} else
            {
                Yii::$app->session->setFlash( self::MENSAJE_ID, 1 );
                Yii::$app->session->setFlash( self::MENSAJE_ALERT, 1 );

	   			$this->redirect(['view']);
            }

   		} else
   		{
   			return $this->render('editargrupo',[
	   			'model' => $model,
	   			'consulta' => 0,
	   		]);
   		}
   	}

   	/**
   	 * Método que se utiliza para actualizar los datos de un grupo o
   	 * mostrar la ventana de modificación.
   	 * @param integer $id Identificador de grupo
   	 */
   	public function actionGrupoupdate($id)
   	{

   		$model = new Usuario();

   		$model->findGrupo($id);

   		if($model->load(Yii::$app->request->post()))
   		{
   			$res = $model->grabarGrupo();

			if ($res['return'] == 0)
				return $this->render('editargrupo',[
		   			'model' => $model,
		   			'consulta' => 3,
	   			]);
	   		else
            {
                Yii::$app->session->setFlash( self::MENSAJE_ID, 1 );
                Yii::$app->session->setFlash( self::MENSAJE_ALERT, 1 );

	   			$this->redirect(['view']);
            }

   		} else
   		{
   			return $this->render('editargrupo',[
	   			'model' => $model,
	   			'consulta' => 3,
	   		]);
   		}
   	}

   	/**
   	 * Método que se utiliza para eliminar un grupo de la BD o
   	 * mostrar la ventana de eliminación.
   	 * @param integer $id Identificador de grupo
   	 */
   	public function actionGrupodelete($id)
   	{
   		$model = new Usuario();

   		$model->findGrupo($id);

   		if($model->load(Yii::$app->request->post()))
   		{
   			$res = $model->eliminarGrupo();

			if ($res['return'] == 0)
				return $this->render('editargrupo',[
		   			'model' => $model,
		   			'consulta' => 2,
	   			]);
	   		else
	   			$this->redirect(['view','m' => 1, 'alert' => 1]);

   		} else
   		{
   			return $this->render('editargrupo',[
	   			'model' => $model,
	   			'consulta' => 2,
	   			'mensaje' => '',
	   		]);
   		}
   	}

	/**
	 * Método que se ejecuta para mostrar información de los accesos de los usuarios al sistema
	 */
	public function actionAcceso($id)
	{
		$model = $this->findModel($id);

		return $this->render('accesousuario',[
	   			'model' => $model,
	   			'consulta' => 1,
	   			'activaQuitarUltAcc' => !( Yii::$app->user->id == $id ),

   		]);

   		/*
   		 * activaQuitarUltAcc es un parámetro que indica si se debe mostrar el botón que habilita
   		 * a eliminar el último acceso del usuario. En caso de que el usuario que se consulta sea
   		 * el propio, no se podrá eliminar el último acceso.
   		 */
	}

	/**
	 * Método que se ejecuta para mostrar información de los procesos
	 */
	public function actionProcesosistema()
	{
		$model = new Usuario();

		return $this->render('procesos',[
	   			'model' => $model,

   		]);
	}

	/**
	 * Función que muesra los datos de un permiso.
	 * @param integer $id Id de proceso
	 */
	public function actionUsuariogrupo($id)
	{
		$model = new Usuario();

		$model->cargarVariablesProceso($id);

		return $this->render('usuarioygrupo',[
	   			'model' => $model,

   		]);
	}
	
	public function actionUsuarioproceso( $id ){
		
		$sistema_id = intVal(Yii::$app->request->post('seguridad_sistema', 0)); 
		$modulo_id = intVal(Yii::$app->request->post('seguridad_modulo', 0)); 
		
		$model = Usuario::findOne([ 'usr_id' => $id ]);
		
		if ( $model == null )
			$model = new Usuario();
			
		$usuario_gru_nom = utb::getCampo('sam.sis_grupo','gru_id = ' . intVal($model->grupo),'nombre');
		$usuario_oficina_nom = utb::getCampo('sam.muni_oficina','ofi_id = ' . intVal($model->oficina),'nombre');		
		
		$arrayProcesos = $model->getProcesos( intVal($model->usr_id), $sistema_id, $modulo_id );
			
		$dataProviderProcesos = new ArrayDataProvider([
			'allModels' => $arrayProcesos,
			'pagination' => [
				'pageSize' => count($arrayProcesos),
			],
			'sort' => [
				'attributes' => [
					'pro_id',
					'sistema' => [
						'asc' => ['sistema' => SORT_ASC, 'modulo' => SORT_ASC, 'nombre' => SORT_ASC],
						'desc' => ['sistema' => SORT_DESC, 'modulo' => SORT_DESC, 'nombre' => SORT_DESC],
								],

				],
			]]);
			
		return $this->render('usuarioprocesos',[
	   		'model' => $model,
			'dataProviderProcesos' => $dataProviderProcesos,
			'usuario_gru_nom' => $usuario_gru_nom,
			'usuario_oficina_nom' => $usuario_oficina_nom,
			'sistema_id' => $sistema_id,
			'modulo_id' => $modulo_id
   		]);
	}


	/**
	 * Método que se utiliza para obtener mensajes
	 */
	public function getAlert($id)
	{
		$alert = '';

		switch ($id)
		{
			case 0:

				$alert = '';
				break;

			case 1:

				$alert = 'Los datos se grabaron correctamente.';
				break;

			case 2:

				$alert = 'Ocurrió un error al grabar los datos.';
				break;

		}

		return $alert;

	}

    /**
     * Finds the Usuario model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Usuario the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuario::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
