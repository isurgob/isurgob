<?php

namespace app\models\config;

use Yii;
use app\utils\helpers\DBException;
use app\utils\db\utb;
use yii\imagine\Image;

/**
 * This is the model class for table "sam.muni_datos".
 *
 * @property string $codigo
 * @property string $nombre
 * @property integer $loc_id
 * @property string $cod_ent
 * @property integer $iva
 * @property string $cuit
 * @property string $ingbrutos
 * @property byte $logo
 * @property byte $logo_grande
 * @property byte $logo_talon
 * @property string $presidente
 * @property string $domi
 * @property string $tel
 * @property string $mail
 * @property string $recl_domi
 * @property string $recl_tel
 * @property string $recl_mail
 * @property string $fisc_domi
 * @property string $fisc_tel
 * @property string $fisc_mail
 * @property string $juz_domi
 * @property string $juz_tel
 * @property string $juz_mail
 * @property string $fchmod
 * @property integer $usrmod
 *
 * @property string $loc_nom
 * @property integer $prov_id
 * @property string $prov_nom
 * @property integer $pais_id
 * @property sring $pais_nom
 */
class Muni extends \yii\db\ActiveRecord
{

	const NOMBRE_IMAGEN_LOGO_GENERAL= "/logo_muni.png";
	const NOMBRE_IMAGEN_LOGO_GRANDE= "/logo_muni_grande.jpg";
	const NOMBRE_IMAGEN_LOGO_TALON= "/logo_muni_chico.jpg";

	 public $loc_nom;
	 public $prov_id;
	 public $prov_nom;
	 public $pais_id;
	 public $pais_nom;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sam.muni_datos';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
    	$ret = [];

    	/**
    	 * VALORES REQUERIDOS
    	 */
    	$ret[] = [
    				[
    			 		'codigo','cuit','ingbrutos', 'domi', 'tel', 'mail',
    			 		//'logo'
				 	],
    			'required'
    			];

    	$ret[] = [
    				[
    			 		'codigo','nombre','cuit','ingbrutos','presidente','domi','tel',
    			 		'mail','recl_domi','recl_tel','recl_mail','fisc_domi','fisc_tel',
    			 		'fisc_mail','juz_domi','juz_tel','juz_mail','fchmod','skype','url'
				 	],
    			'string'
    			];

		//70x60
    	$ret[] = [
					['logo'],
					'image',
					'skipOnEmpty' => true,
					'extensions' => 'png', 
					'maxHeight' => 60,
					'maxWidth' => 70,
					'overHeight' => 'La imagen del {attribute} es demasiado alta. No debe superar los 60 pixeles.',
					'overWidth' => 'La imagen del {attribute} es demasiado ancha. No debe superar los 70 pixeles.'
				];

		//250x100
		$ret[] = [
					['logo_grande'],
					'image',
					'skipOnEmpty' => true,
					'extensions' => 'jpg',
					'maxHeight' => 100,
					'maxWidth' => 250,
					'overHeight' => 'La imagen del {attribute} es demasiado alta. No debe superar los 100 pixeles.',
					'overWidth' => 'La imagen del {attribute} es demasiado ancha. No debe superar los 250 pixeles.'
				];

		//120x22
		$ret[] = [
					['logo_talon'],
					'image',
					'skipOnEmpty' => true,
					'extensions' => 'jpg',
					'maxHeight' => 30,
					'maxWidth' => 120,
					'overHeight' => 'La imagen del {attribute} es demasiado alto. No debe superar los 30 pixeles.',
					'overWidth' => 'La imagen del {attribute} es demasiado ancho. No debe superar los 120 pixeles'
				];

		$ret[]= [
				['logo_grande', 'logo_talon'],
				'default',
				'value' => null
				];

		$ret[] = [
					[
						'incluir_logo2','loc_id','iva','usrmod',
					],
				'integer',
				];
		/**
		 * FIN VALORES REQUERIDOS
		 */

