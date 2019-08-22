<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Test;
use Quid\Core;
use Quid\Base;

// rows
class Rows extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$db = Core\Boot::inst()->db();
		$table = "ormDb";
		assert($db->truncate($table) instanceof \PDOStatement);
		$tb = $db->table($table);
		$rows = $tb->rows();

		// getOverloadKeyPrepend

		// tableFromFqcn
		assert($rows::tableFromFqcn() instanceof Test\Table\OrmDb);

		// orm
		assert($db->truncate($table) instanceof \PDOStatement);
		
		return true;
	}
}
?>