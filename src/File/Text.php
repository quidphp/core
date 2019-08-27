<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Core;

// text
abstract class Text extends Core\FileAlias
{
	// config
	public static $config = [
		'group'=>'text'
	];
}

// config
Text::__config();
?>