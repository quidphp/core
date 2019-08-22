<?php
declare(strict_types=1);
namespace Quid\Core\Role;
use Quid\Core;

// contributor
class Contributor extends Core\RoleAlias
{
	// config
	public static $config = array(
		'ignore'=>true,
		'permission'=>50,
		'can'=>array(
			'login'=>array('app'=>true,'cms'=>true)),
		'db'=>array(
			'*'=>array(
				'rows'=>false,
				'in'=>false,
				'notIn'=>false,
				'insert'=>true,
				'update'=>true,
				'delete'=>true),
			'user'=>array(
				'add'=>false),
			'session'=>array(
				'view'=>false),
			'lang'=>array(
				'view'=>false),
			'redirection'=>array(
				'view'=>false),
			'email'=>array(
				'view'=>false),
			'option'=>array(
				'view'=>false),
			'queueEmail'=>array(
				'view'=>false),
			'log'=>array(
				'view'=>false),
			'logEmail'=>array(
				'view'=>false),
			'logCron'=>array(
				'view'=>false),
			'logError'=>array(
				'view'=>false),
			'logHttp'=>array(
				'view'=>false),
			'logSql'=>array(
				'view'=>false)
		)
	);
}

// config
Contributor::__config();
?>