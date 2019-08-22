<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;
use Quid\Base;

// userPasswordReset
class UserPasswordReset extends Core\ColAlias
{
	// config
	public static $config = array(
		'complex'=>'div',
		'cell'=>Core\Cell\UserPasswordReset::class,
		'search'=>false,
		'visible'=>array(
			'validate'=>'notEmpty',
			'role'=>array('>='=>70)),
		'visibleGeneral'=>false,
		'onComplex'=>true,
		'export'=>false,
		'check'=>array('kind'=>'char'),
		'security'=>null // custom, défini le niveau de sécurité du mot de passe utilisé, support pour loose
	);
	
	
	// onGet
	// retourne une string sha1 du hash
	public function onGet($return,array $option)
	{
		$return = $this->value($return);
		
		if(is_string($return) && !empty($return))
		$return = Base\Crypt::passwordActivate($return,1);
		
		return $return;
	}
	
	
	// getSecurity
	// retourne le niveau de sécurité du mot de passe
	public function getSecurity():?string 
	{
		return $this->attr('security');
	}
}

// config
UserPasswordReset::__config();
?>