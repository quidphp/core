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
// class that contains the default configuration for the shared role 
class Shared extends Core\RoleAlias
{
	// config
	public static $config = [
		'permission'=>10,
		'can'=>[
			'login'=>['app'=>true]]
	];
}

// config
Shared::__config();
?>