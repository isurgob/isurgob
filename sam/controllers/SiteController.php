<?php

namespace app\controllers;

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

	    $permitirSiempre = [
				'site-captcha', 'site-signup', 'site-index', 'site-error', 'site-contact', 'site-login', 'site-logout', 'site-about', 'site-cbioclave', 
				'site-pdflist', 'site-exportar', 'site-download', 'site-auxeditredirect', 'site-limpiarvariablereporte'
		];

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
				'municipios' => $model->CargarMunicipios()
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
		
		$part_nom = '';
		$nropart = '';
		
		$part_nom2 = '';
		$nropart2 = '';
		
		$part_nom3 = '';
		$nropart3 = '';
			
		if(isset($_POST['txAccion'])) $consulta = $_POST['txAccion'];
		if(isset($_POST['txForm'])) $txForm = $_POST['txForm'];

		$model = tablaAux::findOne($t);

		if ($model->link == '')
		{
			$url = 'auxedit';
			$model->nombrelong = tablaAux::GetCampoLong($model->nombre,'nombre');
			$model->codlong = ($model->tcod == 'N' ? 4 : tablaAux::GetCampoLong($model->nombre,'cod'));
			if ($t == 211) $model->codlong = 6;
			$model->CargarTercerCampo();
			$tabla = tablaAux::CargarTabla($model->nombre, $model->tercercamponom);
		}else {
			if($t==37){
				$url = $model->link;
				$tabla = tablaAux::CargarTabla('cem_cuadro');
			}elseif($t==133){
				$url = $model->link;

				$tabla = (new tablaAux())->CargarTablaOficina();
			}elseif($t==139){
				$url = $model->link;

				$tabla = (new tablaAux())->CargarTablaSecretaria();	
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
				// solo tabla domi_calle
				$tcalle = (isset($_POST['tipo']) ? $_POST['tipo'] : 0);
				// solo tabla banco
				$bco_ent = (isset($_POST['bco_ent']) ? $_POST['bco_ent'] : 0);
				$bco_suc = (isset($_POST['bco_suc']) ? $_POST['bco_suc'] : 0);
				$domi = (isset($_POST['domi']) ? $_POST['domi'] : 0);
				$tel = (isset($_POST['tel']) ? $_POST['tel'] : 0);
				// solo tabla muni_oficina
				$resp = (isset($_POST['resp']) ? $_POST['resp'] : 0);
				$sec_id = (isset($_POST['sec_id']) ? $_POST['sec_id'] : 0);
                
				// solo tabla y muni_oficina muni_sec
				$part_id = (isset($_POST['part_id']) ? $_POST['part_id'] : 0 );
				$part_id2 = (isset($_POST['part_id2']) ? $_POST['part_id2'] : 0 );
				$part_id3 = (isset($_POST['part_id3']) ? $_POST['part_id3'] : 0 );

				if ($consulta != 2 and $t==130) $error = $model->grabarTablaAuxDomiCalle($consulta,$cod,$nombre,$tcalle);
				if ($consulta != 2 and $t==80) $error = $model->grabarTablaAuxBancoSuc($consulta,$bco_ent,$bco_suc,$nombre,$domi,$tel);
				if ($consulta == 2 and $t==80) $error = $model->borrarTablaAuxBancoSuc($bco_ent,$bco_suc);
				if ($consulta != 2 and $t==133) $error = $model->grabarTablaAuxOficina($consulta,$cod,$nombre,$resp,$sec_id,$part_id);
				if ($consulta != 2 and $t==139) $error = $model->grabarTablaAuxSecretaria($consulta,$cod,$nombre,$part_id,$part_id2,$part_id3);

				if ($consulta == 2 and $t!=80 and $t!=128) $error = $model->borrarTablaAux($cod);

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
			$tcalle = 0;
			$bco_ent = '';
			$bco_suc = '';
			$domi = '';
			$tel = '';

			$resp = '';
			$sec_id = 0;
			
			$part_id = 0;
			$part_nom = '';
			$nropart = '';
			
			$part_id2 = 0;
			$part_nom2 = '';
			$nropart2 = '';
			
			$part_id3 = 0;
			$part_nom3 = '';
			$nropart3 = '';

			$consulta = 1;
			$mensaje = 1;
		}
		
		if( Yii::$app->request->get( '_pjax', '' ) == '#pjaxCambiaPartida' ){
			$nropart = Yii::$app->request->get( 'partida', 0 );
			$cond = 'tiene_hijo=false AND anio = ' . date('Y');

			//INICIO Cargar y Validar Partida
			if( strpos( $nropart, '.' ) > -1 ){
				$cond .= " and (formato = '" . $nropart .
						"' OR formatoaux = '" .  $nropart . "')";
			} else {
				$cond .= " and (nropart = " . intVal( $nropart  ) . " or part_id=" . intVal( $nropart  ) . ")";
			}
			
			$part_id = intVal(utb::getCampo("fin.v_part", $cond, "part_id"));
			$part_nom = utb::getCampo("fin.v_part", "part_id=" . $part_id, "nombre");
		}
		
		if( Yii::$app->request->get( '_pjax', '' ) == '#pjaxCambiaPartida2' ){
			$nropart2 = Yii::$app->request->get( 'partida2', 0 );
			$cond = 'tiene_hijo=false AND anio = ' . date('Y');

			//INICIO Cargar y Validar Partida
			if( strpos( $nropart2, '.' ) > -1 ){
				$cond .= " and (formato = '" . $nropart2 .
						"' OR formatoaux = '" .  $nropart2 . "')";
			} else {
				$cond .= " and (nropart = " . intVal( $nropart2  ) . " or part_id=" . intVal( $nropart2  ) . ")";
			}
			
			$part_id2 = intVal(utb::getCampo("fin.v_part", $cond, "part_id"));
			$part_nom2 = utb::getCampo("fin.v_part", "part_id=" . $part_id2, "nombre");
		}
		
		if( Yii::$app->request->get( '_pjax', '' ) == '#pjaxCambiaPartida3' ){
			$nropart3 = Yii::$app->request->get( 'partida3', 0 );
			$cond = 'tiene_hijo=false AND anio = ' . date('Y');

			//INICIO Cargar y Validar Partida
			if( strpos( $nropart3, '.' ) > -1 ){
				$cond .= " and (formato = '" . $nropart3 .
						"' OR formatoaux = '" .  $nropart3 . "')";
			} else {
				$cond .= " and (nropart = " . intVal( $nropart3  ) . " or part_id=" . intVal( $nropart3  ) . ")";
			}
			
			$part_id3 = intVal(utb::getCampo("fin.v_part", $cond, "part_id"));
			$part_nom3 = utb::getCampo("fin.v_part", "part_id=" . $part_id3, "nombre");
		}

		return $this->render('//taux/'.$url,['model' => $model, 'tabla' => $tabla, 'error' => $error,'mensaje'=>$mensaje,
					'cod' => $cod,'nombre' => $nombre, 'tercercampo' => $tercercampo, 'tcalle' => $tcalle,
					'bco_ent' => $bco_ent,'bco_suc' => $bco_suc,'domi' => $domi,'tel' => $tel,
					'resp' => $resp, 'sec_id' => $sec_id, 'part_id' => $part_id, 'nropart' => $nropart, 'part_nom' => $part_nom,
					'part_id2' => $part_id2, 'nropart2' => $nropart2, 'part_nom2' => $part_nom2, 'part_id3' => $part_id3, 'nropart3' => $nropart3, 'part_nom3' => $part_nom3,
					'consulta' => $consulta
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

      	$dataProvider= new SqlDataProvider(['sql' => $sql,'totalCount' => 1000,'pagination'=> ['pageSize'=>1000,],]);

      	$session->close();

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

      	$dataProvider= new SqlDataProvider(['sql' => $sql,'totalCount' => 1000,'pagination'=> ['pageSize'=>1000,],]);

        $pdf->content = Yii::$app->controller->renderPartial('//reportes/reportelist', ['columnas' => $columnas, 'provider' => $dataProvider, 'titulo' => $titulo, 'condicion' => $condicion, 'totales' => $totales]);

		return $pdf->render();

    }
	
}
