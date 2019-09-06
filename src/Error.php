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

// error
// extended class used as an error handler
class Error extends Main\Error
{
	// config
	public static $config = [
		'option'=>[ // tableau d'options
			'log'=>[Row\LogError::class,File\Error::class]], // classe pour log, , s'il y a en plusieurs utilise seulement le premier qui fonctionne
		'type'=>[ // description des types additionneles à boot
			33=>['key'=>'dbException','name'=>'Database Exception'],
			34=>['key'=>'routeException','name'=>'Route Exception'],
			35=>['key'=>'routeBreakException','name'=>'Route Break Exception']]
	];


	// init
	// initialise la prise en charge des erreurs, exception et assertion
	public static function init():void
	{
		parent::init();
		Base\Error::setHandler([static::class,'handler']);
		Base\Exception::setHandler([static::class,'exception']);
		Base\Assert::setHandler([static::class,'assert']);

		return;
	}
}

// config
Error::__config();
?>