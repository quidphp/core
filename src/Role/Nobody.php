<?php
declare(strict_types=1);
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