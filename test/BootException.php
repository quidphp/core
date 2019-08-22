<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Core;
use Quid\Main;
use Quid\Base;

// bootException
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