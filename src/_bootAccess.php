<?php
declare(strict_types=1);
namespace Quid\Core;
use Quid\Routing;

// _bootAccess
trait _bootAccess
{
	// boot
	// retourne l'objet boot
	public static function boot():Boot 
	{
		return Boot::inst();
	}
	
	
	// bootSafe
	// retourne l'objet boot si existant, n'envoie pas d'exception
	public static function bootSafe():?Boot 
	{
		return Boot::instSafe();
	}
	
	
	// bootReady
	// retourne l'objet boot si prêt, n'envoie pas d'exception
	public static function bootReady():?Boot 
	{
		return Boot::instReady();
	}
	
	
	// bootException
	// lance une exception de boot
	// ceci stop boot
	public static function bootException(?array $option=null,...$values):void 
	{
		$class = BootException::getOverloadClass();
		static::throwCommon($class,$values,$option);
		
		return;
	}
	
	
	// routeException
	// lance une exception de route
	// ceci force boot à passer à la prochaine route
	public static function routeException(?array $option=null,...$values):void 
	{
		static::throwCommon(Routing\Exception::class,$values,$option);
		
		return;
	}
}
?>