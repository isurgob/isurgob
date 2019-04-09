<?php

namespace app\models\objeto;

use Yii;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Session;
use app\utils\db\utb;
use yii\helpers\Html;

/**
 * This is the model class for table "inm".
 *
 * @property string $obj_id
 * @property string $nc
 * @property string $s1
 * @property string $s2
 * @property string $s3
 * @property string $manz
 * @property string $parc
 * @property integer $parp
 * @property integer $parporigen
 * @property integer $plano
 * @property integer $anio_mensura
 * @property integer $uf
 * @property string $porcuf
 * @property integer $urbsub
 * @property integer $regimen
 * @property integer $tinm
 * @property integer $titularidad
 * @property integer $uso
 * @property string $matric
 * @property string $fchmatric
 * @property integer $anio
 * @property string $supt
 * @property string $supt_pasillo
 * @property string $supm
 * @property string $avalt
 * @property string $avalm
 * @property string $frente
 * @property string $fondo
 * @property integer $es_esquina
 * @property integer $es_calleppal
 * @property string $zonat
 * @property integer $zonav
 * @property integer $zonaop
 * @property integer $agua
 * @property integer $cloaca
 * @property integer $gas
 * @property integer $alum
 * @property integer $pav
 * @property string $valbas
 * @property string $coef
 * @property integer $barr_id
 * @property integer $patrimonio
 * @property string $objeto_superp
 * @property string $archivo
 * @property string $expe
 * @property string $comprador
 *
 *
 * ----- INICIO Propiedades para Frentes
 *
 * Table name: inm_frente
 *
 * @property string $frente_obj_id
 * @property integer $frente_calle_id
 * @property integer $frente_medida
 * @property string $frente_fchmod
 * @property string $frente_usrmod
 *
 * ----- FIN Propiedades para Frentes
 *
 * ----- INICIO Propiedades para Mejoras
 *
 * 	Table name: inm_mej
 *
 * @property string $mejora_obj_id
 * @property integer $mejora_pol
 * @property integer $mejora_perdesde
 * @property integer $mejora_perhasta
 * @property integer $mejora_tori
 * @property integer $mejora_tform
 * @property integer $mejora_nivel
 * @property integer $mejora_tdest
 * @property integer $mejora_tobra
 * @property integer $mejora_anio
 * @property integer $mejora_est
 * @property integer $mejora_subcup
 * @property integer $mejora_supsemi
 * @property integer $mejora_plantas
 * @property string $mejora_cat
 * @property integer $mejora_item01
 * @property integer $mejora_item02
 * @property integer $mejora_item03
 * @property integer $mejora_item04
 * @property integer $mejora_item05
 * @property integer $mejora_item06
 * @property integer $mejora_item07
 * @property integer $mejora_item08
 * @property integer $mejora_item09
 * @property integer $mejora_item10
 * @property integer $mejora_item11
 * @property integer $mejora_item12
 * @property integer $mejora_item13
 * @property integer $mejora_item14
 * @property integer $mejora_item15
 * @property string $mejora_fchmod
 * @property string $mejora_usrmod
 *
 * ----- FIN Propiedades para Mejoras
 */
class Inm extends \yii\db\ActiveRecord
{

	public $error;

	//Estas variables almacenarán los datos de los domicilios
	//Su principal función es la de determinar si se cargaron los domicilios
	public $domi_postal;
	public $domi_parcelario;


	/* ----- INICIO Propiedades para Frentes
	 *
	 * Table name: inm_frente
	 */
	 public $frente_obj_id;
	 public $frente_calle_id;
	 public $frente_medida;
	 public $frente_fchmod;
	 public $frente_usrmod;

	 public $arrayFrente = []; 	//Arreglo que se usa para obtener los datos de frente de la BD para un inmueble
	 							//seleccionado y para realizar el ABM de sus datos.

	 public $arrayMejoras = []; //Arreglo que se usa para obtener los datos de mejoras de la BD para un inmueble
	 							//sellecionado y para realizar el ABM de sus datos.

	 /* ----- FIN Propiedades para Frentes
	 *
	 * ----- INICIO Propiedades para Mejoras
	 *
	 * 	Table name: inm_mej
	 */
	 public $mejora_obj_id;
	 public $mejora_pol;
	 public $mejora_perdesde;
	 	public $mejora_aniodesde;
	 	public $mejora_cuotadesde;
	 public $mejora_perhasta;
	 	public $mejora_aniohasta;
	 	public $mejora_cuotahasta;
	 public $mejora_tori;
	 public $mejora_tform;
	 public $mejora_nivel;
	 public $mejora_tdest;
	 public $mejora_tobra;
	 public $mejora_anio;
	 public $mejora_est;
	 public $mejora_subcup;
	 public $mejora_supsemi;
	 public $mejora_plantas;
	 public $mejora_cat;
	 public $mejora_item01;
	 public $mejora_item02;
	 public $mejora_item03;
	 public $mejora_item04;
	 public $mejora_item05;
	 public $mejora_item06;
	 public $mejora_item07;
	 public $mejora_item08;
	 public $mejora_item09;
	 public $mejora_item10;
	 public $mejora_item11;
	 public $mejora_item12;
	 public $mejora_item13;
	 public $mejora_item14;
	 public $mejora_item15;
	 public $mejora_fchmod;
	 public $mejora_usrmod;
	/*
	 * ----- FIN Propiedades para Mejoras
	 */

	 /*
	  * Variables para avaluo
	  */
	 public $modificaAvaluo; 		// Esta variable determina si se modifican los datos de avaluo.
	 public $avaluo_perdesde_anio;	// Esta variable almacenará el año de perdesde
	 public $avaluo_perdesde_mes;	// Esa variable almacenará el mes de perdesde

