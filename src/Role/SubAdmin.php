<?php
declare(strict_types=1);
namespace Quid\Core\Role;
use Quid\Core;

// subAdmin
class SubAdmin extends Core\RoleAlias
{
	// config
	public static $config = array(
		'ignore'=>true,
		'permission'=>70,
		'can'=>array(
			'home'=>array(
				'infoPopup'=>true),
			'login'=>array('app'=>true,'cms'=>true)),
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
				'add'=>false),
			'session'=>array(
				'add'=>false),
			'log'=>array(
				'add'=>false),
			'logEmail'=>array(
				'add'=>false),
			'logCron'=>array(
				'add'=>false),
			'logError'=>array(
				'add'=>false),
			'logHttp'=>array(
				'add'=>false),
			'logSql'=>array(
				'add'=>false))
	);
}

// config
SubAdmin::__config();
?>