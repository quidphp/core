<?php
declare(strict_types=1);
namespace Quid\Core\Role;
use Quid\Core;

// user
class User extends Core\RoleAlias
{
	// config
	public static $config = array(
		'ignore'=>true,
		'permission'=>20,
		'can'=>array(
			'login'=>array('app'=>true,'cms'=>false))
	);
}

// config
User::__config();
?>