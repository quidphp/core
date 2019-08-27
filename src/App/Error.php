<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\App;
use Quid\Core;

// error
abstract class Error extends Core\Route\Error
{
	// config
	public static $config = [];
}

// config
Error::__config();
?>