	 public $existe_inm_mej_tcat; // indica si existen datos en inm_mej_tcat, para luego mostrar un combo o un textbox


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'inm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['urbsub', 'regimen', 'titularidad', 'uso','pav','zonat'], 'required'],
            [[
				'parp', 'parporigen', 'plano', 'anio_mensura', 'regimen', 'uso', 'anio', 'zonav', 'zonaop', 'pav', 'barr_id', 'patrimonio',
				'es_esquina','es_calleppal', 'agua', 'cloaca', 'gas', 'alum', 'unihab'
            ], 'integer'],
            [['porcuf', 'supt', 'supt_pasillo', 'supm', 'avalt', 'avalm', 'frente','fondo', 'valbas', 'coef', 'modificaAvaluo', 'avaluo_perdesde_anio', 'avaluo_perdesde_mes'], 'number'],
            [['fchmatric'], 'safe'],
            [['obj_id', 'objeto_superp'], 'string', 'max' => 8],
			[['nc'], 'string', 'max' => 26],
            [['s1', 's2', 's3'], 'string', 'max' => 3],
            [['manz'], 'string', 'max' => 6],
            [['urbsub', 'zonat', 'titularidad'], 'string', 'max' => 2],
            [['parc', 'uf' ], 'string', 'max' => 5],
            [['tinm', 'tmatric'], 'string', 'max' => 1 ],
            [[ 'matric'], 'string', 'max' => 15],
            [['archivo','nc_ant'], 'string', 'max' => 20],
            [['expe'], 'string', 'max' => 12],
            [['comprador'], 'string', 'max' => 50],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'obj_id' => 'Codigo de objeto',
            'nc' => 'Nomenclatura',
			'nc_ant' => 'Nomenclatura Anterior',
            's1' => 'Sector',
            's2' => 'Grupo',
            's3' => 'Grupo 2',
            'manz' => 'Manzana',
            'parc' => 'Parcela',
            'parp' => 'Partida provincial',
            'parporigen' => 'Partida provincial origen',
            'plano' => 'Plano',
            'anio_mensura' => 'Año de la mensura',
            'uf' => 'Si es unidad funcional',
            'porcuf' => 'Porcentaje de unidad funcional',
            'urbsub' => 'Codigo de urbsub',
            'regimen' => 'Codigo de regimen',
            'tinm' => 'Codigo de tipo de inmueble',
            'titularidad' => 'Codigo de titularidad',
            'uso' => 'Codigo de tipo de uso',
            'tmatric' => 'Tipo de matrícula',
            'matric' => 'Numero de matricula',
            'fchmatric' => 'Fecha de la matricula',
            'anio' => 'Año de matricula',
            'supt' => 'Superficie del terreno',
            'supt_pasillo' => 'Superficie de pasillo',
            'supm' => 'Superficie de mejoras',
            'avalt' => 'Avaluo del terreno',
            'avalm' => 'Avaluo de las mejoras',
            'frente' => 'Metros de frente',
            'fondo' => 'Metros de fondo',
            'es_esquina' => 'Esquina',
            'es_calleppal' => 'Calle Ppal',
            'zonat' => 'Codigo de zona tributaria',
            'zonav' => 'Codigo de zona valuatoria',
            'zonaop' => 'Codigo de zona obras privadas',
            'pav' => 'Codigo de pavimento',
            'valbas' => 'Valor basico',
            'coef' => 'Coeficiente de ajuste',
            'barr_id' => 'Codigo de barrio',
            'patrimonio' => 'Patrimonio',
            'objeto_superp' => 'Codigo de objeto en superposicion',
            'archivo' => 'Archivo',
            'expe' => 'Identificacion del expediente',
            'comprador' => 'Comprador',
            'modificaAvaluo' => 'Modifica Avalúo',
			'unihab' => 'Unidad Habitacional'
        ];
    }

    public function __construct()
    {
    	 $this->modificaAvaluo = true;
    	 $this->avaluo_perdesde_anio = date('Y');
    	 $this->avaluo_perdesde_mes = date('m');

		 $this->existe_inm_mej_tcat = $this->getExisteInmMejTCat();

    }

    public function afterFind()
    {
    	$this->modificaAvaluo = false;

    	//Obtengo el perdesde de avaluo
    	$sql = "SELECT MAX(perdesde) FROM inm_avaluo WHERE obj_id = '" . $this->obj_id . "'";

    	$perdesde = Yii::$app->db->createCommand( $sql )->queryScalar();

    	$this->avaluo_perdesde_anio = substr( $perdesde, 0, 4 );
    	$this->avaluo_perdesde_mes = intval( substr( $perdesde, 4, 3 ) );

		$this->existe_inm_mej_tcat = $this->getExisteInmMejTCat();
    }

    public function setError($error)
    {
    	$this->error .= $error;
    }

    public function getError()
    {
    	return $this->error;
    }

    public function resetError()
    {
    	$this->error  = [];
    }

    /**
     * Función que se encarga de validar los datos de inmuebles que ingresarán a la BD.
     */
    public function validarInmueble ( $m = 0 )
    {
    	$error = [];

    	$arreglo = $this->arregloLabels();
	    $arregloValidacion = $this->validateArregloLabels();

		$array = utb::samConfig();
		$validarNomenclatura = $array['inm_valida_nc'];
		$nomenclaturaIncompleta = false;

		foreach ($arreglo as $clave => $valor)
		{
			if ($validarNomenclatura == 1) //En el caso que se valide nomenclatura
			{
                if ( current( $arregloValidacion ) )
                {
                	if ( $this->$clave == '' )
                    {
                        $error[] = 'Nomenclatura Incompleta.';
                        $nomenclaturaIncompleta = true;
                        break;
                    }
                }

                next( $arregloValidacion );

			}
			// Quito la validación de numero de parcela ( campo parc) Chany 10/02/2017.
			//if ($clave == 'parc' && $this->$clave == '' )
			//	$error[] = 'Valor de Parcela Incorrecto.';

		}

		if ($this->parc == '' ) $this->parc = '0';  // Establezco valor por defecto para parc - Chany 10/02/2017.

		if(!$nomenclaturaIncompleta){
			$sql		= "Select * from sam.uf_inm_armar_nc('$this->s1', '$this->s2', '$this->s3', '$this->manz', '$this->parc')";
			$this->nc	= Yii::$app->db->createCommand($sql)->queryScalar();
		}


		if ($validarNomenclatura == 1 && !$nomenclaturaIncompleta)
		{
			$sql = "Select Exists (Select 1 From inm Where NC='" . $this->nc . "' and UF= '" . ($this->uf=='' ? 0 : $this->uf) . "'";

            if (!$this->isNewRecord)
                $sql .= " and obj_id <> '".$this->obj_id."'";

            $sql .= ")";

			$existe = Yii::$app->db->createCommand($sql)->queryScalar();
			if ($existe) $error[] = 'Nomenclatura Repetida.';
		}


    	// Inicio Seteo valores para las variables de manera que se puedan grabar en la BD
    	if ($this->uf == '') $this->uf = 0;
		if ($this->porcuf == '') $this->porcuf = 0;
		if ($this->parp == '') $this->parp = 0;
		if ($this->parporigen == '') $this->parporigen = 0;
		if ($this->plano == '') $this->plano = 0;
		if ($this->supt == '') $this->supt = 0;
		if ($this->supt_pasillo == '') $this->supt_pasillo = 0;
		if ($this->supm == '') $this->supm = 0;
		if ($this->avalm == '') $this->avalm = 0;
		if ($this->frente == '') $this->frente = 0;
		if ($this->fondo == '') $this->fondo = 0;
		if ($this->valbas == '') $this->valbas = 0;
		if ($this->anio == '') $this->anio = 0;
		if ($this->avalt == '') $this->avalt = 0;
		if ($this->coef == '') $this->coef = 1;
		if ($this->anio == '') $this->anio = 0;
		if ($this->fchmatric == '') $this->fchmatric = null;
		if ($this->anio_mensura == '') $this->anio_mensura = 0;
		if ($this->zonav == '') $this->zonav = 0;
		if($this->patrimonio <= 0) $this->patrimonio= 0;
		if ($this->es_esquina <= 0) $this->es_esquina = 0;
		if ($this->es_calleppal <= 0) $this->es_calleppal = 0;
		if($this->uso <= 0) $this->uso= 0;
		if($this->pav <= 0) $this->pav= 0;
		if($this->agua <= 0) $this->agua = 0;
		if($this->cloaca <= 0) $this->cloaca = 0;
		if($this->gas <= 0) $this->gas = 0;
		if($this->alum <= 0) $this->alum = 0;
		if($this->zonaop <= 0) $this->zonaop = 0;
		if($this->barr_id <= 0) $this->barr_id = 'null';
		if($this->unihab <= 0) $this->unihab = 0;

		// Fin Seteo valores iniciales

		//0 <= $model->supt <= 9999
		if ($this->supt < 0 || $this->supt > 99999999)
			$error[] = 'Superficie de terreno mal ingresada.';

		//regimen = 3 => uf > 0
		if ( $this->regimen == 3 ){

			if( $m == 0 and ($this->uf == '' || $this->uf <= 0) ){

				$error[] = 'La Unidad Funcional es obligatoria de acuerdo al Régimen ingresado.';
			}

			if( $m == 0 and ($this->porcuf == '' || $this->porcuf <= 0) ){

				$error[] = 'El porcentaje de Unidad Funcional es obligatorio de acuerdo al Régimen ingresado.';
			}

		} else {

			if( $this->uf != '' && $this->uf > 0 ){

				$error[] = 'No se debe ingresar Unidad Funcional de acuerdo al Régimen ingresado.';
			}

			if( $this->porcuf != '' && $this->porcuf > 0 ){

				$error[] = 'No se debe ingresar el porcentaje de Unidad Funcional de acuerdo al Régimen ingresado.';
			}
		}


		//tipo = O => titularidad <> PO
		if ($this->tinm == 'O' and $this->titularidad != 'PO')
			$error[] = 'El Tipo de Inmueble y Titularidad son incompatibles.';

		if($this->regimen <= 0) $error[]= 'Elija un régimen.';
		if($this->urbsub == '') $error[]= 'Elija un UrbSub.';

		if( $this->zonat == '' ) $error[]= 'Elija una zona tributaria.';

		/**
		 * Se debe validar el tipo de régimen con la cantidad de mejoras
		 */
		if($this->objeto_superp != ''){

			if((Yii::$app->db->createCommand("SELECT Exists (Select 1 from objeto where obj_id = '" . $this->objeto_superp . "' And est <> 'B')")->queryScalar() == 0)){
				$error[] = 'El Inmueble en superposición no existe.';
				$this->objeto_superp = '';
			} else if(Yii::$app->db->createCommand("Select Exists (Select 1 From inm Where obj_id = '$this->objeto_superp' And manz = '$this->manz')")->queryScalar() == 0) {
				$error[] = 'El Inmueble en superposición no se encuentra en la misma Manzana.';
				$this->objeto_superp = '';
			}
		}

		/**
		 * Se debe validar, en caso de encontrarse modificaAvaluo activo, el anño y cuota de perdesde
		 */

		if ( $this->modificaAvaluo )
		{
			if ( $this->avaluo_perdesde_anio == '' || $this->avaluo_perdesde_mes == '' )
				$error[] = 'Ingrese un período de vigencia de avalúo.';
		}

		$session = new Session;
	    $session->open();

    	//Realizo validaciones más específicas
    	if (count($error) == 0)
    	{
			$arraySamConfig = utb::samConfig(); //Arreglo con valores de configuración
			$arregloFrentes= $session->get('arregloFrentes', []);
			$arregloMejoras= $session->get('arregloMejoras', []);

			//INICIO Verificar si se validan frentes y validarlos en caso afirmativo
	    	$valida = $arraySamConfig["inm_valida_frente"];

	    	if ($valida == 1)
	    	{
			    //Para validar frente, consulto la cantidad de elementos del arreglo de frentes en sesión.
			    $cant = count($arregloFrentes);

			    if ($cant == 0) $error[] = 'Debe ingresar Frentes.';
	    	}
	    	//FIN Verificar si se validan frentes y validarlos en caso afirmativo


    		if( $this->regimen > 0 ) {

    			//Validar que si el régimen es Baldío, no existan mejoras
		    	if ($this->regimen == 1 && count($arregloMejoras) > 0)
		    		$session['advertenciaRegimen'] = 'Si el Régimen es Baldío, no debería ingresar superficie y avalúos de mejoras.';

		    	if ($this->regimen != 1 && count($arregloMejoras) == 0)
		    		$session['advertenciaRegimen'] = 'Si el Régimen no es Baldío, debería ingresar superficie y avalúos de mejoras.';


		    	//Se debe agregar la validación por computados

				//el regimen es edificado
		    	if( $this->regimen == 2 ) {

		    		//Verfico superficie de mejoras
			    	if (!$this->verificarComputado("supm") && $this->supm <= 0)
			    		$session['advertenciaRegimen'] = 'Si el Régimen es Edificado, debería ingresar Superficie de Mejoras.';

			    	//Verfico avalúo de mejoras
			    	if (!$this->verificarComputado("avalm") && $this->avalm <= 0)
			    		$session['advertenciaRegimen'] = 'Si el Régimen es Edificado, debería ingresar Avalúo de Mejoras.';

		    		//Validación de frente por computado
		    		if (!$this->verificarComputado("frente") && $this->frente <= 0 && $valida == 1) //$valida verifica si se validan los frentes
		    		$error[] = 'Si el Régimen es Edificado, debería ingresar Avalúo de Mejoras.';

		    	}
    		}

			//Validar de coeficiente por computado - 0.08 < $this->coef < 2
			if ( (!$this->verificarComputado("coef")) && ( floatval( $this->coef ) < 0.08 || floatval( $this->coef ) > 15 ) )
				$error[] = 'El Coeficiente de ajuste es incorrecto.';

	    	//Verfico avalúo de terreno
	    	if (!$this->verificarComputado("avalt") && $this->avalt <= 0)
	    		$error[] = 'Avalúo de terreno mal ingresado.';
    	}


    	$session->close();

		 return $error;

    }

    private function verificarComputado( $campo )
    {
    	$arregloComputados = utb::getComputados();

		foreach ($arregloComputados as $array)
		{
		 	if ( $array['campo'] == $campo )
		 		return true;

		}

		return false;
    }

     public function validarFrente ()
    {
    	$indice = 0;
    	$error = [];

   	 //$this->frente_obj_id no puede ser vacío, pero al valor lo genero cuando creo el frente

	 if ($this->frente_calle_id == '')
	 	$error[$indice++] = "Calle no puede ser vacío.";

	 if ($this->frente_medida = '')
	 	$error[$indice++] = "Medida no puede ser vacío.";

	 //$this->frente_fchmod no puede ser vacío, pero al valor lo genero cuando creo el frente
	 //$this->frente_usrmod no puede ser vacío, pero al valor lo genero cuando creo el frente

	 //$this->addErrors($error);

    }

    /**
     * Función que se encarga de validar los datos de mejoras que ingresarán a la BD.
     */
    public function validarMejora ()
    {
    	$indice = 0;
    	$error = [];

		 if ($this->mejora_nivel == '')
			$this->mejora_nivel = 0;

		 if ($this->mejora_subcup == '')
		 	$this->mejora_subcup = 0;

		 if ($this->mejora_supsemi == '')
		 	$this->mejora_supsemi = 0;

    	// Para obra in (1,2) se debe validar que supcub+supsemi > 0
    	 if (($this->mejora_tobra == 1 || $this->mejora_tobra == 1 ) && (($this->mejora_subcup + $this->mejora_supsemi) <= 0))
    	 	$error[$indice++] = 'La superficie cubierta y/o semicubierta debe ser mayor a 0.';

	     if ($this->mejora_aniodesde == '' || $this->mejora_cuotadesde == '')
	     	$error[$indice++] = 'Ingrese el rango de Períodos desde.';
	     else
	     	$this->mejora_perdesde = ($this->mejora_aniodesde * 100) + $this->mejora_cuotadesde;

	     if ($this->mejora_aniohasta == '' || $this->mejora_cuotahasta == '')
	     	$error[$indice++] = 'Ingrese el rango de Períodos hasta.';
	     else
	     	$this->mejora_perhasta = ($this->mejora_aniohasta * 100) + $this->mejora_cuotahasta;

		 if ($this->mejora_pol == '')
		 	$error[$indice++] = 'Polígono no puede ser vacío.';


		 if ($this->mejora_tori == '')
		 	$error[$indice++] = 'Ingrese un origen.';

		 if ($this->mejora_tform == '')
		 	$error[$indice++] = 'Ingrese un formulario.';

		$this->addErrors($error);
    }

    /**
     * Función que grabará los registros de inmueble en la BD
     */
    public function grabar( $m = 0)
    {
    	$grabarAvaluo = false;

    	//En el caso que se grabe un nuevo registro en la BD
    	if ( $this->isNewRecord )
    	{
    	 	$sql = "INSERT INTO inm (obj_id,nc,s1,s2,s3,manz,parc,uf,porcuf,nc_ant,parp,parporigen,plano,anio_mensura," .
    	 			"expe,urbsub,regimen,tinm,titularidad,uso,tmatric,matric,fchmatric,anio,comprador,supt," .
    	 			"supt_pasillo,supm,avalt,avalm,frente,fondo,es_esquina,es_calleppal,zonat,zonav,zonaop,agua,cloaca," .
    	 			"gas,alum,pav,valbas,coef,barr_id,patrimonio,objeto_superp,archivo,unihab) VALUES ('";
    	 	$sql .= $this->obj_id . "','" . $this->nc . "','" . $this->s1 . "','" . $this->s2 . "','";
            $sql .= $this->s3 . "','" . $this->manz . "','" . $this->parc . "','" . $this->uf . "'," . $this->porcuf . ",'" . $this->nc_ant . "',";
            $sql .= $this->parp . "," . $this->parporigen . "," .$this->plano . "," . $this->anio_mensura . ",";
            $sql .= "'" . $this->expe . "','" .$this->urbsub . "'," .$this->regimen . ",'" .$this->tinm . "','" . $this->titularidad . "',";
            $sql .= $this->uso . ",'" . $this->tmatric . "','" .$this->matric . "',";
            $sql .= ($this->fchmatric == null ? 'null' : "'".$this->fchmatric."'") . "," .$this->anio . ",'" .$this->comprador . "'," . $this->supt . "," . $this->supt_pasillo . ",";
            $sql .= $this->supm . "," . $this->avalt . "," . $this->avalm . "," . $this->frente . "," . $this->fondo . ",";
            $sql .= $this->es_esquina . "," . $this->es_calleppal . ",'" .$this->zonat . "'," .$this->zonav . "," .$this->zonaop . "," .$this->agua . ",";
            $sql .= $this->cloaca . "," . $this->gas . "," . $this->alum . "," . $this->pav . "," . $this->valbas . ",";
            $sql .= $this->coef . "," . $this->barr_id . "," .$this->patrimonio . ",'";
            $sql .= $this->objeto_superp . "','" .$this->archivo . "'," . $this->unihab . ")";

			if ( $this->modificaAvaluo ){

				$grabarAvaluo = true;

			}

    	} else {	//En el caso de actualizar "Inmueble"

    		# Se debe realizar distintas modificaciones dependiendo si se modifica el avalúo

			if ( ! $this->modificaAvaluo )	// No se modifica avalúo
			{
				$sql = "UPDATE inm set NC='" . $this->nc . "',S1='" . $this->s1 . "',S2='" . $this->s2 . "',S3='";
	            $sql .= $this->s3 . "',manz='" . $this->manz . "',parc='" . $this->parc . "',uf='" . $this->uf . "',porcuf=" . $this->porcuf . ",nc_ant='" . $this->nc_ant . "',parp=";
	            $sql .= $this->parp . ",parporigen=" .$this->parporigen . ",plano=" .$this->plano . ",anio_mensura=" . $this->anio_mensura . ",";
	            $sql .= "expe='" . $this->expe . "',urbsub='" .$this->urbsub . "',regimen=" .$this->regimen . ",tinm='" .$this->tinm . "',titularidad='" .$this->titularidad . "',";
	            $sql .= "uso=" . $this->uso . ",tmatric='" . $this->tmatric . "',matric='" .$this->matric . "'," . "frente=" . $this->frente . ",";
	            $sql .= "fchmatric=" . ($this->fchmatric == null ? 'null' : "'".$this->fchmatric."'") . ",anio=" .$this->anio . ",comprador='" .$this->comprador . "',";
	            $sql .= "barr_id = " .$this->barr_id . ", patrimonio = " .$this->patrimonio . ",";
	            $sql .= "objeto_superp = '" . $this->objeto_superp . "',archivo = '" .$this->archivo . "',unihab=" . $this->unihab;
	            $sql .= " WHERE obj_id = '" . $this->obj_id . "'";

			} else	// Se modifica avalúo
			{
				$sql = "UPDATE inm set NC='" . $this->nc . "',S1='" . $this->s1 . "',S2='" . $this->s2 . "',S3='";
	            $sql .= $this->s3 . "',manz='" . $this->manz . "',parc='" . $this->parc . "',uf='" . $this->uf . "',porcuf=" . $this->porcuf . ",nc_ant='" . $this->nc_ant . "',parp=";
	            $sql .= $this->parp . ",parporigen=" .$this->parporigen . ",plano=" .$this->plano . ",anio_mensura=" . $this->anio_mensura . ",";
	            $sql .= "expe='" . $this->expe . "',urbsub='" .$this->urbsub . "',regimen=" .$this->regimen . ",tinm='" .$this->tinm . "',titularidad='" .$this->titularidad . "',";
	            $sql .= "uso=" . $this->uso . ",tmatric='" . $this->tmatric . "',matric='" .$this->matric . "',";
	            $sql .= "fchmatric=" . ($this->fchmatric == null ? 'null' : "'".$this->fchmatric."'") . ",anio=" .$this->anio . ",comprador='" .$this->comprador . "',";
	            $sql .= " supt=" . $this->supt . ",supt_pasillo=" . $this->supt_pasillo . ",";
	            $sql .= "supm=" . $this->supm . ",avalt=" . $this->avalt . ",avalm=" . $this->avalm . ",frente=" . $this->frente . ",fondo=" . $this->fondo . ",";
	            $sql .= "es_esquina=" . $this->es_esquina . ",es_calleppal=" . $this->es_calleppal . ",zonat='" .$this->zonat . "',zonav=" .$this->zonav . ",zonaop=" .$this->zonaop . ",";
	            $sql .= "agua=" .$this->agua . ",cloaca=" . $this->cloaca . ",gas=" . $this->gas . ",alum=" . $this->alum . ",pav=" .$this->pav . ",valbas=" . $this->valbas . ",";
	            $sql .= "coef=" . $this->coef . ",barr_id = " .$this->barr_id . ", patrimonio = " .$this->patrimonio . ",";
	            $sql .= "objeto_superp = '" . $this->objeto_superp . "',archivo = '" .$this->archivo . "',unihab=" . $this->unihab;
	            $sql .= " WHERE obj_id = '" . $this->obj_id . "'";

	            $grabarAvaluo = true;
			}

    	}


		if ( ! Yii :: $app->db->createCommand($sql)->execute() > 0 )
		{
			return 'Ocurrió un error al intentar grabar en la BD.';
		}

		# Verificar si se deben grabar los avalúos
		if ( $grabarAvaluo )
			$this->grabarPeriodoAvaluos();

		$sql = "select sam.uf_objeto_computa(1,'$this->obj_id')";
		Yii :: $app->db->createCommand($sql)->execute();

		// si es PH Madre ejecuto funcion
		if ( utb::samConfig()['inm_phmadre'] == 1 and $m == 1 and !$this->isNewRecord ){

			$perdesde = ( intval( $this->avaluo_perdesde_anio ) * 1000 ) + intval( $this->avaluo_perdesde_mes );

			$sql = "select * from sam.uf_inm_phmadre( '$this->obj_id', $perdesde )";

			if ( ! Yii :: $app->db->createCommand($sql)->execute() > 0 )
			{
				return 'Ocurrió un error al intentar grabar PH Madre.';
			}

		}

		return '';

    }

    /**
     * Función que graba el período de avalúo de un inmueble.
     */
    public function grabarPeriodoAvaluos()
    {
    	# Calcular el período de vigencia
    	$perdesde = ( intval( $this->avaluo_perdesde_anio ) * 1000 ) + intval( $this->avaluo_perdesde_mes );

    	$sql = "Select sam.uf_inm_avaluo_perdesde('" . $this->obj_id . "'," . $perdesde. ")";

    	$reliquida = Yii::$app->db->createCommand( $sql )->queryScalar();
    }


    public function cargarInm($id)
    {
    	$model = Inm::findOne($id);

    	return $model;
    }

    public function buscarInm($cond = "", $orden = "", $cantidad = 40)
    {

    	$sql = 'SELECT obj_id, parp, parporigen, nombre, dompar_dir, nc_guiones, est, est_nom, regimen, ndoc,uf FROM v_inm ';
    	$sql2 = "";
    	$sql3 = "";

    	($cond != '' ? $sql2 = 'WHERE ' . $cond : "");
    	($orden != '' ? $sql3 .= ' ORDER BY ' . $orden : ' ORDER BY obj_id');

    	$arreglo = Yii::$app->db->createCommand($sql.$sql2.$sql3)->queryAll();

    	//$count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM v_inm ' . $sql2)->queryScalar();

    	$dataProvider = new ArrayDataProvider([
		 	'allModels' => $arreglo,
            'key' => 'obj_id',
			'totalCount' => count($arreglo),
			'pagination' =>
				['pageSize' => $cantidad,
				],
            'sort' => [

                'attributes' => [

                    'obj_id',
                    'parp',
                    'parporigen',
                    'nombre',
                    'dompar_dir',
                    'nc_guiones',
                    'est_nom',
                    'regimen',
                    'ndoc',

                ],

                'defaultOrder' => [

                    'obj_id' => SORT_ASC,
                ],
            ],
        ]);

        return $dataProvider;
    }


    /**
     * Función que permite cargar las mejoras de los inmuebles.
     *
     * @param string $id Es el id del inmueble del cuál obtendremos los datos.
     * @return dataProvider Retorna un dataProvide con los datos obtenidos.
     *
     */
    public function CargarMejora($id)
    {
    	//Utilizo una bandera, para indicar si se modifica o no la Mej para Auditoria
        //campo Modif: 'O' - Old y 'N' - New. En un alta de casas se deja 'O'
        $sql = "SELECT m.*, 'O' as Modif From V_inm_Mej m ";
        $sql .= "Where Obj_id='" . $id . "' Order by Pol, perdesde";

        $count = Yii::$app->db->createCommand($sql)->queryScalar();

    	$dataProvider = new SqlDataProvider([
			 	'sql' => $sql,
	            'key' => 'obj_id',
				'totalCount' => (int)$count,
				'pagination' =>
					['pageSize' => 3,
					],
	        ]);

	    return $dataProvider;
    }

    /**
     * Función que busca los datos de OSM de la BD
     *
     * @param string $id Identificador del inmueble que se busca los datos
     *
     * @return dataProvider Devuelve los datos de OSM del inmueble seleccionado
     */
    public function CargarOSM( $id = '' )
    {

		$dataProvider = new ArrayDataProvider( [] );

		if ( $id != '' )
		{
			$sql = "SELECT * FROM v_inm_osm WHERE obj_id = '" . $id . "'";

			$dataProvider = new ArrayDataProvider( [
				 	'allModels' => Yii::$app->db->createCommand( $sql )->queryAll(),
		            'key' => 'obj_id',

					'pagination' =>
						['pageSize' => 3,
						],
		        ]);

		}

	    return $dataProvider;

    }

    /**
     * Función que devuelve un dataProvider con los datos de las calles
     */
    public function obtenerCalles($cond)
    {

    	$loc_id = Yii::$app->db->createCommand("SELECT Loc_id FROM sam.Muni_Datos ")->queryScalar();

    	$sql = "SELECT c.calle_id as calle_id, c.nombre, t.nombre as tipo, u.nombre || ' - ' || to_char(c.fchmod, 'dd/MM/yyyy') as modif ";
    	$sql .= "FROM domi_calle c, domi_tcalle t, sam.sis_usuario u WHERE loc_id = '" . $loc_id . "' and t.cod = c.tcalle and c.usrmod = u.usr_id ";
    	if($cond != '') $sql .= $cond;
    	$sql.= " ORDER BY c.nombre";

    	$count = Yii::$app->db->createCommand($sql)->queryScalar();

    	$dataProvider = new SqlDataProvider([
			 	'sql' => $sql,
	            'key' => 'calle_id',
				'totalCount' => (int)$count,
				'pagination' =>
					['params'=>
						[
						'pageSize' => 12,
						'cond' => $cond,
						'method' => 'POST',
					]
					],
	        ]);

	    return $dataProvider;



    }

    /**
     * Función que devuelve un arreglo con los valores de NC
     */
    public function arregloLabels()
    {

		$sql = 'SELECT campo as cod, nombre FROM sam.config_inm_nc WHERE aplica=true order by orden';

    	$cmd = Yii :: $app->db->createCommand($sql);

		return ArrayHelper :: map( $cmd->queryAll(), 'cod', 'nombre' );

    }

	/**
     * Función que devuelve un arreglo con los valores de NC (Sin mapearlos)
     */
    public function arregloLabelsSinMapeo()
    {

		$sql = 'SELECT campo as cod, nombre FROM sam.config_inm_nc WHERE aplica=true order by orden';

    	return  Yii::$app->db->createCommand( $sql )->queryAll();

    }

    /**
     * Función que devuelve la máxima cantidad de caracteres permitidos para los valores de NC
     */
     public function lengthArregoLabels()
     {
     	$sql = 'SELECT campo as cod, max_largo as length FROM sam.config_inm_nc WHERE aplica=true';

    	$cmd = Yii :: $app->db->createCommand($sql);

		return ArrayHelper :: map($cmd->queryAll(), 'cod', 'length');

     }

     /**
      * Función que devuelve un arreglo indicando si se debe validar el campo
      */
     public function validateArregloLabels()
     {
        $sql = 'SELECT campo as cod, solo_nro::integer as validar FROM sam.config_inm_nc WHERE aplica=true';

        $cmd = Yii :: $app->db->createCommand($sql);

        return ArrayHelper :: map($cmd->queryAll(), 'cod', 'validar' );

     }

    public function listarPersonas ($cond)
    {
    	$sql = 'SELECT obj_id, nombre, est, iva, ndoc, dompos_dir ';
    	$sql .= "FROM v_persona where " . ($cond != '' ? $cond : "nombre LIKE '%sdfsdfsdfa%'") . " ORDER BY obj_id";

    	$count = Yii::$app->db->createCommand("SELECT count(*) FROM v_persona " . ($cond != '' ? "WHERE " . $cond  : ""))->queryScalar();

    	echo $sql;
    	$dataProvider = new SqlDataProvider([
			 	'sql' => $sql,
	            'key' => 'cod',
				'totalCount' => (int)$count,
				'pagination' =>
					['params'=>
						[
						'pageSize' => 8,
						//'cond' => $cond,
						'method' => 'POST',
					]
					],
	        ]);

	    return $dataProvider;


    }

    //------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------FRENTE--------------------------------------------------------------------//
    //------------------------------------------------------------------------------------------------------------------//

    /**
     * Función que obtendrá los frentes de un inmueble dado
     *
     * @param string $id Identificador del inmueble del que se deben buscar los datos
     */
    public function obtenerFrentesDeBD($id)
    {

    	$sql = "SELECT calle_id,calle_nom,medida " .
    			"FROM v_inm_frente " .
    			"WHERE obj_id = '" . $id . "'";

    	$cmd = Yii :: $app->db->createCommand($sql);

    	$res = $cmd->queryAll();

    	$this->arrayFrente = ArrayHelper::map($res, 'calle_id', function($model){ return [
    												'calle_id' => $model['calle_id'],
    												'calle_nom' => $model['calle_nom'],
    												'medida' => $model['medida'],

    										];});
    }

     /**
     * Función que carga los datos de los Inmuebles en un dataProvider
     *
     * @param string $id Identificador del inmueble que se busca
     *
     * @return dataProvider Devuelve los datos del inmueble seleccionado
     *
     */
    public function CargarDomicFrente()
    {

		$arreglo = $this->getArrayFrente() ;

	    $dataProvider = new ArrayDataProvider([
			 	'models' => $arreglo,
	            'key' => 'calle_id',
				'totalCount' => count($arreglo),
				'pagination' =>
					['pageSize' => 3,
					],
	        ]);

	    return $dataProvider;
    }

    public function addArrayFrente($cod, $arreglo)
    {

    	$this->arrayFrente[$cod] = $arreglo;
    }

    public function getArrayFrente()
    {
    	return $this->arrayFrente;
    }

    /**
     * Función que se encarga de guardar en la BD los datos de los frentes.
     */
    public function grabarFrentes()
    {
    	/**
    	 * Para grabar los frentes, primero se deben eliminar los datos de frentes que haya en la BD,
    	 * para luego agregar los datos que se encuentren en memoria.
    	 */

    	 //Eliminar los datos de la BD.
    	 $sqlDelete = "DELETE FROM inm_frente " .
    	 		"WHERE obj_id = '" . $this->obj_id . "'";



    	 /* Insertar los datos en la BD
    	 * 1. Obtener las keys delos arreglos actuales en memoria
    	 * 2. Realizar el INSERT en la BD con esos datos
    	 */

    	 $session = new Session;
    	 $session->open();

    	 $arreglo = $session['arregloFrentes'];
    	 $arrayKey = array_keys($arreglo);

    	try{

    	 	//Ejecuto la sentencia que elimina los datos de la BD.
    	 	Yii :: $app->db->createCommand($sqlDelete)->execute();

			 foreach($arrayKey as $clave)
			 {
		    	 //Sentencia que se ejecuta una vez por cada valor que se ingrese a la BD
		    	 $sqlInsert = "INSERT INTO inm_frente VALUES ('" .
		    	 		$this->obj_id . "'," . $arreglo[$clave]['calle_id'] . ", " . $arreglo[$clave]['medida'] . ", current_timestamp," . Yii::$app->user->id . ")";

		    	Yii :: $app->db->createCommand($sqlInsert)->execute();

			 }

    	 } catch (Exception $e)
    	 {
    	 	$error = 'Ocurrió un error al intentar grabar en la BD.';
    	 	return $error;
    	 }

    	 $session->close();

    	 return "";

    }

    //------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------MEJORAS-------------------------------------------------------------------//
    //------------------------------------------------------------------------------------------------------------------//

    /**
     * Función que obtendrá los item activados para mostrar en mejoras
     *
     * @return array Items activos
     */
    public function arregloMejoras()
    {

		$sql = 'SELECT campo as cod, nombre FROM sam.config_inm_mej';

    	$cmd = Yii :: $app->db->createCommand($sql);

		$arreglo = ArrayHelper :: map($cmd->queryAll(), 'cod', 'nombre');

		return $arreglo;

    }

    /**
     * Función que obtendrá las mejoras de un inmueble dado
     *
     * @param string $id Identificador del inmueble del que se deben buscar los datos
     * @param integer $filtro Identificador que hará que la función devuelva un arreglo modificado
     */
    public function obtenerMejorasDeBD($id)
    {

    	$sql = "SELECT (0 || trim(to_char(pol,'999'))) as poligono, * from v_inm_mej " .
    			"WHERE obj_id = '" . $id . "' order by pol";

    	$cmd = Yii :: $app->db->createCommand($sql);

    	$res = $cmd->queryAll();

    	$arreglo = ArrayHelper::map($res, 'poligono', function($model){ return [
    												 'poligono' => $model['poligono'],
    												 'pol' => $model['pol'],
												     'perdesde' => $model['perdesde'],
												     'perdesde_nom' => $model['perdesde_nom'],
												     'perhasta' => $model['perhasta'],
												     'tori' => $model['tori'],
												     'tori_nom' => $model['tori_nom'],
												     'tform' => $model['tform'],
												     'nivel' => $model['nivel'],
												     'tdest' => $model['tdest'],
												     'tdest_nom' => $model['tdest_nom'],
												     'tobra' => $model['tobra'],
												     'tobra_nom' => $model['tobra_nom'],
												     'anio' => $model['anio'],
												     'est' => $model['est'],
												     'est_nom' => $model['est_nom'],
												     'supcub' => $model['supcub'],
												     'supsemi' => $model['supsemi'],
												     'plantas' => $model['plantas'],
												     'cat' => $model['cat'],
												     'item01' => $model['item01'],
												     'item02' => $model['item02'],
												     'item03' => $model['item03'],
												     'item04' => $model['item04'],
												     'item05' => $model['item05'],
												     'item06' => $model['item06'],
												     'item07' => $model['item07'],
												     'item08' => $model['item08'],
												     'item09' => $model['item09'],
												     'item10' => $model['item10'],
												     'item11' => $model['item11'],
												     'item12' => $model['item12'],
												     'item13' => $model['item13'],
												     'item14' => $model['item14'],
												     'item15' => $model['item15'],
												     'estado' => $model['estado'],
												     'BD' => '1',

    										];});


    		$this->arrayMejoras = $arreglo;
    }

     /**
     * Función que carga los datos de las mejoras de Inmuebles en un dataProvider
     *
     * @return dataProvider Devuelve los datos de mejoras del inmueble seleccionado
     *
     */
    public function CargarMejoras($array = [])
    {
    	if (count($array) == 0)
			$arreglo = $this->getArrayMejoras();
		else
			$arreglo = $array;

	    $dataProvider = new ArrayDataProvider([
			 	'models' => $arreglo,
	            'key' => 'poligono',
				'totalCount' => count($arreglo),
				'pagination' =>
					['pageSize' => 3,
					],
	        ]);

	    return $dataProvider;
    }

    public function addArrayMejoras($cod, $arreglo)
    {
    	$this->arrayMejoras[$cod] = $arreglo;
    }

    public function getArrayMejoras()
    {
    	return $this->arrayMejoras;
    }

    /**
     * Función que se encarga de guardar en la BD los datos de las mejoras
     */
    public function grabarMejoras()
    {
    	/**
    	 * Para grabar las mejoras, primero se deben eliminar los datos de mejora que hayan en la BD,
    	 * para luego agregar los datos que se encuentren en memoria.
    	 */

    	 //Eliminar los datos de la BD.
    	 $sqlDelete = "DELETE FROM inm_mej " .
    	 		"WHERE obj_id = '" . $this->obj_id . "'";



    	 /* Insertar los datos en la BD
    	 * 1. Obtener las keys delos arreglos actuales en memoria
    	 * 2. Realizar el INSERT en la BD con esos datos
    	 */

    	 $session = new Session;
    	 $session->open();

    	 $arreglo = $session['arregloMejoras'];
    	 $arrayKey = array_keys($arreglo);

    	try{

    	 	//Ejecuto la sentencia que elimina los datos de la BD.
    	 	Yii :: $app->db->createCommand($sqlDelete)->execute();

			 foreach($arrayKey as $clave)
			 {
		    	 //Sentencia que se ejecuta una vez por cada valor que se ingrese a la BD
		    	 $sqlInsert = "INSERT INTO inm_mej VALUES ('" .
		    	 		$this->obj_id . "'," . $arreglo[$clave]['pol'] . "," . $arreglo[$clave]['perdesde'] . "," .
		    	 		$arreglo[$clave]['perhasta'] . ",'" . $arreglo[$clave]['tori'] . "','" . $arreglo[$clave]['tform'] . "'," .
		    	 		$arreglo[$clave]['nivel'] . "," . $arreglo[$clave]['tdest'] . "," . $arreglo[$clave]['tobra'] . "," .
		    	 		$arreglo[$clave]['anio'] . "," . $arreglo[$clave]['est'] . "," . $arreglo[$clave]['supcub'] . "," .
		    	 		$arreglo[$clave]['supsemi'] . "," . $arreglo[$clave]['plantas'] . ",'" . $arreglo[$clave]['cat'] . "'," .
		    	 		$arreglo[$clave]['item01'] . "," . $arreglo[$clave]['item02'] . "," . $arreglo[$clave]['item03'] . "," .
		    	 		$arreglo[$clave]['item04'] . "," . $arreglo[$clave]['item05'] . "," . $arreglo[$clave]['item06'] . "," .
		    	 		$arreglo[$clave]['item07'] . "," . $arreglo[$clave]['item08'] . "," . $arreglo[$clave]['item09'] . "," .
		    	 		$arreglo[$clave]['item10'] . "," . $arreglo[$clave]['item11'] . "," . $arreglo[$clave]['item12'] . "," .
		    	 		$arreglo[$clave]['item13'] . "," . $arreglo[$clave]['item14'] . "," . $arreglo[$clave]['item15'] . ",'" .
		    	 		$arreglo[$clave]['estado'] . "', current_timestamp," . Yii::$app->user->id . ")";

		    	Yii :: $app->db->createCommand($sqlInsert)->execute();


			 }

    	 } catch (Exception $e)
    	 {
    	 	$error = 'Ocurrió un error al intentar grabar en la BD.';
    	 	return $error;
    	 }

	    return "";

    	 $session->close();

    }


    //------------------------------------------------------------------------------------------------------------------//
    //----------------------------------------RESTRICCIONES-------------------------------------------------------------//
    //------------------------------------------------------------------------------------------------------------------//

    public function obtenerRestricciones($id)
    {
    	$sql = "SELECT * FROM v_inm_restric WHERE obj_id = '" . $id . "'";

    	$count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM v_inm_restric WHERE obj_id = '" . $id . "'")->queryScalar();

    	$dataProvider = new SqlDataProvider([
		 	'sql' => $sql,
            'key' => 'obj_id',
			'totalCount' => (int)$count,
			'pagination' =>
				['pageSize' => 5,
				],
        ]);

        return $dataProvider;

    }

    /**
     * Función que permite guardar una nueva restricción en la BD
     */
    public function NuevaRestriccion($id, $tipo, $sup, $inscrip, $detalle)
    {
    	$supt =  Yii::$app->db->createCommand("Select supt from inm where obj_id='" . $id . "'")->queryScalar();
    	if ($sup > $supt) return "La superficie no puede ser mayor a la superficie de terreno";

    	$orden = Yii::$app->db->createCommand("Select coalesce(max(Orden),0)+1 from inm_restric where obj_id='" . $id . "'")->queryScalar();

    	$sql = "INSERT INTO inm_restric VALUES ('".$id."',".$orden."," . $tipo . ",'".Html::encode($sup)."','".Html::encode($inscrip)."','".Html::encode($detalle)."',";
    	$sql .= "current_timestamp,".Yii::$app->user->id.")";

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";
		} else {
			return "Ocurrió un error al intentar grabar en la BD.";
		}
    }

    /**
     *
     */
    public function ModificarRestriccion($id, $orden, $tipo, $sup, $inscrip, $detalle)
    {
    	$supt =  Yii::$app->db->createCommand("Select supt from inm where obj_id='" . $id . "'")->queryScalar();
    	if ($sup > $supt) return "La superficie no puede ser mayor a la superficie de terreno";

    	$sql = "Update inm_restric Set sup='".Html::encode($sup)."',inscrip='".Html::encode($inscrip)."',trestric=" . $tipo . ",obs='".Html::encode($detalle)."',";
    	$sql .= "fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
    	$sql .= " where obj_id='".$id."' and orden=".$orden." and usrmod=".Yii::$app->user->id;

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";
		} else {
			return "Ocurrió un error al intentar grabar en la BD.";
		}
    }

    public function EliminarRestriccion($id,$orden)
    {
    	$sql = "DELETE FROM inm_restric WHERE obj_id = '" . $id ."' and orden = " . $orden;

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";
		} else {
			return "Ocurrió un error al intentar grabar en la BD.";
		}
    }

    public function Imprimir($id,&$sub1,&$sub2,&$sub3,&$sub4)
    {
    	$sql = "Select * From v_inm Where obj_id='".$id."'";
   		$array = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "Select * From v_objeto_tit where est='A' and obj_id='".$id."'";
   		$sub1 = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "SELECT (0 || trim(to_char(pol,'999'))) as poligono, * from v_inm_mej " .
    			"WHERE obj_id = '" . $id . "' order by pol";
   		$sub2 = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "Select f.Obj_id, f.calle_id, c.Nombre, f.Medida From inm_Frente f inner join domi_Calle c On f.calle_id=c.calle_id where obj_id='".$id."'";
   		$sub3 = Yii::$app->db->createCommand($sql)->queryAll();

   		$sql = "Select * From v_inm_osm where obj_id='".$id."'";
   		$sub4 = Yii::$app->db->createCommand($sql)->queryAll();

   		return $array;
    }

	public function obtenerPHMadre(){

		$sql = "select obj_id from v_inm where nc= '$this->nc' and obj_id <> '$this->obj_id' and est='M' ";
		$ph_madre =  Yii::$app->db->createCommand( $sql )->queryScalar();

		return $ph_madre;

	}

	public function getExisteInmMejTCat(){

		$sql = "select count(*) from inm_mej_tcat";
		$cant =  Yii::$app->db->createCommand( $sql )->queryScalar();

		return ($cant > 0) ;
	}

}
