<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Core;
use Quid\Base;

// tables
class Tables extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$tables = Core\Boot::inst()->db()->tables();

		// keyClassExtends
		assert($tables::keyClassExtends() === array(Core\Row::class,Core\Table::class,Core\Rows::class,Core\Cells::class,Core\Cols::class));

		// orm
		assert($tables instanceof Core\Tables);
		assert($tables->get(Core\Table::class) === null);
		assert($tables->get(Table::class) === null);
		
		return true;
	}
}
?>