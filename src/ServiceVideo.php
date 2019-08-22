<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Main;
use Quid\Base;

// serviceVideo
abstract class ServiceVideo extends Main\ServiceVideo
{
	// trait
	use _fullAccess;
	
	
	// config
	public static $config = array();
	
	
	// getLangCode
	// retourne le code courant de la langue
	public static function getLangCode():string 
	{
		return static::lang()->currentLang();
	}
	
	
	// getOverloadKeyPrepend
	// retourne le prepend de la clé à utiliser pour le tableau overload
	public static function getOverloadKeyPrepend():?string 
	{
		return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Service':null;
	}
}

// config
ServiceVideo::__config();
?>