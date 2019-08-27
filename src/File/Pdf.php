<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;

// pdf
class Pdf extends BinaryAlias
{
	// config
	public static $config = [
		'group'=>'pdf'
	];
}

// config
Pdf::__config();
?>