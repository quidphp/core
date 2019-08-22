<?php
declare(strict_types=1);
namespace Quid\Core\Role;
use Quid\Core;

// admin
class Admin extends Core\RoleAlias
{
	// config
	public static $config = array(
		'permission'=>80,
		'can'=>array(
			'home'=>array(
				'infoPopup'=>true),
			'login'=>array('app'=>false,'cms'=>true)),
		'db'=>array(
			'*'=>array(
				'insert'=>true,
				'update'=>true,
				'delete'=>true,
				'create'=>true,
				'alter'=>true,
				'truncate'=>false,
				'drop'=>true,
				'infoPopup'=>true,
				'mediaRegenerate'=>true,
				'colPopup'=>array(
					'name','required','unique','editable','pattern','preValidate','validate','compare','type','length','unsigned',
					'default','acceptsNull','collation','orderable','filterable','searchable','priority','classCol','classCell')),
			'user'=>array(
				'export'=>true,
				'userWelcome'=>true),
			'lang'=>array(
				'export'=>true),
			'redirection'=>array(
				'export'=>true),
			'queueEmail'=>array(
				'add'=>false,
				'truncate'=>true),
			'session'=>array(
				'add'=>false,
				'truncate'=>true),
			'log'=>array(
				'add'=>false,
				'truncate'=>true,
				'empty'=>true),
			'logEmail'=>array(
				'add'=>false,
				'truncate'=>true,
				'empty'=>true),
			'logCron'=>array(
				'add'=>false,
				'truncate'=>true,
				'empty'=>true),
			'logError'=>array(
				'add'=>false,
				'truncate'=>true,
				'empty'=>true),
			'logHttp'=>array(
				'add'=>false,
				'truncate'=>true,
				'empty'=>true),
			'logSql'=>array(
				'add'=>false,
				'truncate'=>true,
				'empty'=>true)
		)
	);
	
	
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