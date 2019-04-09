<?php

namespace app\models\objeto;

use Yii;
use app\utils\db\utb;
use app\utils\db\Fecha;

class Escribano extends \yii\db\ActiveRecord
{
	
	public $fecha_desde;
	public $fecha_hasta;
	public $inm_nom;
	public $est_nom;
	public $nc;
	public $domicilio;
	public $compradores;
	public $vendedores;
	public $escribano_nom;
	
	public static function tableName()
    {
        return 'inm_vta';
    }
	
	public function __construct(){

		$this->vta_id = 0;
		$this->est = 0;
		$this->fecha_desde = date('d/m/Y');	
		$this->fecha_hasta = date('d/m/Y');
		$this->inm_nom = "";
		$this->est_nom = "";
		$this->nc = "";
		$this->domicilio = "";
		$this->compradores = [];
		$this->vendedores = [];
		$this->escribano = 0;
		$this->obj_id = "";
		$this->escribano_nom = "";
			
	}
	
	public function afterFind(){

    	$this->compradores = $this->getComprador();
		$this->vendedores = $this->getVendedor();
		
		$this->fecha = date("d/m/Y",strtotime($this->fecha));
		
		$this->est_nom = utb::getCampo("inm_tvtaest","cod='" . $this->est . "'");
		$this->nc = utb::getCampo("v_inm","obj_id='" . $this->obj_id . "'", "nc_guiones");
		$this->domicilio = utb::getCampo("v_inm","obj_id='" . $this->obj_id . "'", "dompar_dir");
		$this->inm_nom = utb::getCampo("v_inm","obj_id='" . $this->obj_id . "'", "nombre");
		
		$this->escribano_nom = utb::getCampo("sam.usuarioweb u inner join objeto o on u.obj_id=o.obj_id","u.usr_id=" . $this->escribano,"o.nombre");
		
    }
	
	public function Informar( $id ){
	
		try{

            $sql =  "UPDATE inm_vta SET est = 'I', fchmod = CURRENT_TIMESTAMP, usrmod = " . Yii::$app->user->id . " WHERE vta_id = " . $id;
            Yii::$app->db->createCommand( $sql )->execute();

        } catch (\Exception $e ){

            $this->addError( 'vta_id', $e->getMessage() );

            return false;

        }

        return true;
		
	}
	
	public function getEstados(){
	
		return utb::getAux('inm_tvtaest','cod','nombre',2);
	
	}
	
	public function getEscribanos(){
	
		return utb::getAux('sam.usuarioweb u inner join objeto o on u.obj_id=o.obj_id','u.usr_id','o.nombre',2,"u.acc_escribano='S'");
	
	}
	
	public function getComprador( $cond = "" ) {
	
		$sql = "select c.*,n.nombre tnac_nom from inm_vta_comp c inner join persona_tnac n on c.tnac=n.cod where c.vta_id=$this->vta_id";
		if ( $cond !== "" ) 
			$sql .= " and " . $cond;
			
		$array = Yii::$app->db->createCommand($sql)->queryAll();
		
		if (count($array) > 0){
			foreach ($array as $k => $a){
				$a['cuit'] = str_pad($a['cuit'], 11, "0", STR_PAD_LEFT);
				$array[$k]['cuit'] = substr($a['cuit'],0, 2) . "-" . substr($a['cuit'], 2,8) . "-" . substr($a['cuit'], -1);	
			}
		}
		return $array;
	
	}
	
	public function getVendedor( $cond = "" ) {
	
		$sql = "select v.*,n.nombre tnac_nom from inm_vta_vend v inner join persona_tnac n on v.tnac=n.cod where vta_id=$this->vta_id";
		if ( $cond !== "" ) 
			$sql .= " and " . $cond;
		
		$array = Yii::$app->db->createCommand($sql)->queryAll();
		
		if (count($array) > 0){
			foreach ($array as $k => $a){
				$a['cuit'] = str_pad($a['cuit'], 11, "0", STR_PAD_LEFT);
				$array[$k]['cuit'] = substr($a['cuit'],0, 2) . "-" . substr($a['cuit'], 2,8) . "-" . substr($a['cuit'], -1);	
			}
		}
		return $array;
	
	}
	
	
	public function buscar(){
	
		if ( $this->fecha_desde > $this->fecha_hasta ){
			Yii::$app->session->setFlash( "error_cons", "Error en Rango de Fecha" );
			return [];
		}
		
		$resultado = Escribano::find()->where( $this->ArmarCondicion() )->all();
		
		return $resultado;	
	
	}
	
	public function Imprimir(){
	
		$cond = $this->ArmarCondicion();
		
		$sql = "select *,to_char(fecha,'dd/mm/yyyy') fecha from v_inm_vta " . ( $cond !== "" ? " where " . $cond : "" ) . " order by vta_id";
		
		Yii::$app->session['titulo'] = "Listado de Ventas";
        Yii::$app->session['condicion'] = $this->DescripcionCondicion(); 
		Yii::$app->session['sql'] = $sql;
		Yii::$app->session['proceso_asig'] = 3500;
		Yii::$app->session['columns'] = [
			['attribute'=>'vta_id','header' => 'Venta', 'contentOptions'=>['style'=>'text-align:center']],
			['attribute'=>'fecha','header' => 'Fecha', 'contentOptions'=>['style'=>'text-align:center']],
			['attribute'=>'obj_id','header' => 'Objeto', 'contentOptions'=>['style'=>'text-align:center']],
			['attribute'=>'nc','header' => 'NC', 'contentOptions'=>['style'=>'text-align:center']],
			['attribute'=>'domicilio','header' => 'Domicilio', 'contentOptions'=>['width'=>'40%']],
			['attribute'=>'est_nom','header' => 'Estado']
        ];
	
	}
	
	private function ArmarCondicion(){
	
		$cond = "";
		
		if ( $this->est !== '0') $cond .= "est='$this->est'";
		if ( $this->fecha_desde !== "" and $this->fecha_hasta !== "" ){
			if ( $cond !== "" ) $cond .= " and ";
			$cond .= "fecha between '$this->fecha_desde' and '$this->fecha_hasta'";
		}	
		if ( $this->obj_id !== "" ){
			if ( $cond !== "" ) $cond .= " and ";
			$cond .= "obj_id='$this->obj_id'";
		}
		if ( intVal($this->escribano) !== 0 ){
			if ( $cond !== "" ) $cond .= " and ";
			$cond .= "escribano=$this->escribano";
		}
		
		return $cond;
	}
	
	private function DescripcionCondicion(){
	
		$cond = "";
		
		if ( $this->est !== '0') $cond .= "<br>-Estado= " . utb::getCampo("inm_tvtaest", "est='" . $this->est . "'");
		if ( $this->fecha_desde !== "" and $this->fecha_hasta !== "" )
			$cond .= "<br>- Fecha entre $this->fecha_desde y $this->fecha_hasta";
		if ( $this->obj_id !== "" )
			$cond .= "<br>-Inmueble= $this->obj_id - " . utb::getCampo("v_inm","obj_id='" . $this->obj_id . "'");
		if ( intVal($this->escribano) !== 0 )
			$cond .= "<br>-Escribano= $this->escribano - " . utb::getCampo("sam.usuarioweb u inner join objeto o on u.obj_id=o.obj_id","u.usr_id=" . $this->escribano,"o.nombre");	
		
		
		return $cond;
	}
	
}
