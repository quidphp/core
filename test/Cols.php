<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Core;
use Quid\Base;

// cols
// class for testing Quid\Core\Cols
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

        return true;
    }
}
?>