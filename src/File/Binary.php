<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;
use Quid\Core;

// binary
abstract class Binary extends Core\FileAlias
{
	// config
	public static $config = [];
}

// config
Binary::__config();
?>