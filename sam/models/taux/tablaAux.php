<?php

namespace app\models\taux;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\data\ArrayDataProvider;

//ini_set("display_errors", "on");
//error_reporting(E_ALL);
/**
 * This is the model class for table "sam.tabla_aux".
 *
 * @property integer $cod
 * @property string $nombre
 * @property integer $mod_id
 * @property string $titulo
 * @property string $frm
 * @property string $link
 * @property integer $autoinc
 * @property integer $accesocons
 * @property integer $accesoedita
 * @property string $tcod
 * @property string $web
 * @property string $fchmod
 * @property integer $usrmod
 */
class tablaAux extends \yii\db\ActiveRecord
{
    public $nombrelong;
    public $codlong;
    public $tercercamponom;
    public $tercercampotipo;
    public $tercercampolong;
    public $tercercampodesc;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sam.tabla_aux';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod', 'nombre'], 'required'],
            [['cod', 'mod_id', 'autoinc', 'accesocons', 'accesoedita', 'usrmod'], 'integer'],
            [['fchmod'], 'safe'],
            [['nombre', 'link'], 'string', 'max' => 30],
            [['titulo'], 'string', 'max' => 35],
            [['frm'], 'string', 'max' => 25],
            [['tcod', 'web'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod' => 'Codigo',
            'nombre' => 'Nombre',
            'mod_id' => 'Codigo de modulo',
            'titulo' => 'Titulo',
            'frm' => 'Nombre del formulario',
            'link' => 'Link del sistema web',
            'autoinc' => 'Si el codigo es autoincremental',
            'accesocons' => 'Codigo de acceso de consulta',
            'accesoedita' => 'Codigo de acceso a la edicion',
            'tcod' => 'Tipo de campo del c�digo (c/n)',
            'web' => 'Indica si la table es visible en sistema web',
            'fchmod' => 'Fecha de modificacion',
            'usrmod' => 'Codigo de usuario que modifico',
        ];
    }

    public function CargarTabla( $tabla, $tercercampo='', $condicion = '' )
    {
    	$sql = "select count(*) from ".$tabla . " as t";
    	if($condicion != '') $sql .= " Where $condicion";
    	//echo $sql;
    	//exit();
    	$count = Yii::$app->db->createCommand($sql)->queryScalar();

        $sql = "select t.*,t.".tablaAux::GetCampoClave($tabla)." cod, ";
        if ($tabla != 'caja_mdp' and $tabla != 'judi_hono') $sql .= " u.nombre || ' - ' || to_char(t.fchmod,'dd/mm/yyyy') as modif, ";
        $sql .= ($tercercampo == '' ? "''" : $tercercampo)." as tercercampo ";
        $sql .= " from ".$tabla." t ";

        if ($tabla != 'caja_mdp' and $tabla != 'judi_hono'){

        	$sql .= " inner join sam.sis_usuario u on t.usrmod=u.usr_id " .
        			" Group By cod, u.nombre ";

        	if($condicion != '') $sql .= " Having $condicion";

        } else if($condicion != '')
        	$sql .= " Where $condicion ";
		
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'key' => 'cod',
            'totalCount' => (int)$count,
			'sort' => [
				'attributes' => [
					'nombre',
					'cod',
                    'ofi_id'
				],
                'defaultOrder' => [
                    'nombre' => SORT_ASC,
                ]
			],
			'pagination'=> [
				'pageSize'=>50,
			],

        ]);

        return $dataProvider;

    }

    public function grabarTablaAux($consulta, $cod, $nombre,$tercercampo)
    {
    	if ($consulta == 0) // si es nuevo
    	{
    		if ($this->autoinc == 0)
    		{
    			$sql = "select count(*) from ".$this->nombre." where ";
    			if ($this->tcod == 'N') $sql .= tablaAux::GetCampoClave($this->nombre)."=".$cod;
    			if ($this->tcod == 'C') $sql .= tablaAux::GetCampoClave($this->nombre)."='".$cod."'";

    			$count = Yii::$app->db->createCommand($sql)->queryScalar();

    			if ($count > 0) return "El código se encuentra repetido";
    		}

    		if ($this->autoinc == 1 and $this->tcod == 'N')
    		{
    			$sql = "select coalesce(max(".tablaAux::GetCampoClave($this->nombre)."),0) + 1 from ".$this->nombre;

    			$cod = Yii::$app->db->createCommand($sql)->queryScalar();
    		}

    		if ($this->tcod == 'N')
    		{
    			$sql = "insert into ".$this->nombre." values (".$cod.",'".$nombre."'";

    			if ($this->tercercamponom != '')
    			{
    				if (strpos($this->tercercampotipo, 'var') != '')
    				{
    					// si no es numerico o smallint.. es varchar
    					$sql .= ",'".$tercercampo."'";
    				}else{
    					$sql .= ",".str_replace(",", ".", $tercercampo);
    				}
    			}
    			$sql .= ",current_timestamp,".Yii::$app->user->id.")";
    		}else {
    			$sql = "insert into ".$this->nombre." values ('".$cod."','".$nombre."'";

    			if ($this->tercercamponom != '')
    			{
    				if (strpos($this->tercercampotipo, 'var') != '')
    				{
    					// si no es numerico o smallint.. es varchar
    					$sql .= ",'".$tercercampo."'";
    				}else{
    					$sql .= ",".str_replace(",", ".", $tercercampo);
    				}
    			}
    			$sql .= ",current_timestamp,".Yii::$app->user->id.")";
    		}

    	}else {

    		if ($this->tcod == 'N')
    		{
    			$sql = "update ".$this->nombre." set nombre='".$nombre."',fchmod=current_timestamp,usrmod=".Yii::$app->user->id;

    			if ($this->tercercamponom != '')
    			{
    				if (strpos($this->tercercampotipo, 'var') != '')
    				{
    					// si no es numerico o smallint.. es varchar
    					$sql .= ",".$this->tercercamponom."='".$tercercampo."'";

    				}else{
    					$sql .= ",".$this->tercercamponom."=".str_replace(",", ".", $tercercampo);
    				}
    			}
    			$sql .= " where ".tablaAux::GetCampoClave($this->nombre)."=".$cod;
    		}else {
    			$sql = "update ".$this->nombre." set nombre='".$nombre."',fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
    			if ($this->tercercamponom != '')
    			{
    				if (strpos($this->tercercampotipo, 'var') != '')
    				{
    					// si no es numerico o smallint.. es varchar
    					$sql .= ",".$this->tercercamponom."='".$tercercampo."'";
    				}else{
    					$sql .= ",".$this->tercercamponom."=".str_replace(",", ".", $tercercampo);
    				}
    			}
    			$sql .= " where ".tablaAux::GetCampoClave($this->nombre)."='".$cod."'";
    		}

    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}
    }



 //--------------------------------------------------------------------------------------------------------------------------------
 //--------------------------------------------------------------------------------------------------------------------------------
 //---------------------------------------------TABLA AUXULIAR OPT OBRA----------------------------------------------------------
 //--------------------------------------------------------------------------------------------------------------------------------


    public function grabarTablaAuxOPTObra($consulta, $cod, $nombre,$fchfin)
    {
    	if ($consulta == 0) // si es nuevo
    	{
    		$sql = "Select Count(*) From OP_TObra Where Nombre= '".$nombre."'";
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "El Nombre se encuentra repetido";


    		$sql = "Insert Into OP_TObra (Nombre,FchFin,FchMod,UsrMod) Values ('".$nombre."',".$fchfin;
    		$sql .= ",current_timestamp,".Yii::$app->user->id.")";

    	}else {
    		$sql = "Select Count(*) From OP_TObra Where Nombre= '".$nombre."' and cod <> ".$cod;
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "El Nombre se encuentra repetido";

    		$sql = "Update OP_TObra Set Nombre = '".$nombre."',fchfin=".$fchfin;
    		$sql .= ",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
    		$sql .= " where cod=".$cod;
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}
    }

 //--------------------------------------------------------------------------------------------------------------------------------
 //--------------------------------------------------------------------------------------------------------------------------------
 //---------------------------------------------TABLA AUXULIAR DOMI CALLE----------------------------------------------------------
 //--------------------------------------------------------------------------------------------------------------------------------

    public function grabarTablaAuxDomiCalle($consulta, $cod, $nombre, $tcalle)
    {
    	if ($consulta == 0) // si es nuevo
    	{
    		$sql = "Select Count(*) From domi_calle Where upper(Nombre)= upper('".$nombre."') ";
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "El Nombre se encuentra repetido para esa localidad";

    		$sql = "select Coalesce(max(calle_id), 0)+1 from domi_calle";
    		$cod = Yii::$app->db->createCommand($sql)->queryScalar();

    		$sql = "Insert Into domi_calle (Calle_ID,Nombre,TCalle,FchMod,UsrMod) Values (".$cod.",'".$nombre."',".$tcalle;
    		$sql .= ",current_timestamp,".Yii::$app->user->id.")";

    	}else {
    		$sql = "Select Count(*) From domi_calle Where upper(Nombre)= upper('".$nombre."') and calle_id <> ".$cod;
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "El Nombre se encuentra repetido para esa localidad";

    		$sql = "Update domi_calle Set Nombre = '".$nombre."',tcalle=".$tcalle;
    		$sql .= ",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
    		$sql .= " where calle_id=".$cod;
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}
    }

 //--------------------------------------------------------------------------------------------------------------------------------
 //--------------------------------------------------------------------------------------------------------------------------------
 //---------------------------------------------TABLA AUXULIAR BANCO SUCURSAL------------------------------------------------------
 //--------------------------------------------------------------------------------------------------------------------------------
  public function grabarTablaAuxBancoSuc($consulta,$bco_ent,$bco_suc,$nombre,$domi,$tel){

    	$count="";

    	if ($consulta == 0)
    	{
    		$sql = "SELECT COUNT(*) FROM banco WHERE bco_ent=".$bco_ent." AND bco_suc=".$bco_suc;
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "Ya existe una sucursal con ese codigo";


    		$sql = "INSERT INTO banco(bco_ent,bco_suc,nombre,domi,tel,fchmod,usrmod) " .
    				"VALUES (".$bco_ent.",".$bco_suc.",'".$nombre."','".$domi."','".$tel."',current_timestamp,".Yii::$app->user->id.")";

    	}else {

    		$sql = "UPDATE banco SET nombre = '".$nombre."',domi='".$domi."',tel='".$tel."'";
    		$sql .= ",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
    		$sql .= " where bco_ent=".$bco_ent." AND bco_suc=".$bco_suc;
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}
    }

    public function borrarTablaAuxBancoSuc($bco_ent,$bco_suc){

    		$sql = "DELETE FROM banco";
			$sql .= " WHERE bco_ent=".$bco_ent." and bco_suc=".$bco_suc;
			try{
				$cmd = Yii :: $app->db->createCommand($sql);
				$cmd->execute();
		 	}
		 	catch(\Exception $e){
		 		$validar = strstr($e->getMessage(), 'The SQL being', true);
				$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
		 		return "<li>".$validar."</li>";
		 	}

    }
     public function buscarEntidadBancaria($cond){

     	$sql = "select banco.bco_ent,banco.bco_suc,banco.nombre,banco.domi,banco.tel,(u.nombre || ' - ' || to_char(banco.fchmod,'dd/mm/yyyy')) as modif";
        $sql .= " from banco, sam.sis_usuario u";
        $sql .= " where u.usr_id = banco.usrmod";

		$pag = "select count(*)";
        $pag .= " from banco, sam.sis_usuario u";
        $pag .= " where u.usr_id = banco.usrmod";

        if ($cond != ""){ $sql = $sql.' and '.$cond; $pag = $pag.' and '.$cond;}

        $count = Yii::$app->db->createCommand($pag)->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => (int)$count,
			'pagination'=> [
				'pageSize'=>(int)$count,
			],
        ]);
        return $dataProvider;
    }

   public function getNombreBancoEntidad($idBancoEntidad) {

			$cmd = "";

			if ($idBancoEntidad != ""){

				$sql = "SELECT nombre FROM banco_entidad WHERE bco_ent =" . $idBancoEntidad;
				$cmd = Yii :: $app->db->createCommand($sql)->queryScalar();

			}
			return $cmd;
	}

 //------------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------

  //---------------------------------------------------TABLA AUXULIAR CAJA MDP---------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 		public function grabarTablaAuxCajaMdp($consulta,$cod,$nombre,$tipo,$cotiza,$simbolo,$habilitado){

 			$count="";

	 		if ($consulta == 0)
	    	{
    			$sql = "select MAX(mdp) from caja_mdp";
		    	$cod = Yii::$app->db->createCommand($sql)->queryScalar() + 1;
	    		$sql = "SELECT COUNT(*) FROM caja_mdp WHERE nombre='".$nombre."'";
	    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

	    		if ($count > 0) return "Ya existe una sucursal con ese codigo";

	    		$sql = "INSERT INTO caja_mdp(mdp,nombre,tipo,cotiza,simbolo,habilitado) " .
	    				"VALUES (".$cod.",'".$nombre."','".$tipo."',".$cotiza.",'".$simbolo."',".$habilitado.")";

	    	}else {

	    		$sql = "SELECT nombre FROM intima_tetapa WHERE cod=".$cod;
	    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

	    		if($nombreSql != $nombre){

	    			$sql = "SELECT COUNT(*) FROM intima_tetapa WHERE nombre='".$nombre."'";
	    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
	    		}

	    		if ($count > 0) return "Ya existe una estapa de intimacion con ese nombre";

	    		$sql = "UPDATE caja_mdp";
	    		$sql .=	" SET nombre = '".$nombre."',tipo='".$tipo."',cotiza=".$cotiza.",simbolo='".$simbolo."',habilitado=".$habilitado;
	    		$sql .= " where mdp=".$cod;
	    	}

	    	$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

			if ($rowCount > 0)
			{
				return "";

			} else {

				return "Ocurrio un error al intentar grabar en la BD.";
			}

 		}


  //--------------------------------------------------------------------------------------------------------------------------------
 //---------------------------------------------------------------------------------------------------------------------------------

  //-----------------------------------------------TABLA AUXULIAR CAJA TESORERIA-------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------

	public function grabarTablaAuxCajaTeso($consulta,$cod,$nombre){

			$count="";

			if ($consulta == 0)
	    	{
	    		$sql = "SELECT COUNT(*) FROM caja_tesoreria WHERE teso_id='".$cod."'";
	    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

	    		if ($count > 0) return "Ya existe una caja con ese codigo";

	    		$sql = "SELECT COUNT(*) FROM caja_tesoreria WHERE nombre='".$nombre."'";
	    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

	    		if ($count > 0) return "Ya existe una caja con ese nombre";

	    		$sql = "INSERT INTO caja_tesoreria(teso_id,nombre,est,fchmod,usrmod) " .
	    				"VALUES (".$cod.",'".$nombre."','A',current_timestamp,".Yii::$app->user->id.")";
	    	}else {

	    		$sql = "SELECT nombre FROM intima_tetapa WHERE cod=".$cod;
	    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

	    		if($nombreSql != $nombre){

	    			$sql = "SELECT COUNT(*) FROM intima_tetapa WHERE nombre='".$nombre."'";
	    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
	    		}

	    		if ($count > 0) return "Ya existe una estapa de intimacion con ese nombre";

	    		$sql = "UPDATE caja_tesoreria";
	    		$sql .=	" SET nombre = '".$nombre."',fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
	    		$sql .= " where teso_id=".$cod;
	    	}

	    	$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

			if ($rowCount > 0)
			{
				return "";

			} else {

				return "Ocurrio un error al intentar grabar en la BD.";
			}
	}

	public function borrarTablaAuxCajaTeso($cod){

		    $sql = "UPDATE caja_tesoreria";
		    $sql .= " SET est='B'";
			$sql .= " WHERE teso_id=".$cod;
			try{
				$cmd = Yii :: $app->db->createCommand($sql);
				$cmd->execute();
		 	}
		 	catch(\Exception $e){
		 		$validar = strstr($e->getMessage(), 'The SQL being', true);
				$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
		 		return "<li>".$validar."</li>";
		 	}
	}




  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------

  //-----------------------------------------------TABLA AUXULIAR TIPO DE EXIMISIONES--------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------

	 	public function grabarTablaAuxExenTipo($consulta,$texen_id,$nombre,$norma,$tobj,$item_id,$trenov,$sol_sitlab,$sol_cony,$sol_ingreso,$sol_benef,$val_propunica,$val_benefunaprop,$val_titcony,$val_persfisica,$val_persjuridica,$est){

				$count="";

				if ($consulta == 0)
		    	{
		    		$sql = "SELECT COUNT(*) FROM exen_tipo WHERE nombre='".$nombre."'";
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "Ya existe un tipo de eximision con ese nombre";

		    		$sql = "select MAX(texen_id) from exen_tipo";
			    	$cod = Yii::$app->db->createCommand($sql)->queryScalar() + 1;

		    		$sql = "INSERT INTO exen_tipo(texen_id,nombre,norma,tobj,item_id,trenov,sol_sitlab,sol_cony,sol_ingreso,sol_benef,val_propunica,val_benefunaprop,val_titcony,val_persfisica,val_persjuridica,est,fchmod,usrmod) " .
		    				"VALUES (".$cod.",'".$nombre."','".$norma."',".$tobj.",".$item_id.",'".$trenov."',".$sol_sitlab.",".$sol_cony.",".$sol_ingreso.",".$sol_benef.",".$val_propunica.",".$val_benefunaprop.",".$val_titcony.",".$val_persfisica.",".$val_persjuridica.",'A',current_timestamp,".Yii::$app->user->id.")";
		    	}else {

					$sql = "SELECT nombre FROM intima_tetapa WHERE cod=".$cod;
		    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if($nombreSql != $nombre){

		    			$sql = "SELECT COUNT(*) FROM intima_tetapa WHERE nombre='".$nombre."'";
		    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
		    		}

		    		if ($count > 0) return "Ya existe una estapa de intimacion con ese nombre";

		    		$sql = "UPDATE exen_tipo";
		    		$sql .=	" SET nombre = '".$nombre."',norma='".$norma."',tobj=".$tobj.",item_id=".$item_id.",trenov='".$trenov."',sol_sitlab=".$sol_sitlab.",sol_cony=".$sol_cony.",sol_ingreso=".$sol_ingreso.",sol_benef=".$sol_benef.",val_propunica=".$val_propunica.",val_benefunaprop=".$val_benefunaprop.",val_titcony=".$val_titcony.",val_persfisica=".$val_persfisica.",val_persjuridica=".$val_persjuridica.",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
		    		$sql .= " where texen_id=".$texen_id;
		    	}

		    	$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();

				if ($rowCount > 0)
				{
					return "";

				} else {

					return "Ocurrio un error al intentar grabar en la BD.";
				}
		}

		public function borrarTablaAuxExenTipo($cod){

			    $sql = "UPDATE exen_tipo";
			    $sql .= " SET est='B'";
				$sql .= " WHERE texen_id=".$cod;
				try{
					$cmd = Yii :: $app->db->createCommand($sql);
					$cmd->execute();
			 	}
			 	catch(\Exception $e){
			 		$validar = strstr($e->getMessage(), 'The SQL being', true);
					$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
			 		return "<li>".$validar."</li>";
			 	}
		}



	   public function getNombreItem($idItem) {

			$cmd = "";

			if ($idItem != ""){

				$sql = "SELECT nombre FROM item WHERE item_id =" . $idItem;
					$cmd = Yii :: $app->db->createCommand($sql)->queryScalar();

				}
				return $cmd;
	   }


  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------

  //-----------------------------------------------TABLA AUXULIAR TIPO DE ZONA O.P-----------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 	public function grabarTablaAuxTZonaOP($consulta,$cod,$nombre,$fos,$fot){

 			$count="";

			if ($consulta == 0)
	    	{
	    		$sql = "SELECT COUNT(*) FROM inm_tzonaop WHERE cod='".$cod."'";
	    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

	    		if ($count > 0) return "Ya existe una zona de obra con ese codigo";

	    		$sql = "SELECT COUNT(*) FROM inm_tzonaop WHERE nombre='".$nombre."'";
	    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

	    		if ($count > 0) return "Ya existe una zona de obra con ese nombre";

	    		$sql = "INSERT INTO inm_tzonaop(cod,nombre,fos,fot,fchmod,usrmod) " .
	    				"VALUES (".$cod.",'".$nombre."',".$fos.",".$fot.",current_timestamp,".Yii::$app->user->id.")";
	    	}else {

				$sql = "SELECT nombre FROM intima_tetapa WHERE cod=".$cod;
	    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

	    		if($nombreSql != $nombre){

	    			$sql = "SELECT COUNT(*) FROM intima_tetapa WHERE nombre='".$nombre."'";
	    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
	    		}

	    		if ($count > 0) return "Ya existe una estapa de intimacion con ese nombre";

	    		$sql = "UPDATE inm_tzonaop";
	    		$sql .=	" SET nombre = '".$nombre."',fos=".$fos.",fot=".$fot.",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
	    		$sql .= " where cod=".$cod;
	    	}

	    	$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

			if ($rowCount > 0)
			{
				return "";

			} else {

				return "Ocurrio un error al intentar grabar en la BD.";
			}
	}


  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------

  //-------------------------------------------TABLA AUXULIAR ETAPAS DE INTIMACION-----------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


	 public function grabarTablaAuxIntiEtapas($consulta,$cod,$nombre,$genauto,$est){

	  	$count="";

	  	if ($consulta == 0)
	    	{
	    		$sql = "SELECT COUNT(*) FROM intima_tetapa WHERE cod=".$cod;
	    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

	    		if ($count > 0) return "Ya existe una estapa de intimacion con ese codigo";

	    		$sql = "SELECT COUNT(*) FROM intima_tetapa WHERE nombre='".$nombre."'";
	    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

	    		if ($count > 0) return "Ya existe una etapa de intimacion con ese nombre";

	    		$sql = "INSERT INTO intima_tetapa(cod,nombre,genauto,est,fchmod,usrmod) " .
	    				"VALUES (".$cod.",'".$nombre."',".$genauto.",".$est.",current_timestamp,".Yii::$app->user->id.")";
	    	}else {

				$sql = "SELECT nombre FROM intima_tetapa WHERE cod=".$cod;
	    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

	    		if($nombreSql != $nombre){

	    			$sql = "SELECT COUNT(*) FROM intima_tetapa WHERE nombre='".$nombre."'";
	    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
	    		}

	    		if ($count > 0) return "Ya existe una estapa de intimacion con ese nombre";

	    		$sql = "UPDATE intima_tetapa";
	    		$sql .=	" SET nombre = '".$nombre."',genauto=".$genauto.",est=".$est.",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
	    		$sql .= " where cod=".$cod;
	    	}

	    	$cmd = Yii :: $app->db->createCommand($sql);
			$rowCount = $cmd->execute();

			if ($rowCount > 0)
			{
				return "";

			} else {

				return "Ocurrio un error al intentar grabar en la BD.";
			}

	  }


  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
  //---------------------------------------------TABLA AUXULIAR HONORARIOS JUDICIALES--------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------

 		public function grabarTablaAuxJudiHono($consulta,$est,$supuesto,$deuda_desde,$deuda_hasta,$hono_min,$hono_porc,$gastos){

				$count="";

				if ($consulta == 0)
		    	{
		    		$sql = "SELECT COUNT(*) FROM judi_hono WHERE est='".$est."' and supuesto=".$supuesto." and deuda_desde=".$deuda_desde." and deuda_hasta=".$deuda_hasta;
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "Ya existe un honorario judicial de ese tipo";

		    		$est = strtoupper($est);

		    		$sql = "INSERT INTO judi_hono(est,supuesto,deuda_desde,deuda_hasta,hono_min,hono_porc,gastos,fchmod,usrmod) " .
		    				"VALUES ('".$est."',".$supuesto.",".$deuda_desde.",".$deuda_hasta.",".$hono_min.",".$hono_porc.",".$gastos.",current_timestamp,".Yii::$app->user->id.")";
		    	}else {

		    		$sql = "UPDATE judi_hono";
		    		$sql .=	" SET hono_min = '".$hono_min."',hono_porc='".$hono_porc."',gastos=".$gastos.",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
		    		$sql .= " where est='".$est."' and supuesto=".$supuesto." and deuda_desde=".$deuda_desde." and deuda_hasta=".$deuda_hasta;
		    	}

		    	$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();

				if ($rowCount > 0)
				{
					return "";

				} else {

					return "Ocurrio un error al intentar grabar en la BD.";
				}
		}

		public function borrarTablaAuxJudiHono($est,$supuesto,$deuda_desde,$deuda_hasta){

			    $sql = "DELETE FROM judi_hono";
				$sql .= " WHERE est='".$est."' and supuesto=".$supuesto." and deuda_desde=".$deuda_desde." and deuda_hasta=".$deuda_hasta;
				try{
					$cmd = Yii :: $app->db->createCommand($sql);
					$cmd->execute();
			 	}
			 	catch(\Exception $e){
			 		$validar = strstr($e->getMessage(), 'The SQL being', true);
					$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
			 		return "<li>".$validar."</li>";
			 	}
		}

  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
  //---------------------------------------------TABLA AUXULIAR TIPOS DE INFRACCIONES--------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


	  	 	public function grabarTablaAuxJuzTInfrac($consulta,$cod,$nombre,$origen,$norma,$item_id,$art,$inc,$multa_min,$multa_max){

				$count="";

				if ($consulta == 0)
		    	{
		    		$sql = "SELECT COUNT(*) FROM juz_tinfrac WHERE nombre='".$nombre."'";
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "Ya existe un tipo de infraccion con ese nombre";

		    		$sql = "select MAX(infrac_id) from juz_tinfrac";
			    	$cod = Yii::$app->db->createCommand($sql)->queryScalar() + 1;

		    		$sql = "INSERT INTO juz_tinfrac(infrac_id,nombre,origen,norma,item_id,art,inc,multa_min,multa_max,est,fchmod,usrmod) " .
		    				"VALUES (".$cod.",'".$nombre."',".$origen.",'".$norma."',".$item_id.",".$art.",'".$inc."',".$multa_min.",".$multa_max.",'A',current_timestamp,".Yii::$app->user->id.")";
		    	}else {

					$sql = "SELECT nombre FROM juz_tinfrac WHERE infrac_id=".$cod;
		    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if($nombreSql != $nombre){

		    			$sql = "SELECT COUNT(*) FROM juz_tinfrac WHERE nombre='".$nombre."'";
		    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
		    		}

		    		if ($count > 0) return "Ya existe una tipo de infraccion con ese nombre";

		    		$sql = "UPDATE juz_tinfrac";
		    		$sql .=	" SET nombre = '".$nombre."',norma='".$norma."',origen=".$origen.",item_id=".$item_id.",art=".$art.",inc='".$inc."',multa_min=".$multa_min.",multa_max=".$multa_max.",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
		    		$sql .= " where infrac_id=".$cod;
		    	}

		    	$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();

				if ($rowCount > 0)
				{
					return "";

				} else {

					return "Ocurrio un error al intentar grabar en la BD.";
				}
		}

	  public function borrarTablaAuxJuzTInfrac($cod){

			    $sql = "UPDATE juz_tinfrac";
			    $sql .= " SET est='B'";
				$sql .= " WHERE infrac_id=".$cod;
				try{
					$cmd = Yii :: $app->db->createCommand($sql);
					$cmd->execute();
			 	}
			 	catch(\Exception $e){
			 		$validar = strstr($e->getMessage(), 'The SQL being', true);
					$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
			 		return "<li>".$validar."</li>";
			 	}
		}


  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
 //---------------------------------------------TABLA AUXULIAR TIPOS DE TEXTO----------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 		public function grabarTablaAuxJuzgadoTexto($consulta,$accion,$tipo_fallo,$origen,$texto_id,$texto_id_pie){

				$count="";

				if ($consulta == 0)
		    	{
		    		$sql = "SELECT COUNT(*) FROM juz_ttexto WHERE accion='".$accion."' and tipo_fallo=".$tipo_fallo." and origen=".$origen;
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "Ya existe un texto de ese tipo";

		    		$sql = "INSERT INTO juz_ttexto(accion,tipo_fallo,origen,texto_id,texto_id_pie,fchmod,usrmod) " .
		    				"VALUES ('".$accion."',".$tipo_fallo.",".$origen.",".$texto_id.",".$texto_id_pie.",current_timestamp,".Yii::$app->user->id.")";
		    	}else {

		    		$sql = "UPDATE juz_ttexto";
		    		$sql .=	" SET texto_id = ".$texto_id.",texto_id_pie=".$texto_id_pie.",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
		    		$sql .= " where accion='".$accion."' and tipo_fallo=".$tipo_fallo." and origen=".$origen;
		    	}

		    	$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();

				if ($rowCount > 0)
				{
					return "";

				} else {

					return "Ocurrio un error al intentar grabar en la BD.";
				}
		}

		public function borrarTablaAuxJuzgadoTexto($accion,$tipo_fallo,$origen){

			    $sql = "DELETE FROM juz_ttexto";
				$sql .= " WHERE accion='".$accion."' and tipo_fallo=".$tipo_fallo." and origen=".$origen;
				try{
					$cmd = Yii :: $app->db->createCommand($sql);
					$cmd->execute();
			 	}
			 	catch(\Exception $e){
			 		$validar = strstr($e->getMessage(), 'The SQL being', true);
					$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
			 		return "<li>".$validar."</li>";
			 	}
		}


  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
 //-------------------------------------TABLA AUXULIAR CATEGORIA INSCRIPCION A TRIBUTOS------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 		 public function grabarTablaAuxTribInscripCat($consulta,$trib_id,$cat,$nombre){

				$count="";

				if ($consulta == 0)
		    	{
		    		$sql = "SELECT COUNT(*) FROM objeto_trib_cat WHERE trib_id=".$trib_id." and cat='".$cat."'";
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "Ya existe una categoria de ese tipo";

		    		$sql = "SELECT COUNT(*) FROM objeto_trib_cat WHERE nombre='".$nombre."'";
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "Ya existe una categoria de ese nombre";

		    		$sql = "INSERT INTO objeto_trib_cat(trib_id,cat,nombre,fchmod,usrmod) " .
		    				"VALUES (".$trib_id.",'".$cat."','".$nombre."',current_timestamp,".Yii::$app->user->id.")";
		    	}else {

		    		$sql = "SELECT COUNT(*) FROM objeto_trib_cat WHERE nombre='".$nombre."'";
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "Ya existe una categoria de ese nombre";

		    		$sql = "UPDATE objeto_trib_cat";
		    		$sql .=	" SET nombre ='".$nombre."',fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
		    		$sql .= " where trib_id=".$trib_id." and cat='".$cat."'";
		    	}

		    	$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();

				if ($rowCount > 0)
				{
					return "";

				} else {

					return "Ocurrio un error al intentar grabar en la BD.";
				}
		}

		public function borrarTablaAuxTribInscripCat($trib_id,$cat){

			    $sql = "DELETE FROM objeto_trib_cat";
				$sql .= " WHERE trib_id=".$trib_id." and cat='".$cat."'";
				try{
					$cmd = Yii :: $app->db->createCommand($sql);
					$cmd->execute();
			 	}
			 	catch(\Exception $e){
			 		$validar = strstr($e->getMessage(), 'The SQL being', true);
					$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
			 		return "<li>".$validar."</li>";
			 	}
		}

		public function buscarTrubuto($cond){

		$sql = "select objeto_trib_cat.trib_id,objeto_trib_cat.cat,objeto_trib_cat.nombre,(u.nombre || ' - ' || to_char(objeto_trib_cat.fchmod,'dd/mm/yyyy')) as modif";
        $sql .= " from objeto_trib_cat, sam.sis_usuario u";
        $sql .= " where u.usr_id = objeto_trib_cat.usrmod";

    	//$sql = 'Select * from banco';

        if ($cond != "") $sql = $sql.' and '.$cond;

        //echo $sql;
        //exit();

        $dataProvider = new SqlDataProvider([
            'sql' => $sql
        ]);
        return $dataProvider;

		}


  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
 //-------------------------------------TABLA AUXULIAR OBRAS PART. PROFESIONALES-------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 	     public function grabarTablaAuxObrasPrivProf($consulta,$cod,$nombre,$ttitu1,$ttitu1_matric,$ttitu1_facu,$ttitu2,$ttitu2_matric,$ttitu2_facu,
 	     $num,$tdoc,$ndoc,$dom_part,$tel_part,$dom_prof,$tel_prof,$mail,$carnet_gestor,$matric_muni,$cuit,$expe,$es_cons,$es_empre,$contacto,$obs,$fchbaja,$motivo_baja,$fchultpago,$anioultpago){

				$count="";

				if ($consulta == 0)
		    	{
		    		$sql = "SELECT COUNT(*) FROM op_prof WHERE ttitu1_matric='".$ttitu1_matric."'";
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "El codigo de matricula de titulo ingresado en el campo matricula 1 ya existe";

		    		$sql = "SELECT COUNT(*) FROM op_prof WHERE ttitu2_matric='".$ttitu2_matric."'";
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "El codigo de matricula de titulo ingresado en el campo matricula 2 ya existe";

		    		$sql = "SELECT COUNT(*) FROM op_prof WHERE ndoc=".$ndoc;
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "El D.N.I. ingresado ya existe";

		    		$sql = "SELECT COUNT(*) FROM op_prof WHERE cuit='".$cuit."'";
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "El cuit ingresado ya existe";

		    		$sql = "select MAX(prof_id) from op_prof";
			    	$cod = Yii::$app->db->createCommand($sql)->queryScalar() + 1;

		    		$sql = "INSERT INTO op_prof(prof_id,nombre,ttitu1,ttitu1_matric,ttitu1_facu,ttitu2,ttitu2_matric,ttitu2_facu,
 	     					num,tdoc,ndoc,dom_part,tel_part,dom_prof,tel_prof,mail,carnet_gestor,matric_muni,cuit,expe,es_cons,es_empre,
 	     					contacto,est,obs,fchbaja,motivo_baja,fchultpago,anioultpago,fchmod,usrmod)
		    				VALUES (".$cod.",'".$nombre."',".$ttitu1.",'".$ttitu1_matric."','".$ttitu1_facu."',".$ttitu2.",'".$ttitu2_matric."','".$ttitu2_facu."','".
 	     							$num."',".$tdoc.",".$ndoc.",'".$dom_part."','".$tel_part."','".$dom_prof."','".$tel_prof."','".$mail."','".$carnet_gestor."',".$matric_muni.
									",'".$cuit."','".$expe."','".$es_cons."','".$es_empre."','".$contacto."','A','".$obs."','".$fchbaja."','".$motivo_baja."','".$fchultpago."',".
									$anioultpago.",current_timestamp,".Yii::$app->user->id.")";
		    	}else {

					$sql = "SELECT ttitu1_matric FROM op_prof WHERE prof_id=".$cod;
		    		$ttitu1Matri = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if($ttitu1Matri != $ttitu1_matric){

		    			$sql = "SELECT COUNT(*) FROM op_prof WHERE ttitu1_matric='".$ttitu1_matric."'";
		    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
		    		}

		    		if ($count > 0) return "La matricula de titulo ingresado en el campo matricula 1 ya existe";


					$sql = "SELECT ttitu2_matric FROM op_prof WHERE prof_id=".$cod;
		    		$ttitu2Matri = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if($ttitu2Matri != $ttitu2_matric){

		    			$sql = "SELECT COUNT(*) FROM op_prof WHERE ttitu1_matric='".$ttitu2_matric."'";
		    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
		    		}

		    		if ($count > 0) return "La matricula de titulo ingresado en el campo matricula 2 ya existe";


		    		$sql = "SELECT ndoc FROM op_prof WHERE prof_id=".$cod;
		    		$documento = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if($documento != $ndoc){

		    			$sql = "SELECT COUNT(*) FROM op_prof WHERE ndoc=".$ndoc;
		    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
		    		}

		    		if ($count > 0) return "El D.N.I. ingresado ya existe";


		    		$sql = "SELECT cuit FROM op_prof WHERE prof_id=".$cod;
		    		$numCuit = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if($numCuit != $cuit){

		    			$sql = "SELECT COUNT(*) FROM op_prof WHERE cuit='".$cuit."'";
		    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
		    		}

		    		if ($count > 0) return "El cuit ingresado ya existe";


		    		$sql = "UPDATE op_prof";
		    		$sql .=	" SET nombre='".$nombre."',ttitu1=".$ttitu1.",ttitu1_matric='".$ttitu1_matric."',ttitu1_facu='".$ttitu1_facu."',ttitu2=".$ttitu2.",ttitu2_matric='".$ttitu2_matric."',ttitu2_facu='".$ttitu2_facu."',".
 							"num='".$num."',tdoc=".$tdoc.",ndoc='".$ndoc."',dom_part='".$dom_part."',tel_part='".$tel_part."',dom_prof='".$dom_prof."',tel_prof='".$tel_prof."',mail='".$mail."',carnet_gestor='".$carnet_gestor."',matric_muni=".$matric_muni.
							",cuit='".$cuit."',expe='".$expe."',es_cons='".$es_cons."',es_empre='".$es_empre."',contacto='".$contacto."',obs='".$obs."',fchbaja='".$fchbaja."',motivo_baja='".$motivo_baja."',fchultpago='".$fchultpago."',".
							"anioultpago=".$anioultpago.",fchmod=current_timestamp,usrmod=".Yii::$app->user->id." WHERE prof_id=".$cod;
		    	}

		    	$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();

				if ($rowCount > 0)
				{
					return "";

				} else {

					return "Ocurrio un error al intentar grabar en la BD.";
				}
		}

		public function borrarTablaAuxObrasPrivProf($cod){

			    $sql = "UPDATE op_prof";
			    $sql .= " SET est='B'";
				$sql .= " WHERE prof_id=".$cod;
				try{
					$cmd = Yii :: $app->db->createCommand($sql);
					$cmd->execute();
			 	}
			 	catch(\Exception $e){
			 		$validar = strstr($e->getMessage(), 'The SQL being', true);
					$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
			 		return "<li>".$validar."</li>";
			 	}
		}

  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
 //----------------------------------------------TABLA AUXULIAR TIPOS DE RECLAMO-------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 		public function grabarTablaAuxReclamosTipo($consulta,$cod,$nombre,$ofi_id_alta,$ofi_id,$req_inm,$req_sancion,$plazo){

				$count="";

				if ($consulta == 0)
		    	{
		    		$sql = "SELECT COUNT(*) FROM recl_tipo WHERE cod=".$cod;
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "Ya existe un tipo de reclamo con ese codigo";

		    		$sql = "SELECT COUNT(*) FROM recl_tipo WHERE nombre='".$nombre."'";
		    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if ($count > 0) return "Ya existe un tipo de reclamo con ese nombre";


		    		$sql = "INSERT INTO recl_tipo(cod,nombre,ofi_id_alta,ofi_id,req_inm,req_sancion,plazo,fchmod,usrmod) " .
		    				"VALUES (".$cod.",'".$nombre."',".$ofi_id_alta.",".$ofi_id.",".$req_inm.",".$req_sancion.",".$plazo.",current_timestamp,".Yii::$app->user->id.")";
		    	}else {

					$sql = "SELECT nombre FROM recl_tipo WHERE cod=".$cod;
		    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

		    		if($nombreSql != $nombre){

		    			$sql = "SELECT COUNT(*) FROM recl_tipo WHERE nombre='".$nombre."'";
		    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
		    		}

		    		if ($count > 0) return "Ya existe una tipo de reclamo con ese nombre";

		    		$sql = "UPDATE recl_tipo";
		    		$sql .=	" SET nombre = '".$nombre."',ofi_id_alta=".$ofi_id_alta.",ofi_id=".$ofi_id.",req_inm=".$req_inm.",req_sancion=".$req_sancion.",plazo=".$plazo.",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
		    		$sql .= " where cod=".$cod;
		    	}

		    	$cmd = Yii :: $app->db->createCommand($sql);
				$rowCount = $cmd->execute();

				if ($rowCount > 0)
				{
					return "";

				} else {

					return "Ocurrio un error al intentar grabar en la BD.";
				}
		}


 	   public function getNombreOficina($id_oficina) {

			$nombre = "";

			if ($id_oficina != ""){

				$sql = "SELECT nombre FROM sam.muni_oficina WHERE ofi_id ='". $id_oficina."'";
					$nombre = Yii :: $app->db->createCommand($sql)->queryScalar();

				}
				return $nombre;
	   }


  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
  //----------------------------------------------TABLA AUXULIAR TIPO DE MODELO DE AFORO-----------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 	/*public function grabarTablaAuxRodadoAforoModelo($consulta,$aforo_id,$origen,$marca,$tipo,$modelo,$marca_nom,$tipo_nom,$modelo_nom){

		$marca_nom = strtoupper($marca_nom);
		$modelo_nom = strtoupper($modelo_nom);
		$tipo_nom = strtoupper($tipo_nom);

 		$count="";

		if ($consulta == 0)
    	{
    		$sql = "SELECT COUNT(*) FROM rodado_aforo WHERE origen='".$aforo_id."'";
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "El rodado aforo ingresado ya existe";

    		$sql = "INSERT INTO rodado_aforo(aforo_id,origen,marca,tipo,modelo,marca_nom,tipo_nom,modelo_nom,fchmod,usrmod)
    				VALUES ('".$aforo_id."','".$origen."','".$marca."','".$tipo."','".$modelo."','".$marca_nom."','".$tipo_nom."','".$modelo_nom."',current_timestamp,".Yii::$app->user->id.")";
    	}else {

    		$sql = "UPDATE rodado_aforo";
    		$sql .=	" SET marca_nom='".$marca_nom."',tipo_nom='".$tipo_nom."',modelo_nom='".$modelo_nom."',fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
    		$sql .=	" WHERE aforo_id='".$aforo_id."'";
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}


 	}


	public function borrarTablaAuxRodadoAforoModelo($aforo_id){

	    $sql = "DELETE FROM rodado_aforo";
		$sql .= " WHERE aforo_id='".$aforo_id."'";
		try{
			$cmd = Yii :: $app->db->createCommand($sql);
			$cmd->execute();
	 	}
	 	catch(\Exception $e){
	 		$validar = strstr($e->getMessage(), 'The SQL being', true);
			$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
	 		return "<li>".$validar."</li>";
	 	}
		}
 */

 	public function buscarModeloAforo($fabrica,$aforo,$marca_cod,$tipo_cod,$modelo_cod,$marca_nom,$tipo_nom,$modelo_nom){

 		$condicion = '';

 		if(!empty($fabrica)){

 			$condicion = strtoupper($fabrica);
 			$condicion = "fabr like '%".$condicion."%'";
 		}

        if(!empty($aforo)){

 			$condicion = strtoupper($aforo);
 			$condicion = "aforo_id like '%".$condicion."%'";
 		}

 		if(!empty($marca_cod)){

 			$c = "marca Like '%" . strtoupper($marca_cod) . "%'";
 			$condicion .= (empty($condicion) ? $c : " And $c");
 		}

 		 if(!empty($tipo_cod)){

 			$c = "tipo Like '%%" . strtoupper($tipo_cod) . "'";
 			$condicion .= (empty($condicion) ? $c : " And $c");
 		}

 		if(!empty($modelo_cod)){

			$c = "modelo Like '%" . strtoupper($modelo_cod) . "%'";
 			$condicion .= (empty($condicion) ? $c : " And $c");
 		}

 		if(!empty($marca_nom)){

 			$c = "marca_nom Like '%" . strtoupper($marca_nom) . "%'";
 			$condicion .= (empty($condicion) ? $c : " And $c");
 		}

 		 if(!empty($tipo_nom)){

			$c = "tipo_nom Like '%" . strtoupper($tipo_nom) . "%'";
 			$condicion .= (empty($condicion) ? $c : " And $c");
 		}

 		if(!empty($modelo_nom)){

 			$c = "modelo_nom Like '%" . strtoupper($modelo_nom) . "%'";
 			$condicion .= (empty($condicion) ? $c : " And $c");
 		}

    	// if(isset($origen)){
    	//  	if ($origen == 'SD') {
        //
    	//  		$c = "(origen = 'I' or origen = 'N')";
    	//  		$condicion .= empty($condicion) ? $c : " And $c";
 	// 		}else if ($origen == 'I'){
        //
 	// 			$c = "origen = 'I'";
 	// 			$condicion .= empty($condicion) ? $c : " And $c";
 	// 		}else if ($origen == 'N'){
        //
 	// 			$c = "origen = 'N'";
 	// 			$condicion .= empty($condicion) ? $c : " And $c";
 	// 		}
    	// }else{
    	// 	$c = "(origen = 'I' or origen = 'N')";
    	//  	$condicion .= empty($condicion) ? $c : " And $c";
    	// }

        $c = "(origen = 'I' or origen = 'N')";
    	//  	$condicion .= empty($condicion) ? $c : " And $c";

 		$sql = "SELECT aforo_id, origen, fabr, marca_id_nom, tipo_id_nom, modelo_id_nom, anio_min, anio_max, valor_max" .
 				" FROM v_rodado_aforo";

 		if(!empty($condicion)) $sql .= " Where $condicion";

 		$models = Yii::$app->db->createCommand($sql)->queryAll();
 		$count = count($models);

		$dataProvider = new ArrayDataProvider([
            'allModels' => $models,
            'totalCount' => $count,
            'key' => 'aforo_id',
			'pagination'=> [
				'pageSize'=>50,
				'totalCount' => $count
			],
			'sort' => [
				'attributes' => [

					'aforo_id' => [
						'default' => SORT_DESC
					],
					'origen',
					'anio_min',
					'anio_max',
					'marca_id_nom' => [
						'asc' => ['marca_nom' => SORT_ASC],
						'desc' => ['marca_nom' => SORT_DESC]
					],
					'tipo_id_nom' => [
						'asc' => ['tipo_nom' => SORT_ASC],
						'desc' => ['tipo_nom' => SORT_DESC]
					],
					'modelo_id_nom' => [
						'asc' => ['modelo_nom' => SORT_ASC],
						'desc' => ['modelo_nom' => SORT_DESC]
					]
				],
				'defaultOrder' => [
					'aforo_id' => SORT_DESC
				]
			]
		]);
        return $dataProvider;

 	}


  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
  //-----------------------------------------------TABLA AUXULIAR MODELO RODADO--------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------



 	public function grabarTablaAuxRodadoModelo($consulta,$cod,$nombre,$marca,$cat){

		$count="";

		if ($consulta == 0)
    	{
    		$sql = "SELECT COUNT(*) FROM rodado_modelo WHERE nombre='".$nombre."'";
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "Ya existe un modelo con ese nombre";

    		$sql = "select MAX(cod) from rodado_modelo";
	    	$cod = Yii::$app->db->createCommand($sql)->queryScalar() + 1;

    		$sql = "INSERT INTO rodado_modelo(cod,nombre,marca,cat,fchmod,usrmod) " .
    				"VALUES (".$cod.",'".$nombre."',".$marca.",".$cat.",current_timestamp,".Yii::$app->user->id.")";

    	}else {

			$sql = "SELECT nombre FROM rodado_modelo WHERE cod=".$cod;
    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

    		if($nombreSql != $nombre){

    			$sql = "SELECT COUNT(*) FROM rodado_modelo WHERE nombre='".$nombre."'";
    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
    		}

    		if ($count > 0) return "Ya existe un modelo con ese nombre";

    		$sql = "UPDATE rodado_modelo";
    		$sql .=	" SET nombre = '".$nombre."',marca=".$marca.",cat=".$cat.",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
    		$sql .= " where cod=".$cod;
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}
 	}

 	public function getNombreMarca($idMarca) {

			$cmd = "";

			if ($idMarca != ""){
				$sql = "SELECT nombre FROM rodado_marca WHERE cod =".$idMarca;
				$cmd = Yii :: $app->db->createCommand($sql)->queryScalar();

			}
			return $cmd;
	}

 	public function buscarModelo($modelo,$marca){

 		 if($marca==""){
 			$marca = "rodado_marca.nombre!='".$marca."'";
 		}else{
 			$marca = strtoupper($marca);
 			$marca = "rodado_marca.nombre like '%".$marca."%'";
 		}

 		if($modelo==""){
 			$modelo = "rodado_modelo.nombre!='".$modelo."'";
 		}else{
 			$modelo = strtoupper($modelo);
 			$modelo = "rodado_modelo.nombre like '%".$modelo."%'";
 		}

        $sql = "SELECT rodado_modelo.cod,rodado_modelo.nombre,rodado_modelo.marca,rodado_modelo.cat,(u.nombre || ' - ' || to_char(rodado_modelo.fchmod,'dd/mm/yyyy')) as modif";
		$sql .= " FROM rodado_modelo, rodado_marca,sam.sis_usuario u";
		$sql .= " WHERE rodado_modelo.marca=rodado_marca.cod AND ".$marca." AND ".$modelo." AND u.usr_id = rodado_modelo.usrmod";

		$pag = "SELECT count(*)";
		$pag .= " FROM rodado_modelo, rodado_marca,sam.sis_usuario u";
		$pag .= " WHERE rodado_modelo.marca=rodado_marca.cod AND ".$marca." AND ".$modelo." AND u.usr_id = rodado_modelo.usrmod";

		$count = Yii::$app->db->createCommand($pag)->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => (int)$count,
            'pagination'=> [
			'pageSize'=>20
			]
        ]);
        return $dataProvider;
 	}

  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
  //-------------------------------------------TABLA AUXULIAR DELEGACION DE RODADO-----------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 	public function grabarTablaAuxRodadoDeleg($consulta,$cod,$nombre,$encargado,$prov_id,$localidad,$domi,$cp,$tel,$fax){

 		$nombre = strtoupper($nombre);
 		$encargado = strtoupper($encargado);
 		$localidad = strtoupper($localidad);
 		$domi = strtoupper($domi);

 		$count="";

		if ($consulta == 0)
    	{
    		$sql = "SELECT COUNT(*) FROM rodado_tdeleg WHERE nombre='".$nombre."'";
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "Ya existe una delegacion con ese nombre";

    		$sql = "select MAX(cod) from rodado_tdeleg";
	    	$cod = Yii::$app->db->createCommand($sql)->queryScalar() + 1;

    		$sql = "INSERT INTO rodado_tdeleg(cod,nombre,encargado,prov_id,localidad,domi,cp,tel,fax,fchmod,usrmod) " .
    				"VALUES (".$cod.",'".$nombre."','".$encargado."',".$prov_id.",'".$localidad."','".$domi."',".$cp.",'".$tel."','".$fax."',current_timestamp,".Yii::$app->user->id.")";
    	}else {

			$sql = "SELECT nombre FROM rodado_tdeleg WHERE cod=".$cod;
    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

    		if($nombreSql != $nombre){

    			$sql = "SELECT COUNT(*) FROM rodado_tdeleg WHERE nombre='".$nombre."'";
    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
    		}

    		if ($count > 0) return "Ya existe una delegacion con ese nombre";

    		$sql = "UPDATE rodado_tdeleg";
    		$sql .=	" SET nombre = '".$nombre."',encargado='".$encargado."',prov_id=".$prov_id.",localidad='".$localidad."',domi='".$domi."'" .
    				",cp='".$cp."',tel='".$tel."',fax='".$fax."',fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
    		$sql .= " where cod=".$cod;
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}

 	}


  //-----------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
  //----------------------------------------------TABLA AUXULIAR VALORES DE RODADO-----------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 	public function grabarTablaAuxRodadoVal($consulta,$anioval,$gru,$anio,$pesodesde,$pesohasta,$valor){

 		$count="";

		if ($consulta == 0)
    	{
    		$sql = "SELECT COUNT(*) FROM rodado_val WHERE anioval=".$anioval." AND gru=".$gru." AND anio=".$anio." AND pesodesde=".$pesodesde." " .
    				"AND pesohasta=".$pesohasta;
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "Ya existe un valor de mejora con esos datos";

    		$sql = "INSERT INTO rodado_val(anioval,gru,anio,pesodesde,pesohasta,valor,fchmod,usrmod) " .
    				"VALUES (".$anioval.",".$gru.",".$anio.",".$pesodesde.",".$pesohasta.",".$valor.",current_timestamp,".Yii::$app->user->id.")";
    	}else {

    		$sql = "UPDATE rodado_val";
    		$sql .=	" SET valor = ".$valor.",fchmod=current_timestamp,usrmod=".Yii::$app->user->id;
    		$sql .= " where anioval=".$anioval." AND gru=".$gru." AND anio=".$anio." AND pesodesde=".$pesodesde." AND pesohasta=".$pesohasta;
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}
 	}

 	public function borrarTablaAuxRodadoVal($anioval,$gru,$anio,$pesodesde,$pesohasta){

	    $sql = "DELETE FROM rodado_val";
		$sql .= " WHERE anioval=".$anioval." AND gru=".$gru." AND anio=".$anio." AND pesodesde=".$pesodesde." AND pesohasta=".$pesohasta;
		try{
			$cmd = Yii :: $app->db->createCommand($sql);
			$cmd->execute();
	 	}
	 	catch(\Exception $e){
	 		$validar = strstr($e->getMessage(), 'The SQL being', true);
			$validar = substr($validar, strlen('SQLSTATE[P0001]: Raise exception: 7'));
	 		return "<li>".$validar."</li>";
	 	}
		}


	public function buscarValorDeMejora($anioval,$gru,$anio){

 		if($anioval==""){
 			$sql = "select MAX(anioval) from rodado_val";
	    	$anioval = Yii::$app->db->createCommand($sql)->queryScalar();
 			$anioval = "rodado_val.anioval=".$anioval;
 		}else{
 			$anioval = "rodado_val.anioval=".$anioval;
 		}

 		 if($gru==""){
 		 	$gru = "rodado_val.gru!=0";
 		}else{
 			$gru = "rodado_val.gru=".$gru;
 		}

 		if($anio==""){
 			$anio = "rodado_val.anio!=0";
 		}else{
 			$anio = "rodado_val.anio=".$anio;
 		}

    	$sql = "select count(*) from rodado_val";

    	$count = Yii::$app->db->createCommand($sql)->queryScalar();

 		$sql = "SELECT rodado_val.anioval,rodado_val.gru,rodado_val.anio,rodado_val.pesodesde," .
 				"rodado_val.pesohasta,rodado_val.valor,(u.nombre || ' - ' || to_char(rodado_val.fchmod,'dd/mm/yyyy')) as modif" .
 				" FROM rodado_val,sam.sis_usuario u " .
 				"WHERE ".$anioval." AND ".$gru." AND ".$anio." AND u.usr_id = rodado_val.usrmod";

		$dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'totalCount' => (int)$count,
			'pagination'=> [
			'pageSize'=>20,
        ]]);
        return $dataProvider;

 	}

 //------------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
 //----------------------------------------------TABLA AUXULIAR TIPOS DE VINCULOS DE PERSONAS------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 		public function grabarTablaAuxTVinc($consulta,$cod,$nombre){

 		$count="";

		if ($consulta == 0)
    	{
    		$sql = "SELECT COUNT(*) FROM persona_trela WHERE nombre='".$nombre."'";
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "Ya existe un tipo de vinculo con ese nombre";

    		$sql = "select MAX(cod) from persona_trela";
	    	$cod = Yii::$app->db->createCommand($sql)->queryScalar() + 1;

    		$sql = "INSERT INTO persona_trela(cod,nombre,fchmod,usrmod) " .
    				"VALUES (".$cod.",'".$nombre."',current_timestamp,".Yii::$app->user->id.")";
    	}else {

			$sql = "SELECT nombre FROM persona_trela WHERE cod=".$cod;
    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

    		if($nombreSql != $nombre){

    			$sql = "SELECT COUNT(*) FROM persona_trela WHERE nombre='".$nombre."'";
    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
    		}

    		if ($count > 0) return "Ya existe un tipo de vinculo con ese nombre";

    		$sql = "UPDATE persona_trela";
    		$sql .=	" SET nombre = '".$nombre."'";
    		$sql .= " where cod=".$cod;
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}

 	}
 //------------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
  //---------------------------------------TABLA AUXULIAR TIPOS DE VINCULOS DE OBJETOS-------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 		public function grabarTablaAuxObjTVinc($consulta,$cod,$nombre,$tobj){

 		$count="";

		if ($consulta == 0)
    	{
    		$sql = "SELECT COUNT(*) FROM persona_tvinc WHERE nombre='".$nombre."'";
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "Ya existe un tipo de vinculo con ese nombre";

    		$sql = "select MAX(cod) from persona_tvinc";
	    	$cod = Yii::$app->db->createCommand($sql)->queryScalar() + 1;

    		$sql = "INSERT INTO persona_tvinc(cod,nombre,tobj,fchmod,usrmod) " .
    				"VALUES (".$cod.",'".$nombre."',".$tobj.",current_timestamp,".Yii::$app->user->id.")";
    	}else {

			$sql = "SELECT nombre FROM persona_tvinc WHERE cod=".$cod;
    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

    		if($nombreSql != $nombre){

    			$sql = "SELECT COUNT(*) FROM persona_tvinc WHERE nombre='".$nombre."'";
    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
    		}

    		if ($count > 0) return "Ya existe un tipo de vinculo con ese nombre";

    		$sql = "UPDATE persona_tvinc";
    		$sql .=	" SET nombre = '".$nombre."',tobj=".$tobj;
    		$sql .= " where cod=".$cod;
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}
 	}

 //------------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
 //----------------------------------------------TABLA AUXULIAR TIPOS DE EMPLEADOS-----------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------

	public function CargarTablaTEmple( $condicion = '' )
    {
    	$sql = "select count(*) from plan_temple as t";

    	if( $condicion != '' ) $sql .= " Where $condicion";

    	$count = Yii::$app->db->createCommand( $sql )->queryScalar();

        $sql = "select t.*,t.caja_id cod, u.nombre || ' - ' || to_char(t.fchmod,'dd/mm/yyyy') as modif, c.nombre as caja_nom,t.caja_id || ' - ' || c.nombre as caja";
        $sql .= " from plan_temple t ";
		$sql .= " inner join sam.sis_usuario u on t.usrmod=u.usr_id ";
		$sql .= " inner join caja c on t.caja_id = c.caja_id ";
		if ($condicion != '') $sql .= " Where $condicion ";

        $data = Yii::$app->db->createCommand( $sql )->queryAll();

        $dataProvider = new ArrayDataProvider([

            'allModels' => $data,
            'key'       => 'cod',
            'totalCount' => (int)$count,
            'sort' => [
                'attributes' => [
                    'nombre',
                    'cod',
                    'ofi_id'
                ],
                'defaultOrder' => [
                    'nombre' => SORT_ASC,
                ]
            ],
			'pagination'=> [
				'pageSize'=>200,
			],
        ]);

        return $dataProvider;

    }
 		
		public function grabarTablaAuxTemple($consulta,$caja_id,$cod,$nombre){

 		$count="";

		if ($consulta == 0)
    	{
    		$sql = "SELECT COUNT(*) FROM plan_temple WHERE nombre='".$nombre."'";
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "Ya existe un tipo de empelado con ese nombre";

    		$sql = "select MAX(cod) from plan_temple";
	    	$cod = Yii::$app->db->createCommand($sql)->queryScalar() + 1;

    		$sql = "INSERT INTO plan_temple(cod,caja_id,nombre,fchmod,usrmod) " .
    				"VALUES (".$cod.",".$caja_id.",'".$nombre."',current_timestamp,".Yii::$app->user->id.")";
    	}else {

			$sql = "SELECT nombre FROM plan_temple WHERE cod=".$cod;
    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

    		if($nombreSql != $nombre){

    			$sql = "SELECT COUNT(*) FROM plan_temple WHERE nombre='".$nombre."'";
    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
    		}

    		if ($count > 0) return "Ya existe un tipo de empelado con ese nombre";

    		$sql = "UPDATE plan_temple";
    		$sql .=	" SET nombre = '".$nombre."'";
    		$sql .= " where cod=".$cod." and caja_id=".$caja_id;
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}
 	}



 //------------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
 //---------------------------------------------------TABLA AUXULIAR MUNI OFICINAS-----------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 	public function grabarTablaAuxOficina($consulta,$cod,$nombre,$resp,$sec_id){

 		$count="";

        if(trim($nombre) == '') return "El nombre es obligatorio.";
        if($sec_id <= 0) return "La secretaría es obligatoria.";

		if ($consulta == 0)
    	{
    		$sql = "SELECT COUNT(*) FROM sam.muni_oficina WHERE nombre='".$nombre."'";
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "Ya existe una oficina con ese nombre";

    		$sql = "select MAX(ofi_id) from sam.muni_oficina";
	    	$cod = Yii::$app->db->createCommand($sql)->queryScalar() + 1;

    		$sql = "INSERT INTO sam.muni_oficina(ofi_id,nombre,resp,sec_id,fchmod,usrmod) " .
    				"VALUES (".$cod.",'".$nombre."','".$resp."',".$sec_id.",current_timestamp,".Yii::$app->user->id.")";
    	}else {

			$sql = "SELECT nombre FROM sam.muni_oficina WHERE ofi_id=".$cod;
    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

    		if($nombreSql != $nombre){

    			$sql = "SELECT COUNT(*) FROM sam.muni_oficina WHERE nombre='".$nombre."'";
    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
    		}

    		if ($count > 0) return "Ya existe una oficina con ese nombre";

    		$sql = "UPDATE sam.muni_oficina";
    		$sql .=	" SET nombre = '".$nombre."',resp='".$resp."',sec_id=".$sec_id;
    		$sql .= " where ofi_id=".$cod;
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}
 	}

	public function CargarTablaOficina( $condicion = '' )
    {
    	$sql = "select count(*) from sam.muni_oficina as t";

    	if( $condicion != '' ) $sql .= " Where $condicion";

    	$count = Yii::$app->db->createCommand( $sql )->queryScalar();

        $sql = "select t.*,t.ofi_id cod, u.nombre || ' - ' || to_char(t.fchmod,'dd/mm/yyyy') as modif, s.nombre as sec_nom";
        $sql .= " from sam.muni_oficina t ";
		$sql .= " inner join sam.sis_usuario u on t.usrmod=u.usr_id ";
		$sql .= " inner join sam.muni_sec s on t.sec_id = s.cod ";
		if ($condicion != '') $sql .= " Where $condicion ";

        $data = Yii::$app->db->createCommand( $sql )->queryAll();

        $dataProvider = new ArrayDataProvider([

            'allModels' => $data,
            'key'       => 'cod',
            'totalCount' => (int)$count,
            'sort' => [
                'attributes' => [
                    'nombre',
                    'cod',
                    'ofi_id'
                ],
                'defaultOrder' => [
                    'nombre' => SORT_ASC,
                ]
            ],
			'pagination'=> [
				'pageSize'=>200,
			],
        ]);

        return $dataProvider;

    }
	
