<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Base;
use Quid\Core;
use Quid\Test\Suite;

// db
// class for testing Quid\Core\Db
class Db extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $db = Core\Db::inst();
        $table = 'ormDb';
        $tb = $db[$table];
        $tb->cols();
        $db['ormCol']->cols();

        // orm
        assert(is_int($db->selectCount(Core\Row\Session::class)));
        assert($tb->classe()->rows() === Suite\Rows\OrmDb::class);
        assert(is_a($db['session']->classe()->row(),Core\Row\Session::class,true));
        assert($db['ormCell']->classe()->row() === Suite\Row\OrmCell::class);
        assert($tb->classe()->table() === Suite\Table\OrmDb::class);
        assert(is_a($tb->classe()->col('id'),Core\Col\Primary::class,true));
        assert($db['ormCell']->classe()->col($db['ormCell']['active']) === Core\Col\Active::class);
        assert($tb->classe()->col('dateAdd') === Core\Col\DateAdd::class);
        assert(is_a($db['ormCol']->classe()->col('myRelation'),Core\Col\Enum::class,true));
        assert(is_a($db['ormCol']->classe()->col('user_id'),Core\Col\Enum::class,true));
        assert($db['ormCol']->classe()->col('user_ids') === Suite\Col\UserIds::class);
        assert(is_a($tb->classe()->cell($tb['id']),Core\Cell\Primary::class,true));
        assert(is_a($db['user']->classe()->cell('role'),Core\Cell\Set::class,true));
        assert($db['ormCol']->classe()->cell('myRelation') === Core\Cell\Enum::class);
        assert($db['ormCol']->classe()->cell('user_id') === Core\Cell\Enum::class);
        assert($db['ormCol']->classe()->cell('user_ids') === Core\Cell\Set::class);
        assert($db->colAttr('active') === ['class'=>Core\Col\Active::class,'general'=>true,'label'=>['col','label','*','active'],'priority'=>35]);

        return true;
    }
}
?>