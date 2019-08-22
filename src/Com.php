<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Main;

// com
class Com extends Main\Com
{
	// trait
	use _bootAccess;
	
	
	// config
	public static $config = array();
	
	
	// lang
	// retourne l'objet lang, peut utiliser celui dans inst
	// envoie une exception si introuvable
	// méthode protégé
	protected function lang(?Main\Lang $return=null):Main\Lang
	{
		if($return === null)
		{
			$boot = static::bootReady();
			if(!empty($boot))
			$return = $boot->lang();
		}
		
		return parent::lang($return);
	}
}

// config
Com::__config();
?>