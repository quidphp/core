<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Role;
use Quid\Core;

// cron
class Cron extends Core\RoleAlias
{
	// config
	public static $config = [
		'ignore'=>true,
		'permission'=>90,
		'can'=>[
			'login'=>['app'=>false,'cms'=>false]],
		'db'=>[
			'*'=>[
				'insert'=>true,
				'update'=>true,
				'delete'=>true,
				'create'=>true,
				'alter'=>true,
				'truncate'=>true,
				'drop'=>true]]
	];
}

// config
Cron::__config();
?>