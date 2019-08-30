<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;

// userAdd
// class for the userAdd column, user_id is added automatically on insert
class UserAdd extends EnumAlias
{
	// config
	public static $config = [
		'required'=>false,
		'general'=>true,
		'complex'=>'div',
		'visible'=>['validate'=>'notEmpty'],
		'relation'=>'user',
		'duplicate'=>false,
		'editable'=>false,
		'check'=>['kind'=>'int']
	];


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