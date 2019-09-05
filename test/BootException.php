<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/test/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// bootException
// class for testing Quid\Core\BootException
class BootException extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// construct
		$e = new Core\BootException('well');

		// exception
		assert(!$e instanceof Main\Contract\Catchable);
		assert($e->getCode() === 35);

		return true;
	}
}
?>