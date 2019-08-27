<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\File;

// xml
class Xml extends TextAlias
{
	// config
	public static $config = [
		'group'=>'xml'
	];
}

// config
Xml::__config();
?>