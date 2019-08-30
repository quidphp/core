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

// serviceVideo
// extended abstract class with methods for a service that provides a video object after an HTTP request
abstract class ServiceVideo extends Main\ServiceVideo
{
	// trait
	use _fullAccess;


	// config
	public static $config = [];


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