<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Core;
use Quid\Base;

// cols
class Cols extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$db = Core\Db::inst();
		$table = 'ormCols';
		$tb = $db[$table];
		$cols = $tb->cols();

		// getOverloadKeyPrepend
		assert($cols::getOverloadKeyPrepend() === null);

		// tableFromFqcn

		// keyClassExtends

		// orm
		assert($db['session']->cols()->get(Core\Col\DateAdd::class) instanceof Core\Col\DateAdd);
		assert(strlen($cols->formComplex()['date']) === 260);
		
		return true;
	}
}
?>