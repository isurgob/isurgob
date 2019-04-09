<?php

namespace app\models\objeto;

use Yii;
use yii\validators\EmailValidator;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use app\utils\db\utb;
use app\utils\helpers\DBException;
use app\models\objeto\ComerRubro;

use app\utils\db\Fecha;

/**
 * This is the model class for table "persona".
 *
 * @property string $obj_id
 * @property integer $inscrip
 * @property integer $tipo
 * @property integer $tdoc
 * @property string $ndoc
 * @property string $fchnac
 * @property string $sexo
 * @property integer $nacionalidad
 * @property integer $estcivil
 * @property integer $clasif
 * @property integer $iva
 * @property string $cuit
 * @property string $ag_rete
 * @property string $tel
 * @property string $cel
 * @property string $mail
 * @property string $exis_doc
 * @property string $exis_insc
 * @property string $exis_foto
 *
 * Datos correspondientesa "Ingresos Brutos"
 * @property string $est_ib
 * @property string $ib
 * @property integer $orgjuri
 * @property string $tipoliq
 * @property integer $contador
 * @property integer $contador_verdeuda
 * @property string fchalta_ib
 * @property stirng fchbaja_ib
 */
class Persona extends \yii\db\ActiveRecord
{
    public $domi_legal;
    public $domi_res;

    public $sit_iva_nom;
    public $tipoliq_nom;
    public $orgjuri_nom;
    public $contador_nom;
    public $est_ib_nom;
	public $tbaja_ib_nom;

    //Variables para baja
    public $motivoBaja;
    public $eliminarConDeuda;

    //Arreglo con objetos de tipo Rubro con los datos de los rubros a guardar
	public $rubros;

