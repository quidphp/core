<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// uriAbsolute
// class for a column managing an absolute uri
class UriAbsolute extends Core\ColAlias
{
    // config
    protected static array $config = [
        'validate'=>['uriAbsolute'],
        'check'=>['kind'=>'char']
    ];
}

// init
UriAbsolute::__init();
?>