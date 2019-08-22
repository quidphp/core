<?php
declare(strict_types=1);
namespace Quid\Core\Role;
use Quid\Core;

// shared
class Shared extends Core\RoleAlias
{
	// config
	public static $config = array(
		'ignore'=>true,
		'permission'=>10,
		'can'=>array(
			'login'=>array('app'=>true,'cms'=>false))
	);
}

// config
Shared::__config();
?>