    //Variable que indicará si se requiere que se ingresen rubros para el alta de IB.
    public $requiereRubros = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'persona';
    }

    public function __construct(){

        $this->rubros   = [];

        $this->fchalta_ib  = date( 'd/m/Y' );

        $this->requiereRubros = $this->verificarSiRequiereRubros();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $ret = [
            [['tipo', 'sexo', 'iva', 'clasif'], 'required', 'on' => [ 'persona' ] ],
            [['inscrip', 'tipo', 'tdoc', 'ndoc', 'nacionalidad', 'estcivil', 'clasif', 'iva','exis_doc','exis_insc','exis_foto','ag_rete_manual'], 'integer', 'on' => [ 'persona' ] ],
            [['fchnac'], 'safe', 'on' => [ 'persona' ] ],
            [['obj_id'], 'string', 'max' => 8, 'on' => [ 'persona' ] ],
            [['sexo'], 'string', 'max' => 1, 'on' => [ 'persona' ] ],
            [['tel', 'cel'], 'string', 'max' => 15, 'on' => [ 'persona' ] ],
            [['cuit'], 'string', 'max' => 13, 'on' => [ 'persona' ] ],
            [['ag_rete'], 'string', 'max' => 7, 'on' => [ 'persona' ] ],
            [['mail'], 'string', 'max' => 40, 'on' => [ 'persona' ] ],
            [['domi_legal','domi_res', 'tipoliq' ], 'string', 'on' => [ 'persona' ] ]
        ];

        $ret[] = [
            [ 'obj_id', 'motivoBaja', 'eliminarConDeuda', 'obs' ],
            'required',
            'on' => [ 'delete' ],
        ];

        $ret[] = [
            [ 'est_ib' ],
            'string',
            'max'   => 1,
            'on'    => [ 'insertIB', 'updateIB' ],
        ];

        $ret[] = [
            [ 'tipoliq' ],
            'string',
            'max'   => 2,
            'on'    => [ 'insertIB', 'updateIB' ],
        ];

        $ret[] = [
            [ 'ib' ],
            'string',
            'min'   => 1,
            'max'   => 11,
            'on'    => ['insertIB', 'updateIB'],
        ];

        $ret[] = [
            [ 'fchalta_ib', 'fchbaja_ib' ],
            'string',
            'on'    => [ 'insertIB', 'updateIB' ],
        ];

        $ret[] = [
            [ 'orgjuri', 'contador', 'contador_verdeuda' ],
            'integer',
            'on' => [ 'insertIB', 'updateIB' ],
        ];

		$ret[] = [
            [ 'nombre_fantasia' ],
            'string',
            'max'   => 50,
            'on'    => [ 'insertIB', 'updateIB' ],
        ];

		$ret[] = [
            [ 'tbaja_ib' ],
            'string',
            'max'   => 2,
            'on'    => [ 'insertIB', 'updateIB', 'deleteIB' ],
        ];

        /**
         * Valores requeridos
         */
         $ret[] = [
             [ 'obj_id' ],
             'required',
             'on'    => [ 'insertIB', 'updateIB', 'deleteIB', 'activarIB' ],
             'message' => 'No se encontraron datos anteriores.',
         ];

		  $ret[] = [
             [ 'fchbaja_ib', 'tbaja_ib' ],
             'required',
             'on'    => [ 'deleteIB' ],
             'message' => 'Ingrese una {attribute}',
         ];

        $ret[] = [
            [ 'ib' ],
            'required',
            'on'    => [ 'updateIB' ],
            'message' => 'Ingrese un {attribute}',
        ];

        $ret[] = [
            [ 'orgjuri', 'fchalta_ib' ],
            'required',
            'on'    => [ 'insertIB', 'updateIB' ],
            'message' => 'Ingrese una {attribute}',
        ];

		$ret[] = [
            [ 'fchalta_ib' ],
            'required',
            'on'    => [ 'activarIB' ],
            'message' => 'Ingrese una {attribute}',
        ];

        $ret[] = [
            [ 'tipoliq', 'contador' ],
            'required',
            'on'    => [ 'insertIB', 'updateIB' ],
            'message' => 'Ingrese un {attribute}',
        ];

        /**
         * Valores por defecto
         */
        $ret[] = [
            [ 'nacionalidad' ],
            'default',
            'value' => 0,
            'on' => [ 'persona' ],
        ];

        /**
		 * VALIDACIONES ESPECÍFICAS
		 */

        $ret[] = [
            'ib',
            'validateIB',
            'skipOnEmpty' => false,
            'skipOnError' => false,
			'on' => [ 'insertIB' ],
        ];

        $ret[] = [
            'tipoliq',
            'validateTipoLiquidacion',
            'skipOnEmpty' => false,
            'skipOnError' => false,
            'on' => [ 'insertIB', 'updateIB' ],
        ];

		//Se debe tener como mínimo un rubro
		$ret[] = [
			'rubros',
            'validateRubros',
            'skipOnEmpty' => false,
            'skipOnError' => false,
			'on' => [ 'insertIB', 'updateIB' ],
		];

        return $ret;
    }

    public function scenarios(){

        return [
            'persona'   => [
                'tipo', 'sexo', 'iva', 'clasif', 'inscrip', 'tipo', 'tdoc', 'ndoc', 'nacionalidad', 'estcivil',
                'clasif', 'iva','exis_doc','exis_insc','exis_foto','fchnac', 'obj_id', 'sexo', 'tel', 'cel',
                'cuit', 'ag_rete', 'mail', 'domi_legal','domi_res', 'tipoliq','ag_rete_manual'
            ],
            'delete' => [ 'obj_id', 'motivoBaja', 'eliminarConDeuda', 'obs' ],

            'insertIB'  => [ 'obj_id', 'ib', 'est_ib', 'insertIB', 'orgjuri', 'tipoliq', 'contador', 'contador_verdeuda', 'fchalta_ib', 'fchbaja_ib', 'rubros', 'nombre_fantasia', 'tbaja_ib' ],
            'updateIB'  => [ 'obj_id', 'ib', 'est_ib', 'insertIB', 'orgjuri', 'tipoliq', 'contador', 'contador_verdeuda', 'fchalta_ib', 'fchbaja_ib', 'rubros', 'nombre_fantasia', 'tbaja_ib' ],
            'deleteIB'  => [ 'obj_id', 'fchbaja_ib', 'tbaja_ib' ],
			'activarIB'  => [ 'obj_id', 'fchalta_ib' ],
        ];

    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'obj_id' => 'Objeto',
            'inscrip' => 'Nº Inscrip.:',
            'tipo' => 'Tipo:',
            'tdoc' => 'Tipo Doc.:',
            'ndoc' => 'Nº Doc.:',
            'fchnac' => 'Nacimiento:',
            'sexo' => 'Sexo:',
            'nacionalidad' => 'Nacionalidad:',
            'estcivil' => 'Estado Civil:',
            'clasif' => 'Clasificación:',
            'iva' => 'Situac. IVA:',
            'cuit' => 'CUIT / CUIL:',
            'ag_rete' => 'Ag.Retenc:',
            'tel' => 'Telefono:',
            'cel' => 'Celular:',
            'mail' => 'e-mail:',
            'domi_legal' => 'Domicilio Legal:',
            'domi_res' => 'Domicilio Residencial:',

            // Labels para IB

            'ib'            => 'número de ingresos brutos',
            'orgjuri'       => 'organización jurídica',
            'tipoliq'       => 'tipo de liquidación',
            'contador'      => 'contador',
            'fchalta_ib'    => 'fecha de alta',
            'nacionalidad'  => 'nacionalidad',
			'nombre_fantasia'  => 'Nombre de Fantasía',
			'tbaja_ib'  => 'Tipo de Baja IB',
			'ag_rete_manual' => 'Permitir Retención Manual'

        ];
    }

    /**
     * INICIO Validaciones específicas para el modelo
     */
    public function validateIB( $attribute, $params ){

        /**
         * Según el parámetro ib_modo en la configuración del sistema:
         *      'A' -> Automático: el número de ingresos brutos lo establece el sistema.
         *      'C' -> CUIT: el número de ingresos brutos es igual al número de CUIT ( Debe existir el CUIT ).
         *      'M' -> Manual: el número de ingresos brutos lo ingresa el usuario ( validar que no se repita ).
         */
        $config = utb::getCampo( 'sam.config', '', 'ib_modo' );

        switch( $config ){

            case 'A':   //Automático

                $this->$attribute = $this->obtenerSiguienteIdIB();

                break;

            case 'M':   //Manual

                //Validar que se haya ingresado algún valor para IB
                if( $this->$attribute == '' ){

                    $this->addError( $attribute, "Debe ingresr un número de Ingresos Brutos." );

                } else { //Validar que no se repita

                    $sql = "SELECT EXISTS( SELECT 1 FROM persona WHERE ib = '" . $this->$attribute . "')";

                    if( Yii::$app->db->createCommand( $sql )->queryScalar() ){
                        $this->addError( $attribute, "El número de Ingresos Brutos ingresado ya existe.");
                    }

                }

                break;
        }

    }

    /**
     * Función que se utiliza para validar que al realizar la inscripción a IIBB,
     * el contribuyente se encuentre con liquidación "Local".
     */
    public function validateTipoLiquidacion( $attribute, $params ){

        if( $this->$attribute != 'LO' and $this->$attribute != 'CM' and $this->$attribute != 'AI' and $this->$attribute != 'MO'){

            $this->addError( $attribute, 'El tipo de liquidación es incorrecta.' );
        }
    }

    /**
     * Función que se utiliza para validar que se haya ingresado algún rubro.
     */
    public function validateRubros( $attribute, $params ){

        /**
         * Se debe validar el ingreso de rubros en caso de que el tipo de IB sea "Local" y si existen rubros que se deben declarar.
         *
         */

        if( $this->tipoliq == 'LO' && $this->requiereRubros ){

    		$periodoActual = intval( date( 'Y' ) . str_pad( date('m'), 3, '0', STR_PAD_LEFT ) );

            if( $this->verificarExistenciaDeRubro( $attribute ) ){

                $hayPrincipal = false;

                foreach( $this->$attribute as $array ){

                    if( $array[ 'tipo' ] == 1 && $array['est'] == 'A' ){

                        $hayPrincipal = true;

                        //Validar que el rubro principal tenga vigencia en el período actual en caso de que la fecha de alta sea anterior al día actual
                        if( Fecha::menor( Fecha::usuarioToBD( $this->fchalta_ib ), date( 'Y/m/d' ) ) ){

                            if( $array[ 'nperdesde' ] > $periodoActual || ( $array[ 'nperdesde' ] < $periodoActual && $array[ 'nperhasta' ] < $periodoActual ) ){

                                $this->addError( 'rubro_id', 'El rubro principal no tiene vigencia en el período actual.' );

                            }

                        }
                    }
                }

                if( !$hayPrincipal ){
                    $this->addError( $attribute, "Debe ingresar al menos un rubro principal.");
                }
            }

        }

    }

    /**
     * FIN Validaciones específicas para el modelo
     */

    /**
     * Función que se utiliza para validar que una persona esté inscripta a IB.
     * @param string $id Identificador de objeto.
     */
    public function verificarInscripcionIB(){

        return $this->est_ib != 'N';
    }

    private function verificarExistenciaDeRubro( $attribute ){

        $hayRubro = false;

        if( count( $this->$attribute ) == 0 ){

            $this->addError( $attribute, 'Debe ingresar al menos un rubro.' );

            return false;

        } else {

            foreach( $this->$attribute as $array ){

                if( $array['est'] == 'A' ){

                    $hayRubro = true;
                }
            }
        }

        if( $hayRubro ){

            return true;

        } else {

            $this->addError( $attribute, 'Debe ingresar al menos un rubro activo.' );
        }

        return false;
    }

    public function afterFind(){

        $this->sit_iva_nom  = utb::getCampo( 'comer_tiva', 'cod = ' . $this->iva, 'nombre' );
        $this->tipoliq_nom  = utb::getCampo( 'comer_tliq', "cod = '" . $this->tipoliq . "'", 'nombre');
        $this->orgjuri_nom  = utb::getCampo( 'comer_torgjuri', 'cod = ' . intVal( $this->orgjuri ), 'nombre' );
        $this->contador_nom = utb::getCampo( 'objeto', "obj_id IN ( SELECT obj_id FROM sam.usuarioweb WHERE usr_id = $this->contador)", 'nombre');
        $this->est_ib_nom   = utb::getCampo( 'persona_test_ib', "cod = '$this->est_ib'", 'nombre' );
		$this->tbaja_ib_nom = utb::getCampo( 'persona_tbajaib', "cod = '$this->tbaja_ib'", 'nombre' );

        $this->fchalta_ib   = Fecha::bdToUsuario( $this->fchalta_ib );
        $this->fchbaja_ib   = Fecha::bdToUsuario( $this->fchbaja_ib );

        $this->rubros       = ComerRubro::getRubros( $this->obj_id );
    }

    public static function getOrganizacionesJuridicas(){

        return utb::getAux( 'comer_torgjuri', 'cod', 'nombre' );
    }

    public static function getTipoLiquidaciones(){

        return utb::getAux( 'comer_tliq', 'cod', 'nombre' );
    }

	public static function getTipoBajaIB(){

        return utb::getAux( 'persona_tbajaib', 'cod', 'nombre', 1 );
    }

    public function realizaDDJJ(){

        $sql = "SELECT EXISTS( SELECT 1 FROM trib WHERE tipo = 2 AND est = 'A' AND tobj = 3 )";

        return Yii::$app->db->createCommand( $sql )->queryScalar();
    }

    /**
	 * Funci�n que valida los datos antes de guardar datos en la BD
	 *
	 * @return string error Valor con los errores ocurridos al validar. Devuelve cadena vacia ("") en caso de no haber errores.
	 */
	public function validar()
	{
		if ($this->tdoc=="") $this->tdoc=0;
		if ($this->estcivil=="") $this->estcivil=0;
        if ($this->nacionalidad=="") $this->nacionalidad=0;

		$error = "";

		if ($this->mail !== '')
		{
			$validator = new EmailValidator();

			if (!($validator->validate($this->mail, $err)))
			{
				$error .= "<li>".$err."</li>";
			}
		}
		if ((integer)$this->tipo == 1)
		{
			if (utb::samConfig(0)['per_pedir_doc']){
				if ($this->tdoc==0)
				{
					$error .= "<li>Elija el Tipo de Documento</li>";
				}
			}
			if ( $this->nacionalidad == '' || $this->nacionalidad == 0 )
			{
				$error .= "<li>Elija la Nacionalidad de la Persona</li>";
			}
		}
		if (utb::samConfig(0)['per_pedir_cuit']){
			if ($this->cuit==0 or $this->cuit=='')
			{
				$error .= "<li>Ingrese el CUIT o CUIL de la Persona</li>";
			}
		}

		$this->fchnac= Fecha::usuarioToBD($this->fchnac);

		if ($this->fchnac !='' && (!preg_match("/([0-9]{4})\/([0-9]{2})\/([0-9]{2})/", $this->fchnac) || Fecha::esFuturo($this->fchnac)))
		{
			$error .= "<li>Fecha de nacimiento incorrecta</li>";
		}
		if ( $this->ndoc > 0)
		{
			$sql = "Select count(*) From Persona Where TDoc=".$this->tdoc." and NDoc=". $this->ndoc.($this->isNewRecord ? "" : " and obj_id<>'".$this->obj_id."'");

			$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();

			if ($cantidad > 0)
			{
				$error .= "<li>Ya existe una Persona con ese tipo y número de Documento</li>";
			}else {

				$sql = "Select count(*) From Persona Where NDoc=". $this->ndoc.($this->isNewRecord ? "" : " and obj_id<>'".$this->obj_id."'");
				$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();

				if ($cantidad > 0)
				{
					$error .= "<li>Ya existe una Persona con ese número de Documento</li>";
				}
			}

		} elseif (utb::samConfig(0)['per_pedir_doc']){
			$error .= "<li>El número de documento no es valido</li>";
		}

		if ($this->iva == 2 and $this->cuit == '')
		{
			$error .= "<li>Si es Responsable Inscripto debe ingresar un número de CUIT</li>";
		}
		if ($this->cuit !== '')
		{
			$sql = "Select count(*) From Persona Where cuit='". $this->cuit.($this->isNewRecord ? "'" : "' and obj_id<>'".$this->obj_id."'");
			$cantidad = Yii :: $app->db->createCommand($sql)->queryScalar();

			if ($cantidad > 0)
			{
				$error .= "<li>Ya existe una Persona con ese número de CUIT/CUIL</li>";
			}
			if (utb::ValidarCUIT($this->cuit) == 0)
			{
				$error .= "<li>Ingrese un número de CUIT/CUIL válido</li>";
			}

		}

		if($this->tipo <= 0)
			$error .= "<li>Elija un tipo</li>";
		else {

			$sql= "Select Exists (Select 1 From persona_tipo Where cod = $this->tipo)";
			$cantidad= Yii::$app->db->createCommand($sql)->queryScalar();

			if($cantidad <= 0) $error .= "El tipo no existe";
		}

		if($this->inscrip < 0)
			$error .= "<li>El número de inscripción no es valido</li>";

        # Validar que el código de agente de retención no se duplique
        if( $this->ag_rete != '' ){

            $sql =  "SELECT EXISTS( SELECT 1 FROM persona WHERE ag_rete = '$this->ag_rete' AND obj_id <> '$this->obj_id' )::integer";

            $res = Yii::$app->db->createCommand( $sql )->queryScalar();

            if( $res ){
                $error .= "<li>El código de agente de retención ya se encuentra ingresado.</li>";
            }
        }
		return $error;

	}

	 /**
	 * Funcion que crea o modifica una persona en la base de datos.
	 * @return string Valor con los errores ocurridos. "" en caso de no haber ocurrido ning�n error.
	 */
	public function grabar()
	{
		if ($this->inscrip=="") $this->inscrip=0;
		if ($this->ndoc=="") $this->ndoc=0;
		if($this->clasif == "") $this->clasif= 0;
		if($this->iva == "") $this->iva= 0;

		//Si es un nuevo registro
		if ($this->isNewRecord)
		{
			$sql =  "insert into persona ( obj_id, inscrip, tipo, tdoc, ndoc, fchnac, sexo, nacionalidad, estcivil, clasif, iva, " .
                    "cuit, ag_rete,ag_rete_manual, tel, cel, mail ) values ('$this->obj_id',$this->inscrip,$this->tipo,$this->tdoc,$this->ndoc, " .
                    ($this->fchnac=='' ? "null": "'".$this->fchnac."'").",'$this->sexo',$this->nacionalidad,$this->estcivil, $this->clasif ".
                    ",$this->iva,'$this->cuit','$this->ag_rete',$this->ag_rete_manual,'$this->tel','$this->cel','$this->mail')";

			$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

			if ($rowCount > 0)
			{
				return "";
			} else {
					return "Ocurrió un error al intentar grabar en la BD.";
			}
		} else {

			$transaction = Yii::$app->db->beginTransaction();
			try{
				$sql = "update Persona set ";
				$sql .= "inscrip=".$this->inscrip.",tipo=".$this->tipo.",tdoc=".$this->tdoc.",tipoliq = '$this->tipoliq'";
				$sql .= ",ndoc=".$this->ndoc.",fchnac=".($this->fchnac=='' ? "null": "'".$this->fchnac."'").",sexo='".$this->sexo;
				$sql .= "',nacionalidad=".$this->nacionalidad.",EstCivil=".$this->estcivil.",Clasif=".$this->clasif;
				$sql .= ",iva=".$this->iva;
				$sql .= ",cuit='".$this->cuit."',ag_rete='".$this->ag_rete."',ag_rete_manual=$this->ag_rete_manual,tel='".$this->tel."',cel='".$this->cel;
				$sql .= "',mail='".$this->mail."' where obj_id='".$this->obj_id."'";

				Yii::$app->db->createCommand( $sql )->execute();


				// actualizo el tipo de liquidación del proveedor
				// si es local solo modifico en proveedor si es distinto a BE (beneficiario)
				// sino es la misma para ambos
				$sql = "update fin.proveedor set tliq='$this->tipoliq' where obj_id='$this->obj_id' " . ( $this->tipoliq == 'LO' ? " and tliq<>'BE'" : "" );
				Yii::$app->db->createCommand( $sql )->execute();

			} catch (\Exception $e ){

				$transaction->rollback();

				return $e->getMessage();

			}

			$transaction->commit();
			return "";
		}
	}

	public static function BuscarPersonaAv($cond, $order = '', $cantidad = 40)
    {
        $count = $count2 = 0;
        $sql2 = '';
        $sql = "Select count(*) From V_Persona";
        if ($cond !== ""){
        	$sql = $sql.' where '.$cond;
        }

        $count = Yii::$app->db->createCommand($sql)->queryScalar();

        $sql = "Select * , to_char(fchalta, 'dd/mm/yyyy')," .
        		" CASE WHEN cuit IS NULL OR trim(both ' ' from cuit) = '' THEN ndoc::text" .
        		" else (substr(cuit, 1, 2) || '-' || substr(cuit, 3, 8) || '-' || substr(cuit, 11, 1))" .
        		" END AS documento FROM v_persona";

//        $sql = "Select *,to_char(fchalta,'dd/mm/yyyy') as fchalta from V_Persona ";
        if ($cond !== "") $sql = $sql.' where '.$cond;


        if ($order !== "") $sql .= " order by ".$order;

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'obj_id',
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>$cantidad,

			],
        ]);

        return $dataProvider;
    }

   public function PersonaReemplaza($objeto_origen, $objeto_destino)
    {
    	$sql = "Select sam.uf_persona_reemplaza('".$objeto_origen."','".$objeto_destino."',".Yii::$app->user->id.")";

		$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0) {
			return "";
		} else {
			return "Ocurrio un error al intentar actualizar los datos en la BD.";
		}
    }

   public function PersonaReemplazaLis($num = '',$nombre = '',$ndoc = 0,$ncuit = '')
   {
   		$sql = "select count(*) from sam.uf_persona_reemplaza_ver('$num', '$nombre', $ndoc, '$ncuit')";
   		$count = Yii::$app->db->createCommand($sql)->queryScalar();
   		$sql = "select * from sam.uf_persona_reemplaza_ver('$num', '$nombre', $ndoc, '$ncuit')";

   		$dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>20,
			],
        ]);

        return $dataProvider;
   }

   public function PersonaReemplazaAnula($oldnum)
   {
   		try {
    		$sql = "select * from sam.uf_persona_reemplaza_anula('".$oldnum."',".Yii::$app->user->id.")";
    		$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();
    	} catch(\Exception $e) {

    		$error = DBException::getMensaje($e);
    		return $error;
    	}

    	return "";
   }

   public function Imprimir($id)
   {
   		$sql = "Select *,to_char(fchalta,'dd/mm/yyyy') fchalta,to_char(fchbaja,'dd/mm/yyyy') fchbaja,to_char(fchmod,'dd/mm/yyyy') fchmod,to_char(fchalta_ib,'dd/mm/yyyy') fchalta_ib,
            (substr(cuit, 1, 2) || '-' || substr(cuit, 3, 8) || '-' || substr(cuit, 11, 1)) cuit From V_Persona where obj_id='".$id."'";

   		$array = Yii::$app->db->createCommand($sql)->queryAll();
   		return $array;
   }

   public function GrabarAjustes($id)
   {
		$sql = "update persona_ajuste set est='A' where aju_id=" . $id;
		$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount <= 0)
			return "Error al grabar ajustes.";
		else
			return "";
   }

	public function CargarDocumento($id)
	{
		$nombreArchivoFoto = "images/persona/" . $id . '_Foto.png';
		$nombreArchivoDoc = "images/persona/" . $id . '_Documento.png';
		$nombreArchivoMonoT = "images/persona/" . $id . '_Monotributo.png';

		$doc = [
			'id' => $id,
			'nombre' => utb::getCampo('objeto',"obj_id='" . $id . "'","nombre"),
			'foto' => file_exists($nombreArchivoFoto) ? $nombreArchivoFoto : '',
			'documento' => file_exists($nombreArchivoDoc) ? $nombreArchivoDoc : '',
			'monotributo' => file_exists($nombreArchivoMonoT) ? $nombreArchivoMonoT : ''
		];

		return $doc;
	}

	public function GuardarDocumento($id,$imgfoto,$imgdoc,$imgmonot)
	{
		try {
    		if ($imgfoto != null){
				//guardo la imagen en el servidor
				$nombreArchivoFoto = "images/persona/" . $id . '_Foto.png';
				if(file_exists($nombreArchivoFoto)) unlink($nombreArchivoFoto);
				$imgfoto->saveAs($nombreArchivoFoto, false);

				$sql = "update persona set exis_foto=1 where obj_id='" . $id ."'";
				Yii::$app->db->createCommand($sql)->execute();
			}
			if ($imgdoc != null){
				//guardo la imagen en el servidor
				$nombreArchivoDoc = "images/persona/" . $id . '_Documento.png';
				if(file_exists($nombreArchivoDoc)) unlink($nombreArchivoDoc);
				$imgdoc->saveAs($nombreArchivoDoc, false);

				$sql = "update persona set exis_doc=1 where obj_id='" . $id ."'";
				Yii::$app->db->createCommand($sql)->execute();
			}
			if ($imgmonot != null){
				//guardo la imagen en el servidor
				$nombreArchivoMonoT = "images/persona/" . $id . '_Monotributo.png';
				if(file_exists($nombreArchivoMonoT)) unlink($nombreArchivoMonoT);
				$imgmonot->saveAs($nombreArchivoMonoT, false);

				$sql = "update persona set exis_insc=1 where obj_id='" . $id ."'";
				Yii::$app->db->createCommand($sql)->execute();
			}
    	} catch(\Exception $e) {

    		$error = DBException::getMensaje($e);
    		return $error;
    	}

    	return "";
	}

    /**
     * Función que se utiliza para obtener el siguiente ID de "Ingresos Brutos".
     */
    private function obtenerSiguienteIdIB(){

        //$sql = "SELECT coalesce( MAX( ib )::integer, 0 ) + 1 FROM persona";
		$sql = "SELECT nextval('seq_persona_ib')";

        return Yii::$app->db->createCommand( $sql )->queryScalar();
    }

    /**
     * Función que se utiliza para realizar el ABM de "Ingresos Brutos".
     * @param integer $action Identificador del tipo de acción.
     */
    public function grabarIB( $action ){

        switch( $action ){

            case 0:

                $this->setScenario( 'insertIB' );

                if(!$this->validate())
            		return false;

                break;

            case 2:

                $this->setScenario( 'deleteIB' );

				if(!$this->validate())
            		return false;

                break;

            case 3:

                $this->setScenario( 'updateIB' );

                if(!$this->validate())
            		return false;

                break;
        }

        $hayError = false;

    	$transaction = Yii::$app->db->beginTransaction();

        try{

        	//se graban los rubros
        	if( !$this->grabarRubros( $this->obj_id ) ){
                $hayError = true;
            }

        	if( $hayError ){
        		$transaction->rollBack();
        		return false;
    		}

            switch( $action ){

                case 0:
                case 3:

                    //Se inserta o modifica la persona a "Ingresos Brutos"
                    $sql =  "UPDATE persona SET est_ib = 'A', ib = '$this->ib', orgjuri = $this->orgjuri, tipoliq = '$this->tipoliq', contador = $this->contador," .
                            "contador_verdeuda = $this->contador_verdeuda, fchalta_ib = " . Fecha::usuarioToBD( $this->fchalta_ib, 1 ) . "," .
							"nombre_fantasia='" . $this->nombre_fantasia . "',tbaja_ib='" . $this->tbaja_ib . "'," .
                            " fchbaja_ib = " . Fecha::usuarioToBD( $this->fchbaja_ib, 1 ) . " WHERE obj_id = '$this->obj_id'";

                    $res = Yii::$app->db->createCommand( $sql )->execute() > 0;

                    if(!$res){
                        $transaction->rollBack();
                        return false;
                    }

                    break;

                case 2:

                    $sql =  "UPDATE persona SET est_ib = 'B',tbaja_ib='$this->tbaja_ib', fchbaja_ib = " . Fecha::usuarioToBD( $this->fchbaja_ib, 1 ) . " WHERE obj_id = '$this->obj_id'";

                    $res = Yii::$app->db->createCommand($sql)->execute() > 0;

                    if(!$res){
                        $transaction->rollBack();
                        return false;
                    }

            }

        } catch (\Exception $e){
            $transaction->rollBack();
            $this->addError( 'ib', 'Ocurrió un error al grabar los datos.' );
            return false;
        }

    	$transaction->commit();
    	return true;

    }

    /**
     * Función que se utiliza para activar una inscripción a ingresos brutos dada de baja o exenta.
     * @param string $id Identificador de objeto.
     */
    public function activarInscripcion(){

        if(!$this->validate())
            return false;

		$sql =  "UPDATE persona SET fchbaja_ib = null,tbaja_ib='', est_ib = 'A', fchalta_ib='$this->fchalta_ib' WHERE obj_id = '$this->obj_id'";

        return Yii::$app->db->createCommand( $sql )->execute();

    }

    /**
     * Función que se utiliza para poner exenta una inscripción a ingresos brutos.
     * @param string $id Identificador de objeto.
     */
    public function exento( $id ){

        $sql =  "UPDATE persona SET est_ib = 'E' WHERE obj_id = '$id'";

        return Yii::$app->db->createCommand( $sql )->execute();

    }

    /**
     * Graba los rubros de la persona
     * @param string $obj_id - Código de la persona
     * @return boolean - true si se han grabado los rubros correctamente, false de lo contrario
     */
    public function grabarRubros( $obj_id ){

        //se borran todos los rubros relacionados al objeto
        $sql = "DELETE FROM objeto_rubro WHERE obj_id = '$obj_id'";

        Yii::$app->db->createCommand($sql)->execute();

        foreach( $this->rubros as $rubro ){

            $rubro->subcta = 0;

            if( !$rubro->grabar( $obj_id ) ){
                return false;
            }

        }

        return true;
    }

    /**
     * Función que se utiliza para verificar si se deben ingresar rubros en DDJJ.
     */
    public function verificarSiRequiereRubros(){

        $sql = "SELECT EXISTS ( select 1 from trib WHERE est = 'A' AND tipo = 2 AND trib_id IN ( select trib_id FROM rubro ) )";

        return Yii::$app->db->createCommand( $sql )->queryScalar();
    }
}
