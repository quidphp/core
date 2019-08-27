<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// userCommit
class UserCommit extends EnumAlias
{
	// config
	public static $config = [
		'required'=>true,
		'complex'=>'div',
		'visible'=>['validate'=>'notEmpty'],
		'relation'=>'user',
		'duplicate'=>false,
		'check'=>['kind'=>'int']
	];


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