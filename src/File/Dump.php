<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;

// dump
// class for file which contains an exported value (similar to var_export)
class Dump extends HtmlAlias
{
	// config
	public static $config = [
		'type'=>'dump'
	];
}

// config
Dump::__config();
?>