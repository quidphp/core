<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package.
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * Website: https://quidphp.com
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 * Readme: https://github.com/quidphp/core/blob/master/README.md
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