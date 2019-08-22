<?php
declare(strict_types=1);
namespace Quid\Core\Col;

// userAdd
class UserAdd extends EnumAlias
{
	// config
	public static $config = array(
		'required'=>false,
		'general'=>true,
		'complex'=>'div',
		'visible'=>array('validate'=>'notEmpty'),
		'relation'=>'user',
		'duplicate'=>false,
		'editable'=>false,
		'check'=>array('kind'=>'int')
	);
	
	
	// onInsert
	// donne le user courant lors d'un insert
	// il faut vérifier que boot hasSession car la row session à un champ userAdd
	public function onInsert($value,array $row,array $option)
	{
		$return = 1;
		$boot = static::bootReady();
		
		if(!empty($boot) && $boot->hasSession())
		$return = $boot->session()->user();
		
		return $return;
	}
}

// config
UserAdd::__config();
?>