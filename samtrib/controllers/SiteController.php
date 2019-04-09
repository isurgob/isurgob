<?php

namespace app\controllers;

 ini_set('memory_limit', '-1'); 
 set_time_limit(0) ;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\utils\db\utb;
use app\models\LoginForm;
use app\models\CbioclaveForm;
use app\models\ContactForm;
use app\models\ConvertForm;
use app\models\taux\tablaAux;
use yii\export2excel\Export2ExcelBehavior;

use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
	public function behaviors()
	{
	    return [
	        'access' => [
	            'class' => AccessControl::className(),
	            'only' => ['logout', 'signup', 'about'],
	            'rules' => [
	                [
	                    'actions' => ['login', 'signup', 'error','logout'],
	                    'allow' => true,
	                    'roles' => ['?'],
	                ],
	                [
	                    'actions' => ['about', 'index','logout'],
	                    'allow' => true,
	                    'roles' => ['@'],
	                ],
	            ],
	        ],
	        'verbs' => [
	            'class' => VerbFilter::className(),
	            'actions' => [
	                'logout' => ['get'],
	            ],
	        ],
	        'export2excel' => [
               'class' => Export2ExcelBehavior::className(),
               //'prefixStr' => yii::$app->user->identity->username,
               //'suffixStr' => date('Ymd-His'),
          ],
	    ];
	}


	public function beforeAction($action) {
	    if (!parent::beforeAction($action)) {
	        return false;
	    }

	    $operacion = str_replace("/", "-", Yii::$app->controller->route);

	    $permitirSiempre = ['site-captcha', 'site-signup', 'site-index', 'site-error', 'site-contact', 'site-login', 'site-logout', 'site-about', 'site-cbioclave', 'site-pdflist', 'site-exportar', 'site-download', 'site-auxeditredirect'];

	    if (in_array($operacion, $permitirSiempre)) {
	        return true;
	    }

		// Si el usuario no esta logueado o tiene la clave vacia no admito
		if (\Yii::$app->user->isGuest) {
			echo $this->render('nopermitido');
			return false;
		} else {
			//if (isset(Yii::$app->session['user_sinclave'])) {
				if (Yii::$app->session['user_sinclave'] == 1) {
					echo $this->render('cbioclave');
					return false;
				}
			//} else
			//	return false;
		}

	    if (!utb::getExisteAccion($operacion)) {
	        echo $this->render('nopermitido');
	        return false;
	    }

	    if ($operacion == 'site-auxedit'){
		    $t = (isset($_GET['t']) ? $_GET['t'] : 0);

		    $procesotaux = utb::getCampo('sam.tabla_aux','cod='.$t,'accesocons');

		    if (!utb::getExisteProceso($procesotaux)){
		    	echo $this->render('nopermitido');
		    	return false;
		    }
	    }

	    return true;
	}

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'download' => [
                'class' => 'yii\export2excel\DownloadAction',
            ],
        ];
    }


    public function actionIndex()
    {
        Yii::$app->session['sis_id'] = 3;
		return $this->render('index');
    }


    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            if (Yii::$app->session['user_sinclave'] == 1){
				Yii::$app->session['user_sinclave'] = 0;
				$this->redirect(['cbioclave']);
			}else
				return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->session['user_sinclave'] == 1){
				Yii::$app->session['user_sinclave'] = 0;
				$this->redirect(['cbioclave']);
			}else{
				return $this->render('index');
			}
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    public function actionLogout()
    {
        (new LoginForm)->getGrabarSalida();

        Yii::$app->user->logout();

        return $this->goHome();
    }


    public function actionCbioclave() {
       if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new CbioclaveForm();
        if ($model->load(Yii::$app->request->post()) && $model->cbioclave()) {
        	return $this->goBack();
        } else {
            return $this->render('cbioclave', [
                'model' => $model,
            ]);
        }
    }


    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->contact(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Gracias por contactarnos. Responderemos a la mayor brevedad posible.');
            } else {
                Yii::$app->session->setFlash('error', 'Se ha producido un error al enviar el correo electrónico.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }


    public function actionConfig()
    {
		return $this->render('config');
    }

    public function actionAbout()
    {
        return $this->render('about');
    }


    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', 'Revise su correo electr�nico para obtener m�s instrucciones.');

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', 'Lo sentimos, no podemos restablecer la contrase�a para el usuario proporcionado.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('success', 'Nueva contrase�a ha sido actualizada');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


/***************************************  AUXILIARES  ****************************************************/
    public function actionTaux() {
		return $this->render('//taux/taux');
    }

    public function actionAuxedit($t) {
		$consulta = 1;// inicializo consulta = 1, para ver en modo consulta
		$error = '';
		$mensaje='';
		
		if(isset($_POST['txAccion'])) $consulta = $_POST['txAccion'];
		if(isset($_POST['txForm'])) $txForm = $_POST['txForm'];

		$model = tablaAux::findOne($t);

		if ($model->link == '')
		{
			$url = 'auxedit';
			$model->nombrelong = tablaAux::GetCampoLong($model->nombre,'nombre');
			$model->codlong = ($model->tcod == 'N' ? 4 : tablaAux::GetCampoLong($model->nombre,'cod'));
			$model->CargarTercerCampo();
			$tabla = tablaAux::CargarTabla($model->nombre, $model->tercercamponom);

		}else {
			if($t==133){
				$url = $model->link;

				$tabla = (new tablaAux())->CargarTablaOficina();
			}elseif($t==128){
				$url = $model->link;

				$tabla = (new tablaAux())->CargarTablaTEmple();	
			}else{
				$url = $model->link;
				$tabla = tablaAux::CargarTabla($model->nombre);
			}
		}


		if ($consulta != 1)
		{
			if ($model->link == "") // tablas p�gina comunes comunes
			{
				$cod = (isset($_POST['txCod']) ? $_POST['txCod'] : 0);
				$nombre = (isset($_POST['txNombre']) ? $_POST['txNombre'] : '');
				$tercercampo = (isset($_POST['txTercerCampo']) && $_POST['txTercerCampo'] !='' ? $_POST['txTercerCampo'] : 0);

				if ($consulta != 2) $error = $model->grabarTablaAux($consulta,$cod,$nombre,$tercercampo);
				if ($consulta == 2) $error = $model->borrarTablaAux($cod);

				if($consulta == 2 and $error=='') $mensaje='delete';
				if($consulta != 2 and $error=='') $mensaje='grabado';
				if($consulta == 0 and $error!='') $consulta=0;
				if($consulta == 3 and $error!='') $consulta=3;

			}else {
				// tablas con otras p�ginas
				$cod = (isset($_POST['txCod']) ? $_POST['txCod'] : 0);
				$nombre = (isset($_POST['txNombre']) ? $_POST['txNombre'] : '');
				$tercercampo = (isset($_POST['txTercerCampo']) ? $_POST['txTercerCampo'] : 0);
				// solo tabla op_tobra
				$fchfin = (isset($_POST['ckFchFin']) ? $_POST['ckFchFin'] : 0);
				// solo tabla domi_calle
				$tcalle = (isset($_POST['tipo']) ? $_POST['tipo'] : 0);
				// solo tabla banco
				$bco_ent = (isset($_POST['bco_ent']) ? $_POST['bco_ent'] : 0);
				$bco_suc = (isset($_POST['bco_suc']) ? $_POST['bco_suc'] : 0);
				$domi = (isset($_POST['domi']) ? $_POST['domi'] : 0);
				$tel = (isset($_POST['tel']) ? $_POST['tel'] : 0);
				// solo tabla caja_mdp
				$tipo = (isset($_POST['tipo']) ? $_POST['tipo'] : 0);
				$cotiza = (isset($_POST['cotiza']) ? $_POST['cotiza'] : 0);
				$simbolo = (isset($_POST['simbolo']) ? $_POST['simbolo'] : 0);
				$habilitado = (isset($_POST['habilitado']) ? $_POST['habilitado'] : 0);
				// solo tabla caja_tesoreria
				$est = (isset($_POST['est']) ? $_POST['est'] : 0);
				// solo tabla exen_tipo
				$norma = (isset($_POST['norma']) ? $_POST['norma'] : 0);
				$tobj = (isset($_POST['tobj']) ? $_POST['tobj'] : 0);
				$item_id = (isset($_POST['item_id']) ? $_POST['item_id'] : 0);
				$trenov = (isset($_POST['trenov']) ? $_POST['trenov'] : 0);
				$sol_sitlab = (isset($_POST['sol_sitlab']) ? $_POST['sol_sitlab'] : 0);
				$sol_cony = (isset($_POST['sol_cony']) ? $_POST['sol_cony'] : 0);
				$sol_ingreso = (isset($_POST['sol_ingreso']) ? $_POST['sol_ingreso'] : 0);
				$sol_benef = (isset($_POST['sol_benef']) ? $_POST['sol_benef'] : 0);
				$val_propunica = (isset($_POST['val_propunica']) ? $_POST['val_propunica'] : 0);
				$val_benefunaprop = (isset($_POST['val_benefunaprop']) ? $_POST['val_benefunaprop'] : 0);
				$val_titcony = (isset($_POST['val_titcony']) ? $_POST['val_titcony'] : 0);
				$val_persfisica = (isset($_POST['val_persfisica']) ? $_POST['val_persfisica'] : 0);
				$val_persjuridica = (isset($_POST['val_persjuridica']) ? $_POST['val_persjuridica'] : 0);
				// solo tabla inm_tzonaop
				$fos = (isset($_POST['fos']) ? $_POST['fos'] : 0);
				$fot = (isset($_POST['fot']) ? $_POST['fot'] : 0);
				// solo tabla intima_tetapa
				$genauto = (isset($_POST['genauto']) ? $_POST['genauto'] : 0);
				// solo tabla judi_hono
				$supuesto = (isset($_POST['supuesto']) ? $_POST['supuesto'] : 0);
				$deuda_desde = (isset($_POST['deuda_desde']) ? $_POST['deuda_desde'] : 0);
				$deuda_hasta = (isset($_POST['deuda_hasta']) ? $_POST['deuda_hasta'] : 0);
				$hono_min = (isset($_POST['hono_min']) ? $_POST['hono_min'] : 0);
				$hono_porc = (isset($_POST['hono_porc']) ? $_POST['hono_porc'] : 0);
				$gastos = (isset($_POST['gastos']) ? $_POST['gastos'] : 0);
				// solo tabla juz_tinfrac
				$origen = (isset($_POST['origen']) ? $_POST['origen'] : 0);
				$art = (isset($_POST['art']) ? $_POST['art'] : 0);
				$inc = (isset($_POST['inc']) ? $_POST['inc'] : 0);
				$multa_min = (isset($_POST['multa_min']) ? $_POST['multa_min'] : 0);
				$multa_max = (isset($_POST['multa_max']) ? $_POST['multa_max'] : 0);
				// solo tabla juz_ttexto
				$accion = (isset($_POST['accion']) ? $_POST['accion'] : 0);
				$tipo_fallo = (isset($_POST['tipo_fallo']) ? $_POST['tipo_fallo'] : 0);
				$texto_id = (isset($_POST['texto_id']) ? $_POST['texto_id'] : 0);
				$texto_id_pie = (isset($_POST['texto_id_pie']) ? $_POST['texto_id_pie'] : 0);
				// solo tabla objeto_trib_cat
				$trib_id = (isset($_POST['trib_id']) ? $_POST['trib_id'] : 0);
				$cat = (isset($_POST['cat']) ? $_POST['cat'] : 0);
				// solo tabla op_prof
				$ttitu1 = (isset($_POST['ttitu1']) ? $_POST['ttitu1'] : 0);
				$ttitu1_matric = (isset($_POST['ttitu1_matric']) ? $_POST['ttitu1_matric'] : 0);
				$ttitu1_facu = (isset($_POST['ttitu1_facu']) ? $_POST['ttitu1_facu'] : 0);
				$ttitu2 = (isset($_POST['ttitu2']) ? $_POST['ttitu2'] : 0);
				$ttitu2_matric = (isset($_POST['ttitu2_matric']) ? $_POST['ttitu2_matric'] : 0);
				$ttitu2_facu = (isset($_POST['ttitu2_facu']) ? $_POST['ttitu2_facu'] : 0);
				$num = (isset($_POST['num']) ? $_POST['num'] : 0);
				$tdoc = (isset($_POST['tdoc']) ? $_POST['tdoc'] : 0);
				$ndoc = (isset($_POST['ndoc']) ? $_POST['ndoc'] : 0);
				$dom_part = (isset($_POST['dom_part']) ? $_POST['dom_part'] : 0);
				$tel_part = (isset($_POST['tel_part']) ? $_POST['tel_part'] : 0);
				$dom_prof = (isset($_POST['dom_prof']) ? $_POST['dom_prof'] : 0);
				$tel_prof = (isset($_POST['tel_prof']) ? $_POST['tel_prof'] : 0);
				$mail = (isset($_POST['mail']) ? $_POST['mail'] : 0);
				$carnet_gestor = (isset($_POST['carnet_gestor']) ? $_POST['carnet_gestor'] : 0);
				$matric_muni = (isset($_POST['matric_muni']) ? $_POST['matric_muni'] : 0);
				$cuit = (isset($_POST['cuit']) ? $_POST['cuit'] : 0);
				$expe = (isset($_POST['expe']) ? $_POST['expe'] : 0);
				$es_cons = (isset($_POST['es_cons']) ? $_POST['es_cons'] : 0);
				$es_empre = (isset($_POST['es_empre']) ? $_POST['es_empre'] : 0);
				$contacto = (isset($_POST['contacto']) ? $_POST['contacto'] : 0);
				$obs = (isset($_POST['obs']) ? $_POST['obs'] : 0);
				$fchbaja = (isset($_POST['fchbaja']) ? $_POST['fchbaja'] : 0);
				$motivo_baja = (isset($_POST['motivo_baja']) ? $_POST['motivo_baja'] : 0);
				$fchultpago = (isset($_POST['fchultpago']) ? $_POST['fchultpago'] : 0);
				$anioultpago = (isset($_POST['anioultpago']) ? $_POST['anioultpago'] : 0);
				// solo tabla recl_tipo
				$ofi_id_alta = (isset($_POST['ofi_id_alta']) ? $_POST['ofi_id_alta'] : 0);
				$ofi_id = (isset($_POST['ofi_id']) ? $_POST['ofi_id'] : 0);
				$req_inm = (isset($_POST['req_inm']) ? $_POST['req_inm'] : 0);
				$req_sancion = (isset($_POST['req_sancion']) ? $_POST['req_sancion'] : 0);
				$plazo = (isset($_POST['plazo']) ? $_POST['plazo'] : 0);
				// solo tabla rodado_aforo_modelo
				$aforo_id = (isset($_POST['aforo_id']) ? $_POST['aforo_id'] : 0);
				$modelo = (isset($_POST['modelo']) ? $_POST['modelo'] : 0);
				$marca = (isset($_POST['marca']) ? $_POST['marca'] : 0);
				$tipo = (isset($_POST['tipo']) ? $_POST['tipo'] : 0);
				$modelo_nom = (isset($_POST['modelo_nom']) ? $_POST['modelo_nom'] : 0);
				$marca_nom = (isset($_POST['marca_nom']) ? $_POST['marca_nom'] : 0);
				$tipo_nom = (isset($_POST['tipo_nom']) ? $_POST['tipo_nom'] : 0);
				// solo tabla rodado_modelo
				$marca = (isset($_POST['marca']) ? $_POST['marca'] : 0);
				// solo tabla rodado_tdeleg
				$encargado = (isset($_POST['encargado']) ? $_POST['encargado'] : 0);
				$prov_id = (isset($_POST['prov_id']) ? $_POST['prov_id'] : 0);
				$localidad = (isset($_POST['localidad']) ? $_POST['localidad'] : 0);
				$cp = (isset($_POST['cp']) ? $_POST['cp'] : 0);
				$fax = (isset($_POST['fax']) ? $_POST['fax'] : 0);
				// solo tabla rodado_val
				$anioval = (isset($_POST['anioval']) ? $_POST['anioval'] : 0);
				$gru = (isset($_POST['gru']) ? $_POST['gru'] : 0);
				$anio = (isset($_POST['anio']) ? $_POST['anio'] : 0);
				$pesodesde = (isset($_POST['pesodesde']) ? $_POST['pesodesde'] : 0);
				$pesohasta = (isset($_POST['pesohasta']) ? $_POST['pesohasta'] : 0);
				$valor = (isset($_POST['valor']) ? $_POST['valor'] : 0);
				// solo tabla plan_temple
				$caja_id = (isset($_POST['caja_id']) ? $_POST['caja_id'] : 0);
				// solo tabla muni_oficina
				$resp = (isset($_POST['resp']) ? $_POST['resp'] : 0);
				$sec_id = (isset($_POST['sec_id']) ? $_POST['sec_id'] : 0);

				if ($consulta != 2 and $t==29) $error = $model->grabarTablaAuxOPTObra($consulta,$cod,$nombre,$fchfin);
				if ($consulta != 2 and $t==130) $error = $model->grabarTablaAuxDomiCalle($consulta,$cod,$nombre,$tcalle);
				if ($consulta != 2 and $t==80) $error = $model->grabarTablaAuxBancoSuc($consulta,$bco_ent,$bco_suc,$nombre,$domi,$tel);
				if ($consulta == 2 and $t==80) $error = $model->borrarTablaAuxBancoSuc($bco_ent,$bco_suc);
				if ($consulta != 2 and $t==85) $error = $model->grabarTablaAuxCajaMdp($consulta,$cod,$nombre,$tipo,$cotiza,$simbolo,$habilitado);
				if ($consulta != 2 and $t==81) $error = $model->grabarTablaAuxCajaTeso($consulta,$cod,$nombre);
				if ($consulta == 2 and $t==81) $error = $model->borrarTablaAuxCajaTeso($cod);
				if ($consulta != 2 and $t==103) $error = $model->grabarTablaAuxExenTipo($consulta,$cod,$nombre,$norma,$tobj,$item_id,$trenov,$sol_sitlab,$sol_cony,$sol_ingreso,$sol_benef,$val_propunica,$val_benefunaprop,$val_titcony,$val_persfisica,$val_persjuridica,$est);
				if ($consulta == 2 and $t==103) $error = $model->borrarTablaAuxExenTipo($cod);
				if ($consulta != 2 and $t==30) $error = $model->grabarTablaAuxTZonaOP($consulta,$cod,$nombre,$fos,$fot);
				if ($consulta != 2 and $t==136) $error = $model->grabarTablaAuxIntiEtapas($consulta,$cod,$nombre,$genauto,$est);
				if ($consulta != 2 and $t==95) $error = $model->grabarTablaAuxJudiHono($consulta,$est,$supuesto,$deuda_desde,$deuda_hasta,$hono_min,$hono_porc,$gastos);
				if ($consulta == 2 and $t==95) $error = $model->borrarTablaAuxJudiHono($est,$supuesto,$deuda_desde,$deuda_hasta);
				if ($consulta != 2 and $t==112) $error = $model->grabarTablaAuxJuzTInfrac($consulta,$cod,$nombre,$origen,$norma,$item_id,$art,$inc,$multa_min,$multa_max,$est);
				if ($consulta == 2 and $t==112) $error = $model->borrarTablaAuxJuzTInfrac($cod);
				if ($consulta != 2 and $t==129) $error = $model->grabarTablaAuxJuzgadoTexto($consulta,$accion,$tipo_fallo,$origen,$texto_id,$texto_id_pie);
				if ($consulta == 2 and $t==129) $error = $model->borrarTablaAuxJuzgadoTexto($accion,$tipo_fallo,$origen);
				if ($consulta != 2 and $t==69) $error = $model->grabarTablaAuxTribInscripCat($consulta,$trib_id,$cat,$nombre);
				if ($consulta == 2 and $t==69) $error = $model->borrarTablaAuxTribInscripCat($trib_id,$cat);
				if ($consulta != 2 and $t==27) $error = $model->grabarTablaAuxObrasPrivProf($consulta,$cod,$nombre,$ttitu1,$ttitu1_matric,$ttitu1_facu,$ttitu2,$ttitu2_matric,$ttitu2_facu,$num,$tdoc,$ndoc,$dom_part,$tel_part,
   																							$dom_prof,$tel_prof,$mail,$carnet_gestor,$matric_muni,$cuit,$expe,$es_cons,$es_empre,$contacto,$obs,$fchbaja,$motivo_baja,$fchultpago,$anioultpago);
   				if ($consulta == 2 and $t==27) $error = $model->borrarTablaAuxObrasPrivProf($cod);
   				if ($consulta != 2 and $t==115) $error = $model->grabarTablaAuxReclamosTipo($consulta,$cod,$nombre,$ofi_id_alta,$ofi_id,$req_inm,$req_sancion,$plazo);
				if ($consulta != 2 and $t==49) $error = $model->grabarTablaAuxRodadoAforoModelo($consulta,$aforo_id,$origen,$marca,$tipo,$modelo,$marca_nom,$tipo_nom,$modelo_nom);
				if ($consulta == 2 and $t==49) $error = $model->borrarTablaAuxRodadoAforoModelo($aforo_id);
				if ($consulta != 2 and $t==48) $error = $model->grabarTablaAuxRodadoModelo($consulta,$cod,$nombre,$marca,$cat);
				if ($consulta != 2 and $t==51) $error = $model->grabarTablaAuxRodadoDeleg($consulta,$cod,$nombre,$encargado,$prov_id,$localidad,$domi,$cp,$tel,$fax);
				if ($consulta != 2 and $t==56) $error = $model->grabarTablaAuxRodadoVal($consulta,$anioval,$gru,$anio,$pesodesde,$pesohasta,$valor);
				if ($consulta == 2 and $t==56) $error = $model->borrarTablaAuxRodadoVal($anioval,$gru,$anio,$pesodesde,$pesohasta);
				if ($consulta != 2 and $t==6) $error = $model->grabarTablaAuxTVinc($consulta,$cod,$nombre);
				if ($consulta != 2 and $t==128) $error = $model->grabarTablaAuxTemple($consulta,$caja_id,$cod,$nombre);
				if ($consulta == 2 and $t==128) $error = $model->borrarTablaAuxTemple($caja_id,$cod);
				if ($consulta != 2 and $t==133) $error = $model->grabarTablaAuxOficina($consulta,$cod,$nombre,$resp,$sec_id);
				if ($consulta != 2 and $t==5) $error = $model->grabarTablaAuxObjTVinc($consulta,$cod,$nombre,$tobj);

				if ($consulta == 2 and $t!=80 and $t!=81 and $t!=103 and $t!=95 and $t!=112 and $t!=129 and $t!=69 and $t!=27 and $t!=49 and $t!=56 and $t!=37 and $t!=128) $error = $model->borrarTablaAux($cod);

				if($consulta == 2 and $error=='') $mensaje='delete';
				if($consulta != 2 and $error=='') $mensaje='grabado';
				if($consulta == 0 and $error!='') $consulta=0;
				if($consulta == 3 and $error!='') $consulta=3;

			}

			if ($error == '') return $this->redirect(['auxeditredirect', 't' => $t,'mensaje'=>$mensaje,'consulta'=>$consulta]);
		}

		if ($error == '')
		{
			$cod = '';
			$nombre = '';
			$tercercampo = '';
			$fchfin = false;
			$tcalle = 0;
			$bco_ent = '';
			$bco_suc = '';
			$domi = '';
			$tel = '';
			$tipo = '';
			$cotiza = '';
			$simbolo = '';
			$habilitado = '';
			$est = '';
			$norma = '';
			$tobj = 0;
			$item_id = '';
			$trenov = '';
			$sol_sitlab = 0;
			$sol_cony = 0;
			$sol_ingreso = 0;
			$sol_benef = 0;
			$val_propunica = 0;
			$val_benefunaprop = 0;
			$val_titcony = 0;
			$val_persfisica = 0;
			$val_persjuridica = 0;
			$fos = '';
			$fot = '';
			$genauto = '';
			$supuesto = '';
			$deuda_desde = '';
			$deuda_hasta = '';
			$hono_min = '';
			$hono_porc = '';
			$gastos = '';
			$origen = '';
			$art = '';
			$inc = '';
			$multa_min = '';
			$multa_max = '';
			$accion = '';
			$tipo_fallo = '';
			$texto_id = '';
			$texto_id_pie = '';
			$trib_id = '';
			$cat = '';

			$ttitu1 = 0;
			$ttitu1_matric = '';
			$ttitu1_facu = '';
			$ttitu2 = 0;
			$ttitu2_matric = '';
			$ttitu2_facu = '';
			$num = '';
			$tdoc = 0;
			$ndoc = '';
			$dom_part = '';
			$tel_part = '';
			$dom_prof = '';
			$tel_prof = '';
			$mail = '';
			$carnet_gestor = '';
			$matric_muni = '';
			$cuit = '';
			$expe = '';
			$es_cons = 0;
			$es_empre = 0;
			$contacto = '';
			$obs = '';
			$fchbaja = '';
			$motivo_baja = '';
			$fchultpago = '';
			$anioultpago = '';
			$ofi_id_alta = '';
			$ofi_id = '';
			$req_inm = '';
			$req_sancion = '';
			$plazo = '';
			$aforo_id = '';
			$marca = '';
			$tipo = '';
			$modelo = '';
			$marca_nom = '';
			$tipo_nom = '';
			$modelo_nom = '';
			$marca = '';
			$encargado = '';
			$prov_id = '';
			$localidad = '';
			$cp = '';
			$fax = '';
			$anioval = '';
			$gru = 0;
			$anio = '';
			$pesodesde = '';
			$pesohasta = '';
			$valor = '';
			$caja_id = 0;
			$resp = '';
			$sec_id = 0;

			$consulta = 1;
			$mensaje = 1;
		}



		return $this->render('//taux/'.$url,['model' => $model, 'tabla' => $tabla, 'error' => $error,'mensaje'=>$mensaje,
					'cod' => $cod,'nombre' => $nombre, 'tercercampo' => $tercercampo, 'fchfin' => $fchfin,'tcalle' => $tcalle,
					'bco_ent' => $bco_ent,'bco_suc' => $bco_suc,'domi' => $domi,'tel' => $tel,
					'tipo' => $tipo,'cotiza' => $cotiza, 'simbolo' => $simbolo, 'habilitado' => $habilitado,'est' => $est,
					'norma' => $norma ,'tobj' => $tobj, 'item_id' => $item_id, 'trenov' => $trenov,'sol_sitlab' => $sol_sitlab,
					'sol_cony' => $sol_cony,'sol_ingreso' => $sol_ingreso,'sol_benef' => $sol_benef,'val_propunica' => $val_propunica,
					'val_benefunaprop' => $val_benefunaprop,'val_titcony' => $val_titcony,'val_persfisica' => $val_persfisica,
					'val_persjuridica' => $val_persjuridica, 'fos' => $fos, 'fot' => $fot,'genauto' => $genauto, 'est' => $est,
					'supuesto' => $supuesto, 'deuda_desde' => $deuda_desde, 'deuda_hasta' => $deuda_hasta,'hono_min' => $hono_min,
					'hono_porc' => $hono_porc,'gastos' => $gastos,'origen' => $origen, 'art' => $art, 'inc' => $inc, 'multa_min' => $multa_min,
					'multa_max'	=> $multa_max ,'accion' => $accion,'tipo_fallo' => $tipo_fallo,'texto_id' => $texto_id,
					'texto_id_pie' => $texto_id_pie,'trib_id' => $trib_id, 'cat' => $cat,'ttitu1' => $ttitu1,'ttitu1_matric' => $ttitu1_matric,
					'ttitu1_facu' => $ttitu1_facu,'ttitu2' => $ttitu2,'ttitu2_matric' => $ttitu2_matric,'ttitu2_facu' => $ttitu2_facu,
					'num' => $num,'tdoc' => $tdoc,'ndoc' => $ndoc,'dom_part' => $dom_part,'tel_part' => $tel_part,'dom_prof' => $dom_prof,
					'tel_prof' => $tel_prof,'mail' => $mail,'carnet_gestor' => $carnet_gestor,'matric_muni' => $matric_muni,'cuit' => $cuit,
					'expe' => $expe,'es_cons' => $es_cons,'es_empre' => $es_empre,'contacto' => $contacto,'obs' => $obs,'fchbaja' => $fchbaja,
					'motivo_baja' => $motivo_baja,'fchultpago' => $fchultpago,'anioultpago' => $anioultpago,'ofi_id_alta' => $ofi_id_alta,
					'ofi_id' => $ofi_id, 'req_inm' => $req_inm,'req_sancion' => $req_sancion,'plazo' => $plazo,'localidad'=>$localidad,
					'aforo_id' => $aforo_id,'origen' => $origen, $marca => 'marca','tipo' => $tipo,'modelo' => $modelo,'marca_nom' =>$marca_nom,
					'encargado'=>$encargado,'prov_id'=>$prov_id,'cp'=>$cp,'fax'=>$fax,'tipo_nom' => $tipo_nom, 'modelo_nom' => $modelo_nom,
					'anioval'=>$anioval, 'gru'=>$gru,'anio'=>$anio,'pesodesde'=>$pesodesde,'pesohasta'=>$pesohasta,'marca'=>$marca,
					'valor'=>$valor,'caja_id' => $caja_id,'resp' => $resp, 'sec_id' => $sec_id,'consulta' => $consulta
				]);

    }

    public function actionAuxeditredirect($t) {

    	if(isset($_GET['mensaje'])) $mensaje = $_GET['mensaje'];
    	if(isset($_GET['consulta'])) $consulta = $_GET['consulta'];

    	return $this->redirect(['auxedit', 't' => $t,'mensaje'=>$mensaje,'consulta'=>$consulta]);
    }


/***************************************  REPORTES PDF  ****************************************************/

    public function actionPdflist($format = 'A4-P') {

      	if (!isset(Yii::$app->session['proceso_asig']) or !utb::getExisteProceso(Yii::$app->session['proceso_asig']))
      		return $this->render('nopermitido');

      	$pdf = Yii::$app->pdf;
      	if (strtoupper($format) != 'A4-P') $pdf->format = strtoupper($format);

      	$session= Yii::$app->session;
      	$session->open();

      	$columnas= $session->get('columns', []);
      	$sql= $session->get('sql', 'Select 1');
      	$titulo= $session->get('titulo', '');
      	$condicion= $session->get('condicion', '');

      	//$dataProvider= new SqlDataProvider(['sql' => $sql,'totalCount' => 1000,'pagination'=> ['pageSize'=>1000,],]);

		$footer = array_column($columnas,'footer','attribute');
		$array = Yii :: $app->db->createCommand($sql)->queryAll();

		if (count($footer)>0) {
			$attribute = array_column($columnas,'attribute');
			foreach ($attribute as $clave)
			{
				$attribute[$clave] = $footer[$clave];
			}

			$array[] = $attribute;
      	}
		$dataProvider = new ArrayDataProvider(['allModels' => $array,'totalCount' => 1000,'pagination'=> ['pageSize'=>1000,],]);

      	$session->close();

      	Yii::$app->session['cant_reg'] = count($array) - 1;

        $pdf->marginTop = '30px';
		$pdf->content = $this->renderPartial('//reportes/reportelist', ['columnas' => $columnas, 'provider' => $dataProvider, 'titulo' => $titulo, 'condicion' => $condicion]);

		return $pdf->render();
    }

/***************************************  EXPORTAR  ****************************************************/

    public function actionExportar(){

		if (!isset(Yii::$app->session['proceso_asig']) or !utb::getExisteProceso(Yii::$app->session['proceso_asig']))
      		return $this->render('nopermitido');

      	$array2 = [];
		if ( isset(Yii::$app->session['query']) )
			$array = Yii::$app->session['query']->createCommand()->queryAll();
		else 	
			$array = Yii::$app->db->createCommand(Yii::$app->session['sql'])->queryAll();
			
		if (count($array) == 0) return "<script>history.go(-1)</script>";

		if (isset($_POST['rbFormato']))
		{
			$titulo = (isset($_POST['txTitulo']) ? $_POST['txTitulo'] : Yii::$app->session['titulo']);
			$desc = (isset($_POST['txDetalle']) ? $_POST['txDetalle'] : Yii::$app->session['condicion']);

			// DATOS PARA ARCHIVO DE TEXTO
			// Delimitador de Campo
			if ($_POST['rbDelimitador'] == 'T') $dc = chr(9); //Tab
			if ($_POST['rbDelimitador'] == 'L') $dc = '|'; //Línea Vertical
			if ($_POST['rbDelimitador'] == 'C') $dc = ','; //Coma
			if ($_POST['rbDelimitador'] == 'P') $dc = ';'; // Punto y Coma
			if ($_POST['rbDelimitador'] == 'O') $dc = $_POST['txOtroDelim']; // Otro

			// Separador de Fila
			if ($_POST['rbSepFila'] == 'LF') $sf = chr(10);
			if ($_POST['rbSepFila'] == 'CR') $sf = chr(13);

			// Si se incluye Fila de Título
			$it = (isset ($_POST['ckIncTitulo']) && $_POST['ckIncTitulo'] == 1 ? $_POST['ckIncTitulo'] : 0);

			$LineaT = ''; // Titulo del Archivo txt
			$Linea = ''; // Cuerpo del Archivo txt
			// FIN DATOS PARA ARCHIVO DE TEXTO


			$i=0;
			$tabla = ''; // tabla para exportar LibreOffice
			$tablaT = '<tr>'; // encabezado de las columnas a exportar LibreOffice
			foreach($array as $item)
			{
				if ($_POST['rbFormato'] == 'L') $tabla .= "<tr>";
				foreach($item as $clave => $valor)
				{
				  for ($j=0; $j<count(Yii::$app->session['columns']);$j++)
				  {
				  	if (Yii::$app->session['columns'][$j]['attribute'] == $clave)
				  	{
				  		// archivo de texto
				  		if ($_POST['rbFormato'] == 'T')
						{
							// si se incluye título, genero la línea con el mismo
							if ($it == 1  and $i == 0) $LineaT .= Yii::$app->session['columns'][$j]['label'].$dc;
							$Linea .= $valor.$dc;
						}
				  		// archivo de excel
				  		if ($_POST['rbFormato'] == 'E') $array2[Yii::$app->session['columns'][$j]['label']] = $valor;

				  		// archivo de libre office
				  		if ($_POST['rbFormato'] == 'L' and $i == 0) $tablaT .= "<td>".Yii::$app->session['columns'][$j]['label']."</td>";
				  		if ($_POST['rbFormato'] == 'L') $tabla .= "<td>".$valor."</td>";
				  	}
				  }
				}

				if ($_POST['rbFormato'] == 'E') $exportar[$i] = $array2;
				if ($_POST['rbFormato'] == 'T' and $Linea != '') $Linea .= $sf;
				if ($_POST['rbFormato'] == 'L') $tabla .= "</tr>";
				$i += 1;
			}
			$tablaT .= '</tr>';
			$tabla = '<table>'.$tablaT.$tabla.'</table>';

			unset($array);

			if ($_POST['rbFormato'] == 'L') // libreoffice
			{
				header("Content-type: application/vnd.oasis.opendocument.spreadsheet");
		        header("Content-Disposition: attachment; filename=\"$titulo.ods\";" );

				print $tabla;
			}
			if ($_POST['rbFormato'] == 'E') // excel
			{
				$excel_data = Export2ExcelBehavior::excelDataFormat($exportar); // obtengo los datos del array
				$excel_title = $excel_data['excel_title']; // indico los títulos de las celdas según las claves del array
				$excel_ceils = $excel_data['excel_ceils']; // indico los datos de las celdas

				$excel_content = [
				    [
				        'sheet_name' => 'Listado', // Nombre de pestaña de la hoja de cálculo
					    'sheet_title' => $excel_title,
					    'ceils' => $excel_ceils,
					    'headerColor' => Export2ExcelBehavior::getCssClass("header"),
					],
				];

				$excel_props = [
					'creator' => Yii::$app->param->muni_name,
			        'title' => $titulo,
			        'subject' => '',
			        'desc' => $desc,
			        'keywords' => '',
			        'category' => ''
        		];

		        $this->export2excel($excel_content, $titulo, $excel_props); // parm1: contenido del excel, parm2:nombre del archivo


			}
			if ($_POST['rbFormato'] == 'T') // archivo de texto
			{
				if ($LineaT != '') $LineaT .= $sf;
				$Linea = $LineaT.$Linea;

				header("Content-Type: application/force-download");
		        header("Content-Disposition: attachment; filename=\"$titulo.txt\";" );

				print $Linea;
			}
		}
    }


    /**
     * Genera un pdf como reporte
     *
     * @param Array $columnas Arreglo de columnas con el formato valido para un DataProvider.
     * @param string $sql Codigo SQL a ejecutar para obtener los datos que contendra el DataProvider.
     * @param string $titulo Titulo del documento.
     * @param string $condicion Condicion que cumplen los registros que se estan mostrando. Esta parametro solamente se utilizara para mostrarselo al usuario.
     * @param
     */
    public static function imprimirReporte($columnas, $sql, $titulo, $condicion, $formato= 'A4-P'){


    	if (!isset(Yii::$app->session['proceso_asig']) or !utb::getExisteProceso(Yii::$app->session['proceso_asig']))
      		return $this->render('nopermitido');

      	$pdf = Yii::$app->pdf;
      	if (strtoupper($formato) != 'A4-P') $pdf->format = strtoupper($formato);

      	//$dataProvider= new SqlDataProvider(['sql' => $sql,'totalCount' => 1000,'pagination'=> ['pageSize'=>1000,],]);

		$footer = array_column($columnas,'footer','attribute');
		$array = Yii :: $app->db->createCommand($sql)->queryAll();

		if (count($footer)>0) {
			$attribute = array_column($columnas,'attribute');
			foreach ($attribute as $clave)
			{
				if ($footer[$clave] <> '')
					$attribute[$clave] = str_replace(',','',$footer[$clave]);
				else
					$attribute[$clave] = null;
			}

			$array[] = $attribute;

      	}
		Yii::$app->session['cant_reg'] = count($array) - 1;

		$dataProvider = new ArrayDataProvider(['allModels' => $array,'totalCount' => 1000,'pagination'=> ['pageSize'=>1000,],]);
		$pdf->marginTop = '30px';
        $pdf->content = Yii::$app->controller->renderPartial('//reportes/reportelist', ['columnas' => $columnas, 'provider' => $dataProvider, 'titulo' => $titulo, 'condicion' => $condicion, 'totales' => $totales]);

		return $pdf->render();

    }
}
