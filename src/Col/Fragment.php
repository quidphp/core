<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;

// fragment
class Fragment extends SlugAlias
{
	// config
	public static $config = [
		'unique'=>false,
		'validate'=>[1=>'fragment']
	];
}

// config
Fragment::__config();
?>