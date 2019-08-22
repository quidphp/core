<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Main;
use Quid\Base;

// role
abstract class Role extends Main\Role
{
	// trait
	use _fullAccess;
	

	// config
	public static $config = array(
		'label'=>null, // label du rôle
		'description'=>null, // description du rôle
		'can'=>array( // permission générale
			'login'=>array('app'=>false,'cms'=>false)),
		'db'=>array( // permission pour db
			'*'=>array( // permission pour toutes les tables
				'access'=>true,
				'select'=>true,
				'show'=>true,
				'insert'=>false,
				'update'=>false,
				'delete'=>false,
				'create'=>false,
				'alter'=>false,
				'truncate'=>false,
				'drop'=>false),
			'user'=>array(
				'update'=>true),
			'session'=>array(
				'insert'=>true,
				'update'=>true,
				'delete'=>true),
			'log'=>array(
				'insert'=>true,
				'update'=>false,
				'delete'=>true),
			'queueEmail'=>array(
				'insert'=>true,
				'update'=>false,
				'delete'=>true),
			'logEmail'=>array(
				'insert'=>true,
				'update'=>false,
				'delete'=>true),
			'logCron'=>array(
				'insert'=>true,
				'update'=>false,
				'delete'=>true),
			'logError'=>array(
				'insert'=>true,
				'update'=>false,
				'delete'=>true),
			'logHttp'=>array(
				'insert'=>true,
				'update'=>false,
				'delete'=>true),
			'logSql'=>array(
				'insert'=>true,
				'update'=>false,
				'delete'=>true)),
		'@cms'=>array( // pour cms
			'can'=>array(
				'account'=>true,
				'accountChangePassword'=>true,
				'logout'=>true,
				'footerTypes'=>true,
				'footerTypesCms'=>false,
				'footerModules'=>true,
				'about'=>true,
				'home'=>array(
					'info'=>true,
					'infoPopup'=>false,
					'search'=>true)),
			'db'=>array(
				'*'=>array( // permission pour toutes les tables
					'view'=>true,
					'limit'=>true,
					'perPage'=>true,
					'search'=>true,
					'searchNote'=>true,
					'cols'=>true,
					'filter'=>true,
					'order'=>true,
					'direction'=>true,
					'where'=>true,
					'page'=>true,
					'rows'=>true,
					'action'=>true,
					'in'=>true,
					'notIn'=>true,
					'info'=>true,
					'infoPopup'=>false,
					'highlight'=>true,
					'panelDescription'=>true,
					'add'=>true,
					'modify'=>true,
					'remove'=>true,
					'multiDelete'=>true,
					'reset'=>true,
					'nav'=>true,
					'back'=>true,
					'truncate'=>true,
					'empty'=>false,
					'navAdd'=>true,
					'download'=>true,
					'export'=>false,
					'viewApp'=>true,
					'relationChilds'=>true,
					'specific'=>true,
					'specificOperation'=>true,
					'duplicate'=>false,
					'description'=>true,
					'mediaDelete'=>true,
					'mediaRegenerate'=>false,
					'relation'=>true,
					'generalRelation'=>true,
					'specificRelation'=>true,
					'tableRelation'=>true),
				'user'=>array(
					'userWelcome'=>false),
				'session'=>array(
					'remove'=>false,
					'modify'=>false)))
	);
	
	
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
		
		$return = static::can(array('login',$type));
		
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
		$return = array();
		
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
		$option = Base\Arr::plus($option,array('pattern'=>$pattern));
		
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
		$option = Base\Arr::plus($option,array('pattern'=>$pattern));
		
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