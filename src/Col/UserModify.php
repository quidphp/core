<?php
declare(strict_types=1);
namespace Quid\Core\Col;

// userModify
class UserModify extends EnumAlias
{
	// config
	public static $config = array(
		'complex'=>'div',
		'visible'=>array('validate'=>'notEmpty'),
		'relation'=>'user',
		'required'=>false,
		'duplicate'=>false,
		'check'=>array('kind'=>'int')
	);
	
	
	// onUpdate
	// donne le user courant lors d'un update
	public function onUpdate($cell,array $option)
	{
		$return = 1;
		$boot = static::bootReady();
		
		if(!empty($boot))
		$return = $boot->session()->user();
		
		return $return;
	}
}

// config
UserModify::__config();
?>