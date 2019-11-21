<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
 */

namespace Quid\Test\Core;
use Quid\Base;
use Quid\Core;

// cols
// class for testing Quid\Core\Cols
class Cols extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare
        $db = Core\Db::inst();
        $table = 'ormCols';
        $tb = $db[$table];
        $cols = $tb->cols();

        // tableFromFqcn

        // orm
        assert($db['session']->cols()->get(Core\Col\DateAdd::class) instanceof Core\Col\DateAdd);

        return true;
    }
}
?>