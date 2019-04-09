<?php

namespace app\models\ctacte;

use Yii;

use yii\data\ArrayDataProvider;

use app\utils\helpers\DBException;
use app\utils\db\Fecha;
use app\utils\db\utb;
/**
 * @property string $obj_id
 */
class Ajustes extends \yii\db\ActiveRecord{

	public $debe;
	public $haber;
	public $cta_nom;
	public $cta_id;

	public $objeto_tipo;

	public $detalleAjustes;

	private $usaSubcta;

	public function __construct(){

		parent::__construct();

		$this->usaSubcta = false;
		$this->subcta = 0;
	}

	public static function tableName(){

		return 'v_ctacte_ajuste';
	}

	public function rules(){

		$ret = [];

		/**
		 * CAMPOS REQUERIDOS
		 */
		$ret[] = [
				['cta_id', 'cta_nom'],
				'required',
				'on' => ['cuenta'],
				'message' => 'Elija una cuenta'
				];

		$ret[] = [
				['debe', 'haber'],
				'required',
				'on' => ['cuenta']
				];

		$ret[] = [
				['trib_id', 'obj_id'],
				'required',
				'on' => ['insert'],
				'message' => 'Elija un {attribute}'
				];

		$ret[] = [
				['anio', 'cuota'],
				'required',
				'on' => ['insert'],
				'message' => 'Ingrese un {attribute}'
				];

		$ret[] = [
				'subcta',
				'required',
				'when' => function($model){return $this->usaSubcta == 1;},
				'on' => ['insert'],
				'message' => 'Ingrese la subcuenta'
				];

		/**
		 * FIN CAMPOS REQUERIDOS
		 */

		/**
		 * TIPO Y RANGO DE DATOS
		 */
		$ret[] = [
				'cta_id',
				'integer',
				'min' => 1,
				'on' => ['cuenta'],
				'message' => 'La cuenta no existe'
				];

		$ret[] = [
				['debe', 'haber'],
				'number',
				'on' => ['cuenta']
				];

		$ret[] = [
				'obj_id',
				'string',
				'min' => 8,
				'max' => 8,
				'on' => ['insert']
				];

		$ret[] = [
				'expe',
				'string',
				'max' => 12,
				'on' => ['insert']
				];

		$ret[] = [
				'obs',
				'string',
				'max' => 100,
				'on' => ['insert']
				];

		$ret[] = [
				'subcta',
				'integer',
				'min' => 1,
				'when' => function($model){return $this->usaSubcta == 1;},
				'on' => ['insert']
				];

		/**
		 * FIN TIPO Y RANGO DE DATOS
		 */

		/**
		 * VALORES POR DEFECTO
		 */
		$ret[] = [
				['debe', 'haber'],
				'default',
				'value' => 0,
				'on' => ['cuenta']
				];

		$ret[] = [
				['expe', 'obs'],
				'default',
				'value' => '',
				'on' => ['insert']
				];

		$ret[] = [
				'subcta',
				'default',
				'value' => 0,
				'when' => function($model){return $this->usaSubcta == 1;},
				'on' => ['insert']
				];
		/**
		*
		*/

		$ret[] = [
				'obj_id',
				'existeObjeto',
				'on' => ['insert']
				];

		return $ret;
	}

	public function scenarios(){

		return [

			'cuenta' => ['cta_id', 'cta_nom', 'debe', 'haber'],
			'insert' => ['expe', 'obs', 'trib_id', 'obj_id', 'subcta', 'anio', 'cuota', 'obj_nom']
		];
	}

	public function beforeValidate(){

		if($this->trib_id != null && intval($this->trib_id) > 0)
			$this->usaSubcta = utb::getCampo('v_trib', "trib_id = $this->trib_id", 'uso_subcta');


		if($this->trib_id > 0){

			$dato= utb::getCampo('v_trib', "trib_id = $this->trib_id", 'tobj_nom');
			if($dato !== false) $this->objeto_tipo= $dato;
		}

		return true;
	}

	public function afterFind(){

		$dato= utb::getCampo('v_trib', "trib_id = $this->trib_id", 'tobj_nom');

		if($dato !== false) $this->objeto_tipo= $dato;
	}

