<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Cms;

// _module
// trait that provides some initial configuration for a CMS module route
trait _module
{
	// config
	public static $configModule = [
		'match'=>[
			'role'=>'admin'],
		'response'=>[
			'timeLimit'=>30],
		'group'=>'cms/module',
		'sitemap'=>false,
		'navigation'=>false,
		'ignore'=>false
	];
}
?>