<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Role;
use Quid\Core;

// shared
class Shared extends Core\RoleAlias
{
	// config
	public static $config = [
		'ignore'=>true,
		'permission'=>10,
		'can'=>[
			'login'=>['app'=>true,'cms'=>false]]
	];
}

// config
Shared::__config();
?>