    	return $ret;
    }

    /**
     * @inheritdoc
     */

    public function attributeLabels()
    {
        return [
			'codigo' => 'Código',
			'nombre' => 'Nombre',
			'cuit' => 'CUIT',
			'ingbrutos' => 'Ingresos Brutos',
			'tel' => 'Teléfono',
			'mail' => 'mail',
			'logo' => 'Logo general',
			'logo_grande' => 'Logo Comprobante Superior',
			'logo_talon' => 'Logo Comprobante Talones',
			'domi' => 'Domicilio',
			'incluir_logo2' => 'Incluir Logo 2 en Reportes'
		];
    }

    public function afterFind()
    {
    	//Buscar Localidad
    	$this->loc_nom = utb::getCampo( 'domi_localidad', 'loc_id = ' . $this->loc_id, 'nombre' );

    	//Buscar Provincia
    	$this->prov_id = utb::getCampo( 'domi_localidad', 'loc_id = ' . $this->loc_id, 'prov_id' );

    	$this->prov_nom = utb::getCampo( 'domi_provincia', 'prov_id = ' . $this->prov_id, 'nombre' );

    	//Buscar País
    	$this->pais_id = utb::getCampo( 'domi_provincia', 'prov_id = ' . $this->prov_id, 'pais_id' );

    	$this->pais_nom = utb::getCampo( 'domi_pais', 'pais_id = ' . $this->pais_id, 'nombre' );

		if ($this->cuit !== "") {
			$this->cuit = str_pad($this->cuit,11,0, STR_PAD_LEFT);
			$this->cuit = substr($this->cuit,0,2) . "-" . substr($this->cuit,2,8) . "-" . substr($this->cuit,-1);
		}
    }

    public function grabar()
    {
    	if(!$this->validate()) return false;

		$this->cuit = str_replace("-","",$this->cuit);

    	$sql = "UPDATE sam.muni_datos SET iva = " . $this->iva . ", cuit = '" . $this->cuit . "', ingbrutos = '" . $this->ingbrutos . "',";

    	// Preparo las imágenes para almacenarlas

    	// ------------ //

    	if ($this->logo !== null && file_exists( $this->logo->tempName ) )
    	{
    		$this->guardarImagenFisica('logo', self::NOMBRE_IMAGEN_LOGO_GENERAL);

    		$img = fopen($this->logo->tempName, 'r');
			$data = fread($img, $this->logo->size);

			$this->logo = pg_escape_bytea($data);
			fclose($img);

			$sql .= "logo = '" . $this->logo . "'::bytea,";
    	}

		// ------------ //

		if ($this->logo_grande !== null && file_exists( $this->logo_grande->tempName ) )
    	{
    		$this->guardarImagenFisica('logo_grande', self::NOMBRE_IMAGEN_LOGO_GRANDE);

			$img = fopen($this->logo_grande->tempName, 'r');
			$data = fread($img, $this->logo_grande->size);

			$this->logo_grande = pg_escape_bytea($data);
			fclose($img);

			$sql .= "logo_grande = '" . $this->logo_grande . "'::bytea,";
    	}

		// ------------ //

		if ( $this->logo_talon !== null &&  file_exists( $this->logo_talon->tempName ) )
    	{
    		$this->guardarImagenFisica('logo_talon', self::NOMBRE_IMAGEN_LOGO_TALON);

			$img = fopen($this->logo_talon->tempName, 'r');
			$data = fread($img, $this->logo_talon->size);

			$this->logo_talon = pg_escape_bytea($data);
			fclose($img);

			$sql .= "logo_talon = '" . $this->logo_talon . "'::bytea,";
    	}

		// ------------ //


		$sql .= "incluir_logo2=" . $this->incluir_logo2 . ", presidente = '" . $this->presidente . "', domi = '" . $this->domi . "', tel = '" . $this->tel .
				"', mail = '" . $this->mail . "'" . ", recl_domi = '" . $this->recl_domi . "', recl_tel = '" . $this->recl_tel . "', recl_mail = '" . $this->recl_mail . "'" .
				", fisc_domi = '" . $this->fisc_domi . "', fisc_tel = '" . $this->fisc_tel . "', fisc_mail = '" . $this->fisc_mail . "'" .
				", juz_domi = '" . $this->juz_domi . "', juz_tel = '" . $this->juz_tel . "', juz_mail = '" . $this->juz_mail . "'" .
				", skype = '" . $this->skype . "',url='" . $this->url .
				"', fchmod = CURRENT_TIMESTAMP, usrmod = " . Yii::$app->user->id;

		Yii::$app->db->createCommand( $sql )->execute();

		return true;
	}

    public function getImagen($logo= 1){

    	switch($logo){

    		case 1: $col= 'logo'; break;
    		case 2: $col= 'logo_grande'; break;
    		case 3: $col= 'logo_talon'; break;
    	}
    	return Yii::$app->db->createCommand( "SELECT $col FROM sam.muni_datos" )->queryScalar();
    }

    private function guardarImagenFisica($atributo, $nombreArchivo){

    	$nombreArchivo = "images/" . Yii::$app->db->muni . $nombreArchivo;

		if(file_exists($nombreArchivo)) unlink($nombreArchivo);

		$this->$atributo->saveAs($nombreArchivo, false);
    }
}
