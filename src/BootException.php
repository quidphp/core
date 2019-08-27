<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Main;

// bootException
class BootException extends Main\Exception
{
	// config
	public static $config = [
		'code'=>35 // code de l'exception
	];
}

// config
BootException::__config();
?>