<?php
declare(strict_types=1);
namespace Quid\Core\Role;
use Quid\Core;

// cron
class Cron extends Core\RoleAlias
{
	// config
	public static $config = array(
		'ignore'=>true,
		'permission'=>90,
		'can'=>array(
			'login'=>array('app'=>false,'cms'=>false)),
		'db'=>array(
			'*'=>array(
				'insert'=>true,
				'update'=>true,
				'delete'=>true,
				'create'=>true,
				'alter'=>true,
				'truncate'=>true,
				'drop'=>true))
	);
}

// config
Cron::__config();
?>