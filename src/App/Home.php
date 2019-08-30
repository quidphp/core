<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\App;
use Quid\Core;

// home
// abstract class for the home route of the application
abstract class Home extends Core\Route\Home
{
	// config
	public static $config = [];
}

// config
Home::__config();
?>