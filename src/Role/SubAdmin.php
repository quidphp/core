<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Role;
use Quid\Core;

// subAdmin
// class that contains the default configuration for the subAdmin role (disabled per default)
class SubAdmin extends Core\RoleAlias
{
	// config
	public static $config = [
		'ignore'=>true,
		'permission'=>70,
		'can'=>[
			'home'=>[
				'infoPopup'=>true],
			'login'=>['app'=>true,'cms'=>true]],
		'db'=>[
			'*'=>[
				'insert'=>true,
				'update'=>true,
				'delete'=>true,
				'create'=>true,
				'alter'=>true,
				'truncate'=>false,
				'drop'=>true,
				'infoPopup'=>true,
				'mediaRegenerate'=>true,
				'colPopup'=>[
					'name','required','unique','editable','pattern','preValidate','validate','compare','type','length','unsigned',
					'default','acceptsNull','collation','orderable','filterable','searchable','priority','classCol','classCell']],
			'user'=>[
				'export'=>true,
				'userWelcome'=>true],
			'lang'=>[
				'export'=>true],
			'redirection'=>[
				'export'=>true],
			'queueEmail'=>[
				'add'=>false],
			'session'=>[
				'add'=>false],
			'log'=>[
				'add'=>false],
			'logEmail'=>[
				'add'=>false],
			'logCron'=>[
				'add'=>false],
			'logError'=>[
				'add'=>false],
			'logHttp'=>[
				'add'=>false],
			'logSql'=>[
				'add'=>false]]
	];
}

// config
SubAdmin::__config();
?>