//------------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------
 //---------------------------------------------------TABLA AUXULIAR MUNI SECRETARIA-----------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------


 	public function grabarTablaAuxSecretaria($consulta,$cod,$nombre,$part_id,$part_id2,$part_id3){

 		$count="";

        if(trim($nombre) == '') return "El nombre es obligatorio.";
		if( intVal($part_id) > 0 and in_array(intVal($part_id),[ intVal($part_id2), intVal($part_id3) ]) )
			return "La partida 1 esta repetida";
			
		if( intVal($part_id2) > 0 and in_array(intVal($part_id2),[ intVal($part_id), intVal($part_id3) ]) )
			return "La partida 2 esta repetida";	
			
		if( intVal($part_id3) > 0 and in_array(intVal($part_id3),[ intVal($part_id), intVal($part_id2) ]) )
			return "La partida 3 esta repetida";	
        
		if ($consulta == 0)
    	{
    		$sql = "SELECT COUNT(*) FROM sam.muni_sec WHERE nombre='".$nombre."'";
    		$count = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($count > 0) return "Ya existe una secretaría con ese nombre";

    		$sql = "select MAX(cod) from sam.muni_sec";
	    	$cod = Yii::$app->db->createCommand($sql)->queryScalar() + 1;

    		$sql = "INSERT INTO sam.muni_sec(cod,nombre,part_id,part_id2,part_id3,fchmod,usrmod) " .
    				"VALUES (".$cod.",'".$nombre."',".intVal($part_id).",".intVal($part_id2).",".intVal($part_id3).",current_timestamp,".Yii::$app->user->id.")";
    	}else {

			$sql = "SELECT nombre FROM sam.muni_sec WHERE cod=".$cod;
    		$nombreSql = Yii::$app->db->createCommand($sql)->queryScalar();

    		if($nombreSql != $nombre){

    			$sql = "SELECT COUNT(*) FROM sam.muni_sec WHERE nombre='".$nombre."'";
    			$count = Yii::$app->db->createCommand($sql)->queryScalar();
    		}

    		if ($count > 0) return "Ya existe una secretaría con ese nombre";

    		$sql = "UPDATE sam.muni_sec";
    		$sql .=	" SET nombre = '".$nombre."',part_id=".$part_id.",part_id2=".$part_id2.",part_id3=".$part_id3." where cod=".$cod;
    	}

    	$cmd = Yii :: $app->db->createCommand($sql);
		$rowCount = $cmd->execute();

		if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}
 	}

	public function CargarTablaSecretaria( $condicion = '' )
    {
    	$sql = "select count(*) from sam.muni_sec as t";

    	if( $condicion != '' ) $sql .= " Where $condicion";

    	$count = Yii::$app->db->createCommand( $sql )->queryScalar();

        $sql = "select t.*,p.nropart,p.nombre part_nom,p.formatoaux,p2.nropart nropart2,p2.nombre part_nom2,p2.formatoaux formatoaux2," . 
				"p3.nropart nropart3,p3.nombre part_nom3,p3.formatoaux formatoaux3, u.nombre || ' - ' || to_char(t.fchmod,'dd/mm/yyyy') as modif";
        $sql .= " from sam.muni_sec t ";
		$sql .= " inner join sam.sis_usuario u on t.usrmod=u.usr_id ";
		$sql .= " left join fin.part p on t.part_id = p.part_id ";
		$sql .= " left join fin.part p2 on t.part_id2 = p2.part_id ";
		$sql .= " left join fin.part p3 on t.part_id3 = p3.part_id ";
		if ($condicion != '') $sql .= " Where $condicion ";

        $data = Yii::$app->db->createCommand( $sql )->queryAll();

        $dataProvider = new ArrayDataProvider([

            'allModels' => $data,
            'key'       => 'cod',
            'totalCount' => (int)$count,
            'sort' => [
                'attributes' => [
                    'nombre',
                    'cod',
                    'nropart',
					'part_nom'
                ],
                'defaultOrder' => [
                    'nombre' => SORT_ASC,
                ]
            ],
			'pagination'=> [
				'pageSize'=>200,
			],
        ]);

        return $dataProvider;

    }	

 //------------------------------------------------------------------------------------------------------------------------------------
 //------------------------------------------------------------------------------------------------------------------------------------

    public function borrarTablaAux($cod)
    {
    	if ($this->nombre == "inm_tuso")
    	{
    		$sql = "select count(*) from OP_Expe where uso=".$cod;
    		$cant = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($cant == 0)
    		{
    			$sql = "select count(*) from Inm where uso =".$cod;
    			$cant = Yii::$app->db->createCommand($sql)->queryScalar();
    		}

    		if ($cant > 0) return "No podrá eliminar el tipo de Uso";

    	}else if ($this->nombre == "domi_tcalle")
    	{
    		$sql = "select count(*) from Domi_Calle where tcalle =".$cod;
    		$cant = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($cant > 0) return "No podrá eliminar el tipo de Calle";

    	}else if ($this->nombre == "op_tobra")
    	{
    		$sql = "select count(*) from op_expe where tobra =".$cod;
    		$cant = Yii::$app->db->createCommand($sql)->queryScalar();

    		if ($cant > 0) return "El Tipo de Obra se encuentra vinculado a un Expediente";

    	}

    	$sql = "delete from ".$this->nombre." where ";
    	if ($this->tcod == 'N') $sql .= tablaAux::GetCampoClave($this->nombre)."=".$cod;
    	if ($this->tcod == 'C') $sql .= tablaAux::GetCampoClave($this->nombre)."='".$cod."'";

    	$cmd = Yii::$app->db->createCommand($sql);
    	$rowCount = $cmd->execute();
    	if ($rowCount > 0)
		{
			return "";

		} else {

			return "Ocurrio un error al intentar grabar en la BD.";
		}
    }

    public function GetCampoLong($tabla, $campo)
    {
    	$sql = "Select sam.uf_db_campo_long ('".$tabla."','".$campo."')";

    	$largo = Yii::$app->db->createCommand($sql)->queryScalar();

    	return $largo;
    }

     public function GetCampoTipo($tabla, $campo)
    {
    	$sql = "Select sam.uf_db_campo_type('".$tabla."','".$campo."')";

    	$tipo = Yii::$app->db->createCommand($sql)->queryScalar();

    	return $tipo;
    }

     public function GetCampoDesc($tabla, $id)
    {
    	$sql = "SELECT pg_description.description FROM pg_class FULL JOIN pg_description ON pg_class.relfilenode = pg_description.objoid";
    	$sql .= " WHERE pg_class.relname ='".$tabla."' and pg_description.objsubid=".$id;

    	$tipo = Yii::$app->db->createCommand($sql)->queryScalar();

    	return $tipo;
    }

    private function GetCampoClave($tabla)
    {
    	if($tabla=='sam.muni_oficina') return 'ofi_id';
		if($tabla=='sam.muni_sec') $tabla="muni_sec";
        if(substr($tabla, 0,3) == 'fin') $tabla=substr($tabla, 4);
        if(substr($tabla, 0,2) == 'rh') $tabla=substr($tabla, 3);
    	$sql = "SELECT column_name FROM information_schema.key_column_usage WHERE TABLE_NAME='".$tabla."'";
    	$campoclave = Yii::$app->db->createCommand($sql)->queryScalar();
		
    	return $campoclave;
    }

    public function CargarTercerCampo()
    {
    	$sql = "select count(c.column_name) from information_schema.columns c where UPPER(c.table_name) = upper('".$this->nombre."')";
        $count = Yii::$app->db->createCommand($sql)->queryScalar();

    	if ($count == 5)
    	{
    		$sql = "select c.column_name from information_schema.columns c where c.ordinal_position=3 and UPPER(c.table_name) = upper('".$this->nombre."')";
        	$this->tercercamponom = Yii::$app->db->createCommand($sql)->queryScalar();

        	$this->tercercampotipo = tablaAux::GetCampoTipo($this->nombre,$this->tercercamponom);
        	$this->tercercampolong = tablaAux::GetCampoLong($this->nombre,$this->tercercamponom);
        	$this->tercercampodesc = tablaAux::GetCampoDesc($this->nombre,3);

    	}else{
    		$this->tercercamponom = '';
    		$this->tercercampotipo = '';
    		$this->tercercampolong = '';
    		$this->tercercampodesc = '';
    	}
    }

}
