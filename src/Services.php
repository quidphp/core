<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Main;

// services
// extended class for a collection containing many service objects
class Services extends Main\Services
{
	// trait
	use _fullAccess;


	// config
	public static $config = [];
}
?>