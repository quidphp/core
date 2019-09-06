<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Routing;

// nav
// extended class for storing route navigation-related data
class Nav extends Routing\Nav
{
	// trait
	use _bootAccess;


	// config
	public static $config = [];
}
?>