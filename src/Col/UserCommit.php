<?php
declare(strict_types=1);
namespace Quid\Core\Col;
use Quid\Core;

// userCommit
class UserCommit extends EnumAlias
{
	// config
	public static $config = array(
		'required'=>true,
		'complex'=>'div',
		'visible'=>array('validate'=>'notEmpty'),
		'relation'=>'user',
		'duplicate'=>false,
		'check'=>array('kind'=>'int')
	);
	
	
	// onCommit
	// donne le user courant lors d'un insert ou un update
	// il faut vérifier que boot hasSession car la row session à un champ userCommit
	public function onCommit($value,array $row,?Core\Cell $cell=null,array $option)
	{
		$return = 1;
		$boot = static::bootReady();
		
		if(!empty($boot) && $boot->hasSession())
		$return = $boot->session()->user();
		
		return $return;
	}
}

// config
UserCommit::__config();
?>