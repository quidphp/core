<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Main;
use Quid\Base;

// role
// extended abstract class that provides more advanced logic for a role
abstract class Role extends Main\Role
{
	// trait
	use _fullAccess;


	// config
	public static $config = [
		'label'=>null, // label du rôle
		'description'=>null, // description du rôle
		'can'=>[ // permission générale
			'login'=>[]],
		'db'=>[ // permission pour db
			'*'=>[ // permission pour toutes les tables
				'access'=>true,
				'select'=>true,
				'show'=>true,
				'insert'=>false,
				'update'=>false,
				'delete'=>false,
				'create'=>false,
				'alter'=>false,
				'truncate'=>false,
				'drop'=>false],
			'user'=>[
				'update'=>true],
			'session'=>[
				'insert'=>true,
				'update'=>true,
				'delete'=>true],
			'log'=>[
				'insert'=>true,
				'update'=>false,
				'delete'=>true],
			'queueEmail'=>[
				'insert'=>true,
				'update'=>false,
				'delete'=>true],
			'logEmail'=>[
				'insert'=>true,
				'update'=>false,
				'delete'=>true],
			'logCron'=>[
				'insert'=>true,
				'update'=>false,
				'delete'=>true],
			'logError'=>[
				'insert'=>true,
				'update'=>false,
				'delete'=>true],
			'logHttp'=>[
				'insert'=>true,
				'update'=>false,
				'delete'=>true],
			'logSql'=>[
				'insert'=>true,
				'update'=>false,
				'delete'=>true]]
	];


	// isAdmin
	// retourne vrai si la permission est admin
	public static function isAdmin():bool
	{
		return false;
	}


	// validateReplace
	// retourne un tableau de remplacement en utilisant roles
	// méthode protégé, utilisé par validate
	protected static function validateReplace():?array
	{
		return static::cacheStatic(__METHOD__,function() {
			$return = null;
			$roles = static::boot()->roles();

			if(!empty($roles))
			{
				foreach ($roles as $permission => $role)
				{
					$name = $role::name();
					$return[$name] = $permission;
				}
			}

			return $return;
		});
	}


	// canLogin
	// retourne vrai si le role permet le login dans le type
	public static function canLogin(?string $type=null):bool
	{
		$return = false;

		if($type === null)
		$type = static::boot()->type();

		$return = static::can(['login',$type]);

		return $return;
	}


	// canDb
	// retourne une permission en lien avec une table dans l'objet base de donnée
	// envoie une exception si le retour n'est pas booléean
	public static function canDb(string $action,$table=null):bool
	{
		$return = static::getDb($action,$table);

		if(!is_bool($return))
		static::throw('invalidReturn',$action);

		return $return;
	}


	// getDb
	// retourne une permission ou un tableau de données en lien avec une table dans l'objet base de donnée
	public static function getDb(string $action,$table=null)
	{
		$return = null;

		if(!empty(static::$config['db']['*']) && array_key_exists($action,static::$config['db']['*']))
		$return = static::$config['db']['*'][$action];

		if($table instanceof Table)
		$table = $table->name();

		if(is_string($table) && !empty(static::$config['db'][$table]) && array_key_exists($action,static::$config['db'][$table]))
		$return = static::$config['db'][$table][$action];

		return $return;
	}


	// table
	// retourne les permissions par défaut et pour une table
	// envoie une exception si l'argument table n'est pas utilisable
	public static function table($table=null):array
	{
		$return = [];

		if(!empty(static::$config['db']['*']) && is_array(static::$config['db']['*']))
		$return = static::$config['db']['*'];

		if(!empty($table))
		{
			if($table instanceof Table)
			$table = $table->name();

			if(is_string($table))
			{
				if(!empty(static::$config['db'][$table]) && is_array(static::$config['db'][$table]))
				$return = Base\Arr::replace($return,static::$config['db'][$table]);
			}

			else
			static::throw('invalidTable');
		}

		return $return;
	}


	// label
	// retourne le label du rôle
	// envoie une exception si lang/inst n'existe pas
	public static function label($pattern=null,?string $lang=null,?array $option=null):?string
	{
		$return = null;
		$obj = static::lang();
		$path = (!empty(static::$config['label']))? static::$config['label']:null;
		$option = Base\Arr::plus($option,['pattern'=>$pattern]);

		if(!empty($path))
		$return = $obj->same($path,null,$lang,$option);
		else
		$return = $obj->roleLabel(static::permission(),$lang,$option);

		return $return;
	}


	// description
	// retourne la description du rôle
	// envoie une exception si lang/inst n'existe pas
	public static function description($pattern=null,?array $replace=null,?string $lang=null,?array $option=null):?string
	{
		$return = null;
		$obj = static::lang();
		$path = (!empty(static::$config['description']))? static::$config['description']:null;
		$option = Base\Arr::plus($option,['pattern'=>$pattern]);

		if(!empty($path))
		$return = $obj->same($path,$replace,$lang,$option);
		else
		$return = $obj->roleDescription(static::permission(),$replace,$lang,$option);

		return $return;
	}


	// getOverloadKeyPrepend
	// retourne le prepend de la clé à utiliser pour le tableau overload
	public static function getOverloadKeyPrepend():?string
	{
		return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Role':null;
	}
}

// config
Role::__config();
?>