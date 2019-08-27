<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Role;
use Quid\Core;

// admin
class Admin extends Core\RoleAlias
{
	// config
	public static $config = [
		'permission'=>80,
		'can'=>[
			'home'=>[
				'infoPopup'=>true],
			'login'=>['app'=>false,'cms'=>true]],
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
				'add'=>false,
				'truncate'=>true],
			'session'=>[
				'add'=>false,
				'truncate'=>true],
			'log'=>[
				'add'=>false,
				'truncate'=>true,
				'empty'=>true],
			'logEmail'=>[
				'add'=>false,
				'truncate'=>true,
				'empty'=>true],
			'logCron'=>[
				'add'=>false,
				'truncate'=>true,
				'empty'=>true],
			'logError'=>[
				'add'=>false,
				'truncate'=>true,
				'empty'=>true],
			'logHttp'=>[
				'add'=>false,
				'truncate'=>true,
				'empty'=>true],
			'logSql'=>[
				'add'=>false,
				'truncate'=>true,
				'empty'=>true]
		]
	];


	// isAdmin
	// retourne vrai comme c'est admin
	public static function isAdmin():bool
	{
		return true;
	}
}

// config
Admin::__config();
?>