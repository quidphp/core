<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Test\Core;
use Quid\Base;

// response
// class for testing Quid\Core\Response
class Response extends Base\Test
{
    // trigger
    public static function trigger(array $data):bool
    {
        return true;
    }
}
?>