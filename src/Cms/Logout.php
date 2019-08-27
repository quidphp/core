<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;
use Quid\Core;

// logout
class Logout extends Core\Route\Logout
{
	// trait
	use _common;


	// config
	public static $config = [
		'match'=>[
			'role'=>['>='=>20]],
		'parent'=>Login::class
	];
}

// config
Logout::__config();
?>