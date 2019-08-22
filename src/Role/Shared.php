<?php
declare(strict_types=1);
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