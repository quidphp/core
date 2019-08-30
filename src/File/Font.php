<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;

// font
// class for a font file (like ttf)
class Font extends BinaryAlias
{
	// config
	public static $config = [
		'group'=>'font'
	];
}

// config
Font::__config();
?>