<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Main;
use Quid\Base;

// widget
abstract class Widget extends Main\Widget
{
	// trait
	use _fullAccess;
	
	
	// config
	public static $config = array();
	
	
	// getOverloadKeyPrepend
	// retourne le prepend de la clé à utiliser pour le tableau overload
	public static function getOverloadKeyPrepend():?string 
	{
		return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Widget':null;
	}
}
?>