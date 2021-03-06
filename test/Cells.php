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

// cells
// class for testing Quid\Core\Cells
class Cells extends Base\Test
{
    // trigger
    final public static function trigger(array $data):bool
    {
        // prepare

        // tableFromFqcn

        // orm
        assert(Core\Boot::inst()->session()->storage()->cell(Core\Col\DateAdd::class)->name() === 'dateAdd');

        return true;
    }
}
?>