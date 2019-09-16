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

// com
// class for testing Quid\Core\Com
class Com extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        // prepare
        $com = new Core\Com();

        // lang
        assert(!empty($com->neutralPrepend('Row #1',['replace'=>'ok'],'#id',['pos','okidou'])->output()));

        return true;
    }
}
?>