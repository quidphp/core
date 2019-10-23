<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Base;
use Quid\Core;
use Quid\Suite;

// rows
// class for testing Quid\Core\Rows
class Rows extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $db = Core\Boot::inst()->db();
        $table = 'ormDb';
        assert($db->truncate($table) instanceof \PDOStatement);
        $tb = $db->table($table);
        $rows = $tb->rows();

        // tableFromFqcn
        assert($rows::tableFromFqcn() instanceof Suite\Table\OrmDb);

        // orm
        assert($db->truncate($table) instanceof \PDOStatement);

        return true;
    }
}
?>