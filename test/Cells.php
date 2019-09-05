<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/test/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Core;
use Quid\Base;

// cells
// class for testing Quid\Core\Cells
class Cells extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare

		// getOverloadKeyPrepend

		// tableFromFqcn

		// keyClassExtends

		// orm
		assert(Core\Boot::inst()->session()->storage()->cell(Core\Col\DateAdd::class)->name() === 'dateAdd');

		return true;
	}
}
?>