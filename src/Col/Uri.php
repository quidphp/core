<?php
declare(strict_types=1);

/*
 * This file is part of the QuidPHP package <https://quidphp.com>
 * Author: Pierre-Philippe Emond <emondpph@gmail.com>
 * License: https://github.com/quidphp/core/blob/master/LICENSE
 */

namespace Quid\Core\Col;
use Quid\Core;

// uri
// class for a column managing an uri (relative or absolute)
class Uri extends Core\ColAlias
{
    // config
    protected static array $config = [
        'validate'=>['uri'],
        'keyboard'=>'url',
        'check'=>['kind'=>'char']
    ];
}

// init
Uri::__init();
?>