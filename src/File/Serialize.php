<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;

// serialize
class Serialize extends TextAlias
{
	// config
	public static $config = [
		'group'=>null,
		'type'=>'serialize'
	];
}

// config
Serialize::__config();
?>