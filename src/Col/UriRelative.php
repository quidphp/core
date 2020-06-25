<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// uriRelative
// class for a column managing a relative uri
class UriRelative extends Core\ColAlias
{
    // config
    protected static array $config = [
        'validate'=>['uriRelative'],
        'check'=>['kind'=>'char']
    ];
}

// init
UriRelative::__init();
?>