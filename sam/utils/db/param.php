<?php

namespace app\utils\db;

use Yii;
use yii\base\Component;
use app\utils\db\utb;
use yii\helpers\BaseUrl;

class param extends Component
{
	public $name;
	public $urlsam;
	public $muni_name;
	public $muni_domi;
	public $muni_tel;
	public $muni_mail;
	public $muni_skype;
	public $muni_cuit;
	public $logo;
	public $logo_grande;
	public $logo_talon;
	public $logo_sup;
    public $adminemail;
	public $sis_id;
	public $sis_name;
	public $sis_file;
	public $sis_url;
	public $version;
	
	function __construct(){
		if (Yii::$app->user->isGuest) Yii::$app->session['sis_id'] = 0;
				
		$this->name = "Sistema de Administraci&oacute;n Municipal";
		$this->urlsam = "/sam/index.php?r=";
		$this->muni_name = utb::samMuni()['nombre'];
		$this->muni_domi = utb::samMuni()['domi'];
		$this->muni_tel = utb::samMuni()['tel'];
		$this->muni_mail = utb::samMuni()['mail'];
		$this->muni_cuit = utb::samMuni()['cuit']; 
		// $this->muni_skype = utb::samMuni()['skype'];
		// $this->logo = BaseUrl::toRoute(['config/muni/imagen', 'logo' => 1]);
		// $this->logo_grande = BaseUrl::toRoute(['config/muni/imagen', 'logo' => 2]);
		// $this->logo_talon = BaseUrl::toRoute(['config/muni/imagen', 'logo' => 3]);
		if (Yii::$app->user->isGuest)
			$this->logo = '/sam/images/muni_generico.jpg';
		else 	
			$this->logo = '/sam/images/' . Yii::$app->db->muni . '/logo_muni.png';
		$this->logo_grande = '/var/www/html/sam/images/' . Yii::$app->db->muni . '/logo_muni_grande.jpg';
		$this->logo_talon = '/var/www/html/sam/images/' . Yii::$app->db->muni . '/logo_muni_chico.jpg';
		$this->logo_sup = '/var/www/html/sam/images/' . Yii::$app->db->muni . '/logo_muni_grande.jpg';
		$this->adminemail = 'sistemas@aari.com.ar';
		$this->sis_id = isset(Yii::$app->session['sis_id']) ? Yii::$app->session['sis_id'] : 0;
		$this->sis_name = utb::getCampo('sam.sis_sistema','sis_id='.$this->sis_id);
		if ($this->sis_name == '') $this->sis_name = $this->name;
		switch ($this->sis_id) {
			case 1:
				$this->sis_file = "samseg";
				$this->sis_url = "/samseg/index.php?r=";
				break;
			case 3:
				$this->sis_file = "samtrib";
				$this->sis_url = "/samtrib/index.php?r=";
				break;
			case 4:
				$this->sis_file = "samfin";
				$this->sis_url = "/samfin/index.php?r=";
				break;
			case 6:
				$this->sis_file = "samrh";
				$this->sis_url = "/samrh/index.php?r=";
				break;
			case 7:
				$this->sis_file = "samserweb";
				$this->sis_url = "/samserweb/index.php?r=";
				break;
			case 8:
				$this->sis_file = "samrec";
				$this->sis_url = "/samrec/index.php?r=";
				break;
			default:
				$this->sis_file = "sam";
		}
		//$this->version = utb::getCampo('sam.version','sis_id='.$this->sis_id,'max(version)');
		$this->version = utb::getCampo('sam.version','sis_id=10','max(version)');
	}
	
}
 ?>