	public function grabar(){

		$this->setScenario('insert');
		if(!$this->validate()) return false;

		if(count($this->detalleAjustes) == 0){
			$this->addError($this->obj_id, 'No hay cuentas de ajustes a registrar');
			return false;
		}

		$trans = Yii::$app->db->beginTransaction();

		$ctacte_id = $this->getCtacteId($this->trib_id, $this->obj_id, $this->subcta, $this->anio, $this->cuota);

		if($ctacte_id <= 0){
			$this->addError($this->obj_id, 'No existe cuenta corriente');
			$trans->rollBack();
			return false;
		}

		$codigo = $this->proximoCodigo();

		$sql = "Insert Into ctacte_ajuste(aju_id, ctacte_id, expe, obs, trib_id, fchmod, usrmod)" .
				"Values($codigo, $ctacte_id, '$this->expe', '$this->obs', $this->trib_id, current_timestamp, " . Yii::$app->user->id . ")";

		$res = Yii::$app->db->createCommand($sql)->execute() > 0;

		if(!$res){
			$this->addError($this->trib_id, 'Ocurrió un error al intentar registrar el ajuste');
			$trans->rollBack();
			return false;
		}

		foreach($this->detalleAjustes as $det){

			$sql = "Insert Into ctacte_det(ctacte_id, topera, comprob, cta_id, debe, haber, fecha, est, fchmod, usrmod)" .
				" Values($ctacte_id, 11, $codigo, " . $det['cta_id'] . ", " . $det['debe'] . ", " . $det['haber'] . "," .
				" current_timestamp, 'A', current_timestamp, " . Yii::$app->user->id . ");";

			$res = Yii::$app->db->createCommand($sql)->execute() > 0;

			if(!$res){
				$this->addError($this->trib_id, 'Ocurrió un error al intentar grabar el ajuste');
				$trans->rollBack();
				return false;
			}
		}

		$sql = "Update ctacte Set obs = 'Ajuste Manual Número: $codigo' Where ctacte_id = $ctacte_id";
		$res = Yii::$app->db->createCommand($sql)->execute() > 0;

		if(!$res){
			$this->addError($this->trib_id, 'Ocurrió un error al intentar actualizar la cuenta corriente');
			$trans->rollBack();
			return false;
		}

		$sql = "Select sam.uf_ctacte_ajuste($ctacte_id)";
		Yii::$app->db->createCommand($sql)->queryScalar();

		$this->aju_id = $codigo;

		$trans->commit();
		return true;
	}

	public function borrar(){

		$sql = "Select sam.uf_CtaCte_Ajuste_Borrar($this->aju_id)";
		Yii::$app->db->createCommand($sql)->execute();
	}

	public static function buscarAv($cond, $orden = 'aju_id'){

		$sql = "Select * From v_ctacte_ajuste Where $cond";

		if($orden != '') $sql .= " Order By $orden";

		$models = Yii::$app->db->createCommand($sql)->queryAll();
		$count = count($models);

		return new ArrayDataProvider([
			'allModels' => $models,
			'totalCount' => $count,
			'pagination' => [
				'pageSize' => 40
			]
		]);
	}

	/**
	 * Obtiene el saldo de la cuenta corriente para el periodo dado.
	 *
	 * @param int $trib_id - Codigo de tributo.
	 * @param string $obj_id - Codigo de objeto.
	 * @param int subcta - Sub cuenta.
	 * @param int anio - Año.
	 * @param int $cuota - Cuota.
	 *
	 * @return int - Saldo de la cuenta corriente. Por defecto el saldo es cero.
	 */
	public function getSaldo($trib_id, $obj_id, $subcta, $anio, $cuota){

		$datos = utb::getCampo('v_trib', "trib_id = $trib_id", 'uso_subcta');
		$saldo = 0.00;

		if($datos !== false){

			if($datos == 1 && $subcta <= 0) $this->addError($this->subcta, 'Ingrese la subcta');

			if(!$this->hasErrors()){

				$this->ctacte_id = $this->getCtacteId($trib_id, $obj_id, $subcta, $anio, $cuota);
				if($this->ctacte_id > 0){

					$sql = "Select sam.uf_ctacte_saldo($this->ctacte_id)";

					$saldo = Yii::$app->db->createCommand($sql)->queryScalar();

				} else $this->addError($this->obj_id, 'No existe cuenta corriente');

			}
		}

		return $saldo;
	}

	private function getCtacteId($trib_id, $obj_id, $subcta, $anio, $cuota){

		if($trib_id == 1){
			$sql = "Select CtaCte_id From CtaCte Where Trib_Id = $trib_id and Anio = $anio and Cuota = $cuota";
		} else {
			$sql = "Select CtaCte_id From CtaCte Where Trib_Id=$trib_id and Obj_Id='$obj_id' and SubCta=$subcta and Anio=$anio and Cuota=$cuota";
		}

		$ctacte_id = Yii::$app->db->createCommand($sql)->queryScalar();
		return $ctacte_id;
	}

	private function proximoCodigo(){

		$sql = "Select nextval('seq_ctacte_aju_id'::regclass)";
		$res = Yii::$app->db->createCommand($sql)->queryScalar() + 1;

		return $res;
	}

	public function existeObjeto(){

		$sql = "Select Exists (Select 1 From objeto Where obj_id = '$this->obj_id' And est = 'A')";
		$res = Yii::$app->db->createCommand($sql)->queryScalar();

		if(!$res) $this->addError($this->obj_id, 'El objeto no existe');

	}

	public function cuentas(){

		$sql= "Select c.cta_id, c.nombre As cta_nom,  d.debe, d.haber From cuenta c, ctacte_det d Where c.cta_id = d.cta_id And d.ctacte_id = " . intVal($this->ctacte_id) . " And topera = 11";

		return Yii::$app->db->createCommand($sql)->queryAll();
	}
}
