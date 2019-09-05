<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\TestSuite;
use Quid\Core;
use Quid\Base;

// db
// class for testing Quid\Core\Db
class Db extends Base\Test
{
	// trigger
	public static function trigger(array $data):bool
	{
		// prepare
		$db = Core\Db::inst();
		$table = 'ormDb';
		$tb = $db[$table];
		$tb->cols();
		$db['ormCol']->cols();

		// orm
		assert($tb->classe()->rows() === TestSuite\Rows\OrmDb::class);
		assert($db['session']->classe()->row() === Core\Row\Session::class);
		assert($db['ormCell']->classe()->row() === TestSuite\Row\OrmCell::class);
		assert($tb->classe()->table() === TestSuite\Table\OrmDb::class);
		assert($tb->classe()->col('id') === Core\Col\Primary::class);
		assert($db['ormCell']->classe()->col($db['ormCell']['active']) === Core\Col\Active::class);
		assert($tb->classe()->col('dateAdd') === Core\Col\DateAdd::class);
		assert($db['ormCol']->classe()->col('myRelation') === Core\Col\Enum::class);
		assert($db['ormCol']->classe()->col('user_id') === Core\Col\Enum::class);
		assert($db['ormCol']->classe()->col('user_ids') === TestSuite\Col\UserIds::class);
		assert($tb->classe()->cell($tb['id']) === Core\Cell\Primary::class);
		assert($db['user']->classe()->cell('role') === Core\Cell\Enum::class);
		assert($db['ormCol']->classe()->cell('myRelation') === Core\Cell\Enum::class);
		assert($db['ormCol']->classe()->cell('user_id') === Core\Cell\Enum::class);
		assert($db['ormCol']->classe()->cell('user_ids') === Core\Cell\Set::class);
		assert($db->colAttr('active') === ['class'=>Core\Col\Active::class,'general'=>true,'label'=>['col','label','*','active'],'priority'=>35]);

		return true;
	}
}
?>