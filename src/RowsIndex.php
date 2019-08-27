<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core;
use Quid\Orm;

// rowsIndex
class RowsIndex extends Orm\RowsIndex
{
	// trait
	use _accessAlias;


	// config
	public static $config = [];
}
?>