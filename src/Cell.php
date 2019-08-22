<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Orm;
use Quid\Base;

// cell
class Cell extends Orm\Cell
{
	// trait
	use _routeAttr, _accessAlias;
	
	
	// config
	public static $config = array();
	
	
	// getOverloadKeyPrepend
	// retourne le prepend de la clé à utiliser pour le tableau overload
	public static function getOverloadKeyPrepend():?string 
	{
		return (static::class !== self::class && !Base\Fqcn::sameName(static::class,self::class))? 'Cell':null;
	}
}

// config
Cell::__config();
?>