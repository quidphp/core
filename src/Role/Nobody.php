<?php
declare(strict_types=1);
namespace Quid\Core\Role;
use Quid\Core;

// nobody
class Nobody extends Core\RoleAlias
{
	// config
	public static $config = array(
		'permission'=>1,
		'db'=>array(
			'*'=>array(
				'view'=>false),
			'contact'=>array(
				'insert'=>true)
		)
	);
}

// config
Nobody::__config();
?>