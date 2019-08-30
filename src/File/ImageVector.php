<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;

// imageVector
// class for a vector image file (like svg)
class ImageVector extends ImageAlias
{
	// config
	public static $config = [
		'group'=>'imageVector'
	];
}

// config
ImageVector::__config();
?>