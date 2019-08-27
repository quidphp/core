<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Role;
use Quid\Core;

// nobody
class Nobody extends Core\RoleAlias
{
	// config
	public static $config = [
		'permission'=>1,
		'db'=>[
			'*'=>[
				'view'=>false],
			'contact'=>[
				'insert'=>true]
		]
	];
}

// config
Nobody::__config();
?>