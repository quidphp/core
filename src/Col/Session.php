<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;

// session
class Session extends EnumAlias
{
	// config
	public static $config = array(
		'visible'=>array('validate'=>'notEmpty'),
		'required'=>false,
		'complex'=>'div',
		'inRelation'=>false // custom, n'a pas besoin d'être dans la relation
	);
	
	
	// onCommit
	// retourne le id de la session sur insertion ou sur update
	// note: retourne null si le storage de session n'est pas une row de base de données
	public function onCommit($value,array $row,?Core\Cell $cell=null,array $option):?Core\Row
	{
		$return = null;
		$boot = static::bootReady();
		
		if(!empty($boot))
		{
			$session = $boot->session();
			$storage = $session->storage();
			
			if($storage instanceof Core\Row)
			$return = $storage;
		}
		
		return $return;
	}
}

// config
Session::__config();
?>