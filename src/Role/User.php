<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Role;
use Quid\Core;

// user
// class that contains the default configuration for the user role
class User extends Core\RoleAlias
{
	// config
	public static $config = [
		'permission'=>20,
		'can'=>[
			'login'=>['app'=>true]]
	];
}

// config
User::__config();
?>