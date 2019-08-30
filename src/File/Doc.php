<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;

// doc
// class for a doc file, like microsoft word
class Doc extends TextAlias
{
	// config
	public static $config = [
		'group'=>'doc'
	];
}

// config
Doc::__config();
?>