<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Core;
use Quid\Base;

// cells
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