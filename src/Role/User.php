<?php
declare(strict_types=1);
namespace Quid\Core\Role;
use Quid\Core;

// user
class User extends Core\RoleAlias
{
	// config
	public static $config = [
		'ignore'=>true,
		'permission'=>20,
		'can'=>[
			'login'=>['app'=>true,'cms'=>false]]
	];
}

// config
User::__config();
?>