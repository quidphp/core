<?php
declare(strict_types=1);
namespace Quid\Core\Test;
use Quid\Test;
use Quid\Core;
use Quid\Base;

// db
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
		assert($tb->classe()->rows() === Test\Rows\OrmDb::class);
		assert($db['session']->classe()->row() === Core\Row\Session::class);
		assert($db['ormCell']->classe()->row() === Test\Row\OrmCell::class);
		assert($tb->classe()->table() === Test\Table\OrmDb::class);
		assert($tb->classe()->col('id') === Core\Col\Primary::class);
		assert($db['ormCell']->classe()->col($db['ormCell']['active']) === Core\Col\Active::class);
		assert($tb->classe()->col('dateAdd') === Core\Col\DateAdd::class);
		assert($db['ormCol']->classe()->col('myRelation') === Core\Col\Enum::class);
		assert($db['ormCol']->classe()->col('user_id') === Core\Col\Enum::class);
		assert($db['ormCol']->classe()->col('user_ids') === Test\Col\UserIds::class);
		assert($tb->classe()->cell($tb['id']) === Core\Cell\Primary::class);
		assert($db['user']->classe()->cell('role') === Core\Cell\Enum::class);
		assert($db['ormCol']->classe()->cell('myRelation') === Core\Cell\Enum::class);
		assert($db['ormCol']->classe()->cell('user_id') === Core\Cell\Enum::class);
		assert($db['ormCol']->classe()->cell('user_ids') === Core\Cell\Set::class);
		assert($db->colAttr('active') === array('class'=>Core\Col\Active::class,'general'=>true,'label'=>array('col','label','*','active'),'priority'=>35));
		
		return true;
	}
}
?>